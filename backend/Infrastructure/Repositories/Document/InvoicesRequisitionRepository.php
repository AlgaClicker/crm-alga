<?php

namespace Infrastructure\Repositories\Document;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\GroupBy;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Domain\Contracts\Repository\Business\StockRepositoryContracts;
use Domain\Contracts\Repository\Crm\SpecificationRepositoryContracts;
use Domain\Contracts\Repository\Directory\MaterialRepositoryContract;
use Domain\Contracts\Repository\Document\MaterialRequisitionRepositoryContracts;
use Domain\Entities\Business\Master\Requisition;
use Domain\Entities\Business\Master\RequisitionMaterials;
use Domain\Entities\Business\Objects\Specification;
use Domain\Entities\Business\Objects\SpecificationMaterial;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Infrastructure\Repositories\AbstractRepository;
use Domain\Contracts\Repository\Document\ContractsRepositoryContract;

use Domain\Contracts\Repository\Services\FileRepositoryContracts;

use Domain\Contracts\Repository\Document\InvoicesRequisitionRepositoryContract;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Comparison;

use Domain\Entities\Business\Document\Requisition\Invoice;
use Domain\Entities\Business\Document\Requisition\Invoice\Material as InvoiceMaterial;

/**
 * CommentsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class InvoicesRequisitionRepository extends AbstractRepository implements InvoicesRequisitionRepositoryContract
{
    private FileRepositoryContracts $fileRepository;
    private MaterialRequisitionRepositoryContracts $requisitionRepository;
    private InvoiceMaterialsRequisitionRepository $invoiceMaterialsRequisitionRepository;
    private InvoiceMaterialsConfirmedRequisitionRepository $invoiceMaterialsConfirmedRequisitionRepository;
    private StockRepositoryContracts $stockRepository;
    private SpecificationRepositoryContracts $specificationRepository;
    private MaterialRepositoryContract $materialRepository;
    private ContractsRepositoryContract $repositoryContract;


    public function __construct(
        EntityManager $em,
        Invoice $entity,
        FileRepositoryContracts $fileRepository,
        MaterialRequisitionRepositoryContracts $requisitionRepository,
        InvoiceMaterialsRequisitionRepository $invoiceMaterialsRequisitionRepository,
        StockRepositoryContracts $stockRepository,
        SpecificationRepositoryContracts $specificationRepository,
        MaterialRepositoryContract $materialRepository,
        ContractsRepositoryContract $repositoryContract,
        InvoiceMaterialsConfirmedRequisitionRepository $invoiceMaterialsConfirmedRequisitionRepository
    ){
        $this->fileRepository = $fileRepository;
        $this->requisitionRepository = $requisitionRepository;
        $this->invoiceMaterialsRequisitionRepository = $invoiceMaterialsRequisitionRepository;
        $this->stockRepository = $stockRepository;
        $this->specificationRepository = $specificationRepository;
        $this->materialRepository =  $materialRepository;
        $this->repositoryContract = $repositoryContract;
        $this->invoiceMaterialsConfirmedRequisitionRepository = $invoiceMaterialsConfirmedRequisitionRepository;
        parent::__construct($em, $entity);
    }


    public function amountInvoiceRequisitionMaterial(RequisitionMaterials $materialRequisition) {
        return $this->invoiceMaterialsRequisitionRepository->amountPriceMaterialInvoices($materialRequisition);
    }

    public function checkAttributes($arrayKeyValue) {
        $newArrayKeyValue = [];
        foreach ($arrayKeyValue as $key=>$attribute) {
            if ($attribute && is_array($attribute)) {
                $newArrayKeyValue[$key] = $attribute;
            }

            if ($attribute && is_object($attribute)) {
                $newArrayKeyValue[$key] = $attribute;
            }

            if ($attribute && is_string($attribute)) {
                $property = $key;

                if (mb_eregi('_',$property)) {
                    $tx1 = substr($property,0,strpos($property,'_'));
                    $tx2 = ucfirst(substr($property,strpos($property,'_')+1,strlen($property)));
                    $property = $tx1.$tx2;
                }
                if (property_exists($this->entity,$property)) {
                    $newArrayKeyValue[$key] = $attribute;
                }
            }
        }
        return $newArrayKeyValue;
    }

    public function deleteRequisitionInvoice($requisition,$invoiceId) {
        $invoice = $this->findOne($invoiceId);
        $listMaterials =   $invoice->getMaterials();

        foreach ($listMaterials as $material) {
            $this->invoiceMaterialsRequisitionRepository->delete($material);
        }


        if ($invoice->getFixed() === true) {
            ///abort('400','Фиксированая запись удаление невозможно');
        }

        $invoice->setDeletedAt(new \DateTimeImmutable('now'));
        $this->save($invoice);
        //$this->em->persist($invoice);
        //$this->em->flush();
        $this->recalculationProcentRequisition($requisition);
        return true;


    }

    public function requisitionCalculation(Requisition $requisition,\DateTimeImmutable $date) {

        $requisition = $this->requisitionRepository->findMyCompnay($requisition);
        $this->invoiceRequisitionStatus($requisition);
        //$requisition->getMaterials()->clear();
        //dd($requisition);
        $result_calc['date_at'] = $date->format('Y-m-d h:i:s');
        $result_calc['requisition'] = $requisition->getId()->serialize();
        $result_calc['status'] = $requisition->getStatus();
        $requisition_materials = $requisition->getMaterials();

        foreach ($requisition_materials as $requisition_material) {
            $material['id'] = $requisition_material->getId()->serialize();

            //dd($this->invoiceMaterialsConfirmedRequisitionRepository->)
            //$material['materials'] = $requisition_material->getDirectoryMaterial();
            $quantity_count = $this->invoiceMaterialsRequisitionRepository->amountMaterialInvoices($requisition_material);
            $material['total']= $quantity_count ?? 0;
            $material['quantity']=$requisition_material->getQuantity();
            //dd($this->invoiceMaterialsRequisitionRepository->amountMaterialInvoices($requisition_material));
            //$material['amount'] = $this->amountInvoiceRequisitionMaterial($requisition_material);
            $result_calc['material'][] = $material;
        }

        return $result_calc;
    }




    public function newInvoiceRequisition(Requisition $requisition, $arrayKeyValue) {

        $requisition = $this->requisitionRepository->findMyCompnay($requisition);
        $arrayKeyValue = $this->checkAttributes($arrayKeyValue);
        $contract = $this->repositoryContract->findOne($arrayKeyValue['contract']);
        $invoice = $this->loadNew($arrayKeyValue);

        $invoice->setFixed(true);

        $invoice->setRequisition($requisition);
        $invoice->setContract($contract);
        $this->em->persist($invoice);
        $invoice = $this->generateNumber($invoice);

        if (array_key_exists('delivery_at',$arrayKeyValue)) {

            $invoice->setDeliveryAt(new \DateTimeImmutable($arrayKeyValue['delivery_at']));
        } else {
            abort('400','Нет даты поставики');
        }

        if (array_key_exists("files",$arrayKeyValue) && is_array($arrayKeyValue['files'])) {

            foreach ($arrayKeyValue['files'] as $file) {
                if (array_key_exists("hash", $file)) {
                    $file_obj= $this->fileRepository->getFileHash($file['hash']);
                    $invoice->addFile($file_obj);
                    $file_obj->addRequisitionInvoice($invoice);
                    $this->em->persist($file_obj);

                }
            }

        }

        if (array_key_exists('stock',$arrayKeyValue)) {
            $stock = $this->stockRepository->findMyCompnay($arrayKeyValue['stock']);
            if ($stock) {
                $invoice->setStock($stock);
            } else {
                $specification = $this->specificationRepository->findMyCompnay($requisition->getSpecification());
                if ($specification) {
                    $invoice->setStock($specification->getStock());
                } else {
                    abort('404','Склад не найден');
                }

            }

        } else {
            $specification = $this->specificationRepository->findMyCompnay($requisition->getSpecification());

            if (!$specification && array_key_exists('specification',$arrayKeyValue)) {
                $specification = $this->specificationRepository->findMyCompnay($arrayKeyValue['specification']);
            }

            if ($specification) {
                $invoice->setStock($specification->getStock());
            } else {
                //$this->em->clear($invoice);
                $this->em->detach($invoice);
                $this->em->clear();
                abort('400','Спецификация не указана, необходимо указать склад');
            }
        }

        if (array_key_exists('materials',$arrayKeyValue) && is_array($arrayKeyValue['materials'])) {
            $amount_invoice = 0;
            foreach ($arrayKeyValue['materials'] as $material) {


                $material_requisition =  $this->requisitionRepository->getMaterialRequisition($requisition,$material['id']);

               if (!$material_requisition) {
                   abort('400', 'Не найден материал ['.$material['id'].'] в заявке ['.$requisition->getId().']');
               }
               $invoicesList = $requisition->getInvoices()->getValues();
               //dd($invoicesList);
               if ($invoicesList) {
                   $tolalOrderedMaterail = $this->invoiceMaterialsRequisitionRepository->totalOrderedMaterial($invoicesList,$material_requisition);
                   if ($tolalOrderedMaterail === null) {
                       $tolalOrderedMaterail = 0;
                   }
               } else {
                   $tolalOrderedMaterail = 0;
               }

               $invoice_material_quantity = $material['quantity'];

               if ($material_requisition && $invoice_material_quantity > 0) {
                   $material_invoice = $this->invoiceMaterialsRequisitionRepository->loadNew(['requisitionInvoice'=>$invoice,'requisitionMaterial'=>$material_requisition]);


                   $material_invoice->setQuantity($invoice_material_quantity);
                   $material_invoice->setPrice($material['price']);
                   $material_invoice->setAmount($material_invoice->getPrice()*$material_invoice->getQuantity());
                   $amount_invoice += $material_invoice->getAmount();
                   if ($material_requisition->getSpecificationMaterial()){
                       //dd($tolalOrderedMaterail,$invoice_material_quantity,$material_requisition->getQuantity());
                       if ($tolalOrderedMaterail + $invoice_material_quantity > $material_requisition->getQuantity()) {
                           //$this->em->close();
                           $this->em->remove($material_invoice);
                           $this->em->remove($invoice);

                           abort('400',' Материал: ['.$material_requisition->getSpecificationMaterial()->getFullname().'] Всего по заявке ['.$material_requisition->getQuantity().'] уже заказано ['.$tolalOrderedMaterail.'] можно обработать еще '.$material_requisition->getQuantity() - $tolalOrderedMaterail.' едениц');

                       }

                       $material_invoice->setSpecificationMaterial($material_requisition->getSpecificationMaterial());

                       if ($material_requisition->getDirectoryMaterial()) {
                            $material_invoice->setDirectoryMaterial($material_requisition->getDirectoryMaterial());
                       } else {
                           $this->em->remove($material_invoice);
                           $this->em->remove($invoice);

                           abort('400',' Материал: ['.$material_requisition->getId().'] не привязан к справочнику');
                       }

                   }else if ($material_requisition->getDirectoryMaterial()) {
                       $materialDirectory = $this->materialRepository->findMyCompnay($material_requisition->getDirectoryMaterial());
                       //dd($materialDirectory);
                       if ($tolalOrderedMaterail + $invoice_material_quantity > $material_requisition->getQuantity()) {
                           $this->em->close();
                           abort('400',' Материал: ['.$materialDirectory->getName().'] Всего по заявке ['.$material_requisition->getQuantity().'] уже заказано ['.$tolalOrderedMaterail.'] можно обработать еще ['.$material_requisition->getQuantity() - $tolalOrderedMaterail.'] едениц, вы указали в зявке: ['.$invoice_material_quantity.']');

                       }
                       $material_invoice->setDirectoryMaterial($material_requisition->getDirectoryMaterial());
                   } else {
                       if ($tolalOrderedMaterail + $material['quantity'] > $material_requisition->getQuantity()) {
                           $this->em->remove($material_invoice);
                           $this->em->remove($invoice);

                           abort('400',' Материал: ['.$material_requisition->getName().'] Всего по заявке ['.$material_requisition->getQuantity().'] уже заказано ['.$tolalOrderedMaterail.'] можно обработать еще '.$material_requisition->getQuantity() - $tolalOrderedMaterail.' едениц');
                       }

                   }

                   if (array_key_exists('description',$material) && $material['description']) {
                       $material_invoice->setDescription($material['description']);
                   } else {
                       unset($material['description']);
                   }

                   if (array_key_exists('files',$material) && is_array($material['files'])) {

                       foreach ($material['files'] as $file) {
                           $file_rep = $this->fileRepository->getFileHash($file);
                           if ($file_rep) {
                               $file_rep->addRequisitionInvoiceMaterial($material_invoice);
                               $this->em->persist($file_rep);
                               $material_invoice->addFile($file_rep);
                           }
                       }

                   }

                   $this->em->persist($material_invoice);
                   $invoice->addMaterial($material_invoice);
               }
            }
        } else {
            $this->em->remove($invoice);

            abort('400','Нет материалов в счете');
        }




        if ($invoice->getMaterials()->count() == 0) {
            $this->em->remove($invoice);
            abort('500',"Материалы пустые");
        }


        $invoice->setAmount($amount_invoice);

        $requisition->setStatus('inprogress');
        $this->em->persist($invoice);



        $this->em->flush($invoice);
        $this->invoiceRequisitionStatus($requisition,$invoice);
        $this->recalculationProcentRequisition($requisition);
        $this->em->flush($requisition);

        return $invoice;
    }

    public function invoiceRequisitionStatus(Requisition $requisition, Invoice $invoice=null) {

        $status = $requisition->getStatus();
        $commit_material = 0;
        foreach ($requisition->getMaterials() as $material_requisition) {


            if ($material_requisition->getQuantity() <= $this->invoiceMaterialsRequisitionRepository->amountMaterialInvoices($material_requisition) && $requisition->getStatus() == 'inprogress' ) {
                $status="supplied";
                $commit_material++;

            }else {
                $status = "inprogress";

            }

        }

        $requisition->setStatus($status);

        if ($invoice) {
            $invoice->setStatus("new");
            $this->em->persist($invoice);
        }

        if ($status == 'supplied' && $invoice === null) {

            $invoices = $requisition->getInvoices();
            $this->requisitionRepository->save($requisition);
            foreach ($invoices as $invoice) {
                $invoice = $this->findMyCompnay($invoice);

                if (!$invoice->getStatus()) {
                    $invoice->setStatus("new");
                }


                if ($invoice->getStatus() == 'canceled') {
                    $invoice->setFixed('false');
                } else {
                    $invoice->setFixed('true');
                }

                $this->em->persist($invoice);
            }
        }
    }


    public function invoicesListManager($options)
    {
        $options = $this->parseOptions($options);

        $pagginate=[];
        $orderBy=[];
        if ($options && array_key_exists('pagginate',$options)) {
            $pagginate = $options['pagginate'];

        }
        if ($options && array_key_exists('orderBy',$options)) {
            $orderBy =  $options['orderBy'];
        }

        if (array_key_exists('page',$pagginate)) {
            $page = $pagginate['page'];
        } else {
            $page = 1;
        }

        if (array_key_exists('limit',$pagginate)) {
            $limit = $pagginate['limit'];
        } else {
            $limit = $this->getDefaultLimit();
        }
        $pagginate['recordLimit'] = $limit;

        $qb = $this->em->createQueryBuilder();

        $qb->select('inv')->from(get_class($this->entity),'inv');
        $qb->leftJoin(Requisition::class,'reg','WITH','reg.id = inv.requisition');
        $qb->where($qb->expr()->eq('inv.company',"?1"));
        $qb->andWhere($qb->expr()->eq('reg.fixed',"true"));
        $qb->andWhere($qb->expr()->eq('reg.manager','?2'));
        $qb->groupBy('inv.id');
        if ($orderBy) {
            $orderBy = $this->checkAttr($options['orderBy']);
            foreach ($orderBy as $key => $value) {
                $qb->orderBy('inv.'.$key,$value);
            }
        }
        $query = $this->em->createQuery($qb->getDQL());

        $account = auth()->user();

        $query->setParameter(1, $account->getCompany()->getId());
        $query->setParameter(2, auth()->user()->getId());

        return $this->resultQueryData($query,$options);
    }
    public function invoicesRequisitionList( $requisition, $options) {

        $options = $this->parseOptions($options);

        $pagginate=[];
        $orderBy=[];
        if ($options && array_key_exists('pagginate',$options)) {
            $pagginate = $options['pagginate'];

        }
        if ($options && array_key_exists('orderBy',$options)) {
            $orderBy =  $options['orderBy'];
        }

        if (array_key_exists('page',$pagginate)) {
            $page = $pagginate['page'];
        } else {
            $page = 1;
        }

        if (array_key_exists('limit',$pagginate)) {
            $limit = $pagginate['limit'];
        } else {
            $limit = $this->getDefaultLimit();
        }
        $pagginate['recordLimit'] = $limit;

        $requisition = $this->requisitionRepository->findByUuid($requisition);
        //dd($requisition->getInvoices());
        $qb = $this->em->createQueryBuilder();

        $qb->select('inv')->from(get_class($this->entity),'inv');
        $qb->innerJoin(get_class($requisition),'reg');
        $qb->where($qb->expr()->eq('reg.company',"?1"));
        $qb->andWhere($qb->expr()->eq('inv.company',"?1"));
        $qb->andWhere($qb->expr()->eq('inv.fixed',"true"));
        $qb->andWhere($qb->expr()->eq('inv.requisition','?2'));
        $qb->groupBy('inv.id');
        if ($orderBy) {
            //$qb->orderBy($orderBy);
        }

        $query = $this->em->createQuery($qb->getDQL());

        $account = auth()->user();

        $query->setParameter(1, $account->getCompany()->getId());
        $query->setParameter(2, $requisition->getId());

        $paginator = new Paginator($query, $fetchJoinCollection = false);
        //$result = $query->getResult();
        $count = count($paginator);
        $pages = ceil($count/$limit);
        $options = [
            'pagginate'=>[
                'pages'=>$pages,
                'limit'=>$limit,
                'page'=>$page
            ],
            'orderBy'=>[]
        ];
        $firstResult = ($page-1)*$limit;

        return [
         'data'=>$query->setFirstResult($firstResult)->setMaxResults($limit)->getResult(),
         'options'=>$options
        ];
    }


    private function generateNumber(Object $entity) {

        if (!property_exists($entity,'index')) {
            abort(500,'Для генерации номера необходимо поле index');
        }
        if (!property_exists($entity,'number')) {
            abort(500,'Для генерации номера необходимо поле index');
        }

        if (!property_exists($entity,'company')) {
            abort(500,'Для генерации номера необходимо поле company');
        }

        $qb = $this->em->createQueryBuilder()->setMaxResults(1);
        $qb = $qb->select('en')->from(get_class($entity),'en');
        $company = auth()->user()->getCompany();

        $qb = $qb->where($qb->expr()->eq('en.company',"?1"));
        $qb = $qb->andWhere($qb->expr()->between('en.createdAt','?2','?3'));
        $qb = $qb->orderBy('en.index','DESC');

        $query = $this->em->createQuery($qb->getDQL())->setMaxResults(1);

        $nowDate = new \DateTimeImmutable('now');

        $query->setParameter(1, $company->getId());
        $query->setParameter(2, $nowDate->modify('first day of January this year midnight'));
        $query->setParameter(3, $nowDate->modify('first day of January +1 year midnight'));

        if ($query->getResult()) {
            $result = $query->getSingleResult();
            $newIndex = $result->getIndex() + 1;
        } else {
            $newIndex = 1;
        }

        $newNumber= env('PREFIX_INVOICE_REQ','Счет ').$newIndex."/".$nowDate->format('Y');
        $entity->setIndex($newIndex);
        $entity->setNumber($newNumber);

        return $entity;
    }

    public function invoicesRequisitionListMaster(Requisition $requisition,$options=[])
    {
        $pagginate=[];
        $orderBy=[];
        if ($options && array_key_exists('pagginate',$options)) {
            $pagginate = $options['pagginate'];
        }
        if ($orderBy && array_key_exists('orderBy',$options)) {
            $orderBy = [];
        }

        if (array_key_exists('page',$pagginate)) {
            $page = $pagginate['page'];
        } else {
            $page = 1;
        }

        if (array_key_exists('limit',$pagginate)) {
            $limit = $pagginate['limit'];
        } else {
            $limit = $this->getDefaultLimit();
        }

        $qb = $this->em->createQueryBuilder();

        $qb->select('inv')->from(get_class($this->entity),'inv');

        $qb->where($qb->expr()->eq('inv.company',"?1"));
        $qb->andWhere($qb->expr()->eq('inv.requisition','?2'));


        $query = $this->em->createQuery($qb->getDQL());

        $account = auth()->user();

        $query->setParameter(1, $account->getCompany()->getId());
        $query->setParameter(2, $requisition);

        $paginator = new Paginator($query, $fetchJoinCollection = false);
        //$result = $query->getResult();
        $count = count($paginator);
        $pages = ceil($count/$limit);

        $options = [
            'pagginate'=>[
                'pages'=>$pages,
                'limit'=>$limit,
                'page'=>$page
            ],
            'orderBy'=>[]
        ];
        $firstResult = ($page-1)*$limit;
        $list_resilt = [];
        foreach ($query->getResult() as $entity) {
            $materialresult = [];
            $materials_l = $entity->getMaterials();

            foreach ($materials_l as $material) {
                //dd($material->getMaterials());
                $material_requisitio_calculate = $this->invoiceMaterialsRequisitionRepository->getMaterialCalculate($material->getRequisitionMaterial());
                $materialresult[] = [
                    'requisition_material_id'=>$material->getRequisitionMaterial()->getId()->serialize(),
                    'requisition_material_quantity'=>$material->getRequisitionMaterial()->getQuantity(),
                    'delivery_quantity'=>$material->getQuantity(),
                    'confirmed_quantity'=> $this->invoiceMaterialsConfirmedRequisitionRepository->getQuantityConfirmed($material),
                    'remnant_quantity' => $material_requisitio_calculate['remnant'],
                    'requisition_material'=>$material->getRequisitionMaterial(),
                ];
            }

            $list_resilt[] = [
                'id'=>$entity->getId()->serialize(),
                'status'=>$entity->getStatus(),
                'description'=>$entity->getDescription(),
                'delivery_at'=>$entity->getDeliveryAt(),
                'materials'=>$materialresult


            ];
        }
        return $list_resilt;
    }
    public function invoicesRequisitionListAll(Requisition $requisition,$options=[]) {

        $pagginate=[];
        $orderBy=[];
        if ($options && array_key_exists('pagginate',$options)) {
            $pagginate = $options['pagginate'];
        }
        if ($orderBy && array_key_exists('orderBy',$options)) {
            $orderBy = [];
        }

        if (array_key_exists('page',$pagginate)) {
            $page = $pagginate['page'];
        } else {
            $page = 1;
        }

        if (array_key_exists('limit',$pagginate)) {
            $limit = $pagginate['limit'];
        } else {
            $limit = $this->getDefaultLimit();
        }

        $qb = $this->em->createQueryBuilder();

        $qb->select('inv')->from(get_class($this->entity),'inv');
        $qb->leftJoin(get_class($requisition),'reg');
        $qb->where($qb->expr()->eq('inv.company',"?1"));
        $qb->andWhere($qb->expr()->eq('inv.requisition','?2'));


        $query = $this->em->createQuery($qb->getDQL());

        $account = auth()->user();

        $query->setParameter(1, $account->getCompany()->getId());
        $query->setParameter(2, $this->requisitionRepository->findByUuid($requisition));

        $paginator = new Paginator($query, $fetchJoinCollection = false);
        //$result = $query->getResult();
        $count = count($paginator);
        $pages = ceil($count/$limit);

        $options = [
            'pagginate'=>[
                'pages'=>$pages,
                'limit'=>$limit,
                'page'=>$page
            ],
            'orderBy'=>[]
        ];
        $firstResult = ($page-1)*$limit;

        return $query->getResult();
    }

    public function specificationInvoicesRequisitionList(Specification $specification,$options) {

        $pagginate=[];
        $orderBy=[];
        if ($options && array_key_exists('pagginate',$options)) {
            $pagginate = $options['pagginate'];
        }
        if (array_key_exists('orderBy',$options)) {
           //$orderBy = $this->checkOrderedAttr($options['orderBy'],'inv');
        }

        if (array_key_exists('page',$pagginate)) {
            $page = $pagginate['page'];
        } else {
            $page = 1;
        }

        if (array_key_exists('limit',$pagginate)) {
            $limit = $pagginate['limit'];
        } else {
            $limit = $this->getDefaultLimit();
        }

        $listMaterials = new Collection();

        foreach ($specification->getSpecificationMaterials() as $specificationMaterial) {
            $row['id'] = $specificationMaterial->getId()->serialize();
            $listMaterials->add($row);
        }


        $qb = $this->em->createQueryBuilder();
        $qb->select(['inv'])->from(get_class($this->entity),'inv');
        $qb->innerJoin(\Domain\Entities\Business\Document\Requisition\Invoice\Material::class,'inv_m');
        $qb->leftJoin(SpecificationMaterial::class,'spec_m','WITH','spec_m.id = inv_m.specificationMaterial');
        $qb->leftJoin(Specification::class,'spec');
        $qb->where($qb->expr()->in('spec.id',':specification'));
        $qb->andWhere($qb->expr()->isNull('inv_m.deletedAt'));
        $qb->groupBy('inv');

        foreach ($orderBy as $key=>$val) {
            $qb->orderBy('inv.'.$key,$val);
        }

        $query = $this->em->createQuery($qb->getDQL());
        $query->setParameter('specification',$specification);
        $paginator = new Paginator($query, $fetchJoinCollection = false);
        $count = count($paginator);
        $pages = ceil($count/$limit);

        $options = [
            'pagginate'=>[
                'pages'=>$pages,
                'limit'=>$limit,
                'page'=>$page,
                'count'=>$count
            ],
            'orderBy'=>[]
        ];
        $firstResult = ($page-1)*$limit;

        return [
            'data'=>$query->setFirstResult($firstResult)->setMaxResults($limit)->getResult(),
            'options'=>$options

        ];
    }

    public function deliveryMaterialsRequisition(Requisition $requisition, $requisitionMaterialId) {
        $requisitionMaterial = $this->requisitionRepository->getMaterialRequisitionOne($requisition,$requisitionMaterialId);
        $quantity_count = $this->invoiceMaterialsRequisitionRepository->amountMaterialInvoices($requisitionMaterial);
        return $this->invoiceMaterialsRequisitionRepository->getMaterialCalculate($requisitionMaterial);
    }

    public function deliveryMaterialsRequisitionList(Requisition $requisition) {
        $list_material_result = new Collection();

        if ($requisition->getFixed() === false) {
            return  [];
        }

        $criteria = new Criteria();
        $criteria->orderBy(['specificationMaterial'=>"ASC"]);
        $materials = $requisition->getMaterials()->matching($criteria);

        foreach ($materials as $material) {
            if ($material && is_object($material)) {
                $list_material_result->add($this->invoiceMaterialsRequisitionRepository->getMaterialCalculate($material));
            }

        }

        return $list_material_result->all();
    }

    public function getInvoice($id)
    {
        return $this->findByUuid($id);
    }

    public function getInvoiceMaterial(RequisitionMaterials $requisitionMaterial, Invoice $invoice): InvoiceMaterial | null | bool
    {

        $in = new InvoiceMaterial();
        $in->getRequisitionMaterial();
        $criteria = new Criteria();
        $expr = new Comparison('requisitionMaterial', '=', $requisitionMaterial);
        $criteria->where($expr);
        $result = $invoice->getMaterials()->matching($criteria)->first();
        return $result ?? null;
    }

    public function checkStatusInvoice(Invoice $invoice)
    {

    }

    public function processProcentInvoiceRequisition(Requisition $requisition): float
    {
        $quantity_s = 0;
        $remnant_s = 0;
        foreach ($requisition->getMaterials() as $material) {
            $cal  = $this->invoiceMaterialsRequisitionRepository->getMaterialCalculate($material);
            $quantity_s += $cal['quantity'];
            $remnant_s += $cal['remnant'];
        }
        return round(($remnant_s/$quantity_s )*100,3);
    }

    public function processProcentInvoiceConfirmedRequisition(Requisition $requisition): float
    {
        $confirm_s = 0;
        $remnant_s = 0;
        foreach ($requisition->getMaterials() as $material) {
            $inv_cal  = $this->invoiceMaterialsRequisitionRepository->getMaterialCalculate($material);
            $invoiceMaterial  = $this->invoiceMaterialsRequisitionRepository->getInvoiceMaterials($material);
            if ($invoiceMaterial) {
                $confirm_s += $this->invoiceMaterialsConfirmedRequisitionRepository->getQuantityConfirmed($invoiceMaterial);
            }

            $remnant_s += $inv_cal['remnant'];

        }
        Log::info("======================processProcentInvoiceConfirmedRequisition==================");
        Log::info($confirm_s);
        Log::info($remnant_s);
        if ($remnant_s  == 0) {
            return 0;
        }

        return round(($confirm_s/$remnant_s )*100,3);
    }

    private function invoiceCalculation(Invoice $invoice)
    {
        $confirm_s = 0;
        $remnant_s = 0;
         foreach ($invoice->getMaterials() as $materialInvoice) {
             $confirm_s += $this->invoiceMaterialsConfirmedRequisitionRepository->getQuantityConfirmed($materialInvoice);
             $remnant_s += $materialInvoice->getQuantity();
         }
        $progress =  round(($confirm_s/$remnant_s )*100,3);
        $invoice->setStatus("progress");
        $invoice->setProgress($progress);
        $this->em->persist($invoice);
    }
    public function deliveryMaterialСonfirmed($materialСonfirmed)
    {

        $newConfirmedMaterial = $this->invoiceMaterialsConfirmedRequisitionRepository->loadNew($materialСonfirmed);
        $requisitionInvoice = $newConfirmedMaterial->getRequisitionInvoiceMaterial()->getRequisitionInvoice();
        //$requisition = $newConfirmedMaterial->getRequisitionInvoiceMaterial()->getRequisitionMaterial()->getRequisition();

        // Привязка файлов к подтверждённому материалу
        if (array_key_exists('files', $materialСonfirmed)) {
            foreach ($materialСonfirmed['files'] as $file) {
                $newConfirmedMaterial->addFile($file);
                $file->addRequisitionInvoiceMaterialReceived($newConfirmedMaterial);
                $this->em->persist($file);
            }
        }
        $this->em->persist($newConfirmedMaterial);

        $invoiceMaterial = $newConfirmedMaterial->getRequisitionInvoiceMaterial();
        $invoice = $invoiceMaterial->getRequisitionInvoice();
        $requisitionMaterial = $invoiceMaterial->getRequisitionMaterial();

        // Проверка общего количества подтверждённых материалов
        $countConfirmed = $this->invoiceMaterialsConfirmedRequisitionRepository->getQuantityConfirmed($invoiceMaterial);
        $countInvoiceMaterial = $invoiceMaterial->getQuantity();

        // Обновление статусов в зависимости от подтверждённого количества
        if ($countConfirmed == $countInvoiceMaterial) {
            // Полное подтверждение материала
            $this->setStatusCompleted($newConfirmedMaterial, $requisitionMaterial, $invoiceMaterial, $invoice);
        } elseif ($countConfirmed > $countInvoiceMaterial) {
            $countConfirmed == $countInvoiceMaterial;
            $this->setStatusCompleted($newConfirmedMaterial, $requisitionMaterial, $invoiceMaterial, $invoice);

        } elseif ($countConfirmed > 0) {
            // Частичное подтверждение
            $this->setStatusProcessing($newConfirmedMaterial, $requisitionMaterial, $invoiceMaterial, $invoice);
        } else {
            // Нет подтверждения, состояние новое
            $this->setStatusNew($newConfirmedMaterial, $requisitionMaterial, $invoiceMaterial);
        }

        $this->em->persist($newConfirmedMaterial);

        $this->em->flush();
        $this->invoiceCalculation($requisitionInvoice);
        return $newConfirmedMaterial;
    }

    public function recalculationProcentRequisition(Requisition $requisition)
    {
        $p_confirmed = $this->processProcentInvoiceConfirmedRequisition($requisition);
        $p_remnant = $this->processProcentInvoiceRequisition($requisition);


        $p_avg = ($p_confirmed+$p_remnant)/2;
        $requisition->setProgress($p_avg);
        $this->em->persist($requisition);
        $this->em->flush();

        return $requisition;
    }
    private function setStatusCompleted($newConfirmedMaterial, $requisitionMaterial, $invoiceMaterial, $invoice)
    {
        $newConfirmedMaterial->setStatus('completed');
        $requisitionMaterial->setStatus('completed');
        $invoiceMaterial->setStatus('completed');
        $invoice->setFixed(true);
        $this->em->persist($newConfirmedMaterial);
        $this->em->persist($requisitionMaterial);
        $this->em->persist($invoiceMaterial);
    }

    private function setStatusProcessing($newConfirmedMaterial, RequisitionMaterials $requisitionMaterial, $invoiceMaterial, $invoice)
    {
        $count_confirmed = $this->invoiceMaterialsConfirmedRequisitionRepository->getQuantityConfirmed($invoiceMaterial);
        $requisition  = $requisitionMaterial->getRequisition();

        $requisition->setProgress($count_confirmed);

        $newConfirmedMaterial->setStatus('processing');
        $requisitionMaterial->setStatus('processing');
        $invoiceMaterial->setStatus('processing');
        $invoice->setFixed(true);
        $this->em->persist($newConfirmedMaterial);
        $this->em->persist($requisitionMaterial);
        $this->em->persist($invoiceMaterial);
    }

    private function setStatusNew($newConfirmedMaterial, $requisitionMaterial, $invoiceMaterial)
    {
        $newConfirmedMaterial->setStatus('new');
        $requisitionMaterial->setStatus('processing');
        $invoiceMaterial->setStatus('new');
        $this->em->persist($newConfirmedMaterial);
        $this->em->persist($requisitionMaterial);
        $this->em->persist($invoiceMaterial);
    }

    public function deliveryMasterMaterialsСonfirmed(Requisition $requisition,  array $materialListСonfirmed)
    {
        $result_list = new Collection();

        foreach ($materialListСonfirmed as $material_confirmed) {

            $result_list->add($this->deliveryMaterialСonfirmed($material_confirmed));

        }
        $this->recalculationProcentRequisition($requisition);
        // $this->requisitionRepository->recalculationProcentRequisition($requisition);
        return $result_list->all();
    }

    public function deliveryRequisitionProgress(Requisition $requisition,Invoice $delivery)
    {
        $mt = new InvoiceMaterial();
        //$mt->getRequisitionMaterial()->getQuantity()
        $count_d = 0;
        $count_r = 0;
        foreach ($delivery->getMaterials() as $material) {
            $count_d += $this->invoiceMaterialsConfirmedRequisitionRepository->getQuantityConfirmed($material);
            $count_r += $material->getRequisitionMaterial()->getQuantity();
        }

        return [
            "count"=>$count_d,
            "count_r"=>$count_r,
            "procent"=>round(($count_d/$count_r)*100,2)
        ];
    }

}



//private InvoiceMaterialsRequisitionRepository $invoiceMaterialsRequisitionRepository;
//private InvoiceMaterialsConfirmedRequisitionRepository $invoiceMaterialsConfirmedRequisitionRepository;
