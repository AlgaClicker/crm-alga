<?php

namespace Core\Jobs;
use Domain\Contracts\Services\Directory\BankServiceContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
class LoadBankCbrJob extends Job implements ShouldQueue
{
    use  InteractsWithQueue, Queueable, SerializesModels;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $file;
    public function __construct($file)
    {

        $this->file = $file;

        //$this->onQueue('processing');

        Log::info("Construct LoadBankCbrJob file:".$file);

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info("start Job LoadBankCbrJob ".$this->file);
        $bankService = app(BankServiceContract::class);
        $bankService->loadBankiCbrXml($this->file);
        Log::info("End Job LoadBankCbrJob");
    }




}
