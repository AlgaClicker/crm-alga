<?php
namespace Domain\Services\Directory;
use Domain\Contracts\Services\AccountServiceContracts;
use Domain\Contracts\Services\Directory\ProfessionServiceContract;
use Domain\Contracts\Repository\Directory\ProfessionRepositoryContract;
use Domain\Contracts\Repository\Directory\BankAccountRepositoryContract;
use Domain\Services\AbstractService;
use Carbon\Carbon;
use Core\Jobs\LoadBankCbrJob;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

class ProfessionService extends AbstractService implements ProfessionServiceContract
{
    private  ProfessionRepositoryContract $professionRepository;

    public function __construct(
        ProfessionRepositoryContract $professionRepository
    ){
        $this->professionRepository = $professionRepository;
        parent::__construct($professionRepository);
    }

    public function create($arrKeyValue) {
        return $this->professionRepository->create($arrKeyValue);
    }

}
