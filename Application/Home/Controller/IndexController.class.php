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
				default:
					echo "success";
			}
        }else{
            echo "success";
            exit;
        }
	}
	
	public function getTextMessage($postObj){
        $word = trim($postObj->Content);
		$tmpWord = explode($word," ");
		if($tmpWord['0'] == '天气'){
			$Weather = A('weather');
			$result = json_decode($Weather->getThreeWeather($tmpWord['1']),true);
			$watherWord = $result['results']['0']['location']['name'].'天气';
			foreach($result['results']['0']['daily'] as $k=>$v){
				$tmp .= $v['date'].';白天天气:'.$v['text_day'].';晚间天气:'.$v['text_night'].';最高
					温度:'.$v['high'].';最低温度:'.$v['low'];	
			}
			$watherWord .= $tmp;
			$this->sendTextMessage($postObj,$watherWord);
		}else{
			$this->sendTextMessage($postObj,$word);
		}
	}
	
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
}