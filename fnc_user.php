<?php
	$database = "if21_and_pee";
	
	function store_new_user($firstname, $surname, $gender, $birth_date, $email, $password){
			$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
			$stmt = $conn->prepare("SELECT id FROM vprg_users WHERE email = ?");
			$stmt->bind_param("s", $email);
			$stmt->bind_result($id_from_db);
			$stmt->execute();
			if($stmt->fetch()){
			//kasutaja olemas
				$notice = "Sellise tunnusega (" .$email .") kasutaja on juba olemas!";
			} else {
			//sulgen eelmise käsu
				$stmt->close();
				$stmt = $conn->prepare("INSERT INTO vprg_users (firstname, lastname, birthdate, gender, email, password) VALUES(?,?,?,?,?,?)");
				echo $conn->error;	
				//Krüpteerime parooli
				$option = ["cost" => 12];
				$pwd_hash = password_hash($password, PASSWORD_BCRYPT, $option);
				$stmt->bind_param("sssiss", $firstname, $surname, $birth_date, $gender, $email, $pwd_hash);
				if($stmt->execute()){
					$notice = "Uus kasutaja edukalt loodud!";
				} else {
					$notice = "Uue kasutaja loomisel tekkis viga: " .$stmt->error;
				}
		}	
		$stmt->close();
		$conn->close();
		return $notice;
	}	
	
	function sign_in($email, $password){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id, firstname, lastname, password FROM vprg_users WHERE email = ?");
		echo $conn->error;
		$stmt->bind_param("s", $email);
		$stmt->bind_result($id_from_db, $firstname_from_db, $lastname_from_db, $password_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			//kasutaja on olemas, parool tuli
			if(password_verify($password, $password_from_db)){
				//Parool õige, saab sisse.
				$_SESSION["user_id"] = $id_from_db;
                $_SESSION["first_name"] = $firstname_from_db;
                $_SESSION["last_name"] = $lastname_from_db;
				//=====================================================================================
				//edaspidi loeme kasutajaprofiili, kui see on olemas andmebaasis. Kasutame kasutaja enda valitud värve.
					$stmt->close();
					$stmt = $conn->prepare("SELECT bgcolor, txtcolor FROM vprg_userprofiles WHERE userid = ?");
					$stmt->bind_param("i", $_SESSION["user_id"]);
					$stmt->bind_result($bg_color_from_db, $txt_color_from_db);
					$stmt->execute();
					$_SESSION["bg_color"] = "#FFFFFF";
					$_SESSION["text_color"] = "#000000";
					if($stmt->fetch()){
						if(!empty($txt_color_from_db)){
							$_SESSION["text_color"] = $txt_color_from_db;
						}	
						if(!empty($bg_color_from_db)){
							$_SESSION["bg_color"] = $bg_color_from_db;
						}	
					}	
				//valge #FFFFFF
				//must #000000
				//=====================================================================================
				$stmt->close();
				$conn->close();
				header("Location: home.php");
				exit();
			} else {
				$notice = "Kasutajatunnus või salasõna oli vale!";
			}	
		} else {
			$notice = "Kasutajatunnus või salasõna oli vale!";
		}	
		
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	function read_user_description(){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT description FROM vprg_userprofiles WHERE userid = ?");
		echo $conn->error;
		$stmt->bind_param("i", $_SESSION["user_id"]);
		$stmt->bind_result($description_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			$notice = $description_from_db;
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}	
	
	function save_user_profile($description, $bg_color, $txt_color){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT id FROM vprg_userprofiles WHERE userid = ?");
		echo $conn->error;
		$stmt->bind_param("i", $_SESSION["user_id"]);
		$stmt->bind_result($id_from_db);
		$stmt->execute();
		if($stmt->fetch()){
			$stmt->close();
			$stmt = $conn->prepare("UPDATE vprg_userprofiles SET description = ?, bgcolor = ?, txtcolor = ? WHERE userid = ?");
			echo $conn->error;
			$stmt->bind_param("sssi", $description, $bg_color, $txt_color, $_SESSION["user_id"]);
		} else {
			$stmt->close();
			$stmt = $conn->prepare("INSERT INTO vprg_userprofiles (userid, description, bgcolor, txtcolor) VALUES(?,?,?,?)");
			echo $conn->error;
			$stmt->bind_param("isss", $_SESSION["user_id"], $description, $bg_color, $txt_color);
		}
		if($stmt->execute()){
			$notice = "Korras";
		}	else {
			$notice = "Tekkis probleem salvestamisel: " .$stmt->error;
		}
	$stmt->close();
	$conn->close();
	return $notice;
	}	