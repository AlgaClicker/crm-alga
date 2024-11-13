<?php


namespace Domain\Contracts\Services\Crm;


interface BrigadeServiceContract
{
    public function createPeople($arrKeyValue);
    public function updatePeople($arrKeyValue);
    public function listPeopleBrigade($arrKeyValue);
    public function create($arrKeyValue);
    public function _updateById($id, $arrKeyValue);
    public function _create($arrKeyValue);
    public function getAllBy($arrKeyValue,$options=[]);
    public function _getById($id);
}
