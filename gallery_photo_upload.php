<?php
	require_once("use_session.php");


	require_once("../../config.php");
	require_once("fnc_photoupload.php");
	require_once("fnc_general.php");
	require_once("classes/Photoupload.class.php");
	
	$photo_error = null;
	$photo_upload_notice = null;
	$photo_file_name_prefix = "vp_";
	$photo_file_size_limit = 1024 * 1024;
	$photo_width_limit = 600;
	$photo_height_limit = 400;
	$thumbnail_height = $thumbnail_width = 100;
	$image_size_ratio = 1;
	$alt_text = null;
	$privacy = 1;
	$watermark_file = "pics/vp_logo_color_w100_overlay.png";
	$allowed_photo_types = ["image/jpeg", "image/png", "image/gif"];
	
	if(isset($_POST["photo_submit"])){
		//kas alt tekst on
		if(isset($_POST["alt_input"]) and !empty($_POST["alt_input"])){
			$alt_text = test_input(filter_var($_POST["alt_input"], FILTER_SANITIZE_STRING));
		}
		//kas on privaatsus
		if(isset($_POST["privacy_input"]) and !empty($_POST["privacy_input"])){
			$privacy = filter_var($_POST["privacy_input"], FILTER_VALIDATE_INT);
		}
		if(empty($privacy)){
			$photo_error . " Privaatsus on määramata!";
		}
		//kas fail on valitud
        if(isset($_FILES["photo_input"]["tmp_name"]) and !empty($_FILES["photo_input"]["tmp_name"])){
			//fail on, klass kontrollib kohe, kas on foto
			$photo_upload = new Photoupload($_FILES["photo_input"]);
			if(empty($photo_upload->error)){
				//kas on lubatud tüüpi
				$photo_error .= $photo_upload->check_allowed_type($allowed_photo_types);
				
				if(empty($photo_upload->error)){
					//kas on lubatud suurusega
					$photo_error .= $photo_upload->check_size($photo_file_size_limit);
					//kui seni vigu pole, laeme üles
					if(empty($photo_error)){
						$photo_upload->create_filename($photo_file_name_prefix);
						$photo_upload->resize_photo($photo_width_limit, $photo_height_limit);
						$photo_upload->add_watermark($watermark_file);
						$photo_upload_notice = "Vähendatud pildi " .$photo_upload->save_image($photo_upload_normal_dir .$photo_upload->file_name);
						$photo_upload->resize_photo($thumbnail_width, $thumbnail_height);
						$photo_upload_notice .= " Pisipildi " .$photo_upload->save_image($photo_upload_thumb_dir .$photo_upload->file_name);
						$photo_upload_notice .= $photo_upload->move_orig_photo($photo_upload_orig_dir .$photo_upload->file_name);
						$photo_upload_notice .= " " .store_photo_data($photo_upload->file_name, $alt_text, $privacy);
					}
				}
			} else {
				$photo_error .= " " .$photo_upload->error;
			}
			unset($photo_upload);
			$alt_text = null;
			$privacy = 1;
		} else {
            $photo_error = "Pildifaili pole valitud!";
        }
        
        if(empty($photo_upload_notice)){
			$photo_upload_notice = $photo_error;
		}
    }		
	
	$to_head = '<script src="javascript/fileSizeCheck.js" defer></script>' ."\n";
	
	require("page_header.php");
?>

	<h1><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrameerimine</h1>
	<p>See leht on loodud õppetöö raames ja ei sisalda tõsiseltvõetavat sisu.</p>
	<p>Õppetöö toimub <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
	<ul>
        <p><a href="?logout=1">Logi välja</a></p>
		<p><a href="home.php">Avaleht</a></p>
    </ul>
		<hr>
	<h2>Galerii piltide üleslaadimine</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
		<label for="photo_input">Vali pildifail</label>
		<input type="file" name="photo_input" id="photo_input">
		<br>
		<label for="alt_input">Alternatiivtekst (alt):</label>
		<input type="text" name="alt_input" id="alt_input" placeholder="alternatiivtekst..." value="<?php echo $alt_text; ?>">
		<br>
		<input type="radio" name="privacy_input" id="privacy_input_1" value="1" <?php if($privacy == 1){echo " checked";}?>>
		<label for="privacy_input_1">Privaatne (ainult mina näen)</label>
		<br>
		<input type="radio" name="privacy_input" id="privacy_input_2" value="2" <?php if($privacy == 2){echo " checked";}?>>
		<label for="privacy_input_2">Sisseloginud kasutajatele</label>
		<br>
		<input type="radio" name="privacy_input" id="privacy_input_3" value="3" <?php if($privacy == 3){echo " checked";}?>>
		<label for="privacy_input_3">Avalik (kõik näevad)</label>
		<br>
	    <input type="submit" name="photo_submit" id="photo_submit" value="Lae pilt üles.">
		<span id="notice">Vali pilt!</span>
	</form>
	<span><?php echo $photo_upload_notice; ?></span>
</body>
</html>