<?php

namespace Core\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Laravel\Lumen\Routing\Controller as BaseController;
use Core\Http\Response\JsonResponseDefault;
use Laravel\Lumen\Http\Request;
use stdClass;

class Controller extends BaseController
{
    protected $applicationService;


    /**
     * Controller constructor.
     * @param $applicationService
     */
    public function __construct($applicationService, Request $request)
    {

        if (gettype($request->get('options')) === 'array') {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',

            ]);
            Log::info("Class 'Controller@__construct' check validate 'options'");
        }
        $this->applicationService = $applicationService;

    }

    public function serializeArray($entity)
    {
        return $json = help()->array->entityToArray($entity);
    }

    public function serialize($entity)
    {
        return $json = help()->jms->toJson($entity);
    }

    protected function sendResponseAccount($entity){
        return json_decode($this->serialize($entity));
    }

    protected function sendResponse($entity,$options=[]){

        if (is_array($entity) && array_key_exists('data',$entity) && array_key_exists('options',$entity)) {
            $data = json_decode($this->serialize($entity['data']));
                return JsonResponseDefault::create(true, $data, $entity['options'], 'success', 200);
        }

        if (is_object($entity) && get_class($entity) === "stdClass" ) {
            return JsonResponseDefault::create(true, $entity, $options, 'success', 200);
        }

        if ($entity) {
            $data = json_decode($this->serialize($entity));
            return JsonResponseDefault::create(true, $data, $options, 'success', 200);
        } else {
            return JsonResponseDefault::create(true, null, $options, 'empty', 204);
        }
    }



    public function _getList(Request $request) {

        if ($request->get('id')) {
            $applicationData = $this->applicationService->_getById($request->get('id'));
            return  $this->sendResponse($applicationData);
        }


        if (array_key_exists('options', $request->all())) {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',
            ]);
        }

        $applicationData = $this->applicationService->_getAllBy(['parent'=>null],$request->get('options'));
        return  $this->sendResponse($applicationData['data'],$applicationData['options']);

    }


    private function getClassDomain()
    {
        $class = new \ReflectionClass(get_class($this));
        $namespace = $class->getNamespaceName();
        $domain = str_replace('Application\\Core\Http\\Controllers\\','',$namespace);

        return $domain;
    }
}
