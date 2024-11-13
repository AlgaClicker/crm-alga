<?php

namespace Domain\Contracts\Services\Crm;
use Psr\Http\Message\RequestInterface;

interface SpecificationsServiceContracts{
    public function newSpecification(RequestInterface $arrKeyValue);
    public function editSpecification(RequestInterface $arrKeyValue);
    public function getSpecificationOnly($specificationId);
}
