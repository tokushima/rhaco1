<?php
Rhaco::import("util.Automator");
Rhaco::import("util.UnitTest");

class AutomatorTest extends UnitTest {
	function testExecute(){
		$a = new Automator();
		$a->add("Exe1::get");
		$a->add("Exe1::neko");
		$a->add("Exe1::tora");
		
		$this->assertEquals("hogehogenekotora",$a->execute());
		
		$a = new Automator();
		$a->add("Exe1::get");
		$a->add("Exe2::add");
		$a->add("Exe1::tora");
		
		$this->assertEquals("kokehogehogetora",$a->execute());
	}
}
?>