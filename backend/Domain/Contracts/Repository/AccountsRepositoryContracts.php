<?php
namespace Domain\Contracts\Repository;
use Domain\Entities\Subscriber\Account;

interface AccountsRepositoryContracts{
    public function login($username,$password,$headers='');
    public function heckToken($jwtToken);
    public function find($accountId);
    public function optionsGet($idAccount);
    public function optionsSet($idAccount,$arrKeyValue);
    public function create($arrKeyValue);
    public function setToken(Account $account, $token, $headers);
    public function cleanOldTokens();
}
