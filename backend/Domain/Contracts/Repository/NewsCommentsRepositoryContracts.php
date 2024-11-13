<?php
namespace Domain\Contracts\Repository;

interface NewsCommentsRepositoryContracts{
    public function add($idNews,$request);
}