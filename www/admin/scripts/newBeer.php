<?php
if (!isset($_POST['name']) || !isset($_POST['breweryId']) || !isset($_POST['description']) || !isset($_POST['abv']) || !isset($_POST['style']) || !isset($_POST['ibu']))
	throw new CustomException(MISSING_URL_PARAMETER, __FILE__, __LINE__);

$name = $_POST['name'];
$breweryId = $_POST['breweryId'];
$description = $_POST['description'];
$abv = $_POST['abv'];
$style = $_POST['style'];
$ibu = $_POST['ibu'];

if (strlen($description) === 0)
	$description = "NULL";
else
	$description = "'" . mysql_real_escape_string($description) . "'";

if (strlen($style) === 0)
	$style = "NULL";
else
	$style = "'" . mysql_real_escape_string($style) . "'";

if (strlen($ibu) === 0)
	$ibu = "NULL";
else
	$ibu = "'" . mysql_real_escape_string($ibu) . "'";

$query =
'INSERT INTO beers (name, brewery_id, description, abv, style, ibu)
VALUES (\'' . mysql_real_escape_string($name) . '\', \'' . mysql_real_escape_string($breweryId) . '\', ' . $description . ', \'' . mysql_real_escape_string($abv) . '\', ' . $style . ', ' . $ibu . ')';

$result = mysql_query($query);
if ($result === false)
	throw new CustomException(MYSQL_QUERY_ERROR, __FILE__, __LINE__, mysql_error(), mysql_errno(), $query);

header("location: ?p=adminMenu");
?>