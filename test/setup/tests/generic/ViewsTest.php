<?php
Rhaco::import("abbr.Q");
Rhaco::import("abbr.V");
Rhaco::import("abbr.L");
Rhaco::import("util.UnitTest");
Rhaco::import("model.GenericTest");
Rhaco::import("model.GenericTestType");

class ViewsTest extends UnitTest {
	
	function testDetail(){
		$db = new DbUtil(GenericTest::connection());
		$obj = $db->get(new GenericTest(2));
		$this->assertTrue(Variable::istype("GenericTest",$obj));
		
		$obj = $db->get(new GenericTest(),new Criteria(Q::eq(GenericTest::columnId(),2)));
		$this->assertTrue(Variable::istype("GenericTest",$obj));
		
		$obj = $db->get(new GenericTest(),new Criteria(Q::eq(GenericTest::columnId(),2),Q::fact()));
		$this->assertTrue(Variable::istype("GenericTest",$obj));		

		$db->close();

		$views = new Views(GenericTest::connection());

		$parser = $views->detail(new GenericTest(2));
		if($this->assertTrue(isset($parser->variables) && isset($parser->variables['object']))){
			$this->assertEquals(2,$parser->variables['object']->id);
		}
		
		$parser = $views->detail(new GenericTest(),new C(Q::eq(GenericTest::columnId(),2)));
		if($this->assertTrue(isset($parser->variables) && isset($parser->variables['object']))){
			$this->assertEquals(2,$parser->variables['object']->id);
		}

		$parser = $views->detail(new GenericTest(),new C(Q::eq(GenericTest::columnId(),2),Q::fact()));
		if($this->assertTrue(isset($parser->variables) && isset($parser->variables['object']))){
			$this->assertEquals(2,$parser->variables['object']->id);

			if($this->assertTrue(Variable::istype("GenericTestType",$parser->variables['object']->factGenericTestType))){
				$this->assertEquals(3,$parser->variables['object']->factGenericTestType->id);
			}
		}

		$parser = $views->detail(new GenericTest(2),new C(Q::fact()));
		if($this->assertTrue(isset($parser->variables) && isset($parser->variables['object']))){
			$this->assertEquals(2,$parser->variables['object']->id);

			if($this->assertTrue(Variable::istype("GenericTestType",$parser->variables['object']->factGenericTestType))){
				$this->assertEquals(3,$parser->variables['object']->factGenericTestType->id);
			}
		}
	}
 }
