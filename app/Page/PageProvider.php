<?php

namespace App\Page;

use App\Database\DatabaseUtility;
use App\Result\ResultData;

class PageProvider{

    public static function get_page($request)
    {
        $page = $request->input("page");
        if($page==null){
            return 1;
        }

        $page_int = -1;
        try{
            $page_int = intval($page);
        }catch(\Exception | \Error $e){
            return 1;
        }

        if($page_int<=0){
            return 1;
        }

        return $page_int;
    }

    public static function get_per_page($key)
    {
        $shop_per_page_config = (require CONFIG_PATH."shop.php")['per_page'];
        if(array_key_exists($key,$shop_per_page_config)){
            $per_page = $shop_per_page_config[$key];
        }else{
            return 5;
        }

        $per_page_int = -1;
        try{
            $per_page_int = intval($per_page);
        }catch(\Exception | \Error $e){
            return 5;
        }

        if($per_page_int<=0){
            return 5;
        }

        return $per_page_int;
    }

    public static function get_all_row_len($table_name,$wheres_condition_clause_arr = [],$where_clause_separator = 'or')
    {
        $db = DatabaseUtility::getInstance();
        $select_table_len_sql = "select count(*) as len from $table_name";
        if(count($wheres_condition_clause_arr)!=0){
            $select_table_len_sql = $select_table_len_sql." where ".implode(" $where_clause_separator ",$wheres_condition_clause_arr); 
        }

        $select_table_len_sql = $select_table_len_sql." ;";
        $select_table_len_result = $db->query($select_table_len_sql);
        if(!$select_table_len_result->getSuccess()){
            throw new \Exception(''.$conn->error);
        }

        $select_len_row = $select_table_len_result->getData()['rows'][0];
        $all_row_len = (int) $select_len_row['len'];
        return $all_row_len;
    }

    public static function get_page_data($page,$per_page,$all_row_len)
    {
        $max_mid_page_tab_len = (require CONFIG_PATH."shop.php")['max_mid_page_tab_len'];
        if(!isset($max_mid_page_tab_len)){
            $max_mid_page_tab_len = 5;
        }else{
            try{
                $max_mid_page_tab_len = intval($max_mid_page_tab_len);
                if($max_mid_page_tab_len<=0){
                    $max_mid_page_tab_len = 6;
                }

            }catch(\Exception | \Error $e){
                $max_mid_page_tab_len = 7;
            }
        }

        $first_page = 1;
        $last_page = (int)($all_row_len/$per_page);
        $last_page += ( (((int)($all_row_len%$per_page))!=0) ? 1 : 0);
        $this_page = $page;
        if( ($this_page>$last_page) || ($this_page<1)  ){
            $this_page = 1;
        }

        $has_next_page = true;
        $next_page = $this_page + 1;
        if($next_page>$last_page){
            $has_next_page = false;
        }

        $has_past_page = true;
        $past_page = $this_page - 1;
        if($past_page<1){
            $has_past_page = false;
        }

        $mid_page_arr = [];
        if($last_page<=$max_mid_page_tab_len){
            $i = 1;
            while($i<=$last_page){
                array_push($mid_page_arr,$i);
                $i++;
            }

        }else{
            $helf = (int) ($max_mid_page_tab_len/2);
            $helf_mod = (int) ($max_mid_page_tab_len%2);
            $left_bound = $this_page - $helf;
            $right_bound = $this_page + $helf;
            if($helf_mod==0){
                $left_bound = $left_bound + 1;
            }

            if($left_bound<1){
                $diff = 1-$left_bound;
                $left_bound = 1;
                $right_bound += $diff;
            }else if($right_bound>$last_page){
                $diff = $right_bound - $last_page;
                $right_bound = $last_page;
                $left_bound -= $diff;
            }

            $i = $left_bound;
            while($i<=$right_bound){
                array_push($mid_page_arr,$i);
                $i++;
            }

        }

        $page_data = [
            'first_page'=>$first_page,
            'last_page'=>$last_page,
            'this_page'=>$this_page,
            'has_next_page'=>$has_next_page,
            'next_page'=>$next_page,
            'has_past_page'=>$has_past_page,
            'past_page'=>$past_page,
            'mid_page_arr'=>$mid_page_arr,
        ];

        return $page_data;
    }

    public static function get_page_query_data($page,$per_page_key,$table_name,$wheres_condition_clause_arr = [])
    {
        $per_page = static::get_per_page($per_page_key);
        $all_row_len = static::get_all_row_len($table_name,$wheres_condition_clause_arr);
        $page_data = static::get_page_data($page,$per_page,$all_row_len);
        $this_page = (int) $page_data['this_page'];
        $offset = (int) ($per_page*($this_page-1));
        return new ResultData(true,null,['per_page'=>$per_page,'page_data'=>$page_data,'offset'=>$offset,]);
    }

}

?>