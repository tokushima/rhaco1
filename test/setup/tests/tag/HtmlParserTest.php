<?php
Rhaco::import("tag.HtmlParser");
Rhaco::import("util.UnitTest");

class HtmlParserTest extends UnitTest {
	function testInput(){
		$parser = new HtmlParser();
		$parser->setVariable(array("typetext"=>"hogetext",
									"typehidden"=>"hidden",
									"typecheckbox"=>array("hoge1","hoge2"),
									"typeradio"=>"gako",
									"val1"=>"hoge2",
									"val2"=>"gako",
							));
		$this->assertEquals(
<<< __RESULT__
<html>
<body>
<form>
<input type="text" name="typetext" value="hogetext" />
<input type="hidden" name="typehidden" value="hidden" />
<input type="checkbox" name="typecheckbox[]" value="hoge1" checked />
<input type="checkbox" name="typecheckbox[]" value="hoge2" checked />
<input type="checkbox" name="typecheckbox[]" value="hoge3" />
<input type="radio" name="typeradio" value="gako" checked />
<input type="radio" name="typeradio" value="hoge" />

<input type="checkbox" name="abc[]" value="hoge5" checked />
<input type="checkbox" name="abc[]" value="hoge6" />

<input type="radio" name="typeradio" value="gako" checked />
<input type="radio" name="typeradio" value="hoge" />

<input type="checkbox" name="typecheckbox[]" value="hoge2" checked />
<input type="radio" name="typeradio" value="gako" checked />
</form>
</body>
</html>
__RESULT__
		,$parser->read("html/form_input.html"));
	}
	function testInputVariable(){
		$parser = new HtmlParser();
		$parser->setVariable(array("typetext"=>"hogetext",
									"typehidden"=>"hidden",
									"typecheckbox"=>"hoge",
									"typeradio"=>"gako",
									"var1"=>"typetext",
									"var2"=>"typehidden",
									"var3"=>"typecheckbox",
									"var4"=>"typeradio",
									"abc"=>true,
									"def"=>false,
									"ghi"=>array(123=>123,456=>456,789=>789),
									"jkl"=>"456"
							));
		$this->assertEquals(
<<< __RESULT__
<html>
<body>
<form>
<input type="text" name="typetext" value="hogetext" />
<input type="hidden" name="typehidden" value="hidden" />
<input type="checkbox" name="typecheckbox[]" value="hoge" checked />
<input type="radio" name="typeradio" value="gako" checked />
<input type="radio" name="abc" value="true" checked />
<input type="radio" name="def" value="false" checked />
<select name="jkl"><option value="123">123</option>
<option value="456" selected>456</option>
<option value="789">789</option>
</select>
<input type="checkbox" name="jkl[]" value="123" />
<input type="checkbox" name="jkl[]" value="456" checked />
<input type="checkbox" name="jkl[]" value="789" />
</form>
</body>
</html>
__RESULT__
		,$parser->read("html/form_input_variable.html"));
	}

	function testTextarea(){
		$parser = new HtmlParser();
		$parser->setVariable(array("typetextarea"=>"<hogetext>"));
		$this->assertEquals(
<<< __RESULT__
<html>
<body>
<form>
<textarea name="typetextarea">&lt;hogetext&gt;</textarea>
</form>
</body>
</html>
__RESULT__
		,$parser->read("html/form_textarea.html"));
	}
	function testTextareaVariable(){
		$parser = new HtmlParser();
		$parser->setVariable(array("typetextarea"=>"<hogetext>","var1"=>"typetextarea"));
		$this->assertEquals(
<<< __RESULT__
<html>
<body>
<form>
<textarea name="typetextarea">&lt;hogetext&gt;</textarea>
</form>
</body>
</html>
__RESULT__
		,$parser->read("html/form_textarea_variable.html"));
	}

	function testSelect(){
		$parser = new HtmlParser();
		$parser->setVariable(array("typeselect"=>"moge"));
		$this->assertEquals(
<<< __RESULT__
<html>
<body>
<form>
<select name="typeselect">
<option value="hoge">1</option>
<option value="moge" selected>2</option>
<option value="doge">3</option>
</select>

<select name="abc">
<option value="hoge">1</option>
<option value="moge" selected>2</option>
<option value="doge" selected>3</option>
</select>
</form>
</body>
</html>
__RESULT__
		,$parser->read("html/form_select.html"));
	}
	function testSelectVarialbe(){
		$parser = new HtmlParser();
		$parser->setVariable(array("typeselect"=>"moge","var1"=>"typeselect"));
		$parser->setVariable("varse",2);
		$parser->setVariable("truese",true);
		$this->assertEquals(
<<< __RESULT__
<html>
<body>
<form>
<select name="typeselect">
<option value="hoge">1</option>
<option value="moge" selected>2</option>
<option value="doge">3</option>
</select>

<select name="abc">
<option value="hoge">1</option>
<option value="moge">2</option>
<option value="doge" selected>3</option>
</select>

<select name="def">
<option value="hoge">1</option>
<option value="moge">2</option>
<option value="doge" selected>3</option>
</select>
</form>
</body>
</html>
__RESULT__
		,$parser->read("html/form_select_variable.html"));
	}
	
	function testSelectMulti(){
		$parser = new HtmlParser();
		$parser->setVariable(array("typeselect"=>array("moge","hoge")));
		$this->assertEquals(
<<< __RESULT__
<html>
<body>

<form>
<select name="typeselect[]" multiple="true">
<option value="hoge" selected>1</option>
<option value="moge" selected>2</option>
<option value="doge">3</option>
</select>
</form>

<form>
<select name="typeselect[]" multiple>
<option value="hoge" selected>1</option>
<option value="moge" selected>2</option>
<option value="doge">3</option>
</select>
</form>

<form>
<select name="typeselect[]" multiple="true">
<option value="hoge" selected>1</option>
<option value="moge" selected>2</option>
<option value="doge">3</option>
</select>
</form>

</body>
</html>
__RESULT__
		,$parser->read("html/form_select_multi.html"));
	}
	
	function testFile(){
		$parser = new HtmlParser();
		$this->assertEquals(
<<< __RESULT__
<html>
<body>
<form enctype="multipart/form-data" method="post">
<input type="file" name="typefile" />
</form>

<form method="post" enctype="multipart/form-data">
<input type="file" name="typefile" />
</form>

<form method="post" enctype="multipart/form-data">
<input type="file" name="typefile" />
</form>
</body>
</html>
__RESULT__
		,$parser->read("html/form_file.html"));
	}

	function testIframe(){
		$parser = new HtmlParser();
		$this->assertEquals(
<<< __RESULT__
<html>
<body>
sub content

</body>
</html>
__RESULT__
		,$parser->read("html/i_frame.html"));
	}
	function testMeta(){
		$parser = new HtmlParser();
		$this->assertEquals(
<<< __RESULT__
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Insert title here</title>
</head>
<body>

</body>
</html>
__RESULT__
		,$parser->read("html/meta.html"));
	}

	
	function testUl(){
		$parser = new HtmlParser();
		$list = array("hoge"=>array("abc"=>"123","def"=>"456","ghq"=>"789"),"hoge2"=>array());
		$this->assertEquals(
<<< __RESULT__
<html>
<body>
<ul>
	123
	456
	789
</ul>

</body>
</html>
__RESULT__
		,$parser->read("html/ul.html",$list));
	}
	function testTable(){
		$parser = new HtmlParser();
		$list = array("hoge"=>array("abc"=>"123","def"=>"456","ghq"=>"789"),"hoge2"=>array());
		$this->assertEquals(
<<< __RESULT__
<html>
<body>
<table>
	<tr><td>123</td></tr>
	<tr><td>456</td></tr>
	<tr><td>789</td></tr>
</table>

</body>
</html>
__RESULT__
		,$parser->read("html/table.html",$list));
	}

	function testSelectOptions(){
		$parser = new HtmlParser();
		$list = array("hoge"=>array("abc"=>"123","def"=>"456","ghq"=>"789"));
		$this->assertEquals(
<<< __RESULT__
<html>
<body>
<form>
<select name="zzz"><option value="abc">123</option>
<option value="def">456</option>
<option value="ghq">789</option>
</select>
<select name="zzz"><option value="">none</option><option value="abc">123</option>
<option value="def">456</option>
<option value="ghq">789</option>
</select>
</form>
</body>
</html>
__RESULT__
		,$parser->read("html/form_select_options.html",$list));
	}
	
	
	function testReadPlain(){
		$parser = new HtmlParser();
		$list = array("flag"=>"OK","test"=>"test","test2"=>"test2");
		$this->assertEquals(
<<< __RESULT__
OK

test

test2
a 
b 
c
__RESULT__
		,$parser->read("template/f.html",$list));
	}
}
?>