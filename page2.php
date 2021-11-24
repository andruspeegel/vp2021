<?php
	//session_start();
	require_once("classes/SessionManager.class.php");
	SessionManager::sessionStart("vp", 0, "/~andpee/vp2021/", "greeny.cs.tlu.ee");
	
	require_once("fnc_user.php");
	require_once("../../config.php");
	require_once("fnc_gallery.php");
	$author_name = "Andrus Peegel";	
	//Kontroll, kas POST info jõuab kuhugi:
	//var_dump($_POST);
	//Kontroll, kas klikiti submit
	$todays_adjective_html = null;
	$todays_adjective_error = null;
	$todays_adjective = null;
	if(isset($_POST["adjective_submit"])){
		//echo "Klikiti!";
		if(!empty($_POST["todays_adjective_input"])){
			$todays_adjective_html = "<p> Tänane päev on " .$_POST["todays_adjective_input"] .".</p>";
			$todays_adjective = $_POST["todays_adjective_input"];
		} else {
			$todays_adjective_error = "Palun sisesta tänase päeva kohta sobiv omadussõna.";
		}
	}
	//Juhusliku foto lisamine
	$photo_num = null;
	$photo_dir = "photos/";
	//Loen kataloogi sisu
	$all_files = scandir($photo_dir);
	$all_real_files = array_slice($all_files, 2);
	
	//Sõelume välja päris pildid
	$photo_files = [];
	$allowed_photo_types = ["image/jpeg", "image/png"];
	foreach($all_real_files as $file_name){
		$file_info = getimagesize($photo_dir .$file_name);
		if(isset($file_info["mime"])){
			if(in_array($file_info["mime"], $allowed_photo_types)){
				array_push($photo_files, $file_name);
			}//if(in_array)
		}//if(isset)
	}//foreach	
	
	//var_dump($all_real_files);
	$file_count = count($photo_files);
	//Loosin juhusliku arvu (minimaalne peab olema 0 ja max count -1)
	$photo_num = mt_rand(0, $file_count - 1);
	
	if(isset($_POST["photo_select_submit"])){
		$photo_num = $_POST["photo_select"];
	}
	
	$photo_file_html = null;
	$photo_file = $photo_files[$photo_num];
	$photo_html = '<img src="' .$photo_dir .$photo_file .'" alt="Talinna Ülikool" width="400">';
	
	$photo_file_html = "\n <p>".$photo_file ."</p> \n";
	
	$photo_list_html = "<ul> \n";
	for($i = 0; $i < $file_count; $i ++){
		$photo_list_html .= "<li>" .$photo_files[$i] ."</li> \n";
	}
	$photo_list_html .= "</ul>";
	
	$photo_file_html = null;
	$photo_file = $photo_files[$photo_num];
	$photo_html = '<img src="' .$photo_dir .$photo_file .'" alt="Tallinna Ülikool">';
	
	$photo_file_html = "\n <p>".$photo_file ."</p> \n";
	
	$photo_select_html = '<select name="photo_select">' ."\n";
	for($i = 0; $i < $file_count; $i ++){
		$photo_select_html .= "\t \t" .'<option value="' .$i .'"';
		if($i == $photo_num){
			$photo_select_html .= " selected";
		}
		$photo_select_html .= ">" .$photo_files[$i] ."</option> \n";
	}
	$photo_select_html .= "</select> \n";
	
	$email = null;
	$email_error = null;
	$password_error = null;
	$notice = null;
	//sisselogimine
	$notice = null;
	if(isset($_POST["login_submit"])){
		if(isset($_POST["email_input"]) and !empty($_POST["email_input"])){
			$email = filter_var($_POST["email_input"], FILTER_VALIDATE_EMAIL);
			if(strlen($email) < 5){
				$email_error = "Palun sisesta kasutajatunnus (e-post)!";
			}
		} else {
			$email_error = "Palun sisesta kasutajatunnus (e-post)!";
		}
		if(isset($_POST["password_input"]) and !empty($_POST["password_input"])){
			if(strlen($_POST["password_input"]) < 8){
				$password_error = "Sisestatud salasõna on liiga lühike!";
			}
		} else {
			$password_error = "Palun sisesta salasõna!";
		}
		if(empty($email_error) and empty($password_error)){
			$notice = sign_in($email, $_POST["password_input"]);
		} else {
			$notice = $email_error ." " .$password_error;
		}
    }
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title><?php echo $author_name; ?>, veebiprogrameerimine</title>
</head>
<body>
	<h1><?php echo $author_name; ?>, veebiprogrameerimine</h1>
	<p>See leht on loodud õppetöö raames ja ei sisalda tõsiseltvõetavat sisu.</p>
	<p>Õppetöö toimub <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<input type="email" name="email_input" placeholder="Kasutajatunnus ehk e-post" value="<?php echo $email; ?>">
		<input type="password" name="password_input" placeholder="Parool">
		<input type="submit" name="login_submit" value="Logi sisse">
				<span><?php echo $notice; ?></span>
	</form>
	<p> Loo <a href="add_user.php">kasutajakonto</a></p>
	<hr>
	<form method="POST">
		<input type="text" placeholder="omadussõna tänase kohta" name="todays_adjective_input" value="<?php echo $todays_adjective; ?>">
		<input type="submit" name="adjective_submit" value="Saada">
		<span><?php echo $todays_adjective_error; ?></span>
	</form>
	<?php echo $todays_adjective_html; ?>
	<hr>
	<?php echo show_latest_public_photo(); ?>
	<hr>
	<form method="POST">
		<?php echo $photo_select_html; ?>
		<input type="submit" name="photo_select_submit" value="Näita fotot">
	</form>
	<hr>
	<?php 
		echo $photo_html; 
		echo $photo_file_html;
		echo "<hr> \n";
		echo "<hr> \n";
		echo $photo_list_html; 
	?>
</body>
</html>