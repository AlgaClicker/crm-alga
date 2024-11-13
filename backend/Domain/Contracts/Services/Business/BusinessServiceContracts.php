<?php

namespace Domain\Contracts\Services\Business;

interface BusinessServiceContracts{
    public function showMyCompnayStocks();
    public function getAllStockCompany(array $arrKeyValue);
    public function removeCompany($idCompany);
    public function updateCompnay($idCompany,$arrKeyValue);
    public function addStockCompany($arrKeyValue);
    public function getListCompanyAccount($idCompany);
    public function createCompany($arrKeyValue);
}
