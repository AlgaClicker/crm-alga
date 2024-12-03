<?php

namespace Domain\Entities\Business\Document\Requisition;

/**
 * Invoice
 */
class Invoice
{
    /**
     * @var uuid
     */
    private $id;

    /**
     * @var \Domain\Entities\Business\Company\Company
     */
    private $company;

    /**
     * @var \Domain\Entities\Subscriber\Account
     */
    private $autor;

    /**
     * @var \Domain\Entities\Business\Master\Requisition
     */
    private $requisition;


}
