<?php
namespace Domain\Contracts\Services;

interface NotificationServiceContracts{
    public function sendNotificationSystemAccount($idAccount,$title, $message);
    public function sendNotificationSystem($title, $message);
    public function sendRoleNotification($nameRole,$title,$message);
}
