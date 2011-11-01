<?php
Rhaco::import("lang.Variable");
Rhaco::import("lang.ObjectUtil");
Rhaco::import("lang.ArrayUtil");
/**
 * lang.Variableのエイリアス
 * 
 * @author Kazutaka Tokushima
 * @license New BSD License
 * @copyright Copyright 2007- rhaco project. All rights reserved.
 */
class V extends Variable{
	/**
	 * Variable::copyPropertiesのエイリアス
	 * プロパティーをコピーする
	 * fromobjectがtag.model.SimpleTagの場合はXMLからコピーする
	 * propertyOnly=falseの場合はsetter,getterでコピーする
	 * ObjectUtil::copyPropertiesとは違い、元の変数に変化はない
	 * @param object $fromobject
	 * @param object $toobject
	 * @param boolean $propertyOnly
	 * @param boolean $excludeProperty
	 * @return object
	 */
	function cp($fromobject,$toobject,$propertyOnly=false,$excludeProperty=array()){
		/*** unit("lang.VariableTest"); */
		return Variable::copyProperties($fromobject,$toobject,$propertyOnly,$excludeProperty);
	}
	/**
	 * ObjectUtil::hashConvObjectのエイリアス
	 * ハッシュからオブジェクトにコピー
	 * @param array $hash
	 * @param object $object
	 * @param boolean $isMethod true setter利用/false プロパティ直
	 * @return object
	 */
	function ho($hash,&$object,$isMethod=true){
		/*** unit("lang.VariableTest"); */
		return ObjectUtil::hashConvObject($hash,$object,$isMethod);
	}
	/**
	 * ObjectUtil::objectConvHashのエイリアス
	 * オブジェクトからハッシュにコピー
	 * オブジェクトのプロパティーをハッシュに変換する
	 * $isMethodの場合はメソッドもハッシュに変換する
	 * @param object $object
	 * @param array $hash
	 * @param boolean $isMethod
	 * @return hash
	 */
	function oh($object,$hash=array(),$isMethod=false){
		/*** unit("lang.VariableTest"); */
		return ObjectUtil::objectConvHash($object,$hash,$isMethod);
	}
	/**
	 * ArrayUtil::dictのエイリアス
	 * 辞書をハッシュに変換
	 * @param string $dict
	 * @param string[] $keys
	 * @param boolean $fill
	 * @return hash
	 */
	function dict($dict,$keys,$fill=true){
		/***
		 * $dict = "name=hogehoge,title='rhaco',arg=get\,tha";
		 * $keys = array("name","arg","description","title");
		 * $result = V::dict($dict,$keys);
		 * eq(4,sizeof($result));
		 * foreach($result as $key => $value) $$key = $value;
		 * eq("hogehoge",$name);
		 * eq("rhaco",$title);
		 * eq(null,$description);
		 * eq("get,tha",$arg);
		 * 
		 */
		return ArrayUtil::dict($dict,$keys,$fill);
	}
	/**
	 * ArrayUtil::ishashのエイリアス
	 * ハッシュか
	 * @param mixed $var
	 * @return boolean
	 */
	function ishash($var){
		/***
		 * assert(!V::ishash(array("A","B","C")));
		 * assert(!V::ishash(array(0=>"A",1=>"B",2=>"C")));
		 * assert(V::ishash(array(1=>"A",2=>"B",3=>"C")));
		 * assert(V::ishash(array("a"=>"A","b"=>"B","c"=>"C")));
		 * assert(!V::ishash(array("0"=>"A","1"=>"B","2"=>"C")));
		 * assert(!V::ishash(array(0=>"A",1=>"B","2"=>"C")));
		 */
		return ArrayUtil::ishash($var);		
	}
	/**
	 * ArrayUtil::implodeのエイリアス
	 * 配列要素を文字列により連結する
	 * @param mixed[] $array
	 * @param string $glue
	 * @param integer $offset
	 * @param integer $length
	 * @param boolean $fill
	 * @return string
	 */
	function implode($array,$glue="",$offset=0,$length=0,$fill=false){
		/***
		 * eq("hogekokepopo",V::implode(array("hoge","koke","popo")));
		 * eq("koke:popo",V::implode(array("hoge","koke","popo"),":",1));
		 * eq("koke",V::implode(array("hoge","koke","popo"),":",1,1));
		 * eq("hoge:koke:popo::",V::implode(array("hoge","koke","popo"),":",0,5,true));
		 * 
		 */
		return ArrayUtil::implode($array,$glue,$offset,$length,$fill);
	}
	/**
	 * ArrayUtil::hgetのエイリアス
	 * ハッシュからキーをcase insensitiveで値を取得する
	 * @param string $name
	 * @param array $array
	 * @return mixed
	 */
	function hget($name,$array){
		/***
		 * $list = array("ABC"=>"AA","deF"=>"BB","gHi"=>"CC");
		 * 
		 * eq("AA",V::hget("abc",$list));
		 * eq("BB",V::hget("def",$list));
		 * eq("CC",V::hget("ghi",$list));
		 * 
		 * eq(null,V::hget("jkl",$list));
		 * eq(null,V::hget("jkl","ABCD"));
		 */		
		return ArrayUtil::hget($name,$array);
	}
	/**
	 * ArrayUtil::arraysのエイリアス
	 * 配列として取得
	 * @param mixed[] $array
	 * @param int $low
	 * @param int $high
	 * @return mixed[]
	 */
	function arrays($array,$offset=0,$length=0,$fill=false){
		/***
		 * eq(1,sizeof(ArrayUtil::arrays(array(0,1),1,1)));
		 * eq(2,sizeof(ArrayUtil::arrays(array(0,1,2),0,2)));
		 * eq(3,sizeof(ArrayUtil::arrays(array(0,1),0,3,true)));
		 * eq(2,sizeof(ArrayUtil::arrays(array(0,1,2,3,4),3,6)));
		 */
		return ArrayUtil::arrays($array,$offset,$length,$fill);
	}
	/**
	 * ObjectUtil::mixinのエイリアス
	 * 先頭のオブジェクトに２番からのオブジェクトのメソッドを追加した新しいクラスのオブジェクトを取得
	 * 先頭のオブジェクトをextendsしたクラスとなる
	 * @param object $obj1
	 * @param object $obj2
	 * @return object
	 */
	function mixin(){
		/*** unit("lang.VariableTest"); */
		$args = func_get_args();
		return call_user_func_array(array("ObjectUtil","mixin"),$args);
	}
}
?>