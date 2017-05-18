<?php
/*
>> Information

	Title		: csl_mvc function
	Revision	: 1.11.7
	Notes		:

	Revision History:
	When			Create		When		Edit		Description
	---------------------------------------------------------------------------
	03-09-2016		Poen		04-18-2017	Poen		Create the program.
	09-22-2016		Poen		03-30-2017	Poen		Reforming the program.
	04-06-2016		Poen		04-06-2017	Poen		Improve the index function to correct the info on the intro page.
	04-06-2016		Poen		04-06-2017	Poen		Improve the callEvent function.
	04-07-2016		Poen		05-05-2017	Poen		Improve the program code.
	04-20-2017		Poen		04-20-2017	Poen		Support CLI normal error output.
	04-20-2017		Poen		04-20-2017	Poen		Restricting the CLI mode is the tester mode.
	04-24-2017		Poen		04-24-2017	Poen		Confirm that the script information is available.
	04-24-2017		Poen		04-24-2017	Poen		Modify the control error message to throw.
	04-24-2017		Poen		04-24-2017	Poen		Debug run event start.
	04-26-2016		Poen		04-26-2017	Poen		Add the importEvent function.
	05-04-2016		Poen		05-04-2017	Poen		Debug the error 500 loop error.
	05-04-2016		Poen		05-04-2017	Poen		Add the begin program mechanism.
	05-04-2016		Poen		05-04-2017	Poen		Add the commit program mechanism.
	05-05-2016		Poen		05-16-2017	Poen		Improve the begin program mechanism.
	05-05-2016		Poen		05-16-2017	Poen		Improve the commit program mechanism.
	05-05-2016		Poen		05-05-2017	Poen		Debug the viewTemplate function.
	05-05-2016		Poen		05-08-2017	Poen		Debug the $_SERVER['SCRIPT_FILENAME'] realpath.
	05-16-2016		Poen		05-16-2017	Poen		Add the scriptEvent function.
	05-18-2016		Poen		05-18-2017	Poen		Modify the formPath function to add client URI analysis mode.
	05-18-2016		Poen		05-18-2017	Poen		Modify the index function only to exist for detection.
	---------------------------------------------------------------------------

>> About

	The core directory is the program directory of the CodeSwitcher framework.

	The main program file is related to main.inc.php.

>> Main Directory

	1.core : Main program files directory.

	2.core/system : Main program libraries files directory (Function see the readme.php file).

	3.configs : Configs directory support version control mechanism.

	4.configs/CodeSwitcher : This is the system configuration for the CodeSwitcher framework.

	5.languages : Languages directory support version control mechanism.

	6.libraries : Developer libraries directory support version control mechanism.

	7.models : Models directory support version control mechanism.

	8.events : Events script directory relies on the version control mechanism.

	9.templates : Templates directory support version control mechanism.

	10.begin : Control events begin program relies on the version control mechanism.

	11.commit : Control events end program relies on the version control mechanism.

*/
?>