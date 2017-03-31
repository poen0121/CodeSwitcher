<?php
/*
>> Information

	Title		: csl_debug function
	Revision	: 3.0.0
	Notes		:

	Revision History:
	When			Create		When		Edit		Description
	---------------------------------------------------------------------------
	10-20-2010		Poen		10-20-2010	Poen		Create the program.
	08-17-2016		Poen		02-22-2017	Poen		Reforming the program.
	---------------------------------------------------------------------------

>> About

	Debug the operation mode.

>> Usage Function

	==============================================================
	Include file
	Usage : include('debug/main.inc.php');
	==============================================================

	==============================================================
	Set PHP errors report mode.
	Usage : csl_debug::report($switch);
	Param : boolean $switch (open or close the report error mode) : Default true
	Note : $switch `true` is open the report error types E_ALL.
    Note : $switch `false` is close the report error types 0.
	Return : boolean
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example : Open the report error.
 	csl_debug::report(true);
 	Output >> TRUE
 	Example : Close the report error.
 	csl_debug::report(false);
 	Output >> TRUE
	==============================================================

	==============================================================
	Check the PHP error reporting mode is strictly of type E_ALL.
	Usage : csl_debug::is_all_report();
	Return : boolean
	--------------------------------------------------------------
	Example :
	csl_debug::report(true);
 	csl_debug::is_all_report();
 	Output >> TRUE
	==============================================================

	==============================================================
	Check the PHP error reporting mode is strictly of type 0.
	Usage : csl_debug::is_close_report();
	Return : boolean
	--------------------------------------------------------------
	Example :
	csl_debug::report(false);
	csl_debug::is_close_report();
	Output >> TRUE
	==============================================================

	==============================================================
	Set PHP errors report display mode.
	Usage : csl_debug::display($switch);
	Param : boolean $switch (open or close the report display mode) : Default true
	Note : $switch `true` is open the report display.
	Note : $switch `false` is close the report display.
	Return : boolean
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example : Open the report display.
	csl_debug::display(true);
	Output >> TRUE
	Example : Close the report display.
	csl_debug::display(false);
	Output >> TRUE
	==============================================================

	==============================================================
	Check the PHP error report display mode is open.
	Usage : csl_debug::is_display();
	Return : boolean
	--------------------------------------------------------------
	Example :
	csl_debug::display(true);
	csl_debug::is_display();
	Output >> TRUE
	==============================================================

	==============================================================
	Set PHP log errors to specified default file.
	Usage : csl_debug::error_log_file($path);
	Param : string $path (file path)
	Return : boolean
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example :
	csl_debug::error_log_file('./test.log');
	Output >> TRUE
	Example :
	csl_debug::error_log_file('./test_log');
	Output >> TRUE
	Example :
	csl_debug::error_log_file('http://example/test_log');
	Output >> FALSE
	Example :
	csl_debug::error_log_file('./');
	Output >> FALSE
	==============================================================

	==============================================================
	Set system error log storage.
	Note : Uncontrolled error_log function.
	Usage : csl_debug::record($switch);
	Param : boolean $switch (open or close the logs storage) : Default true
	Note : $switch `true` is open the logs storage.
	Note : $switch `false` is close the logs storage.
	Return : boolean
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example : Open the logs storage.
	csl_debug::record(true);
	Output >> TRUE
	Example : Close the logs storage.
	csl_debug::record(false);
	Output >> TRUE
	==============================================================

	==============================================================
	Check the PHP error log storage mode is open.
	Usage : csl_debug::is_record();
	Return : boolean
	--------------------------------------------------------------
	Example :
	csl_debug::record(true);
	csl_debug::is_record();
	Output >> TRUE
	==============================================================

*/
?>