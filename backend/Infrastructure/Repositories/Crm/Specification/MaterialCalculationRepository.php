<?php

namespace Infrastructure\Repositories\Crm\Specification;
use Doctrine\ORM\EntityManager;
use Domain\Contracts\Repository\Crm\SpecificationMaterialRepositoryContracts;
use Domain\Contracts\Repository\Crm\SpecificationRepositoryContracts;
use Illuminate\Support\Collection;
use Infrastructure\Repositories\AbstractRepository;
use Domain\Contracts\Repository\Crm\Specification\MaterialCalculationRepositoryContracts;
use Domain\Entities\Business\Objects\Specification;
use Domain\Entities\Business\Objects\Specification\Material\Calculation;

/**
 * MaterialCalculation
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MaterialCalculationRepository extends AbstractRepository implements MaterialCalculationRepositoryContracts
{
    protected SpecificationRepositoryContracts $specificationRepository;
    protected SpecificationMaterialRepositoryContracts $specificationMaterialRepository;

    public function __construct(
        EntityManager $em,
        Calculation $entity,
        SpecificationMaterialRepositoryContracts $specificationMaterialRepository
    ) {
        $this->specificationMaterialRepository = $specificationMaterialRepository;
        parent::__construct($em, $entity);
    }


    public function setСalculation(Specification $specification, array $materials) {
        $listMaterialresult = new Collection();

        foreach ($materials as $materialData) {

            $material =  $this->specificationMaterialRepository->findMyCompnay($materialData['id']);
            $newCalculation = new Calculation();
            //return $material->getSpecification();
            if ($material->getSpecification()->contains($specification)) {
                $newCalculation->setSpecificationMaterial($material);
                $newCalculation->setAmount($materialData['amount']);
                $newCalculation->setSpecification($specification);
                $newCalculation->setAutor(auth()->user());
                $newCalculation->setCreatedAt(new \DateTimeImmutable("now"));
                if (array_key_exists('description',$materialData)) {
                    $newCalculation->setDescription($materialData['description']);
                }
                $this->em->persist($newCalculation);
                $this->em->flush();
                $listMaterialresult->add($newCalculation);
            }

        }

        return $listMaterialresult->all();
    }
}
