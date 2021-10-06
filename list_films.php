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
	require_once("fnc_films.php");
	//echo $server_host;
	//$author_name = "Andrus Peegel";	
	$film_html = null;
	$film_html = read_all_films();
	
	require("page_header.php");
?>
<body>
	<h1><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrameerimine</h1>
	<p>See leht on loodud õppetöö raames ja ei sisalda tõsiseltvõetavat sisu.</p>
	<p>Õppetöö toimub <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
	<ul>
        <p><a href="?logout=1">Logi välja</a></p>
		<p><a href="home.php">Avaleht</a></p>
		<p><a href="add_films.php">Filmide lisamine andmebaasi</a> versioon 1</p>
    </ul>
	<hr>
	<h2>Eesti filmid</h2>
	<?php echo $film_html; ?>
	
</body>
</html>