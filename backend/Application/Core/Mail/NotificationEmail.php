<?php
namespace Core\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use Domain\Entities\Services\Notification;

class NotificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public Notification $notification;
    public function __construct(Notification $notification) {

        $this->notification = $notification;
    }

    public function build()
    {

        return $this->subject("Уведомление: ".$this->notification->getTitle())->view('emails.notification');
    }
}
