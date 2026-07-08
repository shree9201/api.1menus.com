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
  "outletId": 5,
  "requestId": 1,
  "status": "ACCEPTED",
  "assignedTo":2,
  "comment": "Request Accepted by Rvi"
}
</pre>
        </details></td></tr>
            <tr><td>Response</td><td><details><summary><strong>Sample response</strong></summary>
<pre>
	{
    "status": "true",
    "value": "Request updated successfully",
    "requestDetails": {
        "status": "true",
        "value": {
            "id": "1",
            "reqCode": "RSRQ-00000001",
            "title": "",
            "userId": "5",
            "roomId": "2",
            "serviceId": "19",
            "assigned": "2",
            "start_time": null,
            "end_time": null,
            "date": "2026-06-24",
            "ip": "::1",
            "additionalField": "",
            "audio": null,
            "note": "asdasdas",
            "status": "ACCEPTED",
            "escalated_by": null,
            "created_date": "2026-06-24 16:51:53",
            "updated_date": "2026-06-25 15:25:28",
            "guestId": null,
            "guestName": null,
            "guestMobile": null,
            "guestCode": null,
            "points": "0"
        },
        "activity": [
            {
                "dateTime": "2026-06-19 03:45:54",
                "status": "NEW",
                "comment": "The In Room Service Request has been CREATED by Customer  (Room-101)",
                "created_date": "2026-06-19 15:45:54",
                "assignedTo": "VISHWAJEET MAHADIK",
                "assignedMobile": "015154676447",
                "timeData": []
            },
            {
                "dateTime": "2026-06-24 04:51:53",
                "status": "NEW",
                "comment": "The In Room Service Request has been CREATED by Customer  (Room-101)",
                "created_date": "2026-06-24 16:51:53",
                "assignedTo": "VISHWAJEET MAHADIK",
                "assignedMobile": "015154676447",
                "timeData": []
            },
            {
                "dateTime": "2026-06-25 14:58:42",
                "status": "ACCEPTED",
                "comment": "Request Accepted by Rvi",
                "created_date": "2026-06-25 14:58:42",
                "assignedTo": "VISHWAJEET MAHADIK",
                "assignedMobile": "015154676447",
                "timeData": []
            },
            {
                "dateTime": "2026-06-25 15:14:01",
                "status": "ACCEPTED",
                "comment": "Request Accepted by Rvi",
                "created_date": "2026-06-25 15:14:01",
                "assignedTo": "VISHWAJEET MAHADIK",
                "assignedMobile": "015154676447",
                "timeData": []
            },
            {
                "dateTime": "2026-06-25 15:14:33",
                "status": "ACCEPTED",
                "comment": "Request Accepted by Rvi",
                "created_date": "2026-06-25 15:14:33",
                "assignedTo": "VISHWAJEET MAHADIK",
                "assignedMobile": "015154676447",
                "timeData": []
            },
            {
                "dateTime": "2026-06-25 15:20:46",
                "status": "ACCEPTED",
                "comment": "Request Accepted by Rvi",
                "created_date": "2026-06-25 15:20:46",
                "assignedTo": "VISHWAJEET MAHADIK",
                "assignedMobile": "015154676447",
                "timeData": []
            },
            {
                "dateTime": "2026-06-25 15:22:26",
                "status": "ACCEPTED",
                "comment": "Request Accepted by Rvi",
                "created_date": "2026-06-25 15:22:26",
                "assignedTo": "VISHWAJEET MAHADIK",
                "assignedMobile": "015154676447",
                "timeData": []
            },
            {
                "dateTime": "2026-06-25 15:25:28",
                "status": "ACCEPTED",
                "comment": "Request Accepted by Rvi",
                "created_date": "2026-06-25 15:25:28",
                "assignedTo": "VISHWAJEET MAHADIK",
                "assignedMobile": "015154676447",
                "timeData": []
            }
        ],
        "timeTracking": [],
        "timeMetrics": {
            "totalTimeMinutes": 0,
            "totalTimeFormatted": "0h 0m",
            "activitiesCount": 0,
            "staffInvolved": [],
            "timeline": [],
            "averageTimePerActivityMinutes": 0
        }
    },
    "notificationDetails": {
        "status": true,
        "value": "Notifications processed",
        "sent": 0,
        "failed": 8,
        "totalDevices": 8,
        "response": [
            {
                "deviceId": "3352",
                "status": "failed",
                "httpCode": 500,
                "error": {
                    "error": "Client error: `POST https://oauth2.googleapis.com/token` resulted in a `400 Bad Request` response:\n{\"error\":\"invalid_grant\",\"error_description\":\"Invalid JWT Signature.\"}\n"
                }
            },
            {
                "deviceId": "dB3JWALVQOORYj6rkiaww-:APA91bHMGEQDF_9JLZOHxXQKLkrhEi3uV_ZALDFHLsFVMQv3cI5BpQgxA1wI87vKaCEg3otvwXkpifQ8jklbhBqQwjtuP0RNIcEtBlK5UPy_R0cCWVi1Tvc",
                "status": "failed",
                "httpCode": 500,
                "error": {
                    "error": "Client error: `POST https://oauth2.googleapis.com/token` resulted in a `400 Bad Request` response:\n{\"error\":\"invalid_grant\",\"error_description\":\"Invalid JWT Signature.\"}\n"
                }
            },
            {
                "deviceId": "dNjPuf1yT9GcdlgnNcOKKo:APA91bFSAdQfJ7ToKfL1vrI4jtgNBp6rIJ9J7nZTMErVn-CI36c6MydKdIno1k2ANwY4dBUyNqxlFj2_2fzHGoE5TXzmJp1xVF6TscrR7fATdLu9LR0VysQ",
                "status": "failed",
                "httpCode": 500,
                "error": {
                    "error": "Client error: `POST https://oauth2.googleapis.com/token` resulted in a `400 Bad Request` response:\n{\"error\":\"invalid_grant\",\"error_description\":\"Invalid JWT Signature.\"}\n"
                }
            },
            {
                "deviceId": "fS0ec6PFRcWPgZThe_QX8S:APA91bHoMrDigDUQeb1d0hxxqsw1w7aM_LOYh_lsxkfos14IBJX8T3dLDyfS1HASDdA8kyMs4WYdPAVUZ4K1aDmASM5ciqbxp31CufrulHXQgGnNmh42JYI",
                "status": "failed",
                "httpCode": 500,
                "error": {
                    "error": "Client error: `POST https://oauth2.googleapis.com/token` resulted in a `400 Bad Request` response:\n{\"error\":\"invalid_grant\",\"error_description\":\"Invalid JWT Signature.\"}\n"
                }
            },
            {
                "deviceId": "eB2v-sFmTIOPR516RHh-Wv:APA91bEQnZwWPYDBcCzgL-Gel-0MvGQzaxr4H_Y5oHHuBcGkI5E6MkA7aOrfnDRnTP7vo0EaX4mF9KsLpMGltpIUIwhnjhlCvi56t5R8ZHclgFcx91qeDcA",
                "status": "failed",
                "httpCode": 500,
                "error": {
                    "error": "Client error: `POST https://oauth2.googleapis.com/token` resulted in a `400 Bad Request` response:\n{\"error\":\"invalid_grant\",\"error_description\":\"Invalid JWT Signature.\"}\n"
                }
            },
            {
                "deviceId": "dB3JWALVQOORYj6rkiaww-:APA91bHMGEQDF_9JLZOHxXQKLkrhEi3uV_ZALDFHLsFVMQv3cI5BpQgxA1wI87vKaCEg3otvwXkpifQ8jklbhBqQwjtuP0RNIcEtBlK5UPy_R0cCWVi1Tvc",
                "status": "failed",
                "httpCode": 500,
                "error": {
                    "error": "Client error: `POST https://oauth2.googleapis.com/token` resulted in a `400 Bad Request` response:\n{\"error\":\"invalid_grant\",\"error_description\":\"Invalid JWT Signature.\"}\n"
                }
            },
            {
                "deviceId": "dtOTEgHtS0-y8sQQOFTWIR:APA91bEPvOUUFTNJfliXH5tysTlBzwK7VregZNAN7IyGUugQgXqvu01ecp1RjwKVnnjwSbZBwyU49Dqj8PAftb9Kk8BhiF5z325uoOjhgDk7jOCqfaDrx-0",
                "status": "failed",
                "httpCode": 500,
                "error": {
                    "error": "Client error: `POST https://oauth2.googleapis.com/token` resulted in a `400 Bad Request` response:\n{\"error\":\"invalid_grant\",\"error_description\":\"Invalid JWT Signature.\"}\n"
                }
            },
            {
                "deviceId": "e7Wegr8aRDK2FExXI6D3EU:APA91bHyIAsxKDpnRcudu9vhCewWFBWqXQomosDJDn67qXsHzdQszUxOC9ZviBs0ejdfpMfqxCrY4Q28C1SLcoKbYgZC-8hpYavTeIsIm_duKI1muptqky4",
                "status": "failed",
                "httpCode": 500,
                "error": {
                    "error": "Client error: `POST https://oauth2.googleapis.com/token` resulted in a `400 Bad Request` response:\n{\"error\":\"invalid_grant\",\"error_description\":\"Invalid JWT Signature.\"}\n"
                }
            }
        ]
    }
}
</pre></details></td>
            </tr>
        </tbody>
<?php APIInfoPageEnd(); ?>
