<?php
///////////////////////////////////////////
// File Name        : emailDrop.php
// Craeted By       : vishu
// Created Date     : 20-11-2015
// File Modified By : vishu
// Modify  Date     : 23-11-2015
// Description      : This is file email process template
///////////////////////////////////////////

class emailDrop {
	//TODO - Insert your code here
	

	function __construct() {
	
		//TODO - Insert your code here
		$this->request = $_REQUEST;
		$this->files = $_FILES;
		$this->responseArray = array();
		$this->urlBase = "http://droptech/emailDrop/server/email.php";
		$this->urlBase = "https://droptech.in/emailDrop/server/email.php";
		$this->url = "?username=vishu&password=1244";
		$this->to = "";
		$this->from = "";
		$this->subject = "";
		$this->body = "";
		$this->type = "";
		$this->debug = 'false';
		$this->content = array();
	}
	
	/**
	 * 
	 */
	function __destruct() {
	
		//TODO - Insert your code here
	}
	function debug($flag='false'){
		if($flag=="true" || $flag==true || $flag===true){
			$this->debug='true';
		}else{
			$this->debug='false';
		}
	}
	function to($to){
		$this->isEmail($to);
		$this->to =$to;
	}
	function from($from){
		$this->isEmail($from);
		$this->from =$from;
	}
	function subject($subject){
		$this->subject =$subject;
	}
	function body($body){
		$this->body =$body;
	}
	function emailType($type){
		$this->type =$type;
	}
	function baseUrl($Url){
		$this->url =$Url;
	}
	function content($content){
		$this->content = json_encode($content);
	}
	function send(){
		$this->prepareArgument('debug', $this->debug);
		$this->prepareArgument('to', $this->to);
		$this->prepareArgument('from', $this->from);
		$this->prepareArgument('subject', $this->subject);
		//$this->prepareArgument('type', $this->type);
		$this->url =$this->url."&type=".$this->type;
		if($this->body!="")$this->prepareArgument('body', $this->body);
		$this->prepareArgument('content', $this->content);
		echo $this->curl();
	}
	
	function curl(){	
		//echo $this->url; exit;
		if (!function_exists('curl_init')){
			die('Sorry cURL is not installed!');
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->urlBase);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$this->url);
		curl_setopt($ch, CURLOPT_REFERER, "http://www.example.org/yay.htm");
		curl_setopt($ch, CURLOPT_USERAGENT, "MozillaXYZ/1.0");
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 100);
		$output = curl_exec($ch);
		curl_close($ch);
		return $output;	
	}
	function prepareArgument($key , $value){
		if($value==null || $value ==""){
			$this->jsonReturnToDisplay(array('status' => 'false','value' =>"( ".$key." ) missing argument to init the functions."));
		}else{
			$this->url =$this->url."&".$key."=".$value;
		}
	}
	function isEmail($email){
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$this->jsonReturnToDisplay(array('status' => 'false','value' =>"Invalid Email Address Format"));
		}
	}
	// function for throw error message
	function jsonReturnToDisplay($arrayName)
	{
		$arrayName=array($arrayName);
		echo json_encode($arrayName);
		exit;
	}
	// function for throw error message
	function jsonDisplay($arrayName)
	{
		$arrayName=array($arrayName);
		echo json_encode($arrayName);		
	}
	function stringFormmat($str){
		$str = urldecode($str);
		//$str = str_replace(" ", '`', $str);
		//$str = str_replace("'", '`', $str);
		//$str = str_replace("'", '`', $str);
		//$str = str_replace("'", '`', $str);
		//$str = str_replace("'", '`', $str);
		//$str = str_replace("'", '`', $str);
		return  $str;
	}
	
}

?>