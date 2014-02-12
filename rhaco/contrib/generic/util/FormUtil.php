<?php
Rhaco::import("tag.model.TemplateFormatter");
Rhaco::import("resources.Message");
/**
 * テーブルモデル、列モデルからHTMLフォームの文字列表現を取得する
 * 
 * ViewsUtilの拡張版というか部分集合みたいなの
 * toStringXX() やらで明示的に指定したい際に使う
 * referenceなど、Table参照がらみは自分で実装してね
 * @author Kentaro YABE
 * @license New BSD License
 * @copyright Copyright 2008- rhaco project. All rights reserved.
 */
class FormUtil{
	/**
	 * SELECTのHTML表現を取得
	 * 対象は，choices， boolean
	 * @param TableObjectBase $table
	 * @param Column $column
	 * @param strint $null
	 * @return string
	 * @static 
	 */
	function select(&$table,$column,$null=null){
		/***
			Rhaco::import("database.model.TableObjectBase");
			Rhaco::import("database.TableObjectUtil");
			Rhaco::import("database.model.Table");
			Rhaco::import("database.model.Column");
			eval('
				class FormUtilSelectTest extends TableObjectBase {
					func'.'tion table(){
						return new Table("formutil",__CLASS__);
					}
					var $aaa;
					func'.'tion columnAaa(){
						return new Column("column=aaa,variable=aaa,type=boolean");
					}
					var $bbb;
					func'.'tion columnBbb(){
						$column = new Column("column=bbb,variable=bbb,type=string");
						$column->choices(array("1"=>"value1","0"=>"value0",""=>"null"));
						return $column;
					}
				}
			');
			$obj = new FormUtilSelectTest();

			$obj->aaa = true;
			$expect = '<select class="boolean" name="aaa">';
			$expect.= '<option value="false">false</option>';
			$expect.= '<option value="true" selected="selected">true</option>';
			$expect.= '</select>';
			eq($expect,FormUtil::select($obj,$obj->columnAaa()));

			$obj->aaa = false;
			$expect = '<select class="boolean" name="aaa">';
			$expect.= '<option value="false" selected="selected">false</option>';
			$expect.= '<option value="true">true</option>';
			$expect.= '</select>';
			eq($expect,FormUtil::select($obj,$obj->columnAaa()));
			
			$obj->bbb = null;
			$expect = '<select class="string" name="bbb">';
			$expect.= '<option value="1">value1</option>';
			$expect.= '<option value="0">value0</option>';
			$expect.= '<option value="" selected="selected">null</option>';
			$expect.= '</select>';
			eq($expect,FormUtil::select($obj,$obj->columnBbb()));

			$obj->bbb = 0;
			$expect = '<select class="string" name="bbb">';
			$expect.= '<option value="1">value1</option>';
			$expect.= '<option value="0" selected="selected">value0</option>';
			$expect.= '<option value="">null</option>';
			$expect.= '</select>';
			eq($expect,FormUtil::select($obj,$obj->columnBbb()));

			$obj->bbb = 1;
			$expect = '<select class="string" name="bbb">';
			$expect.= '<option value="1" selected="selected">value1</option>';
			$expect.= '<option value="0">value0</option>';
			$expect.= '<option value="">null</option>';
			$expect.= '</select>';
			eq($expect,FormUtil::select($obj,$obj->columnBbb()));
		 */
		if(is_string($column) && strpos()) $column = $table->getColumn($column);
		if(sizeof($column->choices()) > 0){
			$value = $table->value($column);
			$result = sprintf('<select class="%s" name="%s">',$column->type(),$column->variable());
			if(!is_null($null)) $result .= sprintf('<option value="">%s</option>',TemplateFormatter::htmlencode($null));
			foreach($column->choices() as $columnvalue => $caption){
				$selected  = ($value === null || $value === "") && $columnvalue === "";
				$selected |= ($value !== null && $value !== "" && $columnvalue !== ""  && $value == $columnvalue);
				$result .= sprintf('<option value="%s"%s>%s</option>',
								$columnvalue,
								$selected ? ' selected="selected"' : "",
								$caption);
			}
			$result .= "</select>";
			return $result;
		}
		if($column->type() == "boolean"){
			$result = sprintf('<select class="boolean" name="%s">',$column->variable());
			if(!is_null($null)) $result .= sprintf('<option value="">%s</option>',TemplateFormatter::htmlencode($null));
			$value = $table->value($column);
			$result .= sprintf('<option value="false"%s>%s</option>',$value ? "" : ' selected="selected"',Message::_("false"));
			$result .= sprintf('<option value="true"%s>%s</option>',$value ? ' selected="selected"' : "",Message::_("true"));
			$result .= '</select>';
			return $result;
		}
	}
	/**
	 * radio属性INPUTのHTML表現を取得
	 * 対象は，choices，boolean
	 * @param TableObjectBase $table
	 * @param Column $column
	 * @param boolean $break
	 * @return string
	 * @static 
	 */
	function radio(&$table,$column,$break=false){
		/***
			Rhaco::import("database.model.TableObjectBase");
			Rhaco::import("database.TableObjectUtil");
			Rhaco::import("database.model.Table");
			Rhaco::import("database.model.Column");
			eval('
				class FormUtilRadioTest extends TableObjectBase {
					func'.'tion table(){
						return new Table("formutil",__CLASS__);
					}
					var $aaa;
					func'.'tion columnAaa(){
						return new Column("column=aaa,variable=aaa,type=boolean");
					}
					var $bbb;
					func'.'tion columnBbb(){
						$column = new Column("column=bbb,variable=bbb,type=string");
						$column->choices(array("1"=>"value1","0"=>"value0",""=>"null"));
						return $column;
					}
				}
			');
			$obj = new FormUtilRadioTest();
			
			$obj->aaa = true;
			$expect = '<label><input type="radio" class="boolean" name="aaa" value="true" checked="checked" />true</label>';
			$expect.= '<label><input type="radio" class="boolean" name="aaa" value="false" />false</label>';
			eq($expect,FormUtil::radio($obj,$obj->columnAaa()));

			$obj->aaa = false;
			$expect = '<label><input type="radio" class="boolean" name="aaa" value="true" />true</label>';
			$expect.= '<label><input type="radio" class="boolean" name="aaa" value="false" checked="checked" />false</label>';
			eq($expect,FormUtil::radio($obj,$obj->columnAaa()));

			$obj->bbb = null;
			$expect = '<label><input type="radio" class="string" name="bbb" value="1" />value1</label>';
			$expect.= '<label><input type="radio" class="string" name="bbb" value="0" />value0</label>';
			$expect.= '<label><input type="radio" class="string" name="bbb" value="" checked="checked" />null</label>';
			eq($expect,FormUtil::radio($obj,$obj->columnBbb()));

			$obj->bbb = 0;
			$expect = '<label><input type="radio" class="string" name="bbb" value="1" />value1</label>';
			$expect.= '<label><input type="radio" class="string" name="bbb" value="0" checked="checked" />value0</label>';
			$expect.= '<label><input type="radio" class="string" name="bbb" value="" />null</label>';
			eq($expect,FormUtil::radio($obj,$obj->columnBbb()));

			$obj->bbb = 1;
			$expect = '<label><input type="radio" class="string" name="bbb" value="1" checked="checked" />value1</label>';
			$expect.= '<label><input type="radio" class="string" name="bbb" value="0" />value0</label>';
			$expect.= '<label><input type="radio" class="string" name="bbb" value="" />null</label>';
			eq($expect,FormUtil::radio($obj,$obj->columnBbb()));
		 */
		if(is_string($column)) $column = $table->getColumn($column);
		$pattern = '<label><input type="radio" class="%s" name="%s" value="%s"%s />%s</label>';
		$value = $table->value($column);
		$result = array();
		$choices = $column->choices();
		if(sizeof($choices) > 0){
			foreach($choices as $columnvalue => $caption){
				$checked  = ($value === null || $value === "") && $columnvalue === "";
				$checked |= ($value !== null && $value !== "" && $columnvalue !== ""  && $value == $columnvalue);
				$label = $caption ? $caption : $columnvalue;
				$result[] = sprintf($pattern,
								$column->type(),
								$column->variable(),
								$columnvalue,
								$checked ? ' checked="checked"' : "",
								$label);
			}
			return implode($break ? "<br/>" : "",$result);
		}
		if($column->type() == "boolean"){
			$result[] = sprintf($pattern,$column->type(),$column->variable(),"true",($value ? ' checked="checked"' : ""),Message::_("true"));
			$result[] = sprintf($pattern,$column->type(),$column->variable(),"false",(!$value ? ' checked="checked"' : ""),Message::_("false"));
			return implode($break ? "<br/>" : "",$result);
		}
	}
	/**
	 * checkbox属性INPUTのHTML表現を取得
	 * 対象は，boolean
	 * @param TableObjectBase $table
	 * @param Column $column
	 * @param mixed $compare
	 * @param boolean $array
	 * @return string
	 * @static 
	 */
	function checkbox(&$table,$column,$compare=null,$array=false){
		/***
			Rhaco::import("database.model.TableObjectBase");
			Rhaco::import("database.TableObjectUtil");
			Rhaco::import("database.model.Table");
			Rhaco::import("database.model.Column");
			eval('
				class FormUtilCheckBoxTest extends TableObjectBase{
					func'.'tion table(){
						return new Table("formutil",__CLASS__);
					}
					var $aaa = true;
					func'.'tion columnAaa(){
						return new Column("column=aaa,variable=aaa,type=boolean,label=aaa");
					}
					var $bbb = false;
					func'.'tion columnBbb(){
						return new Column("column=bbb,variable=bbb,type=boolean,label=bbb");
					}
				}
			');
			$obj = new FormUtilCheckBoxTest();
			
			$expect = '<label><input type="checkbox" class="boolean" name="aaa" value="true" checked="checked" />aaa</label>';
			eq($expect,FormUtil::checkbox($obj,$obj->columnAaa()));

			$expect = '<label><input type="checkbox" class="boolean" name="bbb" value="true" />bbb</label>';
			eq($expect,FormUtil::checkbox($obj,$obj->columnBbb()));
			//TODO
		 */
		if(is_string($column)) $column = $table->getColumn($column);
		if($column->type() == "boolean"){
			return sprintf('<label><input type="checkbox" class="boolean" name="%s" value="true"%s />%s</label>',
						$column->variable(),
						$table->value($column) ? " checked=\"checked\"" : "",
						TemplateFormatter::htmlencode($column->label()));
		}
		return sprintf('<label><input type="checkbox" class="%s" name="%s%s" value="%s"%s />%s</label>',
					$column->type(),
					$column->variable(),
					$array ? "[]" : "",
					$table->value($column),
					($compare === null ? $table->value($column) : $table->value($column) == $compare) ? " checked=\"checked\"" : "",
					TemplateFormatter::htmlencode($column->label()));
	}
	/**
	 * hidden属性INPUTのHTML表現を取得
	 * @param TableObjectBase $table
	 * @param Column $column
	 * @param boolean $formatter 
	 * @return string
	 * @static 
	 */
	function hidden(&$table,$column,$formatter=false){
		/***
			Rhaco::import("database.model.TableObjectBase");
			Rhaco::import("database.TableObjectUtil");
			Rhaco::import("database.model.Table");
			Rhaco::import("database.model.Column");
			eval('
				class FormUtilHiddenTest extends TableObjectBase {
					func'.'tion table(){
						return new Table("formutil",__CLASS__);
					}
					var $aaa = true;
					func'.'tion columnAaa(){
						$column = new Column("column=aaa,variable=aaa,type=boolean");
						$column->label("aaa");
						return $column;
					}
					var $bbb;
					func'.'tion columnBbb(){
						return new Column("column=bbb,variable=bbb,type=string");
					}
					func'.'tion formatBbb(){
						return "null";
					}
				}
			');
			$obj = new FormUtilHiddenTest();

			$expect = '<input type="hidden" class="boolean" name="aaa" value="1" />';
			eq($expect,FormUtil::hidden($obj,$obj->columnAaa()));

			$expect = '<input type="hidden" class="string" name="bbb" value="null" />';
			eq($expect,FormUtil::hidden($obj,$obj->columnBbb(),true));
		 */
		if(is_string($column)) $column = $table->getColumn($column);
		$pattern = '<input type="hidden" class="%s" name="%s" value="%s" />';
		return sprintf($pattern,
					$column->type(),
					$column->variable(),
					TemplateFormatter::htmlencode($table->value($column,$formatter)));
	}
	/**
	 * text属性INPUTのHTML表現を取得
	 * @param TableObjectBase $table
	 * @param Column $column
	 * @param boolean $formatter
	 * @param integer $size
	 * @return string
	 * @static 
	 */
	function text(&$table,$column,$formatter=false,$size=null){
		/***
			Rhaco::import("database.model.TableObjectBase");
			Rhaco::import("database.TableObjectUtil");
			Rhaco::import("database.model.Table");
			Rhaco::import("database.model.Column");
			eval('
				class FormUtilTextTest extends TableObjectBase {
					var $aaa = 123;
					func'.'tion columnAaa(){
						return new Column("column=aaa,variable=aaa,type=string");
					}
					var $bbb;
					func'.'tion columnBbb(){
						return new Column("column=bbb,variable=bbb,type=boolean");
					}
				}
			');
			$obj = new FormUtilTextTest();
			
			$expect = '<input type="text" class="string" name="aaa" value="123" />';
			eq($expect,FormUtil::text($obj,$obj->columnAaa()));

			$expect = '<input type="text" class="boolean" name="bbb" value="0" size="12" />';
			eq($expect,FormUtil::text($obj,$obj->columnBbb(),true,12));
		 */
		if(is_string($column)) $column = $table->getColumn($column);
		$pattern = '<input type="text" class="%s" name="%s" value="%s"%s />';
		$attr = is_numeric($size) ? sprintf(' size="%d"',$size) : "";
		return sprintf($pattern,
					$column->type(),
					$column->variable(),
					TemplateFormatter::htmlencode($table->value($column,$formatter)),
					$attr);
	}
	/**
	 * password属性INPUTのHTML表現を取得
	 * @param TableObjectBase $table
	 * @param Column $column
	 * @param integer $size
	 * @return string
	 * @static 
	 */
	function password(&$table,$column,$size=null){
		/***
			Rhaco::import("database.model.TableObjectBase");
			Rhaco::import("database.TableObjectUtil");
			Rhaco::import("database.model.Table");
			Rhaco::import("database.model.Column");
			eval('
				class FormUtilPasswordTest extends TableObjectBase {
					var $aaa = "passwd";
					func'.'tion columnAaa(){
						return new Column("column=aaa,variable=aaa,type=string");
					}
				}
			');
			$obj = new FormUtilPasswordTest();

			$expect = '<input type="password" class="string" name="aaa" value="passwd" size="12" />';
			eq($expect,FormUtil::password($obj,$obj->columnAaa(),12));
		 */
		if(is_string($column)) $column = $table->getColumn($column);
		$pattern = '<input type="password" class="%s" name="%s" value="%s"%s />';
		$attr = is_numeric($size) ? sprintf(' size="%d"',$size) : "";
		return sprintf($pattern,
					$column->type(),
					$column->variable(),
					TemplateFormatter::htmlencode($table->value($column)),
					$attr);
	}
	/**
	 * TEXTAREAのHTML表現を取得
	 * @param TableObjectBase $table
	 * @param Column $column
	 * @param integer $rows
	 * @param integer $cols
	 * @return string
	 * @static 
	 */
	function textarea(&$table,$column,$rows=null,$cols=null){
		/**
			Rhaco::import("database.model.TableObjectBase");
			Rhaco::import("database.TableObjectUtil");
			Rhaco::import("database.model.Table");
			Rhaco::import("database.model.Column");
			eval('
				class FormUtilTextareaTest extends TableObjectBase {
					var $aaa = "hoge\nhoge";
					func'.'tion columnAaa(){
						return new Column("column=aaa,variable=aaa,type=text");
					}
				}
			');
			$obj = new FormUtilTextareaTest();
			
			$expect = sprintf('<textarea class="text" name="aaa" rows="12" cols="10">%s</textarea>',"hoge\nhoge");
			eq($expect,FormUtil::textarea($obj,$obj->columnAaa(),12,10));
		 */
		if(is_string($column)) $column = $table->getColumn($column);
		$pattern = '<textarea class="%s" name="%s"%s>%s</textarea>';
		$attrs = "";
		$attrs .= is_numeric($rows) ? sprintf(' rows="%d"',$rows) : "";
		$attrs .= is_numeric($cols) ? sprintf(' cols="%d"',$cols) : "";
		return sprintf($pattern,
					$column->type(),
					$column->variable(),
					$attrs,
					TemplateFormatter::htmlencode($table->value($column)));
	}
	/**
	 * file属性のINPUTのHTML表現を取得
	 * @param TableObjectBase $table
	 * @param Column $column
	 * @return string
	 * @static 
	 */
	function file(&$table,$column){
		/***
			Rhaco::import("database.model.TableObjectBase");
			Rhaco::import("database.TableObjectUtil");
			Rhaco::import("database.model.Table");
			Rhaco::import("database.model.Column");
			eval('
				class FormUtilFileTest extends TableObjectBase {
					var $aaa;
					func'.'tion columnAaa(){
						return new Column("column=aaa,variable=aaa,type=string");
					}
				}
			');
			$obj = new FormUtilFileTest();
			
			$expect = '<input type="file" class="file" name="aaa" />';
			eq($expect,FormUtil::file($obj,$obj->columnAaa()));
		 */
		if(is_string($column)) $column = $table->getColumn($column);
		$pattern = '<input type="file" class="file" name="%s" />';
		return sprintf($pattern,$column->variable());
	}
	/**
	 * button属性のINPUTのHTML表現を取得
	 * @param TableObjectBase $table
	 * @param Column $column
	 * @return string
	 * @static 
	 */
	function button(&$table,$column){
		/***
			Rhaco::import("database.model.TableObjectBase");
			Rhaco::import("database.TableObjectUtil");
			Rhaco::import("database.model.Table");
			Rhaco::import("database.model.Column");
			eval('
				class FormUtilButtonTest extends TableObjectBase {
					var $aaa;
					func'.'tion columnAaa(){
						return new Column("column=aaa,variable=aaa,type=string,label=aaa");
					}
				}
			');
			$obj = new FormUtilButtonTest();
			
			$expect = '<input type="button" class="button" name="aaa" value="aaa" />';
			eq($expect,FormUtil::button($obj,$obj->columnAaa()));
		 */
		if(is_string($column)) $column = $table->getColumn($column);
		$pattern = '<input type="button" class="button" name="%s" value="%s" />';
		return sprintf($pattern,$column->variable(),$column->label());
	}
	/**
	 * submit属性のINPUTのHTML表現を取得
	 * @param TableObjectBase $table
	 * @param Column $column
	 * @param string $url
	 * @return string
	 * @static 
	 */
	function submit(&$table,$column,$url=null){
		/**
		 * #pass あとでやる
		 */
		if(is_string($column)) $column = $table->getColumn($column);
		$pattern = '<input type="%s" class="submit" name="%s" value="%s"%s />';
		return sprintf($pattern,
						is_null($url) ? "submit" : "button",
						$column->variable(),
						TemplateFormatter::htmlencode($table->label($column)),
						is_null($url) ? "" : " onclick=\"var el=this.parentNode;while(el.tagName.toLowerCase()!='form'){el=el.parentNode;}el.setAttribute('action','{$url}');el.submit();\""
						);
	}
	
	/**
	 * 画面を遷移するボタン
	 * @param TableObjectBase $table
	 * @param Column $column
	 * @param string $url
	 * @return string
	 * @static 
	 */
	function location(&$table,$column,$url){
		/**
		 * #pass あとでやる
		 */
		if(is_string($column)) $column = $table->getColumn($column);
		$pattern = '<input type="button" class="button" name="%s" value="%s"%s />';
		return sprintf($pattern,
						$column->variable(),
						TemplateFormatter::htmlencode($table->label($column)),
						" onclick=\"location.href='{$url}'\""
						);
	}
	/**
	 * image属性のINPUTのHTML表現を取得
	 * @param TableObjectBase $table
	 * @param Column $column
	 * @param string $src
	 * @return string
	 * @static 
	 */
	function image(&$table,$column,$src){
		/***
			Rhaco::import("database.model.TableObjectBase");
			Rhaco::import("database.TableObjectUtil");
			Rhaco::import("database.model.Table");
			Rhaco::import("database.model.Column");
			eval('
				class FormUtilImageTest extends TableObjectBase {
					var $aaa;
					func'.'tion columnAaa(){
						return new Column("column=aaa,variable=aaa,type=string,label=label");
					}
				}
			');
			$obj = new FormUtilImageTest();
			$expect = '<input type="image" class="image" name="aaa" src="test.png" alt="label" />';
			eq($expect,FormUtil::image($obj,$obj->columnAaa(),"test.png"));
		 */
		if(is_string($column)) $column = $table->getColumn($column);
		$pattern = '<input type="image" class="image" name="%s" src="%s" alt="%s" />';
		$label = $column->label();
		return sprintf($pattern,$column->variable(),$src,$label?$label:$column->variable());
	}
	/**
	 * フォーム要素に属性を設定する
	 * @param string $html HTML表現
	 * @param string/array $attr 属性
	 * @param string $value 属性値
	 * @static
	 */
	function attr($html,$attr=null,$value=null){
		/***
		 * $expect = '<input type="input" name="hoge" value="" maxlength="12" />';
		 * $result = FormUtil::attr('<input type="input" name="hoge" value=""/>',"maxlength",12);
		 * eq($expect,$result);
		 * 
		 * $expect = '<select name="hoge" class="fuga"><option value="1"></option></select>';
		 * $result = FormUtil::attr('<select name="hoge" class="hoge"><option value="1"></option></select>',array("class"=>"fuga"));
		 * eq($expect,$result);
		 * 
		 * $expect = '<textarea type="input" name="hoge" rows="12">hogehoge &lt;textarea</textarea>';
		 * $result = FormUtil::attr('<textarea type="input" name="hoge">hogehoge &lt;textarea</textarea>',"rows",12);
		 * eq($expect,$result);
		 */
		if($attr!="" && !is_array($attr)){
			return FormUtil::attr($html,array($attr=>$value));
		}
		if(is_array($attr) && sizeof($attr)>0){
			$ele = SimpleTag::anyhow($html);
			if(preg_match("/<input/i",$html)){
				foreach($ele->getIn("input") as $tag){
					foreach($attr as $name=>$value){
						$tag->setParameter($name,$value);
					}
					$html = str_replace($tag->getPlain(),$tag->get(),$html);
				}
			}
			if(preg_match("/<select/i",$html)){
				foreach($ele->getIn("select") as $tag){
					foreach($attr as $name=>$value){
						$tag->setParameter($name,$value);
					}
					$html = str_replace($tag->getPlain(),$tag->get(),$html);
				}
			}
			if(preg_match("/<textarea/i",$html)){
				foreach($ele->getIn("textarea") as $tag){
					foreach($attr as $name=>$value){
						$tag->setParameter($name,$value);
					}
					$html = str_replace($tag->getPlain(),$tag->get(),$html);
				}
			}
		}
		return $html;
	}
	/**
	 * クラスを追加する
	 * @param string $html
	 * @param string/array $class
	 * @return string
	 * @static
	 */
	function addClass($html,$class=null){
		/***
		 * $expect = '<input type="input" name="hoge" value="" class="hoge" />';
		 * $result = FormUtil::addClass('<input type="input" name="hoge" value=""/>',"hoge");
		 * eq($expect,$result);
		 * 
		 * $expect = '<select name="hoge" class="hoge"><option value="1"></option></select>';
		 * $result = FormUtil::addClass('<select name="hoge" class="hoge"><option value="1"></option></select>',"hoge");
		 * eq($expect,$result);
		 * 
		 * $expect = '<textarea type="input" name="hoge" class="fuga hoge">hogehoge &lt;textarea</textarea>';
		 * $result = FormUtil::addClass('<textarea type="input" name="hoge" class="fuga">hogehoge &lt;textarea</textarea>',"hoge");
		 * eq($expect,$result);
		 */
		if(!is_array($class)){
			return FormUtil::addClass($html,explode(" ",trim($class)));
		}
		if(is_array($class) && sizeof($class)>0){
			$ele = SimpleTag::anyhow($html);
			if(preg_match("/<input/i",$html)){
				foreach($ele->getIn("input") as $tag){
					$tagClass = $tag->getParameter("class","");
					if($tagClass) $class = array_merge(explode(" ",$tagClass),$class);
					$tag->setParameter("class",implode(" ",array_unique($class)));
					$html = str_replace($tag->getPlain(),$tag->get(),$html);
				}
			}
			if(preg_match("/<select/i",$html)){
				foreach($ele->getIn("select") as $tag){
					$tagClass = $tag->getParameter("class","");
					if($tagClass) $class = array_merge(explode(" ",$tagClass),$class);
					$tag->setParameter("class",implode(" ",array_unique($class)));
					$html = str_replace($tag->getPlain(),$tag->get(),$html);
				}
			}
			if(preg_match("/<textarea/i",$html)){
				foreach($ele->getIn("textarea") as $tag){
					$tagClass = $tag->getParameter("class","");
					if($tagClass) $class = array_merge(explode(" ",$tagClass),$class);
					$tag->setParameter("class",implode(" ",array_unique($class)));
					$html = str_replace($tag->getPlain(),$tag->get(),$html);
				}
			}
		}
		return $html;
	}
}