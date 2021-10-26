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
	
	$person_movie_relation_notice = null;
	$person_selected = null;
	$movie_selected = null;
	$position_selected = null;
	$role = null;
	
	if(isset($_POST["person_movie_relation_submit"])){
		if(isset($_POST["person_select"]) and !empty($_POST["person_select"])){
			$person_selected = filter_var($_POST["person_select"], FILTER_VALIDATE_INT);
		}
		if(empty($person_selected)){
			$person_movie_relation_notice .= "Isik on valimata. ";
		}
		if(isset($_POST["movie_select"]) and !empty($_POST["movie_select"])){
			$movie_selected = filter_var($_POST["movie_select"], FILTER_VALIDATE_INT);
		}
		if(empty($movie_selected)){
			$person_movie_relation_notice .= "Film on valimata. ";
		}
		if(isset($_POST["position_select"]) and !empty($_POST["position_select"])){
			$position_selected = filter_var($_POST["position_select"], FILTER_VALIDATE_INT);
		}
		if(empty($position_selected)){
			$person_movie_relation_notice .= "Amet on valimata. ";
		}

		if($position_selected == 1){
			if(isset($_POST["role_input"]) and !empty($_POST["role_input"])){
				$role = test_input(filter_var($_POST["role_input"], FILTER_SANITIZE_STRING));
			}
			if(empty($role)){
				$person_movie_relation_notice .= "Roll on kirjutamata! ";
			}	
		}

		if(empty($person_movie_relation_notice)){
			$person_movie_relation_notice = store_person_movie_relation($person_selected, $movie_selected, $position_selected, $role);
		}	
	}
	
	$person_selected_for_photo = null;
	$photo_upload_notice = null;
	$person_photo_dir = "person_photo/";
	$file_type = null;
	$file_name = null;
	
	if(isset($_POST["person_photo_submit"])){
		if(isset($_POST["person_select"]) and !empty($_POST["person_select"])){
			$person_selected_for_photo = filter_var($_POST["person_select"], FILTER_VALIDATE_INT);
		}
		if(empty($person_selected_for_photo)){
			$photo_upload_notice .= "Isik on valimata. ";
		}
		
		if(isset($_FILES["photo_input"]["tmp_name"]) and !empty($_FILES["photo_input"]["tmp_name"])){
			$image_check = getimagesize($_FILES["photo_input"]["tmp_name"]);
			if($image_check !== false){
				if($image_check["mime"] == "image/jpeg"){
					$file_type = "jpg";
				}	
				if($image_check["mime"] == "image/png"){
					$file_type = "png";
				}	
				if($image_check["mime"] == "image/gif"){
					$file_type = "gif";
				}	
				
				//teeme failinime
				//genereerin ajatempli
				$time_stamp = microtime(1) * 10000;
				$file_name = "person_" .$_POST["person_select"] ."_" .$time_stamp ."." .$file_type;
				
				//move_uploaded_file($_FILES["photo_input"]["tmp_name"], $person_photo_dir .$_FILES["photo_input"]["name"]);

			} else {
				$photo_upload_notice .= "Pilt on valimata.";
			}
		}
		if(empty($photo_upload_notice)){
			if(move_uploaded_file($_FILES["photo_input"]["tmp_name"], $person_photo_dir .$file_name)){
				//pildi info andmebaasi
				$photo_upload_notice = store_person_photo($file_name, $person_selected_for_photo);
			}	
		}	
		
	}	
	
	$person_firstname = null;
    $person_surname = null;
	$person_birth_month = null;
    $person_birth_year = null;
    $person_birth_day = null;
    $person_birth_date = null;
    $month_names_et = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni","juuli", "august", "september", "oktoober", "november", "detsember"];
	
	$person_firstname_error = null;
    $person_surname_error = null;
    $person_birth_month_error = null;
    $person_birth_year_error = null;
    $person_birth_day_error = null;
    $person_birth_date_error = null;
	
	if($_SERVER["REQUEST_METHOD"] === "POST"){
        if(isset($_POST["person_movie_input"])){
			if(isset($_POST["person_firstname_input"]) and !empty($_POST["person_firstname_input"])){
                $person_firstname = test_input(filter_var($_POST["person_firstname_input"], FILTER_SANITIZE_STRING));
                if(strlen($person_firstname) < 1){
                    $person_firstname_error = "Palun sisesta eesnimi!";
                }
            } else {
                $person_firstname_error = "Palun sisesta eesnimi!";
            }
			
			if(isset($_POST["person_surname_input"]) and !empty($_POST["person_surname_input"])){
                $person_surname = test_input(filter_var($_POST["person_surname_input"], FILTER_SANITIZE_STRING));
                if(strlen($person_surname) < 1){
                    $person_surname_error = "Palun sisesta perekonnanimi!";
                }
            } else {
                $person_surname_error = "Palun sisesta perekonnanimi!";
            }
			
			if(isset($_POST["person_birth_day_input"]) and !empty($_POST["person_birth_day_input"])){
                $person_birth_day = filter_var($_POST["person_birth_day_input"], FILTER_VALIDATE_INT);
                if($person_birth_day < 1 or $person_birth_day > 31){
                    $person_birth_day_error = "Palun vali sünni päev!";
                }
            } else {
                $person_birth_day_error = "Palun vali sünni päev!";
            }
            
            if(isset($_POST["person_birth_month_input"]) and !empty($_POST["person_birth_month_input"])){
                $person_birth_month = filter_var($_POST["person_birth_month_input"], FILTER_VALIDATE_INT);
                if($person_birth_month < 1 or $person_birth_month > 12){
                    $person_birth_month_error = "Palun vali sünni kuu!";
                }
            } else {
                $person_birth_month_error = "Palun vali sünni kuu!";
            }
            
            if(isset($_POST["person_birth_year_input"]) and !empty($_POST["person_birth_year_input"])){
                $person_birth_year = filter_var($_POST["person_birth_year_input"], FILTER_VALIDATE_INT);
                if($person_birth_year < date("Y") - 110 or $person_birth_year > date("Y") - 13){
                    $person_birth_year_error = "Palun vali sünni aasta!";
                }
            } else {
                $person_birth_year_error = "Palun vali sünni aasta!";
            }
            
            //valideerime kuupäeva ja paneme selle kokku
            if(empty($person_birth_day_error) and empty($person_birth_month_error) and empty($person_birth_year_error)){
                if(checkdate($person_birth_month, $person_birth_day, $person_birth_year)){
                    //moodustame kuupäeva
                    $temp_date = new DateTime($person_birth_year ."-" .$person_birth_month ."-" .$person_birth_day);
                    $person_birth_date = $temp_date->format("Y-m-d"); 
                } else {
                    $person_birth_date_error = "Valitud kuupäev on vigane!";
                }
            }
			
			if(empty($person_firstname_error) and empty($person_surname_error) and empty($person_birth_month_error) and empty($person_birth_year_error) and empty($person_birth_day_error) and empty($person_birth_date_error)){
				$notice= store_new_person($person_firstname, $person_surname, $person_birth_date);
			}	
		}
	}		
	
	require("page_header.php");
?>

	<h1><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrameerimine</h1>
	<p>See leht on loodud õppetöö raames ja ei sisalda tõsiseltvõetavat sisu.</p>
	<p>Õppetöö toimub <a href="https://www.tlu.ee/dt">Tallinna Ülikooli Digitehnoloogiate instituudis</a>.</p>
	<hr>
	<ul>
        <p><a href="?logout=1">Logi välja</a></p>
		<p><a href="home.php">Avaleht</a></p>
		<p><a href="list_films.php">Filmide nimekirja vaatamine</a> versioon 1</p>
    </ul>
		<hr>
	<h2>Filmi info seoste loomine</h2>
	<h3> Filmitegelase lisamine </h3>	
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
	<label for ="person_firstname_input"> Isiku eesnimi: </label><br>
	<input type="text" name="person_firstname_input" id="person_firstname_input" placeholder="Eesnimi" value="<?php echo $person_firstname; ?>"><span><?php echo $person_firstname_error; ?></span><br>
	<br>
	<label for="person_surname_input">Perekonnanimi:</label><br>
	<input name="person_surname_input" id="person_surname_input" type="text" placeholder="Perekonnanimi" value="<?php echo $person_surname; ?>"><span><?php echo $person_surname_error; ?></span><br>
	<br>
	<label for="person_birth_day_input">Isiku sünnikuupäev: </label>
	  <?php
	    //sünnikuupäev
	    echo '<select name="person_birth_day_input" id="person_birth_day_input">' ."\n";
			echo "\t \t" .'<option value="" selected disabled>päev</option>' ."\n";
			for($i = 1; $i < 32; $i ++){
				echo "\t \t" .'<option value="' .$i .'"';
				if($i == $person_birth_day){
					echo " selected";
				}
				echo ">" .$i ."</option> \n";
			}
			echo "\t </select> \n";
	  ?>
	  	  <label for="person_birth_month_input">Isiku sünnikuu: </label>
	  <?php
	    echo '<select name="person_birth_month_input" id="person_birth_month_input">' ."\n";
			echo "\t \t" .'<option value="" selected disabled>kuu</option>' ."\n";
			for ($i = 1; $i < 13; $i ++){
				echo "\t \t" .'<option value="' .$i .'"';
				if ($i == $person_birth_month){
					echo " selected ";
				}
				echo ">" .$month_names_et[$i - 1] ."</option> \n";
			}
			echo "</select> \n";
	  ?>
	  <label for="person_birth_year_input">Isiku sünniaasta: </label>
	  <?php
	    echo '<select name="person_birth_year_input" id="person_birth_year_input">' ."\n";
			echo "\t \t" .'<option value="" selected disabled>aasta</option>' ."\n";
			for ($i = date("Y") - 13; $i >= date("Y") - 110; $i --){
				echo "\t \t" .'<option value="' .$i .'"';
				if ($i == $person_birth_year){
					echo " selected ";
				}
				echo ">" .$i ."</option> \n";
			}
			echo "</select> \n";
	  ?>
	  
	 <span><?php echo $person_birth_date_error ." " .$person_birth_day_error ." " .$person_birth_month_error ." " .$person_birth_year_error; ?></span>


	<br>
	<input type="submit" name="person_movie_input" value="Salvesta">
	</form>	
	<h3> Film, isik ja amet</h3>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<label for="movie_select">Film: </label>
		<select name="movie_select" id="movie_select">
			<option value="" selected disabled>Film</option>
			<?php echo read_all_movie_for_select($movie_selected); ?>
		</select>
		
        <label for="person_select">Isik: </label>
		<select name="person_select" id="person_select">
			<option value="" selected disabled>Isik</option>
			<?php echo read_all_person_for_select($person_selected); ?>
		</select>
				
		<label for="position_select">Amet: </label>
		<select name="position_select" id="position_select">
			<option value="" selected disabled>Amet</option>
			<?php echo read_all_position_for_select($position_selected); ?>
		</select>
		
		<label for="role_input">Roll: </label>
		<input type="text" name="role_input" id="role_input" placeholder="Roll" value="<?php echo $role; ?>">
		
        <input type="submit" name="person_movie_relation_submit" value="Salvesta">
    </form>
    <span><?php echo $person_movie_relation_notice; ?></span>
	<hr>
	<h3>Filmitegelase foto lisamine</h3>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
		<label for="person_select_for_photo">Isik: </label>
		<select name="person_select" id="person_select_for_photo">
			<option value="" selected disabled>Isik</option>
			<?php echo read_all_person_for_select($person_selected_for_photo); ?>
		</select>
		<input type="file" name="photo_input" id="photo_input">
	    <input type="submit" name="person_photo_submit" value="Lae pilt üles.">
	</form>
	<span><?php echo $photo_upload_notice; ?></span>
</body>
</html>