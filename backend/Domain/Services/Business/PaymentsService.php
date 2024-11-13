<?php

namespace Domain\Services\Business;
use Core\Exceptions\ApplicationException;

use Domain\Contracts\Services\Business\PaymentsServiceContract;
use Domain\Services\AbstractService;
use Domain\Contracts\Repository\Payments\PayBrigadeRepositoryContract;
use Domain\Contracts\Repository\Payments\InvoicesRepositoryContract;
use Domain\Contracts\Services\Crm\SpecificationsServiceContracts;
use Domain\Contracts\Services\Crm\BrigadeServiceContract;
use Domain\Contracts\Services\Directory\PartnersServiceContract;
use Domain\Contracts\Services\Business\CompanyServiceContract;

use Domain\Contracts\Services\NotificationServiceContracts;

use Kily\Tools1C\ClientBankExchange\Parser;
use Kily\Tools1C\ClientBankExchange\Order;
use Illuminate\Http\UploadedFile;
use DateTimeImmutable;
use Illuminate\Support\Collection;

class PaymentsService extends AbstractService implements  PaymentsServiceContract
{
    private PayBrigadeRepositoryContract $payBrigadeRepository;
    private InvoicesRepositoryContract $invoicesRepository;
    protected SpecificationsServiceContracts $specificationsService;
    protected BrigadeServiceContract $brigadeService;
    protected PartnersServiceContract $partnersService;
    private CompanyServiceContract $companyService;
    private NotificationServiceContracts $notificationService;


    public function __construct(
        PayBrigadeRepositoryContract $payBrigadeRepository,
        InvoicesRepositoryContract $invoicesRepository,
        SpecificationsServiceContracts $specificationsService,
        BrigadeServiceContract $brigadeService,
        PartnersServiceContract $partnersService,
        CompanyServiceContract $companyService,
        NotificationServiceContracts $notificationService
    )
    {
        $this->invoicesRepository = $invoicesRepository;
        $this->payBrigadeRepository = $payBrigadeRepository;
        $this->specificationsService = $specificationsService;
        $this->brigadeService = $brigadeService;
        $this->partnersService = $partnersService;
        $this->companyService = $companyService;
        $this->notificationService = $notificationService;

        parent::__construct($this->invoicesRepository);
    }

    public function invoiceList($options=[]) {

        $this->repository =  $this->invoicesRepository;

        return $this->_getAllBy([],$options);
    }

    public function invoiceOnly($id) {
        return $this->_getById($id);
    }

    public function invoiceCreate($arrKeyValue) {
        $dateInvoice = new \DateTimeImmutable($arrKeyValue['date']);
        $arrKeyValue['date'] = $dateInvoice;
        $this->repository =  $this->invoicesRepository;
        if (array_key_exists("amount",$arrKeyValue)) {
            $arrKeyValue['amount'] = str_replace(",",".",$arrKeyValue['amount']);
        }

        if (array_key_exists("specification",$arrKeyValue) && $arrKeyValue['specification']) {

        } else {
            unset($arrKeyValue['specification']);
        }

        $invoice = $this->_create($arrKeyValue);
        $this->notificationService->sendNotificationSystem("Новый ".$invoice->getType()." платеж",'Сумма '.$invoice->getAmount()." от ".$dateInvoice->format('d/m/Y') );

            return $invoice;
    }




    public function invoiceUpdate($arrKeyValue) {
        $arrKeyValue['date'] = new \DateTimeImmutable($arrKeyValue['date']);
        $this->repository =  $this->invoicesRepository;
        if (array_key_exists("specification",$arrKeyValue) && $arrKeyValue['specification']) {

        } else {
            unset($arrKeyValue['specification']);
        }
        $this->repository =  $this->invoicesRepository;
        $invoice = $this->invoicesRepository->findMyCompnay($arrKeyValue['id']);
        return  $this->_updateById($invoice,$arrKeyValue);
    }

    public function invoiceDelete($id) {
        $this->repository =  $this->invoicesRepository;


        return $this->_delete($id);
    }

    private function importBankTochka(UploadedFile $file) {
        $file = file($file->getRealPath());
        $text = new Collection();
        for ($i=1;$i<count($file);$i++) {
            $newInvoice = [];
            $lineData = explode(";",$file[$i]);
            $newInvoice['number'] = $lineData[4];
            #================================================Исходящий платеж=======================================

            if ($lineData[2] && new DateTimeImmutable($lineData[2])) {
                //$newInvoice['partnerInn'] = $lineData[21];
                $newInvoice['type'] = "out";
                $newInvoice['date'] = (new DateTimeImmutable($lineData[2]));
                //$newInvoice['bik'] = $lineData[15];
                //$newInvoice['account']= ;
                $accountCompany = $this->companyService->checkBankAccountCompany($lineData[12]);

                if (!$accountCompany) {
                    $accountCompany = $this->companyService->createBankAccountCompany(intval($lineData[15]),$lineData[12]);
                }

                $newInvoice['companyBankAccount'] = $accountCompany;
                $partner = $this->partnersService->_getBy(['inn'=>$lineData[21]]);
                if (!$partner && $lineData[21]) {
                    $partner = $this->partnersService->newPartnerInn($lineData[21]);
                }
                if ($partner) {
                    $newInvoice['partner'] = $partner;
                    $bikBankPartner = $lineData[23];
                    //dd($lineData[20],gettype($lineData[20]));
                    $partnerAccount =  $this->partnersService->checkPartnerAccount($partner,$bikBankPartner,$lineData[20]);

                    if (!$partnerAccount) {
                        $partnerAccount = $this->partnersService->addPartnerAccount($partner,$bikBankPartner,$lineData[20]);
                    }
                    $newInvoice['partnerBankAccount'] = $partnerAccount;
                }
            }

            #=========================================Входящий платеж===============================================
            if ($lineData[3] && new DateTimeImmutable($lineData[3])) {
                $newInvoice['type'] = "in";
                $newInvoice['date'] = (new DateTimeImmutable($lineData[3]));
                $partner = $this->partnersService->_getBy(['inn'=>$lineData[13]]);

                $accountCompany = $this->companyService->checkBankAccountCompany($lineData[20]);

                if (!$accountCompany) {
                    $accountCompany = $this->companyService->createBankAccountCompany(intval($lineData[23]),$lineData[20]);
                }

                $newInvoice['companyBankAccount'] = $accountCompany;

                if (!$partner && $lineData[21]) {
                    $partner = $this->partnersService->newPartnerInn($lineData[13]);
                }
                if ($partner) {
                    $newInvoice['partner'] = $partner;
                    $bikBankPartner = $lineData[15];
                    $partnerAccount =  $this->partnersService->checkPartnerAccount($partner,$bikBankPartner,$lineData[12]);
                    if (!$partnerAccount) {
                        $partnerAccount = $this->partnersService->addPartnerAccount($partner,$bikBankPartner,$lineData[12]);
                    }
                    $newInvoice['partnerBankAccount'] = $partnerAccount;
                }
            }

            $newInvoice['description'] = $lineData[10];
            $newInvoice['amount'] = str_replace(',','.',$lineData[6]);

            # В том числе НДС 20%
            if (mb_eregi('20%',$lineData[10]) && mb_eregi('НДС',$lineData[10])) {
                $newInvoice['tax'] = 20;
                $newInvoice['taxAmount'] = round(($newInvoice['amount'] / 120) * $newInvoice['tax'],2);
            }

            # В том числе НДС 10%
            if (mb_eregi('10%',$lineData[10]) && mb_eregi('НДС',$lineData[10])) {

                $newInvoice['tax'] = 10;
                $newInvoice['taxAmount'] = round(($newInvoice['amount'] / 110) * $newInvoice['tax'],2);
            }

            if (!$this->invoicesRepository->findByMyCompnay($newInvoice)) {
               // $newInvoiceEntity =  $this->invoicesRepository->create($newInvoice);
                //$newInvoiceEntity = $this->invoicesRepository->save($newInvoiceEntity);
                $text->add($newInvoiceEntity);
            }


        }
        return  $text->all();
    }

    private function importBankSberbank(UploadedFile $file) {
        $file = file($file->getRealPath());
        $text = new Collection();
        for ($i=1;$i<count($file);$i++) {
            $newInvoice = [];
            $lineData = explode(";",$file[$i]);

        }
        return $text->all();
    }

    public function  invoiceImport(UploadedFile $file,$type) {
        if ($type === '1c') {
            $p = new Parser($file->getRealPath());
            $accountCompany = $p->general['РасчСчет'];
            $accountCompany = $this->companyService->checkBankAccountCompany($accountCompany);


            //dd($accountCompany,$p->general,$p->filter,$p->remainings,$p->documents); // general info
            $myInn = $this->companyService->getMyCompany()->getInn();
            $invoicesList = new Collection();

            foreach ($p->documents as $document) {
                //dd($document);
                $newInvoice=[];
                $newInvoice['date'] = (new DateTimeImmutable($document['Дата']));
                $newInvoice['number'] = $document['Номер'];
                $newInvoice['description'] = $document['НазначениеПлатежа'];

                $payerInn = $document['ПлательщикИНН'];
                $recipientInn = $document['ПолучательИНН'];


                $newInvoice['amount'] = str_replace(',','.',$document['Сумма']);

                # В том числе НДС 20%
                if (mb_eregi('20',$newInvoice['description']) && mb_eregi('НДС',$newInvoice['description']) && mb_eregi('%',$newInvoice['description'])) {
                    $newInvoice['tax'] = 20;
                    $newInvoice['taxAmount'] = round(($newInvoice['amount'] / 120) * $newInvoice['tax'],2);
                } elseif (mb_eregi('10%',$newInvoice['description']) && mb_eregi('НДС',$newInvoice['description'])) { # В том числе НДС 10%
                    $newInvoice['tax'] = 10;
                    $newInvoice['taxAmount'] = round(($newInvoice['amount'] / 110) * $newInvoice['tax'],2);
                }

                if ($document['ПлательщикИНН'] == $myInn) {
                    $newInvoice['type'] = "out";
                    if (!$accountCompany) {
                        $accountCompany = $this->companyService->createBankAccountCompany(intval($document['ПлательщикБИК']),$document['ПлательщикРасчСчет']);
                    }


                    $newInvoice['companyBankAccount'] = $accountCompany;

                    if ($document['ПолучательИНН'] && $accountCompany) {
                        $partner = $this->partnersService->_getBy(["inn"=>$document['ПолучательИНН']]);
                        if (!$partner) {
                            $partner = $this->partnersService->newPartnerInn($document['ПолучательИНН']);
                        }
                        if ($partner) {
                            $newInvoice['partner'] = $partner;
                        }

                        $partnerAccount =  $this->partnersService->checkPartnerAccount($partner,$document['ПолучательБИК'],$document['ПолучательРасчСчет']);

                        if (!$partnerAccount && $partner) {

                            $partnerAccount = $this->partnersService->addPartnerAccount($partner,$document['ПолучательБИК'],$document['ПолучательРасчСчет']);
                        }

                        if ($partnerAccount && $partner) {
                            $newInvoice['partnerBankAccount'] = $partnerAccount;
                        }

                    }
                }


                if ($recipientInn == $myInn) {
                    $newInvoice['type'] = "in";
                    //dd($document);
                    if (!$accountCompany) {
                        $accountCompany = $this->companyService->createBankAccountCompany(intval($document['ПлательщикБИК']),$document['ПлательщикРасчСчет']);
                    }
                    $newInvoice['companyBankAccount'] = $accountCompany;
                    $partner = $this->partnersService->_getBy(["inn"=>$document['ПлательщикИНН']]);
                    $partnerAccount =  $this->partnersService->checkPartnerAccount($partner,$document['ПлательщикБИК'],$document['ПлательщикРасчСчет']);
                    if (!$partnerAccount && $partner) {
                        $partnerAccount = $this->partnersService->addPartnerAccount($partner,$document['ПлательщикБИК'],$document['ПлательщикРасчСчет']);
                    }
                    if ($partnerAccount) {
                        $newInvoice['partnerBankAccount'] = $partnerAccount;
                    }

                }

                if ($accountCompany && !$this->invoicesRepository->findByMyCompnay($newInvoice)) {
                    $newInvoice['autor'] = auth()->user();

                    $newInvoiceEntity =  $this->invoicesRepository->create($newInvoice);
                    //$newInvoiceEntity = $this->invoicesRepository->save($newInvoiceEntity);
                    $invoicesList->add($newInvoiceEntity);
                } else {
                    $invoice = $this->invoicesRepository->findByMyCompnay($newInvoice);
                    if ($invoice) {
                        $invoicesList->add(["invoice"=>$invoice->getId()->serialize(),'date'=>$invoice->getCreatedAt(),"loaded"=>true]);
                    }

                }

            }
            $this->notificationService->sendNotificationSystemAccount(auth()->user(),"Загрузка платежных документов тз 1С", "Загрузка прошла успешно. Загруженно строк:".$invoicesList->count());

            return $invoicesList->all();
        }
        if ($type === 'tochka') {
          // return $this->importBankTochka($file);
        }
        if ($type === 'sber') {
          //  return $this->importBankSberbank($file);
        }


        return false;
    }


    public function invoiceFixed($id) {
        $this->repository =  $this->invoicesRepository;
    }

    public function invoiceUnFixed($id) {
        $this->repository =  $this->invoicesRepository;
    }

    public function brigadePayList($id=null,$options=[]) {
        $this->repository =  $this->payBrigadeRepository;

        if ($id) {
            return $this->_getAllBy(['brigade'=>$id],$options);
        }

        return $this->_getAllBy([],$options);
    }

    public function brigadePayCreate($arrKeyValue) {
        $this->repository =  $this->payBrigadeRepository;
         $date = new \DateTimeImmutable($arrKeyValue['date']);
        $arrKeyValue['date'] = $date;
        $brigade = $this->brigadeService->_getById($arrKeyValue['brigade']);
        $arrKeyValue['brigade'] = $brigade;
        if (array_key_exists('specification',$arrKeyValue)) {
            $arrKeyValue['specification'] = $this->specificationsService->_getById($arrKeyValue['specification']);
        }
        /*
        if (count($this->payBrigadeRepository->searchPayMon($arrKeyValue['brigade'],$arrKeyValue['date'],$arrKeyValue['type']))) {
            throw new ApplicationException("В этом месяце данные бригады [".$arrKeyValue['brigade']->getId()."] по типу [".$arrKeyValue['type']."] уже внесены",400);
        }
        */
        $newPay = $this->payBrigadeRepository->newPay($arrKeyValue);;
        $this->sendSystemNotification("Выплата бригаде","Бригада ".$brigade->getName()." Сумма ".$newPay->getAmount()." от ".$date->format('d-m-Y'));

        return $newPay;
    }

    public function brigadePayUpdate() {
        $this->repository =  $this->payBrigadeRepository;
    }

    public function brigadePayDelete() {
        $this->repository =  $this->payBrigadeRepository;
    }

    public function brigadePayFixed() {
        $this->repository =  $this->payBrigadeRepository;
    }

    public function brigadePayUnFixed() {
        $this->repository =  $this->payBrigadeRepository;
    }


}
