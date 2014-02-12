<?php
Rhaco::import('network.http.ServiceRestAPIBase');
Rhaco::import("tag.model.SimpleTag");
/**
 * Wikipedia search
 *
 * @author
 * @license New BSD License
 */
class WikipediaAPI extends ServiceRestAPIBase {
	
	function WikipediaAPI(){
		parent::ServiceRestAPIBase("http://wikipedia.simpleapi.net/api");
		
	}
	
	function search($query){
		$result = $this->get(array("keyword"=>$query));
		$list = array();
		if(SimpleTag::setof($tags,$result,"Results")){
			foreach($tags->getIn('result') as $tag){
				$list[] = $tag->toHash();
			}
		}
		return $list;
	}
}
?>