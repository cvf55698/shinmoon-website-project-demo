<?php

namespace App\Http\Param;

class ParamFilterUtility{

    public static function get_int($param)
    {        
        try{
            $param_int = intval($param);
        }catch(\Exception | \Error $e){
            throw $e;
        }

        if($param_int<=0){
            throw new \Exception();
        }

        return $param_int;
    }

}

?>