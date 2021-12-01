<?php
	require_once("use_session.php");


	require_once("../../config.php");
	require_once("fnc_general.php");
	require_once("fnc_pidu.php");
	
	$regis_notice = null;
	$regis_error = null;
	$person_selected = null;
	$payment_selected = null;
	
	if(isset($_POST["regis_submit"])){
		if(empty($_POST["person_select"])){
			$regis_error = "Puudub valitud isik.";
		} else {
			$person_selected = test_input($_POST["person_select"]);
		}
		if(empty($_POST["payment_select"])){
			$regis_error .= "Puudub maksmise staatuse valik.";
		} else {
			$payment_selected = test_input($_POST["payment_select"]);
		}
		
		if(empty($regis_error)){
			$regis_notice = payment_update($payment_selected, $person_selected);
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
	<h2>Peo Administraatori leht</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label for="person_select">Isik: </label>
		<select name="person_select" id="person_select">
			<option value="" selected disabled>Isik</option>
			<?php echo read_person_select($person_selected); ?>
		</select>
		<label for="payment_select">Maksmise staatus:</label>
		<select name="payment_select" id="payment_select">
			<option value="" selected disabled>Staatus</option>
			<option value="1">Makstud </option>
		</select>
        <input type="submit" name="regis_submit" value="Salvesta"><span id="notice"><?php echo $regis_error; ?></span>
    </form>
    <span><?php echo $regis_notice; ?></span>
	<h2> Registreerunute info </h2>
	<?php echo list_party_info_admin(); ?>
</body>
</html>