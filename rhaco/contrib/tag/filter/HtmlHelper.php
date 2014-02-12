<?php
/**
 * Htmlヘルパ
 * 
 * @author Kazutaka Tokushima
 * @license New BSD License
 * @copyright Copyright 2007 rhaco project. All rights reserved.
 */
class HtmlHelper{
	function after($src,&$parser){
		return $this->_img2jsinclude($src,$parser);
	}	
	function _img2jsinclude($src,&$parser){
		if(preg_match("/body /i",$src) && SimpleTag::setof($tag,$src,"body")){
			$plain = $tag->getValue();
			$io = new FileUtil();
			$metas = array();
			
			foreach($tag->getIn("img") as $obj){
				if(Variable::bool($obj->getParameter("reference",false))){
					$href = $obj->getParameter("src");
					$id = $obj->getParameter("id","img".preg_replace("/[^\w]/","_",$href));
					$obj->removeParameter("reference");
					$obj->removeParameter("src");
					$obj->setParameter("id",$id);
					
					if(!empty($href)){
						$plain = str_replace($obj->getPlain(),$obj->get(),$plain);
						$metas[$id] = base64_encode($io->read(Url::parseAbsolute(dirname(Rhaco::templatepath($parser->fileName)),$href)));
					}
				}
			}
			if(!empty($metas)){
				$script = "\n<script language=\"JavaScript\"><!--\n";
				foreach($metas as $id => $meta) $script .= "document.getElementById(\"".$id."\").src = \"data:image/gif;base64,".$meta."\";";
				$script .= "\n//--></script>\n";
				
				$plain = str_replace($tag->getValue(),$plain.$script,$tag->getPlain());
				$src = str_replace($tag->getPlain(),$plain,$src);
			}
		}
		return $src;
	}
}
?>