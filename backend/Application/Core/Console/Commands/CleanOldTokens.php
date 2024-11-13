<?php
namespace Core\Console\Commands;
use Domain\Contracts\Services\AccountServiceContracts;
use Exception;
use Event;

use Core\Jobs\CleanOldTokensJob;
use Illuminate\Console\Command;
use Core\Events\NotificationEvent;
use Domain\Entities\Services\Notification;


class CleanOldTokens extends Command
{
    protected $signature = "app:clear:tokens";
    protected $description = "Очиста устаревших токенов";
    public function handle()
    {
        $accountService = app(AccountServiceContracts::class);
        return $accountService->cleanOldTokens();

    }
}
