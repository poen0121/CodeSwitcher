<?php
/*
>> Model

	The models directory support version control mechanism.

	Programmers write programs should function (algorithm implementation, etc.).

	Database experts and data management database design (can implement specific functions).

>> Set Directory Version

	File Structure :

	models
	└── main directory
		├── 1.0.1
		│	└── main.inc.php
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

	Step 5 : Create a release directory `1.0.1/main.inc.php` master file and coding functions.
	Write at the top of the file :
	-----------------------------------------------------
	<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
	-----------------------------------------------------

>> Revision Rule

	[Main version number] . [Minor version number] . [Revision number]

	#Main version number:
	A major software updates for incremental , usually it refers to the time a major update function and interface has been a significant change.
	 
	#Minor version number:
	Software release new features , but does not significantly affect the entire software time increments.
	 
	#Revision number:
	Usually in the software have bug , bug fixes released incremented version.

	Example:
	Version : 0.0.0
	Version : 1.0.0
	Version : 1.0.1
	Version : 1.1.0
	Version : 2.0.0
	Version : 2.0.1
	Version : 2.1.0

>> Framework Usage Function

	==============================================================
	Returns the version number when the model file was loaded form the CodeSwitcher models directory.
	Usage : csl_mvc::importModel($model);
	Param : string $model (model name)
	Return : string
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_mvc::importModel('example');
	Output >> 1.0.1
	==============================================================

	==============================================================
	Get the available version info form the CodeSwitcher root directory file script name.
	Usage : csl_mvc::version($scriptName,$mode);
	Param : string $scriptName (script name in framework)
	Param : string $mode (returns directory relative path or version number) : Default false
	Return : string
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_mvc::version('models/example');
	Output >> Version Number
	Example :
	csl_mvc::version('models/example',TRUE);
	Output >> Directory URI
	==============================================================

*/
?>