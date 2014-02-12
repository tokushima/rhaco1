<?php
Rhaco::import("network.http.ServiceRestAPIBase");
Rhaco::import('lang.Variable');

/**
 * get Wassr Account => http://wassr.jp/
 *
 * @author Takuya Sato
 * @license New BSD License
 * @copyright Copyright 2007- rhaco.org All rights reserved.
 */
class WassrAPI extends ServiceRestAPIBase
{
	var $base_url = "http://api.wassr.jp";
	var $format = "xml";
	var $api_name = "rhaco";
	
	var $login = "";
	var $user_id = "";
	var $password = "";
	var $url = "";
	var $cmd1 = "";
	var $cmd2 = "";
	var $target = "";
	
	function WassrAPI($login = "", $password = ""){
		parent::ServiceRestAPIBase();
		$this->login = $login;
		$this->password = $password;
	}
	
	function status_public_timeline($since_id = "", $iscache=false) {
		$this->createCmd("public_timeline", "statuses");
		$pTag = new SimpleTag();
		$pTag->set($this->get(array(),$iscache), "statuses");
		
		$error = $this->getErrorMsg($pTag);
		if ($error) return $error;
		
		return $this->parseStatusResults($pTag);
	}
	
	function status_friends_timeline($user=null, $since="", $iscache=false) {
		$this->authRequire();
		if (is_null($user)) {
			$this->createCmd("friends_timeline", "statuses");
		} else {
			$this->createCmd($user, "statuses", "friends_timeline");
		}
		
		$options = $this->buildOptions(array());
		
		$pTag = new SimpleTag();
		$pTag->set($this->get($options,$iscache), "statuses");
		
		$error = $this->getErrorMsg($pTag);
		if ($error) return $error;
		
		return $this->parseStatusResults($pTag);
	}
	
	function status_user_timeline($user=null, $iscache=false) {
		if (is_null($user)) {
			$this->authRequire();
			$this->createCmd("user_timeline", "statuses");
		} else {
			$this->createCmd($user, "statuses", "user_timeline");
		}
		$pTag = new SimpleTag();
		$pTag->set($this->get(array(),$iscache), "statuses");
		
		$error = $this->getErrorMsg($pTag);
		if ($error) return $error;
		
		return $this->parseStatusResults($pTag);
	}
	
	function status_sl_timeline($iscache=false) {
		if (is_null($user)) {
			$this->authRequire();
			$this->createCmd("sl_timeline", "statuses");
		} else {
			$this->createCmd($user, "statuses", "sl_timeline");
		}
		$pTag = new SimpleTag();
		$pTag->set($this->get(array(),$iscache), "statuses");
		
		$error = $this->getErrorMsg($pTag);
		if ($error) return $error;
		
		return $this->parseStatusResults($pTag);
	}
	
	function status_show($user, $iscache=false) {
		$this->createCmd($user, "statuses", "show");
		$pTag = new SimpleTag();
		$pTag->set($this->get(array(),$iscache), "statuses");
		
		$error = $this->getErrorMsg($pTag);
		if ($error) return $error;
		
		return $this->parseStatusResults($pTag);
	}
	
	function status_update($status, $reply_status_rid = null) {
		$this->authRequire();
		
		$this->createCmd("update", "statuses", "", "json");
		$pTag = new SimpleTag();
		$option_array = array("source"=>$this->api_name, "status"=>$status);
		if ($reply_status_rid != null) {
			$option_array['reply_status_rid'] = $reply_status_rid;
		}
		$options = $this->buildOptions($option_array);
		$reply = $this->post($options);
		$reply = Variable::parseJson($reply);
		
		return $reply;
	}
	
	function todo_list($page = 0, $done_fg = null, $iscache=false) {
		$this->authRequire();
		
		$this->createCmd("list", "todo", "", "json");
		$pTag = new SimpleTag();
		$option_array = array("page"=>$page);
		if ($done_fg !== null) {
			$option_array['done_fg'] = $done_fg;
		}
		$options = $this->buildOptions($option_array);
		$reply = $this->get($options, $iscache);
		$reply = Variable::parseJson($reply);
		
		return $reply;
	}
	
	function todo_add($body) {
		$this->authRequire();
		
		$this->createCmd("add", "todo", "", "json");
		$pTag = new SimpleTag();
		$option_array = array("body"=>$body);
		$options = $this->buildOptions($option_array);
		$reply = $this->post($options);
		$reply = Variable::parseJson($reply);
		
		return $reply;
	}
	
	function todo_start($todo_rid) {
		$this->authRequire();
		
		$this->createCmd("add", "todo", "", "json");
		$pTag = new SimpleTag();
		$option_array = array("todo_rid"=>$todo_rid);
		$options = $this->buildOptions($option_array);
		$reply = $this->post($options);
		$reply = Variable::parseJson($reply);
		
		return $reply;
	}
	
	function todo_stop($todo_rid) {
		$this->authRequire();
		
		$this->createCmd("stop", "todo", "", "json");
		$pTag = new SimpleTag();
		$option_array = array("todo_rid"=>$todo_rid);
		$options = $this->buildOptions($option_array);
		$reply = $this->post($options);
		$reply = Variable::parseJson($reply);
		
		return $reply;
	}
	
	function todo_done($todo_rid) {
		$this->authRequire();
		
		$this->createCmd("done", "todo", "", "json");
		$pTag = new SimpleTag();
		$option_array = array("todo_rid"=>$todo_rid);
		$options = $this->buildOptions($option_array);
		$reply = $this->post($options);
		$reply = Variable::parseJson($reply);
		
		return $reply;
	}
	
	function todo_delete($todo_rid) {
		$this->authRequire();
		
		$this->createCmd("delete", "todo", "", "json");
		$pTag = new SimpleTag();
		$option_array = array("todo_rid"=>$todo_rid);
		$options = $this->buildOptions($option_array);
		$reply = $this->post($options);
		$reply = Variable::parseJson($reply);
		
		return $reply;
	}
	
	function footmark_recent($iscache=false) {
		$this->authRequire();
		
		$this->createCmd("recent", "footmark", "", "json");
		$pTag = new SimpleTag();
		$options = $this->buildOptions(array());
		$reply = $this->get($options, $iscache);
		$reply = Variable::parseJson($reply);
		
		return $reply;
	}
	
	function status_friends($iscache=false) {
		$this->authRequire();
		
		$this->createCmd("friends", "statuses", "", "json");
		$pTag = new SimpleTag();
		$options = $this->buildOptions(array());
		$reply = $this->get($options, $iscache);
		$reply = Variable::parseJson($reply);
		
		return $reply;
	}
	
	function status_followers($iscache=false) {
		$this->authRequire();
		
		$this->createCmd("followers", "statuses", "", "json");
		$pTag = new SimpleTag();
		$options = $this->buildOptions(array());
		$reply = $this->get($options, $iscache);
		$reply = Variable::parseJson($reply);
		
		return $reply;
	}
	
	function status_via($iscache=false) {
		$this->authRequire();
		
		$this->createCmd("via", "statuses", "", "json");
		$pTag = new SimpleTag();
		$options = $this->buildOptions(array());
		$reply = $this->get($options, $iscache);
		$reply = Variable::parseJson($reply);
		
		return $reply;
	}
	
	function pictogram_recent($iscache=false) {
		$this->authRequire();
		
		$this->createCmd("recent", "pictogram", "", "json");
		$pTag = new SimpleTag();
		$options = $this->buildOptions(array());
		$reply = $this->get($options, $iscache);
		$reply = Variable::parseJson($reply);
		
		return $reply;
	}
	
	function channel_message_list($name_en=null, $iscache=false) {
		$this->createCmd("list", "channel_message", "", "rss");
		$option_array = array();
		if ($name_en !== null) {
			$option_array['name_en'] = $name_en;
		}
		$options = $this->buildOptions($option_array);
		$pTag = new SimpleTag();
		$pTag->set($this->get($options,$iscache), "channel");
		
		$error = $this->getErrorMsg($pTag);
		if ($error) return $error;

		return $pTag->toHash();
	}
	
	function channel_message_update($body, $name_en) {
		$this->authRequire();
		
		$this->createCmd("update", "channel_message", "", "json");
		$pTag = new SimpleTag();
		$option_array = array("body"=>$body, "name_en"=>$name_en);
		$options = $this->buildOptions($option_array);
		$reply = $this->post($options);
		$reply = Variable::parseJson($reply);
		
		return $reply;
	}
	
	function friendships_create($login_id) {
		$this->authRequire();
		
		$this->createCmd($login_id, "friendships", "create", "json");
		$pTag = new SimpleTag();
		$options = $this->buildOptions(array());
		$reply = $this->post($options);
		$reply = Variable::parseJson($reply);
		
		return $reply;
	}
	
	function friendships_destroy($login_id) {
		$this->authRequire();
		
		$this->createCmd($login_id, "friendships", "destroy", "json");
		$pTag = new SimpleTag();
		$options = $this->buildOptions(array());
		$reply = $this->post($options);
		$reply = Variable::parseJson($reply);
		
		return $reply;
	}
	
	function favorites_create($status_rid) {
		$this->authRequire();
		
		$this->createCmd($status_rid, "favorites", "create", "json");
		$pTag = new SimpleTag();
		$options = $this->buildOptions(array());
		$reply = $this->post($options);
		$reply = Variable::parseJson($reply);
		
		return $reply;
	}
	
	function favorites_destroy($status_rid) {
		$this->authRequire();
		
		$this->createCmd($status_rid, "favorites", "destroy", "json");
		$pTag = new SimpleTag();
		$options = $this->buildOptions(array());
		$reply = $this->post($options);
		$reply = Variable::parseJson($reply);
		
		return $reply;
	}
	
	function channel_list($iscache=false) {
		$this->createCmd("list", "channel", "", "rss");
		$options = $this->buildOptions(array());
		$pTag = new SimpleTag();
		$pTag->set($this->get($options,$iscache), "channel");
		
		$error = $this->getErrorMsg($pTag);
		if ($error) return $error;

		return $pTag->toHash();
	}
	
	function channel_exists($name_en, $iscache=false) {
		$this->createCmd("exists", "channel", "", "json");
		$pTag = new SimpleTag();
		$option_array = array("name_en"=>$name_en);
		$options = $this->buildOptions($option_array);
		$reply = $this->get($options, $iscache);
		$reply = Variable::parseJson($reply);
		
		return $reply;
	}
	
	function channel_user_user_list($login_id, $iscache=false) {
		$this->createCmd("user_list", "channel_user", "", "json");
		$pTag = new SimpleTag();
		$option_array = array("login_id"=>$login_id);
		$options = $this->buildOptions($option_array);
		$reply = $this->get($options, $iscache);
		$reply = Variable::parseJson($reply);
		
		return $reply;
	}
	
	function channel_timeline($login_id, $iscache=false) {
		$this->createCmd("channel_timeline", "", "", "json");
		$pTag = new SimpleTag();
		$option_array = array("login_id"=>$login_id);
		$options = $this->buildOptions($option_array);
		$reply = $this->get($options, $iscache);
		$reply = Variable::parseJson($reply);
		
		return $reply;
	}
	
	function channel_favorite_toggle($channel_message_rid) {
		$this->createCmd("toggle", "channel_favorite", "", "");
		$pTag = new SimpleTag();
		$option_array = array("channel_message_rid"=>$channel_message_rid);
		$options = $this->buildOptions($option_array);
		$reply = $this->post($options);
		$reply = Variable::parseJson($reply);
		
		return $reply;
	}


	function authRequire() {
		$this->setBasicAuthorization($this->login, $this->password);
	}
	
	function parseUserResult(&$pTag) {
		$user = array("id"=>$pTag->getInValue("id"),
					"name"=>$pTag->getInValue("name"),
					"screen_name"=>$pTag->getInValue("screen_name"),
					"location"=>$pTag->getInValue("location"),
					"description"=>$pTag->getInValue("description"),
					"profile_image_url"=>$pTag->getInValue("profile_image_url"),
					"url"=>$pTag->getInValue("url"),
					"protected"=>$pTag->getInValue("protected"),
					);
		return $user;
	}
	
	function parseUserResults(&$pTag) {
		$list = array();
		foreach ($pTag->getIn("user") as $user) {
			$list[] = $this->parseUserResult($user);
		}
		return $list;
	}
	
	function parseStatusResults(&$pTag) {
		$ret = $pTag->toHash();
		if (isset($ret['status'])) {
			return $ret['status'];
		}

		return $ret;
		/*
		$list = array();
		foreach($pTag->getIn("status") as $status){
			$userTag = $status->getIn("user");
			$user = array();
			if (count($userTag) > 0) {
				$userTag = $userTag[0];
				$user = $this->parseUserResult($userTag);
			}
			$list[] = array("created_at"=>$status->getInValue("created_at"),
						"id"=>$status->getInValue("id"),
						"text"=>$status->getInValue("text"),
						"user"=>$user,
						);
		}
		return $list;
		 */
	}
	
	function parseRssResults(&$pTag) {
		$list = array();
		foreach($pTag->getIn("item") as $status){
			$userTag = $status->getIn("user");
			$user = array();
			if (count($userTag) > 0) {
				$userTag = $userTag[0];
				$user = $this->parseUserResult($userTag);
			}
			$list[] = array("created_at"=>$status->getInValue("created_at"),
						"id"=>$status->getInValue("id"),
						"text"=>$status->getInValue("text"),
						"user"=>$user,
						);
		}
		return $list;
	}
	
	function getErrorMsg(&$pTag) {
		if (empty($pTag->value)) {
			$plain = $pTag->plain;
			if (!empty($plain)) {
				return $plain;
			}
		}
		
		return false;
	}
	
	function createCmd($target, $cmd1 = "", $cmd2 = "", $format = null) {
		if ($format === null) {
			$format = $this->format;
		}
		$this->url = $this->base_url;
		$this->cmd1 = $cmd1;
		$this->cmd2 = $cmd2;
		if ($format === "") {
			$this->target = $target;
		} else {
			$this->target = $target . "." . $format;
		}
	}
	
	function buildUrl($hash = array()) {
		$cmd = "";
		if (!empty($this->cmd1)) {
			$cmd .= "/" . $this->cmd1;
		}
		if (!empty($this->cmd2)) {
			$cmd .= "/" . $this->cmd2;
		}
		if (!empty($this->target)) {
			$cmd .= "/" . $this->target;
		}
		return parent::buildUrl($hash,array(),$cmd);
	}
	
	function buildOptions($options = array()) {
		$new_options = array();
		foreach($options as $key=>$value) {
			if (!is_null($value) && !empty($value)) {
				$new_options[$key] = $value;
			}
		}
		return $new_options;
	}
}
?>
