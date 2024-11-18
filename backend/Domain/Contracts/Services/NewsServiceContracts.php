<?php

namespace Domain\Contracts\Services;
interface NewsServiceContracts{
    public function create($arrKeyValue);
    public function getAll();
    public function getOne($id);
    public function deleteById($id);
}