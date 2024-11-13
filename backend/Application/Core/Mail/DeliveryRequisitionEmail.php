<?php
namespace Core\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use Domain\Entities\Services\Notification;
use Domain\Entities\Business\Master\Requisition;
use Domain\Entities\Business\Document\Requisition\Invoice;
class DeliveryRequisitionEmail extends Mailable
{
    use Queueable, SerializesModels;

    public Invoice $invoice;
    public $title;
    public function __construct(Invoice $invoice, $title='') {
        $this->invoice = $invoice;

        if (!$title) {
            $creted_at = \Carbon\Carbon::parse($invoice->getRequisition()->getCreatedAt())->format('m-d-Y') ;
            $title =  "Поставка по заявке  №: ".$invoice->getRequisition()->getNumber().' от '.$creted_at;
        }

        $this->title=$title;
    }

    public function build()
    {


        try {
            return $this->subject($this->title)->view('emails.delivery');
        } catch ( \Exception $e ) {
            abort(500,$e->getMessage());
        }

    }


}
