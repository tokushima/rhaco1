<?php
Rhaco::import("tag.model.SimpleTag");
Rhaco::import("network.http.Http");
Rhaco::import("lang.Variable");
Rhaco::import("lang.DateUtil");
Rhaco::import("lang.ArrayUtil");
/**
 * Ping操作
 * 
 * @author Kazutaka Tokushima
 * @license New BSD License
 * 
 * @copyright Copyright 2008 rhaco project. All rights reserved.
 */
class Ping{
	var $about;
	var $url;
	var $title;
	var $description;
	var $creator;
	var $date;
	var $trackback;
	
	function Ping($url=null,$date=null,$trackback=null,$about=null){
		$this->url = $url;
		$this->about = $about;
		$this->trackback = $trackback;
		$this->date = $date;
	}
	function setTitle($value){
		$this->title = htmlspecialchars($value,ENT_QUOTES);
	}
	function setDescription($value){
		$this->description = htmlspecialchars($value,ENT_QUOTES);
	}
	function setCreator($value){
		$this->creator = htmlspecialchars($value,ENT_QUOTES);
	}

	function send($sendurls){
		if(empty($this->url)) return array();
		$results = array();
		$title = empty($this->title) ? $this->url : $this->title;
		$tag = new SimpleTag('methodCall',array(
								new SimpleTag('methodName','weblogUpdates.ping'),
								new SimpleTag("params",array(
										new SimpleTag('param',new SimpleTag('value',$this->title)),
										new SimpleTag('param',new SimpleTag('value',$this->url))
										))
								));
		foreach(ArrayUtil::arrays($sendurls) as $sendurl){
			foreach(explode("\n",StringUtil::toULD($sendurl)) as $pingurl){
				$results[] = Http::post($pingurl,null,array("type"=>"text/xml","rawdata"=>$tag->get(true)));
			}
		}
		return $results;
	}
	function rdf(){
		if(empty($this->url)) return "";
		$desc = new SimpleTag("rdf:Description",null,
							array("rdf:about"=>(empty($this->about) ? $this->url : $this->about),
								"dc:title"=>str_replace("--","&minus;&minus;",(empty($this->title) ? $this->url : $this->title)),
								"dc:identifier"=>$this->url,
								"dc:date"=>DateUtil::formatW3C(empty($this->date) ? time() : $this->date)
							)
					);
		if(!empty($this->trackback)) $desc->setParameter("trackback:ping",$this->trackback);
		if(!empty($this->description)) $desc->setParameter("dc:description",str_replace("--","&minus;&minus;",$this->description));
		if(!empty($this->creator)) $desc->setParameter("dc:creator",str_replace("--","&minus;&minus;",$this->creator));		

		$tag = new SimpleTag("rdf:RDF",$desc,
						array("xmlns:rdf"=>"http://www.w3.org/1999/02/22-rdf-syntax-ns#",
								"xmlns:dc"=>"http://purl.org/dc/elements/1.1/",
								"xmlns:trackback"=>"http://madskills.com/public/xml/rss/module/trackback/"
							)
				);
		return $tag->get();
	}
	function parse($src){
		while(SimpleTag::setof($tag,$src,"rdf:RDF")){
			foreach($tag->getIn("rdf:Description") as $desc){
				$this->title = $desc->param("dc:title");

				if(!empty($this->title)){
					$this->about = $desc->param("rdf:about");
					$this->url = $desc->param("dc:identifier");
					$this->date = DateUtil::parse($desc->param("dc:date"));
					
					$this->trackback = $desc->param("trackback:ping");
					$this->description = $desc->param("dc:description");
					$this->creator = $desc->param("dc:creator");
					
					return true;
				}
			}
			$src = str_replace($tag->getPlain(),"",$src);
		}
		return false;
	}
	function toRssItem(){
		$item = new RssItem20($this->title,$this->description,$this->url);
		$item->setAuthor($this->creator);
		$item->setPubDate($this->date);
		return $item;
	}
}
?>