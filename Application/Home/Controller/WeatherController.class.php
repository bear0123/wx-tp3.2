<?php
namespace Home\Controller;
use Think\Controller;
class WeatherController extends Controller {
	
	/*生成签名*/
	public function getSign($time,$uid){
		$key = C('XINZHI_API');
		$param = 'ts='.$time.'&uid='.$uid;
		$sign = urlencode(hmac_sha1($param,$key));
		return $sign;
	}
	
	/*天气*/
	public function getWeather($city){
		$api = 'https://api.seniverse.com/v3/weather/now.json';
		$time = time();
		$uid = C('XINZHI_UID');
		$url = $api.'?location='.$city.'&language=zh-Hans&unit=c&ts='.$time.'&uid='.$uid.'&sig='.$this->getSign($time,$uid);
		return request($url);
	}
	
	/*三天天气(免费用户只能获取3天的天气，付费用户能获取15天)
	 *
	 *	"date": "2015-09-20",             //日期
	 *  "text_day": "多云",               //白天天气现象文字
	 *	"code_day": "4",                  //白天天气现象代码
	 *	"text_night": "晴",               //晚间天气现象文字
	 *	"code_night": "0",                //晚间天气现象代码
	 *	"high": "26",                     //当天最高温度
	 *	"low": "17",                      //当天最低温度
	 *	"precip": "0",                    //降水概率，范围0~100，单位百分比
	 *	"wind_direction": "",             //风向文字
	 *	"wind_direction_degree": "255",   //风向角度，范围0~360
	 *	"wind_speed": "9.66",             //风速，单位km/h（当unit=c时）、mph（当unit=f时）
	 *	"wind_scale": ""                  //风力等级
	 *
	 **/
	public function getThreeWeather($city){
		$api = 'https://api.seniverse.com/v3/weather/daily.json';
		$time = time();
		$uid = C('XINZHI_UID');
		$url = $api.'?location='.$city.'&language=zh-Hans&unit=c&start=0&days=3&ts='.$time.'&uid='.$uid.'&sig='.$this->getSign($time,$uid);
		return request($url);
	}
	
	/*生活指数
	 *	dressing						//穿衣
	 *	car_washing						//洗车
	 *	flu								//感冒
	 *	sport							//运动
	 *	travel							//旅游
	 *	uv								//紫外线
	 */
	public function getLife($city){
		$api = 'https://api.seniverse.com/v3/life/suggestion.json';
		$time = time();
		$uid = C('XINZHI_UID');
		$url = $api.'?location='.$city.'&language=zh-Hans&ts='.$time.'&uid='.$uid.'&sig='.$this->getSign($time,$uid);
		return request($url);
	}
	
	
	public function test(){
		$city = I('city','深圳');
		$time = time();
		$uid = C('XINZHI_UID');
		//$weather = request('https://api.seniverse.com/v3/weather/now.json?key=9mzrnck0i34g9evt&location=beijing&language=zh-Hans&unit=c');
		//$weather = request('https://api.seniverse.com/v3/weather/now.json?location=北京&language=zh-Hans&unit=c&ts='.$time.'&uid='.$uid.'&sig='.$this->getSign($time,$uid));
		//dump($weather);
		//dump($this->getLife('beijing'));
		$result = json_decode($this->getThreeWeather('beijing'),true);
		$watherWord = $result['results']['0']['location']['name'].'天气';
		foreach($result['results']['0']['daily'] as $k=>$v){
			$tmp .= $v['date'].';白天天气:'.$v['text_day'].';晚间天气:'.$v['text_night'].';最高
				温度:'.$v['high'].';最低温度:'.$v['low'];	
		}
		$watherWord .= $tmp; 
		dump($watherWord);
	}
	
	public function test_xml(){
		$xml = 	'<xml>
					<ToUserName><![CDATA[toUser]]></ToUserName>
					<FromUserName><![CDATA[fromUser]]></FromUserName> 
					<CreateTime>1348831860</CreateTime>
					<MsgType><![CDATA[text]]></MsgType>
					<Content><![CDATA[this is a test]]></Content>
					<MsgId>1234567890123456</MsgId>
				</xml>';
		$postObj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
		echo $postObj->MsgType;
		$this->getLife($postObj);
		
		$word = '天气beijing';
		$tmpWord = explode(" ",$word);
		dump($tmpWord);
	}
	
}