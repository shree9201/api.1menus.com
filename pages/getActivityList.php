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
  "staffId":19
}
</pre>
        </details></td></tr>
            <tr><td>Response</td><td><details><summary><strong>Sample response</strong></summary>
<pre>
{
    "status": "true",
    "value": "Activity list fetched successfully",
    "count": 26,
    "activityList": [
        {
            "id": "792",
            "roomId": "2",
            "roomTitle": "101",
            "userId": "5",
            "userName": "Demo Hotel",
            "dateTime": "2023-02-03 08:20:02",
            "status": "NEW",
            "comment": "The In Room Service Request has been CREATED by Customer  (Room-333)",
            "created_date": "2023-02-03 20:20:02"
        },
        {
            "id": "790",
            "roomId": "2",
            "roomTitle": "101",
            "userId": "5",
            "userName": "Demo Hotel",
            "dateTime": "2023-02-03 08:19:19",
            "status": "NEW",
            "comment": "The In Room Service Request has been CREATED by Customer  (Room-333)",
            "created_date": "2023-02-03 20:19:19"
        },
        {
            "id": "788",
            "roomId": "2",
            "roomTitle": "101",
            "userId": "5",
            "userName": "Demo Hotel",
            "dateTime": "2023-02-03 08:13:37",
            "status": "NEW",
            "comment": "The In Room Service Request has been CREATED by Customer  (Room-333)",
            "created_date": "2023-02-03 20:13:37"
        },
        {
            "id": "786",
            "roomId": "2",
            "roomTitle": "101",
            "userId": "5",
            "userName": "Demo Hotel",
            "dateTime": "2023-02-03 08:10:13",
            "status": "NEW",
            "comment": "The In Room Service Request has been CREATED by Customer  (Room-333)",
            "created_date": "2023-02-03 20:10:13"
        },
        {
            "id": "266",
            "roomId": "2",
            "roomTitle": "101",
            "userId": "5",
            "userName": "Demo Hotel",
            "dateTime": "2022-12-04 16:09:33",
            "status": "END",
            "comment": "The In Room Service Request has been ENDED. <br>(Name-Mr. Rushi Sharma),(Room-333)",
            "created_date": "2022-12-04 16:09:33"
        },
        {
            "id": "265",
            "roomId": "2",
            "roomTitle": "101",
            "userId": "5",
            "userName": "Demo Hotel",
            "dateTime": "2022-12-04 16:09:29",
            "status": "START",
            "comment": "The In Room Service Request has been STARTED. <br>(Name-Mr. Rushi Sharma),(Room-333)",
            "created_date": "2022-12-04 16:09:29"
        },
        {
            "id": "264",
            "roomId": "2",
            "roomTitle": "101",
            "userId": "5",
            "userName": "Demo Hotel",
            "dateTime": "2022-12-04 16:09:26",
            "status": "ACCEPT",
            "comment": "The In Room Service Request has been ACCEPTED. <br>(Name-Mr. Rushi Sharma),(Room-333)",
            "created_date": "2022-12-04 16:09:26"
        },
        {
            "id": "263",
            "roomId": "2",
            "roomTitle": "101",
            "userId": "5",
            "userName": "Demo Hotel",
            "dateTime": "2022-12-04 04:09:23",
            "status": "NEW",
            "comment": "The In Room Service Request has been CREATED by Customer  (Room-333)",
            "created_date": "2022-12-04 16:09:23"
        },
        {
            "id": "260",
            "roomId": "2",
            "roomTitle": "101",
            "userId": "5",
            "userName": "Demo Hotel",
            "dateTime": "2022-12-04 04:07:34",
            "status": "CLOSE",
            "comment": "The In Room Service Request has been CLOSED by Customer",
            "created_date": "2022-12-04 16:07:34"
        },
        {
            "id": "259",
            "roomId": "2",
            "roomTitle": "101",
            "userId": "5",
            "userName": "Demo Hotel",
            "dateTime": "2022-12-04 04:06:04",
            "status": "CLOSE",
            "comment": "The In Room Service Request has been CLOSED by Customer",
            "created_date": "2022-12-04 16:06:04"
        },
        {
            "id": "258",
            "roomId": "2",
            "roomTitle": "101",
            "userId": "5",
            "userName": "Demo Hotel",
            "dateTime": "2022-12-04 16:05:10",
            "status": "END",
            "comment": "The In Room Service Request has been ENDED. <br>(Name-Mr. Rushi Sharma),(Room-333)",
            "created_date": "2022-12-04 16:05:10"
        },
        {
            "id": "257",
            "roomId": "2",
            "roomTitle": "101",
            "userId": "5",
            "userName": "Demo Hotel",
            "dateTime": "2022-12-04 16:04:58",
            "status": "START",
            "comment": "The In Room Service Request has been STARTED. <br>(Name-Mr. Rushi Sharma),(Room-333)",
            "created_date": "2022-12-04 16:04:58"
        },
        {
            "id": "256",
            "roomId": "2",
            "roomTitle": "101",
            "userId": "5",
            "userName": "Demo Hotel",
            "dateTime": "2022-12-04 16:04:54",
            "status": "ACCEPT",
            "comment": "The In Room Service Request has been ACCEPTED. <br>(Name-Mr. Rushi Sharma),(Room-333)",
            "created_date": "2022-12-04 16:04:54"
        },
        {
            "id": "255",
            "roomId": "2",
            "roomTitle": "101",
            "userId": "5",
            "userName": "Demo Hotel",
            "dateTime": "2022-12-04 04:04:37",
            "status": "NEW",
            "comment": "The In Room Service Request has been CREATED by Customer  (Room-333)",
            "created_date": "2022-12-04 16:04:37"
        },
        {
            "id": "109",
            "roomId": "2",
            "roomTitle": "101",
            "userId": "5",
            "userName": "Demo Hotel",
            "dateTime": "2022-09-26 03:52:37",
            "status": "END",
            "comment": "The In Room Service Request has been ENDED. <br>(Name-Mr. Rushi Sharma),(Room-333)",
            "created_date": "2022-09-26 15:52:37"
        },
        {
            "id": "108",
            "roomId": "2",
            "roomTitle": "101",
            "userId": "5",
            "userName": "Demo Hotel",
            "dateTime": "2022-09-26 03:52:36",
            "status": "START",
            "comment": "The In Room Service Request has been STARTED. <br>(Name-Mr. Rushi Sharma),(Room-333)",
            "created_date": "2022-09-26 15:52:36"
        },
        {
            "id": "107",
            "roomId": "2",
            "roomTitle": "101",
            "userId": "5",
            "userName": "Demo Hotel",
            "dateTime": "2022-09-26 03:52:35",
            "status": "ACCEPT",
            "comment": "The In Room Service Request has been ACCEPTED. <br>(Name-Mr. Rushi Sharma),(Room-333)",
            "created_date": "2022-09-26 15:52:35"
        },
        {
            "id": "104",
            "roomId": "2",
            "roomTitle": "101",
            "userId": "5",
            "userName": "Demo Hotel",
            "dateTime": "2022-09-25 03:17:16",
            "status": "REOPEN",
            "comment": "The In-Room Service Request has been RE-OPENED by customer with a Comment - <br> (Service not attende properly)",
            "created_date": "2022-09-25 15:17:16"
        },
        {
            "id": "103",
            "roomId": "2",
            "roomTitle": "101",
            "userId": "5",
            "userName": "Demo Hotel",
            "dateTime": "2022-09-25 03:15:02",
            "status": "END",
            "comment": "The In Room Service Request has been ENDED. <br>(Name-Mr. Rushi Sharma),(Room-333)",
            "created_date": "2022-09-25 15:15:02"
        },
        {
            "id": "102",
            "roomId": "2",
            "roomTitle": "101",
            "userId": "5",
            "userName": "Demo Hotel",
            "dateTime": "2022-09-25 03:14:18",
            "status": "END",
            "comment": "The In Room Service Request has been ENDED. <br>(Name-Mr. Rushi Sharma),(Room-333)",
            "created_date": "2022-09-25 15:14:18"
        },
        {
            "id": "101",
            "roomId": "2",
            "roomTitle": "101",
            "userId": "5",
            "userName": "Demo Hotel",
            "dateTime": "2022-09-25 03:14:05",
            "status": "START",
            "comment": "The In Room Service Request has been STARTED. <br>(Name-Mr. Rushi Sharma),(Room-333)",
            "created_date": "2022-09-25 15:14:05"
        },
        {
            "id": "100",
            "roomId": "2",
            "roomTitle": "101",
            "userId": "5",
            "userName": "Demo Hotel",
            "dateTime": "2022-09-25 03:13:46",
            "status": "ACCEPT",
            "comment": "The In Room Service Request has been ACCEPTED. <br>(Name-Mr. Rushi Sharma),(Room-333)",
            "created_date": "2022-09-25 15:13:46"
        },
        {
            "id": "99",
            "roomId": "2",
            "roomTitle": "101",
            "userId": "5",
            "userName": "Demo Hotel",
            "dateTime": "2022-09-25 03:13:08",
            "status": "START",
            "comment": "The In Room Service Request has been STARTED. <br>(Name-Mr. Rushi Sharma),(Room-333)",
            "created_date": "2022-09-25 15:13:08"
        },
        {
            "id": "98",
            "roomId": "2",
            "roomTitle": "101",
            "userId": "5",
            "userName": "Demo Hotel",
            "dateTime": "2022-09-25 03:13:03",
            "status": "ACCEPT",
            "comment": "The In Room Service Request has been ACCEPTED. <br>(Name-Mr. Rushi Sharma),(Room-333)",
            "created_date": "2022-09-25 15:13:03"
        },
        {
            "id": "97",
            "roomId": "2",
            "roomTitle": "101",
            "userId": "5",
            "userName": "Demo Hotel",
            "dateTime": "2022-09-25 03:12:40",
            "status": "ASSIGN",
            "comment": "The In Room Service Request has been ASSIGNED. <br>(Name-Mr. Rushi Sharma),(Room-333)",
            "created_date": "2022-09-25 15:12:40"
        },
        {
            "id": "95",
            "roomId": "2",
            "roomTitle": "101",
            "userId": "5",
            "userName": "Demo Hotel",
            "dateTime": "2022-09-25 02:59:54",
            "status": "NEW",
            "comment": "The In Room Service Request has been CREATED by Customer  (Room-333)",
            "created_date": "2022-09-25 14:59:54"
        }
    ]
}
</pre></details></td>
            </tr>
        </tbody>
<?php APIInfoPageEnd(); ?>
