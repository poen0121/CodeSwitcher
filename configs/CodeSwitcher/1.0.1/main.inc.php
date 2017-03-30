<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php
/*
 ==============================================================
 The introduction page uses the controller script directory name.
 Only the root directory index.php script is supported.
 Example:
 $CS_CONF['INTRO'] = 'home';
 ==============================================================
 */
$CS_CONF['INTRO'] = 'home';
/*
 ==============================================================
 Set the default host time zone for the script with a value of -12 ~ 14 by GMT.
 Example:
 Taipei time zone :
 $CS_CONF['DEFAULT_TIMEZONE'] = 8;
 ==============================================================
 */
$CS_CONF['DEFAULT_TIMEZONE'] = 8;
/*
 ==============================================================
 Languages xml file version code.
 Example:
 $CS_CONF['LANGUAGE_XML_VERSION'] = '1.0';
 ==============================================================
 */
$CS_CONF['LANGUAGE_XML_VERSION'] = '1.0';
/*
 ==============================================================
 Languages xml file encoding code.
 Example:
 $CS_CONF['LANGUAGE_XML_ENCODING'] = 'utf-8';
 ==============================================================
 */
$CS_CONF['LANGUAGE_XML_ENCODING'] = 'utf-8';
/*
 ==============================================================
 Error stack trace is set to true or false.
 Example:
 $CS_CONF['ERROR_STACK_TRACE_MODE'] = false;
 ==============================================================
 */
$CS_CONF['ERROR_STACK_TRACE_MODE'] = true;
/*
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
 */
$CS_CONF['ERROR_LOG_MODE'] = true;
/*
 ==============================================================
 Error log storage directory location.
 Example:
 $CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'] = '';
 --------------------------------------------------------------
 Note : The void value automatically uses the php.ini error_log location.
 Note : Whether the directory exists and whether the directory permissions are writable.
 ==============================================================
 */
$CS_CONF['ERROR_LOG_STORAGE_DIR_LOCATION'] = csl_mvc :: formPath('/storage/logs');
/*
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
 */
$CS_CONF['TESTER_DEBUG_MODE'] = true;
/*
 ==============================================================
 Testers develop mode is set to true or false.
 Example:
 $CS_CONF['TESTER_DEVELOP_MODE'] = false;
 ==============================================================
 */
$CS_CONF['TESTER_DEVELOP_MODE'] = true;
/*
 ==============================================================
 Testers IP source list settings, the localhost is tester.
 Example:
 $CS_CONF['TESTER_IP'][] = '117.108.121.77';
 ==============================================================
 */
#$CS_CONF['TESTER_IP'][] = '117.108.121.77';

//==============================================================
return $CS_CONF;
?>