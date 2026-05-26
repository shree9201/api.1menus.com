<?php
$pageName = basename(__FILE__, '.php');
$infoPass = array(
		'structure' => '&email=[email]&mobile=[mobile]&name=[name]&password=[password]&app_type=[app type]',
		'example' 	=> '&name=vishwajeet%20mahadik&email=vishu9201@gmail.com&mobile=7709034176&password=vishu&app_type=web',
		'responce' => 'Status (false/true), if true user information array',
		);
APIInfoPageStart($pageName,$infoPass);
?>
        <tbody>
            <tr><td>action</td><td><?php echo $pageName;?></td></tr>
            <tr><td>name</td><td>Customer Full name (Firstname Lastname)</td></tr>
            <tr><td>email</td><td>Customer Email address</td></tr>
            <tr><td>mobile</td><td>Customer Mobile address</td></tr>
            <tr><td>password</td><td>Customer's profile password </td></tr>
            <tr><td>app_type</td><td>Customer's device type <br>app_type='ios'<br>app_type='android'<br>app_type='web' </td></tr>
        </tbody>
 <?php APIInfoPageEnd();?>   
