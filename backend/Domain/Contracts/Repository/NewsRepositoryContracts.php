<?php

namespace Domain\Contracts\Repository;
use Domain\Entities\News;

interface NewsRepositoryContracts{
    public function findAll();

    public function findOne($id);

    public function create($arrKeyValue);

    public function deleteById($id);

    public function createCommnets($news,$arrKeyValue);

    public function load($entity, $arrAttributes=null);
    

}