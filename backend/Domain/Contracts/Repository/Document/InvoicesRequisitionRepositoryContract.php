<?php

namespace Domain\Contracts\Repository\Document;

use Domain\Entities\Business\Document\Requisition\Invoice;
use Domain\Entities\Business\Document\Requisition\Invoice\Material as InvoiceMaterial;
use Domain\Entities\Business\Master\Requisition;
use Domain\Entities\Business\Master\RequisitionMaterials;

interface InvoicesRequisitionRepositoryContract
{
    public function invoicesRequisitionListAll(Requisition $requisition,$options=[]);
    public function getInvoiceMaterial(RequisitionMaterials $requisitionMaterial, Invoice $invoice): InvoiceMaterial | null | bool;
}
