<?php


namespace Infrastructure\Repositories\Directory;


use Domain\Contracts\Repository\Directory\BankRepositoryContract;

class BankRepositorySearch extends \LaravelDoctrine\Scout\SearchableRepository implements BankRepositoryContract
{

}

