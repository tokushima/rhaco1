	/**
<rt:if param="{$column.isDescription()}">
	 * {$column.description}
</rt:if>
<rt:if param="{$column.isChoices()}">
	 * Choices
<rt:loop param="{$column.getChoices()}" var="caption" key="value">
	 * 	{$value}: {$caption} 
</rt:loop>
</rt:if>
	 * 
	 * @return database.model.Column
	 */
	function {$functype}{$column.method}(){
		if(!Rhaco::isVariable("_R_D_C_","{$table.method}::{$column.method}")){
			$column = new Column("{$column.getConstractArg()}",__CLASS__);
			$column->label(Message::_("{$column.label}"));
<rt:if param="{$column.isChoices()}">
			$column->choices({$column.getChoicesString()});
</rt:if>
<rt:if param="{$column.isDepend()}">
			$column->depend(<rt:loop param="{$column.dependList}" var="depend"><rt:ifnot param="{$counter}" value="1">,</rt:ifnot>"{$depend[0].method}::{$depend[1].method}"</rt:loop>);
</rt:if>
			Rhaco::addVariable("_R_D_C_",$column,"{$table.method}::{$column.method}");
		}
		return Rhaco::getVariable("_R_D_C_",null,"{$table.method}::{$column.method}");
	}
	/**
<rt:if param="{$column.isDescription()}">
	 * {$column.description}
</rt:if>
<rt:if param="{$column.isChoices()}">
	 * Choices
<rt:loop param="{$column.getChoices()}" var="caption" key="value">
	 * 	{$value}: {$caption} 
</rt:loop>
</rt:if>
	 * 
	 * @return {$column.type}
	 */
	function set{$column.method}($value){
		$this->{$column.var} = TableObjectUtil::cast($value,"{$column.type}");
	}
	/**
<rt:if param="{$column.isDescription()}">
	 * {$column.description}
</rt:if>
<rt:if param="{$column.isChoices()}">
	 * Choices
<rt:loop param="{$column.getChoices()}" var="caption" key="value">
	 * 	{$value}: {$caption} 
</rt:loop>
</rt:if>
	 * 
	 */
	function get{$column.method}(){
		return $this->{$column.var};
	}
<rt:if param="{$column.isTypeTimestamp()}">
	/** {$column.description} */
	function format{$column.method}($format="Y/m/d H:i:s"){
		return DateUtil::format($this->{$column.var},$format);
	}
</rt:if>
<rt:if param="{$column.isTypeDate()}">
	/** {$column.description} */
	function format{$column.method}($format="Y/m/d"){
		return DateUtil::format($this->{$column.var},$format);
	}
</rt:if>
<rt:if param="{$column.isTypeTime()}">
	/** {$column.description} */
	function format{$column.method}(){
		return DateUtil::formatTime($this->{$column.var});
	}
</rt:if>
<rt:if param="{$column.isTypeBirthday()}">
	/** {$column.description} */
	function format{$column.method}(){
		return DateUtil::formatDate($this->{$column.var});
	}
</rt:if>
<rt:if param="{$column.isChoices()}">
	function caption{$column.method}(){
		return TableObjectUtil::caption($this,{$table.method}::{$functype}{$column.method}());
	}
</rt:if>
<rt:if param="{$column.isTypeBoolean()}">
	/** {$column.description} */
	function is{$column.method}(){
		return Variable::bool($this->{$column.var});
	}
</rt:if>