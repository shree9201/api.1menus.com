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
  "outletId":5
}

</pre>
        </details></td></tr>
            <tr><td>Response</td><td><details><summary><strong>Sample response</strong></summary>
<pre>
[
    {
        "status": true,
        "value": "result found",
        "countByStatus": [
            {
                "status": "CLOSE",
                "count": "13"
            },
            {
                "status": "NEW",
                "count": "3"
            },
            {
                "status": "START",
                "count": "2"
            },
            {
                "status": "ACCEPT",
                "count": "1"
            }
        ]
    }
]    
</pre></details></td>
            </tr>
        </tbody>
<?php APIInfoPageEnd(); ?>
