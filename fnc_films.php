<?php
	$database = "if21_and_pee";
	
	function read_all_films(){
	//Loon andmebaasiühenduse: server, kasutaja, parool, andmebaas
	$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
	//Määrame korrektse kooditabeli
	$conn->set_charset("utf8");
	//Valmistan ette SQL käsu
	//SELECT * FROM film 
	$stmt = $conn->prepare("SELECT * FROM film");
	echo $conn->error;
	//Seome tulemused muutujatega
	$stmt->bind_result($title_from_db, $year_from_db, $duration_from_db, $genre_from_db, $studio_from_db, $director_from_db);
	//Anname käsu täitmiseks
	$film_html = null;
	$stmt->execute();
	//Võtan andmed
	while($stmt->fetch()){
	//Paneme andmed meile sobivasse vormi
	//<h3>filmipealkiri</h3>
	//<ul>
	//<li>valmimisaasta</li>
	//<li>kestvus</li>
	//.....
	//</ul>
		$film_html .= "\n <h3>" .$title_from_db ."</h3> \n <ul> \n";
		$film_html .= "<li>Valmimisaasta: " .$year_from_db ."</li> \n";
		$film_html .= "<li>Kestus minutites: " .$duration_from_db ."</li> \n";
		$film_html .= "<li>Zanr: " .$genre_from_db ."</li> \n";
		$film_html .= "<li>Tootja: " .$studio_from_db ."</li> \n";
		$film_html .= "<li>Lavastaja: " .$director_from_db ."</li> \n";
		$film_html .= "</ul> \n";
		}
	//sulgeme käsu
	$stmt->close();
	//sulgeme andmebaasiühenduse
	$conn->close();
	return $film_html;
	}
	
	function store_film($title_input, $year_input, $duration_input, $genre_input, $studio_input, $director_input){
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		//INSERT INTO film (pealkiri, aasta, kestus, zanr, tootja, lavastaja) VALUES ("Suvi", 1976, 80, "Komöödia", "Tallinn Film", "Arvo Kruusement")
		$stmt = $conn->prepare("INSERT INTO film (pealkiri, aasta, kestus, zanr, tootja, lavastaja) VALUES (?,?,?,?,?,?)");
		echo $conn->error;
		//Seome SQL käsu päris andmetega
		//Andmetüübid: i - integer, d - decimal, s - string
		$stmt->bind_param("siisss", $title_input, $year_input, $duration_input, $genre_input, $studio_input, $director_input);
		$success = null;
		if($stmt->execute()){
			$success = "Salvestamine õnnestus!";
		}	else {
			$success = "Salvestamisel tekkis viga: " .$stmt->error;
		}	
		$stmt->close();
		$conn->close();
		return $success;
	}