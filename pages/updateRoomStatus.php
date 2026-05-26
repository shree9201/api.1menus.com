<?php
$pageName = basename(__FILE__, '.php');
$infoPass = array(
		'structure' => '&userId=[userId]&token=[token]&roomNo=[Room No.]&status=[status]',
		'example' 	=> '&userId=48&token=c1s67MbWC24TGoOjMXC1RieoqwQfpdcWjuvi4UuGbBl&roomNo=101&status=1',
		'responce' => 'Status (false/true)',
		);
APIInfoPageStart($pageName,$infoPass);
?>
        <tbody>
            <tr><td>action</td><td><?php echo $pageName;?></td></tr>
            <tr><td>userId</td><td>Outlet Id</td></tr>
            <tr><td>token</td><td>Unique Outlet Token No for access API</td></tr>
            <tr><td>roomNo</td><td>Hotel Room number (E.g: 101,102) </td></tr>
            <tr><td>status</td><td>Room Status To be set (E.g: 1/0)</td></tr>
        </tbody>
 <?php APIInfoPageEnd();?>   
