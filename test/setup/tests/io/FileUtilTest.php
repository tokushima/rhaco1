<?php
Rhaco::import("util.UnitTest");
Rhaco::import("io.FileUtil");
Rhaco::import("lang.Variable");

class FileUtilTest extends UnitTest {
	var $io;
	
	function tearDown(){
		FileUtil::rm(Rhaco::resource("tests"));
	}
	function setUp(){
		FileUtil::rm(Rhaco::resource("tests"));
		FileUtil::write(Rhaco::resource("tests/hoge1"),"hoge1");
		FileUtil::write(Rhaco::resource("tests/hoge2"),"hoge2");
		FileUtil::write(Rhaco::resource("tests/hoge3"),"hoge3");
	}
	
	function testRead(){
		$this->assertEquals("hoge1",FileUtil::read(Rhaco::resource("tests/hoge1")));
	}
	function testWrite(){
		FileUtil::write(Rhaco::resource("tests/hoge4"),"hoge4");
		$this->assertEquals("hoge4",FileUtil::read(Rhaco::resource("tests/hoge4")));
	}
	function testAppend(){
		FileUtil::write(Rhaco::resource("tests/hoge5"),"hoge5");
		$this->assertEquals("hoge5",FileUtil::read(Rhaco::resource("tests/hoge5")));
		FileUtil::append(Rhaco::resource("tests/hoge5"),"mogo");
		$this->assertEquals("hoge5mogo",FileUtil::read(Rhaco::resource("tests/hoge5")));
	}
	function testExist(){
		$this->assertFalse(FileUtil::exist(Rhaco::resource("tests/hoge6")));
		FileUtil::write(Rhaco::resource("tests/hoge6"),"hoge6");
		$this->assertTrue(FileUtil::exist(Rhaco::resource("tests/hoge6")));		
	}
	function testLs(){
		$ls1 = FileUtil::ls(Rhaco::resource("tests"));
		foreach($ls1 as $file){
			$this->assertTrue(Variable::istype("file",$file));
		}
		FileUtil::write(Rhaco::resource("tests/mogo/mogo1"),"mogo1");
		$ls2 = FileUtil::ls(Rhaco::resource("tests"));		
		$this->assertEquals(sizeof($ls1),sizeof($ls2));

		$ls3 = FileUtil::ls(Rhaco::resource("tests"),true);
		$this->assertTrue(sizeof($ls1) < sizeof($ls3));
	}
	function testDirs(){
		$dir1 = FileUtil::dirs(Rhaco::resource("tests"));
		$this->assertEquals(0,sizeof($dir1));

		FileUtil::write(Rhaco::resource("tests/mogo/fugo/hugo1"),"fugo2");
		$dir2 = FileUtil::dirs(Rhaco::resource("tests"));
		$this->assertEquals(1,sizeof($dir2));

		$dir3 = FileUtil::dirs(Rhaco::resource("tests"),true);
		$this->assertEquals(2,sizeof($dir3));
	}
	function testTransaction(){
		$this->io = new FileUtil();
		
		FileUtil::write(Rhaco::resource("tests/hoge7"),"hoge7");

		$this->io->setTransaction(true);
		$this->assertEquals("hoge7",$this->io->fgets(Rhaco::resource("tests/hoge7")));
		
		$this->io->fputs(Rhaco::resource("tests/hoge7"),"hoge");
		$this->assertEquals("hoge7hoge",$this->io->fgets(Rhaco::resource("tests/hoge7")));
		$this->assertEquals("hoge7",FileUtil::read(Rhaco::resource("tests/hoge7")));

		$this->io->fwrite(Rhaco::resource("tests/hoge7"),"mogo");
		$this->assertEquals("mogo",$this->io->fgets(Rhaco::resource("tests/hoge7")));
		$this->assertEquals("hoge7",FileUtil::read(Rhaco::resource("tests/hoge7")));

		$this->io->rollback(Rhaco::resource("tests/hoge7"));
		$this->assertEquals("hoge7",$this->io->fgets(Rhaco::resource("tests/hoge7")));
		$this->assertEquals("hoge7",FileUtil::read(Rhaco::resource("tests/hoge7")));

		$this->io->fwrite(Rhaco::resource("tests/hoge7"),"mogo");
		$this->assertEquals("mogo",$this->io->fgets(Rhaco::resource("tests/hoge7")));
		$this->assertEquals("hoge7",FileUtil::read(Rhaco::resource("tests/hoge7")));		
		
		$this->io->commit(Rhaco::resource("tests/hoge7"));
		$this->assertEquals("mogo",$this->io->fgets(Rhaco::resource("tests/hoge7")));
		$this->assertEquals("mogo",FileUtil::read(Rhaco::resource("tests/hoge7")));

		$this->io->fputs(Rhaco::resource("tests/hoge7"),"hoge");
		$this->assertEquals("mogohoge",$this->io->fgets(Rhaco::resource("tests/hoge7")));
		$this->assertEquals("mogo",FileUtil::read(Rhaco::resource("tests/hoge7")));

		$this->io->close(Rhaco::resource("tests/hoge7"));
		$this->assertEquals("mogohoge",$this->io->fgets(Rhaco::resource("tests/hoge7")));
		$this->assertEquals("mogohoge",FileUtil::read(Rhaco::resource("tests/hoge7")));
		
		$this->io->close();
	}
	
	function testCp(){
		FileUtil::write(Rhaco::resource("tests/hoge8"),"hoge8");

		$this->assertFalse(FileUtil::exist(Rhaco::resource("tests/hoge9")));
		FileUtil::cp(Rhaco::resource("tests/hoge8"),Rhaco::resource("tests/hoge9"));
		$this->assertTrue(FileUtil::exist(Rhaco::resource("tests/hoge9")));
		$this->assertEquals("hoge8",FileUtil::read(Rhaco::resource("tests/hoge9")));	

		$this->assertFalse(FileUtil::exist(Rhaco::resource("tests/logo/logo1")));
		FileUtil::cp(Rhaco::resource("tests/hoge8"),Rhaco::resource("tests/logo/logo1"));		
		$this->assertTrue(FileUtil::exist(Rhaco::resource("tests/logo/logo1")));
		$this->assertEquals("hoge8",FileUtil::read(Rhaco::resource("tests/logo/logo1")));
	}
	function testRm(){
		$this->assertFalse(FileUtil::exist(Rhaco::resource("tests/hoge10")));
		FileUtil::write(Rhaco::resource("tests/hoge10"),"hoge10");
		$this->assertTrue(FileUtil::exist(Rhaco::resource("tests/hoge10")));
		FileUtil::rm(Rhaco::resource("tests/hoge10"),"hoge10");
		$this->assertFalse(FileUtil::exist(Rhaco::resource("tests/hoge10")));
	}
	function testMkdir(){
		$this->assertFalse(FileUtil::exist(Rhaco::resource("tests/mkdir")));
		FileUtil::mkdir(Rhaco::resource("tests/mkdir"));
		$this->assertTrue(FileUtil::exist(Rhaco::resource("tests/mkdir")));
	}
	function testMv(){
		FileUtil::write(Rhaco::resource("tests/hoge11"),"hoge11");

		$this->assertFalse(FileUtil::exist(Rhaco::resource("tests/hoge12")));
		FileUtil::mv(Rhaco::resource("tests/hoge11"),Rhaco::resource("tests/hoge12"));
		$this->assertTrue(FileUtil::exist(Rhaco::resource("tests/hoge12")));
		$this->assertEquals("hoge11",FileUtil::read(Rhaco::resource("tests/hoge12")));	
		$this->assertFalse(FileUtil::exist(Rhaco::resource("tests/hoge11")));
	}
	function testFind(){
		FileUtil::write(Rhaco::resource("tests/find/rhaco.dat"),"hoge");
		FileUtil::write(Rhaco::resource("tests/find/django.dat"),"hoge");
		FileUtil::write(Rhaco::resource("tests/find/rails.bak"),"hoge");
		
		$find = FileUtil::find("/\.dat$/",Rhaco::resource("tests/find"));
		$this->assertEquals(2,sizeof($find));
		foreach($find as $file){
			$this->assertTrue(Variable::istype("file",$file));
			$this->assertTrue(true == preg_match("/\.dat$/",$file->name));
		}
	}
}
?>