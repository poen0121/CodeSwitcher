<?php
/*
>> Information

	Title		: csl_time function
	Revision	: 2.12.4
	Notes		:

	Revision History:
	When			Create		When		Edit		Description
	---------------------------------------------------------------------------
	05-30-2011		Poen		05-30-2011	Poen		Create the program.
	08-09-2016		Poen		08-12-2016	Poen		Reforming the program.
	08-31-2016		Poen		08-31-2016	Poen		Improve part_days function.
	08-31-2016		Poen		08-31-2016	Poen		Debug part_days function.
	09-21-2016		Poen		09-21-2016	Poen		Debug get_datetime function.
	09-29-2016		Poen		11-21-2016	Poen		Debug the program error messages.
	11-23-2016		Poen		11-23-2016	Poen		Improve get_date function.
	11-23-2016		Poen		11-23-2016	Poen		Improve get_time function.
	11-23-2016		Poen		11-23-2016	Poen		Improve get_datetime function.
	12-14-2016		Poen		12-14-2016	Poen		Improve datetime2sec function.
	03-27-2017		Poen		03-27-2017	Poen		Fix part_days function error message.
	03-27-2017		Poen		03-27-2017	Poen		Fix in_range function error message.
	03-27-2017		Poen		03-27-2017	Poen		Fix date2week function error message.
	03-27-2017		Poen		03-27-2017	Poen		Fix date_range function error message.
	03-27-2017		Poen		03-27-2017	Poen		Fix sub_date function error message.
	03-27-2017		Poen		03-27-2017	Poen		Fix sub_time function error message.
	03-27-2017		Poen		03-27-2017	Poen		Fix sub_datetime function error message.
	03-27-2017		Poen		03-27-2017	Poen		Fix jump_datetime function error message.
	03-27-2017		Poen		03-27-2017	Poen		Fix datetime2sec function error message.
	05-31-2017		Poen		05-31-2017	Poen		Modify set_timezone function.
	06-01-2017		Poen		06-01-2017	Poen		Remove set_timezone function error message.
	06-01-2017		Poen		06-01-2017	Poen		Improve jump_datetime function.
	06-05-2017		Poen		06-05-2017	Poen		Modify the document.
	07-04-2017		Poen		07-04-2017	Poen		Improve the program.
	08-04-2017		Poen		08-04-2017	Poen		Add get_timezone function.
	08-04-2017		Poen		08-04-2017	Poen		Fix jump_datetime function.
	08-04-2017		Poen		08-04-2017	Poen		Fix date2week function.
	---------------------------------------------------------------------------

>> About

	Time-related functions.

>> Usage Function

	==============================================================
	Include file
	Usage : include('time/main.inc.php');
	==============================================================

	==============================================================
	Get the date range of the number of working days and weekend days, if YYYY beyond calculation range 1 ~ 32767 returns false on failure.
	Usage : csl_time::part_days($firstDate,$secondDate,$type)
	Param : string $firstDate (YYYY-MM-DD)
	Param : string $secondDate (YYYY-MM-DD)
	Param : boolean $type (date type `true` is weekday or `false` is weekend) : Default false
	Return : integer
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example :
	csl_time::part_days('2012-06-27','2012-06-27',false);
	Output >> 0
	Example :
	csl_time::part_days('2012-06-27','2012-06-24',false);
	Output >> 1
	Example :
	csl_time::part_days('2012-06-27','2012-06-21',false);
	Output >> 2
	Example :
	csl_time::part_days('2012-06-27','2012-06-24',true);
	Output >> 3
	==============================================================

	==============================================================
	Check the now datetime within limits range, if YYYY beyond calculation range 1 ~ 32767 returns false on failure.
	Usage : csl_time::in_range($nowDatetime,$firstDatetime,$secondDatetime)
	Param : string $nowDatetime (YYYY-MM-DD hh:ii:ss)
	Param : string $firstDatetime (YYYY-MM-DD hh:ii:ss)
	Param : string $secondDatetime (YYYY-MM-DD hh:ii:ss)
	Return : boolean
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example :
	csl_time::in_range('2012-06-05 12:20:30','2012-02-05 12:20:30','2012-10-05 12:20:30');
	Output >> true
	Example :
	csl_time::in_range('2012-06-05 12:20:30','2012-02-05 12:20:30','2012-05-05 12:20:30');
	Output >> false
	==============================================================

	==============================================================
	Get date day (1 ~ 7 : monday ~ sunday) of the week, if YYYY beyond calculation range 1 ~ 32767 returns false on failure.
	Usage : csl_time::date2week($date)
	Param : string $date (YYYY-MM-DD)
	Return : integer
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example :
	csl_time::date2week('2012-01-17');
	Output >> 2
	==============================================================

	==============================================================
	Calculation date range list, if YYYY beyond calculation range 1 ~ 32767 returns false on failure.
	Usage : csl_time::date_range($firstDate,$secondDate)
	Param : string $firstDate (YYYY-MM-DD)
	Param : string $secondDate (YYYY-MM-DD)
	Return : array
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example :
	csl_time::date_range('2012-01-21','2012-01-22');
	Output >>
	Array (
		[0] => 2012-01-21
		[1] => 2012-01-22
	)
	==============================================================

	==============================================================
	Set the script default timezone by timezone id.
	Usage : csl_time::set_timezone($timezoneId)
	Param : string $timezoneId (timezone id)
	Return : boolean
	--------------------------------------------------------------
	Example :
	csl_time::set_timezone('Asia/Taipei');
	Output >> TRUE
	Example :
	csl_time::set_timezone('Test');
	Output >> FALSE
	==============================================================

	==============================================================
	Get the script default timezone id.
	Usage : csl_time::get_timezone()
	Return : string
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example :
	csl_time::set_timezone('Asia/Taipei');
	csl_time::get_timezone();
	Output >> Asia/Taipei
	==============================================================

	==============================================================
	Return part info of date, if YYYY beyond calculation range 1 ~ 32767 returns false on failure.
	Usage : csl_time::sub_date($date,$index)
	Param : string $date (YYYY-MM-DD)
	Param : string $index (index y,m,d)
	Return : string
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example :
	csl_time::sub_date('2011-12-10','y');
	Output >> 2011
	Example :
	csl_time::sub_date('2011-12-10','m');
	Output >> 12
	Example :
	csl_time::sub_date('2011-12-10','d');
	Output >> 10
	==============================================================

	==============================================================
	Return part info of time.
	Usage : csl_time::sub_time($time,$index)
	Param : string $time (hh:ii:ss)
	Param : string $index (index h,i,s,12h)
	Return : string
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example :
	csl_time::sub_time('13:20:30','h');
	Output >> 13
	Example :
	csl_time::sub_time('13:20:30','i');
	Output >> 20
	Example :
	csl_time::sub_time('13:20:30','s');
	Output >> 30
	csl_time::sub_time('13:20:30','12h');
	Output >> PM 01:20:30
	==============================================================

	==============================================================
	Return part info of datetime, if YYYY beyond calculation range 1 ~ 32767 returns false on failure.
	Usage : csl_time::sub_datetime($datetime,$index)
	Param : string $datetime (YYYY-MM-DD hh:ii:ss)
	Param : string $index (index y,m,d,h,i,s,date,24h,12h)
	Return : string
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example :
	csl_time::sub_datetime('2011-12-10 12:20:30','y');
	Output >> 2011
	Example :
	csl_time::sub_datetime('2011-12-10 12:20:30','m');
	Output >> 12
	Example :
	csl_time::sub_datetime('2011-12-10 12:20:30','d');
	Output >> 10
	Example :
	csl_time::sub_datetime('2011-12-10 13:20:30','h');
	Output >> 13
	Example :
	csl_time::sub_datetime('2011-12-10 13:20:30','i');
	Output >> 20
	Example :
	csl_time::sub_datetime('2011-12-10 13:20:30','s');
	Output >> 30
	Example :
	csl_time::sub_datetime('2011-12-10 13:20:30','date');
	Output >> 2011-12-10
	Example :
	csl_time::sub_datetime('2011-12-10 13:20:30','24h');
	Output >> 13:20:30
	Example :
	csl_time::sub_datetime('2011-12-10 13:20:30','12h');
	Output >> PM 01:20:30
	==============================================================

	==============================================================
	Get system microtime.
	Usage : csl_time::get_microtime();
	Return : double
	--------------------------------------------------------------
	Example :
	csl_time::get_microtime();
	==============================================================

	==============================================================
	Get system date.
	Usage : csl_time::get_date($type);
	Param : string $type (type `host` or `gmt`) : Default gmt
	Return : string
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example :
	csl_time::get_date('host');
	==============================================================

	==============================================================
	Get system time.
	Usage : csl_time::get_time($type);
	Param : string $type (type `host` or `gmt`) : Default gmt
	Return : string
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example :
	csl_time::get_time('host');
	==============================================================

	==============================================================
	Get system datetime.
	Usage : csl_time::get_datetime($type);
	Param : string $type (type `host` or `gmt`) : Default gmt
	Return : string
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example :
	csl_time::get_datetime('host');
	==============================================================

	==============================================================
	Jump change datetime, if YYYY beyond calculation range 1 ~ 32767 returns false on failure.
	Usage : csl_time::jump_datetime($datetime,$offsetSec);
	Param : string $datetime (YYYY-MM-DD hh:ii:ss)
	Param : integer $offsetSec (offset sec number -2147483647 ~ 2147483647)
	Return : string
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example :
	csl_time::jump_datetime('2011-12-10 13:20:30',10);
	Output >> 2011-12-10 13:20:40
	Example :
	csl_time::jump_datetime('2011-12-10 13:20:30',-10);
	Output >> 2011-12-10 13:20:20
	==============================================================

	==============================================================
	Datetime conversion total number of seconds, if YYYY beyond calculation range 1 ~ 32767 returns false on failure.
	Usage : csl_time::datetime2sec($datetime);
	Param : string $datetime (YYYY-MM-DD hh:ii:ss)
	Return : double
	Return Note : Returns FALSE on failure.
	--------------------------------------------------------------
	Example :
	csl_time::datetime2sec('2011-12-10 13:20:30');
	Output >> 63460416030
	==============================================================

*/
?>