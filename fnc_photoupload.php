<?php
	$database = "if21_and_pee";
	
	function resize_photo($source, $width, $height, $keep_orig_proportion = true){
		$image_width = imagesx($source);
		$image_height = imagesy($source);
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
		
		//Loome uue ajutise pildiobjekti
		$my_new_image = imagecreatetruecolor($new_width, $new_height);
		//Säilitame vajadusel läbipaistvuse png ja gif piltide jaoks
		imagesavealpha($my_new_image, true);
		$trans_color = imagecolorallocatealpha($my_new_image, 0, 0, 0, 127);
		imagefill($my_new_image, 0, 0, $trans_color);
		
			imagecopyresampled($my_new_image, $source, 0, 0, $cut_x, $cut_y, $new_width, $new_height, $cut_size_width, $cut_size_height);
			return $my_new_image;
	}
	
	function add_watermark($image, $watermark_file){
		$watermark = imagecreatefrompng($watermark_file);
		$watermark_width = imagesx($watermark);
		$watermark_height = imagesy($watermark);
		$watermark_x = imagesx($image) - $watermark_width - 10;
		$watermark_y = imagesy($image) - $watermark_height - 10;
		imagecopy($image, $watermark, $watermark_x, $watermark_y, 0, 0, $watermark_width, $watermark_height);
		imagedestroy($watermark);
		return $image;
	}

	function save_image($image, $file_type, $target){
		$notice = null;
		
		if($file_type == "jpg"){
			if(imagejpeg($image, $target, 90)){
				$notice = "Foto salvestamine õnnestus.";
			} else {
				$notice = "Foto salvestamine ei õnnestunud.";
			}
		}

		if($file_type == "png"){
			if(imagepng($image, $target, 6)){
				$notice = "Foto salvestamine õnnestus.";
			} else {
				$notice = "Foto salvestamine ei õnnestunud.";
			}
		}
		
		if($file_type == "gif"){
			if(imagegif($image, $target)){
				$notice = "Foto salvestamine õnnestus.";
			} else {
				$notice = "Foto salvestamine ei õnnestunud.";
			}
		}
		
		return $notice;
	}

	function store_photo_data($image_file_name, $alt, $privacy){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("INSERT INTO vprg_photos (userid, filename, alttext, privacy) VALUES (?, ?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("issi", $_SESSION["user_id"], $image_file_name, $alt, $privacy);
		if($stmt->execute()){
		  $notice = "Foto lisati andmebaasi!";
		} else {
		  $notice = "Foto lisamisel andmebaasi tekkis tõrge: " .$stmt->error;
		}
		
		$stmt->close();
		$conn->close();
		return $notice;
	}