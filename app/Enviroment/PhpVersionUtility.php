<?php

namespace App\Enviroment;
    
class PhpVersionUtility{

    public static function compare_php_version($to_compare)
    {
        $now_php_version_int_res = PHP_VERSION_ID;
        $now_php_version_int_3 = $now_php_version_int_res % 100;
        $now_php_version_int_res = (int) $now_php_version_int_res/100;
        $now_php_version_int_2 = $now_php_version_int_res % 100;
        $now_php_version_int_res = (int) $now_php_version_int_res/100;
        $now_php_version_int_1 = (int) $now_php_version_int_res;
        $now_php_version_arr = [$now_php_version_int_1,$now_php_version_int_2,$now_php_version_int_3];

        try{
            $to_compare_str_arr = explode('.',$to_compare);
            $to_compare_int_arr = [0,0,0];
            $len = count($to_compare_str_arr);
            for($i=0;$i<$len;++$i){
                $to_compare_int_arr[$i] = (int) $to_compare_str_arr[$i];
            }

            $compare_result = true;                
            for($i=0;$i<3;++$i){
                if($to_compare_int_arr[$i]>$now_php_version_arr[$i]){
                    $compare_result = false;
                    break;
                }
            }

            return $compare_result;
        }catch(\Exception | \Error $e){
            return null;
        }
        
    }

}

?>