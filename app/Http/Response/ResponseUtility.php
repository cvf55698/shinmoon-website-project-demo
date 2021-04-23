<?php

namespace App\Http\Response;
	
use App\Http\Response\Response;

class ResponseUtility{
	
	public static function handle($response)
	{
		if($response instanceof Response){
			if($response->is_redirect){
				header("HTTP/1.1 301 Moved Permanently");
				header("location:".$response->redirect_url );
				$response->set_headers();
			}else if($response->is_view){
				header('Content-Type:text/html; charset=UTF-8');
				$response->set_headers();
				$smarty = load_smarty();
				foreach($response->view_param_mapping_arr as $param_name=>$param_value){
					$smarty->assign($param_name, $param_value);
				}
				
				$smarty->assign('error_message_arr',$response->error_message_arr);
				$smarty->assign('success_message_arr',$response->success_message_arr);
				$smarty->assign('warning_message_arr',$response->warning_message_arr);
				$smarty->display(VIEW_PATH."".$response->view_path.".php");
			}else if($response->is_html){
				header('Content-Type:text/html; charset=UTF-8');
				$response->set_headers();
				echo $response->response_content;
			}else if($response->is_text){
				header('Content-Type:text/plain; charset=UTF-8');
				$response->set_headers();
				echo $response->response_content;
			}else{
				header('Content-Type:'.$response->content_type.'; charset=UTF-8');
				$response->set_headers();
				echo $response->response_content;
			}	

			exit();
		}else{
			throw new \Exception();
		}
	}

}

?>
