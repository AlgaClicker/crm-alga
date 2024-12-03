<?php

namespace Domain\Services;
use Core\Events\NotificationEvent;
use Doctrine\Common\Cache\FilesystemCache;
use Domain\Entities\Subscriber\Account;
use Domain\Entities\Services\Notification;
use Log;
use Kreait\Firebase\Contract\Auth as AuthFirebase;

use Kreait\Laravel\Firebase\Facades\Firebase;
use Domain\Contracts\Repository\AccountsRepositoryContracts;
use Domain\Contracts\Services\AccountServiceContracts;
use Domain\Contracts\Repository\Business\CompanyRepositoryContracts;
use Core\Exceptions\ApplicationException;
use Illuminate\Support\Facades\Auth;
use Domain\Contracts\Repository\RolesRepositoryContracts;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Domain\Contracts\Services\NotificationServiceContracts;
use Illuminate\Support\Str;

use Core\Events\ActiveAccountsEvent;

class AccountService extends AbstractService implements AccountServiceContracts
{
    public NotificationServiceContracts $notificationService;
    private AccountsRepositoryContracts $accountRepository;
    private RolesRepositoryContracts $rolesRepository;
    private CompanyRepositoryContracts $companyRepository;
    private $auth;

    public function __construct(
        AccountsRepositoryContracts $accountRepository,
        RolesRepositoryContracts $rolesRepository,
        CompanyRepositoryContracts $companyRepository,
        //Auth $auth

    ){
        $this->accountRepository = $accountRepository;
        $this->rolesRepository = $rolesRepository;
        $this->companyRepository = $companyRepository;


        try {
            //$this->auth = Firebase::auth();
        } catch ( e) {
            //$this->auth = Auth::authenticate();
        }

        parent::__construct($accountRepository);
    }

    public function getRefreshToken() {

        if (env('FIREBASE_USE',false) == 'true') {
            try {
                $auth = Firebase::auth();
                $refreshToken = $auth->signInWithCustomToken($this->getAccount()->getToken())->refreshToken();
                //dd($auth->getUser($this->getAccount()->getId()),$auth->);
                return trim($refreshToken);
            } catch (\Kreait\Firebase\Exception\AuthException $e) {
                return null;
                throw new ApplicationException('Ошибка FireBase Auth, FIREBASE_USE=true \r\nОшибка:'.$e->getMessage(),500);
            }

        } else {
            return  auth()->refresh(true, true);
        }


    }
    public function getAccount() {
        return auth()->user();
    }

    public function login($username, $password): Account|null
    {

        $headers= Request::header();
        $account = $this->accountRepository->login($username,$password);

        if (!$account) {
           abort(401,'Ошибка авторизации, не верный логин или пароль');
        }

        if ($account->getActive() === false) {
            auth()->logout();
            abort(401,'Ошибка авторизации, учетная запись отключена, обратитесь к руководителю.');
        }

        if (env('FIREBASE_USE',false) == "true") {
            try {
                $auth = Firebase::auth();
                try {
                    $auth->getUser($account->getId()->serialize());
                    $customToken = $auth->createCustomToken($account->getId()->serialize());
                    $token = $customToken->toString();
                    $auth->signInWithCustomToken($customToken);
                    $account->setToken($token);
                    auth()->setUser($account);
                } catch (\Kreait\Firebase\Exception\Auth\UserNotFound $e) {
                    $userProperties = [
                        'uid' => $account->getId(),
                        'email' => $account->getEmail(),
                        'emailVerified' => false,
                        'phoneNumber' => $account->getPhoneNumber(),
                        'password' => $password,
                        'displayName' => $account->getUsername(),
                        'disabled' => !$account->getActive(),
                    ];

                    $user = $auth->createUser($userProperties);



                    $auth->signInAsUser($user);
                    $token = $auth->createCustomToken($account->getId())->toString();
                    auth()->setUser($account);
                }
            } catch (\Kreait\Firebase\Exception\AuthException $e) {
                $token = auth()->login($account);
            }
        } else {
            $token = auth()->login($account);
        }




        $newNotification = new Notification();
        $newNotification->setTitle("Авторизация ");
        $newNotification->setCreatedAt(new \DateTimeImmutable('now'));
        $newNotification->setMessage("Авторизация пользователя: ".$account->getUsername());
        $newNotification->setFromAccount($account);
        $newNotification->setReaded(true);
        $arrAccount = $this->getAccountsCompanyListRole("upravlenie");

        foreach ($arrAccount as $accountSend) {
            $newNotification->setToAccount($accountSend);
            event(new NotificationEvent($newNotification));
        }

        return $this->accountRepository->setToken($account,$token,$headers);
    }

    public function getAccountsCompanyListRole(string $role) {
        $role = $this->getRoleByName($role);
             if (!$role) {
                 throw new ApplicationException('Role: ['.$role.'] not found',404);
             }

        return $this->accountRepository->findAllByCompnay(['roles'=>$role->getId()]);

    }

    public function getRoleByName($role) {

        return $this->rolesRepository->findByMyCompnay(['service'=>$role]);
    }

    public function signInWithRefreshToken(String $refreshToken) {
        try {
            return [
                "access_token" => $this->firebaseAuth->signInWithRefreshToken($refreshToken)->accessToken(),
                "refresh_token" =>$this->firebaseAuth->signInWithRefreshToken($refreshToken)->refreshToken(),
                "ttl" => $this->firebaseAuth->signInWithRefreshToken($refreshToken)->ttl(),
            ];

        } catch (\Kreait\Firebase\Exception\AuthException $e) {
          abort(500,$e->getMessage());
        }
    }

    public function getListAccountsRole($role) {

    }

    public function checkOnline() {
        //ActiveAccountsEvent
        event(new ActiveAccountsEvent($this->getAccount()));
    }

    public function getFirebaseAccount(){
        $auth = Firebase::auth();
        $customToken = $auth->createCustomToken(auth()->user()->getId());
        dd($customToken);
    }

    public function getAccountUuid($idAccount) {
        //return $this->_getById($idAccount);
        $account =  $this->accountRepository->find($idAccount);

        if (!$account->getActive()) {
            throw new ApplicationException('Ошибка авторизации, учетная запись отключена, обратитесь к руководителю.',401);
        }

        return $account;
    }
    public function getAccountsCompnay($idAccount=null) {
        $role = $this->getThisRole()->getService();

        $filter['company'] = $this->getMyCompany()->getId();

        if ($idAccount) {
            return $this->accountRepository->findByMyCompnay(['id'=>$idAccount]);
        }

        $accounts = $this->accountRepository->findAllMyCompnay();

        if ($role == 'upravlenie' || $role == 'administrator') {
            return $accounts;
        } else {
            $accountsCollect = new Collection();
            foreach ($accounts  as $account) {
                $name = '';
                if ($account->getName()) {
                    $name .= $account->getName()." ";
                }
                if ($account->getSurname()) {
                    $name .= $account->getSurname()." ";
                }

                $acc = [
                    "id" =>$account->getId(),
                    "username" =>$account->getUsername(),
                    "name"=>$account->getName(),
                    "surname" => $account->getSurname(),
                    //"company" => $account->getCompany(),
                    "patronymic" => $account->getPatronymic(),
                    "role" =>$account->getRoles() ? $account->getRoles()->getService() : '',
                ];

                if ($name) {
                    $acc['fullname'] = $name;
                }
                $newAccount = $this->accountRepository->loadNew($acc);

                $accountsCollect->add($newAccount);
            }

            return $accountsCollect->all();
        }

    }

    public function getThisRole(){

        $account  =   $this->accountRepository->find(auth()->user()->getId());

        return $account->getRoles();
    }

    public function getMyCompany() {
        return $this->getAccount()->getCompany();
    }

    public function getAccountMyCompnay($idAccount) {
        $compnay = $this->getAccount()->getCompany();
        $account = $this->accountRepository->findBy(['id'=>$idAccount,'company'=>$compnay]);
        return $account;

    }

    public function checkToken($jwtToken)
    {

       return  $this->accountRepository->checkToken($jwtToken);
    }

    public function getOne($accountId)
    {
        $role = $this->getThisRole()->getService();
        $compnay = $this->getAccount()->getCompany();
        if ($role == 'administrator') {
            return $this->accountRepository->find($accountId);
        }
        $account = $this->accountRepository->findBy(['id'=>$accountId,'company'=>$compnay]);

        return  $account;

    }

    public function actionHistory($idAccount) {
        $thisAccount = $this->accountRepository->find($idAccount);
        if ($thisAccount->getCompany() != $this->getAccount()->getCompany()) {
            throw new ApplicationException('Company from account not found',404);
        }
        return $thisAccount = $this->accountRepository->history($idAccount);
    }

    public function auth($account)
    {
        auth()->login($account);
        $token = auth()->login($account);
        $this->accountRepository->update($account,['token'=>$token,'updateAt'=>new \DateTime()]);
        return $token;
    }

    public function toArray()
    {
        return get_object_vars($this);
    }

    public function getAllRoles(): array
    {
        return $this->rolesRepository->findAllByCompnay([]);
    }

    public function getAll()
    {
        $role=$this->getThisRole()->getService();
        if ( $role == 'administrator') {
            return $this->accountRepository->findAllBy([]);
        }

        if ( $role != 'upravlenie') {
            throw new ApplicationException('Access Deny. Your Role:'.$this->getThisRole()->getName(),403);
        }

        return $this->accountRepository->findAllByCompnay([]);
    }

    public function getRole($idRole) {
        return $this->rolesRepository->findOne($idRole);
    }

    public function getCompnayListRole() {
        return $this->rolesRepository->findAllByCompnay([]);
    }

    public function createRole($arrKeyValue)
    {
        $arrKeyValue['service'] = Str::slug($arrKeyValue['name']);
        if ($this->rolesRepository->findOneBy(['service'=>$arrKeyValue['service']])) {
            return  $this->rolesRepository->findOneBy(['service'=>$arrKeyValue['service']]);
        }
        return $this->rolesRepository->create($arrKeyValue);
    }

    public function updateRole($idRole,$arrKeyValue) {

        $role = $this->rolesRepository->findOne($idRole);

        $role->setService();

        $role = $this->accountRepository->update($role,$arrKeyValue);

        return $role;
    }

    public function getOnly($idAccount) {

        $thisCompany = $this->getAccount()->getCompany();
        $thisRole = $this->getThisRole()->getService();
        $account  =   $this->accountRepository->find($idAccount);
        if ($thisRole == "administrator") {
            return  $account ;
        } else {
            if ($account && $account->getCompany() === $thisCompany) {
                return  $account ;
            } else {

            }
        }
    }

    public function deleteAccount($idAccount) {
        if ($this->getThisRole()->getService() =="administrator" || $this->getThisRole()->getService() == "upravlenie" ) {
            $account  =   $this->accountRepository->find($idAccount);
            if ($this->getThisRole()->getService() == "upravlenie" ) {
                if ($account->getCompany() != $this->getAccount()->getCompany()) {
                    throw new ApplicationException('Access Deny Compnay. Your Role:'.$this->getThisRole()->getName(),403);
                }
            }
            $this->accountRepository->deleteById($idAccount);

            return $this->accountRepository->findAll();
        }

    }

    public function registration($arrKeyValue) {
        if (array_key_exists('roles',$arrKeyValue)){
            if ('administrator' == $arrKeyValue['roles'] ) {
                if ($this->getThisRole() != 'administrator') {
                    throw new ApplicationException('Role not Administrator',403);
                }
            }

            //dd($this->rolesRepository->findBy(['company'=>$arrKeyValue['company']]),$arrKeyValue['company']);
            if (Str::isUuid($arrKeyValue['roles'])){
                $roles = $this->rolesRepository->findMyCompnay($arrKeyValue['roles']);
            } else {
                $roles = $this->rolesRepository->findOneBy(['company'=>$arrKeyValue['company'],'service'=>$arrKeyValue['roles']]);
            }



            if ($roles) {
                $arrKeyValue['roles'] = $roles->getId();
            } else {
                throw new ApplicationException('Role not found',404);
            }
        }
        $arrKeyValue['company'] =  $this->companyRepository->findOne($arrKeyValue['company']);

        $newAccount  = $this->accountRepository->create($arrKeyValue);

        return  $this->accountRepository->find($newAccount->getId());
    }

    public function createRolesCompany($company) {
        $roles = [
            "polzovatel"=>"Пользователь",
            "snabzenie"=>"Снабжение",
            "upravlenie"=>"Управление",
            "master"=>"Мастер",
            "buxgalteriia"=>"Бухгалтерия",
            "sklad"=>"Склад",
            "manageobject"=>"Менеджер объекта",
            "kadry"=>"Отдел кадров",
            "smeta"=>"Сметно-расчетный отдел",
        ];

        foreach ($roles as $role=>$title) {
            $arrKeyValue['service'] = $role;
            $arrKeyValue['name'] = $title;
            $arrKeyValue['company'] =  $company;
            $arrKeyValue['fixed'] = true;
            $this->rolesRepository->create($arrKeyValue);

        }
    }

    public function newAccount($arrKeyValue) {

        if (!($this->getThisRole()->getService() == 'administrator' || $this->getThisRole()->getService() == 'upravlenie')) {
            throw new ApplicationException('У вас нет прав для добовления пользователя',403);
        }
        if (array_key_exists('roles',$arrKeyValue)){
            if ('administrator' == $arrKeyValue['roles'] ) {
                if ($this->getThisRole()->getService() != 'administrator') {
                    throw new ApplicationException('Role not Administrator',403);
                }
            }


            if (Str::isUuid($arrKeyValue['roles']) && !is_array($arrKeyValue['roles'])){
                $roles = $this->rolesRepository->findMyCompnay($arrKeyValue['roles']);
            } elseif (is_array($arrKeyValue['roles']) && array_key_exists("service",$arrKeyValue['roles'])) {
                $roles = $this->rolesRepository->findByMyCompnay(['service'=>$arrKeyValue['roles']['service']]);
            } else {
                $roles = $this->rolesRepository->findByMyCompnay(['service'=>$arrKeyValue['roles']]);
            }

                if ($roles) {
                    $arrKeyValue['roles'] = $roles;
                } else {
                    throw new ApplicationException('Role not found',404);
                }
        }
        if (array_key_exists('company',$arrKeyValue) && $this->getThisRole()->getService() =="administrator"){
            $arrKeyValue['company'] =  $this->companyRepository->findByMyCompnay($arrKeyValue['company']);
        } else {
            $arrKeyValue['company'] = $this->getAccount()->getCompany();
        }

        $newAccount  = $this->accountRepository->create($arrKeyValue);

        return  $this->accountRepository->find($newAccount->getId());
    }

    public function updateAccount($idAccount,$arrKeyValue) {
        $account  =   $this->accountRepository->findMyCompnay($idAccount);
        $role = $this->getThisRole()->getService();

        if($account->getRoles()->getService() == "administrator" && $role != "administrator") {
            //throw new ApplicationException('Только администраторы могут изменять администраторов',400);
        }

        if (array_key_exists('email',$arrKeyValue)) {
            $emailAccount = $this->findByEmail($arrKeyValue['email']);

            if ($emailAccount && $emailAccount != $this->getAccount()) {
                throw new ApplicationException('E-mail адрес ['.$arrKeyValue['email'].'] уже используется',400);
            }
        }

        if (array_key_exists('role',$arrKeyValue) && ($this->getThisRole()->getService() == 'upravlenie' || $this->getThisRole()->getService() == "administrator")) {
            $arrKeyValue['roles'] = $this->getRoleByName($arrKeyValue["role"]);
        }

        if (array_key_exists('roles',$arrKeyValue) && ($this->getThisRole()->getService() == 'upravlenie' || $this->getThisRole()->getService() == "administrator")) {
            if ($this->getThisRole()->getService() == 'upravlenie' && $arrKeyValue["roles"]=="administrator") {
                unset($arrKeyValue['roles']);
            } else {
                $arrKeyValue['roles'] = $this->getRoleByName($arrKeyValue["roles"]);
            }

        }

        if ($role != "administrator") {
            unset($arrKeyValue['company']);
        }
        if ($role == "administrator") {
            return $this->accountRepository->update($account,$arrKeyValue);
        }


        if ($role == 'upravlenie' && $account->getCompany() == auth()->user()->getCompany()) {
            return $this->accountRepository->update($account,$arrKeyValue);
        }

        if (!($role == 'upravlenie' || $role == "administrator")) {
                unset($arrKeyValue['active']);
                unset($arrKeyValue['roles']);
                unset($arrKeyValue['company']);
                unset($arrKeyValue['token']);
                unset($arrKeyValue['delete']);
                unset($arrKeyValue['deletedAt']);

                if ($idAccount == $this->getAccount()->getId()) {
                    return $this->accountRepository->update($account,$arrKeyValue);
                }
            throw new ApplicationException('Попытка изменить аккаунет не принадлежащий пользователю',400);

        }


        return false;
    }

    public function findByEmail($email) {
        $account  =   $this->accountRepository->findBy(['email'=>$email]);
        if (!$account) {
            return null;
        }
        return $account;
    }

    public function saveAccount($idAccount,$arrKeyValue) {
        $data = [];


        if (array_key_exists('id',$arrKeyValue) && !$idAccount) {
            $idAccount = $arrKeyValue['id'];
        }

        $account  =   $this->accountRepository->find($this->getAccount());



        if ($this->getThisRole()->getService() =="upravlenie" && $idAccount) {
            $account  =   $this->accountRepository->findBy(['id'=>$idAccount,'company'=>$this->getMyCompany()->getId()]);
        }

        if ($this->getThisRole()->getService() =="administrator") {
            $account  =   $this->accountRepository->find($idAccount);
        }

        if (!$account) {
            throw new ApplicationException('Account id ['.$idAccount.'] not found',404);
        }

        foreach ($arrKeyValue as $key => $val) {
            if (!($key == 'password' || $key == 'token')) {
                $data[$key] = $val;
            }

            if ($key == 'company') {
                $company = $this->companyRepository->findOne($val);
                $data[$key] = $company;
            }

            if ($key == 'password' && strlen($val) > 5) {
                $data[$key] = $val ;
            }

            if ($key == 'roles') {
                $role = $this->rolesRepository->find($val);
                $data[$key] = $role;
            }

        }

        $account = $this->accountRepository->update($account,$data);

        return $account;
    }

    public function optionsAccountSet($arrKeyValue) {
        $idAccount = Auth::user()->getId();
        $arrKeyData = array();
        foreach ($arrKeyValue as $key=>$item ) {
            if (is_array($item)) {
                $arrKeyData[$key] = json_encode($item);
            }else if (is_object($item)) {

            } else {
                $arrKeyData[$key] = $item;
            }

        }
        $this->accountRepository->optionsSet($idAccount,$arrKeyData);
        return $this->optionsAccount() ;
    }

    public function optionsAccount() {

        $idAccount = Auth::user()->getId();

        $options  =   $this->accountRepository->optionsGet($idAccount);
        $returnOptions = [];
        foreach ($options as $key=>$val) {
            $returnOptions[$val->getKey()] = json_decode($val->getVal());
        }

        return $returnOptions;
    }

    public function cleanOldTokens() {
        return $this->accountRepository->cleanOldTokens();
    }
}
