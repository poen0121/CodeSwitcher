<?php
/*
>> Information

	Title		: csl_language function
	Revision	: 2.6.0
	Notes		: Use root XML tag names , the default is `language`.

	Revision History:
	When			Create		When		Edit		Description
	---------------------------------------------------------------------------
	07-01-2011		Poen		07-01-2011	Poen		Create the program.
	09-13-2016		Poen		02-22-2017	Poen		Reforming the program.
	---------------------------------------------------------------------------

>> About

	Language XML file processing.

	Type file extension is xml.

	Load the file returns csl_language_content object.

>> Usage Function

	==============================================================
	Include file
	Usage : include('language/main.inc.php');
	==============================================================

	==============================================================
	Create new Class.
	Usage : Object var name=new csl_language($tag,$version,$encoding);
	Param : string $tag (xml main tag name) : Default language
	Param : string $version (xml version number) : Default 1.0
	Param : string $encoding (xml encoding type) : Default utf-8
	Return : object
	--------------------------------------------------------------
	Example :
	$csl_language=new csl_language();
	Example :
	$csl_language=new csl_language('language','1.0','utf-8');
	==============================================================

	==============================================================
	Load localhost language XML file.
	Usage : Object->load($path);
	Param : string $path (file path)
	Return : object
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	$content=$csl_language->load('files/en_US/01.xml');
	==============================================================

	==============================================================
	Gets tag content.
	Usage : csl_language_content Object->gets($tag,$html);
	Param : string $tag (text tag name)
	Param : boolean $html (html encode mode) : Default true
	Return : string
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example :
	$content=$csl_language->load('files/en_US/01.xml');
	$content->gets('name');
	Output >> Name
	$content=$csl_language->load('files/en_US/01.xml');
	$content->gets('name',false);
	Output >> Name
	==============================================================

>> Established method of reference example language file.

	1. The establishment of language files for multiple file and file name can set their own.
	eg:
	en_US/01.xml
	en_US/02.xml
	...
	-----------------------------------------
	<?xml version="1.0" encoding="utf-8"?>
	<language>
	</language>
	-----------------------------------------

	2. Build language file can set the parameters.
	eg:
	en_US/01.xml
	-----------------------------------------
	<?xml version="1.0" encoding="utf-8"?>
	<language>
		<name>Name</name>
		<image>Image</image>
	</language>
	-----------------------------------------

>> Example

	$csl_language=new csl_language();
`	$content=$csl_language->load('files/en_US/01.xml');
	$content->gets('name');

*/
?>