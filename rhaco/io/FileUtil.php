<?php
Rhaco::import("network.Url");
Rhaco::import("lang.StringUtil");
Rhaco::import("lang.Variable");
Rhaco::import("lang.Env");
Rhaco::import("lang.ArrayUtil");
Rhaco::import("exception.ExceptionTrigger");
Rhaco::import("exception.model.PermissionException");
Rhaco::import("exception.model.NotFoundException");
Rhaco::import("resources.Message");
Rhaco::import("io.model.File");
/**
 * ファイル操作を行うクラス
 *
 * @author Kazutaka Tokushima
 * @license New BSD License
 * @copyright Copyright 2005- rhaco project. All rights reserved.
 */
class FileUtil{
	var $resource		= array();
	var $source			= array();
	var $regClose		= true;
	var $transaction	= false;

	function FileUtil($transaction=false){
		$this->transaction = $transaction;
	}
	/**
	 * ファイルハンドルをクローズする
	 * @param string $filename
	 */
	function close($filename=""){
		/*** unit("io.FileUtilTest"); */
		$list = array();

		if(empty($filename)){
			$list = $this->resource;
		}else if(isset($this->resource[$filename])){
			$list[$filename] = $this->resource[$filename];
		}
		foreach($list as $name => $fp){
			if(is_resource($this->resource[$name]) && (Env::w() || flock($this->resource[$name],LOCK_UN))){
				$this->commit($name);
				fclose($this->resource[$name]);
			}
			unset($this->resource[$name]);
			unset($this->source[$name]);
		}
	}
	/**
	 * ファイルから取得する
	 * トランザクションを利用する事ができる
	 * @param string $filename
	 * @param string $enc
	 * @return string
	 */
	function fgets($filename,$enc=""){
		/*** unit("io.FileUtilTest"); */
		if(FileUtil::isFile($filename) && $this->_open($filename) && $this->_seekhome($filename)){
			if($this->transaction){
				return StringUtil::encode($this->source[$filename],$enc);
			}else{
				$this->_seekhome($filename);
				$buffer = "";
				while(!feof($this->resource[$filename])){
					$buffer .= fgets($this->resource[$filename],4096);
				}
			}
			return empty($enc) ? $buffer : StringUtil::encode($buffer,$enc);
		}
		return ExceptionTrigger::raise(new NotFoundException($filename));
	}
	/**
	 * ファイルに追記する
	 * トランザクションを利用する事ができる
	 * @param string $filename
	 * @param string $src
	 * @param string $enc
	 * @return boolean
	 */
	function fputs($filename,$src,$enc=""){
		/*** unit("io.FileUtilTest"); */
		if($this->_open($filename) && $this->_seekend($filename)){
			if($this->transaction){
				$this->source[$filename] = $this->source[$filename].(empty($enc) ? $src : StringUtil::encode($src,$enc));
				return true;
			}else{
				if(false !== fwrite($this->resource[$filename],(empty($enc) ? $src : StringUtil::encode($src,$enc)))) return true;
				ExceptionTrigger::raise(new PermissionException($filename));
			}
		}
		return false;
	}
	/**
	 * ファイルを上書きする
	 * トランザクションが利用できる
	 * @param string $filename
	 * @param string $src
	 * @param string $enc
	 * @return boolean
	 */
	function fwrite($filename,$src,$enc=""){
		/*** unit("io.FileUtilTest"); */
		if($this->_open($filename) && $this->_seekhome($filename)){
			if($this->transaction){
				$this->source[$filename] = (empty($enc) ? $src : StringUtil::encode($src,$enc));
				return true;
			}else{
				if(ftruncate($this->resource[$filename],0) && false !== fwrite($this->resource[$filename],(empty($enc) ? $src : StringUtil::encode($src,$enc)))) return true;
				ExceptionTrigger::raise(new PermissionException($filename));
			}
		}
		return false;
	}
	/**
	 * トランザクションを利用するか
	 * @param boolean $bool
	 */
	function setTransaction($bool){
		/*** unit("io.FileUtilTest"); */
		$this->transaction = Variable::bool($bool);
	}
	/**
	 * トランザクションをコミットする
	 * @param string $filename
	 * @return boolean
	 */
	function commit($filename){
		/*** unit("io.FileUtilTest"); */
		if($this->transaction){
			if(isset($this->resource[$filename]) && is_resource($this->resource[$filename]) && $this->_seekhome($filename) && ftruncate($this->resource[$filename],0)){
				if(false === fwrite($this->resource[$filename],$this->source[$filename])) return false;
			}
		}
		return true;
	}
	/**
	 * トランザクションをロールバックする
	 * @param string $filename
	 * @return boolean
	 */
	function rollback($filename){
		/*** unit("io.FileUtilTest"); */
		if($this->transaction) $this->_setSource($filename);
	}
	function _open($filename){
		if(!isset($this->resource[$filename])) $this->resource[$filename] = false;
		if(!is_resource($this->resource[$filename])){
			if($this->mkdir(dirname($filename))){
				$this->resource[$filename] = (!FileUtil::exist($filename) || is_writable($filename)) ? @fopen($filename,"ab+") : @fopen($filename,"r");

				if(!is_resource($this->resource[$filename])){
					unset($this->resource[$filename]);
					return ExceptionTrigger::raise(new PermissionException($filename));
				}
				if($this->transaction) $this->_setSource($filename);
				// Windows does not lock
				if(Env::w() || flock($this->resource[$filename],LOCK_SH)){
					if(!$this->regClose){
						Rhaco::register_shutdown(array($this,'close'));
						$this->regClose = true;
					}
					return true;
				}
			}
			return ExceptionTrigger::raise(new PermissionException($filename));
		}
		return true;
	}
	function _setSource($filename){
		if($this->_seekhome($filename)){
			$this->source[$filename] = "";
			while(!feof($this->resource[$filename])){
				$this->source[$filename] .= fgets($this->resource[$filename],4096);
			}
		}
	}
	function _seekend($filename){
		return (!preg_match("/\:\/\//",$filename) && isset($this->resource[$filename]) &&
				is_resource($this->resource[$filename]) && fseek($this->resource[$filename],0,SEEK_END) >= 0);
	}
	function _seekhome($filename){
		return (isset($this->resource[$filename]) && is_resource($this->resource[$filename]) &&
					!preg_match("/\:\/\//",$filename) && fseek($this->resource[$filename],0,SEEK_SET) >= 0);
	}
	function _isResource($filename){
		return (isset($this->resource[$filename]) && is_resource($this->resource[$filename]));
	}
	/**
	 * ファイルから読み込む
	 * @static
	 * @param string $filename
	 * @param string $enc
	 * @return string
	 */
	function read($filename,$enc=""){
		/*** unit("io.FileUtilTest"); */
		$buffer = "";
		if(is_array($filename)){
			foreach($filename as $f){
				$buffer .= FileUtil::read($f,$enc);
			}
			return $buffer;
		}
		if(!is_readable($filename) || !is_file($filename)){
			ExceptionTrigger::raise(new PermissionException($filename));
			return null;
		}
		return (!empty($enc)) ? StringUtil::encode(file_get_contents($filename),$enc) : file_get_contents($filename);
	}
	/**
	 * ファイルに書き込む
	 * @static
	 * @param string $filename
	 * @param string $src
	 * @param string $enc
	 * @return string
	 */
	function write($filename,$src="",$enc=""){
		/*** unit("io.FileUtilTest"); */
		$io = new FileUtil();
		if($io->fwrite($filename,$src,$enc)){
			$io->close($filename);
			unset($io);
			return true;
		}
		unset($io);
		return ExceptionTrigger::raise(new PermissionException($filename));
	}
	/**
	 * ファイルに追記する
	 * @static
	 * @param string $filename
	 * @param string $src
	 * @param string $enc
	 * @return string
	 */
	function append($filename,$src="",$enc=""){
		/*** unit("io.FileUtilTest"); */
		$io = new FileUtil();
		if($io->fputs($filename,$src,$enc)){
			$io->close($filename);
			unset($io);
			return true;
		}
		unset($io);
		return false;
	}
	/**
	 * ファイルパスを生成する
	 * @static
	 * @param string $base
	 * @param string $path
	 * @return string
	 */
	function path($base,$path=""){
		/***
		 * eq("/abc/def/hig.php",FileUtil::path("/abc/def","hig.php"));
		 * eq("/xyz/abc/hig.php",FileUtil::path("/xyz/","/abc/hig.php"));
		 */
		if(!empty($path)){
			$path = FileUtil::parseFilename($path);
			if(preg_match("/^[\/]/",$path,$null)){
				$path = substr($path,1);
			}
		}
		return Url::parseAbsolute(FileUtil::parseFilename($base),FileUtil::parseFilename($path));
	}
	/**
	 * ファイル名をそれっぽくする
	 * @static
	 * @param string $filename
	 * @return string
	 */
	function parseFilename($filename){
		/***
		 * eq("/Users/kaz/Sites/rhacotest/test/io/FileUtilTest.php",FileUtil::parseFilename("/Users/kaz/Sites/rhacotest/test/io/FileUtilTest.php"));
		 * eq("/Users/kaz/Sites/rhacotest/test/io",FileUtil::parseFilename("/Users/kaz/Sites/rhacotest/test/io"));
		 * eq("/Users/kaz/Sites/rhacotest/test/io",FileUtil::parseFilename("/Users/kaz/Sites/rhacotest/test/io/"));
		 * eq("/Users/kaz/Sites/rhacotest/test/io",FileUtil::parseFilename("\\Users\\kaz\\Sites\\rhacotest\\test\\io"));
		 * eq("C:/Users/kaz/Sites/rhacotest/test/io",FileUtil::parseFilename("C:\\Users\\kaz\\Sites\\rhacotest\\test\\io"));
		 */
		$filename = preg_replace("/[\/]+/","/",str_replace("\\","/",trim($filename)));
		return (substr($filename,-1) == "/") ? substr($filename,0,-1) : $filename;
	}
	/**
	 * ファイル、またはフォルダが存在しているか
	 * @static
	 * @param $filename
	 * @return boolean
	 */
	function exist($filename){
		/*** unit("io.FileUtilTest"); */
		return (is_readable($filename) && (is_file($filename) || is_dir($filename) || is_link($filename)));
	}
	/**
	 * ファイルが存在しているか
	 * @static
	 * @param $filename
	 * @return boolean
	 */
	function isFile($filename){
		return (is_readable($filename) && is_file($filename));
	}
	/**
	 * フォルダが存在しているか
	 * @static
	 * @param $filename
	 * @return boolean
	 */
	function isDir($filename){
		return (is_readable($filename) && is_dir($filename));
	}
	/**
	 * 指定された$directory内のファイル情報をio.model.Fileとして配列で取得
	 * @static
	 * @param string $directory
	 * @param boolean $recursive 階層を潜って取得するか
	 * @return File[]
	 */
	function ls($directory,$recursive=false){
		/*** unit("io.FileUtilTest"); */
		$dataFileList = array();
		$directory = FileUtil::parseFilename($directory);

		if(is_dir($directory)){
			if($handle = opendir($directory)){
				while($pointer = readdir($handle)){
					if($pointer != "." && $pointer != ".."){
						$source = sprintf("%s/%s",$directory,$pointer);
						if(is_file($source)){
							$dataFile = new File($source);
							$dataFileList[$dataFile->fullname] = $dataFile;
						}else{
							if($recursive) $dataFileList = array_merge($dataFileList,FileUtil::ls($source,$recursive));
						}
					}
				}
				closedir($handle);
			}
		}else{
			ExceptionTrigger::raise(new PermissionException($directory));
		}
		return $dataFileList;
	}
	/**
	 * フォルダ名の配列を取得
	 * @static
	 * @param string $directory
	 * @param boolean $recursive 階層を潜って取得するか
	 * @return string[]
	 */
	function dirs($directory,$recursive=false,$fullpath=true){
		/*** unit("io.FileUtilTest"); */
		$list		= array();
		$directory	= FileUtil::parseFilename($directory);

		if(is_readable($directory) && is_dir($directory)){
			if($handle = opendir($directory)){
				while($pointer = readdir($handle)){
					if(	$pointer != "." && $pointer != ".."){
						$source = sprintf("%s/%s",$directory,$pointer);

						if(is_dir($source)){
							$list[$source] = ($fullpath) ? $source : $pointer;
							if($recursive)	$list = array_merge($list,FileUtil::dirs($source,$recursive));
						}
					}
				}
				closedir($handle) ;
			}
		}
		usort($list,create_function('$a,$b','
									$at = strtolower($a);
									$bt = strtolower($b);
									return ($at == $bt) ? 0 : (($at < $bt) ? -1 : 1);'));
		return $list;
	}
	/**
	 * ファイルを検索する
	 * @static
	 * @param string $pattern 正規表現パターン
	 * @param string $directory
	 * @param boolean $recursive 階層を潜って取得するか
	 * @return File[]
	 */
	function find($pattern,$directory,$recursive=false){
		/*** unit("io.FileUtilTest"); */
		$match		= array();
		$directory	= trim($directory);
		$fileList	= FileUtil::ls($directory,$recursive);

		if(!empty($fileList)){
			foreach($fileList as $dataFile){
				if(preg_match($pattern,$dataFile->getName())) $match[$dataFile->getFullname()] = $dataFile;
			}
		}
		return $match;
	}
	/**
	 * コピー
	 * $sourceがフォルダの場合はそれ以下もコピーする
	 * @static
	 * @param string $source
	 * @param string $dest
	 * @param int $permission
	 * @return boolean
	 */
	function cp($source,$dest,$permission=755){
		/*** unit("io.FileUtilTest"); */
		$source	= FileUtil::parseFilename($source);
		$dest	= FileUtil::parseFilename($dest);
		$dir	= (preg_match("/^(.+)\/[^\/]+$/",$dest,$tmp)) ? $tmp[1] : $dest;
		$bool	= true;

		if(!FileUtil::exist($source)) return false;
		if(FileUtil::mkdir($dir)){
			if(is_dir($source)){
				if($handle = opendir($source)){
					while($pointer = readdir($handle)){
						if(	$pointer != "." && $pointer != ".."){
							$srcname	= sprintf("%s/%s",$source,$pointer);
							$destname	= sprintf("%s/%s",$dest,$pointer);
							$bool		= FileUtil::cp($srcname,$destname);

							if(!$bool) break;
						}
					}
					closedir($handle);
				}
				return $bool;
			}else{
				$filename = (preg_match("/^.+(\/[^\/]+)$/",$source,$tmp)) ? $tmp[1] : "";
				$dest = (is_dir($dest))	? $dest.$filename : $dest;
				if(is_writable(dirname($dest))) copy($source,$dest);
				return FileUtil::exist($dest);
			}
		}
		return true;
	}
	/**
	 * 削除
	 * $sourceが削除の場合はそれ以下も全て削除します
	 * @static
	 * @param string $source
	 * @return boolean
	 */
	function rm($source){
		/*** unit("io.FileUtilTest"); */
		if(Variable::istype("File",$source)) $source = $source->getFullname();
		$source	= FileUtil::parseFilename($source);

		if(!FileUtil::exist($source)) return true;
		if(is_writable($source)){
			if(is_dir($source)){
				if($handle = opendir($source)){
					$list = array();
					while($pointer = readdir($handle)){
						if($pointer != "." && $pointer != "..") $list[] = sprintf("%s/%s",$source,$pointer);
					}
					closedir($handle);
					foreach($list as $path){
						if(!FileUtil::rm($path)) return false;
					}
				}
				if(rmdir($source)){
					clearstatcache();
					return true;
				}
			}else if(is_file($source) && unlink($source)){
				clearstatcache();
				return true;
			}
		}
		ExceptionTrigger::raise(new PermissionException($source));
		return false;
	}
	/**
	 * ディレクトリを作成する
	 * @static
	 * @param string $source
	 * @param int $permission
	 * @return boolean
	 */
	function mkdir($source,$permission=null){
		/*** unit("io.FileUtilTest"); */
		$source = FileUtil::parseFilename($source);
		if(!FileUtil::isDir($source)){
			$path = $source;
			$dirstack = array();
			while(!is_dir($path) && $path != DIRECTORY_SEPARATOR){
				array_unshift($dirstack,$path);
				$path = dirname($path);
			}
			while($path = array_shift($dirstack)){
				$bool = (empty($permission)) ? @mkdir($path) : @mkdir($path,Rhaco::phpexe(sprintf("return %04d;",$permission)));
				if($bool === false) return ExceptionTrigger::raise(new PermissionException($path));
			}
		}
		if(!empty($permission)) FileUtil::chmod($source,$permission);
		return true;
	}
	/**
	 * 移動
	 * @static
	 * @param string $source
	 * @param string $dest
	 * @return boolean
	 */
	function mv($source,$dest){
		/*** unit("io.FileUtilTest"); */
		$source = FileUtil::parseFilename($source);
		$dest = FileUtil::parseFilename($dest);
		return (FileUtil::exist($source) && FileUtil::mkdir(dirname($dest))) ? rename($source,$dest) : false;
	}
	/**
	 * 権限を変更する
	 * @static
	 * @param string $source
	 * @param int $permission
	 * @return boolean
	 */
	function chmod($source,$permission=755){
		if(FileUtil::exist($source) && Env::w()){
			return chmod($source,Rhaco::phpexe(sprintf("return %04d;",$permission)));
		}
		return true;
	}
	/**
	 * ファイルサイズを取得する
	 * @static
	 * @param string $filename
	 * @param string $format
	 * @return int
	 */
	function size($filename,$format="kb"){
		if(is_readable($filename) && is_file($filename)){
			switch(strtolower($format)){
				case "b":	return filesize($filename);
				case "mb":	return ceil((filesize($filename) / 1024) / 1024);
				case "gb":	return ceil(((filesize($filename) / 1024) / 1024) / 1024);
				case "tb":	return ceil((((filesize($filename) / 1024) / 1024) / 1024) / 1024);
				case "kb":
				default:	return $size = ceil(filesize($filename) / 1024);
			}
		}
		return 0;
	}
	/**
	 * フォルダの空きを取得する
	 * @static
	 * @param string $directory
	 * @param string $format
	 * @return int
	 */
	function free($directory,$format="kb"){
		if(is_readable($directory) && is_dir($directory)){
			switch(strtolower($format)){
				case "b":	return disk_free_space($directory);
				case "mb":	return ceil((disk_free_space($directory) / 1024) / 1024);
				case "gb":	return ceil(((disk_free_space($directory) / 1024) / 1024) / 1024);
				case "tb":	return ceil((((disk_free_space($directory) / 1024) / 1024) / 1024) / 1024);
				case "kb":
				default:	return ceil(disk_free_space($directory) / 1024);
			}
		}
		return 0;
	}
	/**
	 * 更新時間を取得
	 * @static
	 * @param $filename
	 * @return int
	 */
	function time($filename){
		return (is_readable($filename) && is_file($filename)) ? filemtime($filename) : -1;
	}
	/**
	 * 複数のファイルから単一のソースを作成する
	 * @static
	 * @param string/string[] $paths
	 * @return string
	 */
	function pack($paths,$replace=""){
		/***
		 * $path = FileUtil::path(Rhaco::rhacopath(),"/io/FileUtil.php");
		 * $paths = array("FileUtil.php"=>$path,"io/FileUtil.php"=>$path);
		 * $src = FileUtil::pack($paths);
		 * preg_match_all("/\[\[(.+)?\]\]/",$src,$match);
		 * eq(2,sizeof($match[1]));
		 */
		$io = new FileUtil();
		$result = "";
		$packs = array();

		foreach(ArrayUtil::arrays($paths) as $key => $value){
			if(preg_match("/^[\d]+$/",$key)){
				if(is_dir($value)){
					foreach(FileUtil::dirs($value,true) as $dir){
						$packs[substr($dir,strlen(empty($replace) ? $value : $replace))] = $dir;
					}
					foreach(FileUtil::ls($value,true) as $file){
						$packs[substr($file->fullname,strlen(empty($replace) ? $value : $replace))] = $file->fullname;
					}
				}else if(is_file($value)){
					$packs[substr($value,strlen($replace))] = $value;
				}
			}else{
				$packs[$key] = $value;
			}
		}
		foreach($packs as $name => $path){
			if(!FileUtil::exist($path)) Logger::error("pack fail [".$path."]");
			if(is_file($path)){
				$result .= "[[".$name."]]\n";
				$result .= chunk_split(base64_encode($io->read($path)),76,"\n");
				$result .= "\n\n";
			}else if(is_dir($path)){
				$result .= "[[[".$name."]]]\n";
				$result .= "\n\n";
			}
		}
		return $result;
	}
	/**
	 * packされたソースから展開する
	 * @static
	 * @param string $src
	 * @param string $outputdir
	 * @return boolean
	 */
	function unpack($src,$outputdir){
		$bool = true;
		$src = StringUtil::toULD($src);

		foreach(explode("\n\n\n",$src) as $value){
			list($conf,$body) = explode("\n",$value."\n",2);
			if(!empty($conf)){
				if(preg_match("/^\[\[\[([^\[\]]+?)\]\]\]/",$conf,$match)){
					if(!FileUtil::mkdir(FileUtil::path($outputdir,$match[1]))) $bool = false;
				}else if(preg_match("/\[\[([^\[\]]+?)\]\]/",$conf,$match)){
					if(!FileUtil::write(FileUtil::path($outputdir,$match[1]),(base64_decode($body)))) $bool = false;
				}
			}
		}
		return $bool;
	}
	/**
	 * パブリックなファイルか
	 * @param string $path
	 * @return boolean
	 */
	function isPublic($path){
		return (strpos($path,"/.") === false && (basename($path) == "__init__.php" || strpos($path,"/_") === false));
	}	
	/**
	 * ファイル、またはディレクトリからtar圧縮のデータを作成する
	 * @param string $base
	 * @param string $path
	 * @param string $ignore_pattern 除外パターン
	 * @return string
	 */
	function tar($base,$path=null,$ignore_pattern=null){
		$result = null;
		$files = array();
		$base = FileUtil::parseFilename($base);
		$path = FileUtil::parseFilename($path);
		$ignore = (!empty($ignore_pattern));
		if(substr($base,0,-1) != "/") $base .= "/";
		$filepath = Url::parseAbsolute($base,$path);

		if(is_dir($filepath)){
			foreach(FileUtil::dirs($filepath,true) as $dir) $files[$dir] = FileUtil::_tarTypeDir();
			foreach(self::ls($filepath,true) as $file) $files[$file->getFullname()] = FileUtil::_tarTypeFile();
		}else{
			$files[$filepath] = FileUtil::_tarTypeFile();
		}
		foreach($files as $filename => $type){
			$target_filename = str_replace($base,"",$filename);
			if(!$ignore || !FileUtil::_isPattern($ignore_pattern,$target_filename)){
				switch($type){
					case FileUtil::_tarTypeFile():
						$info = stat($filename);
						$rp = fopen($filename,"rb");
							$result .= FileUtil::_tarHead($type,$target_filename,filesize($filename),fileperms($filename),$info[4],$info[5],filemtime($filename));
							while(!feof($rp)){
								$buf = fread($rp,512);
								if($buf !== "") $result .= pack("a512",$buf);
							}
						fclose($rp);
						break;
					case FileUtil::_tarTypeDir():
						$result .= FileUtil::_tarHead($type,$target_filename);
						break;
				}
			}
		}
		$result .= pack("a1024",null);
		return $result;
	}
	function _isPattern($pattern,$value){
		$pattern = (is_array($pattern)) ? $pattern : array($pattern);
		foreach($pattern as $p){
			if(preg_match("/".str_replace(array("\/","/","__SLASH__"),array("__SLASH__","\/","\/"),$p)."/",$value)) return true;
		}
		return false;
	}
	function _tarHead($type,$filename,$filesize=0,$fileperms=0744,$uid=0,$gid=0,$update_date=null){
		if($update_date === null) $update_date = time();
		$checksum = 256;
		$first = pack("a100a8a8a8a12A12",$filename,
						sprintf("%06s ",decoct($fileperms)),sprintf("%06s ",decoct($uid)),sprintf("%06s ",decoct($gid)),
						sprintf("%011s ",decoct(($type === 0) ? $filesize : 0)),sprintf("%11s",decoct($update_date)));
		$last = pack("a1a100a6a2a32a32a8a8a155a12",$type,null,null,null,null,null,null,null,null,null);
		for($i=0;$i<strlen($first);$i++) $checksum += ord($first[$i]);
		for($i=0;$i<strlen($last);$i++) $checksum += ord($last[$i]);
		return $first.pack("a8",sprintf("%6s ",decoct($checksum))).$last;
	}
	function _tarTypeDir(){
		return 5;
	}
	function _tarTypeFile(){
		return 0;
	}
	/**
	 * tarを解凍する、$outpathがあれば書き出す
	 * @param string $src
	 * @param string $outpath
	 * @return array
	 */
	function untar($src,$outpath=null){
		$result = array();
		$out = null;
		for($pos=0,$vsize=0,$cur="";;){
			$buf = substr($src,$pos,512);
			if(strlen($buf) < 512) break;
			$data = unpack("a100name/a8mode/a8uid/a8gid/a12size/a12mtime/"
							."a8chksum/"
							."a1typeflg/a100linkname/a6magic/a2version/a32uname/a32gname/a8devmajor/a8devminor/a155prefix",
							 $buf);
			$pos += 512;
			if(!empty($data["name"])){
				$obj = new stdClass();
				$obj->type = (int)$data["typeflg"];
				$obj->path = $data["name"];
				$obj->update = base_convert($data["mtime"],8,10);
				if(!empty($outpath)) $out = Url::parseAbsolute($outpath,$obj->path);

				switch($obj->type){
					case FileUtil::_tarTypeFile():
						$obj->size = base_convert($data["size"],8,10);
						$obj->content = substr($src,$pos,$obj->size);
						$pos += (ceil($obj->size / 512) * 512);
						if(!empty($outpath)){
							FileUtil::write($out,$obj->content);
							touch($out,$obj->update);
						}
						break;
					case FileUtil::_tarTypeDir():
						if(!empty($outpath)){
							FileUtil::mkdir($out);
						}
						break;
				}
				$result[$obj->path] = $obj;
			}
		}
		return $result;
	}
}
?>