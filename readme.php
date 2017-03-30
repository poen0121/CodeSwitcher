<?php
/*
>> About

	CodeSwitcher is the code version control framework.

	Based on Model 2 MVC architecture.
	-----------------------------------------------------
	MVC :

		( Model ） - in models directory.

		( View ） - in templates directory.

		( Controller ） - in events directory.

>> Directory Structure

	1.core : Main program files directory.

	2.configs : Configs directory support version control mechanism.

	3.languages : Languages directory support version control mechanism.

	4.libraries : Developer libraries directory version control mechanism is optional.

	5.models : Models directory support version control mechanism.

	6.events : Control events to perform directory rely on version control mechanism.

	7.resources : Storage resources directory version control mechanism is optional.

	8.templates : Templates directory support version control mechanism.

>> Version Control - Revision Rule

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

	Note: The CodeSwitcher functions can only be called within the framework script.

	Note: csl_mvc::callEvent function only be called within the events script directory index.php file.

	==============================================================
	Get the available version info form the CodeSwitcher root directory file script name.
	Usage : csl_mvc::version($scriptName,$mode);
	Param : string $scriptName (script name in framework)
	Param : string $mode (returns directory relative path or version number) : Default false
	Note : $mode `true` is returns directory relative path.
	Note : $mode `false` is returns version number.
	Return : string
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_mvc::version('resources/example');
	Output >> Version Number
	Example :
	csl_mvc::version('resources/example',TRUE);
	Output >> Directory URI
	==============================================================

	==============================================================
	Get the relative path form the CodeSwitcher root directory file script name.
	Usage : csl_mvc::formPath($scriptName);
	Param : string $scriptName (script name in framework)
	Return : string
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example :
	csl_mvc::formPath('libraries/test.js');
	Output >> URI
	==============================================================

	==============================================================
	Returns the index page relative path form the CodeSwitcher controller events script directory path name.
	Usage : csl_mvc::index($scriptNaame);
	Param : string $scriptNaame (events script directory path name)
	Return : string
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_mvc::index('example');
	Output >> URI
	==============================================================

	==============================================================
	Returns error log storage status 0 or 1, a temporarily change.
	Usage : csl_mvc::logs($mode);
	Param : boolean $mode (temporarily change mode does not support tester mode) : Default null
	Return : integer
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Current log storage status.
	Example :
	csl_mvc::logs();
	Output >> Current status 0 or 1
	--------------------------------------------------------------
	Change the current log storage status.
	Example :
	csl_mvc::logs(true);
	Output >> 1
	Example :
	csl_mvc::logs(false);
	Output >> 0
	==============================================================

	==============================================================
	Returns debug display state 0 or 1, a temporarily change.
	Usage : csl_mvc::debug($mode);
	Param : boolean $mode (temporarily change mode does not support tester mode) : Default null
	Return : integer
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Current debug status.
	Example :
	csl_mvc::debug();
	Output >> Current status 0 or 1
	--------------------------------------------------------------
	Declare the current debug status.
	Example :
	csl_mvc::debug(true);
	Output >> 1
	Example :
	csl_mvc::debug(false);
	Output >> 0
	==============================================================

	==============================================================
	Load configuration data form the CodeSwitcher configs directory.
	Usage : csl_mvc::cueConfig($model);
	Param : string $model (model name)
	Return : data
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_mvc::cueConfig('example');
	Output >> Example
	==============================================================

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
	Returns the version number when the library file was loaded form the CodeSwitcher libraries directory.
	Usage : csl_mvc::importLibrary($model);
	Param : string $model (model name)
	Return : string
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_mvc::importLibrary('example');
	Output >> 1.0.1
	==============================================================

	==============================================================
	Load the page's template file to view the contents from the CodeSwitcher templates directory.
	Usage : csl_mvc::viewTemplate($model,$data,$process);
	Param : string $model (model name)
	Param : array $data (data array) : Default void array
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

>> CodeSwitcher System Configuration

	Directory : configs/CodeSwitcher
	--------------------------------------------------------------
	Note: The configs/CodeSwitcher directory does not support test develop mode in the csl_mvc::cueConfig function.

	==============================================================
	The introduction page uses the controller script directory name.
	Only the root directory index.php script is supported.
	Example:
	$CS_CONF['INTRO'] = 'home';
	==============================================================

	==============================================================
	Set the default host time zone for the script with a value of -12 ~ 14 by GMT.
	Example:
	Taipei time zone :
	$CS_CONF['DEFAULT_TIMEZONE'] = 8;
	==============================================================

	==============================================================
	Languages xml file version code.
	Example:
	$CS_CONF['LANGUAGE_XML_VERSION'] = '1.0';
	==============================================================

	==============================================================
	Languages xml file encoding code.
	Example:
	$CS_CONF['LANGUAGE_XML_ENCODING'] = 'utf-8';
	==============================================================

	==============================================================
	Error stack trace is set to true or false.
	Example:
	$CS_CONF['ERROR_STACK_TRACE_MODE'] = false;
	==============================================================

	==============================================================
	Error log storage mode is set to true or false.
	Example:
	$CS_CONF['ERROR_LOG_MODE'] = false;
	--------------------------------------------------------------
	About Function : csl_mvc::logs($mode);
	Function : Returns error log storage status 0 or 1, a temporarily change.
	Param : boolean $mode (temporarily change mode does not support tester mode)
	Return : integer
	Return Note : Returns FALSE on failure.
	==============================================================

	==============================================================
	Error log storage directory location.
	Example:
	$CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'] = '';
	--------------------------------------------------------------
	Note : The void value automatically uses the php.ini error_log location.
	Note : Whether the directory exists and whether the directory permissions are writable.
	==============================================================

	==============================================================
	Testers debug display mode is set to true or false.
	Example:
	$CS_CONF['TESTER_DEBUG_MODE'] = false;
	--------------------------------------------------------------
	Note : General user default is false.
	About Function : csl_mvc::debug($mode);
	Function : Returns debug display state 0 or 1, a temporarily change.
	Param : boolean $mode (temporarily change mode does not support tester mode)
	Return : integer
	Return Note : Returns FALSE on failure.
	==============================================================

	==============================================================
	Testers develop mode is set to true or false.
	Example:
	$CS_CONF['TESTER_DEVELOP_MODE'] = false;
	==============================================================

	==============================================================
	Testers IP source list settings, the localhost is tester.
	Example:
	$CS_CONF['TESTER_IP'][] = '117.108.121.77';
	==============================================================

*/
?>