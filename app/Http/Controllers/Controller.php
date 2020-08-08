<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use DB;
use Request;
use Request as Input;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function getDBSelectListSingle($table_name,$arr_select_field="*",$arr_where=array(),$order_by=""){
        
            $query = DB::table($table_name)
                         ->select($arr_select_field)
                         ->where($arr_where);
            
            if($order_by !=""){			 
                $query->orderByRaw($order_by);
            }
                         
            $arr_data = $query->get();
            $arr_data = objToArray($arr_data);
            return $arr_data[0];
    }

    function getDBSelectPluckList($table_name,$arr_select_field="*",$arr_where=array(),$order_by=""){
    	
		$query = DB::table($table_name)
					 ->where($arr_where);
		
		if($order_by !=""){			 
			$query->orderByRaw($order_by);
		}
		
		$arr_data = $query->pluck($arr_select_field[1],$arr_select_field[0])->toArray();
		//echo "<pre>";  print_r($arr_data); die;
		return $arr_data;
	}
}
