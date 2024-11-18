<?php

namespace Domain\Contracts\Repository\Payments;
use Domain\Entities\Business\Directory\Partner;
use Domain\Entities\Business\Payments\Invoice;
use DateTimeImmutable;

interface InvoicesRepositoryContract
{
    public function newInvoice(Partner $partner, DateTimeImmutable $date, $type, float $amount): Invoice;
}
