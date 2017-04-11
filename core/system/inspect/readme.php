<?php
/*
>> Information

	Title		: csl_inspect function
	Revision	: 3.1.0
	Notes		:

	Revision History:
	When			Create		When		Edit		Description
	---------------------------------------------------------------------------
	02-08-2010		Poen		02-08-2010	Poen		Create the program.
	08-01-2016		Poen		04-11-2017	Poen		Reforming the program.
	---------------------------------------------------------------------------

>> About

	Inspect data format.

>> Usage Function

	==============================================================
	Include file
	Usage : include('inspect/main.inc.php');
	==============================================================

	==============================================================
	Verification number string for each group of thousand comma (",").
	Usage : csl_inspect::is_number_format($data);
	Param : string $data (numeric string)
	Return : boolean
	--------------------------------------------------------------
	Example :
	csl_inspect::is_number_format('5,666.00');
	Output >> TRUE
	Example :
	csl_inspect::is_number_format('-5,666.00');
	Output >> TRUE
	Example :
	csl_inspect::is_number_format('5,666');
	Output >> TRUE
	Example :
	csl_inspect::is_number_format('-5,666');
	Output >> TRUE
	==============================================================

	==============================================================
	Iconv encoding type code verification.
	Usage : csl_inspect::is_iconv_encoding($data);
	Param : string $data (encoding type code)
	Return : boolean
	--------------------------------------------------------------
	Example :
	csl_inspect::is_iconv_encoding('utf-8');
	Output >> TRUE
	==============================================================

	==============================================================
	IP format verification.
	Usage : csl_inspect::is_ip($data,$shield);
	Param : string $data (ip)
	Param : boolean $shield (shield private ip and reserved ip) : Default false
	Return : boolean
	--------------------------------------------------------------
	Example :
	csl_inspect::is_ip('127.0.0.1');
	Output >> TRUE
	Example :
	csl_inspect::is_ip('127.0.0.1',true);
	Output >> FALSE
	==============================================================

	==============================================================
	Date format verification.
	Usage : csl_inspect::is_date($data);
	Param : string $data (date YYYY-MM-DD)
	Return : boolean
	--------------------------------------------------------------
	Example :
	csl_inspect::is_date('2010-10-10');
	Output >> TRUE
	==============================================================

	==============================================================
	Time format verification.
	Usage : csl_inspect::is_time($data);
	Param : string $data (time hh:ii:ss)
	Return : boolean
	--------------------------------------------------------------
	Example :
	csl_inspect::is_time('20:10:10');
	Output >> TRUE
	==============================================================

	==============================================================
	Datetime format verification.
	Usage : csl_inspect::is_datetime($data);
	Param : string $data (datetime YYYY-MM-DD hh:ii:ss)
	Return : boolean
	--------------------------------------------------------------
	Example :
	csl_inspect::is_datetime('2010-10-10 20:10:10');
	Output >> TRUE
	==============================================================

	==============================================================
	E-Mail format verification.
	Usage : csl_inspect::is_mail($data);
	Param : string $data (e-mail)
	Return : boolean
	--------------------------------------------------------------
	Example :
	csl_inspect::is_mail('tester@yahoo.com.tw');
	Output >> TRUE
	==============================================================

	==============================================================
	URL format verification.
	Usage : csl_inspect::is_url($data);
	Param : string $data (URL)
	Return : boolean
	--------------------------------------------------------------
	Example :
	csl_inspect::is_url('http://yahoo.com.tw');
	Output >> TRUE
	==============================================================

	==============================================================
	Taiwan id card number format verification.
	Usage : csl_inspect::is_taiwan_id_card($data);
	Param : string $data (taiwan id card number)
	Return : boolean
	--------------------------------------------------------------
	Example :
	csl_inspect::is_taiwan_id_card('U208020602');
	Output >> TRUE
	==============================================================

	==============================================================
	Taiwan uniform number format verification.
	Usage : csl_inspect::is_taiwan_ban($data);
	Param : string $data (taiwan uniform number)
	Return : boolean
	--------------------------------------------------------------
	Example :
	csl_inspect::is_taiwan_ban('22779067');
	Output >> TRUE
	==============================================================

	==============================================================
	Taiwan phone format verification.
	Usage : csl_inspect::is_taiwan_phone($data);
	Param : string $data (taiwan phone)
	Return : boolean
	--------------------------------------------------------------
	Example :
	csl_inspect::is_taiwan_phone('0227208889');
	Output >> TRUE
	Example :
	csl_inspect::is_taiwan_phone('0930684633');
	Output >> TRUE
	==============================================================

	==============================================================
	Taiwan mobile phone format verification.
	Usage : csl_inspect::is_taiwan_phone($data);
	Param : string $data (taiwan mobile phone)
	Return : boolean
	--------------------------------------------------------------
	Example :
	csl_inspect::is_taiwan_mobile('0930684633');
	Output >> TRUE
	Example :
	csl_inspect::is_taiwan_mobile('0227208889');
	Output >> FALSE
	==============================================================

*/
?>