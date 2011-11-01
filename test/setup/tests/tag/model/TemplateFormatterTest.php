<?php
Rhaco::import("tag.model.TemplateFormatter");
Rhaco::import("util.UnitTest");

class TemplateFormatterTest extends UnitTest {
	function testHttpBuildQuery(){
		Rhaco::import("Dummy4");
		$list = array("A"=>"aaaa",
						"B"=>array("Ba"=>1,
								"Bb"=>array("Bb1"=>"cccc",
											"Bb2"=>array(0=>"dddd")
								),
								"Bc"=>new Dummy4(),
								"Bd"=>true,
								"Be"=>null,
							),
					);
		$this->assertEquals("A=aaaa&B[Ba]=1&B[Bb][Bb1]=cccc&B[Bb][Bb2][0]=dddd&B[Bc]=&B[Bd]=true",TemplateFormatter::httpBuildQuery($list));
	}
	
}
?>