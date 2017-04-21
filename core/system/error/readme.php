<?php
/*
>> Information

	Title		: csl_error function
	Revision	: 2.7.0
	Notes		:

	Revision History:
	When			Create		When		Edit		Description
	---------------------------------------------------------------------------
	07-29-2016		Poen		07-29-2016	Poen		Create the program.
	08-05-2016		Poen		03-27-2017	Poen		Reforming the program.
	04-20-2017		Poen		04-20-2017	Poen		Support CLI normal error output.
	---------------------------------------------------------------------------

>> About

	Throw an error by error_reporting control, and save the log records.

	Automatically grab the file and line echo location can be used to improve the depth of the actual stratification.

	Logs is sent to PHP's system logger, using the Operating System's system logging mechanism or a file,
	depending on what the error_log configuration directive is set.

	Set php.ini display_errors control display error message.

	Set php.ini log_errors control save error message.

>> Stack Trace

	Switch variable parameter is $_SERVER['ERROR_STACK_TRACE'] , stack trace calls will consume memory.

	Stack trace grab file and line echo location.

	Enable : $_SERVER['ERROR_STACK_TRACE'] = On;

	Disable : $_SERVER['ERROR_STACK_TRACE'] = Off;

>> Error Level

	The designated error type for this error. It applies only to error_reporting,
	and will default to E_USER_NOTICE.
	-------------------------------------------------------
	Note :
	E_PARSE,E_ERROR,E_CORE_ERROR,E_COMPILE_ERROR,E_USER_ERROR
	Echo a fatal error message and interrupt EXIT.

	Recommended :
	User-Level [ E_USER_ERROR | E_USER_WARNING | E_USER_NOTICE | E_USER_DEPRECATED ]
	E_USER_ERROR : Echo a fatal error message and interrupt EXIT.
	E_USER_WARNING : Echo a warning message and return TRUE.
	E_USER_NOTICE : Echo a notice message and return TRUE.
	E_USER_DEPRECATED : Echo a deprecated message and return TRUE.

>> Usage Function

	==============================================================
	Include file
	Usage : include('error/main.inc.php');
	==============================================================

	==============================================================
	Throws an error and saves the error log.
	Usage : csl_error::cast($errorMessage,$errno,$echoDepth,$logTitle);
	Param : string $errorMessage (error message)
	Param : integer $errno (error level by error_reporting) : Default E_USER_NOTICE
	Param : integer $echoDepth (location echo depth) : Default 0
	Param : string $logTitle (log title) : Default 'PHP'
	Return : boolean
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example :
	function test($var=null)
	{
		csl_error::cast('Test Error',E_USER_WARNING);//get location on line
	}
	test();
	Example :
	function test($var=null)
	{
		csl_error::cast('Test Error',E_USER_WARNING,1);
	}
	test();//get location on line
	==============================================================

*/
?>