<?php
$pageName = basename(__FILE__, '.php');
APIInfoPageStart($pageName, "Retrieves the master metadata required by the client application.");
?>
        <tbody>
            <tr><td>Endpoint</td><td><code><?php echo $pageName;?></code></td></tr>
            <tr><td>Method</td><td><strong>POST</strong></td></tr>
            <tr><td>Request</td><td><details><summary><strong>Sample Request</strong></summary><pre>{}</pre></details></td></tr>
            <tr><td>Response</td><td><details><summary><strong>Sample response</strong></summary>
<pre>
{
	"status": "true",
	"value": "Master data retrieved successfully",
	"staffTypes": [
		{
			"key": "FOMGR",
			"value": "Front Office Manager"
		},
		{
			"key": "FOSU",
			"value": "Front Office Supervisor"
		},
		{
			"key": "FO",
			"value": "Front Office Executive"
		},
		{
			"key": "HKMGR",
			"value": "House Keeping Manager"
		},
		{
			"key": "HKSU",
			"value": "House Keeping Supervisor"
		},
		{
			"key": "HK",
			"value": "House Keeping Executive"
		},
		{
			"key": "MTNS",
			"value": "Maintenance"
		},
		{
			"key": "SPA",
			"value": "Spa"
		},
		{
			"key": "WAITER-STAFF",
			"value": "Waiter/Staff"
		},
		{
			"key": "KITCHEN",
			"value": "Kitchen"
		}
	],
	"departments": [
		{
			"key": "STAFF",
			"value": "Staff"
		},
		{
			"key": "MANAGER",
			"value": "Manager"
		},
		{
			"key": "HR",
			"value": "HR"
		}
	],
	"statusList": {
		"NEW": "NEW",
		"ACCEPT": "ACCEPT",
		"ASSIGN": "ASSIGN",
		"START": "START",
		"HOLD": "HOLD",
		"END": "END",
		"DONE": "DONE",
		"CLOSE": "CLOSE",
		"REJECT": "REJECT",
		"REOPEN": "REOPEN"
	}
}
</pre></details></td>
            </tr>
        </tbody>
<?php APIInfoPageEnd(); ?>
