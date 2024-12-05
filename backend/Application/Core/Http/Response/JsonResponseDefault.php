<?php
namespace Core\Http\Response;

use Illuminate\Support\Facades\Auth;

class JsonResponseDefault
{

    /**
     * @param $success
     * @param $data
     * @param $message
     * @param $code
     * @return mixed
     */
    public static function create($success, $data, $options, $message, $code)
    {
        if ($code == 204) {
            $code = 200;
        }

        $response['success'] = $success;
        $response['data'] = $data;

        if ($options ) {
            $response['options'] = $options;
        }

        $response['message'] = $message;
        $response['code'] = $code;

        if (Auth::user()) {
            //$response['account']  = help()->json->entityToJson(Auth::user());
        }


        $header = [$response['code'] => $response['message']];
        return response()->json($response,$code,$header);
    }
}
