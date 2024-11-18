<?php

namespace Domain\Contracts\Repository\Document;

use Domain\Entities\Business\Master\RequisitionMaterials;

interface MaterialRequisitionRepositoryContracts
{
    public function getMaterial($idMaterial): RequisitionMaterials|null;
}
