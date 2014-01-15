<?php
ini_set('display_errors', '1');
ini_set('error_log', '../logs/errors.txt');

// ***********
// Disable Magic quotes
if (get_magic_quotes_gpc()) {
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][stripslashes($k)] = $v;
                $process[] = &$process[$key][stripslashes($k)];
            } else {
                $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    }
    unset($process);
}
// ***********

include('../includes/errors.php');
include('../includes/constants.php');
include('../includes/database.php');
include('../includes/FirePHPCore/fb.php');

$page = NULL;
$pageURL = NULL;
$title = NULL;
$exception = NULL;
$isScript = false;

if (isset($_GET['p']))
{
	$page = $_GET['p'];
	
	try
	{
		if ($page === 'taps')
			$pageURL = 'pages/taps.php';
		else if ($page === 'newKeg')
			$pageURL = 'pages/newKeg.php';
		else if ($page === 'newBeer')
			$pageURL = 'pages/newBeer.php';
		else if ($page === 'newBrewery')
			$pageURL = 'pages/newBrewery.php';
		else
			throw new CustomException(PAGE_NOT_FOUND, __FILE__, __LINE__);
	}
	catch(Exception $e)
	{
		$exception = $e;
	}
}
else if (isset($_GET['s']))
{
	$isScript = true;
	$script = $_GET['s'];
	$headerFooter = false;
	
	try
	{
		if ($script === 'setTaps')
			$pageURL = 'scripts/setTaps.php';
		else if ($script === 'emptyKeg')
			$pageURL = 'scripts/emptyKeg.php';
		else if ($script === 'newKeg')
			$pageURL = 'scripts/newKeg.php';
		else if ($script === 'newBeer')
			$pageURL = 'scripts/newBeer.php';
		else if ($script === 'newBrewery')
			$pageURL = 'scripts/newBrewery.php';
		else
			throw new CustomException(PAGE_NOT_FOUND, __FILE__, __LINE__);
	}
	catch(Exception $e)
	{
		$exception = $e;
	}
}
else
	$pageURL = 'pages/taps.php';

if ($exception !== NULL)
{
	if (get_class($exception) == 'CustomException')
	{
		if ($isScript)
			echo($exception);
		else
			$exception->GenerateMessage();
	}
	else
		echo($exception);
	
	error_log($exception);
}

if (!$isScript)
{
	?>

<html>
<head>
<title>Mike's KegBot - ADMIN</title>
<meta HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1">
<meta NAME="ROBOTS" CONTENT="All">
<link REL="styleSheet" HREF="style.css" TYPE="text/css">
<link REL="styleSheet" HREF="../styleShared.css" TYPE="text/css">
</head>

<body>
<h3>
	<a href="?p=taps">Taps</a>
	&nbsp;&bull;&nbsp;
	<a href="?p=newKeg">Keg</a>
	&nbsp;&bull;&nbsp;
	<a href="?p=newBeer">Beer</a>
	&nbsp;&bull;&nbsp;
	<a href="?p=newBrewery">Brewery</a>
</h3>

<hr />
	<?php
}

try
{
	if ($pageURL !== NULL)
		include($pageURL);
}
catch(Exception $e)
{
	if (get_class($e) == 'CustomException')
	{
		if (!$isScript)
			$e->GenerateMessage();
		else
			$e->GenerateSimpleMessage();
	}
	else
		echo($e);
	
	error_log($e);
}
if (!$isScript)
{
	?>

</body>
</html>

	<?php
}
?>
