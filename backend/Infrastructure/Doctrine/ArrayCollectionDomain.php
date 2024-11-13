<?php
namespace Infrastructure\Doctrine;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr\Func;

class ArrayCollectionDomain extends ArrayCollection
{

    public function __construct(array $elements = [])
    {
        parent::__construct($elements);
    }

    public function array_keys($key)
    {
        dd($key);
    }
}
