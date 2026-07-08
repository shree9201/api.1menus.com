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
  "staffId":2
}
</pre>
        </details></td></tr>
            <tr><td>Response</td><td><details><summary><strong>Sample response</strong></summary>
<pre>
{
    "status": "true",
    "value": [
        {
            "id": "146",
            "title": null,
            "userId": "5",
            "tableIds": null,
            "name": "Mrs. Priya",
            "email": "priya@menus.com",
            "mobile": "8978451245",
            "username": "priya",
            "password": "priya",
            "type": "HR",
            "department": "HR",
            "customised_position": "Priya",
            "online": "NO",
            "status": "YES",
            "created_date": "2026-06-02 18:13:48",
            "updated_date": "2026-06-02 18:16:40"
        },
        {
            "id": "145",
            "title": null,
            "userId": "5",
            "tableIds": null,
            "name": "Mr. Sunil",
            "email": "sunil@1menus.com",
            "mobile": "4578454545",
            "username": "sunil",
            "password": "sunil",
            "type": "HKMGR",
            "department": "MANAGER",
            "customised_position": "Sunil",
            "online": "NO",
            "status": "YES",
            "created_date": "2026-06-02 18:12:52",
            "updated_date": "2026-06-02 18:12:52"
        },
        {
            "id": "144",
            "title": null,
            "userId": "5",
            "tableIds": null,
            "name": "Mr.Ravi",
            "email": "ravi@1menus.com",
            "mobile": "134567890",
            "username": "ravi",
            "password": "ravi",
            "type": "FO",
            "department": "STAFF",
            "customised_position": "ravi",
            "online": "NO",
            "status": "YES",
            "created_date": "2026-06-02 18:11:48",
            "updated_date": "2026-06-02 18:11:48"
        },
        {
            "id": "2",
            "title": null,
            "userId": "5",
            "tableIds": "1,2,BR-1,GR-1,RM-102,RM-303,RM-L_202,TB-12",
            "name": "vishu",
            "email": "vishwajeet9201@gmail.com",
            "mobile": "015154676447",
            "username": "cafe99mgr",
            "password": "cafe99mgr",
            "type": "FOMGR",
            "department": "STAFF",
            "customised_position": "Mr.Manager",
            "online": "YES",
            "status": "YES",
            "created_date": "2022-03-14 09:13:08",
            "updated_date": "2026-06-02 18:09:31"
        },
        {
            "id": "1",
            "title": null,
            "userId": "5",
            "tableIds": "1,2,BR-1,GR-1,RM-102,RM-303,RM-L_202",
            "name": "vishu",
            "email": "info@1menus.com",
            "mobile": "7709034176",
            "username": "cafe99kitchen",
            "password": "cafe99kitchen",
            "type": "HKM",
            "department": "STAFF",
            "customised_position": "",
            "online": "YES",
            "status": "YES",
            "created_date": "2022-03-14 09:12:29",
            "updated_date": "2026-06-02 18:10:33"
        }
    ]
}
</pre></details></td>
            </tr>
        </tbody>
<?php APIInfoPageEnd(); ?>
