<?php
/*
>> View

	Templates directory support version control mechanism.

	Interface designer graphical interface design.

	System directory : error/500 .

>> Content-Type

	You can use Content-Type to create various file formats.

	HTML file.
	eg :
	-----------------------------------------------------
	<?php header('Content-Type: text/html; charset=utf-8');?>
	-----------------------------------------------------

	JSON file.
	eg :
	-----------------------------------------------------
	<?php header('Content-Type: application/json; charset=utf-8');?>
	-----------------------------------------------------

	XML file.
	eg :
	-----------------------------------------------------
	<?php header('Content-Type: text/xml; charset=utf-8');?>
	-----------------------------------------------------

>> Set Directory Version

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

	Step 5 : Create a release directory `1.0.1/main.inc.php` master file and editing UI.
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
	Load the page's template file to view the contents from the CodeSwitcher templates directory.
	Usage : csl_mvc::viewTemplate($model,$data,$process);
	Param : string $model (model name)
	Param : array $data (data array)
	Param : boolean $process (return content string mode) : Default false
	Return : string
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Returns the version number of the view template content.
	Example :
	csl_mvc::viewTemplate('example',array('text'=>'Content'));
	Output >> 1.0.1
	--------------------------------------------------------------
	A string that returns the contents of the template.
	Example :
	csl_mvc::viewTemplate('example',array('text'=>'Content'),true);
	Output >> Contents String
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
	csl_mvc::version('templates/example');
	Output >> Version Number
	Example :
	csl_mvc::version('templates/example',TRUE);
	Output >> Directory URI
	==============================================================

>> Call Resources URI

	The resource URI path uses a leading / rather than a relative path unless you use the csl_mvc::formPath function to process the output URI.

	Example : http://example/CodeSwitcher

	Enter your own URI.
	eg:
	--------------------------------------------------------------
	<script src="/CodeSwitcher/resources/test.js"></script>
	--------------------------------------------------------------

	Use the csl_mvc::formPath function to process the output URI.
	eg:
	--------------------------------------------------------------
	<script src="<?=csl_mvc::formPath('resources/test.js')?>"></script>
	--------------------------------------------------------------
*/
?>