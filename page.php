<?php
	$author_name = "Andrus Peegel";
	$full_time_now = date("d.m.Y H:i:s");
	$weekday_now = date("N");
	$hour_now = date("H");
	$day_category = "Lihtsalt päev";
	$hour_category = "Lihtsalt tund";
	//echo $weekday_now;
	// võrdub == suurem/väiksem ... < > <= >= pole võrdne (excelis <>) !=
	if($weekday_now <= 5){
		$day_category = "koolipäev";
		if($hour_now <8 or $hour_now >= 23){
			$hour_category = "uneaeg";
	}	elseif($hour_now >= 8 and $hour_now <=18){
			$hour_category = "tundide aeg";
	}	else{
			$hour_category = "vaba aeg";
	}
	} else {
		$day_category = "puhkepäev";
		if($hour_now < 10 or $hour_now >= 00){
			$hour_category = "uneaeg";
		} elseif($hour_now >= 10 and $hour_now <= 17){
			$hour_category = "keset päeva, peaksid midagi tegema, et aega viidata";
		} else{
			$hour_category = "täielik vaba aeg";
		}
	}
	$weekday_names_et = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
	
	//Juhusliku foto lisamine
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
	//img src="kataloog/fail" alt="Tallinna Ülikool">
	$photo_html = '<img src="' .$photo_dir .$photo_files[$photo_num] .'" alt="Talinna Ülikool" width="400">';	
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
	<img src="tlu.jpg" alt="Tallinna Ülikooli Mare hoone" width="600">
	<img src="tlu2.jpg" alt="Tallinna Ülikooli Astra hoone" width="400">
	<p>Lehe avamise hetk: <?php echo $weekday_names_et[$weekday_now - 1] .", " .$full_time_now .", " .$day_category; ?>.</p>
	<?php echo $photo_html; ?>
	<p>Sellel momendil on <?php echo $hour_category; ?> üliõpilastel.</p>
</body>
</html>