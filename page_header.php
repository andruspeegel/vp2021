<?php
	//tekitame style elemendi koos css ridadega värvide jaoks.
	//<style>
	//body{
	//	background-color: #FFFFBB;
	//	color> #FFCC00;
	//}	
	//</style>
	$css_colors = "<style> \n";
	$css_colors .= "\t body { \n";
	$css_colors .= "\t \t background-color: " .$_SESSION["bg_color"] ."; \n";
	$css_colors .= "\t \t color: " .$_SESSION["text_color"] ."; \n";
	$css_colors .= "\t } \n </style> \n";
?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title><?php echo $_SESSION["first_name"] ." " .$_SESSION["last_name"]; ?>, veebiprogrammeerimine</title>
	<?php echo $css_colors; ?>
</head>
<body>
    <img src="pics/vp_banner.png" alt="veebiprogrammeerimise lehe headeri banner">