<?php
namespace Domain\Contracts\Repository\Business;

use Doctrine\ORM\Query\Expr\Func;

interface CompanyRepositoryContracts{
    public function findAll();
    public function create($arrKeyValue);
    public function new($arrKeyValue);
    public function load($arrKeyValue);
    public function deleteById($entityId);
    public function findOne($entityId);
    public function update($idCompany, $arrAttributes);

}
