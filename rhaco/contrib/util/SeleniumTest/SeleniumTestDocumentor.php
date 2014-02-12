<?php
/**
 * Documentor of SeleniumTest
 * @author SHIGETA Takeshiro
 * @license New BSD License
 */
Rhaco::import("contrib.util.SeleniumTest.SeleniumTest");
Rhaco::import("tag.model.SimpleTag");
Rhaco::import("contrib.util.SeleniumTest.model.SeleniumTestResult");
Rhaco::import("io.FileUtil");
class SeleniumTestDocumentor extends SeleniumTest
{
	var $description = "description";
	var $screenshotNo = 1;
	var $screenshotDir = '';
	function __init__($args=null){
		$this->screenshotDir = Rhaco::setuppath("screenshots");
		parent::__init__($args);
	}
	function _command($verb, $args = array())
	{
		$depth = 2;
		$this->_setCalled($depth);
		while($this->method !="begin" && $this->method != "setUp" && $this->method != "tearDown" && $this->method != "finish" && preg_match("/^test/",$this->method)===0){
			$this->method = "";
			$depth++;
			$this->_setCalled($depth);
		}
		$result = new SeleniumTestResult($this->path,$this->line);
		$return = parent::_command($verb,$args);
		if(!in_array(strtolower($verb),array("geteval","runscript","getstore"))){
		$result->setCommand($this->class,$this->method,$verb,$args,$return);
		}
		$this->_clearCalled(true);
		return $return;
	}

	function setDescription($description=""){
		$this->description = $description;
	}
	/**
	 * Assertのオーバーライド
	 *
	 * @param unknown_type $arg1
	 * @param unknown_type $arg2
	 * @param unknown_type $comment
	 * @return unknown
	 */
	function assertEquals($arg1,$arg2,$comment=""){
		if(parent::assertEquals($arg1,$arg2,$comment)){
			$this->_assert("success",$arg1,$arg2,$comment);
			return $this->_clearCalled(true);
		}else{
			$this->_assert("fail",$arg1,$arg2,$comment);
			return $this->_clearCalled(true);
		}
	}
	/**
	 * Assertのオーバーライド
	 *
	 * @param unknown_type $arg1
	 * @param unknown_type $arg2
	 * @param unknown_type $comment
	 * @return unknown
	 */
	function assertNotEquals($arg1,$arg2,$comment=""){
		if(parent::assertNotEquals($arg1,$arg2,$comment)){
			$this->_assert("success",$arg1,$arg2,$comment);
			return $this->_clearCalled(true);
		}else{
			$this->_assert("fail",$arg1,$arg2,$comment);
			return $this->_clearCalled(true);

		}
	}
	/**
	 * Assertのオーバーライド
	 *
	 * @param unknown_type $arg1
	 * @param unknown_type $comment
	 * @return unknown
	 */
	function assertTrue($arg1,$comment=""){
		if(parent::assertTrue($arg1,$comment)){
			$this->_assert("success",true,$arg1,$comment);
			return $this->_clearCalled(true);
		}else{
			$this->_assert("fail",true,$arg1,$comment);
			return $this->_clearCalled(true);
		}
	}
	/**
	 * Assertのオーバーライド
	 *
	 * @param unknown_type $arg1
	 * @param unknown_type $comment
	 * @return unknown
	 */
	function assertFalse($arg1,$comment=""){
		if(parent::assertFalse($arg1,$comment)){
			$this->_assert("success",false,$arg1,$comment);
			return $this->_clearCalled(true);
		}else{
			$this->_assert("fail",false,$arg1,$comment);
			return $this->_clearCalled(true);
		}
	}
	function _assert($type,$arg1,$arg2,$comment=""){//"expectation [%s] : Result [%s]"
		$this->_setCalled(2);
		$obj = new SeleniumTestResult($type,$this->path,$this->line);
		$obj->setAssert($this->class,$this->method,$comment,var_export($arg1,true),var_export($arg2,true));
		return;
	}
	/**
	 * XML出力
	 *
	 * @param unknown_type $path
	 */
	function toXml($path=""){
		$results = SeleniumTestResult::results();
	//	if(SeleniumTestResult::count() > 0){
			$projectModel = new ProjectModel();
			$projectModel->start(Rhaco::setuppath("project.xml"));
			$classes = array();
			$tag = new SimpleTag("seleniumtest",null,array("name"=>$projectModel->name));
			$tag->setValue(new SimpleTag("description",$this->description));
			foreach($results as $result){
				if($result->getCommand()==="assert"){
					$innerTags = array(new SimpleTag("expected",$result->getExpectation()),
					new SimpleTag("result",$result->getResult()),
					new SimpleTag("comment",$result->getComment()));
					$commandTag = new SimpleTag("assert",$innerTags, array("type"=>$result->getType()));
				}else{
					$paramsTag = new SimpleTag("params");
					foreach($result->getParams() as $param){
						$paramsTag->addValue(new SimpleTag("param",$param));
					}
					$commandTag = new SimpleTag("command",array($paramsTag,new SimpleTag("response",$result->getResponse()),new SimpleTag("comment",$result->getComment())), array("name"=>$result->getCommand()));
				}

				if(!in_array($result->getClass(),array_keys($classes))){
					$classes[$result->getClass()] = array();
				}
				if(!in_array($result->getMethod(),array_keys($classes[$result->getClass()]))){
					$testTag = new SimpleTag("test", $commandTag, array("name"=>$result->getMethod()));
					$classes[$result->getClass()][$result->getMethod()] = $testTag;
				}else{
					$classes[$result->getClass()][$result->getMethod()]->addValue($commandTag);
				}
//				var_dump($result->getClass(),$result->getMethod(), $classes);
			}
			foreach($classes as $classname=>$methods){
				$tag->addValue(new SimpleTag("tests",$methods,array("name"=>$classname)));
			}
			if(empty($path)) $path = Rhaco::setuppath("tests.xml");
			FileUtil::write($path,str_replace($this->screenshotDir,"./screenshots",$tag->get(true)));
	//	}
	}
	/**
	 * UnitTestのオーバーライド
	 *
	 */
	function begin(){
		$this->setBrowserName("*firefox");//ieなら*iexplore,operaなら*opera,
		$this->setTargetUrl("http://localhost");
		$this->start();
	}
	/**
	 * UnitTestのオーバーライド
	 *
	 */
	function finish(){
		$this->stop();
		$this->toXml();$this->captureFullScreenshot();
	}
	/**
	 * スクリーンショットした時に保存するファイルを自動的に指定する
	 *
	 * @param string $filename
	 * @return unknown
	 */
	function captureFullScreenshot($filename=""){
		if(empty($filename)){
			$filename = FileUtil::path($this->screenshotDir,$this->screenshotNo++.".jpg");
		}
		return parent::captureFullScreenshot($filename);
	}
	/**
	 * SeleniumResultにコメントを登録する
	 *
	 * @param string $comment
	 */
	function comment($comment=""){
		SeleniumTestResult::setComment($comment);
	}
	function captureEntirePageScreenshot($filename=""){
$script = <<<_EOL_
		win = this.page().getCurrentWindow();
		doc = this.page().getDocument();
		_browserHeight = function(){
        	if ( win.innerHeight ) {
               return win.innerHeight;
        	}
        	else if ( doc.documentElement &&
doc.documentElement.clientHeight != 0 ) {
                return doc.documentElement.clientHeight;
        	}
       		 else if ( doc.body ) {
               return doc.body.clientHeight;
        	}else{
        		return 0;
        	}
        }
		_pageHeight = function(){
		  if (win.innerHeight && win.scrollMaxY) {
			    return win.innerHeight + win.scrollMaxY;
		  } else if (doc.body.scrollHeight > doc.body.offsetHeight){
		    // all but Explorer Mac
		    return doc.body.scrollHeight;
		  } else {
		    // Explorer Mac...would also work in Explorer 6 Strict,
		    // Mozilla and Safari
		    return doc.body.offsetHeight;
		  }
		}
_EOL_;

//_pageHeightはlightboxのパク(ry
		$this->captureFullScreenshot($filename);
		$this->getEval($script);
		$pageHeight = $this->getEval("_result = _pageHeight();");
		$windowHeight = $this->getEval("_result = _browserHeight();");
		$x = 0;
		$pages = intval(ceil($pageHeight/$windowHeight))-1;
		for($i=0;$i<$pages;$i++){
			$x += $windowHeight;
			$this->getEval("window.scroll(0,$x);");
			if(!empty($filename)){
				$loopFilename = $filename.$i;
				$this->captureFullScreenshot($loopFilename);
			}else{
				$this->captureFullScreenshot();
			}
		}
	}

}
?>