<?php
	require_once("../../config.php");
	require_once("fnc_films.php");
	//echo $server_host;
	$author_name = "Andrus Peegel";	
	
	//Valiku meelespidamine
	$title_placeholder = null;
	$year_placeholder = null;
	$duration_placeholder = null;
	$genre_placeholder = null;
	$studio_placeholder = null;
	$director_placeholder = null;
	
	//errorid
	$title_error = null;
	$year_error = null;
	$duration_error = null;
	$genre_error = null;
	$studio_error = null;
	$director_error = null;
	
	$film_store_notice = null;
	
	
	//if(isset($_POST["film_submit"])){
	//	if(!empty($_POST["title_input"]) and !empty($_POST["genre_input"]) and !empty($_POST["studio_input"]) and !empty($_POST["director_input"]) ){
	//		$film_store_notice = store_film($_POST["title_input"], $_POST["year_input"], $_POST["duration_input"], $_POST["genre_input"], $_POST["studio_input"], $_POST["director_input"]);
	//	} else {
	//		$film_store_notice = "Osa andmeid on puudu.";
	//	}	
	//}

	if(isset($_POST["film_submit"])){
		if(isset($_POST["title_input"])){
			$title_placeholder = $_POST["title_input"];
				if((!empty($_POST["title_input"]))){
					$title_input = filter_var($_POST["title_input"], FILTER_SANITIZE_STRING);
					$title_placeholder = $_POST["title_input"];
				}
		}else{
		$title_error = "Vajab täitmist";
		}
		
		if(isset($_POST["year_input"])){
			$year_placeholder = $_POST["year_input"];
				if((!empty($_POST["year_input"]))){
					$year_input = strval(filter_var($_POST["year_input"], FILTER_SANITIZE_NUMBER_INT));
					$year_placeholder = $_POST["year_input"];
				}
		}else{
		$year_error = "Vajab täitmist";
		}
		
		if(isset($_POST["duration_input"])){
			$duration_placeholder = $_POST["duration_input"];
				if((!empty($_POST["duration_input"]))){
					$duration_input = strval(filter_var($_POST["duration_input"], FILTER_SANITIZE_NUMBER_INT));
					$duration_placeholder = $_POST["duration_input"];
				}
		}else{
		$duration_error = "Vajab täitmist";
		}
		
		if(isset($_POST["genre_input"])){
			$genre_placeholder = $_POST["genre_input"];
				if((!empty($_POST["genre_input"]))){
					$genre_input = filter_var($_POST["genre_input"], FILTER_SANITIZE_STRING);
					$genre_placeholder = $_POST["genre_input"];
				}
		}else{
		$genre_error = "Vajab täitmist";
		}
		
		if(isset($_POST["studio_input"])){
			$studio_placeholder = $_POST["studio_input"];
				if((!empty($_POST["studio_input"]))){
					$studio_input = filter_var($_POST["studio_input"], FILTER_SANITIZE_STRING);
					$studio_placeholder = $_POST["studio_input"];
				}
		}else{
		$studio_error = "Vajab täitmist";
		}
		
		if(isset($_POST["director_input"])){
			$director_placeholder = $_POST["director_input"];
				if((!empty($_POST["director_input"]))){
					$director_input = filter_var($_POST["director_input"], FILTER_SANITIZE_STRING);
					$director_placeholder = $_POST["director_input"];
				}
		}else{
		$director_error = "Vajab täitmist";
		}
			if(!empty($_POST["title_input"]) and !empty($_POST["genre_input"]) and !empty($_POST["studio_input"]) and !empty($_POST["director_input"]) ){
			$film_store_notice = store_film($_POST["title_input"], $_POST["year_input"], $_POST["duration_input"], $_POST["genre_input"], $_POST["studio_input"], $_POST["director_input"]);
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
	<h2>Eesti filmide lisamine</h2>
		<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="title_input">Filmi pealkiri</label>
        <input type="text" name="title_input" id="title_input" placeholder="filmi pealkiri" value="<?php echo htmlspecialchars($title_placeholder); ?>"/>
		<a><?php echo $title_error; ?></a>
        <br>
        <label for="year_input">Valmimisaasta</label>
        <input type="number" name="year_input" id="year_input" min="1912" value="<?php echo date("Y"); ?>" value="<?php echo htmlspecialchars($year_placeholder); ?>"/>
		<label><?php echo $year_error; ?><label>
        <br>
        <label for="duration_input">Kestus</label>
        <input type="number" name="duration_input" id="duration_input" min="1" value="60" max="600" value="<?php echo htmlspecialchars($duration_placeholder); ?>"/>
		<label><?php echo $duration_error; ?><label>
        <br>
        <label for="genre_input">Filmi žanr</label>
        <input type="text" name="genre_input" id="genre_input" placeholder="žanr" value="<?php echo htmlspecialchars($genre_placeholder); ?>"/>
		<label><?php echo $genre_error; ?><label>
        <br>
        <label for="studio_input">Filmi tootja</label>
        <input type="text" name="studio_input" id="studio_input" placeholder="filmi tootja" value="<?php echo htmlspecialchars($studio_placeholder); ?>"/>
		<label><?php echo $studio_error; ?><label>
        <br>
        <label for="director_input">Filmi režissöör</label>
        <input type="text" name="director_input" id="director_input" placeholder="filmi režissöör" value="<?php echo htmlspecialchars($director_placeholder); ?>"/>
		<label><?php echo $director_error; ?><label>
        <br>
        <input type="submit" name="film_submit" value="Salvesta">
    </form>
    <span><?php echo $film_store_notice; ?></span>
	
</body>
</html>