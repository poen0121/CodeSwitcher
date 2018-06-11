<?php
/*
>> About

	GitHub : https://github.com/poen0121/CodeSwitcher

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

	4.libraries : Developer libraries directory support version control mechanism.

	5.models : Models directory support version control mechanism.

	6.events : Events script directory relies on the version control mechanism.

	7.resources : Storage resources directory version control mechanism is optional.

	8.templates : Templates directory support version control mechanism.

	9.storage : System storage directory.

>> Note

	PHP headers_sent function can not work in the CodeSwitcher framework.

	PHP output buffer function should be careful to use the ob_start function and the ob_end_clean function.

>> Version Control - Revision Rule

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

	Note: The CodeSwitcher functions can only be called within the framework script.

	Note: csl_mvc::start function only be called within the events script directory index.php file.

	==============================================================
	Get the available version info from the file directory path name in the CodeSwitcher root directory.
	Usage : csl_mvc::version($pathName,$mode);
	Param : string $pathName (path name in framework)
	Param : boolean $mode (returns directory relative path or version number) : Default false
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
	Output >> Directory Relative Path
	==============================================================

	==============================================================
	Get the relative path from the file path name in the CodeSwitcher root directory.
	Usage : csl_mvc::form_path($pathName,$uriMode);
	Param : string $pathName (path name in framework)
	Param : boolean $uriMode (client URI analysis mode) : Default false
	Return : string
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example :
	csl_mvc::form_path('resources/test.js');
	Output >> Server Relative Path
	Example :
	csl_mvc::form_path('resources/test.js',true);
	Output >> Client URI Relative Path
	==============================================================

	==============================================================
	Captures the name of the script event that is currently running.
	Usage : csl_mvc::script_event();
	Return : string
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example : __FILE__ >> /var/www/events/example/index.php
	csl_mvc::script_event();
	Output >> example
	==============================================================

	==============================================================
	Returns the current tester status.
	Usage : csl_mvc::is_tester();
	Return : boolean
	Return Note : FALSE when it fails or does not.
	--------------------------------------------------------------
	Example : On the list of testers.
	csl_mvc::is_tester();
	Output >> TRUE
	Example : Not on the list of testers.
	csl_mvc::is_tester();
	Output >> FALSE
	==============================================================

	==============================================================
	Returns whether the event index page file exists from the events script directory path name in the CodeSwitcher root directory.
	Usage : csl_mvc::is_portal($eventName);
	Param : string $eventName (events script directory path name)
	Return : boolean
	Return Note : FALSE when it fails or does not exist.
	--------------------------------------------------------------
	Example :
	csl_mvc::is_portal('example');
	Output >> TRUE
	Example :
	csl_mvc::is_portal('home');
	Output >> TRUE
	==============================================================

	==============================================================
	Returns whether the event controller file exists from the events script directory path name in the CodeSwitcher root directory.
	Usage : csl_mvc::is_event($eventName);
	Param : string $eventName (events script directory path name)
	Return : boolean
	Return Note : FALSE when it fails or does not exist.
	--------------------------------------------------------------
	Example :
	csl_mvc::is_event('example');
	Output >> TRUE
	Example :
	csl_mvc::is_event('home');
	Output >> TRUE
	==============================================================

	==============================================================
	Returns error log storage status 0 or 1, a temporarily change.
	Usage : csl_mvc::logs($mode);
	Param : boolean $mode (temporarily change mode does not support tester mode) : Default empty
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
	Param : boolean $mode (temporarily change mode does not support tester mode) : Default empty
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
	Usage : csl_mvc::cue_config($model);
	Param : string $model (model name)
	Return : data
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_mvc::cue_config('example');
	Output >> Example
	==============================================================

	==============================================================
	Load create a language object form the CodeSwitcher languages directory.
	Usage : csl_mvc::cue_language($model);
	Param : string $model (model name)
	Return : object
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_mvc::cue_language('en_US');
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
		$language=csl_mvc::cue_language('en_US');
		$language->gets('language_name');
		Output >> English
		$language=csl_mvc::cue_language('en_US');
		$language->gets('language_name',false);
		Output >> English
		**********************************************************
	==============================================================

	==============================================================
	Returns the version number when the script file was loaded form the CodeSwitcher events directory.
	Usage : csl_mvc::start();
	Return : string
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_mvc::start();
	Output >> 1.0.1
	==============================================================

	==============================================================
	Returns the version number when the event file was loaded form the CodeSwitcher events directory.
	Usage : csl_mvc::import_event($model);
	Param : string $model (model name)
	Return : string
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_mvc::import_event('home');
	Output >> 1.0.1
	==============================================================

	==============================================================
	Returns the version number when the model file was loaded form the CodeSwitcher models directory.
	Usage : csl_mvc::import_model($model);
	Param : string $model (model name)
	Return : string
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_mvc::import_model('example');
	Output >> 1.0.1
	==============================================================

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
	Load the page's template file to view the contents from the CodeSwitcher templates directory.
	Usage : csl_mvc::view_template($model,$data,$process);
	Param : string $model (model name)
	Param : array $data (data array) : Default empty
	Param : boolean $process (return content string mode) : Default false
	Return : string
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Returns the version number of the view template content.
	Example :
	csl_mvc::view_template('example',array('text'=>'Content'));
	Output >> 1.0.1
	--------------------------------------------------------------
	A string that returns the contents of the template.
	Example :
	csl_mvc::view_template('example',array('text'=>'Content'),true);
	Output >> Contents String
	==============================================================

	==============================================================
	Get the relative path from the version of the anchor file in the CodeSwitcher resource directory.
	Usage : csl_mvc::resource_path($model,$anchorName,$uriMode);
	Param : string $model (model name)
	Param : string $anchorName (anchor file name at version directory)
	Param : boolean $uriMode (client URI analysis mode) : Default true
	Return : string
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example :
	csl_mvc::resource_path('test','main.js',false);
	Output >> Server Relative Path
	Example :
	csl_mvc::resource_path('test','main.js');
	Output >> Client URI Relative Path
	==============================================================

>> CodeSwitcher System Configuration

	Directory : configs/cs
	--------------------------------------------------------------
	Note: The configs/cs directory does not support test develop mode in the csl_mvc::cue_config or csl_mvc::version function.

	==============================================================
	The introduction page uses the controller script directory name.
	Only the root directory index.php script is supported.
	Example :
	$CS_CONF['INTRO'] = 'home';
	==============================================================

	==============================================================
	Use the timezone identifier, like UTC or Europe/Lisbon to set the default timezone for the script.
	Example :
	$CS_CONF['DEFAULT_TIMEZONE'] = '';
	--------------------------------------------------------------
	Note : The void value automatically uses the PHP default timezone.
	==============================================================

	==============================================================
	Languages xml file version code.
	Example :
	$CS_CONF['LANGUAGE_XML_VERSION'] = '1.0';
	==============================================================

	==============================================================
	Languages xml file encoding code.
	Example :
	$CS_CONF['LANGUAGE_XML_ENCODING'] = 'utf-8';
	==============================================================

	==============================================================
	Error log storage directory location.
	Example :
	$CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'] = '';
	--------------------------------------------------------------
	Note : The void value automatically uses the CodeSwitcher default error_log location /storage/logs.
	Note : Whether the directory exists and whether the directory permissions are writable.
	==============================================================

	==============================================================
	Error stack trace is set to true or false , the system default is false.
	Example :
	$CS_CONF['ERROR_STACK_TRACE_MODE'] = false;
	==============================================================

	==============================================================
	Error log storage mode is set to true or false.
	Example :
	$CS_CONF['ERROR_LOG_MODE'] = false;
	--------------------------------------------------------------
	About Function : csl_mvc::logs($mode);
	Function : Returns error log storage status 0 or 1, a temporarily change.
	Param : boolean $mode (temporarily change mode does not support tester mode)
	Return : integer
	Return Note : Returns FALSE on failure.
	==============================================================

	==============================================================
	Testers debug display mode is set to true or false.
	Example :
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
	Example :
	$CS_CONF['TESTER_DEVELOP_MODE'] = false;
	==============================================================

	==============================================================
	Testers IP source list settings, the localhost is tester.
	Example :
	$CS_CONF['TESTER_IP'][] = '117.108.121.77';
	==============================================================

*/
?>