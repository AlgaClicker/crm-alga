<?php

namespace Domain\Entities\Business\Directory;

/**
 * BankContractor
 */
class BankContractor
{
    /**
     * @var uuid
     */
    private $id;

    /**
     * @var int
     */
    private $account;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $bank = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bank = new \Doctrine\Common\Collections\ArrayCollection();
    }

}
