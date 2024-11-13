<?php
namespace Domain\Contracts\Repository\Utils;


interface UnitsRepositoryContracts{
    public function findCode($code);
    public function findAllBy($arrKeyValue, $arrOrderBy = null);
}
