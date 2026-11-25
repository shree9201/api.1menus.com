<?php
$pageName = basename(__FILE__, '.php');
APIInfoPageStart($pageName, "Chats with the room-service AI using the active hotel service catalogue.");
?>
<tbody>
    <tr>
        <td>Endpoint</td>
        <td><code>api.php?action=<?php echo $pageName; ?></code> or <code>/aiRoomServiceChat</code> (POST)</td>
    </tr>
    <tr>
        <td>Authentication</td>
        <td>Send the existing hotel JWT in the <code>Token</code> header and the matching hotel account ID in the <code>Id</code> header.</td>
    </tr>
    <tr>
        <td>Server configuration</td>
        <td>Set <code>OPENAI_API_KEY</code> in the server environment. <code>OPENAI_MODEL</code> is optional; the default is <code>gpt-4.1-mini</code>.</td>
    </tr>
    <tr>
        <td>Request</td>
        <td>
            <details>
                <summary><strong>Sample JSON request</strong></summary>
                <pre>
{
    "message": "Do you have extra towels?",
    "history": [
        {"role": "user", "content": "I need something for my room."},
        {"role": "assistant", "content": "What would you like me to arrange?"}
    ]
}
</pre>
                <p><code>message</code> is required (maximum 2000 characters). <code>history</code> is optional and may contain up to 12 user/assistant messages.</p>
            </details>
        </td>
    </tr>
    <tr>
        <td>Response</td>
        <td>
            <details>
                <summary><strong>Sample response</strong></summary>
                <pre>
{
    "status": true,
    "value": "AI response generated successfully.",
    "reply": "Yes, extra towels are available. Would you like me to help with anything else?",
    "toolResults": [],
    "requestSubmissionEnabled": false
}
</pre>
                <p>This action can search the live catalogue and read service configuration. It does not create requests; guest authentication and booking context must be integrated before request submission is enabled.</p>
            </details>
        </td>
    </tr>
</tbody>
<?php APIInfoPageEnd(); ?>
