<?php
if (!isset($_POST['kegSizeId']) || !isset($_POST['beerId']) || !isset($_POST['tapId']))
	throw new CustomException(MISSING_URL_PARAMETER, __FILE__, __LINE__);

$query =
'INSERT INTO kegs (beer_id, keg_size_id)
VALUES (\'' . mysql_real_escape_string($_POST['beerId']) . '\', \'' . mysql_real_escape_string($_POST['kegSizeId']) . '\')';

$result = mysql_query($query);
if ($result === false)
	throw new CustomException(MYSQL_QUERY_ERROR, __FILE__, __LINE__, mysql_error(), mysql_errno(), $query);

$tapId = $_POST['tapId'];
if ($tapId !== "NULL")
{
	$query = 'UPDATE taps SET keg_id = ' . mysql_insert_id() . ' WHERE id = \'' . mysql_real_escape_string($_POST['tapId']) . '\'';
	
	$result = mysql_query($query);
	if ($result === false)
		throw new CustomException(MYSQL_QUERY_ERROR, __FILE__, __LINE__, mysql_error(), mysql_errno(), $query);
}

header("location: ?p=adminMenu");
?>