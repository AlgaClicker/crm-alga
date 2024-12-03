<?php

namespace Infrastructure\Repositories\Business;
use Doctrine\ORM\EntityManager;
use Infrastructure\Repositories\AbstractRepository;
use Domain\Contracts\Repository\Business\StockRepositoryContracts;
use Domain\Entities\Business\Company\Stock;
/**
 * CommentsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class StockRepository extends AbstractRepository implements StockRepositoryContracts
{
    protected $em;
    public function __construct(EntityManager $em, Stock $entity)
    {
        parent::__construct($em, $entity);
    }

    public function listStocks() {
        $qb = $this->em->createQueryBuilder();

        $qb = $qb->select('st')->from(get_class(new Stock()),'st');
        $qb = $qb->where($qb->expr()->isNull('st.specification'));
        $qb = $qb->andWhere($qb->expr()->eq('st.company','?1'));

        $query = $this->em->createQuery($qb->getDQL());
        $query->setParameter(1, auth()->user()->getCompany());

        return  $query->getResult();
    }
}
