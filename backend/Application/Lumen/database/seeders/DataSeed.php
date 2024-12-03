<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Domain\Entities\Subscriber\Account;
use Domain\Entities\Business\Company\Company;
use Domain\Entities\Subscriber\Roles;
use Doctrine\ORM\EntityManager;
use DateTime;

class DataSeed extends Seeder
{
    private $em;
    private $adminRole;
    private $account;
    private $company;
    public function __construct(
        EntityManager $em
    ){
        $this->em = $em;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
            $this->createRoles();
            if (env('APP_ENV',false) == 'local') {
                $this->createCompany();
                $this->createAccount();
            }

    }


    public function createRoles() {

        $role = new Roles();
        $role->setName('Администратор');
        $role->setService("administrator");
        $role->setCreatedAt(new DateTime());
        $this->em->persist($role);
        $this->em->flush();
        $this->adminRole = $role;

        $role = new Roles();
        $role->setName('Пользователь');
        $role->setService("polzovatel");
        $role->setCreatedAt(new DateTime());
        $this->em->persist($role);
        $this->em->flush();


        $role = new Roles();
        $role->setName('Снабжение');
        $role->setService("snabzenie");
        $role->setCreatedAt(new DateTime());
        $this->em->persist($role);
        $this->em->flush();

        $role = new Roles();
        $role->setName('Управление');
        $role->setService("upravlenie");
        $role->setCreatedAt(new DateTime());
        $this->em->persist($role);
        $this->em->flush();

        $role = new Roles();
        $role->setName('Мастер');
        $role->setService("master");
        $role->setCreatedAt(new DateTime());
        $this->em->persist($role);
        $this->em->flush();

        $role = new Roles();
        $role->setName('Бухгалтерия');
        $role->setService("buxgalteriia");
        $role->setCreatedAt(new DateTime());
        $this->em->persist($role);
        $this->em->flush();

        $role = new Roles();
        $role->setName('Склад');
        $role->setService("sklad");
        $role->setCreatedAt(new DateTime());
        $this->em->persist($role);
        $this->em->flush();

        $role = new Roles();
        $role->setName('Менеджер объекта');
        $role->setService("manageobject");
        $role->setCreatedAt(new DateTime());
        $this->em->persist($role);
        $this->em->flush();


    }

    public function createCompany() {
        $this->company = new Company();
        $this->company->setCreatedAt(new DateTime());
        $this->company->setName("Наша компания");
        $this->company->setFullname("Наша компания");
        $this->company->setInn("59000059");
        $this->em->persist($this->company);
        $this->em->flush();
    }

    public function createAccount()
    {
        $this->account = new Account();
        $username = "admin";
        $this->account->setUsername($username);
        $password = "adminpass";
        $this->account->setPassword($password);
        $this->account->setDelete(false);
        $this->account->setRoles($this->adminRole);
        $this->account->setEmail($username."@host.local");
        $this->account->setCreatedAt(new DateTime());
        $this->account->setCompany($this->company);
        $this->em->persist($this->account);
        $this->em->flush();

        return $this->account;
    }
}
