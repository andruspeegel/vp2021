<?php
	require_once("use_session.php");
	
	require_once("../../config.php");
	require_once("fnc_news.php");
	
	//Testin classi
	/* require_once("classes/Test.class.php");
	$test_object = new Test(6);
	echo " Teadaolev, avalik number: " .$test_object->known_number .".";
	$test_object->reveal();
	unset($test_object); */
	
	//küpsised
	//
	setcookie("vpvisitor", $_SESSION["first_name"] ." " .$_SESSION["last_name"], time() + (86400 * 8), "/~andpee/vp2021/", "greeny.cs.tlu.ee", isset($_SERVER["HTTPS"]), true);
	//var_dump($_COOKIE);
	$last_visitor = "Pole teada";
	if(isset($_COOKIE["vpvisitor"]) and !empty($_COOKIE["vpvisitor"])){
		$last_visitor = $_COOKIE["vpvisitor"];
	}
	//cookie kustutamine, pannakse aegumine minevikus
	//time() - 3600
	
	   require("page_header.php");
?>
		<h1><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrameerimine</h1>
		<p>See leht on loodud õppetöö raames ja ei sisalda tõsiseltvõetavat sisu.</p>
		<p>Õppetöö toimub <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
		<hr>
			<?php echo "<p>Eelmine külastaja " .$last_visitor ."</p> \n"; ?>
		<hr>
		<ul>
		<p><a href="?logout=1">Logi välja</a></p>
			<p><a href="list_films.php">Filmide nimekirja vaatamine</a> versioon 1</p>
			<p><a href="add_films.php">Filmide lisamine andmebaasi</a> versioon 1</p>
			<p><a href="user_profile.php">Kasutajaprofiil</a></p>
			<p><a href="movie_relations.php">Filmi, isiku ja muude seoste loomine.</a></p>
			<p><a href="movie_info.php">Filmi info</a></p>
			<p><a href="gallery_photo_upload.php">Galeeripiltide üleslaadimine</a></p>
			<p><a href="gallery_public.php">Sisseloginud kasutajatele nähtavate fotode galerii</a></p>
			<p><a href="gallery_own.php">Minu fotode galerii</a></p>
			<p><a href="add_news.php">Uudiste lisamine</a></p>
			<p><a href="peo_regis.php">Peole registreerumine</a></p>
			<p><a href="peo_cancel.php">Peole registreerumise tühistamine</a></p>
			<p><a href="peo_admin.php"> Peo administraatori leht</a></p>
    </ul>
	<br>
	<h2>Uudised</h2>
	<?php 
		echo latest_news(5);
	?>
</body>
</html>