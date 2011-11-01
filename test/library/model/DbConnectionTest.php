<?php
Rhaco::import("database.model.DbConnection");

class DbConnectionTest extends DbConnection{
	function DbConnectionTest($new=true){
		$this->setHost(Rhaco::constant("DATABASE_test_HOST"));
		$this->setUser(Rhaco::constant("DATABASE_test_USER"));
		$this->setPassword(Rhaco::constant("DATABASE_test_PASSWORD"));
		$this->setName(Rhaco::constant("DATABASE_test_NAME"));
		$this->setPort(Rhaco::constant("DATABASE_test_PORT"));
		$this->setEncode(Rhaco::constant("DATABASE_test_ENCODE"));
		$this->setType(Rhaco::constant("DATABASE_test_TYPE"));		
		$this->setNew($new);
	}
}

?>