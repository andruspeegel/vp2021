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
	require_once("fnc_movie.php");
	require_once("fnc_general.php");
	
	require("page_header.php");

?>

	<h1><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrammeerimine</h1>
	<p>See leht on valminud õppetöö raames ja ei sisalda mingisugust tõsiseltvõetavat sisu!</p>
	<p>Õppetöö toimus <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
    <ul>
        <li><a href="?logout=1">Logi välja</a></li>
		<li><a href="home.php">Avaleht</a></li>
    </ul>
	<hr>
    <h2>Filmi info</h2>
    <?php echo list_movie_info(); ?>
</body>
</html>