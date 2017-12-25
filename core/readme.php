<?php
/*
>> Information

	Title		: csl_mvc function
	Revision	: 1.21.25
	Notes		:

	Revision History:
	When			Create		When		Edit		Description
	---------------------------------------------------------------------------
	03-09-2016		Poen		04-18-2017	Poen		Create the program.
	09-22-2016		Poen		03-30-2017	Poen		Reforming the program.
	04-06-2016		Poen		04-06-2017	Poen		Improve the index function to correct the info on the intro page.
	04-06-2016		Poen		04-06-2017	Poen		Improve call_event function.
	04-07-2016		Poen		09-20-2017	Poen		Improve the program.
	04-20-2017		Poen		04-20-2017	Poen		Support CLI normal error output.
	04-20-2017		Poen		04-20-2017	Poen		Restricting the CLI mode is the tester mode.
	04-24-2017		Poen		04-24-2017	Poen		Confirm that the script information is available.
	04-24-2017		Poen		04-24-2017	Poen		Modify the control error message to throw.
	04-24-2017		Poen		04-24-2017	Poen		Debug run event start.
	04-26-2016		Poen		04-26-2017	Poen		Add import_event function.
	05-04-2016		Poen		05-04-2017	Poen		Debug the error 500 loop error.
	05-04-2016		Poen		05-04-2017	Poen		Add the begin program mechanism.
	05-04-2016		Poen		05-04-2017	Poen		Add the commit program mechanism.
	05-05-2016		Poen		07-14-2017	Poen		Improve the begin program mechanism.
	05-05-2016		Poen		07-14-2017	Poen		Improve the commit program mechanism.
	05-05-2016		Poen		05-05-2017	Poen		Debug the view_template function.
	05-05-2016		Poen		05-08-2017	Poen		Debug the $_SERVER['SCRIPT_FILENAME'] realpath.
	05-16-2016		Poen		05-16-2017	Poen		Add script_event function.
	05-18-2016		Poen		05-18-2017	Poen		Modify the form_path function to add client URI analysis mode.
	05-18-2016		Poen		05-18-2017	Poen		Modify the index function only to exist for detection.
	05-19-2016		Poen		05-19-2017	Poen		Debug the form_path function by client URI analysis mode.
	05-24-2016		Poen		05-24-2017	Poen		Improve the http state 500 status information flexibility customization.
	05-31-2017		Poen		05-31-2017	Poen		Change the timezone by timezone id.
	05-31-2017		Poen		06-01-2017	Poen		Improve the program initialization error display.
	06-01-2017		Poen		06-01-2017	Poen		Add timezone default mechanism.
	06-01-2017		Poen		08-04-2017	Poen		Fix the program initialization error message.
	06-08-2017		Poen		06-08-2017	Poen		Rename index function to the isPage.
	06-08-2017		Poen		06-08-2017	Poen		Add is_event function.
	06-08-2017		Poen		06-08-2017	Poen		Modify the error log file name format.
	06-08-2017		Poen		06-08-2017	Poen		Rename isPage function to is_portal.
	06-09-2017		Poen		06-09-2017	Poen		Improve is_portal function.
	06-09-2017		Poen		06-09-2017	Poen		Improve is_event function.
	06-09-2017		Poen		06-09-2017	Poen		Modify form_path function error message.
	06-09-2017		Poen		06-09-2017	Poen		Improve form_path function.
	06-21-2017		Poen		06-21-2017	Poen		The system default closes the error stack track.
	06-22-2016		Poen		06-22-2017	Poen		Improve logs function.
	06-22-2016		Poen		06-22-2017	Poen		Improve debug function.
	06-22-2017		Poen		06-22-2017	Poen		Add peel error log mechanism.
	07-14-2017		Poen		07-14-2017	Poen		Fix the program error 500 display.
	07-14-2017		Poen		07-14-2017	Poen		Improve bufferClean function.
	07-14-2017		Poen		07-14-2017	Poen		Modify bufferClean function to public.
	07-14-2017		Poen		07-14-2017	Poen		Add an error status message at the bufferClean function buffer level.
	07-17-2017		Poen		07-17-2017	Poen		Remove bufferClean function.
	08-04-2017		Poen		08-04-2017	Poen		Fix the program begin error message.
	08-04-2017		Poen		08-04-2017	Poen		Fix the program commit error message.
	08-04-2017		Poen		08-04-2017	Poen		Fix the program initialization starting point.
	09-11-2017		Poen		09-11-2017	Poen		Fix the program error log file date.
	11-08-2017		Poen		11-08-2017	Poen		Ass is_tester function.
	---------------------------------------------------------------------------

>> About

	The core directory is the program directory of the CodeSwitcher framework.

	The main program file is related to main.inc.php.

>> Main Directory

	1.core : Main program files directory.

	2.core/system : Main program libraries files directory (Function see the readme.php file).

	3.configs : Configs directory support version control mechanism.

	4.configs/cs : This is the system configuration for the CodeSwitcher framework.

	5.languages : Languages directory support version control mechanism.

	6.libraries : Developer libraries directory support version control mechanism.

	7.models : Models directory support version control mechanism.

	8.events : Events script directory relies on the version control mechanism.

	9.templates : Templates directory support version control mechanism.

	10.begin : Control events begin program relies on the version control mechanism.

	11.commit : Control events end program relies on the version control mechanism.

	12.storage : System storage directory.

	13.storage/logs : System error logs storage directory.

>> Note

	PHP headers_sent function can not work in the CodeSwitcher framework.

	PHP output buffer function should be careful to use the ob_start function and the ob_end_clean function.

*/
?>