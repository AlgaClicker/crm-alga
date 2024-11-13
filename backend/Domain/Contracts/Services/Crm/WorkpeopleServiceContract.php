<?php


namespace Domain\Contracts\Services\Crm;


interface WorkpeopleServiceContract
{
    public function _create($arrKeyValue);
    public function _updateById($id, $arrKeyValue);
    public function _getAllBy($arrKeyValue,$options=[]);
    public function _getById($id);
    public function deleteTimeSheet($idWorkpeople, \DateTimeImmutable $date);
    public function editTimeSheet(
        $workpeople,
        \DateTimeImmutable $date,
        $timeAmount,
        $timeExtra='',
        $specificationId='',
        $description=''
    );

}
