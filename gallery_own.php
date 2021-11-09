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
	$privacy = 2;
	
	//greeny.cs.tlu.ee/~andpee/vp2021/gallery_public.php?page=2
	$page = 1;
	$limit = 2;
	$photo_count = count_own_photos();
	//kontrollime, mis lehel oleme ja kas selline leht on võimalik.
	if(!isset($_GET["page"]) or $_GET["page"] < 1){
		$page = 1;
	} elseif(round($_GET["page"] -1) * $limit >= $photo_count){
		$page = ceil($photo_count / $limit);
	} else {
		$page = $_GET["page"];
	}
	
	$to_head = '<link rel="stylesheet" type="text/css" href="style/gallery.css">' . "\n";
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
	<h2>Enda fotode galerii</h2>
	<div class="gallery">
		<p>
			<?php
				// | <span><a href="?page=2">Järgmine leht</a></span>
				if($page > 1){
					echo '<span><a href="?page=' .($page - 1) .'">Eelmine leht</a></span>';
				} else {
					echo "<span>Eelmine leht</span>";
				}
				echo " | ";
				if($page * $limit < $photo_count){
					echo '<span><a href="?page=' .($page + 1) .'">Järgmine leht</a></span>';
				} else {
					echo "<span>Järgmine leht</span>";
				}
			?>
		</p>
		<?php echo read_own_photo_thumbs($page, $limit); ?>
	</div>
</body>
</html>