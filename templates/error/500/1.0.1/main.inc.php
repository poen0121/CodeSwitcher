<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php
csl_header :: nocache();
csl_header :: http('Internal Server Error', 500);
header('Content-Type: text/html; charset=utf-8');
include ('content.txt');
?>