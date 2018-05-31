<?php
/*
>> Library

	The libraries directory support version control mechanism.

	Development of software subroutines function.

>> Set Directory Version

	File Structure :

	libraries
	└── main directory
		├── 1.0.1
		│	└── main.inc.php
	  	└── ini
	  		└── 1.0.1
				└── version.php

	Step 1 : Create a main directory.

	Step 2 : Create `ini` version directory such as `ini/1.0.1`.

	Step 3 : Create limit version file as `ini/1.0.1/version.php`.
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

	Example :
	Version : 0.0.0
	Version : 1.0.0
	Version : 1.0.1
	Version : 1.1.0
	Version : 2.0.0
	Version : 2.0.1
	Version : 2.1.0

>> Framework Usage Function

	==============================================================
	Returns the version number when the library file was loaded form the CodeSwitcher libraries directory.
	Usage : csl_mvc::import_library($model);
	Param : string $model (model name)
	Return : string
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_mvc::import_library('example');
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
	csl_mvc::version('libraries/example');
	Output >> Version Number
	Example :
	csl_mvc::version('libraries/example',TRUE);
	Output >> Directory Relative Path
	==============================================================
*/
?>