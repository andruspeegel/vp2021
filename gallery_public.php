<?php
	require_once("use_session.php");

	require_once("../../config.php");
	require_once("fnc_general.php");
	require_once("fnc_gallery.php");
	$privacy = 2;
	
	//greeny.cs.tlu.ee/~andpee/vp2021/gallery_public.php?page=2
	$page = 1;
	$limit = 2;
	$photo_count = count_public_photos($privacy);
	//kontrollime, mis lehel oleme ja kas selline leht on võimalik.
	if(!isset($_GET["page"]) or $_GET["page"] < 1){
		$page = 1;
	} elseif(round($_GET["page"] -1) * $limit >= $photo_count){
		$page = ceil($photo_count / $limit);
	} else {
		$page = $_GET["page"];
	}
	
	$to_head = '<link rel="stylesheet" type="text/css" href="style/gallery.css">' . "\n";
	$to_head .= '<link rel="stylesheet" type="text/css" href="style/modal.css">' . "\n";
	$to_head .= '<script src="javascript/modal.js" defer></script>' ."\n";
	require("page_header.php");
?>
	<!--Modaalaken galeriipiltide jaoks-->
	<div id="modalarea" class="modalarea">
		<span id="modalclose" class="modalclose">&times;</span>
		<div class="modalhorizontal">
			<div class="modalvertical">
				<p id="modalcaption"></p>
				<img id="modalimage" src="pics/empty.png" alt="Galeriipilt">
				<br>
				<input id="rate1" name="rating" type="radio" value="1"><label for="rate1">1</label>
				<input id="rate2" name="rating" type="radio" value="2"><label for="rate2">2</label>
				<input id="rate3" name="rating" type="radio" value="3"><label for="rate3">3</label>
				<input id="rate4" name="rating" type="radio" value="4"><label for="rate4">4</label>
				<input id="rate5" name="rating" type="radio" value="5"><label for="rate5">5</label>
				<button id="storeRating" type="button">Salvesta hinne</button>
				<br>
				<p id="avgRating"></p>
			</div>
		</div>
	</div>
	
	<h1><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrameerimine</h1>
	<p>See leht on loodud õppetöö raames ja ei sisalda tõsiseltvõetavat sisu.</p>
	<p>Õppetöö toimub <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
	<ul>
        <p><a href="?logout=1">Logi välja</a></p>
		<p><a href="home.php">Avaleht</a></p>
    </ul>
		<hr>
	<h2>Avalike fotode galerii</h2>
	<div id="gallery" class="gallery">
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
		<?php echo read_public_photo_thumbs($privacy, $page, $limit); ?>
	</div>
</body>
</html>