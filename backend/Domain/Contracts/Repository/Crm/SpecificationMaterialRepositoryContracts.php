<?php

namespace Domain\Contracts\Repository\Crm;

use Domain\Entities\Business\Objects\SpecificationMaterial;

interface SpecificationMaterialRepositoryContracts {
    public function findAllByCompnay($arrKeyValue, $arrOrderBy = [],$page=null,$offset=null);
    public function findAllByMyCompnay($arrAttributes, $arrOrderBy = null);
    public function findByMyCompnay($arrKeyValue);
    public function findAllMyCompnay();
    public function findMyCompnay($entityId);
    public function update($specificationMaterial, $arrAttributes);
    public function searchBy($arrKeyValue, $orderBy=[]) ;
}
