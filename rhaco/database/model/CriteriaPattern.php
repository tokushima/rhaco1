<?php
Rhaco::import("lang.Variable");
/**
 * Criterionが利用する条件式格納用クラス
 * 
 * @author Kazutaka Tokushima
 * @license New BSD License
 * @copyright Copyright 2005- rhaco project. All rights reserved.
 */
class CriteriaPattern{
	var $argA;
	var $argB;
	var $pattern;
	/**
	 * コンストラクタ
	 * @param mixed $argA
	 * @param mixed $argB
	 * @param integer $pattern
	 */
	function CriteriaPattern($argA,$argB,$pattern=1){
		$this->argA	= $argA;
		$this->argB	= $argB;
		$this->pattern = $pattern;
	}
	/**
	 * 2つめの比較対象がColumnか
	 * @return boolean
	 */
	function isColumn(){
		return Variable::istype("Column",$this->argB);
	}
}
?>