<?php
include('../includes/database.php');

if (!isset($_GET['s']))
	die('p');

$secret = $_GET['s'];

if ($secret !== 'kegerator111111yo1dog')
	die('s');

$query = mysql_query('SELECT id FROM taps');

if ($query === false)
	die('m');

// note each id is a single char (max of 10 taps). taps.id tinyint(1)
while ($row = mysql_fetch_row($query))
	echo $row[0];

die('1');
?>