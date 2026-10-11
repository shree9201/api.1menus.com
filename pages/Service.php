<?php
$pageName = basename(__FILE__, '.php');
APIInfoPageStart($pageName, "Retrieves all available device IDs against staff id.");
?>
<tr><td colspan="2"><h3>SERVICE VIEW</h3></td></tr>
        <tbody>
            <tr><td>Endpoint</td><td><code><?php echo $pageName;?>/view/id</code></td></tr>
            <tr><td>Method</td><td><strong>POST</strong></td></tr>
            <tr><td>Request</td><td><details><summary><strong>Sample Request</strong></summary>
<pre>
{
  "outletId":5,
  "staffId":19,
}
</pre>
        </details></td></tr>
            <tr><td>Response</td><td><details><summary><strong>Sample response</strong></summary>
<pre>
{
    "status": "true",
    "value": "Services list fetched successfully",
    "count": 1,
    "servicesList": [
        {
            "id": "283",
            "title": "SPA",
            "userId": "5",
            "boxId": "6",
            "serviceId": "35",
            "actionBy": "FO",
            "aksDateTime": "NO",
            "sq": null,
            "information": "",
            "reminderTime": "0",
            "escalationTime": "0",
            "priority": "Medium",
            "points": "1",
            "onHoldOption": "YES",
            "status": "YES",
            "created_date": "2022-12-17 22:36:11",
            "updated_date": "2022-12-17 22:36:11",
            "category": "Spa Services"
        }
    ]
}
</pre></details></td>
            </tr>
            <tr><td colspan="2"><h3>CATEGORY VIEW</h3></td></tr>
        <tbody>
            <tr><td>Endpoint</td><td><code><?php echo $pageName;?>/category/id</code></td></tr>
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
    "value": "Service categories fetched successfully",
    "count": 1,
    "categoryList": [
        {
            "id": "1",
            "title": "Order Food & Beverages",
            "subTitle": "Delicious food...",
            "userId": "5",
            "fId": "1",
            "sq": "2",
            "status": "YES",
            "created_date": "2022-06-23 09:40:12",
            "updated_date": "2022-06-28 13:17:00"
        }
    ]
}
</pre></details></td>
            </tr>
               <tr><td colspan="2"><h3>SERVICE UPDATE</h3></td></tr>
        <tbody>
            <tr><td>Endpoint</td><td><code><?php echo $pageName;?>/update</code></td></tr>
            <tr><td>Method</td><td><strong>POST</strong></td></tr>
            <tr><td>Request</td><td><details><summary><strong>Sample Request</strong></summary>
<pre>
{
  "outletId":5,
  "staffId":20,
   "id":283,
   "title":"SPA",
   "boxId": "6",
   "actionBy":"FOMGR",
   "aksDateTime":"NO",
   "information":"HELLO",
   "reminderTime":0,
   "escalationTime":0,
   "priority":"Medium",
   "points":1,
   "onHoldOption":"NO",
   "status":"YES"
}
</pre>
        </details></td></tr>
            <tr><td>Response</td><td><details><summary><strong>Sample response</strong></summary>
<pre>
{
    "status": "true",
    "value": "Service updated successfully",
    "id": 283,
    "updated": true,
    "updatedFields": [
        "title='SPA1'"
    ]
}
</pre></details></td>
            
               <tr><td colspan="2"><h3>SERVICE DELETE</h3></td></tr>
        <tbody>
            <tr><td>Endpoint</td><td><code><?php echo $pageName;?>/delete</code></td></tr>
            <tr><td>Method</td><td><strong>POST</strong></td></tr>
            <tr><td>Request</td><td><details><summary><strong>Sample Request</strong></summary>
<pre>

{
  "outletId":5,
  "staffId":19,
  "id":250
}

</pre>
        </details></td></tr>
            <tr><td>Response</td><td><details><summary><strong>Sample response</strong></summary>
<pre>
{
    "status": "true",
    "value": "service deleted successfully",
    "staffId": 19
}
</pre></details></td>
            </tr>
        </tbody>
        



        <?php APIInfoPageEnd(); ?>
