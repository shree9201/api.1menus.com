<?php
$pageName = basename(__FILE__, '.php');
$infoPass = array(
		'structure' => '&city=[city]&name=[name]',
		'example' 	=> '&city=pune&name=cafe99',
		'responce' => 'Status (false/true), if true user information array',
		);
APIInfoPageStart($pageName,$infoPass);
?>
        <tbody>
            <tr><td>action</td><td><?php echo $pageName;?></td></tr>
            <tr><td>city</td><td>Outlet City</td></tr>
            <tr><td>name</td><td>Outlet Name</td></tr>
        </tbody>
 <?php APIInfoPageEnd();?>   
