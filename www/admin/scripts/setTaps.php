<?php
if (!isset($_POST['taps']))
	throw new CustomException(MISSING_URL_PARAMETER, __FILE__, __LINE__);

$query = 'UPDATE taps SET keg_id = CASE id';

$tapsStr = $_POST['taps'];

$index1 = 0;
$index2 = 0;
while (($index2 = strpos($tapsStr, ',', $index1)) !== false)
{
	$index2s = strpos($tapsStr, ':', $index1);
	$tapId = substr($tapsStr, $index1, $index2s - $index1);
	
	$index2s += 1;
	$kegId = substr($tapsStr, $index2s, $index2 - $index2s);
	
	$query .= ' WHEN ' . mysql_real_escape_string($tapId) . ' THEN ' . mysql_real_escape_string($kegId);
	
	$index1 = $index2 + 1;
}

$query .= ' ELSE NULL END';

$result = mysql_query($query);
if ($result === false)
	throw new CustomException(MYSQL_QUERY_ERROR, __FILE__, __LINE__, mysql_error(), mysql_errno(), $query);

die('1');
?>