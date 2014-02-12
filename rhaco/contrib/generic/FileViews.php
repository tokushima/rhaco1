<?php
Rhaco::import("generic.Views");
/**
 * PHPを通したファイルのダウンロード用のViews
 *
 * @author Kentaro YABE
 * @license New BSD License
 * @copyright Copyright 2009- rhaco project. All rights reserved.
 */
class FileViews extends Views{
	/**
	 * CSV出力
	 *
	 * @param TableObjectBase $tableObject
	 * @param Criteria $criteria
	 * @param string $filename
	 * @param boolean $formatter
	 * @param string $confmethod
	 * @param string $confkey
	 * @return unknown
	 */
	function csv($tableObject,$criteria=null,$filename=null,$formatter=true,$confkey="csv",$confmethod="views"){
		/*** #viewing */
		if($this->_connection($tableObject)){
			Logger::disableDisplay();
			if(!Variable::istype("Criteria",$criteria)) $criteria = new Criteria();
			$this->_readCriteria($tableObject,$criteria,$confmethod);
			$criteria->q(Criterion::fact());
			$columns = $tableObject->models($confmethod,$confkey);
			if(!$columns) $columns = $tableObject->columns();
			$result = $line = array();
			foreach($columns as $column){
				$line[] = $column->label();
			}
			$result[] = $line;
			$rows = $this->dbUtil->select($tableObject,$criteria);
			foreach($rows as $row){
				$line = array();
				foreach($columns as $column){
					$line[] = $row->value($column,$formatter);
				}
				$result[] = $line;
			}
			if(!$filename) $filename = get_class($tableObject).".csv";
			$this->header();
			Header::attach(StringUtil::encode(ArrayUtil::toCsv($result),"SJIS"),$filename);
			Rhaco::end();
		}else{
			$this->_notFound();
		}
		return $this->parser();
	}
	
	/**
	 * IEのバグに対応
	 *
	 */
	function header($header=array()){
		Header::write(array_merge(array("Pragma"=>"private","Cache-Control"=>"private"),$header));
	}
	
	/**
	 * セッションを利用可能にする
	 *
	 * @static
	 * @param string $id
	 */
	function usesession($id=""){
		if(session_id() == "" && isset($_SERVER["HTTP_USER_AGENT"])){
			//IEのバグに対応
			session_cache_limiter("public");
			session_cache_expire(Rhaco::constant("SESSION_EXPIRE_TIME",2592000));
			if(!empty($id))	session_id($id);
			session_start();
		}
	}
}
?>