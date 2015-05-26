<?php
require_once("Zebra_Image.php");

class ImageManager {

	public function __construct(){
		
		
	}
	
	public function imageResize($source, $destination, $width, $height){
		$image = new Zebra_Image();
		
		$image->source_path = $source;
		$image->target_path = $destination;
		$image->jpeg_quality = 90;
		
		$image->preserve_aspect_ratio = true;
		$image->enlarge_smaller_images = true;
		$image->preserve_time = true;
		
		if (!$image->resize($width, $height, ZEBRA_IMAGE_NOT_BOXED, '#FFFFFF')) {
		
			// if there was an error, let's see what the error is about
			switch ($image->error) {
		
				case 1:
					echo 'Source file could not be found!';
					break;
				case 2:
					echo 'Source file is not readable!';
					break;
				case 3:
					echo 'Could not write target file!';
					break;
				case 4:
					echo 'Unsupported source file format!';
					break;
				case 5:
					echo 'Unsupported target file format!';
					break;
				case 6:
					echo 'GD library version does not support target file format!';
					break;
				case 7:
					echo 'GD library is not installed!';
					break;
		
			}
		
			// if no errors
		} else {
		
			echo 'Success!';
		
		}
		
	}
	
	public function fileDestManagement($source,$id){
		$fil= strrchr($source,'/');
		$deb=substr($fil, 0,strrpos($fil,"."));
		$ext= strrchr($fil,'.');
		$filename = $deb.'-'.$id.$ext;
		return $filename;
	}
	
}