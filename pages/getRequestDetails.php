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
        "id": "2",
        "reqCode": "RSRQ-00000002",
        "title": "",
        "userId": "5",
        "roomId": "2",
        "serviceId": "17",
        "assigned": "2",
        "start_time": null,
        "end_time": null,
        "date": "2026-06-24",
        "ip": "::1",
        "additionalField": "",
        "audio": null,
        "note": "123123123",
        "status": "NEW",
        "escalated_by": null,
        "created_date": "2026-06-24 16:54:20",
        "updated_date": "2026-06-24 16:54:20",
        "guestId": null,
        "guestName": null,
        "guestMobile": null,
        "guestCode": null,
        "points": "0"
    },
    "serviceDetails": {
        "id": "17",
        "title": "Need towel 123",
        "userId": "5",
        "boxId": "2",
        "serviceId": "5",
        "actionBy": "HKMGR",
        "aksDateTime": "NO",
        "sq": "6",
        "information": "",
        "reminderTime": "0",
        "escalationTime": "0",
        "priority": "Medium",
        "points": "1",
        "onHoldOption": "NO",
        "status": "YES",
        "created_date": "2022-06-23 09:49:25",
        "updated_date": "2023-02-17 15:33:43"
    },
    "activity": [
        {
            "dateTime": "2026-06-19 03:47:43",
            "status": "NEW",
            "comment": "The In Room Service Request has been CREATED by Customer  (Room-101)",
            "created_date": "2026-06-19 15:47:43",
            "assignedTo": "VISHWAJEET MAHADIK",
            "assignedMobile": "015154676447",
            "timeSpent": 0,
            "startTime": "2026-06-19 15:47:43",
            "endTime": null,
            "timeSpentMinutes": 0,
            "timeSpentFormatted": "0h 0m"
        },
        {
            "dateTime": "2026-06-24 04:54:20",
            "status": "NEW",
            "comment": "The In Room Service Request has been CREATED by Customer  (Room-101)",
            "created_date": "2026-06-24 16:54:20",
            "assignedTo": "VISHWAJEET MAHADIK",
            "assignedMobile": "015154676447",
            "timeSpent": 435997,
            "startTime": "2026-06-24 16:54:20",
            "endTime": "2026-06-19 15:47:43",
            "timeSpentMinutes": 7266.62,
            "timeSpentFormatted": "121h 6m"
        }
    ],
    "timeMetrics": {
        "totalTimeMinutes": 7266.62,
        "totalTimeFormatted": "121h 6m",
        "activitiesCount": 2,
        "averageTimePerActivityMinutes": 0
    }
}
</pre></details></td>
            </tr>
        </tbody>
<?php APIInfoPageEnd(); ?>
