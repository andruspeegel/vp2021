<?php
	class Photoupload{
		private $photo_to_upload;
		private $file_type;//esialgu saadame, hiljem teeb klass selle väärtuse ise kindlaks.
		private $my_temp_image;
		private $my_new_temp_image;
		
		function __construct($photo, $file_type){
			$this->photo_to_upload = $photo;
			$this->file_type = $file_type;
			$this->my_temp_image = $this->create_image_from_file($this->photo_to_upload["tmp_name"], $this->file_type);
		}
		
		function __destruct(){
			if(isset($this->my_temp_image){
				@imagedestroy($this->my_temp_image);
			}
			if(isset($this->my_new_temp_image){
				@imagedestroy($this->my_new_temp_image);
			}
		}
		
		private function create_image_from_file($photo, $file_type){
			//loome image objekti ehk pikslikogumi
			if($file_type == "jpg"){
				$my_temp_image = imagecreatefromjpeg($photo);
			}
			if($file_type == "png"){
				$my_temp_image = imagecreatefrompng($photo);
			}
			if($file_type == "gif"){
				$my_temp_image = imagecreatefromgif($photo);
			}
			return $my_temp_image;
		}
		
		public function resize_photo($width, $height, $keep_orig_proportion = true){
		$image_width = imagesx($this->my_temp_image);
		$image_height = imagesy($this->my_temp_image);
		$new_width = $width;
		$new_height = $height;
		$cut_x = 0;
		$cut_y = 0;
		$cut_size_width = $image_width;
		$cut_size_height = $image_height;
		
		if($width == $height){
			if($image_width > $image_height){
				$cut_size_width = $image_height;
				$cut_x = round(($image_width - $cut_size_width) /2);
			} else {
				$cut_size_height = $image_width;
				$cut_y = round(($image_height - $cut_size_height) /2);
			}
		} elseif($keep_orig_proportion){
			if($image_width / $width > $image_height / $height){
				$new_height = round($image_height / ($image_width / $width));
			} else {
				$new_width = round($image_width / ($image_height / $height));
			}
		} else {
			if($image_width / $width < $image_height / $height){
				$cut_size_height = round($image_width / $width * $height);
				$cut_y = round(($image_height - $cut_size_height) / 2);
			} else {
				$cut_size_width = round($image_height / $height * $width);
				$cut_x = round(($image_width - $cut_size_width) / 2);
			}
		}
		$this->my_new_temp_image = imagecreatetruecolor($new_width, $new_height);
		//Säilitame vajadusel läbipaistvuse png ja gif piltide jaoks
		imagesavealpha($this->my_new_temp_image, true);
		$trans_color = imagecolorallocatealpha($this->my_new_temp_image, 0, 0, 0, 127);
		imagefill($this->my_new_temp_image, 0, 0, $trans_color);
			imagecopyresampled($this->my_new_temp_image, $this->my_temp_image, 0, 0, $cut_x, $cut_y, $new_width, $new_height, $cut_size_width, $cut_size_height);
	}
	
	public function add_watermark($watermark_file){
		$watermark = imagecreatefrompng($watermark_file);
		$watermark_width = imagesx($watermark);
		$watermark_height = imagesy($watermark);
		$watermark_x = imagesx($this->my_new_temp_image) - $watermark_width - 10;
		$watermark_y = imagesy($this->my_new_temp_image) - $watermark_height - 10;
		imagecopy($this->my_new_temp_image, $watermark, $watermark_x, $watermark_y, 0, 0, $watermark_width, $watermark_height);
		imagedestroy($watermark);
	}
	
	public function save_image($target){
		$notice = null;
		if($this->file_type == "jpg"){
			if(imagejpeg($this->my_new_temp_image, $target, 90)){
				$notice = "foto salvestamine õnnestus.";
			} else {
				$notice = "Foto salvestamine ei õnnestunud.";
			}
		}
		if($this->file_type == "png"){
			if(imagepng($this->my_new_temp_image, $target, 6)){
				$notice = "foto salvestamine õnnestus.";
			} else {
				$notice = "Foto salvestamine ei õnnestunud.";
			}
		}
		if($this->file_type == "gif"){
			if(imagegif($this->my_new_temp_image, $target)){
				$notice = "foto salvestamine õnnestus.";
			} else {
				$notice = "Foto salvestamine ei õnnestunud.";
			}
		}
		imagedestroy($this->my_new_temp_image);
		return $notice;
	}
	
	public function move_orig_photo($target){
		$notice = null;
		if(move_uploaded_file($this->photo_to_upload["tmp_name"], $target)){
				$notice .= " Originaalfoto laeti üles!";
			} else {
				$notice .= " Foto üleslaadimine ei õnnestunud!";
			}
			return $notice;
	}
		
	}