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
  "limit":"0,10", // Optional
    "filterBy": [{ // Optional
        "key": "assigned",
        "value": "17"
    },
    {
        "key":"date",
        "value":"2022-09-25"
    }
    ]
}

</pre>
        </details></td></tr>
            <tr><td>Response</td><td><details><summary><strong>Sample response</strong></summary>
<pre>
{
    "status": true,
    "value": "result found",
    "count": 1,
    "roomRequest": [
        {
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
            "points": "0",
            "timeMetrics": {
                "totalTimeMinutes": 0,
                "totalTimeFormatted": "0h 0m",
                "activitiesCount": 0,
                "staffInvolved": [],
                "timeline": [],
                "averageTimePerActivityMinutes": 0
            }
        }
    ]
}
</pre></details></td>
            </tr>
        </tbody>
<?php APIInfoPageEnd(); ?>
