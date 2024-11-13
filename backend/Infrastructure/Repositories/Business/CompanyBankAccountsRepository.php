<?php

namespace Infrastructure\Repositories\Business;
use Doctrine\ORM\EntityManager;
use Infrastructure\Repositories\AbstractRepository;
use Domain\Contracts\Repository\Business\CompanyBankAccountsRepositoryContracts;
use Domain\Entities\Business\Company\BankAccounts;


class CompanyBankAccountsRepository extends AbstractRepository implements CompanyBankAccountsRepositoryContracts
{
    public function __construct(EntityManager $em, BankAccounts $entity)
    {
        parent::__construct($em, $entity);
    }
}
