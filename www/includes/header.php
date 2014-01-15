<html>
<head>
<title>Mike's KegBot</title>
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<meta NAME="ROBOTS" CONTENT="All">
<link REL="styleSheet" HREF="style.css" TYPE="text/css">
<link REL="styleSheet" HREF="styleShared.css" TYPE="text/css">
<link REL="SHORTCUT ICON" HREF="images/favicon.ico" TYPE="image/x-icon">

<?php
$menueTabs = array(
	array("home", "menuOnTap"),
	array("drinkfeed", "menuDrinkFeed"),
	array("statistics", "menuStatistics"),
	array("development", "menuDevelopment"));
?>

<script type="text/javascript" async src="http://www.google-analytics.com/ga.js"></script>
<script type="text/javascript">
// <!--
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-10797534-3']);
  _gaq.push(['_trackPageview']);

<?php
foreach ($menueTabs as $tab)
{
	echo ('var ' . $tab[0] . 'Img1 = new Image(); ' . $tab[0] . 'Img1.src = "images/' . $tab[1] . '.png"; var ' . $tab[0] . 'Img2 = new Image(); ' . $tab[0] . 'Img2.src = "images/' . $tab[1] . 'Hover.png"; var '. $tab[0] . 'Img3 = new Image(); ' . $tab[0] . 'Img3.src = "images/' . $tab[1] . 'Selected.png";');
}
?>

var logoImg = new Image();
logoImg.src = "images/logo.png";

var footerImg = new Image();
footerImg.src = "images/footerRight.png";

var footerImgHover = new Image();
footerImgHover.src = "images/footerRightHover.png";
  
// -->
</script>

</head>
<body>
<div id="main">
<a href="?p=home"><img id="logo" src="images/logo.png" alt="Mike's Keg Bot" /></a>
<div id="menu">
    <img src="images/menuLeft.png" /><?php
foreach ($menueTabs as $tab)
{
	$thisTab = $page === $tab[0] || ($tab[0] === "home" && $page === NULL);
	$str = '';
	
	if (!$thisTab)
		$str .= '<a href="?p=' . $tab[0] . '">';
	
	$str .= '<img src="images/' . $tab[1];
	if ($thisTab)
		$str .= 'Selected';
	
	$str .= '.png"';
	
	if (!$thisTab)
		$str .= ' onMouseOver="this.src=\'images/' . $tab[1] . 'Hover.png\';" onMouseOut="this.src=\'images/' . $tab[1] . '.png\';" onClick="this.src=\'images/' . $tab[1] . 'Hover.png\';"';
	
	$str .= ' />';
	
	if (!$thisTab)
		$str .= '</a>';
	
	echo($str);
}
?>
</div>