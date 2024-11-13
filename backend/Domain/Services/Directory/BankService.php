<?php
namespace Domain\Services\Directory;
use Domain\Contracts\Services\AccountServiceContracts;
use Domain\Contracts\Services\Directory\BankServiceContract;
use Domain\Contracts\Repository\Directory\BankRepositoryContract;
use Domain\Contracts\Repository\Directory\BankAccountRepositoryContract;
use Domain\Services\AbstractService;
use Carbon\Carbon;
use Core\Jobs\LoadBankCbrJob;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
class BankService extends AbstractService implements BankServiceContract
{
    private BankRepositoryContract $bankRepository;
    private BankAccountRepositoryContract $bankAccountRepository;
    private AccountServiceContracts $accountService;

    public function __construct(
        BankRepositoryContract $bankRepository,
        AccountServiceContracts $accountService,
        BankAccountRepositoryContract $bankAccountRepository
    ){
        $this->bankRepository = $bankRepository;
        $this->accountService = $accountService;
        $this->bankAccountRepository = $bankAccountRepository;
        parent::__construct($bankRepository);
    }


    public function getBankAccounts($idBank) {
        return $this->bankAccountRepository->findAllBy(['bank'=>$idBank]);
    }

    public function getBankBik(string $bik) {
        return $this->bankRepository->findBy(['bik'=>$bik]);
    }


    public function getBankAccount($idBankAccount) {
        return $this->bankAccountRepository->find($idBankAccount);
    }

    public function loadBankiCbr(String $nameFile) {
        $ts = microtime(true);
        //$this->loadBankiCbrXml($nameFile);
        dispatch(new LoadBankCbrJob(str($nameFile)));
        if ($this->accountService->getThisRole()->getService() == 'administrator') {

        } else {
           // Log::info("Error Acces Load BNKS");
        }
        //$job = LoadBankCbrJob::dispatch($this->bankRepository);
       // dispatch($job);
        $spent = microtime(true) - $ts;


        return $spent ;
    }


    public function loadBankiCbrXml($file) {
        # Загрузка справочника банков
        #
        #https://cbr.ru/vfs/mcirabis/BIKNew/20230517ED01OSBR.zip
        #
        Log::info("loadBankiCbrXml: ".Storage::path($file));

        $file = Storage::path($file);
        if (!file_exists($file)) {
            throw new ApplicationException('Error file load',500);
        }
        $objXmlDocument = simplexml_load_file($file);

        if ($objXmlDocument === FALSE) {
            echo "There were errors parsing the XML file.\n";
            foreach(libxml_get_errors() as $error) {
                throw new ApplicationException($error->message,500);
            }

        }

        $objJsonDocument = json_encode($objXmlDocument);
        $arrOutput = json_decode($objJsonDocument, TRUE);
        $listBank = $arrOutput['BICDirectoryEntry'];
        $count = 0 ;
        $oldCount = 0 ;
        $countNewBank = 0 ;
        $countUpdateBank = 0;
        $showLines = 100;
        foreach ($listBank  as $itemBank) {
            $count++;
            $thisBankId = '';
            $thisNewBank = null;
            $bik = $itemBank['@attributes']['BIC'];
            $participantInfo = $itemBank['ParticipantInfo']['@attributes'];

            $thisNewBank = $this->bankRepository->findOneby(['bik'=>$bik]);

            $bank['bik'] = $bik;
            $bank['name'] = $participantInfo['NameP'];
            $bank['fullname'] = $participantInfo['NameP'];
            $address = "";
            if (array_key_exists('Ind',$participantInfo)) {
                $address .= $participantInfo['Ind']." ";
            }

            if (intdiv($count,$showLines) != $oldCount) {
                Log::info("Loading Bank lines:".$count);
                Log::info("News bank:".$countNewBank);
                Log::info("Updated bank:".$countUpdateBank);
            }

            $oldCount =  intdiv($count,$showLines);

            if (array_key_exists('Tnp',$participantInfo)) {
                $address .= $participantInfo['Tnp'].".".$participantInfo['Nnp'].", ";
            }
            if (array_key_exists('Adr',$participantInfo)) {
                $address .= $participantInfo['Adr'];
            }
            $bank['address'] = $address;
            $bank['dateIn'] = $participantInfo['DateIn'];

            if ($thisNewBank) {

                $thisBankId = $thisNewBank->getId();
                $thisNewBank = $this->bankRepository->update($thisNewBank,$bank);
                $countUpdateBank++;
            } else {

                $thisNewBank = $this->bankRepository->loadNew($bank);
                $thisNewBank = $this->bankRepository->save($thisNewBank);
                $thisBankId = $thisNewBank->getId();
                $countNewBank++;
            }



            if (array_key_exists('Accounts',$itemBank)) {
                $accounts= $itemBank['Accounts'];
                foreach ($accounts as $account) {
                    //
                    $acc = $account;
                    if (array_key_exists('@attributes',$account)) {
                        $account = $account['@attributes'];
                    }

                    if (array_key_exists('Account',$account)) {
                        $accountData['account'] = $account['Account'];
                        $accountData['bank'] = $thisNewBank->getId() ;
                        $accountData['regulationAccountType'] = $account['RegulationAccountType'];
                        $accountData['ck'] = $account['CK'];
                        $accountData['accountCBRBIC'] = $account['AccountCBRBIC'];
                        $accountData['dateIn'] = new \DateTimeImmutable($account['DateIn']);
                        $accountData['accountStatus'] = $account['AccountStatus'];


                        if (!$this->bankAccountRepository->findOneby(['account' => $accountData['account']])) {


                            $bankAccount =  $this->bankAccountRepository->create($accountData);


                        }
                    }

                }
            }

        }

    }
}
