<?php 

$page 		= isset($_REQUEST['page'])?$_REQUEST['page']:"test";
$content = (object)$_REQUEST;
$themeColor = isset($content->themeColor)?$content->themeColor:"blue";
if($themeColor=="")$themeColor="blue";
$colorArray = array('white'=>"#fff",'yellow'=>"#FDDF56" , 'green'=>'#00a65a','blue'=>'#3c8dbc','red'=>'#dd4b39','orange'=>'#f39c12','purple'=>'#555299','black'=>'#000');
$themeColorCode = isset($colorArray[$themeColor]) ? $colorArray[$themeColor] : $colorArray['white'];
//require_once 'common/header.php';
$url = "page/".$page.".php";
include_once($url);
//require_once 'common/footer.php';
?>