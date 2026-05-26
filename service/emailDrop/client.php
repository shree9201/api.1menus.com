<?php
error_reporting(E_ALL);
	$url = "https://droptech.in/emailDrop/test.php";
	$apipostdata = "action=test";
	$requesturl='https://droptech.in/emailDrop/test.php';
$ch=curl_init($requesturl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$cexecute=curl_exec($ch);
curl_close($ch);
echo $result = $cexecute;

?>