<?php
/*
>> Languages

	The languages directory support version control mechanism.

	Language XML file resources.

>> Set Directory Version

	File Structure :

	languages
	└── main directory
		├── 1.0.1
		│	└── main.inc.xml
	  	└── ini
			└── version.php

	Step 1 : Create a main directory.

	Step 2 : Create `ini` directory.

	Step 3 : Create limit version file `ini/version.php`.
	Write at the top of the file :
	-----------------------------------------------------
	<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
	-----------------------------------------------------
	eg :
	-----------------------------------------------------
	<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
	<?php
	return '1.0.1';
	?>
	-----------------------------------------------------

	Step 4 : Create a directory such as `1.0.1` version.

	Step 5 : Create a release directory `1.0.1/main.inc.xml` master file contains the contents of the language (Reference : Established Method).

>> Revision Rule

	[Main version number] . [Minor version number] . [Revision number]

	#Main version number:
	A major software updates for incremental , usually it refers to the time a major update function and interface has been a significant change.
	 
	#Minor version number:
	Software release new features , but does not significantly affect the entire software time increments.
	 
	#Revision number:
	Usually in the software have bug , bug fixes released incremented version.

	Example :
	Version : 0.0.0
	Version : 1.0.0
	Version : 1.0.1
	Version : 1.1.0
	Version : 2.0.0
	Version : 2.0.1
	Version : 2.1.0

>> Established Method

	Step 1 :
	Create language directory.
	eg :
	en_US

	Step 2 :
	Create language XML main file.
	eg:
	en_US/1.0.1/main.inc.xml
	-----------------------------------------
	<?xml version="1.0" encoding="utf-8"?>
	<language>
	</language>
	-----------------------------------------

	Step 3 :
	Build language file can set the parameters.
	eg:
	en_US/1.0.1/main.inc.xml
	-----------------------------------------
	<?xml version="1.0" encoding="utf-8"?>
	<language>
		<language_name>English</language_name>
		<change>Change</change>
	</language>
	-----------------------------------------

>> Framework Usage Function

	==============================================================
	Load create a language object form the CodeSwitcher languages directory.
	Usage : csl_mvc::cueLanguage($model);
	Param : string $model (model name)
	Return : object
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_mvc::cueLanguage('en_US');
	Output >> csl_language_content Object
		**********************************************************
		Gets tag content.
		Usage : csl_language_content Object->gets($tag,$html);
		Param : string $tag (text tag name)
		Param : boolean $html (html encode mode) : Default true
		Return : string
		Return Note : Returns FALSE on failure.
		----------------------------------------------------------
		Example :
		$language=csl_mvc::cueLanguage('en_US');
		$language->gets('language_name');
		Output >> English
		$language=csl_mvc::cueLanguage('en_US');
		$language->gets('language_name',false);
		Output >> English
		**********************************************************
	==============================================================

	==============================================================
	Get the available version info from the file directory path name of the CodeSwitcher root directory.
	Usage : csl_mvc::version($pathName,$mode);
	Param : string $pathName (path name in framework)
	Param : string $mode (returns directory relative path or version number) : Default false
	Note : $mode `true` is returns directory relative path.
	Note : $mode `false` is returns version number.
	Return : string
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_mvc::version('languages/en_US');
	Output >> Version Number
	Example :
	csl_mvc::version('languages/en_US',TRUE);
	Output >> Directory URI
	==============================================================

*/
?>