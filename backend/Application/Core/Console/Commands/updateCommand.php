<?php
namespace Core\Console\Commands;
use Event;
use Illuminate\Console\Command;
use Core\Events\NotificationEvent;
use Domain\Entities\Services\Notification;
use Domain\Contracts\Services\Business\BusinessServiceContracts;
use Domain\Contracts\Services\Directory\BankServiceContract;
use Domain\Contracts\Repository\Directory\BankRepositoryContract;
use Domain\Contracts\Repository\Business\CompanyRepositoryContracts;
use Domain\Contracts\Repository\RolesRepositoryContracts;
use Illuminate\Support\Str;

class updateCommand extends Command
{
    protected $signature = "app:update {version}";
    protected $description = "Index search building";
    public function handle()
    {
        $version = $this->argument('version');
       // echo "$version \r\n";
        $bankService = app(BankServiceContract::class);
        $bankRepository = app(BankRepositoryContract::class);
        $businessService = app(BusinessServiceContracts::class);
        $companyRepository = app(CompanyRepositoryContracts::class);
        $rolesRepository = app(RolesRepositoryContracts::class);
        switch ($version) {
            case "0.0.1":
                echo "0.0.1 \r\n";
                $listCompany = $companyRepository->findAll();

                foreach ($listCompany as $company) {

                    if ($company->getRoles()->count() == 0 && $company->getId()) {
                        $roles = ["Управление","Мастер","Снабжение","Бухгалтерия","Гость","Менеджер объекта"];
                        foreach ($roles as $role) {
                            $arrAttr['name'] = trim($role);
                            $arrAttr['service'] = Str::slug($arrAttr['name']);
                            $arrAttr['company'] = $company->getId();
                            $role = $rolesRepository->create($arrAttr);
                            $company->addRole($role);
                            $companyRepository->save($company);
                            //$company->addRole($role);
                        }

                    }
                }


                break;

            default:
                echo "No Updates  \r\n";
                break;
        }

    }
}

