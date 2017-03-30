<?php
/*
>> Information

	Title		: csl_header function
	Revision	: 2.6
	Notes		:

	Revision History:
	When			Create		When		Edit		Description
	---------------------------------------------------------------------------
	08-22-2012		Poen		08-22-2012	Poen		Create the program.
	08-12-2016		Poen		03-27-2017	Poen		Reforming the program.
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