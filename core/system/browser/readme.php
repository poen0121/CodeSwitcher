<?php
/*
>> Information

	Title		: csl_browser function
	Revision	: 2.9.2
	Notes		:

	Revision History:
	When			Create		When		Edit		Description
	---------------------------------------------------------------------------
	09-21-2011		Poen		09-25-2015	Poen		Create the program.
	08-01-2016		Poen		04-11-2017	Poen		Reforming the program.
	04-27-2016		Poen		04-28-2017	Poen		Debug info function.
	04-27-2016		Poen		04-28-2017	Poen		Debug in_source function.
	---------------------------------------------------------------------------

>> About

	Query the user's browser information.

	Device type Approximate values are not exact values.

>> Usage Function

	==============================================================
	Include file
	Usage : include('browser/main.inc.php');
	==============================================================

	==============================================================
	Returns the browser information, if less information will return NULL.
	If the parameter passed to the specified index is invalid return false and an error of level E_USER_WARNING.
	Usage : csl_browser::info($index);
	Param : string $index (information index name)
	Index : $index ######################
		language : browser language
		server : server address
		host : http host
		source : http source
		url : browser URL
		ip : client ip
		proxy : proxy address
		name : browser name
		version : browser version
		os : browser os
		device : user device type (`desktop` , `mobile` , `tablet`)
	#####################################
	Return : string|null
	--------------------------------------------------------------
	Example :
	csl_browser::info('ip');
	==============================================================

	==============================================================
	Verify execute source.
	Usage : csl_browser::in_source();
	Return : boolean
	--------------------------------------------------------------
	Example :
	if(csl_browser::in_source())
	{
		echo 'Welcome!!';
	}
	else
	{
		echo 'Illegal entry.';
	}
	==============================================================

*/
?>