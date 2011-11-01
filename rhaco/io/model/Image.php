<?php
Rhaco::import("exception.ExceptionTrigger");
Rhaco::import("exception.model.NotFoundException");
Rhaco::import("exception.model.PermissionException");
Rhaco::import("exception.model.DataTypeException");
/**
 * 画像のリサイズ等を行う
 *
 * @author riaf <riafweb@gmail.com>
 * @author kazutaka tokushima
 * @author yabeken
 * @license New BSD License
 * @copyright Copyright 2009- rhaco project. All rights reserved.
 */
class Image{
	var $handle;
	var $width;
	var $height;
	var $type;
	var $imageinfo;

	/**
	 * コンストラクタ
	 * @param $filename
	 */
	function Image($filename=null){
		if(!extension_loaded('gd')) ExceptionTrigger::raise(new NotFoundException("GD"));
		Rhaco::register_shutdown(array($this,"close"));
		if(!is_null($filename)) $this->loadFile($filename);
	}
	/**
	 * ファイルハンドルを閉じる
	 */
	function close(){
		if($this->handle !== null) imagedestroy($this->handle);
	}
	/**
	 * バイナリから画像を読み込む
	 * @param binary $src
	 * @param string $type
	 */
	function parse($src,$type=null){
		$this->handle = imagecreatefromstring($src);
		if($this->handle !== false){
			$this->width = imagesx($this->handle);
			$this->height = imagesy($this->handle);
			$this->type = is_null($type) ? IMAGETYPE_JPEG : $type;
		}
	}
	/**
	 * ファイルから画像を読み込む
	 * @param string $filename
	 * @return object Image
	 */
	function loadFile($filename){
		if(!is_file($filename)) return ExceptionTrigger::raise(new PermissionException($filename));

		$size = getimagesize($filename,$this->imageinfo);
		if($size === false) return ExceptionTrigger::raise(new DataTypeException($filename));
		list($this->width,$this->height,$this->type) = $size;

		switch($this->type){
			case IMAGETYPE_GIF:
				$this->handle = imagecreatefromgif($filename);
				break;
			case IMAGETYPE_JPEG:
				$this->handle = imagecreatefromjpeg($filename);
				break;
			case IMAGETYPE_PNG:
				$this->handle = imagecreatefrompng($filename);
				break;
			default:
				ExceptionTrigger::raise(new DataTypeException($filename));
				$this->handle = null;
		}
		return $this;
	}
	/**
	 * 画像が指定サイズより大きい場合にリサイズを行う
	 * @param int $x
	 * @param int $y
	 * @return object Image
	 */
	function fit($x,$y){
		$this->fitWidth($x);
		$this->fitHeight($y);
		return $this;
	}
	/**
	 * 画像の横が指定サイズより大きい場合にリサイズを行う
	 * @param int $x
	 * @return object Image
	 */
	function fitWidth($x){
		if($x < $this->width) $this->resizeWidth($x);
		return $this;
	}
	/**
	 * 画像の縦が指定サイズより大きい場合にリサイズを行う
	 * @param int $y
	 * @return object Image
	 */
	function fitHeight($y){
		if($y < $this->height) $this->resizeHeight($y);
		return $this;
	}
	/**
	 * リサイズを行う
	 * @param int $x
	 * @param int $y
	 * @return object Image
	 */
	function resize($x, $y){
		$this->resizeWidth($x);
		$this->resizeHeight($y);
		return $this;
	}
	/**
	 * 幅指定のリサイズを行う
	 * @param int $width
	 * @param boolean $keep
	 * @return object Image
	 */
	function resizeWidth($width,$keep=true){
		$dst_height = $keep ? $this->height : ($this->height / ($this->width / $width));
		$this->_resize($width, $dst_height);
		return $this;
	}
	/**
	 * 縦指定のリサイズを行う
	 * @param int $height
	 * @param boolean $keep
	 * @return object Image
	 */
	function resizeHeight($height,$keep=true){
		$dst_width  = $keep ? $this->width : ($this->width / ($this->height / $height));
		$this->_resize($dst_width, $height);
		return $this;
	}
	/**
	 * ファイルに出力する
	 * @param string $filename
	 * @param string $type
	 * @return boolean
	 */
	function save($filename,$type=null){
		if(is_null($type)) $type = $this->type;
		
		$bool = false;
		switch($type){
			case IMAGETYPE_GIF:
				$bool = imagegif($this->handle,$filename);
				break;
			case IMAGETYPE_JPEG:
				$bool = imagejpeg($this->handle,$filename);
				break;
			case IMAGETYPE_PNG:
				$bool = imagepng($this->handle,$filename);
				break;
		}
		return ($bool) ? true : ExceptionTrigger::raise(new PermissionException($filename));
	}
	/**
	 * イメージを取得する
	 * @param string $type
	 * @return binary
	 */
	function read($type=null){
		if(is_null($type)) $type = $this->type;
		ob_start();
		$this->_outputImage($type);
		return ob_get_clean();
	}
	/**
	 * 標準出力へ出力する
	 * @param string $type
	 * @return boolean
	 */
	function output($type=null){
		if(is_null($type)) $type = $this->type;
		header('Content-Type: '.image_type_to_mime_type($type));
		return $this->_outputImage($type);
	}
	function _outputImage($type){
		switch($type){
			case IMAGETYPE_GIF:
				return imagegif($this->handle);
			case IMAGETYPE_JPEG:
				return imagejpeg($this->handle);
			case IMAGETYPE_PNG:
				return imagepng($this->handle);
		}
		return ExceptionTrigger::raise(new DataTypeException($type));
	}
	function _resize($dst_width,$dst_height){
		switch($this->type){
			case IMAGETYPE_GIF:
				$dst_image = imagecreate($dst_width,$dst_height);
				$tcolor = imagecolorallocate($dst_image,255,255,255);
				imagecolortransparent($dst_image,$tcolor);
				imagefilledrectangle($dst_image,0,0,$dst_width,$dst_height,$tcolor);
				break;
			default:
				$dst_image = imagecreatetruecolor($dst_width,$dst_height);
				break;
		}
		imagecopyresized($dst_image,$this->handle,0,0,0,0,$dst_width,$dst_height,$this->width,$this->height);
		imagedestroy($this->handle);
		$this->width = $dst_width;
		$this->height = $dst_height;
		$this->handle = $dst_image;
	}
}
?>
