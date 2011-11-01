<?php
class Repository{
	/**
	 * repository serverからdownloadする
	 * 要 Zlibモジュール
	 * 
	 * @param string $repository_name
	 * @param string $package
	 * @param string $download_path
	 * @return boolean
	 */
	function download($package,$download_path){
		foreach(Rhaco::repositorys() as $search_path){
			$search_path = str_replace('\\','/',$search_path);
			if(substr($search_path,-1) != '/') $search_path = $search_path.'/';
			
			$fp = @gzopen($search_path.$package.'.tgz',"rb");
			if($fp !== false){
				$buf = null;
				while(!gzeof($fp)) $buf .= gzread($fp,4096);
				gzclose($fp);
				FileUtil::untar($buf,$download_path);
				return true;
			}
		}
		return false;		
	}
}
?>