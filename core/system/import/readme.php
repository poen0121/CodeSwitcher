<?php
/*
>> Information

	Title		: csl_import function
	Revision	: 2.9.1
	Notes		:

	Revision History:
	When			Create		When		Edit		Description
	---------------------------------------------------------------------------
	03-23-2016		Poen		03-23-2016	Poen		Create the program.
	08-01-2016		Poen		03-10-2017	Poen		Reforming the program.
	04-20-2017		Poen		04-20-2017	Poen		Support CLI normal error output.
	06-21-2017		Poen		06-21-2017	Poen		Fix error log time and line breaks.
	---------------------------------------------------------------------------

>> About

	Import file.

	Handling Returns: include returns FALSE on failure and raises a warning. Successful includes,
	unless overridden by the included file, return 1.

	It is possible to execute a return statement inside an included file in order to terminate processing in
	that file and return to the script which called it.

	Also, it's possible to return values from included files.

>> Error Stack Trace

	Switch variable parameter is $_SERVER['ERROR_STACK_TRACE'] , stack trace calls will consume memory.

	Stack trace grab file and line echo location.

	Enable : $_SERVER['ERROR_STACK_TRACE']=1;

	Disable : $_SERVER['ERROR_STACK_TRACE']=0;

>> Usage Function

	==============================================================
	Include file
	Usage : include('import/main.inc.php');
	==============================================================

	==============================================================
	Importing a file function for include.
	Usage : csl_import::from($file);
	Param : string $file (file path)
	Return : boolean|data
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_import::from('C:\\test.php');
	==============================================================

	==============================================================
	Importing a file function for include_once.
	Usage : csl_import::from_once($file);
	Param : string $file (file path)
	Return : boolean|data
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_import::from_once('C:\\test.php');
	==============================================================

*/
?>