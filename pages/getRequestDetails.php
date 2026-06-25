<?php
$pageName = basename(__FILE__, '.php');
APIInfoPageStart($pageName, "Retrieves all available device IDs against staff id.");
?>
        <tbody>
            <tr><td>Endpoint</td><td><code><?php echo $pageName;?></code></td></tr>
            <tr><td>Method</td><td><strong>POST</strong></td></tr>
            <tr><td>Request</td><td><details><summary><strong>Sample Request</strong></summary>
<pre>
{
  "outletId":5,
  "requestId":302
}

</pre>
        </details></td></tr>
            <tr><td>Response</td><td><details><summary><strong>Sample response</strong></summary>
<pre>
{
    "status": "true",
    "value": {
        "id": "24",
        "reqCode": "RSRQ-00000024",
        "title": "",
        "userId": "5",
        "roomId": "2",
        "serviceId": "113",
        "assigned": "0",
        "start_time": "2022-09-25 15:13:08",
        "end_time": "2022-09-25 15:14:18",
        "date": "2022-09-25",
        "ip": "45.252.70.51",
        "additionalField": "25-09-2022 01:00:00 AM",
        "audio": null,
        "note": "",
        "status": "CLOSE",
        "escalated_by": null,
        "created_date": "2022-09-25 15:11:04",
        "updated_date": "2022-12-04 16:08:57",
        "guestId": "0",
        "guestName": "",
        "guestMobile": "",
        "guestCode": "",
        "points": "1"
    },
    "activity": [
        {
            "dateTime": "2022-09-25 03:12:40",
            "status": "ASSIGN",
            "comment": "The In Room Service Request has been ASSIGNED. <br>(Name-Mr. Rushi Sharma),(Room-333)",
            "created_date": "2022-09-25 15:12:40",
            "assignedTo": "Mr.Ravi",
            "assignedMobile": "134567890"
        },
        {
            "dateTime": "2022-09-25 03:13:03",
            "status": "ACCEPT",
            "comment": "The In Room Service Request has been ACCEPTED. <br>(Name-Mr. Rushi Sharma),(Room-333)",
            "created_date": "2022-09-25 15:13:03",
            "assignedTo": "Mr.Ravi",
            "assignedMobile": "134567890"
        },
        {
            "dateTime": "2022-09-25 03:13:08",
            "status": "START",
            "comment": "The In Room Service Request has been STARTED. <br>(Name-Mr. Rushi Sharma),(Room-333)",
            "created_date": "2022-09-25 15:13:08",
            "assignedTo": "Mr.Ravi",
            "assignedMobile": "134567890"
        },
        {
            "dateTime": "2022-09-25 03:14:18",
            "status": "END",
            "comment": "The In Room Service Request has been ENDED. <br>(Name-Mr. Rushi Sharma),(Room-333)",
            "created_date": "2022-09-25 15:14:18",
            "assignedTo": "Mr.Ravi",
            "assignedMobile": "134567890"
        },
        {
            "dateTime": "2022-09-25 03:17:46",
            "status": "ESCALATED",
            "comment": "The In Room Service Request has been ESCALATEDED. <br>(Name-Mr. Prasad Sharma),(Room-333)",
            "created_date": "2022-09-25 15:17:46",
            "assignedTo": "Mrs. Priya",
            "assignedMobile": "8978451245"
        }
    ]
}
</pre></details></td>
            </tr>
        </tbody>
<?php APIInfoPageEnd(); ?>
