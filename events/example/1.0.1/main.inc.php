<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php
csl_mvc :: import_library('example'); //Library
csl_mvc :: import_model('example'); //Model
$language = csl_mvc :: cue_language('en_US'); //Language
$data = array (
'config' => csl_mvc :: cue_config('example'), //Config Data
'language' => $language->gets('language_name'), //Language Function
'time' => unixTime(), //Library Function
'text' => text() //Model Function
);
csl_mvc :: view_template('example', $data); //View
?>
