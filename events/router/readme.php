<?php
/*
>> Information

	Title		: router controller
	Revision	: 1.0.1
	Notes		:

	Revision History:
	When			Create		When		Edit		Description
	---------------------------------------------------------------------------
	07-10-2017		Poen		03-05-2018	Poen		Create the program.
	---------------------------------------------------------------------------

>> About

	Hide script file path information, through the router control script.

	The developer uses the execution commands used by the router profile definition.

>> Constant

	ROUTER_URL_VAR : The name of the receiving parameter for the URL.

>> Enabled

	Directory : configs/cs

	Set the CodeSwitcher framework introduction page using the controller script directory name.

	$CS_CONF['INTRO'] = 'router';

>> Main Directory

	1.configs/routes : system configuration for the routes.

	2.events/router : router controller script.

	3.templates/error/400 : system error templates.

	4.templates/error/500 : system error templates.

>> Configuration

	Directory : configs/routes

	==============================================================
	Set the URL to receive the parameter name for the route event controller.
	Example :
	define('ROUTER_URL_VAR', 'show');
	==============================================================

	==============================================================
	Defines the name of the function to which the route points.
	$ROUTES_CONF[ Route Name ] = Command Array;
	Route Name : The value passed through the URL.
	Command Array : Execute the command.
	Command Format : array ('-e' => event path name, '-c' => class name, '-f' => function name, '-a' => function argument array);
	Example:
	$ROUTES_CONF['example'] = array ('-e' => 'example', '-c' => 'example_c', '-f' => 'index', '-a' => array ('test', 'go'));
	Analyze :
	Route Name => example
	Command -e event path name => example
	Command -c class name => example_c
	Command -f function name => index
	Command -a function argument 1 => test
	Command -a function argument 2 => go
	==============================================================

>> URL

	Format : www.example.com/index.php?ROUTER_URL_VAR=[routes index]

	Example : www.example.com/index.php?show=example

>> Note

	Routes config : $ROUTES_CONF[]

	Command -e event path name is must exist.

	Command -c class name can be customized to use.

	Command -f function name can be customized to use.

	Command -a function argument array can be customized to use.

>> Event Script Development

	File Structure :

	events
	└── main directory
		├── 1.0.1
		│	└── main.inc.php
	  	└── ini
			└── version.php

	Step 1 : Create a main directory.

	Step 2 : Create `ini` directory.

	Step 3 : Create limit version file `ini/version.php`.
	Write at the top of the file :
	-----------------------------------------------------
	<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
	-----------------------------------------------------
	eg :
	-----------------------------------------------------
	<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
	<?php
	return '1.0.1';
	?>
	-----------------------------------------------------

	Step 4 : Create a directory such as `1.0.1` version.

	Step 5 : Create a release directory `1.0.1/main.inc.php` master file and coding logic mechanisms.
	Write at the top of the file :
	-----------------------------------------------------
	<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
	-----------------------------------------------------

	Step 6 : Coding mechanisms.

	#Normal Mode :
	-----------------------------------------------------
	<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
	<?php
		coding ...
	?>
	-----------------------------------------------------

	#Function Mode :
	-----------------------------------------------------
	<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
	<?php
		function funs ( $argument ){
			coding ...
		}
	?>
	-----------------------------------------------------
	1.Build function related code.
		function funs (){
			coding ...
		}
		Command -f function name can be customized to use.
	2.Build function argument related code.
		function funs ( $argument ){
			coding ...
		}
		Command -a function argument array can be customized to use.
	-----------------------------------------------------

	#Class Object Mode :
	-----------------------------------------------------
	<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
	<?php
		Class Object {
			public function funs ( $argument ){
				coding ...
			}
		}
	?>
	-----------------------------------------------------
	1.Build class object related code.
		Class Object {
			coding ...
		}
		Command -c class name can be customized to use.
	2.Build class object public function related code.
		Class Object {
			public function funs (){
				coding ...
			}
		}
		Command -f function name can be customized to use.
	3.Build class object public function argument related code.
		Class Object {
			public function funs ( $argument ){
				coding ...
			}
		}
		Command -a function argument array can be customized to use.
	-----------------------------------------------------

 */
?>
