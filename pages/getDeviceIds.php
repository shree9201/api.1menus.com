<?php
$pageName = basename(__FILE__, '.php');
$infoPass = array(
		'structure' => 'action=getDeviceIds&staffId=2',
		'example' 	=> 'action=getDeviceIds&staffId=22',
		'responce' => 'Status (false/true) and value array',
		);
APIInfoPageStart($pageName,$infoPass);
?>
<tbody>
            <tr><td>action</td><td><?php echo $pageName;?></td></tr>
            <tr><td>Staff Id</td><td>Staff's Id </td></tr>
        </tbody>
    <?php APIInfoPageEnd();?>
