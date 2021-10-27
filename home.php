<?php
	session_start();
	//$author_name = "Andrus Peegel";	
	if(!isset($_SESSION["user_id"])){
        header("Location: page2.php");
    }
	if(isset($_GET["logout"])){
        session_destroy();
        header("Location: page2.php");
    }
	
	   require("page_header.php");
?>
		<h1><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrameerimine</h1>
		<p>See leht on loodud õppetöö raames ja ei sisalda tõsiseltvõetavat sisu.</p>
		<p>Õppetöö toimub <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
		<hr>
	<ul>
		<p><a href="?logout=1">Logi välja</a></p>
			<p><a href="list_films.php">Filmide nimekirja vaatamine</a> versioon 1</p>
			<p><a href="add_films.php">Filmide lisamine andmebaasi</a> versioon 1</p>
			<p><a href="user_profile.php">Kasutajaprofiil</a></p>
			<p><a href="movie_relations.php">Filmi, isiku ja muude seoste loomine.</a></p>
			<p><a href="movie_info.php">Filmi info</a></p>
			<p><a href="gallery_photo_upload.php">Galeeripiltide üleslaadimine</a></p>

    </ul>
	
</body>
</html>