<?php
if (mysql_connect('127.0.0.1', 'kegerator', "kegerator111111") === false)
	throw new CustomException(MYSQL_CONNECTION_FAILED, __FILE__, __LINE__, mysql_error(), mysql_errno());

if (!mysql_select_db('kegerator'))
	throw new CustomException(MYSQL_CONNECTION_FAILED, __FILE__, __LINE__, mysql_error(), mysql_errno());
?>
