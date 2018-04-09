<?php
namespace Home\Controller;
use Think\controller;

class MenuController extends Controller {
	private $access_token;
	
	/**
	 *	不考虑个性化菜单
	 */
	 
	function _initialize(){
		$this->access_token = get_accesstoken();
	}
	
	function index(){
		dump($this->access_token);
		echo 'it is ok';
	}
	
	//创建菜单
	function createMenu(){
		$access_token = $this->access_token;
		$url = 'https://api.weixin.qq.com/cgi-bin/menu/create?access_token='.$access_token;
		$menuJson =  '{
			"button":[{	
				"type":"click",
				"name":"今日歌曲",
				"key":"click"
			},{
				"name":"菜单",
				"sub_button":[{
					"type":"view",
					"name":"搜索",
					"url":"http://www.baidu.com/"
				},{
					"type":"view",
					"name":"视频",
					"url":"http://v.qq.com/"
				},{
					"type":"click",
					"name":"赞一下我们",
					"key":"V1001_GOOD"
				}]
			}]
		}';
		$result = request($url,'post',$menuJson);
		dump($result);
	}
	
	//删除菜单
	function deleteMenu(){
		$access_token = $this->access_token;
		$url = 'https://api.weixin.qq.com/cgi-bin/menu/delete?access_token='.$access_token;
		$result = request($url);
	}
	
	
	//查询菜单
	function getMenu(){
		$access_token = $this->access_token;
		$url = 'https://api.weixin.qq.com/cgi-bin/menu/get?access_token='.$access_token;
		$result = request($url);
	}
}


















