<?php
$pageName = basename(__FILE__, '.php');
APIInfoPageStart($pageName, "Submits a room service request, optionally with an audio recording.");
?>
        <tbody>
            <tr><td>Endpoint</td><td><code><?php echo $pageName;?></code></td></tr>
            <tr><td>Method</td><td><strong>POST (multipart/form-data or application/json)</strong></td></tr>
            <tr><td>Request</td><td><details><summary><strong>Request fields</strong></summary>
<pre>
userId=5
roomId=22
serviceId=113
name=Send Luggage Boy
guestId=
guestMobile=
guestName=
additionalField=
note=Please call before entering
audio=&lt;optional audio file or data:audio/webm;codecs=opus;base64,...&gt;
</pre>
            <p>The <code>audio</code> field is optional. Send an audio file as multipart form data, a base64 audio data URI (for example, <code>data:audio/webm;codecs=opus;base64,...</code>), or a raw base64 string. Audio is saved in its original supported format without conversion. MP3 and M4A/AAC are recommended for broad mobile compatibility; WebM/Opus playback depends on the device and browser.</p>
            <p>A duplicate request is rejected when a request for the same outlet, room, and service has any status other than <code>CLOSE</code>, regardless of its date.</p>
        </details></td></tr>
            <tr><td>Response</td><td><details><summary><strong>Sample response</strong></summary>
<pre>
{
    "status": true,
    "value": "Room service request submitted successfully.",
    "action": "roomServiceRequestSubmit",
    "id": 302,
    "reqCode": "RSRQ-00000302",
    "audio": "https://dashboard.1menus.com/media/room_service_request/8a04d49ddc82434bcaefaa7428e15f1a.webm"
}
</pre>
            <p>Request details return the saved audio file as an absolute URL under <code>https://dashboard.1menus.com/media/room_service_request/</code>; requests without audio return <code>null</code>.</p>
        </details></td></tr>
        </tbody>
<?php APIInfoPageEnd(); ?>
