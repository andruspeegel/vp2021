<?php
	$database = "if21_and_pee";
	require_once("fnc_general.php");
	require_once("../../config.php");
	
	function save_news($news_title, $news, $expire_date, $file_name){
		$response = null;
		$photo_id = null;
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		if(!empty($file_name)){
			$stmt = $conn->prepare("INSERT INTO vprg_newsphotos (userid, filename) VALUES(?, ?)");
			echo $conn->error;
			$stmt->bind_param("is", $_SESSION["user_id"], $file_name);
			if($stmt->execute()){
				$photo_id = $conn->insert_id;
			}
			$stmt->close();
		}
		$stmt = $conn->prepare("INSERT INTO vprg_news (userid, title, content, photoid, expire) VALUES (?,?,?,?,?)");
		echo $conn->error;
		$stmt->bind_param("issis", $_SESSION["user_id"], $news_title, $news, $photo_id, $expire_date);
		if($stmt->execute()){
			$response = "Uudis on edukalt salvestatud.";
		} else {
			$response = "Uudist ei saanud salvestada, tekkis viga.";
		}
		$stmt->close();
		$conn->close();
		return $response;
	}
	
	function latest_news($limit){
		$html_news = null;
		$today = date("Y-m-d");
		$conn = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_password"], $GLOBALS["database"]);
		$conn->set_charset("utf8");
		$stmt = $conn->prepare("SELECT title, content, vprg_news.added, filename FROM vprg_news LEFT JOIN vprg_newsphotos ON vprg_newsphotos.id = vprg_news.photoid WHERE vprg_news.expire >= ? AND vprg_news.deleted IS NULL GROUP BY vprg_news.id ORDER BY vprg_news.id DESC LIMIT ?");
		echo $conn->error;
		$stmt->bind_param("si", $today, $limit);
		$stmt->bind_result($title_from_db, $content_from_db, $added_from_db, $filename_from_db);
		$stmt->execute();
		while ($stmt->fetch()){
			$html_news .= '<div class="newsblock"';
			if(!empty($filename_from_db)){
				$html_news .=" fullheightnews";
			}
			$html_news .= '">' ."\n";
			if(!empty($filename_from_db)){
				$html_news .= "\t" .'<img src="' .$GLOBALS["photo_upload_news_dir"].$filename_from_db .'" ';
				$html_news .= 'alt="' .$title_from_db .'"';
				$html_news .= "> \n";
			}
			
			$html_news .= "\t <h3>" .$title_from_db ."</h3> \n";
			$addedtime = new DateTime($added_from_db);
			$html_news .= "\t <p>(Lisatud: " .$addedtime->format("d.m.Y H:i:s") .")</p> \n";
			$html_news .= "\t <div>" .htmlspecialchars_decode($content_from_db) ."</div> \n";
			$html_news .= "</div> \n";
		}
		if($html_news == null){
			$html_news = "<p>Uudiseid praegu ei ole.</p>";
		}
		$stmt->close();
		$conn->close();
		return $html_news;
	}