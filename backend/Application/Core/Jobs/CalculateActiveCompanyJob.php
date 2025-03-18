<?php

namespace Core\Jobs;
use Domain\Contracts\Services\Directory\BankServiceContract;
use Domain\Contracts\Services\AccountServiceContracts;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CalculateActiveCompanyJob extends Job implements ShouldQueue
{
    use  InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {


        Log::info("Расчет подписки");

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Log::info("Старт расчета подписки ");

        Log::info("End Job CleanOldTokensJob");
    }




}
