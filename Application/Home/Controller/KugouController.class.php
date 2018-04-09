<?php
namespace Home\Controller;
use Think\Controller;
class KugouController extends Controller{
	
	/**
	 *		@param	$keyword:搜索关键词		$page:页数		$pagesize:每页个数
	 *		@dec	根据关键词返回酷狗音乐api数据
	 *		@return	Array OR FALSE
	 *		@author	bear0123
	 *		@time	2018.04.05
	 **/
	 
	protected function getHash($keyword,$page=1,$pagesize=10){
		$urlArray = array(
			'keyword' 	=> $keyword,
			'page' 		=> $page,
			'pagesize' 	=> $pagesize,
			'platform' 	=> 'WebFilter',
			'_' 		=> time(),
		);
		$url = 'http://songsearch.kugou.com/song_search_v2?'.http_build_query($urlArray);
		//初始化
		$curl = curl_init();
		//设置抓取的url
		curl_setopt($curl, CURLOPT_URL, $url);
		//设置头文件的信息作为数据流输出
		curl_setopt($curl, CURLOPT_HEADER, 0);
		//设置获取的信息以文件流的形式返回，而不是直接输出。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		//执行命令
		$data = curl_exec($curl);
		//格式化数据
		$result = json_decode($data,true);
		//关闭URL请求
		curl_close($curl);
		//显示获得的数据
		return $result['data']['lists'];
	}


	/**
	 *		@param	$hash:歌曲hash值		$album_id:专辑ID
	 *		@dec	根据歌曲hash值返回酷狗音乐歌曲地址及专辑信息
	 *		@return	Array OR FALSE
	 *		@author	bear0123
	 *		@time	2018.04.05
	 **/
	 
	protected function getPalyUrl($hash,$album_id){
		$urlArray = array(
			'r' 		=> 'play/getdata',
			'hash' 		=> $hash,
			'album_id' 	=> $album_id,
			'_' 		=> time(),
		);
		$url = 'http://www.kugou.com/yy/index.php?'.http_build_query($urlArray);
		//初始化
		$curl = curl_init();
		//设置抓取的url
		curl_setopt($curl, CURLOPT_URL, $url);
		//设置头文件的信息作为数据流输出
		curl_setopt($curl, CURLOPT_HEADER, 0);
		//设置获取的信息以文件流的形式返回，而不是直接输出。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		//执行命令
		$data = curl_exec($curl);
		//格式化数据
		$result = json_decode($data,true);
		//关闭URL请求
		curl_close($curl);
		//显示获得的数据
		return $result['data'];
	}
	
	/**
	 *		@param	$str:歌曲名称
	 *		@dec	根据歌曲名称值返回微信所需的歌曲信息
	 *		@return	Array OR FALSE
	 *		@author	bear0123
	 *		@time	2018.04.06
	 **/
	public function getMusic($str){
		$hash = $this->getHash($str);
		$palyUrl = $this->getPalyUrl($hash[0]['FileHash'],$hash[0]['AlbumID']);
		$result['title'] = $palyUrl['song_name'];
		$result['dec'] = $palyUrl['audio_name'];
		$result['url'] = $palyUrl['play_url'];
		$result['HQurl'] = $palyUrl['play_url'];
		return $result;
	}
}	
