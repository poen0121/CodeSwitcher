<?php
/*
>> Information

	Title		: csl_file function
	Revision	: 4.4.2
	Notes		:

	Revision History:
	When			Create		When		Edit		Description
	---------------------------------------------------------------------------
	02-04-2010		Poen		05-28-2015	Poen		Create the program.
	08-05-2016		Poen		08-05-2016	Poen		Reforming the program.
	08-22-2016		Poen		08-22-2016	Poen		Change basename function name become fullname.
	08-23-2016		Poen		08-23-2016	Poen		Change fullname function $query_keep default false.
	09-01-2016		Poen		09-01-2016	Poen		Add save function $mode options.
	09-01-2016		Poen		09-01-2016	Poen		Add load function $mode parameter.
	09-01-2016		Poen		09-05-2016	Poen		Improve load function.
	09-01-2016		Poen		09-01-2016	Poen		Improve size function.
	09-01-2016		Poen		09-02-2016	Poen		Add unit2size function.
	09-01-2016		Poen		09-02-2016	Poen		Improve size2unit function.
	09-01-2016		Poen		09-02-2016	Poen		Improve unit2min function.
	09-01-2016		Poen		09-02-2016	Poen		Improve unit2max function.
	09-01-2016		Poen		09-01-2016	Poen		Remove content_replace function.
	09-01-2016		Poen		09-13-2016	Poen		Improve save function.
	09-05-2016		Poen		09-05-2016	Poen		Improve create_dir function.
	09-06-2016		Poen		09-06-2016	Poen		Debug unit string preg_match regular expression.
	09-07-2016		Poen		09-07-2016	Poen		Improve memory function.
	09-07-2016		Poen		09-07-2016	Poen		Change create_dir function name become mk_dir.
	09-08-2016		Poen		07-04-2017	Poen		Improve the program.
	09-08-2016		Poen		09-08-2016	Poen		Change get_mode function name become get_mod.
	09-08-2016		Poen		09-08-2016	Poen		Change set_mode function name become ch_mod.
	09-14-2016		Poen		09-14-2016	Poen		Debug save function.
	09-14-2016		Poen		09-14-2016	Poen		Debug mk_dir function.
	09-20-2016		Poen		09-20-2016	Poen		Debug memory function.
	09-29-2016		Poen		11-21-2016	Poen		Debug the program error messages.
	09-30-2016		Poen		09-30-2016	Poen		Debug clearstatcache().
	02-22-2017		Poen		02-22-2017	Poen		Debug get_mod function.
	02-22-2017		Poen		02-22-2017	Poen		Debug ch_mod function.
	02-22-2017		Poen		02-22-2017	Poen		Debug directory function.
	02-22-2017		Poen		02-22-2017	Poen		Debug size function.
	02-22-2017		Poen		02-22-2017	Poen		Debug load function.
	02-22-2017		Poen		02-22-2017	Poen		Debug save function.
	06-07-2017		Poen		06-07-2017	Poen		Debug size2unit function.
	06-07-2017		Poen		06-07-2017	Poen		Debug unit2min function.
	06-07-2017		Poen		06-07-2017	Poen		Debug unit2max function.
	---------------------------------------------------------------------------

>> About

	File operations.

>> Usage Function

	==============================================================
	Include file
	Usage : include('file/main.inc.php');
	==============================================================

	==============================================================
	Get localhost file permissions code.
	Usage : csl_file::get_mod($path);
	Param : string $path (file path)
	Return : string
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_file::get_mod('./test.txt');
	==============================================================

	==============================================================
	Set localhost file on permissions.
	Usage : csl_file::ch_mod($path,$power);
	Param : string $path (file path)
	Param : integer $power (file permissions number three octal)
	Return : boolean
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_file::ch_mod('./test.txt',0755);
	==============================================================

	==============================================================
	Get the script file memory currently in use.
	Usage : csl_file::memory($type);
	Note : Set type mode to TRUE to get the real size of memory allocated from system.
	Note : If not set or FALSE only the memory used by emalloc() is reported.
	Param : boolean $type (type mode) : Default false
	Return : string
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_file::memory();
	==============================================================

	==============================================================
	Get path's file full name.
	Usage : csl_file::fullname($path);
	Param : string $path (path)
	Param : string $query_keep (after the question mark ? data keep mode) : Default false
	Return : string
	--------------------------------------------------------------
	Example :
	csl_file::fullname('./test.php?id=101',true);
	Output >> test.php?id=101
	Example :
	csl_file::fullname('./test.php?id=101',false);
	Output >> test.php
	==============================================================

	==============================================================
	Get path's file name.
	Usage : csl_file::name($path);
	Param : string $path (path)
	Return : string
	--------------------------------------------------------------
	Example :
	csl_file::name('./test.php');
	Output >> test
	==============================================================

	==============================================================
	Get path's file extension.
	Usage : csl_file::extension($path);
	Param : string $path (path)
	Return : string
	--------------------------------------------------------------
	Example :
	csl_file::extension('./test.php');
	Output >> php
	==============================================================

	==============================================================
	Get path's file directory.
	Usage : csl_file::directory($path);
	Param : string $path (path)
	Return : string
	--------------------------------------------------------------
	Example :
	csl_file::directory('./test.php');
	Output >> ./
	==============================================================

	==============================================================
	Get unit format size estimate data size.
	Note : unit Byte|KB|MB|GB|TB|PB|EB|ZB|YB max 1024 YB
	Usage : csl_file::unit2size($unitSize);
	Param : string $unitSize (bytes unit size)
	Return : double
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_file::unit2size('1.00 KB');
	Output >> 1024
	==============================================================

	==============================================================
	Get data size estimate unit format size.
	Note : unit Byte|KB|MB|GB|TB|PB|EB|ZB|YB max 1024 YB
	Usage : csl_file::size2unit($size);
	Param : double $size (bytes size number)
	Return : string
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_file::size2unit(1024.00);
	Output >> 1.00 KB
	==============================================================

	==============================================================
	Get data size unit format estimate min unit size.
	Note : unit Byte|KB|MB|GB|TB|PB|EB|ZB|YB max 1024 YB
	Usage : csl_file::unit2min($unitSize);
	Param : string $unitSize (bytes unit size)
	Return : string
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_file::unit2min('1.00 KB');
	Output >> 1,024 Byte
	==============================================================

	==============================================================
	Get data size unit format estimate max unit size.
	Note : unit Byte|KB|MB|GB|TB|PB|EB|ZB|YB max 1024 YB
	Usage : csl_file::unit2max($unitSize);
	Param : string $unitSize (bytes unit size)
	Return : string
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_file::unit2max('1,024 Byte');
	Output >> 1.00 KB
	==============================================================

	==============================================================
	Get localhost file's estimate unit format size.
	Note : unit Byte|KB|MB|GB|TB|PB|EB|ZB|YB max 1024 YB
	Usage : csl_file::size($path);
	Param : string $path (file path)
	Return : string
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_file::size('./test.php');
	==============================================================

	==============================================================
	Load localhost file contents.
	Usage : csl_file::load($path,$mode,$lock);
	Param : string $path (file path)
	Param : string $mode (mode to the stream r,rb) : Default r
	$mode :
	'r' 	>> Open for reading only; place the file pointer at the beginning of the file.
	'rb' 	>> Open for reading only; place the bit file pointer at the beginning of the file.
	Param : boolean $lock (lock file mode) : Default true
	Return : string
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_file::load('./test.php');
	==============================================================

	==============================================================
	Save the file contents to the localhost.
	Usage : csl_file::save($path,$content,$mode,$lock);
	Param : string $path (file path)
	Param : string $content (content)
	Param : string $mode (mode to the stream w,a,wb,ab) : Default w
	$mode :
	'w' 	>> Open for writing only; place the file pointer at the beginning of the file and truncate the file to zero length. If the file does not exist, attempt to create it.
	'a' 	>> Open for writing only; place the file pointer at the end of the file. If the file does not exist, attempt to create it. In this mode, fseek() has no effect, writes are always appended.
	'wb' 	>> Open for writing only; place the bit file pointer at the beginning of the file and truncate the file to zero length. If the file does not exist, attempt to create it.
	'ab' 	>> Open for writing only; place the bit file pointer at the end of the file. If the file does not exist, attempt to create it. In this mode, fseek() has no effect, writes are always appended.
	Param : boolean $lock (lock file mode) : Default true
	Return : boolean
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_file::save('./test.php','test content');
	==============================================================

	==============================================================
	Create a directory to the localhost , if the directory exists will try to modify the permissions.
	Usage : csl_file::mk_dir($dir,$power);
	Param : string $dir (directory path)
	Param : integer $power (directory permissions number three octal) : Default 0755
	Return : boolean
	Return Note : Returns FALSE on error.
	--------------------------------------------------------------
	Example :
	csl_file::mk_dir('./test/');
	==============================================================

*/
?>