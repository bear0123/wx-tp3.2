<?php
namespace Home\Controller;
use Think\controller;

class MaterialController extends Controller {
	private $access_token;

	function _initialize(){
		$this->access_token = get_accesstoken();
	}
	
	//微信上传永久缩略图素材接口
	function index(){
		$access_token = $this->access_token;
		$url = "https://api.weixin.qq.com/cgi-bin/material/add_material";
		$path = getcwd().'/Public/image/2.jpg';
		$data = array(
					'media' => new \CURLFile($path),
					'access_token' => $access_token,
					'type' => 'thumb'
				);
		$result = request($url,'post',$data);
		dump($result);
	}
	
	//微信上传永久缩略图素材接口(表单上传)
	function form(){
		$access_token = $this->access_token;
		dump($access_token);
		$this->assign('access_token',$access_token);
		//$this->display();
	}

	//获取永久素材列表
	function getMaterialList(){
		$access_token = $this->access_token;
		$url = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token='.$access_token;
		$data = array(
			"access_token" => $access_token,
			"type" => "image",
			"offset" => 0,
			"count" => 2	
		);
		$data2 = '{"type":"image","offset":"0","count":"20"}';
		$result = request($url,'post',$data2);
		dump(json_decode($result,true));
	}
	
	//获取永久素材
	function getMaterial(){
		$access_token = $this->access_token;
		$url = 'https://api.weixin.qq.com/cgi-bin/material/get_material?access_token='.$access_token;
		$data = '{"media_id":"t-rHmR2dHYgrXmS8SZJBY6ZcJX248O7E2GtilJOokAs"}';
		$result = request($url,'post',$data);
		echo $result;
		//dump(json_decode($result,true));
	}

}


















