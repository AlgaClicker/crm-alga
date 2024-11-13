<?php
namespace Infrastructure\Util;

class DadataHelper
{
    private $dadata;

    public function __construct()
    {

        $dadata = new \Dadata\DadataClient(env('DADATA_TOKEN'), env('DADATA_SECRET'));
    }

    public function dateFormat($format = 'd/m/Y', $add="", $date = 'now'){
        $date = new \DateTime($date, new \DateTimeZone(env('APP_TIMEZONE','+00:00')));
        if($add){
            $date->add(new \DateInterval($add));
        }
        return $date->format($format);
    }
}
