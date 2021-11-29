<?php
	require_once("use_session.php");


	require_once("../../config.php");
	require_once("fnc_photoupload.php");
	require_once("fnc_general.php");
	require_once("classes/Photoupload.class.php");
	require_once("fnc_news.php");
	
	$news_notice = null;
	$news_error = null;
	$news_title = null;
	$news = null;
	
	$expire = new DateTime("now");
	$expire->add(new DateInterval("P7D"));
	$expire_date = date_format($expire, "Y-m-d");
	$photo_file = null;
	
	$photo_file_name_prefix = "vp_";
	$photo_file_size_limit = 1024 * 1024;
	$photo_width_limit = 600;
	$photo_height_limit = 400;
	$thumbnail_height = $thumbnail_width = 100;
	$watermark_file = "pics/vp_logo_color_w100_overlay.png";
	$allowed_photo_types = ["image/jpeg", "image/png", "image/gif"];
	
	if(isset($_POST["news_submit"])){
		//kui uudisele on valitud foto, siis see salvestage esimesena ja lisage esimesena ka andmetabelisse (uudisefotodel eraldi andmetabel).
		//siis lisate uudise koos uudise pealkirja, aegumise ja foto id-ga eraldi andmetabelisse.
		//Andmebaasi salvestamisel saab pärast execute() käsku just salvestatud kirje id kätte:
		//$muutuja = $conn->insert_id;
		//uudise sisu peaks läbima funktsiooni test_input(uudis) (fnc_general.php).
		//Seal on htmlspecialchars() funktsioon, mis teinendab html märgised (nt: < ---> &lt;)
		//tagasi saab htmlspecialchars_decode(uudis_andmebaasist)
		
		//aegumistähtaja saate date inputist.
		//uudiste näitamisel võrdlete SQL lauses andmebaasis olevat aegumiskuupäeva tänasega.
		//$today = date("Y-m-d");
		//SQL-is	WHERE expire >= ? ($today)
		
		if(empty($_POST["title_input"])){
			$news_error = "Puudub uudise pealkiri.";
		} else {
			$news_title = test_input($_POST["title_input"]);
		}
		if(empty($_POST["news_input"])){
			$news_error .= " Puudub uudise sisu.";
		} else {
			$news = test_input($_POST["news_input"]);
		}
		if(!empty($_POST["expire_input"])){
			$expire_date = $_POST["expire_input"];
		} else {
			$news_error .= " Puudub uudise aegumistähtaeg";
		}
		if($expire_date < date("Y-m-d")){
			$news_error .= " Aegumistähtaeg ei ole sobiv (on minevikus).";
		}
		
		if(isset($_FILES["photo_input"]["tmp_name"]) and !empty($_FILES["photo_input"]["tmp_name"])){
			$photo_upload = new Photoupload($_FILES["photo_input"]);
			if(empty($photo_upload->error)){
				$photo_upload->check_allowed_type($allowed_photo_types);
				if(empty($photo_upload->error)){
					$photo_upload->check_size($photo_file_size_limit);
					if(empty($photo_upload->error) and empty($news_error)){
						$photo_upload->create_filename($photo_file_name_prefix);
						$photo_upload->resize_photo($photo_width_limit, $photo_height_limit);
						$news_notice = "Uudise pildi " .$photo_upload->save_image($photo_upload_news_dir .$photo_upload->file_name);
						$photo_file .= $photo_upload->file_name;
					}
				}
			}
			$news_error .= $photo_upload->error;
			unset($photo_upload);
		}
		if(empty($news_error)){
			$news_notice .= save_news($news_title, $news, $expire_date, $photo_file);
		}
    }		
	
	$to_head = '<script src="javascript/fileSizeCheck.js" defer></script>' ."\n";
	$to_head = '<script src="https://cdn.ckeditor.com/4.17.1/standard/ckeditor.js"></script>' ."\n";
	
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
	<h2>Uudise lisamine</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
		<label for="title_input">Uudise pealkiri</label>
		<input type="text" id="title_input" name="title_input" value="<?php echo $news_title; ?>">
		<br>
		<label for="news_input">Uudise sisu</label>
		<br>
		<textarea id="news_input" name="news_input"><?php echo htmlspecialchars_decode($news); ?></textarea>
		<script>CKEDITOR.replace('news_input');</script>
		<br>
		<label for="expire_input">Uudis aegub pärast:</label>
		<input type="date" id="expire_input" name="expire_input" value="<?php echo $expire_date; ?>">
		<br>
		<label for="photo_input">Vali pildifail</label>
		<input type="file" name="photo_input" id="photo_input">
		<br>
		
	    <input type="submit" name="news_submit" id="news_submit" value="Salvesta uudis."><span id="notice"><?php echo $news_error; ?></span>
	</form>
	<span><?php echo $news_notice; ?></span>
</body>
</html>