<?php
namespace Home\Controller;
use Think\Controller;

class MessageController extends Controller {
	private $access_token;
	
	function _initialize(){
		$this->access_token = get_accesstoken();
	} 
	
	//客服 (测试号应该不能配置客服)
	function CustomerService(){
		$access_token = $this->access_token;
		dump($access_token);
		$url = 'https://api.weixin.qq.com/customservice/kfaccount/add?access_token='.$access_token;
		$data = '{
			"kf_account":"test",
			"nickname":"客服1",
			"passward":"123456",
		}';
		//$result = request($url,'post',$data);
	}
}









