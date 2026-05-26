<?php
$translate = file_get_contents('app/service/translate/en.json');
$lg = (object)json_decode($translate,true);


function TR($prop,$return=false){
	$props = explode('.', $prop);
	global $lg;
	$folder = $lg->$props[0];
	if($return)
	return $folder[$props[1]];
	else echo $folder[$props[1]];
}
?>