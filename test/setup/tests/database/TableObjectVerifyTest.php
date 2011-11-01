<?php
Rhaco::import("abbr.Q");
Rhaco::import("abbr.V");
Rhaco::import("abbr.L");
Rhaco::import("util.UnitTest");
Rhaco::import("model.RequireTest");

class TableObjectVerifyTest extends UnitTest {
	var $db = null;

	function begin() {
		$this->db = new DbUtil(UniqueTest::connection());
	}
	function finish() {
		$this->db->close();
	}
	function testRquireWith(){
		// すべて削除
		$this->db->alldelete(new RequireTest());

		// 必須チェック違反
		$data = new RequireTest();
		$this->assertFalse($this->db->insert($data));
		$this->assertEquals(0,$this->db->sizeof(new RequireTest()));
		
		// 正常
		$data = new RequireTest();
		$data->setName("hoge");
		$this->assertTrue(Variable::istype("RequireTest",$this->db->insert($data)));
		$this->assertEquals(1,$this->db->sizeof(new RequireTest()));
		
		// require with 違反
		$data = new RequireTest();
		$data->setName("hoge");
		$data->setZip(1234567);
		$this->assertFalse($this->db->insert($data));
		$this->assertEquals(1,$this->db->sizeof(new RequireTest()));
		
		// require with 正常
		$data = new RequireTest();
		$data->setName("hoge");
		$data->setZip(1234567);
		$data->setAddress("japan");
		$this->assertTrue(Variable::istype("RequireTest",$this->db->insert($data)));
		$this->assertEquals(2,$this->db->sizeof(new RequireTest()));
		
		// require with 正常 (zipなしでもOK)
		$data = new RequireTest();
		$data->setName("hoge");
		$data->setAddress("japan");
		$this->assertTrue(Variable::istype("RequireTest",$this->db->insert($data)));
		$this->assertEquals(3,$this->db->sizeof(new RequireTest()));
		
		// すべて削除
		$this->db->alldelete(new RequireTest());
	}
	
	function testMinMax(){
		$this->db->alldelete(new MinmaxTest());		
		
		$data = new MinmaxTest();
		$val1column = $data->columnVal1();
		$this->assertEquals(0,$val1column->min());
		$this->assertEquals(10,$val1column->max());

		$val2column = $data->columnVal2();
		$this->assertEquals(-10,$val2column->min());
		$this->assertEquals(0,$val2column->max());

		$data = new MinmaxTest();
		$data->setVal1(-1);
		$this->assertFalse($this->db->insert($data));
		
		$data = new MinmaxTest();
		$data->setVal1(11);
		$this->assertFalse($this->db->insert($data));
		
		$data = new MinmaxTest();
		$data->setVal2(-11);
		$this->assertFalse($this->db->insert($data));
		
		$data = new MinmaxTest();
		$data->setVal2(1);
		$this->assertFalse($this->db->insert($data));		

		$data = new MinmaxTest();		
		$val3column = $data->columnVal3();
		$this->assertEquals(0,$val3column->min());
		$this->assertEquals(10,$val3column->max());

		$data = new MinmaxTest();
		$data->setVal3(null);
		$this->assertTrue(Variable::istype("MinmaxTest",$this->db->insert($data)));

		$data = new MinmaxTest();
		$data->setVal3("12345678901");
		$this->assertFalse($this->db->insert($data));		
		 	
		$this->db->alldelete(new MinmaxTest());
	}
}
?>