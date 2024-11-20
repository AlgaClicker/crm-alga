<?php
namespace Domain\Contracts\Services;


interface AccountServiceContracts{
    public function login($username, $password);
    public function checkToken($jwttoken);
    public function getAllRoles(): array;
    public function getOne($idAccount);
    public function getAll();
    public function getAccount();
    public function getThisRole();
    public function deleteAccount($idAccount);
    public function optionsAccount();
    public function optionsAccountSet($arrKeyValue);
    public function getMyCompany();
    public function createRole($arrKeyValue);
    public function updateRole($idRole,$arrKeyValue);
    public function auth($account);
    public function getAccountMyCompnay($idAccount);


}
