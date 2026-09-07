<?php
$pageName = basename(__FILE__, '.php');
APIInfoPageStart($pageName, "Retrieves all available device IDs against staff id.");
?>
<tr><td colspan="2"><h3>STAFF VIEW</h3></td></tr>
        <tbody>
            <tr><td>Endpoint</td><td><code><?php echo $pageName;?>/add</code></td></tr>
            <tr><td>Method</td><td><strong>POST</strong></td></tr>
            <tr><td>Request</td><td><details><summary><strong>Sample Request</strong></summary>
<pre>
{
  "outletId":5
}
</pre>
        </details></td></tr>
            <tr><td>Response</td><td><details><summary><strong>Sample response</strong></summary>
<pre>
{
    "status": "true",
    "value": "Staff list fetched successfully",
    "count": 5,
    "staffList": [
        {
            "id": "21",
            "userId": "5",
            "name": "Mrs. Priya",
            "email": "priya@menus.com",
            "mobile": "8978451245",
            "username": "priya",
            "department": "HR",
            "customised_position": "Priya",
            "online": "NO",
            "status": "YES"
        },
        {
            "id": "20",
            "userId": "5",
            "name": "Mr. Sunil",
            "email": "sunil@1menus.com",
            "mobile": "4578454545",
            "username": "sunil",
            "department": "MANAGER",
            "customised_position": "Sunil",
            "online": "NO",
            "status": "YES"
        },
        {
            "id": "19",
            "userId": "5",
            "name": "ravi",
            "email": "ravi@1menus.com",
            "mobile": "7709034176",
            "username": "Ravi",
            "department": "STAFF",
            "customised_position": "Staff Member",
            "online": "NO",
            "status": "YES"
        },
        {
            "id": "2",
            "userId": "5",
            "name": "VISHWAJEET MAHADIK",
            "email": "vishwajeet9201@gmail.com",
            "mobile": "015154676447",
            "username": "cafe99mgr",
            "department": "STAFF",
            "customised_position": "Mr.Manager",
            "online": "YES",
            "status": "YES"
        },
        {
            "id": "1",
            "userId": "5",
            "name": "Vishwajeet Mahadik",
            "email": "info@1menus.com",
            "mobile": "7709034176",
            "username": "cafe99kitchen",
            "department": "STAFF",
            "customised_position": "",
            "online": "YES",
            "status": "YES"
        }
    ]
}
</pre></details></td>
            </tr>
            <tr><td colspan="2"><h3>STAFF EDIT</h3></td></tr>
        <tbody>
            <tr><td>Endpoint</td><td><code><?php echo $pageName;?>/view/id</code></td></tr>
            <tr><td>Method</td><td><strong>POST</strong></td></tr>
            <tr><td>Request</td><td><details><summary><strong>Sample Request</strong></summary>
<pre>

{
  "outletId":5,
  "staffId":19,
  "name":"mahesh",
  "email":"mahesh@gmail.com",
  "mobile":"1234567890",
  "username":"mahesh",
  "password":"mahesh",
  "department":"STAFF",
  "type":"FO"
}

</pre>
        </details></td></tr>
            <tr><td>Response</td><td><details><summary><strong>Sample response</strong></summary>
<pre>
{
    "status": "true",
    "value": "Staff added successfully",
    "staffId": 147
}
</pre></details></td>
            </tr>
               <tr><td colspan="2"><h3>STAFF UPDATE</h3></td></tr>
        <tbody>
            <tr><td>Endpoint</td><td><code><?php echo $pageName;?>/update</code></td></tr>
            <tr><td>Method</td><td><strong>POST</strong></td></tr>
            <tr><td>Request</td><td><details><summary><strong>Sample Request</strong></summary>
<pre>

{
  "outletId":5,
  "staffId":19,
  "name":"mahesh",
  "email":"mahesh@gmail.com",
  "mobile":"1234567890",
  "username":"mahesh",  
  "department":"STAFF",
  "type":"FO"
}

</pre>
        </details></td></tr>
            <tr><td>Response</td><td><details><summary><strong>Sample response</strong></summary>
<pre>
{
    "status": "true",
    "value": "Staff updated successfully",
    "staffId": 19
}
</pre></details></td>
            </tr>
                    <tr><td colspan="2"><h3>STAFF EDIT</h3></td></tr>
        <tbody>
            <tr><td>Endpoint</td><td><code><?php echo $pageName;?>/view/id</code></td></tr>
            <tr><td>Method</td><td><strong>POST</strong></td></tr>
            <tr><td>Request</td><td><details><summary><strong>Sample Request</strong></summary>
<pre>

{
  "outletId":5,
  "staffId":19,
  "name":"mahesh",
  "email":"mahesh@gmail.com",
  "mobile":"1234567890",
  "username":"mahesh",
  "password":"mahesh",
  "department":"STAFF",
  "type":"FO"
}

</pre>
        </details></td></tr>
            <tr><td>Response</td><td><details><summary><strong>Sample response</strong></summary>
<pre>
{
    "status": "true",
    "value": "Staff added successfully",
    "staffId": 147
}
</pre></details></td>
            </tr>
               <tr><td colspan="2"><h3>STAFF DELETE</h3></td></tr>
        <tbody>
            <tr><td>Endpoint</td><td><code><?php echo $pageName;?>/delete</code></td></tr>
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
    "value": "Staff deleted successfully",
    "staffId": 19
}
</pre></details></td>
            </tr>
        </tbody>
        



        <?php APIInfoPageEnd(); ?>
