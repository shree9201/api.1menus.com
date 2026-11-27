<?php
$pageName = basename(__FILE__, '.php');
APIInfoPageStart($pageName, "Submits food order items and creates a room-service request containing the order details.");
?>
        <tbody>
            <tr><td>Endpoint</td><td><code><?php echo $pageName;?></code></td></tr>
            <tr><td>Method</td><td><strong>POST (application/json)</strong></td></tr>
            <tr><td>Request</td><td><details><summary><strong>Sample Request</strong></summary>
<pre>
{
  "outletId": 5,
  "username": "hotel-account",
  "tableNo": "101",
  "serviceId": 113,
  "items": [
    { "id": 42, "quantity": 2, "notes": "No onions" },
    { "id": 55, "quantity": 1 }
  ]
}
</pre>
            <p><code>serviceId</code> must identify an active room-service service for the outlet. <code>tableNo</code> must match an active room configured for that outlet; both plain and <code>RM-</code>-prefixed room titles are accepted.</p>
        </details></td></tr>
            <tr><td>Response</td><td><details><summary><strong>Response fields</strong></summary>
<pre>
{
    "status": true,
    "value": "Food order submitted successfully.",
    "tableNo": "RM-101",
    "data": [123, 124],
    "roomRequestId": 302,
    "reqCode": "RSRQ-00000302",
    "activityLogged": true,
    "notificationDetails": {}
}
</pre>
            <p>The room-service request includes the item names, quantities, and any item notes in its <code>note</code> field, so the order appears in room-service requests.</p>
        </details></td></tr>
        </tbody>
<?php APIInfoPageEnd(); ?>
