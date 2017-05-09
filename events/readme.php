<?php
/*
>> Controller

	Events script directory relies on the version control mechanism.

	Responsible for forwarding the request to process the request.

>> Set Directory Version

	File Structure :

	events
	└── main directory
		├── 1.0.1
		│	└── main.inc.php
	  	├── ini
		│	└── version.php
		└── index.php

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

	Step 5 : Create a release directory `1.0.1/main.inc.php` master file and coding logic mechanisms.
	Write at the top of the file :
	-----------------------------------------------------
	<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
	-----------------------------------------------------

	Step 6 : Create link file `index.php` in the main directory.
	-----------------------------------------------------
	<?php
	chdir(dirname(__FILE__));
	include('../../core/main.inc.php');
	csl_mvc::callEvent();
	?>
	-----------------------------------------------------

	Step 7 : URL call format.

	domain / events / events script directory link file path
	eg :
	-----------------------------------------------------
	example.com/events/example/index.php
	-----------------------------------------------------

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

>> Framework Usage Function

	Note: csl_mvc::callEvent function only be called within the events script directory index.php file.

	==============================================================
	Returns the version number when the script file was loaded form the CodeSwitcher events directory.
	Usage : csl_mvc::callEvent();
	Return : string
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_mvc::callEvent();
	Output >> 1.0.1
	==============================================================

	==============================================================
	Returns the version number when the event file was loaded form the CodeSwitcher events directory.
	Usage : csl_mvc::importEvent($model);
	Param : string $model (model name)
	Return : string
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_mvc::importEvent('home');
	Output >> 1.0.1
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
	csl_mvc::version('events/example');
	Output >> Version Number
	Example :
	csl_mvc::version('events/example',TRUE);
	Output >> Directory URI
	==============================================================

*/
?>