<?php
$pageName = basename(__FILE__, '.php');
$infoPass = array(
		'structure' => 'action=staffLogin&userType=STAFF&outletId=1&username=staff_user&password=1234&deviceId=1234&deviceType=ios',
		'example' 	=> 'action=staffLogin&userType=STAFF&outletId=1&username=staff_user&password=1234&deviceId=1234&deviceType=ios',
		'responce' => 'Status (false/true) and value array',
		);
APIInfoPageStart($pageName,$infoPass);
?>
<tbody>
            <tr><td>action</td><td><?php echo $pageName;?></td></tr>
            <tr><td>userType</td><td>Staff's user type <br>userType='STAFF' / userType='MANAGER'  / userType='HR' </td></tr>
            <tr><td>Outlet Id</td><td>Staff's Outlet Id </td></tr>
            <tr><td>username</td><td>Staff's Application username </td></tr>
            <tr><td>password</td><td>Staff's application password </td></tr>
            <tr><td>deviceId</td><td>Staff's device id for notification </td></tr>
            <tr><td>deviceType</td><td>Staff's device type <br>deviceType='ios'<br>deviceType='android' </td></tr>
        </tbody>
    <?php APIInfoPageEnd();?>
