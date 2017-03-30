<?php
/*
>> Information

	Title		: csl_template function
	Revision	: 3.3
	Notes		:

	Revision History:
	When			Create		When		Edit		Description
	---------------------------------------------------------------------------
	02-25-2016		Poen		04-11-2016	Poen		Create the program.
	08-26-2016		Poen		03-10-2017	Poen		Reforming the program.
	---------------------------------------------------------------------------

>> About

	Template control.

	Load the file output.

	It is possible to execute a return statement inside an included file in order to terminate processing in
	that file and return to the script which called it.

>> Error Stack Trace

	Switch variable parameter is $_SERVER['ERROR_STACK_TRACE'] , stack trace calls will consume memory.

	Stack trace grab file and line echo location.

	Enable : $_SERVER['ERROR_STACK_TRACE']=1;

	Disable : $_SERVER['ERROR_STACK_TRACE']=0;

>> Usage Function

	==============================================================
	Include file
	Usage : include('template/main.inc.php');
	==============================================================

	==============================================================
	View content.
	Usage : csl_template::view($path,$data,$process);
	Param : string $path (template file path)
	Param : array $data (template param data array) : Default void array
	Param : boolean $process (return content string mode) : Default false
	Note : $process is false echo display and the function returns boolean or true returns content string.
	Return : boolean|string
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_template::view('views/example.php',array('content'=>'Test data content.'));
	Output >> TRUE
	Example :
	csl_template::view('views/example.php',array('content'=>'Test data content.'),true);
	Output >> Content String
	==============================================================

>> Create Template PHP File

	File Top :
	Restrict direct display template file.
	-----------------------------------------
	<?php
	class_exists('csl_template') OR exit('No direct script access allowed');
	?>
	-----------------------------------------

	Data Array :
	Use the text data.
	If the data array `Array( 'content' => 'text')` calls the `$content` parameter display text.

	Example :
	-----------------------------------------
	<?php
	class_exists('csl_template') OR exit('No direct script access allowed');//Restrict direct display
	?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Example Template</title>

		<style type="text/css">
		h1 {
			color: #444;
			background-color: transparent;
			font-size: 19px;
			font-weight: normal;
			margin: 0 0 14px 0;
			padding: 14px 15px 10px 15px;
		}
		#body {
			margin: 0 15px 0 15px;
		}
		</style>
	</head>
	<body>
		<h1>Example Template : <?php echo $content;?></h1>
	</html>
	-----------------------------------------

*/
?>