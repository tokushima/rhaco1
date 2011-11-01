<?php
Rhaco::import("util.UnitTest");
Rhaco::import("database.model.TableObjectBase");
Rhaco::import("model.ArticleTag");
Rhaco::import("model.ExtExtraTestTable");

class TableObjectBaseTest extends UnitTest {
	function testConnection(){
		$obj = new ArticleTag();
		$this->assertTrue(Variable::istype("DbConnection",$obj->connection()));	
	}
	function testPrimaryKey(){
		$obj = new ArticleTag();
		$columns = $obj->primaryKey();
		
		$this->assertEquals(1,sizeof($columns));
		$this->assertTrue(Variable::istype("Column",$columns[0]));	
	}
	function testPrimaryKeyValue(){
		$obj = new ArticleTag(10);
		$values = $obj->primaryKeyValue();
		
		$this->assertEquals(1,sizeof($values));
		$this->assertEquals(10,$values["id"]);	
	}
	function testColumns(){
		$obj = new ArticleTag();
		$columns = $obj->columns();
		
		$this->assertEquals(3,sizeof($columns));
		foreach ($columns as $column){
			$this->assertTrue(Variable::istype("Column",$column));
			$this->assertTrue(
					(
						strtolower("ArticleTagTable.id") == strtolower($column->getColumnFullname())
						|| strtolower("ArticleTagTable.article_id") == strtolower($column->getColumnFullname())
						|| strtolower("ArticleTagTable.tag_id") == strtolower($column->getColumnFullname())
					),$column->getColumnFullname()
				);
		}
	}
	function testExtra(){
		$obj = new ArticleTag();
		$columns = $obj->extra();
		
		$this->assertEquals(1,sizeof($columns));
		foreach ($columns as $column){
			$this->assertTrue(Variable::istype("Column",$column));
			$this->assertEquals(strtolower("ArticleTagTable.category"),strtolower($column->getColumnFullname()));
		}
	}
	function testTable(){
		$obj = new ArticleTag();
		$table = $obj->table();

		$this->assertTrue(Variable::istype("Table",$table));
	}
	function testToStrin(){
		$obj = new ArticleTag();
		$name = $obj->toString();
		$this->assertEquals(get_class($obj),$name);
	}
	function testIsSerial(){
		$obj = new ArticleTag();
		$this->assertTrue($obj->isSerial());
	}
	function testValues(){
		$obj = new ArticleTag(10);
		$this->assertEquals(10,$obj->value(ArticleTag::columnId()));
	}
	function testLabel(){
		$obj = new ArticleTag(10);
		$this->assertEquals("id",$obj->label(ArticleTag::columnId()));
	}
	function testValidName(){
		$obj = new ArticleTag(10);		
		$this->assertEquals(strtolower(get_class($obj)."_id"),$obj->validName(ArticleTag::columnId()));
	}
	
	function testModels(){
		$obj = new ArticleTag(10);
		$columns = $obj->models("viewsModelTestMethod","hoge");
		$this->assertEquals(2,sizeof($columns));
		
		$columns = $obj->models("viewsModelTestMethod","kaeru");
		$this->assertEquals(2,sizeof($columns));
		
		$columns = $obj->models("viewsModelTestMethod","kaeru",true);
		$this->assertEquals(1,sizeof($columns));
	}
	function testExtExtra(){
		$obj = new ExtExtraTestTable();
		$this->assertEquals("extra1",$obj->getExtra1());
		$this->assertEquals("extra_value",$obj->getExtraValue());
	}
	function testExtLabel(){
		$obj = new ExtExtraTestTable();
		$this->assertEquals("ばりゅー",$obj->label(ExtExtraTestTable::columnValue1()));
		$this->assertEquals("エキストラ１",$obj->label(ExtExtraTestTable::extraExtra1()));
		$this->assertEquals("えきすとら",$obj->label(ExtExtraTestTable::extraExtraValue()));
	}
	function testGetColumn(){
		$obj = new ExtExtraTestTable();
		$this->assertTrue(Variable::istype("Column",$obj->getColumn("value1")));
		$this->assertTrue(Variable::istype("Column",$obj->getColumn("extra_value")));
		$this->assertTrue(Variable::istype("Column",$obj->getColumn("extraValue")));
		$this->assertTrue(Variable::istype("Column",$obj->getColumn("extra1")));
	}
}
?>