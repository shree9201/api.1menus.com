<?php
//////////////////////////////////////////////////////////////////////////////////////////
// File Name        : constants.php														//
// Craeted By       : Vishwajeet Mahadik												//
// Created Date     : 26-June-2019													    //
// File Modified By : Vishwajeet Mahadik												//
// Created Date     : 26-June-2019													 	//
// Description      : Website Global constants variables initialize						//
//////////////////////////////////////////////////////////////////////////////////////////
##############################################
# GENERAL SETTINGS
##############################################
error_reporting(E_ALL ^ E_NOTICE); 	// display all errors except notices
@ini_set('display_errors', '1'); // display all errors
@ini_set('safe_mode', 'off'); // display all errors
date_default_timezone_set("Asia/Kolkata"); 
@ini_set("max_execution_time",0); //this sets it unlimited 
// Turn off all error reporting
error_reporting(0);
$isLive = true;
$protocol = stripos($_SERVER['SERVER_PROTOCOL'],'https') === 0 ? 'https://' : 'http://';


define("DB_SERVER", "localhost");
define("TIMEPASSED", 0);
if($_SERVER['HTTP_HOST']=="localhost" || $_SERVER['HTTP_HOST']=="127.0.0.1" || $_SERVER['HTTP_HOST']  == "192.168.0.117"){
	define("DB_NAME", "1menus_application_database_core_2026");
	define("DB_USER", "root");
	define("DB_PASS", "root");
	$isLive = false;
	$Directory_Name = "1menus";
}else{
	define("DB_NAME", "1menus_application_database_core_2026");
	define("DB_USER", "1menus_droptech_vishu");
	define("DB_PASS", "Vishu9201@9201");
}

function site_protocol() {
	if(isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) || isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&  $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')  return $protocol = 'https://'; else return $protocol = 'http://';
}

//for the site host name
$Root=dirname(__DIR__);
$Document_Root = ($_SERVER['DOCUMENT_ROOT']);
$Directory_Name = substr($Root, strlen($Document_Root), (strlen($Root)-strlen($Document_Root)));
$host= isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'';
$port= isset($_SERVER['HTTP_PORT'])?$_SERVER['HTTP_PORT']:'';
//if(!$isLive){$site_url.="/";}
if (!empty($Directory_Name) && $Directory_Name[0] != '/' ) {
 	$Directory_Name = "".$Directory_Name;
 	$Directory_Name = str_replace('/\/', '/', $Directory_Name);
}

$siteUrl = site_protocol()."".$host."/";
if($Directory_Name!="" && $isLive == false)$siteUrl.=$Directory_Name."/";
$siteUrl = str_replace('/app', '', $siteUrl);
$siteUrl = str_replace('\app', '', $siteUrl);
	
// define constance variables

define("SITE_TITLE", "1Menus");
define("SITE_URL", $siteUrl);
define("CMS_URL", $siteUrl."cms/");
define("API_URL", $siteUrl."service/api.php");
define("JWT_SECRET_KEY", "8b9f3d23a5c74e1f923a4b6c7d8e9f01a2b3c4d5e6f7a");
define("JWT_ALGORITHM", "HS256");
define("JWT_EXPIRE_SECONDS", 3600);
define("CMS_NAVIGATION_PROCESS_URL", $siteUrl);


define("DROPTECH_MEDIA_PATH", $siteUrl."media/");
define("DROPTECH_PLUGIN_PATH", $siteUrl."cms/plugins/");
define("CMS_THEME", $siteUrl."theme/b2b/");
define("DROPTECH_WATTERMARK", '<a class="droptechWatterMark" target="_blank" href="http://www.droptech.in">&nbsp;</a>');
define("CMS_THEME_PATH", $siteUrl."theme/b2b/");
define("CMS_MEDIA_PATH", $siteUrl."media/");
define("WEB_ASSETS_PATH", $siteUrl."app/b2c/assets/");
define("CMS_ASSETS_PATH", $siteUrl."app/b2b/assets/");
define("WEB_THEME_PATH", $siteUrl."theme/b2c/");
define("API_THEME_PATH", $siteUrl."theme/api/");
define("WEB_THEME_PATH_LANDING", $siteUrl."theme/b2c/landingPage/");
define("WEB_COMP_PATH", $siteUrl."app/b2c/view/components/");
define("CM_API_BASE", 'http://api.bookinghotel.co.in/api/');
define("IS_SMS_NOTIFICATION", 'NO');
define("PLACEHOLDERIMAGE", $siteUrl.'app/b2c/assets/img/placeholder-loading.gif');
function isUserLogin(){

	if($_SESSION['userInfo']['username']=='')
		return false;
	else
		return true;
}
function isB2CUserLogin(){
	$sId = isset($_SESSION['customer']->id)?$_SESSION['customer']->id:'';
	if($sId == "")
		return false;
	else
		return true;
}
function isLoggedIn(){
	$sId = isset($_SESSION['customer']->id)?$_SESSION['customer']->id:'';
	if($sId == ""){?>
		<script>window.location = "login";</script>
	<?php }else{
		return true;
	}
		
}
function imagePath($imageName,$return=false){
	$pageFlag 	= isset($_REQUEST['profileId'])?$_REQUEST['profileId']:"";
	
	$imageFullName="";
	if($imageName=="" || $imageName == null){
		$imageFullName = CMS_MEDIA_PATH."default.png";
	}else{
		$imageFullName = CMS_MEDIA_PATH."".$imageName;
		if($pageFlag!="")
			$photo = "../../../media/".$imageName;
		else
			$photo = "media/".$imageName;
		if (!file_exists($photo)) {
			$imageFullName = CMS_MEDIA_PATH."default.png";
		}
	}
	if($return==true){
		return $imageFullName; 
	}else{
	echo $imageFullName;
	}
}
function alertBloxB2c(){?>



<div class="alertBlock">
<div class="alert alert-danger" role="alert">
<span class='msg'>XXX</span>
</div>

<div class="alert alert-success" role="alert">
<span class='msg'>XXX</span>
</div>

<div class="frame-wrap alert alert-process">
<div class="border p-3">
<div class="d-flex justify-content-center">
<div class="spinner-grow" role="status">
<span class="sr-only">Loading...</span>
</div>
</div>
</div>
</div>
</div>
<?php }
?>
<?php 
function Send_SMS($number,$msg,$responce=""){

	$number = str_replace('+91',"",$number);
	$number = str_replace('+',"",$number);
	error_reporting (E_ALL ^ E_NOTICE);
	if($msg!="" && $number!=""){
		//$msg = "Dear Customer ".$msg;
		$msg = 'Dear Member , '.$msg.' -'.SITE_TITLE.' INFORU';
		$url = "http://173.45.76.227/send.aspx?username=droptc&pass=Vishu9201&route=trans1&senderid=INFOSS&ispreapproved=1&numbers=".urlencode($number)."&message=".urlencode($msg);
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$curl_scraped_page = curl_exec($ch);
		curl_close($ch);
		return true;
	}
}
function getStatusColorForDisplay($status){
	$c = 'blue';
	if($status == "NEW"){
		$c = 'blue';
	}
	else if($status == "ACCEPT") {
		$c = 'green';
	}
	else if($status == "ASSIGNED" || $status == "ASSIGN" || $status == "END" || $status == "DONE") {
		$c = 'green';
	}
	else if($status == "START") {
		$c = 'yellow';
	}
	else if($status == "CLOSE") {
		$c = 'black';
	}
	else if($status == "REJECT" || $status == "ESCALATED") {
		$c = 'red';
	}
	else if($status == "REOPEN"){
		$c = 'red';
	}
	else if($status == "HOLD"){
		$c = 'purple';
	}
	else{$c = "blue";
	}
	return $c;
}
function getTimeBetween($start,$end){
	if($start!=0 && $start!="" && $end!=0 && $end!=""){

		$startSort = intval(strtotime($start));
		$endSort = intval(strtotime($end));
		$start = new DateTime($start);
		$end = new DateTime($end);
		$interval = $end->diff($start);
		$returnData = "";
		$dfHrs =  $interval->format('%h');
		$dfMin =  $interval->format('%i');
		$dfSec =  $interval->format('%s');
		if($dfHrs != 0){
			$returnData.=$dfHrs." Hour";
			if($dfHrs > 1){
				$returnData.="s,";
			}
			$returnData.=" ";
		}
		if($dfMin != 0){
			$returnData.=$dfMin." Minute";
			if($dfMin > 1){
				$returnData.="s";
			}
			$returnData.=" ";
		}
		if($dfSec != 0){
			$returnData.=$dfSec." Sec";
			$returnData.=" ";
		}
		return  $returnData;

		//$start = strtotime($start);
		//$end = strtotime($end);
		//return round(abs($end - $start) / 60,2). " minute";
	}else{
		return '';
	}
}
function imgsrc($name){
	echo WEB_ASSETS_PATH.'img/'.$name;
}
function getPrice($rate,$format=false){
	if($format)
		return "₹ ".number_format(round($rate),2,'.',',')."/-";
	else 
		return number_format(round($rate,2),2,'.',',');
		
}
function format_time($t,$f=':') // t = seconds, f = separator
{
	return sprintf("%02d%s%02d%s%02d", floor($t/3600), $f, ($t/60)%60, $f, $t%60);
}
?>