<?php
$pageName = basename(__FILE__, '.php');
APIInfoPageStart($pageName, "Retrieves the master metadata required by the client application.");
?>
        <tbody>
            <tr><td>Endpoint</td><td><code><?php echo $pageName;?></code></td></tr>
            <tr><td>Method</td><td><strong>POST</strong></td></tr>
            <tr><td>Request</td><td><details><summary><strong>Sample Request</strong></summary><pre>
{
  "outletId":5,
  "staffId":19,
  "flag":false
}

			</pre></details></td></tr>
            <tr><td>Response</td><td><details><summary><strong>Sample response</strong></summary>
<pre>
{
    "errors": [
        "Incorrect subscription_id format in include_subscription_ids (not a valid UUID): dB3JWALVQOORYj6rkiaww-:APA91bHMGEQDF_9JLZOHxXQKLkrhEi3uV_ZALDFHLsFVMQv3cI5BpQgxA1wI87vKaCEg3otvwXkpifQ8jklbhBqQwjtuP0RNIcEtBlK5UPy_R0cCWVi1Tv88"
    ]
}{
    "status": "true",
    "value": "onDutyFlag updated successfully",
    "onDutyFlag": "NO",
    "info": {
        "id": "19",
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
    }
}
</pre></details></td>
            </tr>
        </tbody>
<?php APIInfoPageEnd(); ?>
