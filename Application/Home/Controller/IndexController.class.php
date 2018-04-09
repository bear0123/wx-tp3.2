<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
		$postStr = file_get_contents("php://input");

        if(!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$type = $postObj->MsgType;
            switch($type){
				case 'text':						//文本消息
					$this->getTextMessage($postObj);
					break;
				case 'image':						//图片消息
					$this->getImageMessage($postObj);
					break;		
				case 'voice':						//语音消息
					$this->getVoiceMessage($postObj);
					break;
				case 'video':						//视频消息
					$this->getVideoMessage($postObj);
					break;
				case 'shortvideo':					//小视频消息
					$this->getShortvideoMessage($postObj);
					break;
				case 'location':					//地理位置消息
					$this->getLocationMessage($postObj);
					break;
				case 'link':						//连接消息
					$this->getLinkMessage($postObj);
					break;
				case 'event':						//事件推送
					$event = $postObj->Event;
					if($event == 'subscribe'){					//订阅事件或者关注扫描带参数二维码事件
						sendTextMessage($postObj,'欢迎关注lulu的测试微信公众号');
					}else if($event == 'unsubscribe'){			//取消订阅事件或者
						
					}else if($event == 'SCAN'){					//未关注扫描带参数二维码事件
						
					}else if($event == 'LOCATION'){				//上报地理位置事件
						$latitude = $postObj->Latitude;						//地理位置纬度
						$longitude = $postObj->Longitude;					//地理位置经度
						$precision = $postObj->Precision;					//地理位置精度
						sendTextMessage($postObj,'你上报的位置经度为'.$longitude.'纬度为'.$latitude.'精度为'.$precision);
					}else if($event == 'CLICK'){				//点击菜单拉取消息时的事件推送
						$this->menuClick($postObj);
					}else if($event == 'VIEW'){					//点击菜单跳转链接时的事件推送
						
					}else{
						echo "success";
					}
					
				default:
					echo "success";
			}
        }else{
			
            echo "success";
	}
	}
	
	/*读取文本消息*/
	public function getTextMessage($postObj){
        $word = trim($postObj->Content);
		if(strpos($word,'天气') !== FALSE){					//天气预报
			$city = strstr($word,'天气',true);
			$Weather = A('weather');
			$result = json_decode($Weather->getThreeWeather(urlencode($city)),true);
			$watherWord = $result['results']['0']['location']['name'].'天气';
			foreach($result['results']['0']['daily'] as $k=>$v){
				$tmp .= "\n ".$v['date']."\n  最高气温:".$v['high'].',最低气温:'.$v['low']."\n  白天天气:".$v['text_day']."\n  晚间天气:".$v['text_night'];
			}
			$watherWord .= $tmp;
			$this->sendTextMessage($postObj,$watherWord);
		}elseif(strpos($word,'音乐') !== FALSE){			//音乐播放
			$keyword = strstr($word,'音乐',true);
			$Kugou = A('kugou');
			//$music = $Kugou->getMusic($keyword);
			$music['url'] = 'http://fs.w.kugou.com/201804071016/cb63b9bf4e3da296140c3f59fcfdc929/G117/M04/06/13/VZQEAFpEyweAFQMqADQ599bT5sM399.mp3';
			$music['title'] = '空空';
			$music['des'] = '空空如也-任然';
			$music['HQurl'] = '';
			$mediaId = 't-rHmR2dHYgrXmS8SZJBY4mbJgEj2NQN7RAIvT2Y4zk';
			$this->sendMusicMessage($postObj,$music,$mediaId);
		}else{
			$this->sendTextMessage($postObj,$word);
		}
	}
	
	/*点击菜单*/
	public function menuClick($postObj){
		$eventKey = $postObj->EventKey;
		$this->sendTextMessage($postObj,'你点击的是key为'.$eventKey.'的菜单');
	}
	
	/*发送文本消息*/
	public function sendTextMessage($postObj,$word){
		$ToUserName = $postObj->FromUserName;
        $FromUserName = $postObj->ToUserName;
		$time = time();				
		$msgType = "text";
		$textTpl = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <Content><![CDATA[%s]]></Content>
                    </xml>";
		$resultStr = sprintf($textTpl, $ToUserName, $FromUserName, $time, $msgType, $word);
		echo $resultStr;
	}
	
	/*发送音乐消息*/
	public function sendMusicMessage($postObj,$music,$mediaId){
		$ToUserName = $postObj->FromUserName;
        $FromUserName = $postObj->ToUserName;
		$time = time();				
		$msgType = "music";
		$textTpl = "<xml>
						<ToUserName>< ![CDATA[%s] ]></ToUserName>
						<FromUserName>< ![CDATA[%s] ]></FromUserName>
						<CreateTime>%s</CreateTime>
						<MsgType>< ![CDATA[%s] ]></MsgType>
						<Music>
							<Title>< ![CDATA[%s] ]></Title>
							<Description>< ![CDATA[%s] ]></Description>
							<MusicUrl>< ![CDATA[%s] ]></MusicUrl>
							<HQMusicUrl>< ![CDATA[%s] ]></HQMusicUrl>
							<ThumbMediaId>< ![CDATA[%s] ]></ThumbMediaId>
						</Music>
					</xml>";
		$resultStr = sprintf($textTpl, $ToUserName, $FromUserName, $time, $msgType, $music['title'], $music['dec'], $music['url'], $music['HQurl'], $mediaId);
		echo $resultStr;
	}
}
