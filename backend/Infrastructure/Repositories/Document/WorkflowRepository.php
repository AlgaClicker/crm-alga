<?php

namespace Infrastructure\Repositories\Document;
use Doctrine\ORM\EntityManager;
use Infrastructure\Repositories\AbstractRepository;
use Domain\Contracts\Repository\Document\WorkflowRepositoryContract;
use Domain\Contracts\Repository\AccountsRepositoryContracts;
use Domain\Contracts\Repository\Services\FileRepositoryContracts;
use ReflectionParameter;

use Domain\Entities\Business\Document\Workflow;
/**
 * CommentsRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class WorkflowRepository extends AbstractRepository implements WorkflowRepositoryContract
{
    private AccountsRepositoryContracts $accountsRepository;
    private FileRepositoryContracts $fileRepository;
    public function __construct(
        EntityManager $em,
        Workflow $entity,
        AccountsRepositoryContracts $accountsRepository,
        FileRepositoryContracts $fileRepository
    ){
        $this->accountsRepository = $accountsRepository;
        $this->fileRepository = $fileRepository;
        parent::__construct($em, $entity);
    }

    public function create($arrAttributes)
    {

        $arrAttributes['status'] = "new";

        $newDoc = parent::loadNew($arrAttributes);

        $newDoc = parent::save($newDoc);

        return parent::find($newDoc) ;
    }


}
