<?php
/*
>> Information

	Title		: csl_path function
	Revision	: 3.8.1
	Notes		: You can use chdir() change current script parent directories.

	Revision History:
	When			Create		When		Edit		Description
	---------------------------------------------------------------------------
	04-02-2010		Poen		05-28-2015	Poen		Create the program.
	10-19-2016		Poen		04-21-2017	Poen		Reforming the program.
	---------------------------------------------------------------------------

>> About

	Processing file path.

>> Usage Function

	==============================================================
	Include file
	Usage : include('path/main.inc.php');
	==============================================================

	==============================================================
	Change path to norm path.
	Usage : csl_path::norm($path);
	Param : string $path (path)
	Return : string
	--------------------------------------------------------------
	Example :
	csl_path::norm('\var\www\index.php');
	Output >> /var/www/index.php
	==============================================================

	==============================================================
	Count actual arrive path's `../` relative layer number.
	Usage : csl_path::relative_layer_count($path);
	Param : string $path (relative path)
	Return : integer
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example : __FILE__ >> /var/www/path/index.php
	csl_path::relative_layer_count('index.php');
	Output >> 0
	Example : __FILE__ >> /var/www/path/index.php
	csl_path::relative_layer_count('../index.php');
	Output >> 1
	==============================================================

	==============================================================
	Get the host path's nexus relative full path based on the current script and document root target script.
	Usage : csl_path::nexus_full_relative($path,$script_path);
	Param : string $path (path)
	Param : string $scriptName (script name)
	Return : string
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example : __FILE__ >> /var/www/path/index.php
	csl_path::nexus_full_relative('index.php','deep/test/script.php');
	Output >> ../../path/index.php ( Position : /var/www/path/index.php )
	Example : __FILE__ >> /var/www/path/index.php
	csl_path::nexus_full_relative('../index.php','deep/test/script.php');
	Output >> ../../index.php ( Position : /var/www/index.php )
	Example : __FILE__ >> /var/www/path/index.php
	csl_path::nexus_full_relative(__FILE__,'deep/test/script.php');
	Output >> ../../path/index.php ( Position : /var/www/path/index.php )
	csl_path::nexus_full_relative('../../index.php','deep/test/script.php');
	Output >> ../../../index.php ( Position : /var/index.php )
	==============================================================

	==============================================================
	Get the host path's relative full path based on the current script.
	Usage : csl_path::full_relative($path);
	Param : string $path (path)
	Return : string
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example : __FILE__ >> /var/www/path/index.php
	csl_path::full_relative('index.php');
	Output >> ../path/index.php ( Position : /var/www/path/index.php )
	Example : __FILE__ >> /var/www/path/index.php
	csl_path::full_relative('../index.php');
	Output >> ../index.php ( Position : /var/www/index.php )
	Example : __FILE__ >> /var/www/path/index.php
	csl_path::full_relative(__FILE__);
	Output >> ../path/index.php ( Position : /var/www/path/index.php )
	Example : __FILE__ >> /var/www/path/index.php
	csl_path::full_relative('../../index.php');
	Output >> ../../index.php ( Position : /var/index.php )
	==============================================================

	==============================================================
	Get the host path's script name based on the current script and document root.
	Usage : csl_path::script($path);
	Param : string $path (path)
	Return : string
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example : __FILE__ >> /var/www/path/index.php
	csl_path::script('index.php');
	Output >> /path/index.php
	Example : __FILE__ >> /var/www/path/index.php
	csl_path::script('../index.php');
	Output >> /index.php
	Example : __FILE__ >> /var/www/path/index.php
	csl_path::script(__FILE__);
	Output >> /path/index.php
	==============================================================

	==============================================================
	Get the target script's relative path based on the current script and document root.
	Usage : csl_path::relative($scriptName);
	Param : string $scriptName (script name)
	Return : string
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example : __FILE__ >> /var/www/path/index.php
	csl_path::relative('index.php');
	Output >> ../index.php
	Example : __FILE__ >> /var/www/path/index.php
	csl_path::relative('../../index.php');
	Output >> ../index.php
	Example : __FILE__ >> /var/www/path/index.php
	csl_path::relative('./index.php');
	Output >> ../index.php
	Example : __FILE__ >> /var/www/path/index.php
	csl_path::relative(__FILE__);
	Output >> ../path/index.php
	==============================================================

	==============================================================
	Get the target script's absolute path based on the document root.
	Usage : csl_path::absolute($scriptName);
	Param : string $scriptName (script name)
	Return : string
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example : http://example & __FILE__ >> /var/www/path/index.php
	csl_path::absolute('index.php');
	Output >> http://example/index.php
	Example : http://example & __FILE__ >> /var/www/path/index.php
	csl_path::absolute('../index.php');
	Output >> http://example/index.php
	Example : http://example & __FILE__ >> /var/www/path/index.php
	csl_path::absolute(__FILE__);
	Output >> http://example/path/index.php
	==============================================================

	==============================================================
	Returns a parent directory's script name based on the document root.
	Usage : csl_path::cutdir($scriptName);
	Param : string $scriptName (script name)
	Return : string
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example : __FILE__ >> /var/www/path/index.php
	csl_path::cutdir('index.php');
	Output >> /
	Example : __FILE__ >> /var/www/path/index.php
	csl_path::cutdir('../../index.php');
	Output >> /
	Example : __FILE__ >> /var/www/path/index.php
	csl_path::cutdir(__FILE__);
	Output >> /path/
	==============================================================

	==============================================================
	Returns the full path to arrive.
	Usage : csl_path::arrive($path);
	Param : string $path (path)
	Param : boolean $mode (keep empty string directory) : Default false
	Return : string
	--------------------------------------------------------------
	Example :
	csl_path::arrive('../index.php');
	Output >> ../index.php
	Example :
	csl_path::arrive('');
	Output >> ./
	Example :
	csl_path::arrive('../path/../index.php');
	Output >> ../index.php
	Example : __FILE__ >> /var/www/path/index.php
	csl_path::arrive(__FILE__);
	Output >> ./path/index.php
	Example : __FILE__ >> /var/www/path/index.php
	csl_path::arrive(__DIR__.'/../index.php');
	Output >> ./index.php
	Example : __FILE__ >> /var/www/path/index.php
	csl_path::arrive(__DIR__.'/../../index.php');
	Output >> ../index.php
	Example :
	csl_path::arrive('http://example.com/path/../index.php');
	Output >> http://example.com/index.php
	Example :
	csl_path::arrive('http://example.com/./index.php');
	Output >> http://example.com/index.php
	Example :
	csl_path::arrive('http://example.com/////index.php');
	Output >> http://example.com/index.php
	==============================================================

	==============================================================
	Get the correct full path script name.
	Usage : csl_path::clean($path);
	Param : string $path (path)
	Param : boolean $mode (keep empty string directory) : Default false
	Return : string
	--------------------------------------------------------------
	Example :
	csl_path::clean('../../../index.php');
	Output >> /index.php
	Example :
	csl_path::clean('');
	Output >> /
	Example :
	csl_path::clean('../path/../index.php');
	Output >> /path/index.php
	Example :
	csl_path::clean('../path/////index.php');
	Output >> /path/index.php
	Example : __FILE__ >> /var/www/path/index.php
	csl_path::clean(__DIR__.'/../../../index.php');
	Output >> /path/index.php
	Example :
	csl_path::clean('http://example.com/../index.php');
	Output >> http://example.com/index.php
	Example :
	csl_path::clean('http://example.com/./index.php');
	Output >> http://example.com/index.php
	Example :
	csl_path::clean('http://example.com/////index.php');
	Output >> http://example.com/index.php
	==============================================================

	==============================================================
	Get document root directory path.
	Usage : csl_path::document_root();
	Return : string
	--------------------------------------------------------------
	Example :
	csl_path::document_root();
	==============================================================

	==============================================================
	Check path format is relative path.
	Usage : csl_path::is_relative($path);
	Param : string $path (path)
	Return : boolean
	--------------------------------------------------------------
	Example :
	csl_path::is_relative('index.php');
	Output >> TRUE
	Example :
	csl_path::is_relative('/index.php');
	Output >> TRUE
	Example :
	csl_path::is_relative('./index.php');
	Output >> TRUE
	Example :
	csl_path::is_relative('../index.php');
	Output >> TRUE
	==============================================================

	==============================================================
	Check path format is absolute path.
	Usage : csl_path::is_absolute($path);
	Param : string $path (path)
	Return : boolean
	--------------------------------------------------------------
	Example :
	csl_path::is_absolute('http://example/index.php');
	Output >> TRUE
	==============================================================

	==============================================================
	Check path format is document root model path.
	Usage : csl_path::is_root_model($path);
	Param : string $path (path)
	Return : boolean
	--------------------------------------------------------------
	Example :
	csl_path::is_root_model(__FILE__);
	Output >> TRUE
	==============================================================

	==============================================================
	Check path format is file path.
	Usage : csl_path::is_files($path);
	Param : string $path (path)
	Return : boolean
	--------------------------------------------------------------
	Example :
	csl_path::is_files(__FILE__);
	Output >> TRUE
	Example :
	csl_path::is_files('./index.php');
	Output >> TRUE
	Example :
	csl_path::is_files('./index.php/');
	Output >> FALSE
	Example :
	csl_path::is_files('http://example/index.php');
	Output >> TRUE
	Example :
	csl_path::is_files('http://example/index.php/');
	Output >> TRUE
	==============================================================

	==============================================================
	Check path is self current script location.
	Usage : csl_path::is_self($path);
	Param : string $path (path)
	Return : boolean
	--------------------------------------------------------------
	Example : __FILE__ >> /var/www/path/index.php
	csl_path::is_self(__FILE__);
	Output >> TRUE
	Example : __FILE__ >> /var/www/path/index.php
	csl_path::is_self('./index.php');
	Output >> TRUE
	Example : __FILE__ >> /var/www/path/index.php
	csl_path::is_self('./index.php/');
	Output >> FALSE
	Example : http://example & __FILE__ >> /var/www/path/index.php
	csl_path::is_self('http://example/index.php');
	Output >> FALSE
	Example : http://example & __FILE__ >> /var/www/path/index.php
	csl_path::is_self('http://example/path/index.php');
	Output >> TRUE
	==============================================================

*/
?>