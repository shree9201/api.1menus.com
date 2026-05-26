<?php
$pageName = basename(__FILE__, '.php');
$infoPass = array(
		'structure' => '&id=[id]&token=[token]',
		'example' 	=> '&id=22&token=CASD45aasd455Sdsdghjssddfas5eSw4sAs544aSSsasd',
		'responce' => 'Status (false/true), if true user information array',
		);
APIInfoPageStart($pageName,$infoPass);
?>
        <tbody>
            <tr><td>action</td><td><?php echo $pageName;?></td></tr>
            <tr><td>id</td><td>Outlet Id</td></tr>
            <tr><td>token</td><td> Token Number</td></tr>
            <tr><td>status</td><td>(Optional) get status 'OPEN' or 'CLOSE'</td></tr>
            <tr><td>lastUpdated</td><td>(Optional) get Order Specific perid after the timestamp(created_date) format only</td></tr>
            <tr><td>all</td><td>(Optional) If in case all records fetch</td></tr>
        </tbody>
 <?php APIInfoPageEnd();?>   
