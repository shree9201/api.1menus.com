<?php
// Set headers and start session BEFORE any includes or error output
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Credentials: true");
header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
header('Access-Control-Max-Age: 1000');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
header('Content-Type: application/json; charset=utf-8');
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

///////////////////////////////////////////
// File Name        : api_class.php
// Craeted By       : vishu
// Created Date     : 04-July-2020
// File Modified By : vishu
// Modify  Date     : 04-July-2020
// Description      : This is file API process functions. API methods for the Android  and IOS application.
///////////////////////////////////////////
// try multiple locations for includes to support different deployments
function _safe_include_once_top(array $candidates, $name){
	foreach($candidates as $file){
		if(file_exists($file)){
			include_once $file;
			return true;
		}
	}
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode(array('status'=>false,'value'=>'Missing required file: '.$name,'checked'=>$candidates));
	exit;
}

_safe_include_once_top(array(__DIR__.'/constant.php', __DIR__.'/service/constant.php', __DIR__.'/../constant.php'), 'constant.php');
_safe_include_once_top(array(__DIR__.'/service/database_class.php', __DIR__.'/database_class.php', __DIR__.'/../service/database_class.php'), 'database_class.php');
_safe_include_once_top(array(__DIR__.'/emailDrop.php', __DIR__.'/service/emailDrop.php', __DIR__.'/../emailDrop.php'), 'emailDrop.php');
_safe_include_once_top(array(__DIR__.'/massages.php', __DIR__.'/service/massages.php', __DIR__.'/../massages.php'), 'massages.php');
_safe_include_once_top(array(__DIR__.'/jwt.php', __DIR__.'/service/jwt.php', __DIR__.'/../jwt.php'), 'jwt.php');
class api_class {
	
	var $post;
	var $get;
	var $request;
	var $files;
	var $email;
	var $db;
	var $responseArray;
	var $response;
	var $sessionInfo;
	var $loginUserId;
	var $action;
	var $ip;
	var $config;
	var $statusList;
	var $hotelWebsiteThemes;
	var $staffTypes;
	var $departments;
	var $message;
	public function __construct(){
		$this->post = $_POST;
		$this->get = $_GET;
		$this->request = $_REQUEST;
		$this->files = $_FILES;	
		$this->email = new emailDrop();
		$this->db = new database_class();
		// object create
		$this->responseArray = array();
		$this->response = "";
		$this->sessionInfo = explode('_', isset($_SESSION['people']) ? $_SESSION['people'] : "");
		$this->responseArray = array();
		$this->sessionInfo 	= isset($_SESSION['userInfo'])?$_SESSION['userInfo']:"";
		$this->loginUserId = isset($this->sessionInfo['id'])?$this->sessionInfo['id']:'';
		$this->action = isset($_REQUEST['action'])?$_REQUEST['action']:"";
		$this->ip = isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'';
		$this->config = $this->db->selectSingleRowData('config', 1);
		$this->statusList = array('NEW'=>'NEW' , 'ACCEPT'=>'ACCEPT','ASSIGN' => 'ASSIGN' , 'START'=>'START','HOLD'=>'HOLD','END'=>'END','DONE'=>'DONE','CLOSE'=>'CLOSE','REJECT'=>'REJECT','REOPEN'=>'REOPEN');
		$this->hotelWebsiteThemes = array("HotWebTheme-1"=>'HotWebTheme-1');
		$this->staffTypes = [['key' => 'FOMGR','value' => 'Front Office Manager'],['key' => 'FOSU','value' => 'Front Office Supervisor'],['key' => 'FO','value' => 'Front Office Executive'],['key' => 'HKMGR','value' => 'House Keeping Manager'],['key' => 'HKSU','value' => 'House Keeping Supervisor'],['key' => 'HK','value' => 'House Keeping Executive'],['key' => 'MTNS','value' => 'Maintenance'],['key' => 'SPA','value' => 'Spa'],['key' => 'WAITER-STAFF','value' => 'Waiter/Staff'],['key' => 'KITCHEN','value' => 'Kitchen']];
		$this->departments = [['key' => 'STAFF','value' => 'Staff'],['key'=>'MANAGER','value'=>'Manager'],['key'=>'HR','value'=>'HR']];
		// jwt_token authentication for API access validation can be implemented here if needed for all API calls
		$this->getBodyJsonData();
		if($_REQUEST['action'] !== 'getJwtToken'){ // skip token validation for getJwtToken API to allow clients to obtain token
			 $this->getAndValidateHeaderTokenForJWT();
			 
		}
	}

	public function getBodyJsonData(){
		$json = file_get_contents('php://input'); 
	$data = json_decode($json, true);
	// convert $data to request param array (only if decoded to array)
	if (is_array($data)) {
		foreach ($data as $key => $value) {
			$_REQUEST[$key] = $value;
		}
		return $data;
	}
	return array();
	}	
	// function for the validate Email address
	public function emailValid($email=NULL){
		$email = $this->optionalParametterValidate($email,'email');
		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			$responseArray =  array('status' => 'false','value' =>"Email Address Invalid");
			$this->displayOutputJson($responseArray);
		}
		else {return true;
		}
	}
	
	// function  for the validate the mobile number
	public function mobileValid($mobile=NULL)
	{
		$mobile = $this->optionalParametterValidate($mobile,'mobile');
		if(preg_match('/^((\+){0,1}91(\s){0,1}(\-){0,1}(\s){0,1})?([0-9]{10})$/', $mobile,$matches)){
			return true;
		}
		else {
			$responseArray =  array('status' => 'false','value' =>"Mobile Number Invalid");
			$this->displayOutputJson($responseArray);
		}
	}
	
	// function for the check if any optional parmiter runs any function to next process
	public function optionalParametterValidate($value,$requestValue){
		if($this->checkIsNull($value)){
			$value 	= isset($_REQUEST[$requestValue])?$_REQUEST[$requestValue]:"";
		}
		if($value==""){
			$error= 'The Missing paramtter '.$requestValue;
			$responseArray =  array('status' => 'false','value' =>$error);
			$this->displayOutputJson($responseArray);
		}
		return $value;
	}
	
	//	functiona for check if string is null or not
	public function checkIsNull($str){
		if(empty($str))
			return true;
		else
			return false;
	}
	
	// function to get translated message from translation JSON file
	public function getTranslatedMessage($translationKey, $replacements = array()){
		$translate = file_get_contents('../service/translate/en.json');
		$lg = (object)json_decode($translate, true);
		
		$keys = explode('.', $translationKey);
		if(count($keys) === 2){
			$section = $lg->{$keys[0]};
			$message = isset($section[$keys[1]]) ? $section[$keys[1]] : $translationKey;
			
			// Replace placeholders with actual values
			foreach($replacements as $key => $value){
				$message = str_replace('%' . $key . '%', $value, $message);
			}
			
			return $message;
		}
		return $translationKey;
	}
	
	// function for the validae number from passed string
	public function numberValid($str){
		if (is_numeric($str)) {
			return true;
		} else {
			$this->displayOutputJson(array('status' => 'false','value' =>'Enter the sting is not numeric string'));
			return false;
		}
	}
	//function to validate website url
	public function urlValid($url){
	
		if(!filter_var($url, FILTER_VALIDATE_URL))
			$this->displayOutputJson(array('status' => 'false','value' =>'Enter valid url'));
		else
			return true;
	}
	//function to enter username containing alphbet,at least one number and one special character
	public function usernameValid($uname)
	{
		if(preg_match("/^.*(?=.{6,10})(?=.*[a-zA-Z])(?=.*[!@#$%])(?=.*\d)[a-zA-Z0-9!@#$%]+$/",$uname))
		{
			return true;
		}
		else
		{
			$this->displayOutputJson(array('status' => 'false','value' =>'Please enter alphabetical and atleast one number and one special character'));
		}
	}
	//function to compare password and confirm password
	public function passwordConfirmValid($password,$confirmPass)
	{
		if(strcmp($password,$confirmPass)==0)
		{
			return true;
		}
		else
			$this->displayOutputJson(array('status' => 'false','value' =>'Enter correct password'));
	}
	// function for the show display output
	public	function jsonDisplay($arrayName)
	{
		echo json_encode($arrayName);
	}
	// function for throw error message
	public	function displayOutputJson($arrayName)
	{
		$status = isset($arrayName['status']) ? $arrayName['status'] : 200;
		if($status === false ||  $status === 'false' || (is_string($status) && strtolower($status) === 'false')){
			http_response_code(500);
		}
		$arrayName=array($arrayName);
		$this->APIAccessLogs($arrayName); // log API access with response
		echo json_encode($arrayName[0]);
		exit;
	}
	public function getCurldata($url) {
		$ch = curl_init();
	
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
		curl_setopt($ch, CURLOPT_URL, $url);
	
		$data = curl_exec($ch);
		curl_close($ch);
	
		return $data;
	}
	public function getAuthorizationHeader(){
	// Read the Authorization header from common server variables.
	$headers = null;
	if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
		$headers = trim($_SERVER['HTTP_AUTHORIZATION']);
	} elseif (isset($_SERVER['Authorization'])) {
		$headers = trim($_SERVER['Authorization']);
	} elseif (function_exists('apache_request_headers')) {
		$requestHeaders = apache_request_headers();
		if (isset($requestHeaders['Authorization'])) {
			$headers = trim($requestHeaders['Authorization']);
		} elseif (isset($requestHeaders['authorization'])) {
			$headers = trim($requestHeaders['authorization']);
		}
	}
	return $headers;
}

public function getBearerToken(){
	$header = $this->getAuthorizationHeader();
	// Extract Bearer token from header value like: Authorization: Bearer TOKEN
	if (!empty($header) && preg_match('/Bearer\s+(.*)$/i', $header, $matches)) {
		return trim($matches[1]);
	}
	return "";
}

public function applicationAPI($url) {
		$ch = curl_init();	
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
		curl_setopt($ch, CURLOPT_URL, $url);	
		$data = curl_exec($ch);
		curl_close($ch);	
		return $data;
	}
	public function checkIsIdExist($table,$id){
		if($this->db->selectCount("select count(*) as count from ".$table." where id=".$id)!=0){
			return true;
		}else{
			$responseArray =  array('status' => 'false','value' =>"With this Id the ".$table." records does not exist in system");
			$this->displayOutputJson($responseArray);
		}
	}
	public function updateTableRecordValue($table,$id,$key,$value){
		if($this->db->update("update ".$table." set ".$key." = '".$value."' where id=".$id)){
			return true;
		}else{
			$responseArray =  array('status' => 'false','value' =>MSG_COM_109);
			$this->displayOutputJson($responseArray);
		}
	}
	public function apiDefault(){
		$responseArray =  array('status' => 'false','value' =>"INVALID API");
		$this->displayOutputJson($responseArray);
	}
	public function isImageAvaibaleOrNot($imageName){
		$imageFullName = "assets/img/site/".$imageName;
		$path="assets/img/site/";
	
		if($imageName=="") {
			$path="assets/img/site/no_iamge.jpg";
		}
		else {
			$path="assets/img/site/no_iamge.jpg";
			if (file_exists($imageFullName)) {
				$path="assets/img/site/".$imageName;
			}
		}
		return $path;
	}
	
public function generateRandomString($length = 5) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
public function generateRandomToken($length = 15) {
	$characters = '0123456789';
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, strlen($characters) - 1)];
	}
	return $randomString;
}

// function for throw error message
public	function jsonReturnToDisplay($arrayName)
{
	$arrayName=array($arrayName);
	echo json_encode($arrayName);
	exit;
}

public function get_time_passed( $timestamp ) {
	$to_time = strtotime(date('Y-m-d H:i:s'));

	$from_time = strtotime($timestamp);
	$from_time = $from_time+45000;
	$mins = round(abs($to_time - $from_time) / 60,2). " mins ago.";
	$duration = intval( $mins );
	$dur = array();
	if( $duration >= 60 ) {
		$hours = intval( $duration/60 );
		$mins = $duration%60;
		if( $hours >= 24 ) {
			$days = intval( $hours/24 );
			$hours = $hours%24;
			$dur['min'] = $mins;
			$dur['hrs'] = $hours;
			$dur['dys'] = $days;
		} else {
			$dur['min'] = $mins;
			$dur['hrs'] = $hours;
		}
	} else {
		$dur['min'] = $duration;
	}
	$t_passed=$dur;
	if( isset( $t_passed['dys'] ) && $t_passed['dys'] > 0 ) {
		$timeLeft= $t_passed['dys'].' days ago';
		if( isset( $t_passed['dys'] ) && $t_passed['dys'] > 360 ) {
			$timeLeft= number_format($t_passed['dys']/360,2).' Year ago';
		}
	} else {
		if( isset( $t_passed['hrs'] ) && $t_passed['hrs'] > 0 ) {
			$mins = ( $t_passed['min'] > 0 ) ? ' and '.$t_passed['min'].' minuts ' : '';
			$timeLeft= $t_passed['hrs'].' hours'.$mins.' ago';
		} else {
			$timeLeft= $t_passed['min'].' minutes ago';
		}
	}
	return $timeLeft;
}

public function imgSourceReturn($src){
	$url = "";
	if($src!=null){
		if (is_numeric($src)) {
			$url= $this->getMyRecordValue('media', $src, 'path');
		} else {
			$file = "../../media/".$src;
			if (file_exists($file)) {
				$url = $src;
			}else {
				$url."default.jpg";
			}
		}
	}else{
		$url= "default.jpg";
	}
	return  CMS_MEDIA_PATH.$url;

}
public function APIIsValidToken($token,&$id = null){
	if($token == ""){
		$token = isset($_REQUEST['jwt_token']) ? $_REQUEST['jwt_token'] : $this->getBearerToken();
	}
	if($token != ""){
		if(strpos($token, '.') !== false){
			$payload = null;
			if(!$this->validateJwtToken($token, $payload)){
				$responseArray =  array('status' => false,'value'=>'Invalid or expired JWT token');
				$this->displayOutputJson($responseArray);
			}
			if(isset($payload['sub'])){
				$tokenUserId = intval($payload['sub']);
				if($tokenUserId <= 0){
					$responseArray =  array('status' => false,'value'=>'Invalid JWT subject value');
					$this->displayOutputJson($responseArray);
				}
				if(empty($id)){
					$id = $tokenUserId;
				} elseif(intval($id) !== $tokenUserId){
					$responseArray =  array('status' => false,'value'=>'JWT token subject does not match requested user id');
					$this->displayOutputJson($responseArray);
				}
			}
			return true;
		}
		if(empty($id)){
			$responseArray =  array('status' => false,'value'=>'Missing Account id for legacy token validation');
			$this->displayOutputJson($responseArray);
		}
		$count = $this->db->selectCount("select count(*) as count from jwt_tokens_user where id=".$id." and token='".addslashes($token)."'");
		if($count == 0){
			$responseArray =  array('status' => false,'value'=>'Invalid legacy token or account id, Please contact to support team');
			// return API response code is 500
			 http_response_code(500);
			$this->displayOutputJson($responseArray);
		}else{
			return true;
		}
	}
	$responseArray =  array('status' => false,'value'=>'Missing Data for Token or Account id');
	$this->displayOutputJson($responseArray);
}

public function validateJwtToken($token, &$payload = null){
	$payload = JWTHandler::decode($token, JWT_SECRET_KEY);
	if(!$payload || !is_array($payload)){
		return false;
	}
	if(!isset($payload['exp']) || time() > intval($payload['exp'])){
		return false;
	}
	if(!isset($payload['sub'])){
		return false;
	}
	$userId = intval($payload['sub']);
	if($userId <= 0){
		return false;
	}
	$count = $this->db->selectCount("select count(*) as count from jwt_tokens_user where id=".$userId." and jwt_token='".addslashes($token)."' and jwt_expires_at >= NOW()");
	if($count == 0){
		return false;
	}
	return true;
}

public function generateJwtToken($userId){
	$issuedAt = time();
	$expiresAt = $issuedAt + JWT_EXPIRE_SECONDS;
	$payload = array(
		'iss' => SITE_URL,
		'aud' => API_URL,
		'iat' => $issuedAt,
		'exp' => $expiresAt,
		'sub' => $userId,
		'userId' => $userId
	);
	$jwt = JWTHandler::encode($payload, JWT_SECRET_KEY, JWT_ALGORITHM);
	$expiresAtSql = date('Y-m-d H:i:s', $expiresAt);
	
	// Store JWT in the dedicated jwt_tokens_user table to match validation logic.
	if($this->db->selectCount("select count(*) as count from jwt_tokens_user where id=".$userId) == 0){
		$this->db->insert(array(
			'id' => $userId,
			'jwt_token' => $jwt,
			'jwt_expires_at' => $expiresAtSql
		), 'jwt_tokens_user');
	} else {
		$this->db->update("update jwt_tokens_user set jwt_token='".addslashes($jwt)."', jwt_expires_at='".$expiresAtSql."' where id=".$userId);
	}
	return $jwt;
}

public function getJwtToken(){
	$id = $this->optionalParametterValidate($_REQUEST['id'], 'id');
	$token = $this->optionalParametterValidate($_REQUEST['token'], 'token');
	//$this->APIIsValidToken($token, $id);
	$jwt = $this->generateJwtToken($id);
	$responseArray = array(
		'status' => true,
		'value' => 'JWT token created successfully',
		'jwtToken' => $jwt,
		'expiresAt' => date('Y-m-d H:i:s', time() + JWT_EXPIRE_SECONDS),
		'tokenType' => 'Bearer'
	);
	$this->displayOutputJson($responseArray);
}
public function APIIsItemPresents($table,$id){
	if($table!="" && $id!=""){
		$count = $this->db->selectCount("select count(*) as count from ".$table." where id=".$id);
		if($count == 0){
			$responseArray =  array('status' => false,'value'=>'There is no such data availabe for Table:'.$table.' and id:'.$id);
			$this->displayOutputJson($responseArray);
		}else{ return true;
		}
	}else{
		$responseArray =  array('status' => false,'value'=>'Missing Data for Table name and field name');
		$this->displayOutputJson($responseArray);
	}
}
public function APIReturnListData($list){
	if(count($list)!=0){
		$responseArray =  array('status' => true,'value'=>$list);
	}else{
		$responseArray =  array('status' => false,'value'=>'There is no any records found');
	}
	$this->displayOutputJson($responseArray);
}
/////************************************** Application related process APIs **********************************//////
public function getAppConfig(){
	$responseArray = array();

	$appConfig = $this->db->select("select title,email,mobile,whatsapp,facebook_link,insta_link,logo,fevicon from config where id=1");
	if(count($appConfig)==0){
		$responseArray =  array('status' => false,'value'=>'There is no any App Config data found');
	}else{
		$responseArray =  array('status' => true,'value'=>$appConfig[0]);
	}
	$this->displayOutputJson($responseArray);
}
public function outletInfo(){

    $city 	= isset($_REQUEST['city'])?$_REQUEST['city']:"";
    $name 	= isset($_REQUEST['name'])?$_REQUEST['name']:"";
	$table = isset($_REQUEST['table'])?$_REQUEST['table']:"";
	if($this->db->selectCount("select count(*) as count from users where city='".$city."' and username='".$name."'") == 0){
		$responseArray =  array('status' => false,'value' => $this->getTranslatedMessage('MAIN.ERROR_INVALID_ACCOUNT_DETAILS'));
	}else{
		$accountInfo = $this->db->select("select * from users where city='".$city."' and username='".$name."' order by id DESC LIMIT 0,1");
		$accountInfo = $accountInfo[0]?$accountInfo[0]:"";
		if($accountInfo->status == 'NO'){
			$responseArray =  array('status' => false,'value' => $this->getTranslatedMessage('MAIN.ERROR_ACC_DISABLED'));
		}else{
			$checkSubscriptions = $this->db->select("select * from client_subscription where userId=".$accountInfo->id." order by created_date DESC LIMIT 0,1");
			if(count($checkSubscriptions)==0){
				$responseArray =  array('status' => false,'value' => $this->getTranslatedMessage('MAIN.ERROR_NO_ACTIVE_SUBSCRIPTION'));
			}else{
				$subscriptionInfo = $checkSubscriptions[0];
				$package_status = $subscriptionInfo->package_status?$subscriptionInfo->package_status:'';
				$end_date = $subscriptionInfo->end_date?$subscriptionInfo->end_date:'';
				if($package_status === 'DEACTIVE' || $package_status === 'EXPIRED' ){
					$message = $this->getTranslatedMessage('MAIN.ERROR_SUBSCRIPTION_STATUS', array('status' => $package_status, 'date' => $subscriptionInfo->end_date));
					$responseArray =  array('status' => 'false','value' => $message);
				}else{
					$packageId = $subscriptionInfo->pid;
					$packageInfo = array();
					if($packageId!=''){
						$packageInfo = $this->db->selectSingleRowData("packages", $packageId);
					}
					$currentDate = strtotime(date('Y-m-d h:i:s'));
					$end_date = strtotime($end_date.""." 23:59:59");
					if($end_date <= $currentDate){
						$message = $this->getTranslatedMessage('MAIN.ERROR_SUBSCRIPTION_EXPIRED', array('date' => $subscriptionInfo->end_date));
						$responseArray = array('status' => 'false','value' => $message);
					}else{
						//$outlet_hours = $this->db->select("select * from outlet_hours where userId=".$accountInfo->id);
						$outlet_hours_response = array();
						$outlet_hours = $this->db->select("select * from outlet_hours where userId=".$accountInfo->id);
						if(count($outlet_hours)!=0){
							//$outlet_hours = $outlet_hours;
							$outlet_hours = $outlet_hours[0];
							$daysArray = array('sunday','monday','tuesday','wednesday','thursday','friday','saturday');
							//foreach($outlet_hours as $key => $value) {
								//$dayValue = array();
								// add new attribute for each day isToday=true/false and make it true if day maches today's day
								$today = date('l');
								foreach($daysArray as $day){
									$dayValue = $outlet_hours->$day;
									$openKey = $day.'_open';
									$closeKey = $day.'_close';
									$day_open_time = $outlet_hours->$openKey?$outlet_hours->$openKey:'00:00';
									$day_close_time = $outlet_hours->$closeKey?$outlet_hours->$closeKey:'00:00';
									$isOpen = false;
									if($dayValue == 'YES'){
										$isOpen = true;
									}
									// display string E.g: Open 10:00 AM - 11:59 PM
									$displaystring = $isOpen ? 'Open '.date('g:i A', strtotime($day_open_time)).' - '.date('g:i A', strtotime($day_close_time)) : 'Closed'; 
									array_push($outlet_hours_response, array('isBold'=>ucfirst($day) == $today ? true : false, 'day'=>ucfirst($day),'isOpen'=>$isOpen,'open'=>$day_open_time,'close'=>$day_close_time,'label'=>$displaystring) );
								}
								//$outlet_hours[$key]->days = $dayValue;
							//}
							//$dayValue = 
						}
						// logic for menuGroup
						$menuGroupResponse = array();
						if($accountInfo->isGroupShow == 'YES'){
							$menuGroup = $this->db->select("select g.id,g.title,g.img,i.photo from category c,groups g , images i where i.id=g.img and c.group_id=g.id and c.userId=".$accountInfo->id."  group by c.group_id order by c.sq asc");
							if(count($menuGroup)!=0){
								array_push($menuGroupResponse,array('id'=>'all','title'=>'ALL','img'=>0,'photo'=>'category/defaultCategory.png'));
								for($g=0;$g<count($menuGroup);$g++){
									array_push($menuGroupResponse,$menuGroup[$g]);
								}
							}
						}
						// logic for table QR valication if table param is passed
						$tableInfo = array();
						if($table != ""){
							$checkTable = $this->db->select("select id,tableId,isOrderButton,status from qrcode where userId=".$accountInfo->id." and tableId	='".$table."' and status='YES'");
							if(count($checkTable) == 0){
								$responseArray =  array('status' => false,'value' => 'Invalid Table QR Code or QR Code has been disabled by outlet admin');
								$this->displayOutputJson($responseArray);
							}else{
								$tableInfo = $checkTable[0];								
							}
						}
						// logic for add logs for the track page visit
						$_REQUEST['page'] = 'menu';
						$_REQUEST['data'] = $city;
						$_REQUEST['key'] = $name;
						$_REQUEST['value'] = $table;
						$this->db->pageTrack();

						$responseArray = array('status' => true,'value' =>'Account validated successfully','accountInfo'=>$accountInfo,'outlet_hours'=>$outlet_hours_response,'menuGroup'=>$menuGroupResponse,'tableInfo'=>$tableInfo,'subscriptionInfo'=>$subscriptionInfo,'packageInfo'=>$packageInfo);
					}
				}
				
			}
			
		}
	}
	$this->displayOutputJson($responseArray);
}

public function getData(){
	$responseArray = array();
	$allowedTables = array('category', 'menu', 'banner', 'food_order', 'bill', 'bill_items');
	$table = isset($_REQUEST['table']) ? $_REQUEST['table'] : "";
	$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : "";
	$token = isset($_REQUEST['token']) ? $_REQUEST['token'] : "";
	
	$this->optionalParametterValidate($id, 'id');
	$this->APIIsItemPresents('users', $id);
	$this->APIIsValidToken($token, $id);
	
	if(!in_array($table, $allowedTables)){
		$responseArray = array('status' => false, 'value' => 'Invalid Table name for fetch data');
		$this->displayOutputJson($responseArray);
	}
	
	$data = $this->db->select("SELECT * FROM " . $table . " WHERE userId=" . $id . " AND status='YES' ORDER BY sq ASC");
	
	if(count($data) == 0){
		$responseArray = array('status' => false, 'value' => 'There are no records found for table: ' . $table);
	} else {
		$responseArray = array('status' => true, 'value' => 'Data fetched successfully', 'table' => $table, 'data' => $data);
	}
	
	$this->displayOutputJson($responseArray);
}
public function getBanners(){
	$id 		= isset($_REQUEST['id'])?$_REQUEST['id']:"";
	$token 	= isset($_REQUEST['token'])?$_REQUEST['token']:"";
	$this->optionalParametterValidate($_REQUEST['id'], 'id');
	$this->APIIsValidToken($token, $id);
	$banners = $this->db->select("select * from banner where userId=".$id." and status='YES' order by sq asc ");
	$returnData = array();
	for($b=0;$b<count($banners);$b++){
		$bannerInnerObject = $banners[$b];
		//$bannerInnerObject->photo = $this->imgSourceReturn($bannerInnerObject->photo);
		$duration = $bannerInnerObject->duration;
		if($duration == 'all' || $duration == "" || $duration == null){
			array_push($returnData, $bannerInnerObject);
		}else{
			$dates = $bannerInnerObject->dates;
			$dates = explode(' - ', $dates);
			$startDate =  date("Y-m-d", strtotime($dates[0]));
			$endDate =  date("Y-m-d", strtotime($dates[1]));
			$currentDate = date("Y-m-d");
			if($currentDate >= $startDate && $currentDate <= $endDate){
				array_push($returnData, $bannerInnerObject);
			}
		}
		
	}	
	$this->APIReturnListData($returnData);
}
public function getReviews(){
	$id 		= isset($_REQUEST['id'])?$_REQUEST['id']:"";
	$token 	= isset($_REQUEST['token'])?$_REQUEST['token']:"";
	$this->optionalParametterValidate($_REQUEST['id'], 'id');
	$this->APIIsValidToken($token, $id);
	$isFeedbackdisplay = false;
	$isFeedbackCustomQue = false;
	$$isFeedback = $this->db->select("select isFeedback,isFeedbackCustomQue from users where id=".$id);
	if(count($$isFeedback)!=0){
		$isFeedbackdisplay = $$isFeedback[0]->isFeedback;
		$isFeedbackCustomQue = $$isFeedback[0]->isFeedbackCustomQue;
	}
	if($isFeedbackdisplay == 'NO'){
		$responseArray =  array('status' => false,'value'=>'Feedback option has been disabled by outlet admin');
	
	}else{

		$reviews = $this->db->select("select * from feedback where userId=".$id." order by created_date desc ");
		//$avgRating = $this->db->select("select AVG(rating) as avgRating from feedback where userId=".$id);
		$avgRating = $avgRating[0]->avgRating?$avgRating[0]->avgRating:0;
		//// calculate avg rating
		$totalReviews = count($reviews);
		$sumRating = 0;
		for($r=0;$r<$totalReviews;$r++){
			$sumRating = $sumRating + intval($reviews[$r]->rating);
		}
		$avgRating = 0;
		if($totalReviews!=0){
			$avgRating = $sumRating / $totalReviews;
		}
		// calculate total number of reviews for each ratiing numbers 1 to 5
		$ratingCountArray = array();
		for($i=1;$i<=5;$i++){
			$ratingCount = 0;
			for($r=0;$r<$totalReviews;$r++){
				if(intval($reviews[$r]->rating) == $i){
					$ratingCount = $ratingCount + 1;					
				}
			}
			$ratingCountArray[$i] = $ratingCount;
			$ratingPercentages[$i] = round(($ratingCount / $totalReviews) * 100, 2);
		}
	
		$responseArray =  array('status' => true,'value'=>'Customer reviews fetched successfully','isFeedbackCustomQue'=>$isFeedbackCustomQue,'reviews'=>$reviews,'avgRating'=>round($avgRating,1),'totalReviews'=>$totalReviews,'ratingCountArray'=>$ratingCountArray,'ratingPercentages'=>$ratingPercentages,'');
	}
	$this->displayOutputJson($responseArray);

}
public function getReviewQuestions(){
	$this->getBodyJsonData();
	$id 		= isset($_REQUEST['id'])?$_REQUEST['id']:"";
	$token 	= isset($_REQUEST['token'])?$_REQUEST['token']:"";
	$this->optionalParametterValidate($_REQUEST['id'], 'id');
	$this->APIIsValidToken($token, $id);
	$questions = $this->db->select("select * from feedback_fields where userId=".$id." order by created_date desc LIMIT 0,1");
	if(count($questions)==0){
		$responseArray =  array('status' => false,'value'=>'There is no any custom questions found for reviews');
	}else{
		$questions = $questions[0]?$questions[0]:"";
		$questionList = array();
		for($i=1;$i<=5;$i++){
			$questionKey = 'field_'.$i.'_que';
			if($questions->$questionKey != null && $questions->$questionKey != ""){
				array_push($questionList, $questions->$questionKey);
			}
		}
		$responseArray =  array('status' => true,'value'=>'Custom questions found for reviews','questions'=>$questionList);
	}
	$this->displayOutputJson($responseArray);
}
public function addReview(){
	//id=5&token=of0prgMawPXEaglEpHIm5zAA9iNIwuExRzDAkxsVTIe&name=Vishwajeet%20Mahadik&mobile=7709034176&rating=4&comment=123123&date=Wed%20Jan%2007%202026%2020:40:03%20GMT%2B0530%20(India%20Standard%20Time)
	$id = isset($_REQUEST['id'])?$_REQUEST['id']:"";
	$token = isset($_REQUEST['token'])?$_REQUEST['token']:"";
	$name = isset($_REQUEST['name'])?$_REQUEST['name']:"";
	$mobile = isset($_REQUEST['mobile'])?$_REQUEST['mobile']:"";
	$rating = isset($_REQUEST['rating'])?$_REQUEST['rating']:"";
	$comment = isset($_REQUEST['comment'])?$_REQUEST['comment']:"";
	$rating_factores = isset($_REQUEST['rating_factores'])?$_REQUEST['rating_factores']:"";
	$date = date('Y-m-d');
	$this->optionalParametterValidate($_REQUEST['id'], 'id');
	$this->APIIsValidToken($token, $id);
	$status = "YES";
	if($rating == "1" || $rating == "2" )$status = "NO";
	if($name == "" || $mobile == "" || $rating == "" || $comment == ""){
		$responseArray =  array('status' => false,'value'=>'Please enter all required fields for review');
		$this->displayOutputJson($responseArray);
	}else{	
		$insertArray = array(
			'userId' => $id,
			'title' => $name,
			'mobile' => $mobile,
			'rating' => $rating,
			'message' => $comment,
			'date' => $date,
			'rating_factores' => $rating_factores,
			'status' => $status
		);
		$insertId = $this->db->insert($insertArray, "feedback");
		$feedbackInfo = $this->db->selectSingleRowData("feedback", $insertId);		
		$this->displayOutputJson(array('status' => true,'value'=>'Feedback added successfully', 'feedbackInfo'=>$feedbackInfo));	
		}
}
public function addToCartTakeAway(){
	$this->getBodyJsonData();
	$ip = $_SERVER['REMOTE_ADDR'];
	$date = date('Y-m-d');
	$dateTime = date('Y-m-d H:i:s');
	$customer = isset($_REQUEST['customer']['name'])?$_REQUEST['customer']['name']:"";
	$mobile = isset($_REQUEST['customer']['mobile'])?$_REQUEST['customer']['mobile']:"";
	$address = isset($_REQUEST['customer']['address'])?$_REQUEST['customer']['address']:"";
	$status = $_REQUEST['status']?$_REQUEST['status']:"";
	$tableNo = isset($_REQUEST['tableNo'])?$_REQUEST['tableNo']:"";
	$items = isset($_REQUEST['items'])?$_REQUEST['items']:"";
	$outletId = isset($_REQUEST['outletId'])?$_REQUEST['outletId']:"";
	$outletToken = isset($_REQUEST['outletToken'])?$_REQUEST['outletToken']:"";
	$token = isset($_REQUEST['token'])?$_REQUEST['token']:"";
	$total = isset($_REQUEST['total'])?$_REQUEST['total']:0;
	$this->optionalParametterValidate($_REQUEST['outletId'], 'outletId');
	$this->APIIsValidToken($outletToken, $outletId);
	$inserIdAray = array();
	//$items = json_encode($items);
	for($i=0;$i<count($items);$i++){

		$menuId = $items[$i]['id'];
		$quantity = $items[$i]['quantity'];
		$rate = $items[$i]['rate'];
		$type = $items[$i]['type'];
		$note = $items[$i]['note'];
		$total = $total + ($rate * $quantity);
		if($type == 'TA'){
			$tableNo = $type.'-'.$mobile;
		}
		$insertArray = array(
			'userId' => $outletId,
			'name' => $customer,
			'tableNo'=>$tableNo,
			'menuId' => $menuId,
			'qty'=> $quantity,
			'ip'=> $ip,
			'type'=> $type,	
			'order_note'=>$address,
			'note'=> $note,
			'status' =>$status,
			'date' => $date,
			'date_time' => $dateTime,
			'created_date' => $dateTime,
			'updated_date' => $dateTime
		);
		$insertId = $this->db->insert($insertArray, 'food_order');
		array_push($inserIdAray, $insertId);
	}
	$userInfo = $this->db->selectSingleRowData("users", $outletId);
	$orderInfo = array('customer'=>$customer,'mobile'=>$mobile,'address'=>$address,'total'=>$total,'items'=>count($items));
	$this->takeAwayEmailTrigger($userInfo, $orderInfo);
	$this->displayOutputJson(array('status' => true,'value'=>'Data received successfully', 'data'=>$inserIdAray));
}
public function addToCartOrder(){
	$this->getBodyJsonData();
	$ip = $_SERVER['REMOTE_ADDR'];
	$date = date('Y-m-d');
	$dateTime = date('Y-m-d H:i:s');
	$menuId = isset($_REQUEST['menuId'])?$_REQUEST['menuId']:"";
	$quantity = isset($_REQUEST['quantity'])?$_REQUEST['quantity']:"";
	$type = isset($_REQUEST['type'])?$_REQUEST['type']:"";
	$note = isset($_REQUEST['note'])?$_REQUEST['note']:"";
	$outletId = isset($_REQUEST['outletId'])?$_REQUEST['outletId']:"";
	$outletToken = isset($_REQUEST['outletToken'])?$_REQUEST['outletToken']:"";
	$token = isset($_REQUEST['token'])?$_REQUEST['token']:"";
	$this->optionalParametterValidate($_REQUEST['outletId'], 'outletId');
	$tableNo = isset($_REQUEST['tableNo'])?$_REQUEST['tableNo']:"";
	$status = isset($_REQUEST['status'])?$_REQUEST['status']:"";
	$notes = isset($_REQUEST['notes'])?$_REQUEST['notes']:"";
	$this->APIIsValidToken($outletToken, $outletId);
	$insertArray = array(
			'userId' => $outletId,
			'tableNo'=>$tableNo,
			'menuId' => $menuId,
			'qty'=> $quantity,
			'ip'=> $ip,
			'type'=> $type,
			'note'=> $notes,
			'status' =>$status,
			'date' => $date,
			'date_time' => $dateTime,
			'created_date' => $dateTime,
			'updated_date' => $dateTime
		);
		$insertId = $this->db->insert($insertArray, 'food_order');
		$this->displayOutputJson(array('status' => true,'value'=>'Order added successfully', 'data'=>$insertId));	
}
public function getTableOrders(){
	$this->getBodyJsonData();
	$id = isset($_REQUEST['id'])?$_REQUEST['id']:"";
	$token = isset($_REQUEST['token'])?$_REQUEST['token']:"";
	$tableNo = isset($_REQUEST['tableNo'])?$_REQUEST['tableNo']:"";
	$this->optionalParametterValidate($_REQUEST['id'], 'id');
	$this->optionalParametterValidate($_REQUEST['tableNo'], 'tableNo');
	$this->APIIsValidToken($token, $id);
	$orders = $this->db->select("select o.id,o.menuId,m.title as name,m.rate,o.qty,o.status,o.note,o.tableNo,o.created_date,o.updated_date from food_order o  , menu m where o.menuId=m.id and o.userId=".$id." and o.tableNo='".$tableNo."' and o.status!='CLEAR' and o.status!='TA' and o.date_time >  SUBDATE( CURRENT_TIMESTAMP, INTERVAL 6 HOUR) order by created_date DESC ");
	if(count($orders)==0){
		$responseArray =  array('status' => false,'value'=>'There is no any orders found for this table');
	}else{
		$responseArray =  array('status' => true,'value'=>'Orders fetched successfully','orders'=>$orders);
	}
	$this->displayOutputJson($responseArray);
}
public function getLastBill(){
	$this->getBodyJsonData();
	$id = isset($_REQUEST['id'])?$_REQUEST['id']:"";
	$token = isset($_REQUEST['token'])?$_REQUEST['token']:"";
	$tableNo = isset($_REQUEST['tableNo'])?$_REQUEST['tableNo']:"";
	$this->optionalParametterValidate($_REQUEST['id'], 'id');
	$this->optionalParametterValidate($_REQUEST['tableNo'], 'tableNo');
	$this->APIIsValidToken($token, $id);
	$bill = $this->db->select("select * from bill where userId=".$id." and tableNo='".$tableNo."' order by created_date DESC LIMIT 0,1");
	if(count($bill)==0){
		$responseArray =  array('status' => false,'value'=>'There is no any bill found for this table');
	}else{
		$billItems = $this->db->select("select * from bill_items  where userId='".$id."' and tableId='".$tableNo."' and billId=".$bill[0]->id);
		$responseArray =  array('status' => true,'value'=>'Bill fetched successfully','billId'=>$bill[0]->id,'bill'=>$bill[0],'billItems'=>$billItems);
	}
	$this->displayOutputJson($responseArray);
}
public function saveGuestInfo(){
	$this->getBodyJsonData();
	$id = isset($_REQUEST['id'])?$_REQUEST['id']:"";
	$token = isset($_REQUEST['token'])?$_REQUEST['token']:"";
	$name = isset($_REQUEST['name'])?$_REQUEST['name']:"";
	$phone = isset($_REQUEST['phone'])?$_REQUEST['phone']:"";	
	$this->optionalParametterValidate($_REQUEST['id'], 'id');
	$this->optionalParametterValidate($_REQUEST['name'], 'name');
	$this->optionalParametterValidate($_REQUEST['phone'], 'phone');
	$this->APIIsValidToken($token, $id);
	$ip = $_SERVER['REMOTE_ADDR'];
	if($this->db->selectCount("select count(*) as count from guest_info where userId=".$id." and title='".$name."' and phone='".$phone."' and date='".date('Y-m-d')."'")>0){
		$this->db->update("update guest_info set ip='".$ip."', updated_date=NOW() where userId=".$id." and title='".$name."' and phone='".$phone."' and date='".date('Y-m-d')."'");
		$responseArray =  array('status' => false,'value'=>'Guest info with this phone number already saved for today');
	}else{

	$insertArray = array(
			"userId"=>$id,
			"title"=>$name,
			"phone"=>$phone,
			"date"=>date('Y-m-d'),
			"ip"=>$ip
	);
	$insertId = $this->db->insert($insertArray, 'guest_info');
	$this->db->update("update guest_info set updated_date=NOW() where id=".$insertId);
	if($insertId){
		$responseArray =  array('status' => true,'value'=>'Guest info saved successfully', 'data'=>$insertId);
	}else{
		$responseArray =  array('status' => false,'value'=>'Failed to save guest info');
	}
	}
	$this->displayOutputJson($responseArray);
}
	// function for waiter call button
	public function callButtonWaiter(){
	$responseArray = array();
	$this->getBodyJsonData();
	$id = isset($_REQUEST['id'])?$_REQUEST['id']:"";
	$token = isset($_REQUEST['token'])?$_REQUEST['token']:"";
	$tableNo = isset($_REQUEST['tableNo'])?$_REQUEST['tableNo']:"";
	$this->optionalParametterValidate($_REQUEST['id'], 'id');
	$this->optionalParametterValidate($_REQUEST['tableNo'], 'tableNo');
	$this->APIIsValidToken($token, $id);
		$insertArray = array(
				"tableNo"=>$tableNo,
				"userId"=>$id,
				"date"=>date('Y-m-d')
		);
		$call = $this->db->insert($insertArray, 'call_button');
		if($call){
			$responseArray = array('status' => 'true','value' =>'Call button triggered','action'=>'callButton');
		}else{
			$responseArray = array('status' => 'false','value' =>'fail to Call button trigger','action'=>'callButton');
		}
		$this->displayOutputJson($responseArray);
	}
public function popularItems(){
$this->getBodyJsonData();
	$id 		= isset($_REQUEST['id'])?$_REQUEST['id']:"";
	$token 	= isset($_REQUEST['token'])?$_REQUEST['token']:"";
	$this->optionalParametterValidate($_REQUEST['id'], 'id');
	$this->APIIsValidToken($token, $id);
	$popularItems = $this->db->select("SELECT m.id, m.title, m.cid,m.img as mediaID, IFNULL(NULLIF(i.photo, ''), 'https://1menus.com/app/b2c/assets/img/foodIcon.png') AS img FROM menu m LEFT JOIN images i ON m.img = i.id WHERE m.userId=".$id." AND m.populate='YES' AND m.status='YES' ORDER BY m.sq ASC");
	$this->APIReturnListData($popularItems);
}
public function getOutletInfo($return=false){
	$id 		= isset($_REQUEST['id'])?$_REQUEST['id']:"";
	$mobile 	= isset($_REQUEST['mobile'])?$_REQUEST['mobile']:"";
	$username 	= isset($_REQUEST['username'])?$_REQUEST['username']:"";
	$token 	= isset($_REQUEST['token'])?$_REQUEST['token']:"";
	$this->optionalParametterValidate($_REQUEST['id'], 'id');
	$this->optionalParametterValidate($_REQUEST['mobile'], 'mobile');
	$this->optionalParametterValidate($_REQUEST['username'], 'username');
	//$this->APIIsValidToken($token, $id);
	if($this->db->selectCount("select count(*) as count from users where id=".$id." and mobile='".$mobile."' and username='".$username."' and token='".$token."'" )==0){
		$responseArray =  array('status' => 'false','value' =>'Invalid Details for fetch data for outlets');
	}else{
		$info = $this->db->select("select * from users where id=".$id." and mobile='".$mobile."' and username='".$username."' and token='".$token."'");
		if($info[0]->status == 'NO'){
			$responseArray =  array('status' => 'false','value' =>'Outlet account has been disabled');
		}else{
			$info[0]->password = "***";
		$responseArray =  array('status' => 'true','value' =>$info[0]);
		}
	}
		$this->displayOutputJson($responseArray);
}
public function getLiveOrders(){
	$responseArray = array();
	$latestTimestamp = "";
	$userId = isset($_REQUEST['id'])?$_REQUEST['id']:'';
	$lastUpdated = isset($_REQUEST['lastUpdated'])?$_REQUEST['lastUpdated']:'';
	$history = isset($_REQUEST['history'])?$_REQUEST['history']:'';
	$sqlTables =  "select title,name,tableNo,date_time from food_order where  userId=".$userId. " and status!='TA' ";
	if($history != 'YES'){
		$sqlTables.=" and status!='CLEAR' and  date_time >  SUBDATE( CURRENT_TIMESTAMP, INTERVAL 6 HOUR) ";
	}
	if($lastUpdated!=""){
		$sqlTables.=" and updated_date > '".$lastUpdated."'";
	}
	$sqlTables.=" group by tableNo order by date_time DESC";
	$orderTable = $this->db->select($sqlTables);	
	$address = $orderTable[0]->title?$orderTable[0]->title:'';
	$lastUpdated = $orderTable[0]->date_time;
	$userIfInfp = $this->db->selectSingleRowData("users", $userId);
	if(count($orderTable)!=0){
		for($t=0;$t<count($orderTable);$t++){
			$table = $orderTable[$t]->tableNo?$orderTable[$t]->tableNo:'';
			$name = $orderTable[$t]->name?$orderTable[$t]->name:'';
			$ssqqll = "select o.id,o.menuId,m.title as name,m.rate,o.qty,o.status,o.note,o.tableNo,o.created_date,o.updated_date from food_order o  , menu m where o.menuId=m.id and o.userId=".$userId." and o.tableNo='".$table."' ";
			if($history != 'YES'){
				$ssqqll.=" and o.status!='CLEAR' and  o.date_time >  SUBDATE( CURRENT_TIMESTAMP, INTERVAL 6 HOUR) ";
			}
			if($lastUpdated!=""){
				$sqlTables.=" and o.updated_date > '".$lastUpdated."'";
			}
			$ssqqll.=" order by created_date DESC ";
			$tableMenus = $this->db->select($ssqqll);
			$totalItems = count($tableMenus);
			$orderId =  $tableMenus[$totalItems-1]->updated_date;
			$orderId = str_replace('-','',$orderId);			
			$orderId = str_replace(':','',$orderId);
			$orderId = str_replace(' ','',$orderId);
			
			if($latestTimestamp < $tableMenus[0]->updated_date){
				$latestTimestamp = $tableMenus[0]->updated_date;
			}
			//$orderId = intval($$orderId);
			$responseArray[$t] = array('orderId'=>$orderId,'lastUpdated'=>$tableMenus[0]->updated_date?$tableMenus[0]->updated_date:$lastUpdated,'table'=>$table,'totalIems'=>$totalItems,'foodItems'=>$tableMenus);
		}
	}
	$this->displayOutputJson(array('status'=>true,'totalOrder'=>count($responseArray),'lastUpdated'=>$latestTimestamp,'value'=>$responseArray?$responseArray:'There are no any Order found'));
}

public function getOrders(){
	$id 		= isset($_REQUEST['id'])?$_REQUEST['id']:"";
	$all 		= isset($_REQUEST['all'])?$_REQUEST['all']:"";
	$token 	= isset($_REQUEST['token'])?$_REQUEST['token']:"";
	$lastUpdated = isset($_REQUEST['lastUpdated'])?$_REQUEST['lastUpdated']:"";
	$status = isset($_REQUEST['status'])?$_REQUEST['status']:"";
	$this->APIIsItemPresents('users', $id);	
	$this->APIIsValidToken($token, $id);
	$sql = "select o.menuId ,o.qty,m.title as menu,m.rate ,o.tableNo,o.date,o.id,o.name,o.created_date,o.status from food_order o, menu m ,users u where u.id=o.userId and o.menuId=m.id and o.userId=".$id." and u.token='".$token."'  ";
	
	if($lastUpdated!=""){
		$sql.=" and  o.created_date > '".$lastUpdated."'";
	}
	if($status == "OPEN"){
		$sql.=" and  o.status!= 'CLEAR'";
	}
	if($status == "CLOSE"){
		$sql.=" and  o.status = 'CLEAR'";
	}
	$sql.= " order by o.created_date DESC ";
	if($all==""){
		$sql.=" limit 0,500";
	}else{
		$sql.=" limit 0,10000";
	}
	
	$orders = $this->db->select($sql);
	$this->APIReturnListData($orders);
}
public function getBills(){
	$id 		= isset($_REQUEST['id'])?$_REQUEST['id']:"";
	$token 	= isset($_REQUEST['token'])?$_REQUEST['token']:"";
	$date 		= isset($_REQUEST['date'])?$_REQUEST['date']:"";
	$start_date 		= isset($_REQUEST['start_date'])?$_REQUEST['start_date']:"";
	$end_date 		= isset($_REQUEST['end_date'])?$_REQUEST['end_date']:"";
	$lastUpdated = isset($_REQUEST['lastUpdated'])?$_REQUEST['lastUpdated']:"";
	$all 		= isset($_REQUEST['all'])?$_REQUEST['all']:"";
	$this->APIIsItemPresents('users', $id);
	$this->APIIsValidToken($token, $id);
	$sql = "select id,tableNo,date,totalItems,subtotal,SGST,CGST,total,status,created_date,isPaid from bill where userId=".$id;
	if($date!=""){
		$sql.=" and date='".$date."'";
	}
	if($start_date!="" && $end_date!=""){
		//$sql.=" and  (date BETWEEN  '".$start_datee."' and  '".$end_date."')";
	}
	if($lastUpdated!=""){
		$sql.=" and  created_date > '".$lastUpdated."'";
	}
	$sql.=" order by created_date DESC";
	if($all==""){
		$sql.=" limit 0,100";
	}else{
		$sql.=" limit 0,10000";
	}
	$bills = $this->db->select($sql);
	$returnData = array();
	for($b=0;$b<count($bills);$b++){
		$billInnerObject = $bills[$b];
		$billInnerObject->totalRound = "".round($billInnerObject->total);
		//$billInnerObject->name = $billInnerObject->name?$billInnerObject->name:'';
		$billInnerObject->isPaid = $billInnerObject->isPaid?$billInnerObject->isPaid:'';
		$billItems = $this->db->select("select menuName,qty,rate from bill_items where billId=".$bills[$b]->id." and userId=".$id);
		array_push($returnData, array('bill'=>$billInnerObject,'Items'=>$billItems));
	}
	$this->APIReturnListData($returnData);
}

public function getCategory($onlyIds=false){
	$this->getBodyJsonData();
	$onlyIdsArray = array();
	$id 		= isset($_REQUEST['id'])?$_REQUEST['id']:"";
	$token 	= isset($_REQUEST['token'])?$_REQUEST['token']:"";
	$this->APIIsItemPresents('users', $id);
	$this->APIIsValidToken($token, $id);
	$images = $this->getImagesArray();
	$this->APIIsValidToken($token, $id);
	$sql = "select * from category where userId=".$id." order by sq ASC";
	$categoryList = $this->db->select("select * from category where status='YES' and userId=".$id." order by sq ASC");
	for($c=0;$c<count($categoryList);$c++){
		if($categoryList[$c]->img!=""){
			if(array_key_exists($categoryList[$c]->img, $images)){
				$categoryList[$c]->img = $images[$categoryList[$c]->img];
			}else{
				$categoryList[$c]->img = "";
			}
		}
		$isAllTime = $categoryList[$c]->isAllTime?$categoryList[$c]->isAllTime:'NO';
		if($isAllTime == 'NO'){
			$currentTime = date("H:i");
			$slot1_open_time = $categoryList[$c]->slot1_open_time?$categoryList[$c]->slot1_open_time:'00:00';
			$slot1_close_time = $categoryList[$c]->slot1_close_time?$categoryList[$c]->slot1_close_time:'00:00';
			$slot2_open_time = $categoryList[$c]->slot2_open_time?$categoryList[$c]->slot2_open_time:'00:00';
			$slot2_close_time = $categoryList[$c]->slot2_close_time?$categoryList[$c]->slot2_close_time:'00:00';
			if(!(($currentTime >= $slot1_open_time && $currentTime <= $slot1_close_time) || ($currentTime >= $slot2_open_time && $currentTime <= $slot2_close_time))){
				unset($categoryList[$c]);
			}else{
				array_push($onlyIdsArray, $categoryList[$c]->id);
			}
		}else{
			array_push($onlyIdsArray, $categoryList[$c]->id);
		}
	}
	if($onlyIds){
		return implode(',',$onlyIdsArray);
	}else{
		$this->APIReturnListData($categoryList);
	}
}
public function getItems($categoryId=""){
		$this->getBodyJsonData();
		
		
	$id 		= isset($_REQUEST['id'])?$_REQUEST['id']:"";
	$token 	= isset($_REQUEST['token'])?$_REQUEST['token']:"";
	$limit 	= isset($_REQUEST['limit'])?$_REQUEST['limit']:"";
	$cids 	= isset($_REQUEST['cids'])?$_REQUEST['cids']:"";
	if($limit!=""){
		$limit = " limit 0,".$limit." ";
	}
	$this->APIIsItemPresents('users', $id);
	$images = $this->getImagesArray();
	$this->APIIsValidToken($token, $id);
	$sql = "select m.*,c.title as categoryName,c.subTitle as categorySubTitle from menu m , category c where m.cid=c.id and m.userId=".$id." ";
	if($categoryId!=""){
		$sql.=" and c.id=".$categoryId." ";
	}
	if($cids!=""){
		
		$sql.=" and c.id IN (".$cids.") ";
	}
	$sql.=" order by c.sq , m.sq ASC ".$limit;
	
	$menuList = $this->db->select($sql);
	 for($m=0;$m<count($menuList);$m++){
		if($menuList[$m]->img!=""){
			$menuList[$m]->img = $images[$menuList[$m]->img];
		}
	}
	if($categoryId!="")
		return $menuList;
	else
		$this->APIReturnListData($menuList);
}

public function getCategoryItems(){
	$id 		= isset($_REQUEST['id'])?$_REQUEST['id']:"";
	$token 	= isset($_REQUEST['token'])?$_REQUEST['token']:"";
	$this->APIIsItemPresents('users', $id);
	$this->APIIsValidToken($token, $id);
	$images = $this->getImagesArray();
	$collectionArray = array();
	$categoryList = $this->db->select("select id,title,subTitle,img,vegType from category where status='YES' and userId=".$id." order by sq ASC");
	for($c=0;$c<count($categoryList);$c++){
		if($categoryList[$c]->img!=""){
			$categoryList[$c]->img = $images[$categoryList[$c]->img];			
			$categoryList[$c]->items = $this->getItems($categoryList[$c]->id);
		}
	}
	$this->APIReturnListData($categoryList);
	//echo $sql = "select m.id,m.sq,m.cid,m.title,m.subTitle,m.vegType,m.img,m.populate,m.rate,m.ac_rate,m.rate_big,m.rate_discount,m.rate_tax,m.tag,m.status,c.title as categoryName from menu m , category c , users u where m.cid=c.id and m.userId=u.id  and c.userId=".$id." and m.userId=".$id." order by c.sq , m.sq ASC";
	
}
// function for get image Items
public function getImagesArray(){
	$getGalaryData = $this->db->select("select * from images where 1 order by id ASC");
	$getGalaryDataArray = array();
	for($g = 0 ; $g<count($getGalaryData);$g++){
		$getGalaryDataArray[$getGalaryData[$g]->id]  = CMS_MEDIA_PATH."".$getGalaryData[$g]->photo;
	}
	return $getGalaryDataArray;
}
public function updateInlineData(){
	$table = $this->optionalParametterValidate($table, 'table');
	$key = $this->optionalParametterValidate($key, 'key');
	$value = $this->optionalParametterValidate($value, 'value');
	$id = $this->optionalParametterValidate($key, 'id');
	if($this->$db->selectCount("select count(*) as count from ".$table." where id=".$id)!=0){
		$this->db->update("update  ".$table." set  ".$key."='".$value."' where id=".$id);
		$responseArray =  array('status' => true,'value' =>'The formation for '.ucfirst($key).' has been updated');
	}else{
		$responseArray =  array('status' => false,'value' =>'Invalid Details for update information');
	}
	$this->APIReturnListData($returnData);
}
public function updateRoomStatus(){
	$userId =$this->optionalParametterValidate($userId, 'userId');
	$token =$this->optionalParametterValidate($_REQUEST['token'], 'token');
	if($this->db->selectCount("select count(*) as count from users where id=".$userId)==0){
		$responseArray =  array('status' => false,'value' =>'Invalid Details for fetch data for outlets');
	}else{
	
		$info = $this->db->select("select * from users where id=".$userId." and token='".$token."'");
		if(count($info) == 0 ){
			$responseArray =  array('status' => false,'value' =>'Invalid Tocken or UserId');
		}else {
	
			if($info[0]->status == 'NO'){
				$responseArray =  array('status' => false,'value' =>'Outlet account has been disabled');
			}else{
				
				$roomNo =$this->optionalParametterValidate($_REQUEST['roomNo'], 'roomNo');
				if($roomNo!=""){
					if($this->db->selectCount("select count(*) as count from room_service_rooms where userId=".$userId." and title='".$roomNo."'")==0){
						$responseArray =  array('status' => false,'value' =>'The Room Number ('.$roomNo.') Is not exist in our system');
					}else{
						$status =$this->optionalParametterValidate($_REQUEST['status'], 'status');
						if($status == "NO" || $status == "0" || $status == 0 || $status == false || $status == "false"){
							$status = "NO";
						}else{
							$status = "YES";
						}
						$this->db->update("update room_service_rooms set status='".$status."' where userId=".$userId." and title='".$roomNo."'");
						$responseArray =  array('status' => true,'value' =>"The Room Availability Status Update done");
					}
				}else{
					$responseArray =  array('status' => false,'value' =>'Room Number Should not be empty');
				}
			}
		}
	}
	$this->displayOutputJson($responseArray);
}
public function signup(){
	$name 		= isset($_REQUEST['name'])?$_REQUEST['name']:"";
	$email 		= isset($_REQUEST['email'])?$_REQUEST['email']:"";
	$mobile 	= isset($_REQUEST['mobile'])?$_REQUEST['mobile']:"";
	$password 	= isset($_REQUEST['password'])?$_REQUEST['password']:"";
	$app_type 	= isset($_REQUEST['app_type'])?$_REQUEST['app_type']:"";
	$this->emailValid($this->request['email']);
	$this->mobileValid($this->request['mobile']);
	$passwordCore = $password;
	$otp = $this->generateRandomToken(6);
	
	$insertArray = array(
			'name'=>$name,
			'email'=>$email,
			'mobile'=>$mobile,
			'password'=>md5($password),
			'otp'=>$otp,
			'app_type'=>$app_type,
			'photo'=>SITE_URL."plugins/profile_upload/uploades/medium/defaultUser.png",
			'status'=>'non-verifyed'	
			);
	if($this->db->selectCount("select count(*) as count from customer where mobile='".$mobile."'")==0){
		$userID = $this->db->insert($insertArray, 'customer');
		if($userID){
			$otpMsg = APP_SMS_1."".$otp;
			$insertArrayShow = array(
						'Name'=>$name,
						'Email'=>$email,
						'Mobile'=>$mobile,
						'Password'=>$passwordCore,
						'OTP Code'=>$otp
				);
				
				$to		 		= $this->getMyRecordValue('customer', $userID, 'email');
				$name 			= $this->getMyRecordValue('customer', $userID, 'name');
				$link 			= "<a target='_blank' href='".$this->websiteLink."'>".$this->websiteTitle."</a>";
				$subject 		= "Welcome to ".$this->websiteTitle."  | Account Email / Mobile number verification on ".$this->websiteTitle;
				$last 			= "For more infomation please contact at ".$this->websiteTitle." on Email : ".$this->getMyRecordValue('config', 1, 'owner_email')." Or Mobile : ".$this->getMyRecordValue('config', 1, 'owner_mobile');
				$content = array(
						'name'		=>$name,
						'siteName'	=>$this->websiteTitle,
						'siteLink'	=>$this->websiteLink,
						'logo'		=>DROPTECH_WEBSITE_PATH.'admin/collection/'.$this->getMyRecordValue("config", 1, 'logo'),
						'title'		=>" Welcome into ".$this->websiteTitle.", thank you for part of  ".$this->websiteTitle,
						'subtitle'	=>$subject,
						'themeColor'=>'green',
						'last'		=>array($last),
						'enquiry'	=>$insertArrayShow,
						'paragraph'	=>array('We have created account for '.$this->websiteTitle.'','Your account verification details given'),
						'socialLink'=>$this->socialLink,
						'contactInfo'=>$this->contactInfo,
				);
				$email = new emailDrop();
				$email->emailType('enquiry');
				//$email->debug(true);
				$email->from($this->adminEmail);
				$email->to($to);
				$email->subject($subject);
				$email->content($content);
				$email->send();
				$this->sms_send($mobile, $otpMsg,"");
				$responseArray =  array('status' => 'true','id' => $userID,'otp'=>$otp,'value' =>MSG_USER_101);
					
		}
	}else{
		$info = $this->db->select("select * from  customer where mobile='".$mobile."'");
		$responseArray =  array('status' => 'false','check'=>'otp','id' => $info[0]->id,'value' =>MSG_USER_102);
	}
	$this->displayOutputJson($responseArray);
}
public function test2(){
	header('Access-Control-Allow-Origin: *');
	header("Access-Control-Allow-Credentials: true");
	header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
	header('Access-Control-Max-Age: 1000');
	header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token , Authorization');
	if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
		header('Access-Control-Allow-Headers: token, Content-Type');
		header('Access-Control-Max-Age: 1728000');
		header('Content-Length: 0');
		header('Content-Type: text/plain');
		die();
	}
	
	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json');
	
	$responseArray =  array('status' => 'true','value' =>MSG_USER_108);	
	$this->displayOutputJson($responseArray);
}
public function profileUpdatePhoto(){
	$user_id 			= isset($_REQUEST['user_id'])?$_REQUEST['user_id']:"";
	if($user_id!=""){
		$fileName = $user_id."_profile_photo.jpg";
	}
	$base 			= isset($_REQUEST['image'])?$_REQUEST['image']:"";
	$binary=base64_decode($base);
	header('Content-Type: bitmap; charset=utf-8');
	$file = fopen($fileName, 'wb');
	fwrite($file, $binary);
	fclose($file);
	$path = SITE_URL."plugins/profile_upload/uploades/medium/".$fileName;
	if($db->update("update customer set photo='".$path."' where id=".$user_id)){
		$responseArray =  array('status' => 'true','value' =>'Your photo uploaded successfully');
	}else {
		$responseArray =  array('status' => 'false','value' =>'Something wrong input');
	}
	echo json_encode($responseArray);
}
public function change_password(){
	$id = $this->optionalParametterValidate($_REQUEST['id'], 'id');
	$old_password =$this->optionalParametterValidate($_REQUEST['old_password'], 'old_password');
	$new_password =$this->optionalParametterValidate($_REQUEST['new_password'], 'new_password');
	$originalNewPassword = $new_password;
	$old_password = md5($old_password);
	$new_password = md5($new_password);
	$this->checkIsIdExist('customer', $id);
	if ($this->getMyRecordValue('customer', $id,'password')==$old_password){
		$this->updateTableRecordValue('customer', $id, 'password', $new_password);
		$smsBody = APP_SMS_3." ".$originalNewPassword;
		$this->sms_send($this->getMyRecordValue('customer', $id, 'mobile'), $smsBody,"");
		$responseArray =  array('status' => 'true','value' =>MSG_USER_111);
	}else {
		$responseArray =  array('status' => 'false','value' =>MSG_USER_110);
	}
	$this->displayOutputJson($responseArray);
}
public function forgot_password() {
	//$username =$this->optionalParametterValidate($_REQUEST['mobile'], 'mobile');
	$mobile =$this->optionalParametterValidate($_REQUEST['mobile'], 'mobile');
	//$this->emailValid($email);
	$this->mobileValid($mobile);
	if($this->db->selectCount("select count(*) as count from customer where mobile='".$mobile."'")==0){
		$responseArray =  array('status' => 'false','value' =>MSG_COM_006);
		$this->displayOutputJson($responseArray);
	}else {
		$userInfo = $this->db->select("select * from customer where mobile='".$mobile."'");
		$userInfo= $userInfo[0];
		
		$userId=$userInfo->id;
		$password_new = explode('@',$userInfo->email);
		$password_newEmail = $password_new[0];
		$password_new =  md5($password_newEmail);
		$this->updateTableKeyValue('customer', $userId, 'password', $password_new);
		// SMS send
		$smsBody = APP_SMS_3." ".$password_newEmail;
		$this->sms_send($mobile, $smsBody,"");		
		 $insertArrayShow = array(
				'Name'=>$userInfo->name,
				'Email'=>$userInfo->email,
				'Mobile'=>$userInfo->mobile,
				'Password'=>'<b>'.$password_newEmail.'</b>'
		);
		$to		 		= $userInfo->email;
		$name 			= $userInfo->name;
		$link 			= "<a target='_blank' href='".$this->websiteLink."'>".$this->websiteTitle."</a>";
		$subject 		= "Password reset request created account for ".$this->websiteTitle;
		$last 			= "For more infomation please contact at ".$this->websiteTitle." on Email : ".$this->getMyRecordValue('config', 1, 'owner_email')." Or Mobile : ".$this->getMyRecordValue('config', 1, 'owner_mobile');
		$content = array(
				'name'		=>$name,
				'siteName'	=>$this->websiteTitle,
				'siteLink'	=>$this->websiteLink,
				'logo'		=>$this->websiteLogo,
				'title'		=>$this->websiteTitle." Password reset",
				'subtitle'	=>$subject,
				'themeColor'=>'orange',
				'last'		=>array($last),
				'enquiry'	=>$insertArrayShow,
				'paragraph'	=>array('The password reset request found for '.$this->websiteTitle.'','Your account new password set, Information is given '),
				'socialLink'=>$this->socialLink,
				'contactInfo'=>$this->contactInfo,
		);
		$email = new emailDrop();
		$email->emailType('enquiry');
		//$email->debug(true);
		$email->from($this->adminEmail);
		$email->to($to);
		$email->subject($subject);
		$email->content($content);
		$email->send(); 
		$responseArray =  array('status' => 'true','value' =>MSG_USER_112);
		$this->displayOutputJson($responseArray);
	}
	
}
public function profileImageUpload(){
	$process 		= isset($_REQUEST['process'])?$_REQUEST['process']:"";$process=strtolower($process);
	$user_id 		= isset($_REQUEST['user_id'])?$_REQUEST['user_id']:"";
	$code 			= isset($_REQUEST['code'])?$_REQUEST['code']:"";
	//$this->optionalParametterValidate($user_id, $user_id);
	//$oldCode = $this->getMyRecordValue('customer', $user_id, 'photo');
	$newCode = "";
	if($process =='start'){
		$newCode =$code;		
	}
	if($process =='append'){
		$newCode =$oldCode."".$code;
	}
	if($process =='end'){
		$newCode =$oldCode;
	}	
	if($process =='end' && $newCode!="" ){
		$desiredExt='png';
		$fileName = "plugins/profile_upload/uploades/medium/".rand(333, 999) . time() . ".$desiredExt";
		$fileNameForProcess = "../".$fileName;
		$newCode = str_replace(" ","+", $newCode);
		if(file_put_contents($fileNameForProcess, base64_decode($newCode))){			
			$this->db->update("update customer set photo='".SITE_URL.$fileName."' where id=".$user_id);
			$responseArray =  array('status' => 'true','value'=>MSG_USER_121,'Photo'=>SITE_URL.$fileName);
		}		
	}else {
		$responseArray =  array('status' => 'true','value'=>'code added, please continue');
		$this->db->update("update customer set photo='".$newCode."' where id=".$user_id);
	}
	$this->displayOutputJson($responseArray);
}

// functin for 1menus private project
public function getHotelInfo(){
	$userId =$this->optionalParametterValidate($userId, 'userId');
	$token =$this->optionalParametterValidate($_REQUEST['token'], 'token');
	if($this->db->selectCount("select count(*) as count from users where id=".$userId)==0){
		$responseArray =  array('status' => false,'value' =>'Invalid Details for fetch data for outlets');
	}else{
		
		$info = $this->db->select("select * from users where id=".$userId." and token='".$token."'");
		if(count($info) == 0 ){
			$responseArray =  array('status' => false,'value' =>'Invalid Tocken or UserId');
		}else {
			
			if($info[0]->status == 'NO'){
				$responseArray =  array('status' => false,'value' =>'Outlet account has been disabled');
			}else{
				$responseArray =  array('status' => true,'value' =>$this->getHotelInfoDetails($userId, $token));
			}
		}
	}
	$this->displayOutputJson($responseArray);
}
public function getHotelInfoDetails($userId,$token){
	$responseArray = array();
	$whereClose = " and userId=".$userId." and token='".$token."' and status='YES' ";
	
	// Hotel Main Information
	$hotelInformation = $this->db->select("select * from hotel_information where 1 ".$whereClose);
	$logo = $this->db->getMyRecordValue('users', $userId, 'logo');
	$outletInfo = $this->db->select("select id,mobile,username,logo,city,facebook,instagram,googleMapLink,connectbookerLink,logo,photo,cover_photo from users where 1  and id=".$userId);
	$facility =  $hotelInformation[0]->facilities?$hotelInformation[0]->facilities:'';
	$facilityList = "";
	if($facility!="")
	$facilityList = $this->db->select("select * from hotel_facilities where 1 and id in (".$facility.") ");
	
	$banner =  $this->db->select("select * from hotel_banner where 1 ".$whereClose." order by sq ASC");
	
	$roomTypes = $this->db->select("select * from hotel_room_types where 1 ".$whereClose." order by sq ASC");
	
	$gallery =  $this->db->select("select * from hotel_gallery where 1 ".$whereClose." group by type order by sq ASC");
	$galleryKeys = $this->db->select("select type from hotel_gallery where 1 ".$whereClose." group by type order by sq ASC");
	$galaryAllItems = $this->db->select("select * from hotel_gallery where 1 ".$whereClose." order by sq ASC");
	
	
	$onsiteFeatures = $this->db->select("select * from hotel_onsite_features where 1 ".$whereClose."  order by title ASC");
	$pages = $this->db->select("select * from hotel_pages where 1 ".$whereClose."  order by title ASC");
	
	// setup final data 
	$responseArray['outletInfo']= $this->unwantedInfoRemove($outletInfo);
	$responseArray['hotelInfo']= $this->unwantedInfoRemove($hotelInformation);
	$responseArray['facility']= $this->unwantedInfoRemove($facilityList);
	$responseArray['banner']= $this->unwantedInfoRemove($banner);
	$responseArray['roomTypes']= $this->unwantedInfoRemove($roomTypes);
	$responseArray['gallery']= $this->unwantedInfoRemove($gallery);
	$responseArray['galleryKeys'] = $galleryKeys;
	$responseArray['galleryItems']= $this->unwantedInfoRemove($galaryAllItems);
	$responseArray['onsiteFeatures'] = $this->unwantedInfoRemove($onsiteFeatures);
	$responseArray['pages']= $this->unwantedInfoRemove($pages);
	
	return $responseArray;
}
public function unwantedInfoRemove($array){
	
		for($a=0;$a<count($array);$a++){
			unset($array[$a]->token);
			unset($array[$a]->id);
			unset($array[$a]->userId);
			unset($array[$a]->created_date);
			unset($array[$a]->status);
			unset($array[$a]->sq);
			unset($array[$a]->metadata);
			unset($array[$a]->updated_date);
			
			$photo = $array[$a]->photo?$array[$a]->photo:'';
			if($photo!=""){
				$array[$a]->photo = $this->imgSourceReturn($array[$a]->photo);
			}
			
			$logo = $array[$a]->logo?$array[$a]->logo:'';
			if($photo!=""){
				$array[$a]->logo = $this->imgSourceReturn($array[$a]->logo);
			}
			
			$cover_photo = $array[$a]->cover_photo?$array[$a]->cover_photo:'';
			if($cover_photo!=""){
				$array[$a]->cover_photo = $this->imgSourceReturn($array[$a]->cover_photo);
			}
			
			$amenities = $array[$a]->amenities?$array[$a]->amenities:'';
			if($amenities!=""){
				$amenitiesList = $this->db->select("select title,icon from hotel_amenities where 1 and id in (".$amenities.") ");
				$array[$a]->amenities = $amenitiesList;
			}
		}
	
	return $array;
}
public function products(){
    $info = $this->db->select("SELECT * from products where status='YES' order by sq asc");
    $categoryList = $this->db->select("select category from products group by category");
    $responseArray =  array('status' => 'true','category'=>$categoryList,'value' =>$info);
    $this->displayOutputJson($responseArray);
}
public function takeAwayEmailTrigger($userInfo,$orderInfo){
	$to = $userInfo->email?$userInfo->email:'';
	if($to!=""){
$subject = 'Received a new Take Away from-'.$orderInfo['mobile'];
$activateLink = array('Check Order Details' => SITE_URL.'cms/my_orders');
$content = array(
		'name'		=>$userInfo->name,
		'siteName'	=>$userInfo->title,
		'siteLink'	=>SITE_URL.'menus'.$userInfo->city."/".$userInfo->username,
		'logo'		=>CMS_MEDIA_PATH."".$userInfo->logo,
		'title'		=>'Received a new Take Away from-'.$orderInfo['mobile'],
		'subtitle'	=>'Received a new Take Away from-'.$orderInfo['mobile'],
		'themeColor'=>'green',
		'last'		=>array('Please login to CMS admin panel to check the order details and process the order'),
		'enquiry'	=>array('Name'=>$orderInfo['customer'],'Mobile'=>$orderInfo['mobile'],'Address'=>$orderInfo['address'],'Items(s)'=>$orderInfo['items'],'Total Amount'=>$orderInfo['total']),
		'paragraph'	=>array('Received a new Take Away from-'.$orderInfo['mobile'],'Please login to CMS admin panel to check the order details and process the order'),
		//'socialLink'=>$this->socialLink,
		"link"=>$activateLink, 
		'contactInfo'=>$userInfo->mobile,
);
$email = new emailDrop();
$email->emailType('enquiry');
$email->from('info@1menus.com');
$email->to($to);
$email->subject($subject);
$email->content($content);
$email->send();
	}
}

public function emailDrop(){
	//require_once('service/emailDrop.php');
//$email = new emailDrop();
$to = 'vishwajeet9201@gmail.com';
$subject = 'Testing emailDrop';
$content = array(
		'name'		=>'Vishwajeet Singh',
		'siteName'	=>'1menus',
		'siteLink'	=>'https://1menus.in',
		'logo'		=>'https://droptech.in/admin/collection/1687436985.png',
		'title'		=>'Testing emailDrop',
		'subtitle'	=>'Testing emailDrop',
		'themeColor'=>'green',
		'last'		=>array('This is testing email for emailDrop class'),
		'enquiry'	=>array('Testing emailDrop class','This is testing email for emailDrop class'),
		'paragraph'	=>array('Testing emailDrop class','This is testing email for emailDrop class'),
		'socialLink'=>array('facebook'=>'https://www.facebook.com/droptech.in','instagram'=>'https://www.instagram.com/droptech.in/','twitter'=>'https://twitter.com/droptech_in'),
		'contactInfo'=>array('email'=>'contact@	droptech.in','mobile'=>'+91-9205069205','address'=>'DropTech IT Solutions, 123, ABC Street, City, Country')
);
//$email = new emailDrop();
$this->email->emailType('enquiry');
$this->email->to($to);
$this->email->subject($subject);
$this->email->content($content);
$this->email->from('contact@droptech.in');
$em = $this->email->send($to,$subject,$content);
print_r($em);
	}
	// secttion API endpoint for android app for 1menus private project
	public function staffLogin(){
		$this->getBodyJsonData();
		$outletId = $this->optionalParametterValidate($_REQUEST['outletId'], 'outletId');
		$userType = $this->optionalParametterValidate($_REQUEST['userType'], 'userType');
		$username = $this->optionalParametterValidate($_REQUEST['username'], 'username');
		$password = $this->optionalParametterValidate($_REQUEST['password'], 'password');
		$deviceType = $this->optionalParametterValidate($_REQUEST['deviceType'], 'deviceType');
		$deviceId = $this->optionalParametterValidate($_REQUEST['deviceId'], 'deviceId');
		if($this->db->selectCount("select count(*) as count from staff where userId='".$outletId."' and username='".$username."' and password='".$password."'")==0){
			$responseArray =  array('status' => 'false','value' =>'Invalid username or password');
			$this->displayOutputJson($responseArray);
		}else{
			$staffInfo = $this->db->select("select * from staff where department='".$userType."' and userId='".$outletId."' and username='".$username."' and password='".$password."'");
			if(count($staffInfo)==0){
				$responseArray =  array('status' => 'false','value' =>'Invalid username or password');
				$this->displayOutputJson($responseArray);
			}else{
			$staffInfo = $staffInfo[0];
			
			if($staffInfo->status == 'NO'){
				$responseArray =  array('status' => 'false','value' =>'Your account has been disabled, please contact to outlet admin');
				$this->displayOutputJson($responseArray);
			}else{
				$deviceIdList = array();
				if($deviceId!='' && $deviceType!=''){
					if($this->db->selectCount("select count(*) as count from staff_devices where staffId=".$staffInfo->id." and deviceId='".$deviceId."' and deviceType='".$deviceType."'")==0){
							$insertNewDevice = array(
									'staffId'=>$staffInfo->id,
									'deviceId'=>$deviceId,
									'deviceType'=>$deviceType,
									'last_login'=>date("Y-m-d H:i:s"),
									'created_date'=>date("Y-m-d H:i:s"),
									'updated_date'=>date("Y-m-d H:i:s")
							);
							$insertId = $this->db->insert($insertNewDevice, "staff_devices");
						}else{
							$this->db->update("update staff_devices set last_login='".date("Y-m-d H:i:s")."' where staffId=".$staffInfo->id." and deviceId='".$deviceId."' and deviceType='".$deviceType."'");
						}
						$deviceIdList = $this->db->select("select deviceType,deviceId,last_login from staff_devices where staffId=".$staffInfo->id." order by last_login DESC");
					}
					$responseArray =  array('status' => 'true','value' =>$staffInfo,'devices'=>$deviceIdList);
				}
		
		}
			$this->displayOutputJson($responseArray);
		}
}

/* Application Level API Methods */
public function getDeviceIds(){
	
	$this->getBodyJsonData();
	$staffId = $this->optionalParametterValidate($_REQUEST['staffId'], 'staffId');
	if($this->db->selectCount("select count(*) as count from staff_devices where staffId=".$staffId)==0){
		$responseArray =  array('status' => 'false','value' =>'Invalid Staff Id');
		$this->displayOutputJson($responseArray);
	}else{
		
	$deviceIdList = $this->db->select("select deviceType,deviceId,last_login from staff_devices where staffId=".$staffId." order by last_login DESC");
	$responseArray =  array('status' => 'true','value' =>$deviceIdList);
	$this->displayOutputJson($responseArray);
	}
}
// function for sending push notification to staff devices
public function sendPushNotification($argStaffId="", $argTitle="", $argMessage=""){
	try {
		// Validate file exists
		$pushFile = __DIR__ . '/push-notification/send_push.php';
		if(!file_exists($pushFile)){
			$this->displayOutputJson(array('status' => false, 'value' => 'Push notification file not found at: ' . $pushFile));
			return;
		}
		require_once 'push-notification/send_push.php';
		
		// Check if class exists
		if(!class_exists('PushNotification')){
			$this->displayOutputJson(array('status' => false, 'value' => 'PushNotification class not found'));
			return;
		}
		$push = new PushNotification();
		$this->getBodyJsonData();
		if($argStaffId != ""){
			$_REQUEST['staffId'] = $argStaffId;
			$staffId = $argStaffId;
		}
		$staffId = $this->optionalParametterValidate($_REQUEST['staffId'], 'staffId');
		
		// Validate staff has devices
		$deviceCount = $this->db->selectCount("select count(*) as count from staff_devices where staffId=".$staffId);
		if($deviceCount == 0){
			$this->displayOutputJson(array('status' => false, 'value' => 'No device found for this staff'));
			return;
		}
		
		if($argTitle != ""){
			$_REQUEST['title'] = $argTitle;
			$title = $argTitle;
		}
		if($argMessage != ""){
			$_REQUEST['message'] = $argMessage;
			$message = $argMessage;
		}
		$deviceIdList = $this->db->select("select deviceType,deviceId from staff_devices where staffId=".$staffId." order by last_login DESC");
		$title = $this->optionalParametterValidate($_REQUEST['title'], 'title');
		$message = $this->optionalParametterValidate($_REQUEST['message'], 'message');
		
		// Validate inputs
		if(empty($title) || empty($message)){
			$this->displayOutputJson(array('status' => false, 'value' => 'Title and message are required'));
			return;
		}
		
		$responseArraySet = array();
		$successCount = 0;
		$failureCount = 0;
		
		// Send notification to each device
		for($d=0; $d<count($deviceIdList); $d++){
			try {
				$deviceToken = $deviceIdList[$d]->deviceId;
				
				if(empty($deviceToken)){
					$failureCount++;
					array_push($responseArraySet, array('deviceId' => 'unknown', 'status' => 'failed', 'error' => 'Empty device token'));
					continue;
				}
				
				$notification = $push->sendFcm($deviceToken, $title, $message);
				
				// Check if notification was sent successfully
				if(is_array($notification) && isset($notification[0])){
					if($notification[0] >= 200 && $notification[0] < 300){
						$successCount++;
						array_push($responseArraySet, array('deviceId' => $deviceToken, 'status' => 'sent', 'httpCode' => $notification[0]));
					} else {
						$failureCount++;
						array_push($responseArraySet, array('deviceId' => $deviceToken, 'status' => 'failed', 'httpCode' => $notification[0], 'error' => isset($notification[1]) ? $notification[1] : 'Unknown error'));
					}
				} else {
					$failureCount++;
					array_push($responseArraySet, array('deviceId' => $deviceToken, 'status' => 'failed', 'error' => 'Invalid response from sendFcm'));
				}
			} catch (Exception $e) {
				$failureCount++;
				array_push($responseArraySet, array('deviceId' => isset($deviceToken) ? $deviceToken : 'unknown', 'status' => 'failed', 'error' => $e->getMessage()));
			}
		}
		$rteunArrayObject =array(
			'status' => true,
			'value' => 'Notifications processed',
			'sent' => $successCount,
			'failed' => $failureCount,
			'totalDevices' => count($deviceIdList),
			'response' => $responseArraySet
		);
		if($argStaffId != "" && $argTitle != "" && $argMessage != ""){
			return $rteunArrayObject;
		}else{
			return $this->displayOutputJson($rteunArrayObject);
		}
		
	} catch (Exception $e) {
		$this->displayOutputJson(array(
			'status' => false,
			'value' => 'Error sending notifications',
			'error' => $e->getMessage(),
			'file' => $e->getFile(),
			'line' => $e->getLine()
		));
	}
}

// function for get a list of staff
public function getStaffList(){
	$outletId = isset($_REQUEST['outletId'])?$_REQUEST['outletId']:"";
	if($this->db->selectCount("select count(*) as count from staff where userId=".$outletId)==0){
		$responseArray =  array('status' => 'false','value' =>'No staff found for this outlet');
		$this->displayOutputJson($responseArray);
	}else{
		$staffList = $this->db->select("select * from staff where userId=".$outletId." order by id DESC");
		$responseArray =  array('status' => 'true','value' =>$staffList);
		$this->displayOutputJson($responseArray);
	}
}
public function getAndValidateHeaderTokenForJWT(){
	// Fetch all headers into an associative array
$headers = getallheaders(); 
// Target key (Note: header names are case-insensitive, but arrays are case-sensitive)
$token = isset($headers['Token']) ? $headers['Token'] : null;
$id = isset($headers['Id']) ? $headers['Id'] : null;

if($token == null || $id == null){
	$responseArray =  array('status' => 'false', 'value' => "Token and Id headers are required");
	$this->displayOutputJson($responseArray);
}else{
	$p = $this->APIIsValidToken($token, $id);	
}

}

// function for log API access for JWT token based authentication for 1menus private project
public function APIAccessLogs($responseArray){
	$headers = getallheaders();
		$json = file_get_contents('php://input'); 
	$requestBody = json_decode($json, true);
	$requestMethod = $_SERVER['REQUEST_METHOD'];
// Target key (Note: header names are case-insensitive, but arrays are case-sensitive)
$token = isset($headers['Token']) ? $headers['Token'] : null;
$id = isset($headers['Id']) ? $headers['Id'] : null;
$title = $this->db->getMyRecordValue('jwt_tokens_user', $id, 'title');
$date = date("Y-m-d");
$request = $json;
$response = json_encode($responseArray);
	$existingTokenCount = $this->db->selectCount("select count(*) as count from jwt_tokens_log where date = '".$date."' and jwt_tokens_user=".$id." and jwt_token='".$token."' and method='".$requestMethod."' and request='".$request."'");
	if($existingTokenCount !=0){
		// update date_time and count+1
		$this->db->update("update jwt_tokens_log set date_time='".date("Y-m-d H:i:s")."', count=count+1 where date = '".$date."' and jwt_tokens_user=".$id." and jwt_token='".$token."' and method='".$requestMethod."' and request='".$request."'");
	}else{
	$insertArray = array(
		'title' => $title,
		'jwt_tokens_user' => $id,
		'jwt_token' => $token,
		'date'=> $date,
		'IP' => $_SERVER['REMOTE_ADDR'],
		'date_time' => date("Y-m-d H:i:s"),
		'method' => $requestMethod,
		'request' => $request,
		'response' => $response
	);
	$this->db->insert($insertArray, 'jwt_tokens_log');
	}
}

// function for get master data for 1menus private project
public function masterData(){
	$responseArray =  array('status' => 'true', 'value' => 'Master data retrieved successfully', 'staffTypes' =>$this->staffTypes,'departments'=>$this->departments,'statusList'=>$this->statusList);
	$this->displayOutputJson($responseArray);
}

// function for validate outlet for 1menus private project
public function validateOutlet($outletId){
	$responseArray = array();
	$info = $this->db->select("select * from users where id=".$outletId);
	if(count($info)==0){
		$responseArray =  array('status' => false,'value' =>'Invalid Outlet Id');
	}else{
		$info = $info[0];
	$id 		= $info->id?$info->id:$outletId;
	$mobile 	= $info->mobile?$info->mobile:"";
	$username 	= $info->username?$info->username:"";
	$token 	= $info->token?$info->token:"";
	if($this->db->selectCount("select count(*) as count from users where id=".$id." and mobile='".$mobile."' and username='".$username."' and token='".$token."'" )==0){
		$responseArray =  array('status' => 'false','value' =>'Invalid Details for fetch data for outlets');
	}else{
		$info = $this->db->select("select * from users where id=".$id." and mobile='".$mobile."' and username='".$username."' and token='".$token."'");
		if($info[0]->status == 'NO'){
			$responseArray =  array('status' => 'false','value' =>'Outlet account has been disabled');
		}else{
			$info[0]->password = "***";
			$responseArray =  array('status' => 'true','value' =>$info[0]);
		}
	}
	}
	return $responseArray;
}

// function for get room service my services list for outlet
public function getOutletServices(){
	$outletId = isset($_REQUEST['outletId'])?$_REQUEST['outletId']:"";
	$outletValidatedResponse = $this->validateOutlet($outletId);
	$value = $outletValidatedResponse['value'];
	$status = $outletValidatedResponse['status'];
	// check $status is string or boolean and convert to boolean
	if(is_string($status)) {
	$status = ($status === 'true') ? true : false;
	}
	$responseArray = array();
		if($status){
			$room_service_my_service = $this->db->select("select * from room_service_my_service where userId=".$outletId." and status='YES' order by sq ASC");
			$responseArray = array(
				'status' => $status,
				'value' => 'result found',
				'count' => is_array($room_service_my_service) ? count($room_service_my_service) : 0,
				'services' => is_array($room_service_my_service) ? $room_service_my_service : array()
			);
		}else{
			$responseArray =  array('status' => $status,'value' =>$value);
		}
		$this->displayOutputJson($responseArray);
	}
	// function for get room service categories for outlet
	public function getOutletCategories(){
		$outletId = isset($_REQUEST['outletId'])?$_REQUEST['outletId']:"";
	$outletValidatedResponse = $this->validateOutlet($outletId);
	$value = $outletValidatedResponse['value'];
	$status = $outletValidatedResponse['status'];
	// check $status is string or boolean and convert to boolean
	if(is_string($status)) {
	$status = ($status === 'true') ? true : false;
	}
	$responseArray = array();
		if($status){
			$serviceCategory = $this->db->select("select id,title,subTitle from room_service_my_category where userId=".$outletId." and status='YES' order by sq ASC");	
			$responseArray = array(
				'status' => $status,
				'value' => 'result found',
				'count' => is_array($serviceCategory) ? count($serviceCategory) : 0,
				'services' => is_array($serviceCategory) ? $serviceCategory : array()
			);
			
		}else{
			$responseArray =  array('status' => $status,'value' =>$value);
		}
	
	$this->displayOutputJson($responseArray);
	}

	// function for get room service request list for outlet
	public function getRoomRequest(){
	$responseArray = array();
	
	$outletId = isset($_REQUEST['outletId'])?$_REQUEST['outletId']:"";
	$filterBy = isset($_REQUEST['filterBy']) ? $_REQUEST['filterBy'] : array();
	if (!is_array($filterBy)) {
		$filterBy = array();
	}
	$filterBySqlString = "";
	if (count($filterBy) > 0) {
		for ($f = 0; $f < count($filterBy); $f++) {
			if (isset($filterBy[$f]['key']) && isset($filterBy[$f]['value'])) {
				$filterBySqlString .= " and " . $filterBy[$f]['key'] . "='" . $filterBy[$f]['value'] . "'";
			}
		}
	}
	$outletValidatedResponse = $this->validateOutlet($outletId);
	$value = $outletValidatedResponse['value'];
	$status = $outletValidatedResponse['status'];
	// check $status is string or boolean and convert to boolean
	if(is_string($status)) {
	$status = ($status === 'true') ? true : false;
	}
	$responseArray = array();
		if($status){
			$roomRequest = $this->db->select("select * from room_service_request where userId=".$outletId." ".$filterBySqlString." order by id DESC");	
			
			// Add time tracking metrics to each request
			if (is_array($roomRequest) && count($roomRequest) > 0) {
				foreach ($roomRequest as &$request) {
					$requestId = null;
					if (is_array($request) && isset($request['id'])) {
						$requestId = $request['id'];
					} elseif (is_object($request) && isset($request->id)) {
						$requestId = $request->id;
					}
					if ($requestId !== null) {
						$timeMetrics = $this->getRequestTimeMetrics($requestId, $outletId);
						if (is_array($request)) {
							$request['timeMetrics'] = $timeMetrics;
						} elseif (is_object($request)) {
							$request->timeMetrics = $timeMetrics;
						}
					}
				}
				unset($request);
			}
			
			$responseArray = array(
				'status' => $status,
				'value' => 'result found',
				'count' => is_array($roomRequest) ? count($roomRequest) : 0,
				'roomRequest' => is_array($roomRequest) ? $roomRequest : array()
			);
			
		}else{
			$responseArray =  array('status' => $status,'value' =>$value);
		}
	$this->displayOutputJson($responseArray);
}

// function for get outlet request overview counts
public function serviceStatusCount(){
	$myRoomServiceRequestCountByStatus = array();
	$outletId = isset($_REQUEST['outletId'])?$_REQUEST['outletId']:"";
	$sql = "select status, count(*) as count from room_service_request where userId=".$outletId." group by status";
	$myRoomServiceRequestCountByStatus = $this->db->select($sql);
	$responseArray = array(
		'status' => true,
		'value' => 'result found',
		'countByStatus' => is_array($myRoomServiceRequestCountByStatus) ? $myRoomServiceRequestCountByStatus : array()
	);
	$this->displayOutputJson($responseArray);	
}

public function getRequestDetails(){
	$outletId = isset($_REQUEST['outletId'])?$_REQUEST['outletId']:"";
	$requestId = isset($_REQUEST['requestId'])?$_REQUEST['requestId']:"";
	$responseArray = $this->getRequestDetailsById($outletId, $requestId);
	$this->displayOutputJson($responseArray);
}
public function getRequestDetailsById($outletId, $requestId){
	$requestDetails = $this->db->select("select * from room_service_request where userId=".$outletId." and id=".$requestId);
	if(count($requestDetails)==0){
		return array('status' => 'false','value' =>'No request found for this request id');
	}else{
		$activity = $this->db->select("select rsra.dateTime,rsra.status,rsra.comment,rsra.created_date,s.name as assignedTo,s.mobile as assignedMobile from room_service_request_activity rsra , staff s where rsra.assigned=s.id and rsra.reqId=".$requestId." and rsra.userId=".$outletId." order by rsra.id ASC");
		// Get time tracking details for this request
		$timeTrackingData = $this->db->select("select * from room_service_request_activity where reqId=".$requestId." and userId=".$outletId." order by id ASC");
		
		// Add time metrics to request details
		$timeMetrics = array();
		//$timeMetrics = $this->getRequestTimeMetrics($requestId, $outletId);
		
		// Enrich activity with time tracking information
		$activityTracker = array();
		$lastEndTime = 0;
		$totalTimeMinutes = 0;
		for($a=0; $a<count($activity); $a++){
			$start_time = isset($timeTrackingData[$a]->created_date) ? $timeTrackingData[$a]->created_date : null;
			if($lastEndTime > 0 && $start_time !== null){
				$activity[$a]->timeSpent = strtotime($start_time) - $lastEndTime;
			} else {
				$activity[$a]->timeSpent = 0;
			}
			$activity[$a]->startTime = $start_time;
			$activity[$a]->endTime = $lastEndTime > 0 ? date("Y-m-d H:i:s", $lastEndTime) : null;
			// timeSpent in minites
			$activity[$a]->timeSpentMinutes = round($activity[$a]->timeSpent / 60, 2);
			// timeSpent in hours and minutes
			$hours = floor($activity[$a]->timeSpent / 3600);
			$minutes = floor(($activity[$a]->timeSpent % 3600) / 60);
			$totalTimeMinutes += $activity[$a]->timeSpentMinutes;
			$activity[$a]->timeSpentFormatted = $hours . "h ". $minutes . "m";
			$lastEndTime = isset($timeTrackingData[$a]->created_date) ? strtotime($timeTrackingData[$a]->created_date) : $lastEndTime;
	
			$activityTracker[] = $activity[$a];
		}
		$enrichedActivity =  $activityTracker;// $this->enrichActivityWithTimeTracking($activity, $timeTrackingData);
		$timeMetrics = array(
			'totalTimeMinutes' => $totalTimeMinutes,
			'totalTimeFormatted' => $hours . "h ". $minutes . "m",
			'activitiesCount' => count($enrichedActivity),
			'averageTimePerActivityMinutes' => 0
		);
		return array(
			'status' => 'true',
			'value' => $requestDetails[0],
			'activity' => is_array($enrichedActivity) ? $enrichedActivity : array(),
			'timeTracking' => is_array($timeTrackingData) ? $timeTrackingData : array(),
			'timeMetrics' => $timeMetrics
		);
	}
}	
// function for update service details for outlets
public function updateRequest(){
	$outletId = isset($_REQUEST['outletId'])?$_REQUEST['outletId']:"";
	$requestId = isset($_REQUEST['requestId'])?$_REQUEST['requestId']:"";
	$status = isset($_REQUEST['status'])?$_REQUEST['status']:"";
	$comment = isset($_REQUEST['comment'])?$_REQUEST['comment']:"";
	$assignedTo = isset($_REQUEST['assignedTo'])?$_REQUEST['assignedTo']:"";
	
	
	if($this->db->selectCount("select count(*) as count from room_service_request where userId=".$outletId." and id=".$requestId)==0){
		$responseArray =  array('status' => 'false','value' =>'No request found for this request id');
		$this->displayOutputJson($responseArray);
	}else{
		$reqInfo = $this->db->select("select * from room_service_request where userId=".$outletId." and id=".$requestId);
		$roomId = $reqInfo[0]->roomId?$reqInfo[0]->roomId:"";
		$reqCode = $reqInfo[0]->reqCode?$reqInfo[0]->reqCode:"";
		$currentStatus = $reqInfo[0]->status?$reqInfo[0]->status:"";
		$updateArray = array(
			'status' => $status,
			'comment' => $comment,
			'assignedTo' => $assignedTo,
			'updated_date' => date("Y-m-d H:i:s")
		);
		$this->db->update("update room_service_request set status='".$status."',  assigned='".$assignedTo."', updated_date='".date("Y-m-d H:i:s")."' where userId=".$outletId." and id=".$requestId);

		
		// Insert into activity log
		$activityArray = array(
			'reqId' => $requestId,
			'roomId' => $roomId,
			'userId' => $outletId,
			'status' => $status,
			'comment' => $comment,
			'assigned' => $assignedTo,
			'dateTime' => date("Y-m-d H:i:s"),
			'ip' => $_SERVER['REMOTE_ADDR'],
			'created_date' => date("Y-m-d H:i:s")
		);
		$this->db->insert($activityArray, "room_service_request_activity");
		$notificationDetails = $this->sendPushNotification($assignedTo, "Room Service Status: " . $status, "You have been assigned to a new room service request (Code: $reqCode) for Room ID: $roomId. Please check the app for details.");
			$_REQUEST['outletId'] = $outletId;
			$_REQUEST['requestId'] = $requestId;
		$updatedDetails = $this->getRequestDetailsById($outletId, $requestId);
		$responseArray =  array('status' => 'true','value' =>'Request updated successfully', 'requestDetails' => $updatedDetails, 'notificationDetails' => $notificationDetails);
		$this->displayOutputJson($responseArray);
	}
	
}


/**
 * Calculate time metrics for a request
 * Returns total time spent, average time per activity, and timeline
 */
public function getRequestTimeMetrics($requestId, $outletId) {
	$timeTrackingData = $this->db->select("select * from room_service_request_staff_time_track where reqId=".$requestId." and userId=".$outletId." order by id ASC");
	
	$metrics = array(
		'totalTimeMinutes' => 0,
		'totalTimeFormatted' => '0h 0m',
		'activitiesCount' => 0,
		'staffInvolved' => array(),
		'timeline' => array(),
		'averageTimePerActivityMinutes' => 0
	);
	
	if(!is_array($timeTrackingData) || count($timeTrackingData) == 0) {
		return $metrics;
	}
	
	$totalSeconds = 0;
	$staffList = array();
	
	foreach($timeTrackingData as $trackItem) {
		$track = is_object($trackItem) ? json_decode(json_encode($trackItem), true) : $trackItem;
		if(!empty($track['start_time']) && !empty($track['end_time'])) {
			$startTime = strtotime($track['start_time']);
			$endTime = strtotime($track['end_time']);
			$diffSeconds = $endTime - $startTime;
			
			if($diffSeconds > 0) {
				$totalSeconds += $diffSeconds;
				
				// Add to timeline
				$metrics['timeline'][] = array(
					'userId' => isset($track['userId']) ? $track['userId'] : null,
					'assignedTo' => isset($track['assigned']) ? $track['assigned'] : null,
					'startTime' => $track['start_time'],
					'endTime' => $track['end_time'],
					'durationMinutes' => round($diffSeconds / 60, 2),
					'durationFormatted' => $this->secondsToTimeFormat($diffSeconds)
				);
				
				// Track staff involved
				if(!in_array($track['assigned'], $staffList)) {
					$staffList[] = $track['assigned'];
				}
			}
		}
	}
	
	$metrics['totalTimeMinutes'] = round($totalSeconds / 60, 2);
	$metrics['totalTimeFormatted'] = $this->secondsToTimeFormat($totalSeconds);
	$metrics['activitiesCount'] = count($timeTrackingData);
	$metrics['staffInvolved'] = $staffList;
	
	if(count($timeTrackingData) > 0) {
		$metrics['averageTimePerActivityMinutes'] = round($metrics['totalTimeMinutes'] / count($timeTrackingData), 2);
	}
	
	return $metrics;
}

/**
 * Enrich activity records with time tracking information
 */
public function enrichActivityWithTimeTracking($activity, $timeTrackingData) {
	if(!is_array($activity) || !is_array($timeTrackingData)) {
		return $activity;
	}
	// Create index of time tracking data by assigned staff
	$timeIndex = array();
	foreach ($timeTrackingData as $trackItem) {
		$track = is_object($trackItem) ? json_decode(json_encode($trackItem), true) : $trackItem;
		$assigned = isset($track['assigned']) ? $track['assigned'] : '';
		if (!isset($timeIndex[$assigned])) {
			$timeIndex[$assigned] = array();
		}
		$timeIndex[$assigned][] = $track;
	}
	// Sort each staff's time records by created_date and compute timeData entries
	foreach ($timeIndex as $assigned => &$records) {
		usort($records, function($a, $b) {
			echo "<br>".$createdA = isset($a['created_date']) ? strtotime($a['created_date']) : 0;
			echo "<br>".$createdB = isset($b['created_date']) ? strtotime($b['created_date']) : 0;
			if ($createdA === $createdB) {
				$startA = isset($a['start_time']) ? strtotime($a['start_time']) : 0;
				$startB = isset($b['start_time']) ? strtotime($b['start_time']) : 0;
				if ($startA === $startB) {
					return isset($a['id']) && isset($b['id']) ? $a['id'] - $b['id'] : 0;
				}
				return $startA < $startB ? -1 : 1;
			}
			return $createdA < $createdB ? -1 : 1;
		});

		$totalSeconds = 0;
		$entries = array();
		$overallStart = null;
		$overallEnd = null;

		for ($index = 0; $index < count($records); $index++) {
			$record = $records[$index];
			$start = !empty($record['start_time']) ? strtotime($record['start_time']) : (!empty($record['created_date']) ? strtotime($record['created_date']) : null);
			$end = !empty($record['end_time']) ? strtotime($record['end_time']) : null;

			// If end_time is missing, infer it from the next record's created_date
			if ($start !== null && $end === null && isset($records[$index + 1])) {
				$nextCreated = !empty($records[$index + 1]['created_date']) ? strtotime($records[$index + 1]['created_date']) : null;
				if ($nextCreated !== null && $nextCreated > $start) {
					$end = $nextCreated;
				}
			}

			$durationSeconds = 0;
			if ($start !== null && $end !== null) {
				$durationSeconds = max(0, $end - $start);
				$totalSeconds += $durationSeconds;
			}

			if ($start !== null) {
				$overallStart = $overallStart === null || $start < $overallStart ? $start : $overallStart;
			}
			if ($end !== null) {
				$overallEnd = $overallEnd === null || $end > $overallEnd ? $end : $overallEnd;
			}

			$entries[] = array(
				'id' => isset($record['id']) ? $record['id'] : null,
				'reqId' => isset($record['reqId']) ? $record['reqId'] : null,
				'assigned' => $assigned,
				'date' => isset($record['date']) ? $record['date'] : null,
				'created_date' => isset($record['created_date']) ? $record['created_date'] : null,
				'start_time' => $start !== null ? date('Y-m-d H:i:s', $start) : null,
				'end_time' => $end !== null ? date('Y-m-d H:i:s', $end) : null,
				'durationSeconds' => $durationSeconds,
				'durationMinutes' => round($durationSeconds / 60, 2),
				'durationHours' => round($durationSeconds / 3600, 2),
				'durationFormatted' => $this->secondsToTimeFormat($durationSeconds)
			);
		}
		$timeIndex[$assigned] = array(
			'entries' => $entries,
			'totalSeconds' => $totalSeconds,
			'totalMinutes' => round($totalSeconds / 60, 2),
			'totalHours' => round($totalSeconds / 3600, 2),
			'totalFormatted' => $this->secondsToTimeFormat($totalSeconds),
			'overallStartTime' => $overallStart !== null ? date('Y-m-d H:i:s', $overallStart) : null,
			'overallEndTime' => $overallEnd !== null ? date('Y-m-d H:i:s', $overallEnd) : null,
			'entryCount' => count($entries)
		);
	}
	unset($records);

	// Enrich activity with time data
	foreach ($activity as &$actItem) {
		$actArray = is_object($actItem) ? json_decode(json_encode($actItem), true) : $actItem;
		$assigned = isset($actArray['assignedTo']) ? $actArray['assignedTo'] : '';
		$timeData = isset($timeIndex[$assigned]) ? $timeIndex[$assigned] : array(
			'entries' => array(),
			'totalSeconds' => 0,
			'totalMinutes' => 0,
			'totalFormatted' => '0m',
			'overallStartTime' => null,
			'overallEndTime' => null,
			'entryCount' => 0
		);

		if (is_object($actItem)) {
			$actItem->timeData = $timeData;
		} else {
			$actItem['timeData'] = $timeData;
		}
	}
	unset($actItem);

	return $activity;
}

/**
 * Convert seconds to human-readable format (e.g., "2h 30m")
 */
public function secondsToTimeFormat($seconds) {
	$seconds = (int)$seconds;
	$hours = floor($seconds / 3600);
	$minutes = floor(($seconds % 3600) / 60);
	$secs = $seconds % 60;
	
	$formatted = '';
	if($hours > 0) {
		$formatted .= $hours . 'h ';
	}
	if($minutes > 0 || $hours > 0) {
		$formatted .= $minutes . 'm';
	}
	if($secs > 0 && $hours == 0 && $minutes == 0) {
		$formatted .= $secs . 's';
	}
	
	return trim($formatted) ?: '0m';
}

/**
 * Track request activity start time
 */
public function trackRequestTimeStart() {
	$outletId = isset($_REQUEST['outletId'])?$_REQUEST['outletId']:"";
	$requestId = isset($_REQUEST['requestId'])?$_REQUEST['requestId']:"";
	$requestCode = isset($_REQUEST['requestCode'])?$_REQUEST['requestCode']:"";
	$staffId = isset($_REQUEST['staffId'])?$_REQUEST['staffId']:"";
	$date = isset($_REQUEST['date'])?$_REQUEST['date']:date('Y-m-d');
	
	if(!$requestId || !$staffId || !$outletId) {
		$responseArray = array('status' => 'false', 'value' => 'Missing required parameters');
		$this->displayOutputJson($responseArray);
		return;
	}
	
	// Insert start time record
	$insertData = array(
		'reqId' => $requestId,
		'reqCode' => $requestCode,
		'userId' => $outletId,
		'assigned' => $staffId,
		'date' => $date,
		'start_time' => date('Y-m-d H:i:s'),
		'created_date' => date('Y-m-d H:i:s')
	);
	
	$insertResult = $this->db->insert('room_service_request_staff_time_track', $insertData);
	
	if($insertResult) {
		$responseArray = array(
			'status' => 'true',
			'value' => 'Time tracking started',
			'trackId' => $this->db->lastId(),
			'startTime' => $insertData['start_time']
		);
	} else {
		$responseArray = array('status' => 'false', 'value' => 'Failed to start time tracking');
	}
	
	$this->displayOutputJson($responseArray);
}

/**
 * Track request activity end time
 */
public function trackRequestTimeEnd() {
	$outletId = isset($_REQUEST['outletId'])?$_REQUEST['outletId']:"";
	$requestId = isset($_REQUEST['requestId'])?$_REQUEST['requestId']:"";
	$staffId = isset($_REQUEST['staffId'])?$_REQUEST['staffId']:"";
	$trackId = isset($_REQUEST['trackId'])?$_REQUEST['trackId']:"";
	
	if(!$trackId || !$requestId || !$staffId) {
		$responseArray = array('status' => 'false', 'value' => 'Missing required parameters');
		$this->displayOutputJson($responseArray);
		return;
	}
	
	// Update end time record
	$updateData = array(
		'end_time' => date('Y-m-d H:i:s')
	);
	
	$updateResult = $this->db->update('room_service_request_staff_time_track', $updateData, "id=".$trackId);
	
	if($updateResult) {
		// Fetch the updated record with time difference
		$trackRecord = $this->db->select("select * from room_service_request_staff_time_track where id=".$trackId);
		
		if(is_array($trackRecord) && count($trackRecord) > 0) {
			$track = $trackRecord[0];
			if(is_object($track)) {
				$track = json_decode(json_encode($track), true);
			}
			$startTime = strtotime($track['start_time']);
			$endTime = strtotime($track['end_time']);
			$diffSeconds = $endTime - $startTime;
			$diffMinutes = round($diffSeconds / 60, 2);
			
			$responseArray = array(
				'status' => 'true',
				'value' => 'Time tracking ended',
				'trackId' => $trackId,
				'startTime' => $track['start_time'],
				'endTime' => $track['end_time'],
				'durationSeconds' => $diffSeconds,
				'durationMinutes' => $diffMinutes,
				'durationFormatted' => $this->secondsToTimeFormat($diffSeconds)
			);
		} else {
			$responseArray = array('status' => 'false', 'value' => 'Track record not found');
		}
	} else {
		$responseArray = array('status' => 'false', 'value' => 'Failed to end time tracking');
	}
	
	$this->displayOutputJson($responseArray);
}

/**
 * Get activity time report by staff
 */
public function getStaffTimeReport() {
	$outletId = isset($_REQUEST['outletId'])?$_REQUEST['outletId']:"";
	$staffId = isset($_REQUEST['staffId'])?$_REQUEST['staffId']:"";
	$startDate = isset($_REQUEST['startDate'])?$_REQUEST['startDate']:date('Y-m-d', strtotime('-7 days'));
	$endDate = isset($_REQUEST['endDate'])?$_REQUEST['endDate']:date('Y-m-d');
	
	if(!$outletId) {
		$responseArray = array('status' => 'false', 'value' => 'Missing outlet ID');
		$this->displayOutputJson($responseArray);
		return;
	}
	
	$whereClause = "userId=".$outletId." and date between '".$startDate."' and '".$endDate."'";
	if($staffId) {
		$whereClause .= " and assigned=".$staffId;
	}
	
	$timeRecords = $this->db->select("select * from room_service_request_staff_time_track where ".$whereClause." order by date DESC, start_time DESC");
	
	// Calculate summary metrics
	$totalSeconds = 0;
	$requestCount = 0;
	$staffMetrics = array();
	
	if(is_array($timeRecords)) {
		foreach($timeRecords as $recordItem) {
			$record = is_object($recordItem) ? json_decode(json_encode($recordItem), true) : $recordItem;
			if(!empty($record['start_time']) && !empty($record['end_time'])) {
				$startTime = strtotime($record['start_time']);
				$endTime = strtotime($record['end_time']);
				$diffSeconds = $endTime - $startTime;
				
				if($diffSeconds > 0) {
					$totalSeconds += $diffSeconds;
					$assigned = $record['assigned'];
					
					if(!isset($staffMetrics[$assigned])) {
						$staffMetrics[$assigned] = array(
							'totalSeconds' => 0,
							'taskCount' => 0,
							'avgTimePerTask' => 0
						);
					}
					
					$staffMetrics[$assigned]['totalSeconds'] += $diffSeconds;
					$staffMetrics[$assigned]['taskCount']++;
				}
			}
		}
		
		// Calculate averages
		foreach($staffMetrics as &$metric) {
			$metric['totalTimeFormatted'] = $this->secondsToTimeFormat($metric['totalSeconds']);
			$metric['totalTimeMinutes'] = round($metric['totalSeconds'] / 60, 2);
			if($metric['taskCount'] > 0) {
				$metric['avgTimePerTask'] = round($metric['totalSeconds'] / $metric['taskCount'], 2);
				$metric['avgTimePerTaskFormatted'] = $this->secondsToTimeFormat($metric['avgTimePerTask']);
			}
		}
		unset($metric);
	}
	
	$responseArray = array(
		'status' => 'true',
		'value' => 'Staff time report',
		'period' => array('startDate' => $startDate, 'endDate' => $endDate),
		'totalTimeMinutes' => round($totalSeconds / 60, 2),
		'totalTimeFormatted' => $this->secondsToTimeFormat($totalSeconds),
		'recordCount' => is_array($timeRecords) ? count($timeRecords) : 0,
		'staffMetrics' => $staffMetrics,
		'records' => is_array($timeRecords) ? $timeRecords : array()
	);
	
	$this->displayOutputJson($responseArray);
}
}

?>