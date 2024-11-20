<?php

namespace Core\Jobs;
use Domain\Contracts\Services\Directory\BankServiceContract;
use Domain\Contracts\Services\AccountServiceContracts;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CleanOldTokensJob extends Job implements ShouldQueue
{
    use  InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $file;
    public function __construct()
    {

        $this->file = $file;

        //$this->onQueue('processing');

        Log::info("Удаление устаревших токенов");

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Log::info("start Job CleanOldTokensJob ");
        $accountService = app(AccountServiceContracts::class);
        $accountService->cleanOldTokens();
        Log::info("End Job CleanOldTokensJob");
    }




}
