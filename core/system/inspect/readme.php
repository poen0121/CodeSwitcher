<?php
/*
>> Information

	Title		: csl_inspect function
	Revision	: 3.3.0
	Notes		:

	Revision History:
	When			Create		When		Edit		Description
	---------------------------------------------------------------------------
	02-08-2010		Poen		02-08-2010	Poen		Create the program.
	08-01-2016		Poen		08-02-2016	Poen		Reforming the program.
	08-29-2016		Poen		08-29-2016	Poen		Add is_iconv_encoding function.
	08-29-2016		Poen		08-29-2016	Poen		Add is_taiwan_phone function.
	08-29-2016		Poen		08-29-2016	Poen		Add is_taiwan_mobile function.
	08-31-2016		Poen		01-06-2017	Poen		Improve is_date function.
	09-02-2016		Poen		09-02-2016	Poen		Debug is_iconv_encoding function.
	09-06-2016		Poen		09-06-2016	Poen		Add is_number_format function.
	09-06-2016		Poen		09-07-2016	Poen		Improve is_iconv_encoding function.
	09-10-2016		Poen		09-10-2016	Poen		Improve is_number_format function.
	09-10-2016		Poen		09-10-2016	Poen		Improve is_taiwan_mobile function.
	10-27-2016		Poen		10-28-2016	Poen		Improve is_iconv_encoding function.
	01-06-2017		Poen		01-06-2017	Poen		Improve is_time function.
	01-06-2017		Poen		01-06-2017	Poen		Improve is_datetime function.
	04-11-2017		Poen		04-11-2017	Poen		Improve is_ip function special purposes.
	06-07-2017		Poen		06-07-2017	Poen		Remove is_number_format function.
	07-03-2017		Poen		07-03-2017	Poen		Improve the program.
	---------------------------------------------------------------------------

>> About

	Inspect data format.

>> Usage Function

	==============================================================
	Include file
	Usage : include('inspect/main.inc.php');
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
	Date format verification, if YYYY beyond calculation range 1 ~ 32767 returns false on failure.
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
	Datetime format verification, if YYYY beyond calculation range 1 ~ 32767 returns false on failure.
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