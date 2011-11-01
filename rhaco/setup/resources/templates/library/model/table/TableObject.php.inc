Rhaco::import("resources.Message");
Rhaco::import("database.model.TableObjectBase");
Rhaco::import("database.model.DbConnection");
Rhaco::import("database.TableObjectUtil");
Rhaco::import("database.model.Table");
Rhaco::import("database.model.Column");
/**
 * #ignore
 * {$table.description}
 */
class {$table.method}Table extends TableObjectBase{
<rt:loop param="{$table.columnList}" var="column">
	/** {$column.description} */
	var ${$column.var};
</rt:loop>
<rt:include href="TableObjectVariableDef.inc" />

	function {$table.method}Table(<rt:loop param="{$table.primaryList()}" var="column"><rt:ifnot param="{$counter}" value="1">,</rt:ifnot>${$column.var}=null</rt:loop>){
		$this->__init__(<rt:loop param="{$table.primaryList()}" var="column"><rt:ifnot param="{$counter}" value="1">,</rt:ifnot>${$column.var}</rt:loop>);
	}
	function __init__(<rt:loop param="{$table.primaryList()}" var="column"><rt:ifnot param="{$counter}" value="1">,</rt:ifnot>${$column.var}=null</rt:loop>){
<rt:loop param="{$table.columnList}" var="column">
		$this->{$column.var} = {$column.default};
</rt:loop>
<rt:loop param="{$table.extraList}" var="column">
		$this->{$column.var} = {$column.default};
</rt:loop>
<rt:loop param="{$table.primaryList()}" var="column">
		$this->set{$column.method}(${$column.var});
</rt:loop>
	}
	function connection(){
		if(!Rhaco::isVariable("_R_D_CON_","{$database.class}")){
			Rhaco::addVariable("_R_D_CON_",new DbConnection("{$database.class}"),"{$database.class}");
		}
		return Rhaco::getVariable("_R_D_CON_",null,"{$database.class}");
	}
	function table(){
		if(!Rhaco::isVariable("_R_D_T_","{$table.method}")){
			Rhaco::addVariable("_R_D_T_",new Table(Rhaco::constant("DATABASE_{$database.class}_PREFIX")."{$table.name}",__CLASS__),"{$table.method}");
		}
		return Rhaco::getVariable("_R_D_T_",null,"{$table.method}");
	}


<rt:loop param="{$table.getColumnExtra()}" key="functype" var="columnList">
<rt:loop param="columnList" var="column">
<rt:include href="TableObjectColumn.inc" />
</rt:loop>
</rt:loop>


<rt:include href="TableObjectFact.inc" />
<rt:include href="TableObjectReference.inc" />
}