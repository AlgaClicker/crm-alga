<?php
namespace Core\Console\Commands;
use Exception;
use Event;
use Illuminate\Console\Command;
use Core\Events\NotificationEvent;
use Domain\Entities\Services\Notification;
use Domain\Contracts\Services\Directory\BankServiceContract;
use Domain\Contracts\Repository\Directory\BankRepositoryContract;

class IndexBuilding extends Command
{
    protected $signature = "app:index-rebuild {filed}";
    protected $description = "Index search building";
    public function handle()
    {
        $filed = $this->argument('filed');
        $bankService = app(BankServiceContract::class);
        $bankRepository = app(BankRepositoryContract::class);
        $this->info("Index search building. filed: $filed");
    }
}

