<?php
Rhaco::import("tag.TemplateParser");
Rhaco::import("util.UnitTest");
Rhaco::import("io.FileUtil");

class TemplateParserTest extends UnitTest {
	function testComment(){
		$parser = new TemplateParser();
		$resulst = <<< __RESULT__
<html>
<body>
<form>

</form>
</body>
</html>
__RESULT__;
		$this->assertEquals($resulst,$parser->read("template/rt_comment.html"));
	}
	
	function testTemplate(){
		$parser = new TemplateParser();
		$resulst = <<< __RESULT__
everes

webooo

__RESULT__;
		$this->assertEquals($resulst,$parser->read("template/rt_template.html"));
	}
	
	function testReplace(){
		$parser = new TemplateParser();
		$parser->setReplace("everes","django");
		$resulst = <<< __RESULT__
<html>
<body>
<form>
django
</form>
</body>
</html>
__RESULT__;
		$this->assertEquals($resulst,$parser->read("template/rt_replace.html"));
	}
	function testReplaceInner(){
		$parser = new TemplateParser();
		$resulst = <<< __RESULT__
<html>
<body>
<form>
django
</form>
</body>
</html>
__RESULT__;
		$this->assertEquals($resulst,$parser->read("template/rt_replace_inner.html"));
	}

	function testInclude(){
		$parser = new TemplateParser();
		$resulst = <<< __RESULT__
<html>
<body>
<form>
everes
</form>
</body>
</html>
__RESULT__;
		$this->assertEquals($resulst,$parser->read("template/rt_include.html"));
	}
	function testExtends(){
		$parser = new TemplateParser();
		$resulst = <<< __RESULT__
<html>
<body>
<form>

everes

</form>
</body>
</html>
__RESULT__;
		$this->assertEquals($resulst,$parser->read("template/rt_extends.html"));
	}
	
	function testBlock(){
		$parser = new TemplateParser();
		$parser->setBlock("template/rt_block_everes.html");
		$resulst = <<< __RESULT__
<html>
<body>
<form>

everes

</form>
</body>
</html>
__RESULT__;
		$this->assertEquals($resulst,$parser->read("template/rt_block.html"));
	}
	function testBlockInner(){
		$parser = new TemplateParser();
		$resulst = <<< __RESULT__
<html>
<body>
<form>

everes

</form>
</body>
</html>
__RESULT__;
		$this->assertEquals($resulst,$parser->read("template/rt_block_inner.html"));
	}
	
	function testLoop(){
		$parser = new TemplateParser();
		$resulst = <<< __RESULT__
<html>
<body>
<form>
1:rhaco
2:django
3:rails
</form>
</body>
</html>
__RESULT__;
		$this->assertEquals($resulst,$parser->read("template/rt_loop.html",array("hogehoge"=>array("rhaco","django","rails"))));
	}
	function testFor(){
		$parser = new TemplateParser();
		$resulst = <<< __RESULT__
<html>
<body>
<form>
10:rhaco
12:rhaco
14:rhaco
16:rhaco
18:rhaco
20:rhaco
</form>
</body>
</html>
__RESULT__;
		$this->assertEquals($resulst,$parser->read("template/rt_for.html"));
	}
	function testIf(){
		$parser = new TemplateParser();
		$resulst = <<< __RESULT__
<html>
<body>
hoge

gohogoho
</body>
</html>
__RESULT__;
		$this->assertEquals($resulst,$parser->read("template/rt_if.html",array("hoge"=>10)));
	}
	function testNotIf(){
		$parser = new TemplateParser();
		$resulst = <<< __RESULT__
<html>
<body>
hogehoge

goho
</body>
</html>
__RESULT__;
		$this->assertEquals($resulst,$parser->read("template/rt_ifnot.html",array("hoge"=>10)));
	}
	function testContents(){
		$parser = new TemplateParser();
		$resulst = <<< __RESULT__
<html>
<body>
<span>10</span>
</body>
</html>
__RESULT__;
		$this->assertEquals($resulst,$parser->read("template/rt_contents.html",array("hoge"=>10)));		
	}
	
	function testReadF(){
		$parser = new TemplateParser();
		$resulst = <<< __RESULT__
OK

test

test2
a 
b 
c
__RESULT__;
		$this->assertEquals($resulst,$parser->read("template/plain.html",array("flag"=>"OK","test"=>"test","test2"=>"test2")));
	}

	function testReadPlain(){
		$parser = new TemplateParser();
		$resulst = <<< __RESULT__
OK

test

test2
a 
b 
c
__RESULT__;
		$this->assertEquals($resulst,$parser->read("template/f.html",array("flag"=>"OK","test"=>"test","test2"=>"test2")));
	}	
}
?>