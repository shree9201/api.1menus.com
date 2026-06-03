<?php
$pageName = basename(__FILE__, '.php');
$infoPass = array(
		'structure' => '',
		'example' 	=> '',
		'responce' => 'Status (false/true), if true user information array',
		);
APIInfoPageStart($pageName,$infoPass);
?>
        <tbody>
            <tr><td>action</td><td><?php echo $pageName;?></td></tr>
        </tbody>
 <?php APIInfoPageEnd();?>   
