<?php


namespace Core\Listeners;


use Core\Events\NotificationEvent;
use Illuminate\Support\Facades\Log;

abstract class AbstractListener
{
    public function __construct()
    {
        //
    }
    function handle( $event)
    {

        return $event;
    }
}
