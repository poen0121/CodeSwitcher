<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php
csl_mvc::importLibrary('example');//Library
csl_mvc::importModel('example');//Model
$language=csl_mvc::cueLanguage('en_US');//Language
$data=array(
'config'=>csl_mvc::cueConfig('example'),//Config Data
'language'=>$language->gets('language_name'),//Language Function
'time'=>unixTime(),//Library Function
'text'=>text()//Model Function
);
csl_mvc::viewTemplate('example',$data);//View
?>
