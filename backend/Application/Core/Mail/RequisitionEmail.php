<?php
namespace Core\Mail;

use Domain\Entities\Business\Master\RequisitionMaterials;
use Domain\Entities\Subscriber\Account;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use Domain\Entities\Services\Notification;
use Domain\Entities\Business\Master\Requisition;
class RequisitionEmail extends Mailable
{
    use Queueable, SerializesModels;

    public Requisition $requisition;
    public $title;
    public array $materials;
    public Account $account;
    public $role;
    //public RequisitionMaterials $material;
    public function __construct(Requisition $requisition, Account $account_to,$title='') {
        $this->requisition = $requisition;

        $this->materials = $this->requisition->getMaterials()->toArray();
        $this->account = $account_to;
        $this->role = $this->account->getRoles()->getService();
        if (!$title) {
            $creted_at = \Carbon\Carbon::parse($this->requisition->getCreatedAt())->format('m-d-Y') ;
            $title =  "Заявка №: ".$this->requisition->getNumber().' от '.$creted_at;
        }
        $this->title=$title;
    }

    public function build()
    {
        $this->materials = $this->requisition->getMaterials()->toArray();
        try {
            return $this->subject($this->title)->view('emails.requisition',['materials'=>$this->materials]);
        } catch ( \Exception $e ) {
            LOG::error($e->getMessage());

        }

    }


}
