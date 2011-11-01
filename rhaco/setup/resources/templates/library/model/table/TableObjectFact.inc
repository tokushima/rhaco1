<rt:loop param="{$table.columnList}" var="column">
<rt:if param="{$column.isReference()}">
	function getFact{$column.method}(){
		return $this->fact{$column.method};
	}
	function setFact{$column.method}($obj){
		$this->fact{$column.method} = $obj;
	}
</rt:if>
</rt:loop>