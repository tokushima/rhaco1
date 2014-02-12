<?php
Rhaco::import("lang.ArrayUtil");
Rhaco::import("lang.Variable");
/**
 * データインジェクション対策ユーティリティ
 *
 * @author Kentaro YABE
 * @license New BSD License
 * @copyright Copyright 2009- rhaco project. All rights reserved.
 */
class SecurityUtil{
	/**
	 * モデルで指定した列以外の情報をRequestから削除する
	 *
	 * @param Request $request
	 * @param TableObjectBase $tableObject
	 * @param string $confkey
	 * @param string $confmethod
	 */
	function allow(&$request,&$tableObject,$confkey="allow",$confmethod="security"){
		/***
			Rhaco::import("database.model.TableObjectBase");
			Rhaco::import("database.TableObjectUtil");
			Rhaco::import("database.model.Table");
			Rhaco::import("database.model.Column");
			eval('
				class SecurityUtilAllow extends TableObjectBase{
					func'.'tion table(){
						return new Table("secutiry",__CLASS__);
					}
					func'.'tion columnAaa(){
						return new Column("column=aaa,variable=aaa,",__CLASS__);
					}
					func'.'tion columnCccDdd(){
						return new Column("column=ccc_ddd,variable=cccDdd,",__CLASS__);
					}
					func'.'tion extraBbb(){
						return new Column("column=bbb,variable=bbb,",__CLASS__);
					}
					func'.'tion security(){
						return array("allow"=>"aaa","allow2"=>"bbb","allow3"=>"ccc_ddd");
					}
				}
			');
			$request = new Request();
			$table = new SecurityUtilAllow();

			$request->setVariable(array("aaa"=>"aaa","bbb"=>"bbb","ccc_ddd"=>"ccc_ddd","__aa__a_"=>"aaa","cccDdd"=>"ccc_ddd"));
			SecurityUtil::allow($request,$table);
			eq("aaa",$request->getVariable("aaa"));
			eq(null,$request->getVariable("ccc_ddd"));
			eq("aaa",$request->getVariable("__aa__a_"));
			eq(null,$request->getVariable("cccDdd"));
			eq(null,$request->getVariable("bbb"));

			$request->clearVariable();
			$request->setVariable(array("aaa"=>"aaa","bbb"=>"bbb","ccc_ddd"=>"ccc_ddd","__aa__a_"=>"aaa","cccDdd"=>"ccc_ddd"));
			SecurityUtil::allow($request,$table,"allow2");
			eq(null,$request->getVariable("aaa"));
			eq(null,$request->getVariable("ccc_ddd"));
			eq(null,$request->getVariable("__aa__a_"));
			eq(null,$request->getVariable("cccDdd"));
			eq("bbb",$request->getVariable("bbb"));

			$request->clearVariable();
			$request->setVariable(array("aaa"=>"aaa","bbb"=>"bbb","ccc_ddd"=>"ccc_ddd","__aa__a_"=>"aaa","cccDdd"=>"ccc_ddd"));
			SecurityUtil::allow($request,$table,"allow3");
			eq(null,$request->getVariable("aaa"));
			eq("ccc_ddd",$request->getVariable("ccc_ddd"));
			eq(null,$request->getVariable("__aa__a_"));
			eq("ccc_ddd",$request->getVariable("cccDdd"));
			eq(null,$request->getVariable("bbb"));
		 */
		if(!Variable::istype("Request",$request) || !Variable::istype("TableObjectBase",$tableObject)) return;
		if(!method_exists($tableObject,$confmethod)) return;
		$conf = ArrayUtil::arrays($tableObject->$confmethod());
		if(!isset($conf[$confkey])) return;
		$colVars = explode(",",strtolower(str_replace("_","",$conf[$confkey])));
		$columns = array();
		foreach($tableObject->columns() as $c){
			if(!in_array(strtolower(str_replace("_","",$c->variable())),$colVars)){
				$columns[] = $c;
			}
		}
		foreach($tableObject->extra() as $c){
			if(!in_array(strtolower(str_replace("_","",$c->variable())),$colVars)){
				$columns[] = $c;
			}
		}
		SecurityUtil::clearVariable($request,$columns);
	}

	/**
	 * モデルで指定した列をRequestから削除する
	 *
	 * @param Request $request
	 * @param TableObjectBase $tableObject
	 * @param string $confkey
	 * @param string $confmethod
	 */
	function deny(&$request,&$tableObject,$confkey="deny",$confmethod="security"){
		/***
			Rhaco::import("database.model.TableObjectBase");
			Rhaco::import("database.TableObjectUtil");
			Rhaco::import("database.model.Table");
			Rhaco::import("database.model.Column");
			eval('
				class SecurityUtilDeny extends TableObjectBase{
					func'.'tion table(){
						return new Table("secutiry",__CLASS__);
					}
					func'.'tion columnAaa(){
						return new Column("column=aaa,variable=aaa,",__CLASS__);
					}
					func'.'tion columnCccDdd(){
						return new Column("column=ccc_ddd,variable=cccDdd,",__CLASS__);
					}
					func'.'tion extraBbb(){
						return new Column("column=bbb,variable=bbb,",__CLASS__);
					}
					func'.'tion security(){
						return array("deny"=>"aaa","deny2"=>"bbb","deny3"=>"ccc_ddd");
					}
				}
			');
			$request = new Request();
			$table = new SecurityUtilDeny();

			$request->setVariable(array("aaa"=>"aaa","bbb"=>"bbb","ccc_ddd"=>"ccc_ddd","__aa__a_"=>"aaa","cccDdd"=>"ccc_ddd"));
			SecurityUtil::deny($request,$table);
			eq(null,$request->getVariable("aaa"));
			eq("ccc_ddd",$request->getVariable("ccc_ddd"));
			eq(null,$request->getVariable("__aa__a_"));
			eq("ccc_ddd",$request->getVariable("cccDdd"));
			eq("bbb",$request->getVariable("bbb"));

			$request->clearVariable();
			$request->setVariable(array("aaa"=>"aaa","bbb"=>"bbb","ccc_ddd"=>"ccc_ddd","__aa__a_"=>"aaa","cccDdd"=>"ccc_ddd"));
			SecurityUtil::deny($request,$table,"deny2");
			eq("aaa",$request->getVariable("aaa"));
			eq("ccc_ddd",$request->getVariable("ccc_ddd"));
			eq("aaa",$request->getVariable("__aa__a_"));
			eq("ccc_ddd",$request->getVariable("cccDdd"));
			eq(null,$request->getVariable("bbb"));

			$request->clearVariable();
			$request->setVariable(array("aaa"=>"aaa","bbb"=>"bbb","ccc_ddd"=>"ccc_ddd","__aa__a_"=>"aaa","cccDdd"=>"ccc_ddd"));
			SecurityUtil::deny($request,$table,"deny3");
			eq("aaa",$request->getVariable("aaa"));
			eq(null,$request->getVariable("ccc_ddd"));
			eq("aaa",$request->getVariable("__aa__a_"));
			eq(null,$request->getVariable("cccDdd"));
			eq("bbb",$request->getVariable("bbb"));
		 */
		if(!Variable::istype("Request",$request) || !Variable::istype("TableObjectBase",$tableObject)) return;
		if(!method_exists($tableObject,$confmethod)) return;
		$conf = ArrayUtil::arrays($tableObject->$confmethod());
		if(!isset($conf[$confkey])) return;
		$colVars = explode(",",strtolower(str_replace("_","",$conf[$confkey])));
		$columns = array();
		foreach($tableObject->columns() as $c){
			if(in_array(strtolower(str_replace("_","",$c->variable())),$colVars)){
				$columns[] = $c;
			}
		}
		foreach($tableObject->extra() as $c){
			if(in_array(strtolower(str_replace("_","",$c->variable())),$colVars)){
				$columns[] = $c;
			}
		}
		SecurityUtil::clearVariable($request,$columns);
	}
	
	/**
	 * Requestから指定した列情報を削除する
	 *
	 * @param Request $request
	 * @param array(Column) $columns
	 */
	function clearVariable(&$request,$columns=array()){
		/***
			Rhaco::import("database.model.TableObjectBase");
			Rhaco::import("database.TableObjectUtil");
			Rhaco::import("database.model.Table");
			Rhaco::import("database.model.Column");
			eval('
				class SecurityUtilClearVariable extends TableObjectBase{
					func'.'tion table(){
						return new Table("secutiry",__CLASS__);
					}
					func'.'tion columnAaa(){
						return new Column("column=aaa,variable=aaa,",__CLASS__);
					}
					func'.'tion columnCccDdd(){
						return new Column("column=ccc_ddd,variable=cccDdd,",__CLASS__);
					}
					func'.'tion extraBbb(){
						return new Column("column=bbb,variable=bbb,",__CLASS__);
					}
				}
			');
			$request = new Request();
			$table = new SecurityUtilClearVariable();

			$request->setVariable(array("aaa"=>"aaa","bbb"=>"bbb","ccc_ddd"=>"ccc_ddd","__aa__a_"=>"aaa","cccDdd"=>"ccc_ddd"));
			SecurityUtil::clearVariable($request,$table->columns());
			eq(null,$request->getVariable("aaa"));
			eq(null,$request->getVariable("ccc_ddd"));
			eq(null,$request->getVariable("__aa__a_"));
			eq(null,$request->getVariable("cccDdd"));
			eq("bbb",$request->getVariable("bbb"));

			$request->clearVariable();
			$request->setVariable(array("aaa"=>"aaa","bbb"=>"bbb","ccc_ddd"=>"ccc_ddd","__aa__a_"=>"aaa","cccDdd"=>"ccc_ddd"));
			SecurityUtil::clearVariable($request,$table->extra());
			eq("aaa",$request->getVariable("aaa"));
			eq("ccc_ddd",$request->getVariable("ccc_ddd"));
			eq("aaa",$request->getVariable("__aa__a_"));
			eq("ccc_ddd",$request->getVariable("cccDdd"));
			eq(null,$request->getVariable("bbb"));
		 */
		$colVars = array();
		foreach($columns as $col){
			$colVars[] = strtolower(str_replace("_","",$col->variable()));
		}
		if(empty($colVars)) return;
		foreach(array_keys($request->getVariable()) as $name){
			if(in_array(strtolower(str_replace("_","",$name)),$colVars)){
				$request->clearVariable($name);
			}
		}
	}
}
?>