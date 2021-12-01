<?php
	require_once("use_session.php");


	require_once("../../config.php");
	require_once("fnc_general.php");
	require_once("fnc_pidu.php");
	
	$regis_notice = null;
	$eesnimi = null;
	$pkonnanimi = null;
	$kood = null;
	$regis_error = null;
	
	if(isset($_POST["regis_submit"])){
		if(empty($_POST["eesnimi_input"])){
			$regis_error = "Puudub eesnimi.";
		} else {
			$eesnimi = test_input($_POST["eesnimi_input"]);
		}
		if(empty($_POST["pkonnanimi_input"])){
			$regis_error .= " Puudub perekonnanimi.";
		} else {
			$pkonnanimi = test_input($_POST["pkonnanimi_input"]);
		}
		if(empty($_POST["kood_input"])){
			$regis_error .= " Puudub üliõpilaskood.";
		} else {
			$kood = test_input($_POST["kood_input"]);
		}
		if($kood > "999999"){
			$regis_error .= " Ebakorrektne üliõpilaskood.";
		}
		
		if(empty($regis_error)){
			$regis_notice = save_regis($eesnimi, $pkonnanimi, $kood);
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
	<h2>Peole registreerimise leht</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="eesnimi_input">Eesnimi</label>
        <input type="text" name="eesnimi_input" id="eesnimi_input" placeholder="eesnimi" value="<?php echo $eesnimi; ?>">
        <br>
		<label for="pkonnanimi_input">Perekonnanimi</label>
        <input type="text" name="pkonnanimi_input" id="pkonnanimi_input" placeholder="perekonnanimi" value="<?php echo $pkonnanimi; ?>">
        <br>
        <label for="kood_input">Üliõpilaskood</label>
        <input type="number" name="kood_input" id="kood_input" max="999999" value="<?php echo $kood; ?>">
        <br>
        <input type="submit" name="regis_submit" value="Salvesta"><span id="notice"><?php echo $regis_error; ?></span>
    </form>
    <span><?php echo $regis_notice; ?></span>
	<h2> Registreerunute info </h2>
	<?php 
	echo list_party_count_user();
	echo list_party_payment_count_user();
	?>
</body>
</html>