<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php
csl_header :: nocache();
csl_header :: http('Bad Request', 400);
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
<title>400 Bad Request</title>
</head>
<body>
<h1>400 Bad Request</h1>
<hr>
<p>The request could not be understood by the server due to malformed syntax.<p>
</body>
</html>