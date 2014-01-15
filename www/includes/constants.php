<?php
// Database
define('PROJECT_IMAGE_FILENAME_CHARS',	32);

define('COMMENT_TEXT_CHARS', 512);

define('NAME_CHARS',	32);
define('URL_CHARS',		128);
define('EMPLOYER_POSITION_CHARS',	64);

define('TABLE_LANGUAGES',	0);
define('TABLE_FRAMEWORKS',	1);
define('TABLE_SOFTWARE',	2);
define('TABLE_EMPLOYERS',	3);
define('TABLE_CATEGORIES',	4);
define('TABLE_PROJECTS',	5);
function GetTableName($table)
{
	if ($table == TABLE_LANGUAGES)
		return 'languages';
	else if ($table == TABLE_FRAMEWORKS)
		return 'frameworks';
	else if ($table == TABLE_SOFTWARE)
		return 'software';
	else if ($table == TABLE_EMPLOYERS)
		return 'employers';
	else if ($table == TABLE_CATEGORIES)
		return 'categories';
	else if ($table == TABLE_PROJECTS)
		return 'projects';
	
	return NULL;
}
function GetTableColumns($table)
{
	if ($table == TABLE_LANGUAGES || $table == TABLE_CATEGORIES)
		return array('name' => 'Name', 'description' => 'Description');
	if ($table == TABLE_FRAMEWORKS || $table == TABLE_SOFTWARE)
		return array('name' => 'Name', 'url' => 'URL', 'description' => 'Description');
	else if ($table == TABLE_EMPLOYERS)
		return array('name' => 'Name', 'year_from' => 'Year1', 'year_to' => 'Year2', 'position' => 'Position', 'url' => 'URL', 'description' => 'Description');
}

// Images
define('IMAGE_EXT', '.png');
?>