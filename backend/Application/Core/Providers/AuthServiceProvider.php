<?php

namespace Core\Providers;

use Domain\Entities\Subscriber\Account;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Core\Exceptions\ApplicationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Foundation\Application;
use LaravelDoctrine\ORM\Auth\DoctrineUserProvider;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Infrastructure\Repositories\Account\AccountsRepository;
use Domain\Contracts\Repository\AccountsRepositoryContracts;


class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {

        $this->app['auth']->viaRequest('jwt', function ($request) {

        });

        $this->app['auth']->viaRequest('firebase', function ($request) {
            dd("AuthServiceProvider");
            dd($request);
        });

        $this->app['auth']->viaRequest('api', function ($request) {

            if (!$request->hasHeader('Authorization')) {
                throw new ApplicationException('Token not found in request', 400);
            }

            $authHeader = $request->header('Authorization');
            try {
                list($jwt) = (sscanf($authHeader, 'Bearer %s'));

            } catch (\Exception $e) {
                throw new ApplicationException('Unauthorized', 401);
            }

        });
    }
}
