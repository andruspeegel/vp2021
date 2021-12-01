<?php
	$database = "if21_and_pee";
	require_once("fnc_general.php");
	require_once("../../config.php");
	
	function save_regis($eesnimi, $pkonnanimi, $kood){
		$response = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("INSERT INTO vprg_party (firstname, lastname, studentcode) VALUES (?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("ssi", $eesnimi, $pkonnanimi, $kood);
		if($stmt->execute()){
			$response = "Edukalt salvestatud.";
		} else {
			$response = "Tekkis viga.";
		}
		$stmt->close();
		$conn->close();
		return $response;
	}
	
	function cancel_regis($kood){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("UPDATE vprg_party SET cancelled = NOW() WHERE studentcode = ?");
		echo $conn->error;
		$stmt->bind_param("i", $kood);
		if($stmt->execute()){
			$notice = "Edukalt tühistatud.";
		} else {
			$notice = "Tekkis viga.";
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	function read_person_select($selected){
		$options_html = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT id, firstname, lastname FROM vprg_party WHERE cancelled IS NULL");
		echo $conn->error;
		$stmt->bind_result($id_from_db, $first_name_from_db, $last_name_from_db);
		$stmt->execute();
		while($stmt->fetch()){
			$options_html .= '<option value="' .$id_from_db .'"';
			if($id_from_db == $selected) {
				$options_html .= " selected";
			}
			$options_html .= ">" .$first_name_from_db ." " .$last_name_from_db ."</options> \n";
		}	
		$stmt->close();
		$conn->close();
		return $options_html;
	}
	
	function payment_update($payment, $person){
		$notice = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("UPDATE vprg_party SET payment = ? WHERE id = ?");
		echo $conn->error;
		$stmt->bind_param("ii", $payment, $person);
		if($stmt->execute()){
			$notice = "Edukalt muudetud.";
		} else {
			$notice = "Tekkis viga.";
		}
		$stmt->close();
		$conn->close();
		return $notice;
	}
	
	function list_party_info_admin(){
		$html = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT firstname, lastname, studentcode, payment, cancelled FROM vprg_party");
		echo $conn->error;
		$stmt->bind_result($first_name_from_db, $last_name_from_db, $student_code_from_db, $payment_from_db, $cancel_status_from_db);
		$stmt->execute();
		while($stmt->fetch()){
			$html .= "<li>" .$first_name_from_db ." " .$last_name_from_db .", üliõpilaskood: " .$student_code_from_db .", ";
			if(!empty($payment_from_db)){
				$html .= "on makstud";
			} else {
				$html .= "veel on maksmata";
			}
			if(!empty($cancel_status_from_db)){
				$html .= ", tulek on tühistatud";
			} else {
				$html .= ", tuleb peole.";
			}
			$html .= "</li> \n";
		}
		if(empty($html)){
			$html = "<p>Info puudub.</p> \n";
		} else {
            $html = "<ul> \n" .$html ."</ul> \n";
        }
		$stmt->close();
        $conn->close();
        return $html;
	}
	
	function list_party_count_user(){
		$number = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT COUNT(id) FROM vprg_party WHERE cancelled IS NULL");
		echo $conn->error;
		$stmt->bind_result($count_from_db,);
		$stmt->execute();
		if($stmt->fetch()){
			$number = $count_from_db ." inimest on registreerunud \n <br>";
		} else {
			$number = "Tekkis viga";
		}
		$stmt->close();
        $conn->close();
        return $number;
	}
	
	function list_party_payment_count_user(){
		$count = null;
        $conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
        $conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT COUNT(payment) FROM vprg_party WHERE cancelled IS NULL");
		echo $conn->error;
		$stmt->bind_result($count_from_db,);
		$stmt->execute();
		if($stmt->fetch()){
			$count = $count_from_db ." inimest on maksnud ja kindlalt tulemas \n";
		} else {
			$count = "Tekkis viga";
		}
		$stmt->close();
        $conn->close();
        return $count;
	}