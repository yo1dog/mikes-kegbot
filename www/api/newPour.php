<?php
include('../includes/database.php');

if (!isset($_GET['s']) || !isset($_GET['o']) || !isset($_GET['t']))
	die('p');

$secret = $_GET['s'];
$ounces = $_GET['o'];
$tapId = $_GET['t'];

if ($secret !== 'kegerator111111yo1dog')
	die('s');

$query = 'INSERT INTO pours (keg_id, ounces, timestamp)
		VALUES ((SELECT keg_id FROM taps WHERE id = \'' . mysql_real_escape_string($tapId) . '\'), \'' . mysql_real_escape_string($ounces) . '\', CURRENT_TIMESTAMP)';

if (mysql_query($query) === false)
	die($query);

die('1');
?>
