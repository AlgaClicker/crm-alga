<?php
use PHPUnit\Framework\TestCase;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Infrastructure\Repositories\Account\AccountsRepository;
use Infrastructure\Repositories\Account\RolesRepository;
use Infrastructure\Repositories\Business\CompanyRepository;
use Domain\Entities\Business\Company\Company;
use Domain\Contracts\Repository\AccountOptionsContract;
use Domain\Contracts\Repository\AccountTokensRepositoryContract;



class AccountServiceTest extends TestCase
{
    private $accountService;
    private $companyRepository;
    private $accountRepository;
    private $rolesRepository;
    private $entityManager;


    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        // Убедитесь, что все необходимые сервис-провайдеры загружаются
        $app->register(App\Providers\AppServiceProvider::class);
        $app->register(App\Providers\AuthServiceProvider::class);
        $app->register(App\Providers\RepositoryServiceProvider::class);
        $app->register(App\Providers\AccountsServiceProvider::class);
        $app->register(Domain\Contracts\Repository\AccountOptionsContrac::class);
        $app->register(Domain\Contracts\Repository\AccountTokensRepositoryContract::class);
        $app->register(LaravelDoctrine\ORM\DoctrineServiceProvider::class);
        $app->register(Core\Providers\AuthServiceProvider::class);
        $app->register(Tymon\JWTAuth\Providers\LumenServiceProvider::class);

        return $app;
    }

    protected function setUp(): void
    {
        echo "\r\n===============setUp===========\r\n";
        echo "             ".env("APP_NAME")."\r\n";
        echo "===============================\r\n";



        parent::setUp();
        // Настройка параметров подключения
        $connectionParams = [
            'dbname' => getenv('DB_DATABASE') ?: 'test_db',
            'user' => getenv('DB_USERNAME') ?: 'postgres',
            'password' => getenv('DB_PASSWORD') ?: 'secret',
            'host' => getenv('DB_HOST') ?: 'postgres',
            'driver' => 'pdo_pgsql', // Измените, если используете другой драйвер
        ];
        $paths = [__DIR__ . '../../Infrastructure/Doctrine/Mappings'];
        $isDevMode = true;

        $config = Setup::createAnnotationMetadataConfiguration($paths, $isDevMode);
        $this->entityManager = EntityManager::create($connectionParams, $config);
        $companyEntity = new Company();
        $this->companyRepository = new CompanyRepository($this->entityManager, $companyEntity);
        $accountEntity =  new \Domain\Entities\Subscriber\Account();
        //dd($accountEntity);


        $this->accountRepository = new AccountsRepository($this->entityManager,$accountEntity);



    }

    public function testLogin(): void
    {
        $email = 'it59com@mail.ru';
        $password = 'master24';

        echo "\r\n ====== Запуск теста testLogin ====== \r\n";

        // Здесь предполагается, что метод login возвращает результат, который можно проверить
        //$result = $this->accountService->login($email, $password);


        // Проверьте, что результат соответствует ожиданиям (замените assertTrue на фактическую проверку)
//        /$this->assertTrue($result->isAuthorized());
    }
}
