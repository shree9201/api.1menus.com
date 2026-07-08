<?php
// Set CORS headers first, before any includes or output
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization');
header('Content-Type: application/json; charset=utf-8');

///////////////////////////////////////////
// File Name        : api.php
// Craeted By       : vishu
// Created Date     : 26-May-2026
// File Modified By : vishu
// Modify  Date     : 26-May-2026
// Description      : This is file file api.
///////////////////////////////////////////

// object create
require_once 'api_class.php';

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

$action = isset($_REQUEST['action'])?$_REQUEST['action']:"";
$responseArray = array();
$item = new api_class();
$allowedAction = false;
if ($action !== '' && preg_match('/^[a-zA-Z0-9_]+$/', $action) && method_exists($item, $action) && is_callable([$item, $action])) {
	$internalMethods = array(
		'__construct', 'getBodyJsonData', 'emailValid', 'mobileValid', 'optionalParametterValidate',
		'checkIsNull', 'getTranslatedMessage', 'numberValid', 'urlValid', 'usernameValid',
		'passwordConfirmValid', 'jsonDisplay', 'displayOutputJson', 'getCurldata', 'applicationAPI',
		'checkIsIdExist', 'updateTableRecordValue', 'apiDefault', 'isImageAvaibaleOrNot',
		'generateRandomString', 'generateRandomToken', 'jsonReturnToDisplay', 'get_time_passed',
		'imgSourceReturn', 'APIIsValidToken', 'APIIsItemPresents', 'APIReturnListData',
		'getImagesArray', 'takeAwayEmailTrigger', 'unwantedInfoRemove'
	);
	if (!in_array($action, $internalMethods, true)) {
		$allowedAction = true;
	}
}

if ($allowedAction) {
	$item->$action();
	exit;
} else {
	echo json_encode(array('status' => false, 'value' => 'your Param are not valid to perform actions', 'action' => $action));
	exit;
}
