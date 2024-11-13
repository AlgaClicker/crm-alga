<?php


namespace Domain\Contracts\Repository\Document;


interface TasksRepositoryContract
{
    public function create($arrAttributes);
    public function findAllByCompnay($arrKeyValue, $arrOrderBy = [],$page=null,$offset=null);
    public function findAllByMyCompnay($arrAttributes, $arrOrderBy = null);
    public function findByMyCompnay($arrKeyValue);
    public function findAllMyCompnay();
    public function findMyCompnay($entityId);
    public function update($entity, $arrAttributes);
    public function searchBy($arrKeyValue, $orderBy=[]) ;

}
