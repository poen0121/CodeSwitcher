<?php
/*
>> Intro Page

	The system automatically loads the introductory page by CodeSwitcher configuration.

	Note :
	If you want to develop API you can delete this file.

	About : configs/CodeSwitcher directory.
	==============================================================
	The introduction page uses the controller script name.
	Example:
	$CS_CONF['INTRO']='home';
	==============================================================
 */
chdir(dirname(__FILE__));
include ('./core/main.inc.php');
csl_mvc :: callEvent();
?>
