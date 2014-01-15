<meta property="og:title" content="Marine Recruiter">
<meta property="og:url" content="http://www.mikeskegbot.com">
<meta property="og:site_name" content="Marines.com">
<meta property="og:description" content="Marine recruiters take it seriously and feel a great responsibility to fulfill the job to the best of their ability. Many Marines say that recruiting duty is among the most rewarding assignments they have had as a Marine, because they are helping to ensure the high standards and future of our Corps.">
<meta property="og:image" content="http://qa-www.marines.com/image/layout_icon?img_id=80848">
<meta property="og:type" content="article">

<meta property="fb:admins" content="509203541">
<meta property="fb:app_id" content="115208188549">
<meta property="fb:locale" content="en_US">

<link rel="image_src" href="http://qa-www.marines.com/image/layout_icon?img_id=80848" />

<?php
ini_set('display_errors', '1');
ini_set('error_log', 'logs/errors.txt');

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

include('includes/errors.php');
include('includes/constants.php');
include('includes/database.php');
include('includes/FirePHPCore/fb.php');

$headerFooter = true;
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
		if ($page === 'home')
			$pageURL = 'pages/home.php';
		else if ($page === 'drinkfeed')
			$pageURL = 'pages/drinkfeed.php';
		else if ($page === 'statistics')
			$pageURL = 'pages/statistics.php';
		else if ($page === 'development')
			$pageURL = 'pages/development.php';
		else
			throw new CustomException(PAGE_NOT_FOUND, __FILE__, __LINE__);
	}
	catch(Exception $e)
	{
		$exception = $e;
	}
}
/*else if (isset($_GET['s']))
{
	$isScript = true;
	$script = $_GET['s'];
	$headerFooter = false;
	
	try
	{
		if ($script === 'getProjectComments')
			$pageURL = 'scripts/getProjectComments.php';
		else if ($script === 'postComment')
			$pageURL = 'scripts/postComment.php';
		else
			throw new CustomException(PAGE_NOT_FOUND, __FILE__, __LINE__);
	}
	catch(Exception $e)
	{
		$exception = $e;
	}
}*/
else
	$pageURL = 'pages/home.php';

if ($headerFooter)
	include('includes/header.php');

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


if ($headerFooter)
	include('includes/footer.php');
?>