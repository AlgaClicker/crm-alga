<?php

namespace Core\Providers;

use Illuminate\Support\ServiceProvider;

use Domain\Contracts\Repository\Payments\InvoicesRepositoryContract;
use Infrastructure\Repositories\Payments\InvoicesRepository;

use Domain\Contracts\Repository\Payments\PayBrigadeRepositoryContract;
use Infrastructure\Repositories\Payments\PayBrigadeRepository;

use Domain\Contracts\Services\Business\PaymentsServiceContract;
use Domain\Services\Business\PaymentsService;


class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Register any PaymentService services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(InvoicesRepositoryContract::class, InvoicesRepository::class);
        $this->app->bind(PayBrigadeRepositoryContract::class, PayBrigadeRepository::class);

        $this->app->bind(PaymentsServiceContract::class, PaymentsService::class);
    }


}
