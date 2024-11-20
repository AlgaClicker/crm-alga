<?php

namespace Infrastructure\Repositories;

use Cassandra\Date;
use Doctrine\DBAL\Driver\Exception;
use Doctrine\ORM\Exception\NotSupported;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use LaravelDoctrine\ORM\Serializers\Jsonable;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Core\Exceptions\ApplicationException;
use Illuminate\Support\Facades\Auth;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use ReflectionProperty;
use Infrastructure\Repositories\Services\LogsRepository;
use DateTimeImmutable;
use function PHPUnit\Framework\isNull;

abstract class AbstractRepository
{
    use Jsonable;
    /**
     * @var
     */
    protected $entity;
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var object
     */
    protected $logs;

    /**
     * @var object
     */
    protected $account;

    /**
     * @var object
     */
    private $company;

    /**
     * @var string
     */
    protected $entityNamespace;

    /**
     * @var array
     */
        protected const RECORDS_LIMIT = [1,2,5,10,20,25,50,100];

    /**
     * AbsctractRepository constructor.
     * @param EntityManager $em
     * @param $entity
    */
    public function __construct(EntityManager $em, $entity)
    {

        $this->em = $em;
        $this->entity = $entity;
        $this->entityNamespace = get_class($entity);
        $this->account = auth()->user();
        if (Auth::getUser()) {

            //$this->company = Auth::getUser()->getCompany();
            $this->company = $this->em->getRepository('Domain\Entities\Business\Company\Company')->find(Auth::getUser()->getCompany());
        }
        $this->logs = new LogsRepository($em);
    }

    public function checkAttr($attribArray) {
        $attribByGood = [];
        if ($attribArray === null) {
            return  [];
        }
        foreach ($attribArray as $property=>$mode) {


            if (!property_exists($this->entity, $property)) {
                $property = substr($property, 0, strpos($property, '_')) . ucfirst(substr($property, strpos($property, '_') + 1));
            }

            if (property_exists($this->entity, $property) && $mode !== null) {
                $attribByGood[$property]  = $mode;
            }
            if (property_exists($this->entity, $property) && $mode == null) {
                $attribByGood[$property]  = $mode;
            }

        }

        return $attribByGood;
    }

    public function filterByQuery($arrKeyValue,$orderBy=[]) {
        $qb = $this->em->createQueryBuilder();
        $atual = $qb->select("thisEntity")->from($this->entityNamespace,'thisEntity');

        if(method_exists($this->entity,'setCompany')){
            $atual->where("thisEntity.company='".$this->account->getCompany()->getId()."'");
        }

        if(method_exists($this->entity,'setDeletedAt')){
            $atual->andWhere($qb->expr()->isNull('thisEntity.deletedAt'));
        }
        $idx_row = 1;
        foreach ($arrKeyValue as $key=>$val) {
            $method = 'get'.ucfirst($key);
            $setMethod = 'set'.ucfirst($key);

            if (property_exists($this->entity,$key) && method_exists($this->entity,$method)) {
                $rp = new ReflectionProperty($this->entity, $key);
                $sName = stripos($rp->getDocComment(), ' * @var ') + 8;
                $stName = substr($rp->getDocComment(), $sName);
                $stEnd = stripos($stName, "\n");
                $type = substr($stName, 0, $stEnd);
                $type = substr($type,0, strpos($type,"|"));

                if ($type == "") {
                    $type = substr($rp->getDocComment(),$sName,$stEnd);
                }

                if (mb_substr($type,0,-strlen($type)+1) == "\\") {
                    $type = substr($type,1);
                }

                if (method_exists($this->entity,$setMethod) && $val && !is_array($val)) {
                    $childrenEntityReflection = new \ReflectionParameter(array($this->entity, $setMethod), 0);
                    $parentClassId  = $childrenEntityReflection->getClass();

                    if ($parentClassId && Uuid::isValid($val)) {
                        $parentClassId  = $childrenEntityReflection->getClass()->getName();
                        $prentEntity = $this->em->getRepository($parentClassId)->findOneBy(['id'=>$val]);

                        if ($prentEntity) {
                            $qb=$qb->andWhere($qb->expr()->eq("thisEntity.".$key,"'$val'"));
                        }
                    }
                }

                if (property_exists($this->entity,$key) && method_exists($this->entity,$method) && !is_bool($val) && $val == null) {
                    $qb=$qb->andWhere($qb->expr()->isNull("thisEntity.".$key));
                }

                if (is_array($val)) {

                    if (array_key_exists('start',$val) && array_key_exists('end',$val)) {
                        $start = $val['start'];
                        $end = $val['end'];

                        if ($start && $type == "DateTime") {

                            if (date_parse($start)['error_count']>0) {
                                abort('400','Ошибка фильтра! Поле ['.$key.'] , формат даты [ГГГГ-ММ-ДД]');
                            }
                                $start = new \DateTimeImmutable($start);
                        }

                        if ($end && $type == "DateTime") {
                            if (date_parse($end)['error_count']>0) {
                                abort('400','Ошибка фильтра, формат даты [ГГГГ-ММ-ДД]');
                            }

                            $end = new \DateTimeImmutable($end);
                        }

                        if ($start && $end && $type == "DateTime") {

                            $start = $start->modify('midnight')->format('Y-m-d H:i:s');
                            $end =$end->modify('tomorrow')->format('Y-m-d H:i:s');
                           // dd($start,$end);
                            $atual->andWhere(
                                $qb->expr()->between("thisEntity.$key", '\''.$start.'\'','\''.$end.'\'')
                            );

                        }

                        if ($type == "int" || $type == "float") {
                            if (floatval($start) && floatval($end)) {
                                $atual->andWhere(
                                    $qb->expr()->between("thisEntity.$key", '\''.$start.'\'','\''.$end.'\'')
                                );
                            }
                            if (intval($start) && intval($end)) {
                                $atual->andWhere(
                                    $qb->expr()->between("thisEntity.$key", '\''.$start.'\'','\''.$end.'\'')
                                );
                            }

                        }

                    }

                } else {
                    if ($type=='bool') {
                        if ($val=='true') {
                            $bol = "true";
                        } else {
                            $bol = "false";
                        }
                        $atual->andWhere("thisEntity.$key = '$bol'");
                    } elseif ($type == 'string') {
                        $atual->andWhere("LOWER(thisEntity.$key) LIKE LOWER('%" . $val . "%')");
                    } elseif ($type == 'float' || $type == 'int') {
                        $atual->andWhere("thisEntity.$key = '".$val."'");
                    } elseif (class_exists($type) && Str::of($val)->isUuid() ){

                        if (is_array($val) || is_object($val)) {
                            if ($this->em->getMetadataFactory()->isTransient(get_class($val))) {
                                $atual->andWhere("thisEntity.".$key." = '".$val."'");
                            }
                        } else {
                            $parent_search = $this->em->getRepository($type)->findOneBy(['id'=>$val]);
                            if ($parent_search) {
                                $atual->andWhere("thisEntity.".$key." = '".$parent_search->getId()."'");
                            }
                        }

                    } else {
                        #TODO Определить выборку по критериям подчиненой сущности
                        if (class_exists($type)) {
                            //dd($type,$val,Str::of($val)->isUuid());
                        }
                        //dd(class_exists($type),$type,$rp->getDocComment(),$stEnd,substr($rp->getDocComment(),$sName,$stEnd));
                    }
                }
            }
            $idx_row++;
        }

        if ($orderBy && is_array($orderBy)) {

            foreach ($orderBy as $key=>$val) {
                $method = 'get'.ucfirst($key);
                if (property_exists($this->entity,$key) && method_exists($this->entity,$method)) {
                    $atual->addOrderBy("thisEntity.$key",$val);
                }
            }

        }

        return $atual->getDQL();
    }

    public function filterByPagginate($arrKeyValue,$orderBy=[],$page=0,$limit=50) {
        //dd($page);
        $queryDQL = $this->filterByQuery($arrKeyValue,$orderBy);
        $query = $this->em->createQuery($queryDQL);
        $count = count($query->getScalarResult());
        $page = $page + 1;
        $pages = ceil($count/$limit);

        if ($page> $pages) {
            $page = $pages;
        }

        if ($count==0) {
            $page = 1;
            $pages = 1;
        }


       return [
           "page"=>$page,
           "pages"=> $pages,
           "limit"=>$limit,
           "count"=>$count
       ];
    }

    public function filterBy($arrKeyValue,$orderBy=[],$page=0,$limit=50) {

        if (is_array($arrKeyValue) && $arrKeyValue) {
            foreach ($arrKeyValue as $key => $value) {
                unset($arrKeyValue[$key]);
                if (!property_exists($this->entity, $key)) {
                    $key = substr($key, 0, strpos($key, '_')) . ucfirst(substr($key, strpos($key, '_') + 1));
                }
                if (property_exists($this->entity, $key)) {
                    $arrKeyValue[$key] = $value;
                }
            }
        }

        if (is_array($orderBy) && $orderBy) {
            foreach ($orderBy as $key => $value) {
                unset($orderBy[$key]);
                if (!property_exists($this->entity, $key)) {

                    $key = substr($key, 0, strpos($key, '_')) . ucfirst(substr($key, strpos($key, '_') + 1));
                }
                if (property_exists($this->entity, $key)) {
                    $orderBy[$key] = $value;
                }
            }
        }

        $queryDQL = $this->filterByQuery($arrKeyValue,$orderBy);

        $query = $this->em->createQuery($queryDQL)->setFirstResult($page*$limit)->setMaxResults($limit);
        //dd($query);
        //$query->setMaxResults(env('DEFAULT_SEARCH_LIMIT', $limit));
        $paginator = new Paginator($query, fetchJoinCollection: true);
        return $query->getResult();
    }

    /**
     * @param array $arrKeyValue
     * @param array $orderBy = null
     * @return null|array
     * @throws \ReflectionException
     */
    public function searchBy($arrKeyValue, $orderBy=[]) {
        $qb = $this->em->createQueryBuilder();
        $atual = $qb->select("thisEntity")->from($this->entityNamespace,'thisEntity');
        $line = 0;


        foreach ($arrKeyValue as $attr => $val)
        {
            if(method_exists($this->entity, 'get'.ucfirst($attr)) && $val){

                $rp = new ReflectionProperty($this->entity, $attr);

                if (is_string($val)) {
                    $val = str_replace(" ","%",$val );
                }


                if (stripos($rp->getDocComment(),'integer')) {
                    if ($line != 0 && $line<count($arrKeyValue)) {
                        $atual->orWhere($qb->expr()->eq("thisEntity.$attr", $val));
                    } else {
                        $atual->where($qb->expr()->eq("thisEntity.$attr", $val));
                    }

                } elseif (stripos($rp->getDocComment(),'boolean')) {

                    $val = $val ? "true" : "false";
                    if ($line != 0 && $line<count($arrKeyValue)) {
                        $atual->andWhere("thisEntity.$attr = $val");
                    } else {
                        $atual->where("thisEntity.$attr = $val");
                    }
                } else {
                    if (is_string($val)) {
                        $val = str_replace(" ","%",$val);
                    }

                    if (is_object($val) && Uuid::isValid($val)) {

                        if ($line != 0 && $line<count($arrKeyValue)) {
                            $atual->orWhere("thisEntity.$attr = '$val'");
                        } else {
                            $atual->where("thisEntity.$attr = '$val'");
                        }
                    } else {
                        if ($line != 0 && $line<count($arrKeyValue)) {
                            $atual->orWhere("LOWER(thisEntity.$attr) LIKE LOWER('%$val%')");
                        } else {
                            $atual->where("LOWER(thisEntity.$attr) LIKE LOWER('%$val%')");
                        }
                    }
                }

                $line++;
            } else {
                unset($arrKeyValue[$attr]);
            }
        }

        if(method_exists($this->entity,'setIsGroup')){
            $atual->andWhere("thisEntity.isGroup=false");
        }

        if(method_exists($this->entity,'setCompany')){
            $atual->andWhere("thisEntity.company='".$this->account->getCompany()->getId()."'");
        }

        if(method_exists($this->entity,'setDeletedAt')){
            $atual->andWhere($qb->expr()->isNull('thisEntity.deletedAt'));
        }

        if(method_exists($this->entity,'setDelete')){
            $atual->andWhere("thisEntity.delete='false'");
        }

        //dd($atual->getDQL());


        if (!$line) {
            return null;
        }
        $atual->orderBy('thisEntity.'.array_key_first($arrKeyValue),'DESC');

        $query = $this->em->createQuery( $atual->getDQL());

        $query->setMaxResults(env('DEFAULT_SEARCH_LIMIT', 20));

        return $query->getResult();
    }

    /**
     * @param int $entityId
     * @return null|object
     */
    public function find($entityId)
    {
        return $this->em->getRepository($this->entityNamespace)->find($entityId);
    }

    /**
     * @param int $entityId
     * @return null|object
     */
    public function findMyCompnay($entityId) {
        if ($this->account) {

            $findArray['id'] = $entityId;

            if(method_exists($this->entity,'setCompany') && $this->company){
                $findArray['company'] = $this->company;
            }

            return $this->em->getRepository($this->entityNamespace)->findOneBy($findArray);
        }
        return null;
    }


    public function findAllMyCompnay()
    {
        if (!$this->account) {
            return null;
        }

        if(method_exists($this->entity,'setDelete')){
            $result = $this->em->getRepository($this->entityNamespace)->findBy(['delete'=>false,'company'=>$this->company],['id' => 'DESC']);
        } else {
            $result =  $this->em->getRepository($this->entityNamespace)->findBy(['company'=>$this->company],['id' => 'DESC']);
        }
        return $result ;
    }

    public function findByMyCompnay($arrKeyValue)
    {

        if (!$this->account) {
            if (auth()->user()) {
                $this->account = auth()->user();
                $this->company = $this->account->getCompany();
            } else {
                return null;
            }
        }

        if(method_exists($this->entity,'setDelete')){
            $arrKeyValue = array_merge($arrKeyValue,['delete'=>false]);
        }

        if(method_exists($this->entity,'setCompany') && $this->company){
            unset($arrKeyValue['company']);
            $arrKeyValue['company'] =$this->company;
        }

        return $this->em->getRepository($this->entityNamespace)->findOneBy($arrKeyValue);
    }


    public function findByUuid($entityUuid) {

        $findArray['id'] = $entityUuid;

        if(method_exists($this->entity,'setCompany') && $this->company){
            $findArray['company'] =$this->company;
        }

        if(method_exists($this->entity,'setDelete')){
            $findArray['delete'] = false;
        }

        return $this->em->getRepository($this->entityNamespace)->findOneBy($findArray);
    }

    public function findAllByMyCompnay($arrAttributes, $arrOrderBy = null) {

        return $this->findAllByCompnay($arrAttributes,$arrOrderBy);
    }


    public function findAll()
    {

        if(method_exists($this->entity,'setDelete')){
            $result = $this->em->getRepository($this->entityNamespace)->findBy(array('delete'=>false),['created_at' => 'DESC']);
        } else {
            $result =  $this->em->getRepository($this->entityNamespace)->findBy(array(),['created_at' => 'DESC']);
        }
        return $result ;
    }


    public function findOne($entityId)
    {
        if(method_exists($this->entity,'setDelete')){
            $arrKeyValue = array_merge(['delete'=>false],['id'=>$entityId]);
            $arrKeyValue['id'] = $entityId;

        } else {
            $arrKeyValue = ['id'=>$entityId];
        }


        return $this->findOneBy($arrKeyValue);
    }



    public function findBy($arrKeyValue)
    {
        if(method_exists($this->entity,'setDelete')){
            $arrKeyValue = array_merge($arrKeyValue,['delete'=>false]);
        }

        return $this->em->getRepository($this->entityNamespace)->findOneBy($arrKeyValue);
    }

    public function findOneBy($arrKeyValue)
    {


        if(method_exists($this->entity,'setDelete')){
            $arrKeyValue['delete']=false;
        }

        if(method_exists($this->entity,'setCompany') && $this->company){
                $arrKeyValue['company'] = $this->company;
        }

        return $this->em->getRepository($this->entityNamespace)->findOneBy($arrKeyValue);
    }


    /**
     * @param $arrKeyValue
     * @param $arrOrderBy
     * @return array
     */
    public function findAllBy($arrKeyValue, $arrOrderBy = null)
    {

        if (!$arrOrderBy) {
            $arrOrderBy =  ['id' => 'DESC'];
        }
        if(method_exists($this->entity,'setDelete') && !array_key_exists("delete",$arrKeyValue)){
            $arrKeyValue['delete']=false;
        }


        return  $this->em->getRepository($this->entityNamespace)->findBy($arrKeyValue,$arrOrderBy);
    }


    public function findAllByCompnay($arrKeyValue, $arrOrderBy = [],$page=null,$offset=null)
    {

        if (is_array($arrOrderBy)) {
            foreach ($arrOrderBy as $key => $value) {
                unset($arrOrderBy[$key]);
                if (!property_exists($this->entity,$key)) {

                    $key = substr($key,0,strpos($key,'_')).ucfirst(substr($key,strpos($key,'_')+1));
                }
                if (property_exists($this->entity,$key)) {
                  $arrOrderBy[$key] = $value;
                }
            }

        }

        if(method_exists($this->entity,'setCompany') && $this->company){
            $arrKeyValue['company'] = $this->company;
        }

        if ($page || $offset) {

            $pageinate['page']=$page;
            $pageinate['recordLimit']=$offset;

            return $this->findAllByPaginate($arrKeyValue,$arrOrderBy,$pageinate);
        }

        return $this->findAllBy($arrKeyValue, $arrOrderBy);
    }

    public function findAllByUser($arrKeyValue) {
        $user =  Auth::getUser();
        $collection = new ArrayCollection();
        if(method_exists($this->entity,'setDelete')){
            $arrKeyValue = array_merge(['delete'=>false],$arrKeyValue);
        }

        if(method_exists($this->entity,'setCompany') && $this->company){
            $arrKeyValue['company'] = $this->company;
        }

        $allRecords = $this->em->getRepository($this->entityNamespace)->findBy($arrKeyValue);
        foreach ($allRecords as $record) {
            if(method_exists($record,'getAccounts')){
                foreach($record->getAccounts() as $account) {
                    if ($user == $account) {
                       $collection->push($record);
                    }
                }
            }

        }
        return $collection;

    }

    public function history($entityId) {
            return $this->logs->listHistory($this->entityNamespace,$entityId);

    }

    public function count(){
        return  $this->em->getRepository($this->entityNamespace)->count([]);
    }

    public function countByCompnay($arrKeyValue=[]){


        if ($this->account && $this->account->getCompany() && method_exists($this->entity,'getCompany')) {
            $arrKeyValue['company'] = $this->account->getCompany()->getId();
        }
        if (method_exists($this->entity,'getDelete')) {
            $arrKeyValue['delete'] = false;
        }


        return  $this->em->getRepository($this->entityNamespace)->count($arrKeyValue);
    }

    public function countAttr($arrKeyValue){
        return  $this->em->getRepository($this->entityNamespace)->count($arrKeyValue);
    }


    public function findAllByPaginate($arrKeyValue, $arrOrderBy=[],$paginate=[])
    {
        $limit = env('DEFAULT_SEARCH_LIMIT', 20);
        $offset = null;
        if(method_exists($this->entity,'setDelete')){
            $arrKeyValue = array_merge($arrKeyValue,['delete'=>false]);
        }

        $repository = $this->em->getRepository($this->entityNamespace);
        $count_records = count($repository->findBy($arrKeyValue));
        if ($paginate) {
            if (!array_key_exists('recordLimit',$paginate)) {
                $paginate['recordLimit'] = $this->getDefaultLimit();
            }

            if (array_search($paginate['recordLimit'],self::RECORDS_LIMIT)){
                $limit = $paginate['recordLimit'];
                $offset = ($paginate['page']*$limit);

                if ($limit > $count_records) {
                    $offset=0;
                    $arrKeyValue['pagginate']['page'] = 1;
                }

                $pagin = new Paginator($repository,true);

            } else {
                throw new ApplicationException('recordLimit[5,10,20,25,50,100]',400);
            }
        }
        if (!$arrOrderBy) {
            $arrOrderBy = ['createdAt'=>'DESC'];
        }
        unset($arrKeyValue['pagginate']);
        unset($arrKeyValue['orderBy']);
        unset($arrOrderBy['pagginate']);

        return $repository->findBy($arrKeyValue, $arrOrderBy,$limit,$offset);
    }

    public function getDefaultLimit()
    {
        return env('DEFAULT_RECORDS_LIMIT',10);
    }

    public function deleteByUuid($entityUuid) {
        $entity = $this->find($entityUuid);
        if (!$entity) {
            abort('400',"Не найдена запись с ID [$entityUuid] для удаления");
        }
        $skipMethods = ['getId','getCreatedAt'];


        foreach (get_class_methods($entity) as $method) {

            if ($method !== "getId" && mb_ereg_match('get',$method) && $entity->$method() instanceof \Doctrine\ORM\PersistentCollection) {
                if (is_array($entity->$method()) && $entity->$method()->count()) {
                    $this->em->close();
                    throw new ApplicationException("В объекте с ID {".$entity->getId()."} имеются связанные записи (".$entity->$method()->count()."). Method:".$method."()",400);
                }
            }
        }

        $this->isFixedChek($entity);

        if(method_exists($entity,'setDelete')){
            $entity->setDelete(true);
        }

        $this->logs->create($this->account->getId(),$entity,'delete');

        if(method_exists($entity,'setDeleteAt')){
            $entity->setDeleteAt(new \DateTimeImmutable('now'));
            $this->save($entity);
            return true;
        }


        if(method_exists($entity,'setDeletedAt')){
            $entity->setDeletedAt(new \DateTimeImmutable('now'));

            $this->save($entity);
            return  $entity;
        }

        return false;
    }

    public function delete($entityId) {
        return $this->deleteByUuid($entityId);
    }

    public function deleteHard($entityId) {
        $entity = $this->find($entityId);
        $this->isFixedChek($entity);

        if(method_exists($entity,'setDelete')){
            $entity->setDelete(true);
        }

        $this->logs->create($this->account->getId(),$entity,'deleteHard');

        $this->em->remove($entity);
        $this->em->flush();
    }

    public function setArchive($entityId) {
        $entity = $this->findByUuid($entityId);
        if(method_exists($entity,'setArchiveAt')){
            $entity->setArchiveAt(new \DateTimeImmutable('now'));
            $this->save($entity);
        }

        return $entity;
    }

    public function unSetArchive($entityId) {
        $entity = $this->findByUuid($entityId);
        if(method_exists($entity,'setArchiveAt')){
            $entity->setArchiveAt(null);
            $this->save($entity);
        }
        return $entity;
    }


    public function unDeleteById($entityId) {
        $entity = $this->find($entityId);
        if(method_exists($entity,'setDelete')){
            $entity->setDelete(false);
        }
        if(method_exists($entity,'setDeletedAt')){
            $entity->setDeletedAt(null);
        }
        $this->save($entity);
        return $entity;
    }

    private function isFixedChek($entity) {

        if(method_exists($entity,'getFixed')){
            if ($entity->getFixed()) {
                throw new ApplicationException('Фиксированая запись, удаление невозможно!',400);
            }
        }
    }

    public function deleteById($entityId)
    {
        return $this->deleteByUuid($entityId);
    }

    public function context($arrAttributes) {
        $findArray = [];
        if (is_array($arrAttributes)) {
            foreach ($arrAttributes as $key => $arrAttribute) {
                if(method_exists($this->entity,'set'.ucfirst($key))){
                    $findArray[$key]=$arrAttribute;
                }

            }
        }
        return $this->findAllByCompnay($findArray);
    }

    /**
     * @param $entity
     * @param $arrAttributes
     * @return mixed
     */
    public function update($entity, $arrAttributes)
    {

        unset($arrAttributes['created_at']);
        unset($arrAttributes['createdAt']);

        $this->entity = $entity;
        $user =  Auth::getUser();
        if (env('LOG_CORE',false) &&  $user) {
            $this->logs->create($user->getId(),$entity,'update');
        }

        unset($arrAttributes['company']);
        unset($arrAttributes['autorId']);
        unset($arrAttributes['autor']);
        unset($arrAttributes['delete']);

        if($entity && method_exists($entity,'setUpdatedAt')){
            $this->entity->setUpdatedAt(new \DateTime('now'));
        }

        $arrAttributes = $this->checkAttr($arrAttributes);

        $this->setAttributes($this->entity,$arrAttributes);

        $this->addRelatedEntity($this->entity,$arrAttributes);

        return $this->save($this->entity);


    }

    public function create($arrAttributes)
    {


        $this->entity = new $this->entityNamespace();

        $this->entity =$this->setAttributes($this->entity,$arrAttributes);

        $this->entity = $this->addRelatedEntity( $this->entity,$arrAttributes);


        if(method_exists($this->entity,'setAutor') && $this->account){
            unset($arrAttributes['autor']);
            $this->entity->setAutor($this->account);
        }

        if(method_exists($this->entity,'setCompany') && $this->account){
            unset($arrAttributes['company']);
            $this->entity->setCompany($this->company);
        }

        if(method_exists($this->entity,'setAutorId') && $this->account){
            $this->entity->setAutorId($this->account->getId());
        }

        if(method_exists($this->entity,'setCreatedAt')){
            $this->entity->setCreatedAt(new \DateTime('now'));
        }

        if(method_exists($this->entity,'setActive') && !array_key_exists('active',$arrAttributes) ){
            $this->entity->setActive(true);
        }

        if(method_exists($this->entity,'setDelete')){
            $this->entity->setDelete(false);
        }


        $this->em->persist($this->entity);

        $this->em->flush();



        if (env('LOG_CORE',false)) {
            if ($this->account) {
                $this->logs->create($this->account->getId(),$this->entity,'create');
            } else {
               // $this->logs->create('00000000-7777-0000-6666-333333333333',$this->entity,'create');
            }

        }
        $entity = $this->findOne($this->entity);

        return $entity;
    }

    public function new($arrAttributes)
    {
        $user =  Auth::getUser();

        $this->entity =$this->setAttributes( $this->entity,$arrAttributes);
        $this->entity = $this->addRelatedEntity( $this->entity,$arrAttributes);


        if(method_exists($this->entity,'setCompany') && $this->account && $this->company){
            unset($arrAttributes['company']);
            $this->entity->setCompany($this->company);
        }
        $this->setAttributes($this->entity,$arrAttributes);
        if(method_exists($this->entity,'setDelete')){
            $this->entity->setDelete(false);
        }

        if(method_exists($this->entity,'setActive')){
            $this->entity->setActive(true);
        }


        if(method_exists($this->entity,'setAutorId')  && $this->account){
            $this->entity->setAutorId($this->account);
        }

        if(method_exists($this->entity,'setCreatedAt')){
            $this->entity->setCreatedAt((new \DateTime('now')));
        }

        $this->em->persist($this->entity);
        $this->em->flush();
        $this->em->clear();

        #Логирование
        if (env('LOG_CORE',false)) {
            if ($this->account) {
                $this->logs->create($this->account->getId(),$this->entity,'new');
            } else {
                $this->logs->create(0,$this->entity,'new');
            }

        }

        return $this->entity;
    }

    /**
     * @param null $arrAttributes
     * @return mixed
     */
    public function loadNew($arrAttributes=null)
    {

        $entity =  new $this->entityNamespace();
        if($arrAttributes){
            $this->setAttributes($entity,$arrAttributes);
            $this->addRelatedEntity($entity,$arrAttributes);
        }

        if(method_exists($entity,'setAutorId') && $this->account){
            $entity->setAutorId($this->account->getId());
        }
        if(method_exists($entity,'setAutor') && $this->account){
            $entity->setAutor($this->account);
        }
        if(method_exists($this->entity,'setCompany') && $this->account){
            unset($arrAttributes['company']);
            $entity->setCompany($this->company);
        }

        if(method_exists($entity,'setCreatedAt')){
            $entity->setCreatedAt(new \DateTime('now'));
        }
        if(method_exists($entity,'setActive')){
            $entity->setActive(true);
        }

        if(method_exists($entity,'setDelete')){
            $entity->setDelete(false);
        }
        return $entity;
    }
    /**
     * @param $entity
     * @return Entity
     */
    public function copyEntity($entity) {

        return $this->load($entity);
    }

    /**
     * @param $entity
     * @param null $arrAttributes
     * @return mixed
     */
    public function load($entity, $arrAttributes=null)
    {
        if($arrAttributes){
            $this->setAttributes($entity,$arrAttributes);
            $this->loadRelatedEntity($entity,$arrAttributes);
        }

        return $entity;
    }

    /**
     * @param $entity
     * @param $arrAttributes
     */
    private function addRelatedEntity(object $entity,array $arrAttributes)
    {
        $entity = $this->addRelatedEntity_old($entity,$arrAttributes);
        foreach($arrAttributes as $parentAttribute => $values){


            $methodEntity = 'add'.ucfirst(substr($parentAttribute,0,-1));
            $getMethod = 'get'.ucfirst($parentAttribute);
            $removeMethod = 'remove'.ucfirst(substr($parentAttribute,0,-1));

            $parentClassIdOrign  = get_class($entity);
            $parentClassId = substr($parentClassIdOrign, strrpos($parentClassIdOrign, '\\') + 1);
            $childrenMethodEntityRemove = 'remove'.$parentClassId;
            $childrenMethodEntityAdd = 'add'.$parentClassId;
            $childrenMethodEntityGet = 'get'.$parentClassId."s";

            if (is_array($values) && method_exists($entity,$removeMethod)) {
                $parentEntityCollect = $entity->$getMethod();

                foreach ($parentEntityCollect as $childrenEntity) {


                    if(method_exists($childrenEntity,$childrenMethodEntityRemove)) {

                        //$childrenEntity->$childrenMethodEntityRemove($entity);
                        $entity->$removeMethod($childrenEntity);
                        //$this->em->persist($entity);
                        $this->em->persist($childrenEntity);
                        //$this->em->persist($childrenEntity);
                    }
                }
            }

            if (is_array($values) && method_exists($entity,$methodEntity) && count($values)>0) {

                foreach ($values as $parentEntity) {

                    if (is_array($parentEntity) && array_key_exists('id', $parentEntity) && Uuid::isValid($parentEntity['id']) ) {
                        $associationMappings = $this->em->getClassMetadata($this->entity::class)->associationMappings;

                        if ( array_key_exists($parentAttribute, $associationMappings)) {
                            $targetEntity = $associationMappings[$parentAttribute]['targetEntity'];
                            $parentEntity = $this->em->getRepository($targetEntity)->find($parentEntity['id']);
                            $parentMethodAdd = 'add'.ucfirst($associationMappings[$parentAttribute]['mappedBy']);
                            if ($parentEntity) {
                                $entity->$methodEntity($parentEntity);

                            }

                            if ($parentEntity && is_object($parentEntity) && !method_exists(get_class($parentEntity),$parentMethodAdd)) {
                                $parentMethodAdd = substr($parentMethodAdd,0,-1);
                            }

                            if ($parentEntity && is_object($parentEntity) && method_exists(get_class($parentEntity),$parentMethodAdd)) {
                                $parentEntity->$parentMethodAdd($entity);
                            }
                        }
                    }

                    if (is_object($parentEntity)) {
                        if(method_exists($parentEntity,$childrenMethodEntityAdd)) {
                            $parentEntity->$childrenMethodEntityAdd($entity);
                            $entity->$methodEntity($parentEntity);
                            $this->em->persist($parentEntity);
                        }
                    }
                }
            }
        }

        return $entity;
    }
    /**
     * @param $entity
     * @param $arrAttributes
     */
    private function addRelatedEntity_old($entity,$arrAttributes)
    {

        foreach($arrAttributes as $parentAttribute => $values){

            $methodEntity = 'add'.ucfirst(substr($parentAttribute,0,-1));
            $getMethod = 'get'.ucfirst($parentAttribute);
            $removeMethod = 'remove'.ucfirst(substr($parentAttribute,0,-1));

            $parentClassId  = get_class($entity);
            $parentClassId = substr($parentClassId, strrpos($parentClassId, '\\') + 1);
            $childrenMethodEntity = 'remove'.$parentClassId;
            $childrenMethodEntityAdd = 'add'.$parentClassId;
            $childrenMethodEntityGet = 'get'.$parentClassId."s";

            if (is_array($values) && method_exists($entity,$methodEntity) && count($values)>0) {
                $listGetMethod = $entity->$getMethod();
                foreach ($listGetMethod as $parentEntitySet) {

                }
                if ($listGetMethod->count() > 0) {
                    //dd($listGetMethod->count(),count($values),$getMethod,$childrenMethodEntityGet);
                }

                foreach ($values as $parentEntity) {

                    if (!$listGetMethod->contains($parentEntity)) {

                    }

                    if ($parentEntity && is_object($parentEntity) && method_exists($parentEntity,$childrenMethodEntityGet)) {
                        $listParent = $parentEntity->$childrenMethodEntityGet();

                    }
                }
            }


            if (is_array($values) && method_exists($entity,$methodEntity) && method_exists($entity,$getMethod) && count($values) == 0) {

                $listParent = $entity->$getMethod();

                foreach ($listParent as $parentEntity){
                    if (is_object($parentEntity)) {
                        $entity->$getMethod()->clear();
                        $parentClassId  = get_class($entity);
                        $parentClassId = substr($parentClassId, strrpos($parentClassId, '\\') + 1);
                        $childrenMethodEntity = 'remove'.substr($parentClassId,0,-1);
                        $childrenMethodEntitySet = 'set'.substr($parentClassId,0,-1);

                        if (!method_exists($parentEntity,$childrenMethodEntitySet)) {
                            $childrenMethodEntitySet = 'set'.$parentClassId;
                        }

                        if (!method_exists($parentEntity,$childrenMethodEntity)) {
                            $childrenMethodEntity = 'remove'.$parentClassId;
                        }

                        if (method_exists($parentEntity,$childrenMethodEntitySet)) {
                            $parentEntity->$childrenMethodEntitySet();
                            $this->em->persist($parentEntity);
                        }

                        if (method_exists($parentEntity,$childrenMethodEntity)) {
                            //$parentEntity->$childrenMethodEntity($entity);

                            $entity->$removeMethod($parentEntity);
                            $this->em->persist($parentEntity);
                        }
                    }
                }

            }

            if(is_array($values)  && method_exists($entity,$getMethod) && count($values) > 0){
                try {
                    if (is_array($entity->$getMethod())  && count($entity->$getMethod()) > 0 ) {

                            $entity->$getMethod()->clear();
                    }

                } catch (\Exception $e) {
                    dd($e);

                }
                foreach ($values as $parentEntity){
                    if (is_object($parentEntity)) {


                        $parentClassId  = get_class($entity);
                        $parentClassId = substr($parentClassId, strrpos($parentClassId, '\\') + 1);
                        $childrenMethodEntityGet = 'get'.substr($parentClassId,0,-1);
                        $childrenMethodEntity = 'remove'.substr($parentClassId,0,-1);
                        $childrenMethodEntitySet = 'set'.substr($parentClassId,0,-1);


                        if (!method_exists($parentEntity,$childrenMethodEntitySet)) {
                            $childrenMethodEntitySet = 'set'.$parentClassId;
                        }
                        if (!method_exists($parentEntity,$childrenMethodEntityGet)) {
                            $childrenMethodEntityGet = 'get'.$parentClassId;
                        }
                        if (!method_exists($parentEntity,$childrenMethodEntity)) {
                            $childrenMethodEntity = 'remove'.$parentClassId;
                        }
                        if (method_exists($parentEntity,$childrenMethodEntityGet)) {
                            //dd($entity->$getMethod()->count());
                            if ($parentEntity->$childrenMethodEntityGet() && is_array($parentEntity->$childrenMethodEntityGet())) {
                                //dd($parentEntity->$childrenMethodEntityGet());
                                $parentEntity->$childrenMethodEntity($entity);
                                $this->em->persist($parentEntity);

                            }
                        }
                    }
                }
            }

            if(is_array($values) && count($values)<>0 && method_exists($entity,$methodEntity)){

                foreach ($values as $parentEntity){
                    //dd($parentEntity);
                    $childrenEntityReflection = new \ReflectionParameter(array($this->entityNamespace, 'add'.ucfirst(substr($parentAttribute,0,-1))), 0);
                    if(method_exists($entity,$methodEntity) && is_object($parentEntity)){

                        if (is_object($childrenEntityReflection)) {

                            $entityChildrenClass = $childrenEntityReflection->getClass()->name;
                            $parentClassId  = get_class($entity);
                            $parentClassId = substr($parentClassId, strrpos($parentClassId, '\\') + 1);

                            $childrenMethodEntity = 'add'.substr($parentClassId,0,-1);
                            $childrenMethodEntitySet = 'set'.$parentClassId;
                            $childrenGetMethodEntity = 'get'.$parentClassId."s";

                            if (!method_exists($parentEntity,$childrenGetMethodEntity)) {
                                $childrenGetMethodEntity = substr($childrenGetMethodEntity,0,-1);
                            }


                            if (!method_exists($parentEntity,$childrenMethodEntity)) {
                                $childrenMethodEntity = 'add'.$parentClassId;
                            }

                            if (method_exists($parentEntity,$childrenMethodEntitySet)) {

                                $parentEntity->$childrenMethodEntitySet($entity);
                                $entity->$methodEntity($parentEntity);
                                $this->em->persist($parentEntity);
                            }

                            if (method_exists($entity,$methodEntity) && is_object($parentEntity) ) {

                                if (method_exists($parentEntity,$childrenMethodEntity)) {
                                    if (method_exists($parentEntity,$childrenGetMethodEntity)) {
                                        if ($parentEntity->$childrenGetMethodEntity() && !$parentEntity->$childrenGetMethodEntity()->contains($entity)) {
                                            $parentEntity->$childrenMethodEntity($entity);
                                            $entity->$methodEntity($parentEntity);
                                            $this->em->persist($parentEntity);
                                            $this->em->persist($entity);
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }


        return $entity;
    }

    /**
     * @param $boll
     * @return Entity
     */
    public function fixed($entityId,$boll) {
        $this->entity = $this->findMyCompnay($entityId);
        if(method_exists($this->entity,'setFixed') && method_exists($this->entity,'getFixed')){
            if ($this->entity->getFixed() != $boll) {
                $this->entity->setFixed(!$this->entity->getFixed());
                $this->em->persist($this->entity);
                $this->em->flush();
                $this->em->clear();
            }
        }
        return $this->entity;
    }
    /**
     * @param $entity
     * @param null $arrAttributes
     * @return mixed
     */
    public function save($entity)
    {
        if(method_exists($entity,'setCreatedAt') && !($entity->getCreatedAt())){
            $entity->setCreatedAt((new \DateTime('now')));
        }

        $this->em->persist($entity);
        $this->em->flush();

        if (env('LOG_CORE',false)) {
            if ($this->account) {
                $this->logs->create($this->account->getId(),$entity,'save');
            } else {
                // $this->logs->create('00000000-7777-0000-6666-333333333333',$this->entity,'create');
            }

        }

        $this->em->refresh($entity);
        return $this->find($entity);
    }

    public function logWr($entity,$mode)
    {
        if (env('LOG_CORE',false)) {
            if ($this->account) {
                $this->logs->create($this->account->getId(),$entity,$mode);
            } else {
                // $this->logs->create('00000000-7777-0000-6666-333333333333',$this->entity,'create');
            }

        }
    }
    /**
     * @param $entity
     * @param $arrAttributes
     */
    public function loadRelatedEntity($entity,$arrAttributes)
    {

        foreach($arrAttributes as $attr => $val){

            if(is_array($val)){
                foreach ($val as $id){
                    if(method_exists($entity, 'add'.$attr)){

                        $param = new \ReflectionParameter(array($this->entityNamespace, 'add'.$attr), 0);


                        if(!is_null($param->getClass())){
                            $entityClass = $param->getClass()->name;

                            $value = $this->findExternal($entityClass,$id);
                            $entity->{'add'.$attr}($value);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param $entityId
     * @return null|object
     */
    public function findExternal($entityNamespace,$entityId)
    {
        $entityLoaded = $this->em->find($entityNamespace, $entityId);
        if ( $entityLoaded ) {
        return $entityLoaded;
        }

        return null;

    }


    public function setAttributes($entity,$arrAttributes)
    {
        if (!is_array($arrAttributes)) {
            return null;
        }

        foreach($arrAttributes as $attr => $val){

            if(method_exists($entity, 'set'.ucfirst($attr))){
                $param = new \ReflectionParameter(array(get_class($entity), 'set'.ucfirst($attr)), 0);
                $rp = new ReflectionProperty($this->entity, $attr);
                $newRelatedEntity  = new $entity;
                if ($attr == 'brithAt') {

                    //dd($rp->getDocComment(),$rp,$param,$param->getClass(),get_class($param));
                    //$start = new \DateTime($val['start']);
                }



                if (stripos($rp->getDocComment(),'DateTime') && !($val instanceof  \DateTimeImmutable)) {
                    try {
                        $val = new \DateTime($val);
                    } catch (\Exception $e) {
                        abort(500,'Поле ['.$attr.'] тип DateTime, формат ГГГГ-ММ-ДД');
                    }
                }

                if(!is_null($param->getClass())){
                    $entityClass = $param->getClass()->name;
                    if ($val) {
                        $value = $this->findExternal($entityClass,$val);
                        $entity->{'set'.ucfirst($attr)}($value);

                    } else {
                        $entity->{'set'.ucfirst($attr)}(null);
                    }
                }
                else{
                    $entity->{'set'.ucfirst($attr)}($val);
                }
            }
        }
        return $entity;
    }

    public function parseOptions($options=[]): array
    {


        $orderBy = [];
        $filter = [];
        $pagginate = [
            'page'=>1,
            'limit'=>$this->getDefaultLimit()
        ];

        if ($options == []) {
            return [
                "orderBy" => null,
                "filter" => $filter,
                "pagginate" => $pagginate
            ];
        }



        if ($options && array_key_exists('orderBy', $options) && $options['orderBy']) {

            $orderBy = $this->checkAttr($options['orderBy']);

        }

        if ($options && array_key_exists('filter', $options)) {

            $filter = $this->checkAttr($options['filter']);

        }

        if ($options && array_key_exists('pagginate', $options) && $options['pagginate']) {
            $pagginate = $options['pagginate'];
        }

        return [
            "orderBy" => $orderBy,
            "filter" => $filter,
            "pagginate" => $pagginate
        ];
    }

    public function qbSetFilterParametr($qb,$arrAttrValue) {

        if (!$arrAttrValue) {
            return $qb;
        }


        foreach ($arrAttrValue as $attr => $val)
        {
            $line = 0;
            $methodGet = 'get'.ucfirst($attr);
            if(method_exists($this->entity, $methodGet) && $val){

                $rp = new ReflectionProperty($this->entity, $attr);



                if (is_string($val)) {
                    $val = str_replace(" ","%",$val );
                }


                if (stripos($rp->getDocComment(),'integer')) {
                    if ($line != 0 && $line<count($arrAttrValue)) {
                        $qb->orWhere($qb->expr()->eq("thisEntity.$attr", $val));
                    } else {
                        $qb->andwhere($qb->expr()->eq("thisEntity.$attr", $val));
                    }

                }

                if (stripos($rp->getDocComment(),'boolean')) {

                    $val = $val ? "true" : "false";
                    if ($line != 0 && $line<count($arrAttrValue)) {
                        $qb->andWhere("thisEntity.$attr = $val");
                    } else {
                        $qb->andwhere("thisEntity.$attr = $val");
                    }
                }

                if (stripos($rp->getDocComment(),'string')) {

                    if ($val) {


                        $qb->andWhere("LOWER(thisEntity.$attr) LIKE LOWER('%$val%')");
                    }
                }

                if (stripos($rp->getDocComment(),'DateTime') && $val) {
                    if (is_array($val) && array_key_exists('start',$val) && array_key_exists('end',$val)) {

                        try {
                            $start = new \DateTime($val['start']);
                        } catch (\Exception $e) {
                            abort('500','Ошибка формата даты');
                        }

                        try {
                            $end = new \DateTime($val['end']);
                        } catch (\Exception $e) {
                            abort('500','Ошибка формата даты');
                        }

                        $start = $start->modify('midnight')->format('Y-m-d H:i:s');
                        $end =$end->modify('tomorrow')->format('Y-m-d H:i:s');

                        $qb->andWhere($qb->expr()->between('thisEntity.'.$attr,"'$start'","'$end'"));
                    }

                    if (is_string($val)) {
                        try {
                        $time = new \DateTime($val);
                        } catch (\Exception $e) {
                            abort('500','Ошибка формата даты');
                        }
                        $start = $time->modify('midnight')->format('Y-m-d');
                        $qb->andWhere($qb->expr()->eq('thisEntity.'.$attr,"'$start'"));
                    }


                }

                if (stripos($rp->getDocComment(),'uuid')) {
                    //dd($rp,Uuid::isValid($val),$val);
                    if (Uuid::isValid($val)) {

                        if ($line != 0 && $line<count($arrAttrValue)) {
                            $qb->orWhere("thisEntity.$attr = '$val'");
                        } else {
                            $qb->andWhere("thisEntity.$attr = '$val'");
                        }
                    }
                }

                $line++;
            } else {
                unset($arrAttrValue[$attr]);
            }
        }

        return $qb;
    }
    public function resultQueryData( $query,$options=[]) {

        $pagginate=[];
        $orderBy=[];
        if ($options && array_key_exists('pagginate',$options)) {
            $pagginate = $options['pagginate'];
        }
        if (!array_key_exists('filter',$options)) {
            $options['filter'] = [];
        }

        if ($options && array_key_exists('orderBy',$options)) {
            $orderBy = $this->checkAttr($options['orderBy']);
        }

        if ($options &&array_key_exists('page',$pagginate)) {
            $page = $pagginate['page'];
        } else {
            $page = 1;
        }

        if ($options && array_key_exists('limit',$pagginate)) {
            $limit = $pagginate['limit'];
        } else {
            $limit = $this->getDefaultLimit();
        }

        $companyId = auth()->user()->getCompany();
        //$query->setParameter('company', $companyId);


        $paginator = new Paginator($query, $fetchJoinCollection = false);
        $count = count($paginator);
        $pages = ceil($count/$limit);

        if ($page>$pages) {
            $page = $pages;
        }
        if ($page<=1) {
            $page = 1;
        }
        $options = [
            'pagginate'=>[
                'pages'=>$pages,
                'limit'=>$limit,
                'page'=>$page,
                'count'=>$count
            ],
            'orderBy'=>$orderBy,
            'filter'=>$options['filter']
        ];
        $firstResult = ($page-1)*$limit;
        return  [
            'data'=>$query->setFirstResult($firstResult)->setMaxResults($limit)->getResult(),
            'options'=>$options
        ];
    }

}
