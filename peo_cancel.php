<?php
	require_once("use_session.php");


	require_once("../../config.php");
	require_once("fnc_general.php");
	require_once("fnc_pidu.php");
	
	$kood = null;
	$regis_error = null;
	$regis_notice = null;
	
	if(isset($_POST["regis_submit"])){
		if(empty($_POST["kood_input"])){
			$regis_error .= " Puudub üliõpilaskood.";
		} else {
			$kood = test_input($_POST["kood_input"]);
		}
		
		if($kood > "999999"){
			$regis_error .= " Ebakorrektne üliõpilaskood.";
		}
		
		if(empty($regis_error)){
			$regis_notice = cancel_regis($kood);
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
    </ul>
		<hr>
	<h2>People registreerimise tühistamise leht</h2>
	<hr>
	<p>Palun sisestage oma üliõpilaskood, et tühistada registreerumine.</p>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <input type="number" name="kood_input" id="kood_input" max="999999" value="<?php echo $kood; ?>">
        <br>
        <input type="submit" name="regis_submit" value="Salvesta"><span id="notice"><?php echo $regis_error; ?></span>
    </form>
    <span><?php echo $regis_notice; ?></span>
</body>
</html>