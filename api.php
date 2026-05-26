<?php
///////////////////////////////////////////
// File Name        : api.php
// Craeted By       : Vishwajeet Mahadik
// Created Date     : 26-May-2026
// File Modified By : Vishwajeet Mahadik
// Modify  Date     : 26-May-2026
// Description      : This is file file api.
///////////////////////////////////////////
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');
$defaultContentType = 'text/html; charset=utf-8';
// If a clean API action path is used (e.g. /app/API/outletInfo) detect it and treat as API call
$detectedAction = "";
if (empty($_REQUEST['action']) && !empty($_SERVER['REQUEST_URI'])) {
	$path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
	$parts = explode('/', $path);
	// try to find 'API' segment and take the next segment as action
	$apiIndex = array_search('API', $parts);
	if ($apiIndex !== false && isset($parts[$apiIndex + 1]) && $parts[$apiIndex + 1] !== '') {
		$detectedAction = $parts[$apiIndex + 1];
		$_REQUEST['action'] = $detectedAction;
	}
}
// Set JSON content type when an API action is requested
if (!empty($_REQUEST['action'])) {
	header('Content-Type: application/json; charset=utf-8');
} else {
	header('Content-Type: ' . $defaultContentType);
}
$action 	= isset($_REQUEST['action'])?$_REQUEST['action']:"";
	$responseArray= array();
	// swich case for action 
	$action 	= isset($_REQUEST ['action'])?$_REQUEST ['action']:"";	
	// object create	
	require_once 'api_class.php';
	$item = new api_class();		
	//echo $action;				// for config validate fields
switch ($action)
{
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// ANDRIOD AND IOS SECTION API
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	case 'staffLogin':						$item->staffLogin();					break;
	case 'getDeviceIds':					$item->getDeviceIds();					break;
	case 'sendPushNotification':			$item->sendPushNotification();			break;
	case 'getStaffList':					$item->getStaffList();					break;
	default: 								require_once 'info/index.php';			break;
	
	
	
	break;
		
}
