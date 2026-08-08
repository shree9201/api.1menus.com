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
}

			</pre></details></td></tr>
            <tr><td>Response</td><td><details><summary><strong>Sample response</strong></summary>
<pre>
{
    "status": "true",
    "value": "result found",
    "count": 4,
    "attendanceDetails": [
        {
            "staffId": "19",
            "date": "2026-08-06",
            "date_time": "2026-08-06 15:52:55",
            "login_date_time": "2026-08-06 15:52:55",
            "logout_date_time": "2026-08-06 15:53:10",
            "created_date": "2026-08-06 15:52:55",
            "updated_date": "2026-08-06 15:53:10",
            "totalWorkingHours": "0h 0m",
            "totalWorkingMinutes": 0.25
        },
        {
            "staffId": "21",
            "date": "2026-08-06",
            "date_time": "2026-08-06 15:56:49",
            "login_date_time": "2026-08-06 15:56:49",
            "logout_date_time": "2026-08-06 15:57:19",
            "created_date": "2026-08-06 15:56:49",
            "updated_date": "2026-08-06 15:57:19",
            "totalWorkingHours": "0h 0m",
            "totalWorkingMinutes": 0.5
        },
        {
            "staffId": "19",
            "date": "2026-08-06",
            "date_time": "2026-08-06 16:30:37",
            "login_date_time": "2026-08-06 16:30:37",
            "logout_date_time": "2026-08-06 16:30:49",
            "created_date": "2026-08-06 16:30:37",
            "updated_date": "2026-08-06 16:30:49",
            "totalWorkingHours": "0h 0m",
            "totalWorkingMinutes": 0.2
        },
        {
            "staffId": "19",
            "date": "2026-08-06",
            "date_time": "2026-08-06 17:00:48",
            "login_date_time": "2026-08-06 17:00:48",
            "logout_date_time": "2026-08-06 17:01:04",
            "created_date": "2026-08-06 17:00:48",
            "updated_date": "2026-08-06 17:01:04",
            "totalWorkingHours": "0h 0m",
            "totalWorkingMinutes": 0.27
        }
    ]
}
</pre></details></td>
            </tr>
        </tbody>
<?php APIInfoPageEnd(); ?>
