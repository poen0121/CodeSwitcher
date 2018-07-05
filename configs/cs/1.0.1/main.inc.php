<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php
$CS_CONF = array ();
/*
 ==============================================================
 The introduction page uses the controller script directory name.
 Only the root directory index.php script is supported.
 Example :
 $CS_CONF['INTRO'] = 'home';
 ==============================================================
 */
$CS_CONF['INTRO'] = 'home';
/*
 ==============================================================
 Use the timezone identifier, like UTC or Europe/Lisbon to set the default timezone for the script.
 Example :
 $CS_CONF['DEFAULT_TIMEZONE'] = '';
 --------------------------------------------------------------
 Note : The void value automatically uses the PHP default timezone.
 ==============================================================
 */
$CS_CONF['DEFAULT_TIMEZONE'] = 'Asia/Taipei';
/*
 ==============================================================
 Languages xml file version code.
 Example :
 $CS_CONF['LANGUAGE_XML_VERSION'] = '1.0';
 ==============================================================
 */
$CS_CONF['LANGUAGE_XML_VERSION'] = '1.0';
/*
 ==============================================================
 Languages xml file encoding code.
 Example :
 $CS_CONF['LANGUAGE_XML_ENCODING'] = 'utf-8';
 ==============================================================
 */
$CS_CONF['LANGUAGE_XML_ENCODING'] = 'utf-8';
/*
 ==============================================================
 Error log storage directory location.
 Example :
 $CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'] = '';
 --------------------------------------------------------------
 Note : The void value automatically uses the CodeSwitcher default error_log location /storage/logs.
 Note : Whether the directory exists and whether the directory permissions are writable.
 ==============================================================
 */
$CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'] = '';
/*
 ==============================================================
 Error stack trace is set to true or false , the system default is false.
 Example :
 $CS_CONF['ERROR_STACK_TRACE_MODE'] = false;
 ==============================================================
 */
$CS_CONF['ERROR_STACK_TRACE_MODE'] = true;
/*
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
 */
$CS_CONF['ERROR_LOG_MODE'] = true;
/*
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
 */
$CS_CONF['TESTER_DEBUG_MODE'] = true;
/*
 ==============================================================
 Testers develop mode is set to true or false.
 Example :
 $CS_CONF['TESTER_DEVELOP_MODE'] = false;
 ==============================================================
 */
$CS_CONF['TESTER_DEVELOP_MODE'] = true;
/*
 ==============================================================
 Testers IP source list settings, the localhost is tester.
 Example :
 $CS_CONF['TESTER_IP'][] = '117.108.121.77';
 ==============================================================
 */
#$CS_CONF['TESTER_IP'][] = '117.108.121.77';

//==============================================================
return $CS_CONF;
?>
