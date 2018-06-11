<?php
/*
>> Information

	Title		: csl_header function
	Revision	: 2.8.2
	Notes		:

	Revision History:
	When			Create		When		Edit		Description
	---------------------------------------------------------------------------
	08-22-2012		Poen		08-22-2012	Poen		Create the program.
	08-12-2016		Poen		08-12-2016	Poen		Reforming the program.
	09-21-2016		Poen		10-13-2016	Poen		Improve location function.
	11-24-2016		Poen		11-24-2016	Poen		Add http function.
	12-05-2016		Poen		07-04-2017	Poen		Improve the program.
	03-27-2017		Poen		03-27-2017	Poen		Fix http function error message.
	04-28-2017		Poen		04-28-2017	Poen		Debug http function.
	02-06-2018		Poen		02-06-2018	Poen		Fix PHP 7 content function to retain original input args.
	---------------------------------------------------------------------------

>> About

	Header-related functions.

>> Usage Function

	==============================================================
	Include file
	Usage : include('header/main.inc.php');
	==============================================================

	==============================================================
	Set header no-cache.
	Usage : csl_header::nocache();
	Return : boolean
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_header::nocache();
	==============================================================

	==============================================================
	Set header http status code.
	Usage : csl_header::http($text,$code);
	Param : string $text (http status text)
	Param : integer $code (http status code)
	Return : boolean
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_header::http('OK',200);
	==============================================================

	==============================================================
	Set header location like form, the function can not be called continuously.
	Usage : csl_header::location($url,$transfer,$target);
	Param : string $url (url string)
	Param : string $transfer (transfer method `GET` or `POST`) : Default GET
	Param : string $target (target mode `_parent` , `_top` , `_self`) : Default _self
	Return : boolean
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_header::location('http://www.example.com/index.php?name=tester','POST','_top');

	Example :
	csl_header::location('./index.php?name=tester','POST','_top');
	==============================================================

*/
?>