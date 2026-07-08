 <?php
$pageName = basename(__FILE__, '.php');
APIInfoPageStart($pageName, "Login for application to access their profile and order history.");
?>
        <tbody>
            <tr><td>Endpoint</td><td><code><?php echo $pageName;?></code></td></tr>
            <tr><td>Method</td><td><strong>POST</strong></td></tr>
            <tr><td>Request</td><td><details><summary><strong>Sample Request</strong></summary>
<pre>
    {
    "userType":"STAFF",
    "username": "ravi",
    "password": "ravi",
    "outletId":5,
    "deviceId":"dB3JWALVQOORYj6rkiaww-:APA91bHMGEQDF_9JLZOHxXQKLkrhEi3uV_ZALDFHLsFVMQv3cI5BpQgxA1wI87vKaCEg3otvwXkpifQ8jklbhBqQwjtuP0RNIcEtBlK5UPy_R0cCWVi1Tv88",
    "deviceType":"andriod"
    }
</pre>
        </details></td></tr>
            <tr><td>Response</td><td><details><summary><strong>Sample response</strong></summary>
<pre>
{
    "status": "true",
    "value": {
        "id": "144",
        "title": null,
        "userId": "5",
        "tableIds": null,
        "name": "Mr.Ravi",
        "email": "ravi@1menus.com",
        "mobile": "134567890",
        "username": "ravi",
        "password": "ravi",
        "type": "FO",
        "department": "STAFF",
        "customised_position": "ravi",
        "online": "NO",
        "status": "YES",
        "created_date": "2026-06-02 18:11:48",
        "updated_date": "2026-06-02 18:11:48"
    },
    "devices": [
        {
            "deviceType": "andriod",
            "deviceId": "dB3JWALVQOORYj6rkiaww-:APA91bHMGEQDF_9JLZOHxXQKLkrhEi3uV_ZALDFHLsFVMQv3cI5BpQgxA1wI87vKaCEg3otvwXkpifQ8jklbhBqQwjtuP0RNIcEtBlK5UPy_R0cCWVi1Tv88",
            "last_login": "2026-06-09 15:36:28"
        }
    ]
}
</pre></details></td>
            </tr>
        </tbody>
<?php APIInfoPageEnd(); ?>
