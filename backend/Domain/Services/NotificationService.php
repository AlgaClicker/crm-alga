<?php
namespace Domain\Services;
use Domain\Contracts\Services\NotificationServiceContracts;
use Domain\Contracts\Repository\Services\NotificationRepositoryContracts;
use Domain\Contracts\Services\AccountServiceContracts;
use Domain\Entities\Services\Notification;
use Domain\Entities\Subscriber\Account;
use Illuminate\Support\Facades\Auth;
use Domain\Contracts\Services\FileServiceContracts;
use Core\Exceptions\ApplicationException;
use Core\Events\NotificationEvent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Mail;
use Core\Mail\NotificationEmail;
use Domain\Entities\Business\Master\Requisition;
use Core\Mail\RequisitionEmail;
use Core\Mail\DeliveryRequisitionEmail;
use Core\Mail\RegisterEmail;
use Domain\Contracts\Services\Crm\RequisitionServiceContract;
use Illuminate\Support\Facades\Log;
class NotificationService extends AbstractService implements NotificationServiceContracts
{
    protected NotificationRepositoryContracts $notificationRepository;
    protected AccountServiceContracts $accountService;
    protected FileServiceContracts $fileService;


    public function __construct(
        NotificationRepositoryContracts $notificationRepository,
        AccountServiceContracts $accountService,
        FileServiceContracts $fileService
    ){
        $this->notificationRepository = $notificationRepository;
        $this->accountService = $accountService;
        $this->fileService = $fileService;
        parent::__construct($this->notificationRepository);
    }

    public function sendNotificationSystemAccount($idAccount,$title, $message) {
        $arrKeyValue['fromAccount'] = auth()->user();
        $arrKeyValue['toAccount'] = $this->accountService->getAccountMyCompnay($idAccount);
        $arrKeyValue['message'] = $message;
        $arrKeyValue['createdAt'] = new \DateTimeImmutable('now');
        $arrKeyValue['title'] = $title;
        $newNotification = $this->notificationRepository->create($arrKeyValue);

        event(new NotificationEvent($newNotification));
        //Mail::to(auth()->user()->getEmail())->queue(new NotificationEmail($newNotification));


    }

    public function sendRoleNotification($nameRole,$title,$message) {
        $arrKeyValue['fromAccount'] = auth()->user();
        $arrKeyValue['toAccount'] = auth()->user();
        $arrKeyValue['message'] = $message;
        $arrKeyValue['createdAt'] = new \DateTimeImmutable('now');
        $arrKeyValue['title'] = $title;
        $newNotification = $this->notificationRepository->loadNew($arrKeyValue);
        event(new NotificationEvent($newNotification));
        $arrAccount = $this->accountService->getAccountsCompanyListRole($nameRole);
        $thisAccount = $newNotification->getToAccount();
        foreach ($arrAccount as $account) {
            if ($thisAccount != $account) {
                $arrKeyValue['toAccount'] = $account;
                $newNotification = $this->notificationRepository->loadNew($arrKeyValue);
                event(new NotificationEvent($newNotification));
            }
        }
    }

    public function sendNotificationSystemInfo($title, $message): Notification {
        $arrKeyValue['fromAccount'] = auth()->user();
        $arrKeyValue['toAccount'] = auth()->user();
        $arrKeyValue['message'] = $message;
        $arrKeyValue['createdAt'] = new \DateTimeImmutable('now');
        $arrKeyValue['title'] = $title;
        $newNotification = $this->notificationRepository->loadNew($arrKeyValue);
        event(new NotificationEvent($newNotification));
        return $newNotification;

    }
    public function sendNotificationSystem($title, $message):Notification {
        $arrKeyValue['fromAccount'] = auth()->user();
        $arrKeyValue['toAccount'] = auth()->user();
        $arrKeyValue['message'] = $message;
        $arrKeyValue['createdAt'] = new \DateTimeImmutable('now');
        $arrKeyValue['title'] = $title;
        $newNotification = $this->notificationRepository->create($arrKeyValue);
        event(new NotificationEvent($newNotification));

        $arrAccount = $this->accountService->getAccountsCompanyListRole("upravlenie");
        $thisAccount = $newNotification->getToAccount();
        foreach ($arrAccount as $account) {
            if ($thisAccount != $account) {
                $arrKeyValue['toAccount'] = $account->getId()->serialize();
                $newNotification = $this->notificationRepository->loadNew($arrKeyValue);
                event(new NotificationEvent($newNotification));
            }
        }
        return $newNotification;
    }

    public function templateNot($arrKeyValue)
    {
        $arrKeyValue['fromAccount'] = auth()->user();
        return $this->notificationRepository->loadNew($arrKeyValue);
    }
    public function sendWebSocketNotification($arrKeyValue)
    {
        $arrKeyValue['fromAccount'] = auth()->user();
        $arrAccount = $this->accountService->getAccountsCompanyListRole("upravlenie");
        $newNotification = parent::_create($arrKeyValue);
        event(new NotificationEvent($newNotification));

        return $newNotification;
    }

    public function sendNotificationTemplate($arrKeyValue)
    {
        $requisitionService = app(RequisitionServiceContract::class);
        $arrKeyValue['methods'] = 'mail';
        if ($arrKeyValue['template'] == 'register') {
            $account = $this->accountService->getAccountUuid($arrKeyValue['account']['id']);

            if (!$account) {
                abort(404,"Аккаунт не найден");
            }
            return new RegisterEmail($account,);
        }
        if ($arrKeyValue['template'] == 'request') {

            $requisition = $requisitionService->getRequisition($arrKeyValue['request']['id']);
            try {
                $creted_at = \Carbon\Carbon::parse($requisition->getCreatedAt())->format('m-d-Y') ;
                $title =  "Новая заявка №: ".$requisition->getNumber().' от '.$creted_at;
                return new RequisitionEmail($requisition,\auth()->user(),$title);
            } catch (\Exception $exception) {
                abort(500, $exception->getMessage());
            }
        }

        if ($arrKeyValue['template'] == 'delivery') {
            $delivery_id = $arrKeyValue['delivery']['id'];
            $invoice = $requisitionService->getInvoice($delivery_id);
            return new DeliveryRequisitionEmail($invoice);

        }

        if ($arrKeyValue['template'] == 'notification') {

            return new NotificationEmail($this->templateNot($arrKeyValue));
        }

    }

    public function requisitionSendAutor(Requisition $requisition,$title='')
    {
            $creted_at = \Carbon\Carbon::parse($requisition->getCreatedAt())->format('m-d-Y') ;
            $title =  "Заявка №: ".$requisition->getNumber().' от '.$creted_at. " | ". $title;
        $arrKeyValue['fromAccount'] = auth()->user();
        $arrKeyValue['toAccount'] = $requisition->getAutor();
        $arrKeyValue['message'] = $title;
        $arrKeyValue['createdAt'] = new \DateTimeImmutable('now');
        $arrKeyValue['title'] = $title;
        $newNotification = $this->notificationRepository->loadNew($arrKeyValue);
        $this->notificationRepository->save($newNotification);

        event(new NotificationEvent($newNotification));
        //$this->sendNotificationSystemAccount($requisition->getAutor(),$title,'Изменилась заявка');
        try {
            Mail::to($requisition->getAutor()->getEmail())->queue(new RequisitionEmail($requisition,$requisition->getAutor(),$title));
        } catch (\Exception $e) {

        }

    }
    public function requisitionFixedSend(Requisition $requisition)
    {

            $accounts = $this->accountService->getAccountsCompanyListRole("snabzenie");

            foreach ($accounts as $account) {
               $this->requisitionSendTo($requisition,$account);
            }
    }

    public function requisitionSendTo(Requisition $requisition, Account $to_account,$title='')
    {
            $delay = now()->addSeconds(rand(5,60));
            try {
                Mail::to($to_account->getEmail())->later($delay, new RequisitionEmail($requisition, $to_account,$title));
                Log::info("Mail scheduled for " . $to_account->getEmail() . " with delay. $delay");
            } catch (\Exception $e) {
                Log::error("Error sending mail to " . $to_account->getEmail() . ": " . $e->getMessage());
            }
    }

    public function sendNotification($arrKeyValue) {
        $arrKeyValue['fromAccount'] = auth()->user();
        $result_l=[];
        if (array_key_exists('methods', $arrKeyValue) && !empty($arrKeyValue['methods'])) {

            foreach ($arrKeyValue['methods'] as $method) {
                if ($method === 'local') {
                    $arrKeyValue_p = $arrKeyValue;
                    $arrKeyValue_p['methods']='local';
                    $result_l['local'] = $this->sendWebSocketNotification($arrKeyValue_p);
                    $arrKeyValue['localnot'] = $result_l['local'];
                }

                if ($method === 'mail') {
                    $result_l['mail'] = $this->sendMail($arrKeyValue);
                }
            }
        } else {

            $arrKeyValue['methods']='local';
            $result_l['local'] = $this->sendWebSocketNotification($arrKeyValue);
        }
        return $result_l;

    }

    public function sendMailNotificationAccount(Account $to_account,Notification $notification) {
        Mail::to($to_account->getEmail())->send(new NotificationEmail($notification));
    }

    public function sendMail($arrKeyValue)
    {

        if (!array_key_exists("toAccount",$arrKeyValue)) {
            abort("500","toAccount обязательно к заполнению");
        }
        if (!array_key_exists('localnot',$arrKeyValue) || !is_object($arrKeyValue['localnot'])) {
            abort("500",'$arrKeyValue[\'localnot\'] is Notification entity');
        }
        $arrKeyValue['fromAccount'] = auth()->user();
        $toAccount = $this->accountService->getAccountUuid($arrKeyValue['toAccount']);
        if (array_key_exists('localnot',$arrKeyValue) && is_object($arrKeyValue['localnot'])) {
            Mail::to($toAccount->getEmail())->send(new NotificationEmail($arrKeyValue['localnot']));
        }

        $data = [
            'toAddress' => $toAccount->getEmail(),
        ]; //
        //dd($toAccount->getEmail());


    }

    public function sendMessage($arrKeyValue) {

        $fromAccount = $this->accountService->getAccount()->getId();
        if (!$fromAccount) {
            throw new ApplicationException('not found account',500);
        }


        $arrKeyValue['fromAccount'] = $fromAccount;
        $toAccount = $arrKeyValue['toAccount'];
        unset($arrKeyValue['toAccount']);
        $notifications = new Collection();
        if (gettype($toAccount ) == "array" ) {
            foreach ($toAccount as $account) {
                $arrKeyValue['toAccount'] = $account;
                if ($this->accountService->getOne($account)) {
                    $newNotification = $this->notificationRepository->create($arrKeyValue);
                    $notifications->add($newNotification);
                    event(new NotificationEvent($newNotification));
                }
            }
        }

        return $notifications->all();
    }

    public function getHeaderMessages() {

    }

    public function readNotification($idNotification) {
        $notification = $this->_getById($idNotification);
        if ($notification && $notification->getToAccount()===auth()->user()) {
            return $this->_updateById($idNotification,["readed"=>true]);
        } else {
            return "false";
        }
    }

    public function  sendNewWorkflow($account,$workflow) {

    }

    public function getAll() {
        $thisAccount = $this->accountService->getAccount()->getId();
        $listNotification =  $this->notificationRepository->findAllBy(['toAccount'=>$thisAccount,'active'=>true]);
        return $listNotification;
    }
}
