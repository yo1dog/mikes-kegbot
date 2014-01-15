<?php
if (!isset($_POST['name']) || !isset($_POST['description']))
	throw new CustomException(MISSING_URL_PARAMETER, __FILE__, __LINE__);

$name = $_POST['name'];
$description = $_POST['description'];

if (strlen($description) === 0)
	$description = "NULL";
else
	$description = "'" . mysql_real_escape_string($description) . "'";

$query =
'INSERT INTO breweries (name, description)
VALUES (\'' . mysql_real_escape_string($name) . '\', ' . $description . ')';

$result = mysql_query($query);
if ($result === false)
	throw new CustomException(MYSQL_QUERY_ERROR, __FILE__, __LINE__, mysql_error(), mysql_errno(), $query);

header("location: ?p=adminMenu");
?>