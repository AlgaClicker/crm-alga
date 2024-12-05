<?php
namespace Domain\Contracts\Services;

use Domain\Entities\Services\Notification;
use Domain\Entities\Subscriber\Account;

interface NotificationServiceContracts{
    public function sendNotificationSystemAccount($idAccount,$title, $message);
    public function sendNotificationSystem($title, $message);
    public function sendRoleNotification($nameRole,$title,$message);
    public function readNotification($idNotification);
    public function sendMail($arrKeyValue);
    public function sendMailNotificationAccount(Account $to_account,Notification $notification);

}
