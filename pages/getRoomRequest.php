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
            "id": "302",
            "reqCode": "RSRQ-00000302",
            "title": "",
            "userId": "5",
            "roomId": "199",
            "serviceId": "68",
            "assigned": "20",
            "start_time": null,
            "end_time": null,
            "date": "2023-07-08",
            "ip": "152.58.18.57",
            "additionalField": "",
            "audio": null,
            "note": "",
            "status": "NEW",
            "escalated_by": null,
            "created_date": "2023-07-08 22:29:39",
            "updated_date": "2023-07-08 22:29:39",
            "guestId": "0",
            "guestName": "",
            "guestMobile": "",
            "guestCode": "",
            "points": "0"
        }
    ]
}
</pre></details></td>
            </tr>
        </tbody>
<?php APIInfoPageEnd(); ?>
