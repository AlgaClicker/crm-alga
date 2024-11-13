<?php

namespace Infrastructure\Repositories\Account;
use Doctrine\ORM\EntityManager;
use Infrastructure\Repositories\AbstractRepository;
use Domain\Entities\Subscriber\AccountOptions;
use Domain\Contracts\Repository\AccountOptionsRepositoryContract;

class OptionsRepository extends AbstractRepository implements AccountOptionsRepositoryContract
{
    public function __construct(EntityManager $em, AccountOptions $entity)
    {
        parent::__construct($em, $entity);
    }

}
