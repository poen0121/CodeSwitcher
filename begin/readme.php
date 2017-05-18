<?php
/*
>> Begin

	This is a required directory file.

	Control events begin program relies on the version control mechanism.

	Event script execution will work together.

	You can control the event script to interrupt execution.

	You can do some pretreatment function.

>> Set Directory Version

	File Structure :

	begin
	├── 1.0.1
	│	└── main.inc.php
	└── ini
		└── version.php

	Step 1 : Create `ini` directory.

	Step 2 : Create limit version file `ini/version.php`.
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

	Step 3 : Create a directory such as `1.0.1` version.

	Step 4 : Create a release directory `1.0.1/main.inc.php` master file and coding functions.
	Write at the top of the file :
	-----------------------------------------------------
	<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
	-----------------------------------------------------
	Control returns :
	-----------------------------------------------------
	<?php
	return true;
	?>
	-----------------------------------------------------
	eg :
	-----------------------------------------------------
	<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
	<?php
	return true;
	?>
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
	csl_mvc::version('begin');
	Output >> Version Number
	Example :
	csl_mvc::version('begin',TRUE);
	Output >> Directory Relative Path
	==============================================================

>> Example

	Preload the library.
	-----------------------------------------------------
	<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
	<?php
	csl_mvc::importLibrary('example');
	return true;
	?>
	-----------------------------------------------------

*/
?>