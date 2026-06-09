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
        "status": "true",
        "value": [
            {
                "deviceType": "ioandriods",
                "deviceId": "3352",
                "last_login": "2026-06-08 16:19:48"
            },
            {
                "deviceType": "andriod",
                "deviceId": "dB3JWALVQOORYj6rkiaww-:APA91bHMGEQDF_9JLZOHxXQKLkrhEi3uV_ZALDFHLsFVMQv3cI5BpQgxA1wI87vKaCEg3otvwXkpifQ8jklbhBqQwjtuP0RNIcEtBlK5UPy_R0cCWVi1Tvc",
                "last_login": "2026-06-03 17:09:03"
            },
            {
                "deviceType": "android",
                "deviceId": "dNjPuf1yT9GcdlgnNcOKKo:APA91bFSAdQfJ7ToKfL1vrI4jtgNBp6rIJ9J7nZTMErVn-CI36c6MydKdIno1k2ANwY4dBUyNqxlFj2_2fzHGoE5TXzmJp1xVF6TscrR7fATdLu9LR0VysQ",
                "last_login": "2026-05-31 13:26:10"
            },
            {
                "deviceType": "android",
                "deviceId": "fS0ec6PFRcWPgZThe_QX8S:APA91bHoMrDigDUQeb1d0hxxqsw1w7aM_LOYh_lsxkfos14IBJX8T3dLDyfS1HASDdA8kyMs4WYdPAVUZ4K1aDmASM5ciqbxp31CufrulHXQgGnNmh42JYI",
                "last_login": "2026-05-31 08:47:53"
            },
            {
                "deviceType": "android",
                "deviceId": "eB2v-sFmTIOPR516RHh-Wv:APA91bEQnZwWPYDBcCzgL-Gel-0MvGQzaxr4H_Y5oHHuBcGkI5E6MkA7aOrfnDRnTP7vo0EaX4mF9KsLpMGltpIUIwhnjhlCvi56t5R8ZHclgFcx91qeDcA",
                "last_login": "2026-05-31 07:52:58"
            },
            {
                "deviceType": "android",
                "deviceId": "dB3JWALVQOORYj6rkiaww-:APA91bHMGEQDF_9JLZOHxXQKLkrhEi3uV_ZALDFHLsFVMQv3cI5BpQgxA1wI87vKaCEg3otvwXkpifQ8jklbhBqQwjtuP0RNIcEtBlK5UPy_R0cCWVi1Tvc",
                "last_login": "2026-05-18 14:47:53"
            },
            {
                "deviceType": "android",
                "deviceId": "dtOTEgHtS0-y8sQQOFTWIR:APA91bEPvOUUFTNJfliXH5tysTlBzwK7VregZNAN7IyGUugQgXqvu01ecp1RjwKVnnjwSbZBwyU49Dqj8PAftb9Kk8BhiF5z325uoOjhgDk7jOCqfaDrx-0",
                "last_login": "2026-05-13 16:52:51"
            },
            {
                "deviceType": "android",
                "deviceId": "e7Wegr8aRDK2FExXI6D3EU:APA91bHyIAsxKDpnRcudu9vhCewWFBWqXQomosDJDn67qXsHzdQszUxOC9ZviBs0ejdfpMfqxCrY4Q28C1SLcoKbYgZC-8hpYavTeIsIm_duKI1muptqky4",
                "last_login": "2026-05-13 16:35:22"
            }
        ]
    }
]
</pre></details></td>
            </tr>
        </tbody>
<?php APIInfoPageEnd(); ?>
