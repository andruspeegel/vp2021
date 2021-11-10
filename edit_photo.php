<?php
	session_start();
	
	if(!isset($_SESSION["user_id"])){
        header("Location: page2.php");
	}
	
	 if(isset($_GET["logout"])){
        session_destroy();
        header("Location: page2.php");
    }


	require_once("../../config.php");
	require_once("fnc_general.php");
	require_once("fnc_gallery.php");
	
	$photo_data = [];
	$photo_data_update_notice = null;
	
	if(isset($_POST["photo_data_submit"])){
		$privacy = 1;
		if(isset($_POST["privacy_input"])){
			if(!empty(filter_var($_POST["privacy_input"], FILTER_VALIDATE_INT))){
				$privacy = filter_var($_POST["privacy_input"], FILTER_VALIDATE_INT);
			}
		}
		$photo_data_update_notice = photo_data_update($_POST["photo_input"], test_input(filter_var($_POST["alt_input"], FILTER_SANITIZE_STRING)), $privacy);
	}
	
	if(isset($_POST["delete_submit"])){
		$photo_data_update_notice = delete_photo($_POST["photo_input"]);
		if($photo_data_update_notice == "Korras"){
			header("Location: gallery_own.php");
		}
	}
	
	if(isset($_GET["photo"]) and !empty($_GET["photo"])){
		$photo_data = read_own_photo($_GET["photo"]);
    } else {
        header("Location: home.php");
    }
	
	require("page_header.php");
?>

	<h1><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrameerimine</h1>
	<p>See leht on loodud õppetöö raames ja ei sisalda tõsiseltvõetavat sisu.</p>
	<p>Õppetöö toimub <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
	<ul>
        <p><a href="?logout=1">Logi välja</a></p>
		<p><a href="gallery_own.php">Enda pildigallerii</a></p>
    </ul>
		<hr>
	<h2>Enda fotode galerii</h2>
	<?php
	if($photo_data[0] == true){
			echo '<img src="' .$photo_upload_normal_dir .$photo_data[1] .'" alt="';
            if(empty($photo_data[2])){
                echo "Üleslaetud foto";
            } else {
                echo $photo_data[2];
            }
            echo '">' ."\n";
			echo '<form method="POST" action="' .htmlspecialchars($_SERVER["PHP_SELF"]) ."?photo=" .$_GET["photo"] .'" enctype="multipart/form-data">' ."\n";
			echo '<input type="hidden" name="photo_input" value="' .$_GET["photo"] .'">' ."\n";
			echo '<label for="alt_input">Alternatiivtekst (alt): </label>' . "\n";
			echo '<input type="text" name="alt_input" id="alt_input" placeholder="alternatiivtekst" value="' .$photo_data[2] .'">' ."\n";
			echo "<br> \n";
			echo '<input type="radio" name="privacy_input" id="privacy_input_1" value="1"';
			if($photo_data[3] == 1){
				echo " checked";
			}
			echo "> \n";
			echo '<label for="privacy_input_1">Privaatne (ainult mina näen)</label>';
			echo "<br> \n";
			echo '<input type="radio" name="privacy_input" id="privacy_input_2" value="2"';
			if($photo_data[3] == 2){
				echo " checked";
			}
			echo "> \n";
			echo '<label for="privacy_input_2">Sisseloginud kasutajatele</label>';
			echo "<br> \n";
			echo '<input type="radio" name="privacy_input" id="privacy_input_3" value="3"';
			if($photo_data[3] == 3){
				echo " checked";
			}
			echo "> \n";
			echo '<label for="privacy_input_3">Avalik (kõik näevad)</label>';
			echo "<br> \n";
			echo '<input type="submit" name="photo_data_submit" value="Salvesta andmed">' ."\n";
			echo "</form> \n";
			echo "<br> \n";
			echo "<hr>";
			echo '<form method="POST" action="' .htmlspecialchars($_SERVER["PHP_SELF"])."?photo=" .$_GET["photo"] .'" >' ."\n";
			echo '<input type="hidden" name="photo_input" value="' .$_GET["photo"] .'">' ."\n";
			echo '<input type="submit" name="delete_submit" value="Kustuta foto">' ."\n";
			echo "</form> \n";			
		} else {
			echo "<p>Valitud fotoga seotud andmeid ei saa muuta!</p>";
		}
	?>
	<p><?php echo $photo_data_update_notice; ?></p>
</body>
</html>