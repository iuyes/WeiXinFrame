<?php
class Weixin {
	public $token = '';
	public $setFlag = false;
	public $msgtype = 'text';
	public $msg = array();
	public function __construct($token) {
		$this->token = $token;
	}
	public function getMsg() {
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
		if (DEBUG) {
			$this->write_log($postStr);
		}
		if (!empty($postStr)) {
			$this->msg     = (array) simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
			$this->msgtype = strtolower($this->msg['MsgType']);
			$this->write_log($this->msg['Event']);
		}
	}
	public function makeText($text = '') {
		$CreateTime = time();
		$FuncFlag   = $this->setFlag ? 1 : 0;
		$textTpl    = "<xml>
			<ToUserName><![CDATA[{$this->msg['FromUserName']}]]></ToUserName>
			<FromUserName><![CDATA[{$this->msg['ToUserName']}]]></FromUserName>
			<CreateTime>{$CreateTime}</CreateTime>
			<MsgType><![CDATA[text]]></MsgType>
			<Content><![CDATA[%s]]></Content>
			<FuncFlag>%s</FuncFlag>
			</xml>";
		return sprintf($textTpl, $text, $FuncFlag);
	}
	public function makeNews($newsData = array()) {
		$CreateTime   = time();
		$FuncFlag     = $this->setFlag ? 1 : 0;
		$newTplHeader = "<xml>
			<ToUserName><![CDATA[{$this->msg['FromUserName']}]]></ToUserName>
			<FromUserName><![CDATA[{$this->msg['ToUserName']}]]></FromUserName>
			<CreateTime>{$CreateTime}</CreateTime>
			<MsgType><![CDATA[news]]></MsgType>
			<Content><![CDATA[%s]]></Content>
			<ArticleCount>%s</ArticleCount><Articles>";
		$newTplItem   = "<item>
			<Title><![CDATA[%s]]></Title>
			<Description><![CDATA[%s]]></Description>
			<PicUrl><![CDATA[%s]]></PicUrl>
			<Url><![CDATA[%s]]></Url>
			</item>";
		$newTplFoot   = "</Articles>
			<FuncFlag>%s</FuncFlag>
			</xml>";
		$Content      = '';
		$itemsCount   = count($newsData['items']);
		$itemsCount   = $itemsCount < 10 ? $itemsCount : 10; //微信公众平台图文回复的消息一次最多10条
		if ($itemsCount) {
			foreach ($newsData['items'] as $key => $item) {
				if ($key <= 9) {
					$Content .= sprintf($newTplItem, $item['title'], $item['description'], $item['picurl'], $item['url']);
				}
			}
		}
		$header = sprintf($newTplHeader, $newsData['content'], $itemsCount);
		$footer = sprintf($newTplFoot, $FuncFlag);
		return $header . $Content . $footer;
	}
	public function makeMusic($musicData = array()) {
		$CreateTime = time();
		$FuncFlag   = $this->setFlag ? 1 : 0;
		$musicTpl   = "<xml>
			<ToUserName><![CDATA[{$this->msg['FromUserName']}]]></ToUserName>
			<FromUserName><![CDATA[{$this->msg['ToUserName']}]]></FromUserName>
			<CreateTime>{$CreateTime}</CreateTime>
			<MsgType><![CDATA[music]]></MsgType>
			<Music>
			<Title><![CDATA[%s]]></Title>
			<Description><![CDATA[%s]]></Description>
			<MusicUrl><![CDATA[%s]]></MusicUrl>
			<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
			</Music>
			<FuncFlag>%s</FuncFlag>
			</xml>";
		return sprintf($musicTpl, $musicData['title'], $musicData['description'], $musicData['musicurl'], $musicData['hqmusicurl'], $FuncFlag);
	}
	public function reply($data) {
		if (DEBUG) {
			$this->write_log($data);
		}
		echo $data;
	}
	public function valid() {
		if ($this->checkSignature()) {
			if ($_SERVER['REQUEST_METHOD'] == 'GET') {
				echo $_GET['echostr'];
				exit;
			}
		} else {
			$this->write_log('认证失败');
			exit;
		}
	}
	private function checkSignature() {
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce     = $_GET["nonce"];
		$tmpArr    = array(
			$this->token,
			$timestamp,
			$nonce
		);
		sort($tmpArr);
		$tmpStr = implode($tmpArr);
		$tmpStr = sha1($tmpStr);
		if ($tmpStr == $signature) {
			return true;
		} else {
			return false;
		}
	}
	public function write_log($log) {
		sae_set_display_errors(false); //关闭信息输出
		sae_debug($log); //记录日志
		sae_set_display_errors(true); //记录日志后再打开信息输出，否则会阻止正常的错误信息的显示
	}
}
?>