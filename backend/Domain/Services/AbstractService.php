<?php
namespace Domain\Services;
use Core\Events\NotificationEvent;
use Core\Exceptions\ApplicationException;
use Doctrine\ORM\Mapping\Entity;
use Illuminate\Support\Collection;
use Domain\Entities\Subscriber\Account;
use PhpParser\Node\Expr\Array_;
use Ramsey\Uuid\Uuid;
use Infrastructure\Repositories\Services\LogsRepository;
use Domain\Entities\Services\Notification;
abstract class AbstractService
{
    protected $service;
    protected $repository;
    protected ?Account $account;
    private $roleService;


    public function __construct($repository) {


        $this->repository = $repository;
        $this->account = auth()->user();
        if ($this->account) {
            $this->roleService = $this->account->getRoles()->getService();
        }
    }
    public function _getBy($arrKeyValue,$options=[]) {
        return $this->repository->findBy($arrKeyValue);
    }


    private function checkUuid($value) {
        if (is_object($value)) {
            return true;
        }

        if (Uuid::isValid($value)) {
            return true;
        }
        return false;
    }

    protected function _setOptionsResponse($options = [],$countObjects=5) {


        if ($options == [] || is_array($options) && !array_key_exists('orderBy',$options)) {
            $options['orderBy'] = [
                'created_at' => 'DESC'
            ];
        }

        if (is_array($options) && array_key_exists('pagginate',$options)) {
                if(array_key_exists('limit',$options['pagginate'])) {
                    $limit = $options['pagginate']['limit'];
                } else {
                    $limit = env('DEFAULT_RECORDS_LIMIT',5);
                }
        } else {
            $limit = env('DEFAULT_RECORDS_LIMIT',5);
            $options['pagginate']['limit'] = $limit;
        }

        if ($limit>=$countObjects) {
            $options['pagginate']['pages'] = 1;
            $options['pagginate']['page'] = array_key_exists('page',$options['pagginate']) && $options['pagginate']['page'] ? $options['pagginate']['page'] : 1;

            return $options;
        }

        if (is_array($options) && array_key_exists('pagginate',$options)) {
            $pagginate = $options['pagginate'];
            if (!array_key_exists("limit",$pagginate)) {
                $pagginate['limit'] = $limit;
            }

            if (array_key_exists("page",$pagginate)) {

                if (ceil($countObjects / $pagginate['limit']) < $pagginate['page']) {
                    $pagginate['page'] = ceil($countObjects / $pagginate['limit']);
                }

            }

            unset($options['pagginate']);
            if (array_key_exists('page',$pagginate) || array_key_exists('limit',$pagginate)) {
                if (!array_key_exists('page',$pagginate) ) {
                    $pagginate['page'] = 1;
                }
                $options['pagginate']['page'] = $pagginate['page'];
                $options['pagginate']['limit'] = $pagginate['limit'];
                $options['pagginate']['pages'] = ceil($countObjects / $pagginate['limit']);
            }
        } else {
            $options['pagginate']['page'] = 1;
            $options['pagginate']['limit'] = $limit;
            $options['pagginate']['pages'] = ceil($countObjects/$limit);
        }

        return $options;
    }

    public function  _getAllBy($arrKeyValue,$options=[]) {
        $orderBy = [];
        $page = 1;
        $limit = env('DEFAULT_RECORDS_LIMIT',5);
        $countRows = $this->repository->countByCompnay($arrKeyValue);

        $options = $this->_setOptionsResponse($options,$countRows);

        $data['options'] = $options;

        if ($options && array_key_exists('orderBy',$arrKeyValue)) {
            unset($options['orderBy']);
        }

        if ($options && array_key_exists('pagginate',$options)) {
            if (!array_key_exists('limit',$options['pagginate'])) {
                $options['pagginate']['limit'] = env('DEFAULT_RECORDS_LIMIT',10);
            }
            if (!array_key_exists('page',$options['pagginate'])) {
                $options['pagginate']['page'] = 1;

            }
             $limit = $options['pagginate']['limit'];
             $page = $options['pagginate']['page']-1;


            $data['options'] = $options;
        } else {
            $page = 0;
        }


        if ($options && array_key_exists('filter',$options) && $options['filter']) {

            $data['data'] = $this->repository->filterBy($options['filter'],$options['orderBy'],$page,$limit);
            $data['options']['pagginate'] = $this->repository->filterByPagginate($options['filter'],$options['orderBy'],$page,$limit);
        } else {
            //$data['data'] = $this->repository->filterBy(['orderBy'=>['created_at'=>"DESC"]],$options['orderBy'],$page,$limit);
            $data['data'] = $this->repository->findAllByCompnay($arrKeyValue,$options['orderBy'],$page,$limit);

        }

       // $data['data'] = $this->repository->findAllByCompnay($arrKeyValue,$options['orderBy'],$page,$limit);
        return $data;
    }

    public function _getAll($options=[]): Array {

        $orderBy = [];
        if ($options && array_key_exists("pagginate",$options)) {
            $page = $options['pagginate']['page'];
            $limit = $options['pagginate']['limit'];
        } else {
            $page = 1;
            $limit = env('DEFAULT_RECORDS_LIMIT',5);
        }



        $countRows = $this->repository->countByCompnay();
        $options = $this->_setOptionsResponse($options,$countRows);

        if ($options && array_key_exists('orderBy',$options)) {
            $orderBy = $options['orderBy'];
        }

        $data['data'] = $this->repository->findAllByCompnay([],$orderBy,$page,$limit);

        $data['options'] = $options;

        return $data;

    }

    public function _options(array $options) {
        return $this->repository->countByCompnay();
    }

    /**
     * @param $id
     * @return Entity
     */
    public function _getById($id) {
        if (is_string($id) && $this->checkUuid($id)) {
            return $this->repository->findMyCompnay($id);
        } elseif ($id && is_object($id) && $this->checkUuid($id)) {
            return $this->repository->findMyCompnay($id);
        }

        return false;
    }

    public function sendSystemNotification($title, $message,$entity='') {
        $newNotification = new Notification();
        $newNotification->setCreatedAt((new \DateTimeImmutable('now'))->getTimestamp());
        if ($entity && is_object($entity)) {
            $childrenEntityReflection = new \ReflectionObject($entity);

            $message = [
                "message"=>$message,
                "data"=> $this->serialize($entity)
            ];
        }

        $newNotification->setMessage($message);
        $newNotification->setToAccount(auth()->user());
        $newNotification->setFromAccount(auth()->user());
        $newNotification->setTitle($title);
        $newNotification->setMethods(['system','local']);
        event(new NotificationEvent($newNotification));
    }
    /**
     * @param $arrKeyValue
     * @return Entity
     */
    public function _create($arrKeyValue) {



        $newEntity = $this->repository->loadNew($arrKeyValue);

        $repEntyty = $this->repository->checkAttr($arrKeyValue);
        $arrKeyValue = $this->updateAttributtes($newEntity,$arrKeyValue);

        $arrKeyValue == array_merge($repEntyty,$arrKeyValue);

        return $this->repository->create($arrKeyValue);
    }



    private function createAttributtes($arrKeyValue) {
        foreach ($arrKeyValue as $key=>$value) {
            $serviceName = substr($key,0,-1)."Service";
            if (!property_exists($this,$serviceName)) {
                $serviceName = $key."Service";
            }
            if (property_exists($this,$serviceName)) {

            }

        }

        return $arrKeyValue;
    }

    /**
     * @param $entity
     * @return boolean
     */
    private function chekResourcesDelete($entity) {
        $list = new Collection();
        //dd($entity->getWorkpeople());
        foreach (get_class_methods($entity) as $method) {
            $nameGetMethods = "get".substr($method,3)."s";
            $nameGetMethod = "get".substr($method,3);





            //$list->push($nameGetMethods);
            if ( substr($method,0,3) === 'add' && method_exists($entity,$nameGetMethods)) {
                $list->push($entity->$nameGetMethods()->count());
                if ($entity->$nameGetMethods()->count()>0 && is_array($entity->$nameGetMethods())) {
                    throw new ApplicationException("В объекте с ID {".$entity->getId()."} имеются связанные записи. Method:".$nameGetMethods,400);
                }
            }

            if (method_exists($entity,$nameGetMethods) && is_array($entity->$nameGetMethods())) {
                dd($entity->$nameGetMethods());
            }

            if ( substr($method,0,3) === 'add' && method_exists($entity,$nameGetMethod)) {
                if ($entity->$nameGetMethod()->count()>0) {
                    throw new ApplicationException("В объекте с ID {".$entity->getId()."} имеются связанные записи. Method:".$nameGetMethod,400);
                }
            }


        }
        return true;
    }
    /**
     * @param $idEntity
     * @return boolean
     */
    public function _delete($idEntity) {
        $entity = $this->_getById($idEntity);

        if (property_exists($entity,'fixed') && method_exists($entity,'getFixed')) {
            if ($entity->getFixed() === true) {
                abort('400','Фиксированная запись');
            }
        }

        if ($this->chekResourcesDelete($entity)) {


            if ($entity && $this->repository->deleteById($entity)) {
                return true;
            } else {
                return false;
            }


        }

        return "НЕ работет, нужны дополнительные проверки";

    }

    /**
     * @param $arrKeyValue
     * @return mixed
     */
    public function _getByContext($arrKeyValue) {
        return $this->repository->findAllBy($arrKeyValue);
    }

    /**
     * @param $arrKeyValue
     * @return mixed
     */
    public function _search($arrKeyValue) {
        unset($arrKeyValue['id']);
        unset($arrKeyValue['options']);
        $data = $this->checkAttributtes($arrKeyValue);
        if ($data == null) {
            return null;
        }
        return $this->repository->searchBy($data);
    }
    /**
     * @param $boll
     * @return Entity
     */
    public function _setFixed($id,$boll) {

        return $this->repository->fixed($id,$boll);
    }

    public function _updateById($id,$arrKeyValue) {
        unset($arrKeyValue['created_at']);


        $entity = $this->repository->findMyCompnay($id);


        if (!$entity) {
            throw new ApplicationException("Объект с ID {$id} не найден",404);
        }

        $arrKeyValue = $this->updateAttributtes($entity,$arrKeyValue);

        return $this->repository->update($entity,$arrKeyValue);
    }

    public function _updateByHash($hash,$arrKeyValue) {
        dd("AbstractService:_updateByHash",$hash,$arrKeyValue);

        return $this->repository->findAllBy($arrKeyValue);
    }

    public function unsetAttributtes($arrKeyValue) {
        $attributtes = ['company','delete','active','createAt','autorId','autor','updatedAt'];
        foreach ($attributtes as $attributte) {
            unset($arrKeyValue[$attributte]);
        }
        return $arrKeyValue;
    }

    protected function checkAttributtes($arrKeyValue) {
        $newArrKeyValue = [];

        $entity = $this->repository->loadNew($arrKeyValue);
        foreach ($arrKeyValue as $key=>$val) {
            if(method_exists($entity, 'set'.ucfirst($key))){
                $newArrKeyValue[$key] = $val;
            }
        }
        return $newArrKeyValue;
    }

    public function updateAttributtes($entity, $arrKeyValue) {
        $arrKeyValue = $this->unsetAttributtes($arrKeyValue);

        foreach ($arrKeyValue as $key=>$value) {
            if (mb_ereg('_id',$key)) {
                $key = substr($key,0,-2);
            }
            $serviceName = substr($key,0,-1)."Service";

            if ($key == "responsibles" || $key == "responsible") {
                $serviceName = "accountsService";
                if (!property_exists($entity,$serviceName)) {
                    $serviceName = "accountService";
                }

            }

            if ($key == 'files') {
                $serviceName = "fileService";

            }

            if ($entity && property_exists($entity,$key)  ) {

                $setMethod = 'set'.ucfirst($key);
                $getMethod = 'get'.ucfirst($key);
                $addMethod = 'add'.substr(ucfirst($key),0,-1);
                if (!method_exists($entity,$addMethod)) {
                    $addMethod = 'add'.ucfirst($key);
                }

                if (method_exists($entity,$setMethod) && is_string($value) && Uuid::isValid($value) && !is_array($value))
                {
                    $childrenEntityReflection = new \ReflectionParameter(array($entity, $setMethod), 0);
                    $parentClassId  = $childrenEntityReflection->getClass();

                    if ($parentClassId) {

                        $parentClassId  = $childrenEntityReflection->getClass()->getName();
                        $parentClassId = strtolower(substr($parentClassId, strrpos($parentClassId, '\\') + 1));
                        $parentService = $parentClassId."Service";

                        if (!property_exists($this,$parentService)) {
                            $parentService = $parentClassId."Service";
                        }

                        if (!property_exists($this,$parentService)) {
                            $parentService = substr($parentClassId,0,-1)."Service";
                        }



                        if (property_exists($this,$parentService)) {

                            $parent = $this->$parentService->_getById($value);
                            //dd($parent,$parentClassId,property_exists($this,$parentService));
                            if ($parent) {
                                $arrKeyValue[$key] = $parent;
                            } else {
                                unset($arrKeyValue[$key]);
                            }
                        } else {
                            unset($arrKeyValue[$key]);
                        }
                    }

                }

                if (method_exists($entity,$addMethod) && is_array($value) && count($value)>0) {

                    $childrenEntityReflection = new \ReflectionParameter(array($entity, $addMethod), 0);
                    $parentClassId  = $childrenEntityReflection->getClass()->getName();
                    $parentClassId = strtolower(substr($parentClassId, strrpos($parentClassId, '\\') + 1));
                    $parentService = $parentClassId."Service";
                    if ($key == 'specifications' ) {
                        $parentService ="specificationsService";
                    }

                    if ($key == 'files' ) {

                        $values = $arrKeyValue[$key];
                        unset($arrKeyValue[$key]);

                        if (property_exists($this,$parentService) || property_exists($this,$serviceName)) {
                                if (property_exists($this,$serviceName)) {
                                    $parentService = $serviceName;
                                }
                                foreach ($values as $hashFile) {
                                    $file = null;

                                    if (is_array($hashFile)) {
                                        if (array_key_exists('hash',$hashFile) && $hashFile['hash']) {

                                            $arrKeyValue[$key][] = $this->$parentService->getOne($hashFile['hash']);
                                        }
                                    }
                                    if (is_object($hashFile)) {
                                        $arrKeyValue[$key][] = $hashFile;
                                    }
                                    if (is_string($hashFile)) {

                                        $arrKeyValue[$key][] = $this->$parentService->getOne($hashFile);
                                    }

                                }
                            //dd($arrKeyValue[$key]);
                        }

                    }


                    if (property_exists($this,$parentService) && is_array($value) && count($value) && $key !== 'files') {
                        unset($arrKeyValue[$key]);
                        foreach ($value as $key_parent=>$value_list) {
                            if ($key_parent == 'id' || is_numeric($key_parent)) {
                                if (is_array($value_list)) {
                                    $value_list = $value_list[array_key_first($value_list)];
                                }


                                if ($value_list && Uuid::isValid($value_list)) {

                                    try {

                                       $parent = $this->$parentService->_getById($value_list);
                                        if ($parent) {
                                            $arrKeyValue[$key][] = $parent;
                                        }
                                    } catch (\Exception $e) {
                                        unset($arrKeyValue[$key]);
                                    }

                                }
                            }
                        }

                    }

                    if (!property_exists($this,$serviceName)) {
                        unset($arrKeyValue[$key]);
                    }
                }
            } else {


            }
        }

        return $arrKeyValue;
    }

    public function setAttributtes($key,array $arrKeyValue,$entity=null) {
        $serviceName = substr($key,0,-1)."Service";


        if ( array_key_exists($key,$arrKeyValue) ) {
            $newValuesCollect = new Collection();
            $values = $arrKeyValue[$key];
            unset($arrKeyValue[$key]);
            $arrKeyValue[$key]=[];

            if (!property_exists($this,$serviceName)) {
               return  $arrKeyValue;
            }

            if (is_array($values)) {

                foreach ($values as $value) {

                    if (property_exists($this->$serviceName,"_getById")) {
                        $obj =  $this->$serviceName->_getById($value);

                        if ($obj && !$newValuesCollect->contains($obj))
                        {
                            $newValuesCollect->add($obj);
                        }
                    }
                }
                $arrKeyValue[$key] = $newValuesCollect->all();
            }
        }

        return $arrKeyValue;
    }

    public function serialize($entity)
    {
        return $json = help()->jms->toJson($entity);
    }

    public function checkRoleUprav() {
        if (!($this->account->getRoles()->getService() == 'administrator' || $this->account->getRoles()->getService() == "upravlenie")) {
            throw new ApplicationException("Отказано в доступе ваши права:".$this->account->getRoles()->getName(),403);
        }
    }
}
