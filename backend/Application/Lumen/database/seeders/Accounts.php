<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Domain\Entities\Subscriber\Account;
use Domain\Contracts\Repository\AccountsRepositoryContracts;
use Doctrine\ORM\EntityManager;
use Illuminate\Support\Facades\Hash;

class Accounts extends Seeder
{
    private AccountsRepositoryContracts $accountsRepository;
    private $em;
    public function __construct(AccountsRepositoryContracts $accountsRepository, EntityManager $em)
    {
        $this->accountsRepository = $accountsRepository;
        $this->em = $em;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($perfix=1;$perfix<=5;$perfix++)
        {
            $pr = strval($perfix);
            $this->createAccount($pr);
            $pr = $pr.$pr;
            $this->createAccount($pr);

        }

    }

    public function createAccount($prefix)
    {
        $account = new Account();
        $username = "user-".$prefix;
        $account->setUsername($username);
        $password = "pass".$prefix;
        $account->setPassword($password);
        $account->setEmail($username."@host.local");
        $account->setCreatedAt(new \DateTime());
        $this->em->persist($account);
        $this->em->flush();
    }
}
