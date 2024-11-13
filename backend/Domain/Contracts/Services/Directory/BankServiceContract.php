<?php


namespace Domain\Contracts\Services\Directory;


interface BankServiceContract{
    public function loadBankiCbr(String $nameFile);
    public function _getById($id);
    public function getBankAccounts($idBank);
    public function _getAllBy($arrKeyValue,$options=[]);

}

