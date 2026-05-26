<?php
///////////////////////////////////////////
// File Name        : template.php
// Craeted By       : Vishwajeet Mahadik
// Created Date     : 21-11-2015
// File Modified By : Vishwajeet Mahadik
// Modify  Date     : 22-11-2015
// Description      : This is file email template file.
///////////////////////////////////////////
class template {
	//TODO - Insert your code here
	

	function __construct() {
	
		//TODO - Insert your code here
		$this->request = $_REQUEST;
		$this->files = $_FILES;
		$this->responseArray = array();
		$this->type = "";
		$this->body = "";
		//$this->templetPreUrl ="https://droptech.in/emailDrop/server/templates/"; 
		$this->templetPreUrl ="https://crestwio.com/app/service/emailDrop/server/templates/";
		
	}
	
	/**
	 * 
	 */
	function __destruct() {
	
		//TODO - Insert your code here
	}
	function templateView(){
		$content = isset($_REQUEST['content'])?$_REQUEST['content']:"";
		$content = (object) json_decode($content);
		$body = "";
		$opts = array(  'http' =>
				array(  'method'  => 'POST',
						'header'  => 'Content-type: application/x-www-form-urlencoded',
						'content' => http_build_query($content)
				)
		);
		$body = file_get_contents($this->templetPreUrl."templateView.php?page=".strtolower($this->type), false, stream_context_create($opts));
		return  $body;				 
	}
	function emailSend($to,$from,$subject,$body){
		$header = "From: ". $from . " \r\n". "Content-type: text/html; charset=iso-8859-1";
		ini_set("SMTP","mail.crestwio.com");
		ini_set("smtp_port","465");
		$result = mail($to, $subject, $body, $header);

		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers = "From: $from\n";
		'X-Mailer: PHP/' . phpversion();
	
		//$result = @mail($to,$subject,$body,$headers);

		$arrayName[] = array('status' => 'true','value' =>"An email send successfully at ".$to." email address");
		echo  json_encode($arrayName);
	}
	function templateDesignMainContent($content){
		$para = $content->paragraph;
		$link = $content->link;
				
		$StrContent = '';
		for($p=0;$p<count($para);$p++){
			$StrContent.='<p>'.$para[$p].'</p>';
		}
		foreach ($link as $k=>$v){
			$StrContent.='<p><a target="_blank" href="'.$v.'" class="btn-primary" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif; box-sizing: border-box; font-size: 14px; color: #FFF; text-decoration: none; line-height: 2em; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background-color: #348eda; margin: 0; border-color: #348eda; border-style: solid; border-width: 10px 20px;">'.$k.'</a></p>';
		}
		return $StrContent;
	}	
}

?>