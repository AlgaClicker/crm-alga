<?php
namespace Core\Mail;

use Domain\Entities\Business\Master\RequisitionMaterials;
use Domain\Entities\Subscriber\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use Domain\Entities\Services\Notification;
use Domain\Entities\Business\Master\Requisition;
class RegisterEmail extends Mailable
{

    use Queueable, SerializesModels;

    public Account $account;
    //public RequisitionMaterials $material;
    public function __construct(Account $account) {
        $this->account = $account;
    }

    public function build()
    {
        //
        return $this->subject("Подтверждение регистрации компании: ".$this->account->getCompany()->getName())->view('emails.register', ['account' => $this->account,"confirmationToken"=> $this->account->getToken()]);

//        return $this->view('emails.register', ['account' => $this->account,"confirmationToken"=>md5(uniqid(rand(),true))]);

    }


}
