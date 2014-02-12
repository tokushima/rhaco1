<?php
Rhaco::import('network.http.ServiceRestAPIBase');
Rhaco::import('tag.model.TemplateFormatter');
Rhaco::import('tag.model.SimpleTag');
Rhaco::import('lang.Variable');
/**
 * Lingr API 使用ライブラリ
 *
 * @author  SHIGETA Takeshiro <shigepon0@gmail.com>
 * @license New BSD License
 */
class LingrAPI extends ServiceRestAPIBase {
	
	var $apikey = '';
	var $baseurl = 'http://www.lingr.com/api/';
	var $session;
	var $ticket;
	var $counter;
	var $method;
	var $url;
	var $errorcode;
	
	function LingrAPI($apikey=''){
		parent::ServiceRestAPIBase();
		if($apikey) $this->apikey = $apikey;
		$this->__init__();
	}
	
	function __init__(){
		$this->createSession();
	}
	
	function createSession(){
		$this->url = Url::parseAbsolute($this->baseurl,'./session/create/');
		$response = $this->sendPostCommand(array('api_key'=>$this->apikey));
		if($this->isOk($response)){
			$this->session = $response->getInValue('session');
			return true;
		}else{
			$this->getError($response);
			return false;
		}
	}
	
	function setTicket($response){
		$this->ticket = $response->getInValue('ticket');
	}
	
	function setCounter($response){
//	  $this->counter = $response->getInValue('counter');
// <status>ok</status> であっても <counter> タグが必ず存在するとは限らないので存在しているときのみ
// カウンタ更新するよう変更した。（observerがタイムアウトで返ってきたときを想定）
		$counter = $response->getInValue('counter');
		if(!empty($counter)) $this->counter = $counter;
	}
// 外部から明示的にカウンタを設定する為に追加
	function setCounterDirect($counter){
		$this->counter = $counter;
	}
	
	function login($id,$password){
		$this->url = Url::parseAbsolute($this->baseurl,'./auth/login/');
		$response = $this->sendPostCommand(array('email'=>$id,'password'=>$password));
		return $this->isOk($response);
	}
	
	function logout(){
		$this->url = Url::parseAbsolute($this->baseurl,'./auth/logout/');
		$response = $this->sendPostCommand();
		return $this->isOk($response);
	}
	
	function setNickname($nickname){
		$this->url = Url::parseAbsolute($this->baseurl,'./room/set_nickname/');
		$response = $this->sendPostCommand(array('nickname'=>$nickname));
		return $this->isOk($response);
	}
	
	function enterRoom($roomid,$nickname='',$passwd='',$itempotent=false){
		$this->url = Url::parseAbsolute($this->baseurl,'./room/enter/');
		$param = array('id'=>$roomid);
		if($passwd) $param['password'] = $passwd;
		if($nickname) $param['nickname'] = $nickname;
		if($itempotent) $param['itempotent'] = true;
		$response = $this->sendPostCommand($param);
		if($this->isOk($response)){
			$this->setTicket($response);
			$this->setCounter($response);
			return array('occupants'=>$this->_getOccupants($response));
		}else{
			return array();
		}
	}
	
	function getUserInfo(){
		$this->url = Url::parseAbsolute($this->baseurl,'./user/get_info/');
		$response = $this->sendGetCommand();
		if($result = $this->isOk($response)){
			$result =  array('user'=>array(
			'id'=>$response->getInValue('user_id'),
			'email'=>$response->getInValue('email'),
			'defaultNickname'=>$response->getInValue('default_nickname'),
			'description'=>$response->getInValue('description'),
			'counter'=>$response->getInValue('counter')
			),
			'owenedRoom'=>$this->_getRooms($response,'owned_'),
			'favoriteRoom'=>$this->_getRooms($response,'favorite_'),
			'monitoredRoom'=>$this->_getRooms($response,'monitored_'),
			'visitedRoom'=>$this->_getRooms($response,'visited_'),
			'occupiedRoom'=>$this->_getRooms($response,'occupied_')
			);
		}
		return $result;
	}
	
	function getRoomInfo($roomid,$counter='',$user_messages_only=false,$passwd=''){
		$this->url = Url::parseAbsolute($this->baseurl,'./room/get_info/');
		$param = array('id'=>$roomid);
		if($counter) $param['counter'] = $counter;
		if($user_messages_only) $param['user_messages_only'] = true;
		if($passwd) $param['password'] = $passwd;
		$response = $this->sendGetCommand($param);
		if($result = $this->isOk($response)){
			$result =  array(
			'id'=>$response->getInValue('id'),
			'name'=>$response->getInValue('name'),
			'description'=>$response->getInValue('description'),
			'url'=>$response->getInValue('url'),
			'icon_url'=>$response->getInValue('icon_url'),
			'counter'=>$response->getInValue('counter'),
			'max_user_message_id'=>$response->getInValue('max_user_message_id')
			);
			return array('room'=>$this->_getRooms($rsponse),'messages'=>$this->_getMessages($response),'occupants'=>$this->_getOccupants($response));
		}
		return $result;
	}
	
	function startObserving(){
		$this->url = Url::parseAbsolute($this->baseurl,'./user/start_observing/');
		$response = $this->sendPostCommand();
		if($this->isOk($response)){
			$this->setCounter($response);
			return true;
		}else{
			return false;
		}
	}
	
	function observe(){
		$this->url = Url::parseAbsolute($this->baseurl,'./room/observe/');
		$response = $this->sendGetCommand(array('session'=>$this->session,'ticket'=>$this->ticket,'counter'=>$this->counter));
		if($this->isOk($response)){
			$this->setCounter($response);
			return array(
			'messages'=>$this->_getMessages($response),
			'occupants'=>$this->_getOccupants($response)
			);
		}else{
			return false;
		}
	}
	
	function stopObserving(){
		$this->url = Url::parseAbsolute($this->baseurl,'./user/stop_observing/');
		$response = $this->sendPostCommand();
		if($this->isOk($response)){
			return array(
				'messages'=>$this->_getMessages($response),
				'occupants'=>$this->_getOccupants($response)
			);
		}else{
			return false;
		}
	}
	
	function getMessages($user_messages_only=false){
		$this->url = Url::parseAbsolute($this->baseurl,'./room/get_messages/');
		$param = array('session'=>$this->session,'ticket'=>$this->ticket,'counter'=>$this->counter);
		if($user_messages_only) $param['user_messages_only'] = $user_messages_only;
		$response = $this->sendGetCommand($param);
		if($this->isOk($response)){
			$this->setCounter($response);
			return array(
				'messages'=>$this->_getMessages($response),
				'occupants'=>$this->_getOccupants($response)
			);
		}else{
			return false;
		}
	}
	
	function getArchives($roomid,$date=array('year'=>'','month'=>'','day'=>''),$user_messages_only=false,$passwd=''){
		$this->url = Url::parseAbsolute($this->baseurl,'./room/get_archives/');
		$param = array('api_key'=>$this->apikey,'id'=>$roomid);
		if($date['year'] && $date['month'] && $date['day']){
			$param = array_merge($param,$date);
		}
		if($user_messages_only) $param['user_message_only'] = $user_messages_only;
		if($passwd) $param['password'] = $passwd;
		$response = $this->sendGetCommand($param);
		if($this->isOk($response)){
			return array(
				'messages'=>$this->_getMessages($response),
				'room'=>$this->_getRooms($response)
			);
		}else{
			return false;
		}
	}
	
	function say($message='',$occupant_id=''){
		/**
		 * $lingr = new LingrAPI('');
		 * $result = $lingr->enterRoom('rhaco','hoge');
		 * assert(array_key_exists('occupants',$result));
		 * assert($lingr->say('This is a test message from rhacolibs Lingr API'));
		 * assert($lingr->exitRoom());
		 * assert($lingr->destroySession());
		 */
		$this->url = Url::parseAbsolute($this->baseurl,'./room/say/');
		$param = array('message'=>StringUtil::encode($message));
		if($occupant_id) $param['occupant_id'] = $occupant_id;
		$response = $this->sendPostCommand($param);
		return $this->isOk($response);
	}
	
	function getHotRooms($count=10){
		/**
		 * $lingr = new LingrAPI();
		 * $result = $lingr->getHotRooms();
		 * //eq("",$result);
		 * eq(10,count($result["rooms"]));
		 * $result = $lingr->getHotTags();
		 * eq(10,count($result["tags"]));
		 * $result = $lingr->getAllTags();
		 * eq(10,count($result["tags"]));
		 * $result = $lingr->search("rhaco");
		 * eq("rhaco-ja",$result["rooms"][0]["id"]);
		 * $result = $lingr->searchTags("conveyor");//no tags
		 * $result = $lingr->searchArchives("hello");//false
		 * //$result = $lingr->getArchives("rhaco");
		 * //eq("",$result);
		 */
		$this->url = Url::parseAbsolute($this->baseurl,'./explore/get_hot_rooms/');
		$response = $this->sendGetCommand(array('api_key'=>$this->apikey,'count'=>$count));
		if($this->isOk($response)){
			return array('rooms'=>$this->_getRooms($response));
		}else{
			return false;
		}
	}
	
	function getNewRooms($count=10){
		$this->url = Url::parseAbsolute($this->baseurl,'./explore/get_new_rooms/');
		$response = $this->sendGetCommand(array('api_key'=>$this->apikey,'count'=>$count));
		if($this->isOk($response)){
			return array('rooms'=>$this->_getRooms($response));
		}else{
			return false;
		}
	}	
	function getHotTags($count=10){
		$this->url = Url::parseAbsolute($this->baseurl,'./explore/get_hot_tags/');
		$response = $this->sendGetCommand(array('api_key'=>$this->apikey,'count'=>$count));
		if($this->isOk($response)){
			return array('tags'=>$this->_getTags($response));
		}else{
			return false;
		}
	}
	
	function getAllTags($count=10){
		$this->url = Url::parseAbsolute($this->baseurl,'./explore/get_all_tags/');
		$response = $this->sendGetCommand(array('api_key'=>$this->apikey,'count'=>$count));
		if($this->isOk($response)){
			return array('tags'=>$this->_getTags($response));
		}else{
			return false;
		}
	}
	
	function search($query=''){
		$this->url = Url::parseAbsolute($this->baseurl,'./explore/search/');
		$response = $this->sendGetCommand(array('api_key'=>$this->apikey,'q'=>$query));
		if($this->isOk($response)){
			return array('rooms'=>$this->_getRooms($response));
		}else{
			return false;
		}
	}
	
	function searchTags($query=''){
		$this->url = Url::parseAbsolute($this->baseurl,'./explore/search_tags/');
		$response = $this->sendGetCommand(array('api_key'=>$this->apikey,'q'=>$query));
		if($this->isOk($response)){
			return array('rooms'=>$this->_getRooms($response));
		}else{
			return false;
		}
	}
	
	function searchArchives($query='',$count=10,$roomid=''){
		$this->url = Url::parseAbsolute($this->baseurl,'./room/search_archives/');
		$param = array('api_key'=>$this->apikey,'q'=>$query,'count'=>$count);
		if($roomid) $param['id'] = $roomid;
		$response = $this->sendGetCommand($param);
		if($this->isOk($response)){
			return array(
				'messages'=>$this->_getMessages($response)
			);
		}else{
			return false;
		}
		
	}
	
	function exitRoom(){
		$this->url = Url::parseAbsolute($this->baseurl,'./room/exit/');
		$response = $this->sendPostCommand();
		if($this->isOk($response)){
			$this->ticket = '';
			return true;
		}else{
			return false;
		}
	}
	
	function destroySession(){
		$this->url = Url::parseAbsolute($this->baseurl,'./session/destroy/');
		$response = $this->sendPostCommand();
		if($this->isOk($response)){
			$this->session = '';
			return true;
		}else{
			return false;
		}
	}
	
	function isOk($response){
		if(Variable::istype("SimpleTag",$response) && $response->getInValue("status")=='ok'){
			$this->errorcode = 0;
			return true;
		}elseif(Variable::istype("SimpleTag",$response) && $response->getInValue("status")!='ok'){
			$this->getError($response);
			return false;
		}else{
			L::warning("Response is not SimpleTag object.");
			return false;
		}
	}
	
	function getError($response){
		$errormessage = "";
		
		if(Variable::istype('SimpleTag',$response)){
			$error = $response->getInValue('code');
		}else{
			$error = 1;
		}
		$this->errorcode = $error;
		switch($error){
			case 1:
				$errormessage = 'request incomplete';
			case 100:
				$errormessage = 'invalid HTTP method';
				break;
			case 102:
				$errormessage = 'invalid session';
				break;
			case 104:
				$errormessage = 'invalid response format';
				break;
			case 108:
				$errormessage = 'invalid room id';
				break;
			case 109:
				$errormessage = 'invalid ticket';
				break;
			case 112:
				$errormessage = 'rate limiter';
				break;
			case 114:
				$errormessage = 'no counter parameter';
				break;
			case 116:
				$errormessage = 'invalid room password';
				break;
			case 117:
				$errormessage = 'room requires password';
				break;
			case 120:
				$errormessage = 'invalid encoding';
				break;
			case 122:
				$errormessage = 'repeated counter';
				break;
			default:
		}
		if(!empty($errormessage)) L::warning($errormessage);
	}
	
	function sendGetCommand($param=array()){
		if(!empty($this->session) && empty($param['session']))$param['session'] = $this->session;
		if(!empty($this->ticket) && empty($param['ticket'])) $param['ticket'] = $this->ticket;
		if(SimpleTag::setof($response,$this->get($param),"response")){
			return $response;
		}else{
			return false;
		}
	}
	
	function sendPostCommand($param=array()){
		if(!empty($this->session) && empty($param['session']))$param['session'] = $this->session;
		if(!empty($this->ticket) && empty($param['ticket'])) $param['ticket'] = $this->ticket;
		L::debug($param);
		if(SimpleTag::setof($response,$this->post($param),"response")){
			return $response;
		}else{
			return false;
		}
	}
	
	function _getMessages($response){
		if($response->getIn('message')){
			$messages = array();
			foreach($response->getIn('message') as $message){
				$messages[] = array(
					'id'=>$message->getInValue('id'),
					'type'=>$message->getInValue('type'),
					'occupant_id'=>$message->getInValue('occupant_id'),
					'nickname'=>$message->getInValue('nickname'),
					'source'=>$message->getInValue('source'),
					'client_type'=>$message->getInValue('client_type'),
					'icon_url'=>$message->getInValue('icon_url'),
					'timestamp'=>$message->getInValue('timestamp'),
					'text'=>$message->getInValue('text')
				);
			}
			return $messages;
		}
	}
	
	function _getOccupants($response){
		if($response->getIn('occupant')){
			$occupants = array();
			foreach($response->getIn('occupant') as $occupant){
				$occupants[] = array(
					'id'=>$occupant->getInValue('id'),
					'nickname'=>$occupant->getInValue('nickname'),
					'source'=>$occupant->getInValue('source'),
					'client_type'=>$occupant->getInValue('client_type'),
					'icon_url'=>$occupant->getInValue('icon_url'),
					'timestamp'=>$occupant->getInValue('timestamp'),
					'description'=>$occupant->getInValue('description')
				);
			}
			return $occupants;
		}
	}
	
	function _getRooms($response,$extraTag=''){
		if($response->getIn($extraTag.'rooms')){
			$rooms = array();
			foreach($response->getIn($extraTag.'room') as $room){
				$rooms[] = array(
					'id'=>$room->getInValue('id'),
					'name'=>$room->getInValue('name'),
					'description'=>$room->getInValue('description'),
					'url'=>$room->getInValue('url'),
					'icon_url'=>$room->getInValue('icon_url'),
					'timestamp'=>$room->getInValue('timestamp'),
					'counter'=>$room->getInValue('counter'),
					'max_user_message_id'=>$room->getInValue('max_user_message_id'),
					'tags'=>$this->_getTags($room)
				);
			}
			return $rooms;
		}elseif($room = $response->getIn($extraTag.'room')){
			$room = $room[0];
			$rooms[] = array(
				'id'=>$room->getInValue('id'),
				'name'=>$room->getInValue('name'),
				'description'=>$room->getInValue('description'),
				'url'=>$room->getInValue('url'),
				'icon_url'=>$room->getInValue('icon_url'),
				'timestamp'=>$room->getInValue('timestamp'),
				'counter'=>$room->getInValue('counter'),
				'max_user_message_id'=>$room->getInValue('max_user_message_id'),
				'tags'=>$this->_getTags($room)
			);
			return $rooms;
		}
	}
	
	function _getTags($response){
		if($response->getIn('tags')){
			$tags = array();
			foreach($response->getIn('tag') as $tag){
				$tags[] = array(
					'id'=>$tag->getInValue('id'),
					'name'=>$tag->getInValue('name'),
					'display_name'=>$tag->getInValue('display_name'),
					'url'=>$tag->getInValue('url'),
					'rank'=>$tag->getInValue('rank')
				);
			}
			return $tags;
		}
	}
	
	function getCode(){
		return $this->errorcode;
	}
}