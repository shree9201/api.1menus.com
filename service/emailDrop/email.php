<?php

require_once 'client/lib/emailDrop.php';
$email = new emailDrop();
$content = array(
		'name'		=>'Mahesh',
		'siteName'	=>'Droptech Solution',
		'siteLink'	=>'www.dropteh.in',
		'logo'		=>"http://localhost/droptech.in/img/logo.png",
		'title'		=>'This is Main Title By Droptech Email',		
		'subtitle'		=>'This is informaing you , you have just created account on droptech website , and wanted to confirm your email address',
		'themeColor'=>'orange',
		'images'	=> array('http://localhost/igms/img/slider-image-3.jpg'),
		'last'		=>array('for more infomation please contact to droptech solution @ +917709034176'),
		'enquiry'	=>array('Name'=>'vishu','Email'=>'vishwajeet9201@gmail.com','Mobile'=>'+91 7709034176','Gender'=>'Male','Product Name'=>'TV','Message'=>'I like this product and service'),
		"level_1"	=>array("vvvvvvvvvvvvvvvvvvvvvvvv","mmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmmm mmmmmmmmmmmmmm mmmmmmmmmmm mmmmmmmmmm mmmmmmm"),
		'paragraph'	=>array('This is first Para with Value','This is second paragraph to display'),
		'link'		=>array('Activate Link'=>'http://www.google.com'),
		'socialLink'=>array('facebook'=>'www.facebook.com','google-plus'=>'ww.google.com','twitter'=>'www.twitter.com'),
		'contactInfo'=>array('phone'=>'+91 7709034176','email'=>'vishwajeet9201@gmail.com'),
		'linkPanel'	=>array('title'=>'Droptech','desc'=>'Web solution IT comany for building nice websites','link'=>array('Link 1'=>'www.google.com','Link 2'=>'www.google.com','Link 3'=>'www.google.com','Link 4'=>'www.google.com','Link 5'=>'www.google.com')),
		//'level_2'	=>array("asd asd asdas das dasd", "asda sdasd asd adsas dasd asda sdas dasd asdas"),
		//'level_3'	=>array('Okyeeeee'),
		//'box'		=>array('Hello'=>"This is text related to describe the hellow word ",'Hello 2'=>'<a target="_blank" href=""><img src="https://www.google.co.in/images/branding/googlelogo/2x/googlelogo_color_272x92dp.png" height="100%" width="100%"></a>','NEW'=>'vishu'),
		
		);
		
$email->emailType('sidebar-hero');
//$email->debug(true);

$email->from('vishwajeet9201@gmail.com');
$email->to('vishwajeet9201@gmail.com');
$email->subject("Test template design");

$email->body("custom body email");

$email->content($content);
echo $email->send();
$to      = 'vishwajeet9201@gmail.com';
$from = 'contact@droptech.in';
?>
<?php

//$subject = 'the subject';
//$message = 'hello';
//$headers = 'From: contact@droptech.in' . "\r\n" .
//    'Reply-To: contact@droptech.in' . "\r\n" .
//    'X-Mailer: PHP/' . phpversion();

//mail($from, $subject, $message, $headers);
?>