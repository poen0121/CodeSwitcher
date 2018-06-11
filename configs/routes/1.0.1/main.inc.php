<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php
/*
 ==============================================================
 Set the URL to receive the parameter name for the route event controller.
 Example :
 define('ROUTER_URL_VAR', 'show');
 ==============================================================
 */
define('ROUTER_URL_VAR', 'show');
/*
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
 */
$ROUTES_CONF[''] = array ('-e' => 'home');
$ROUTES_CONF['example'] = array ('-e' => 'example', '-c' => 'example_c', '-f' => 'index', '-a' => array ('test', 'go'));

//==============================================================
return $ROUTES_CONF;
?>
