<?php
$pageName = basename(__FILE__, '.php');
$infoPass = array(
		'structure' => '&id=[id]&mobile=[mobile]&username=[username]&token=[token]',
		'example' 	=> '&id=22&mobile=8011558810&username=cafe99&token=CASD45aasd455Sdsdghjssddfas5eSw4sAs544aSSsasd',
		'responce' => 'Status (false/true), if true user information array',
		);
APIInfoPageStart($pageName,$infoPass);
?>
        <tbody>
            <tr><td>action</td><td><?php echo $pageName;?></td></tr>
            <tr><td>id</td><td>Outlet Id</td></tr>
            <tr><td>mobile</td><td>Outlet Mobile</td></tr>
            <tr><td>token</td><td> Token Number</td></tr>
            <tr><td>username</td><td>Outlet username  </td></tr>
        </tbody>
 <?php APIInfoPageEnd();?>   
