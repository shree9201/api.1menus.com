# api.1menus.com
API service for application

## Trigger a notification

Call `api.php?action=notificationTrigger` with the existing `token` and `id` authentication headers and a JSON request body:

```json
{
  "type": "email",
  "recipients": ["guest@example.com"],
  "templateId": "001",
  "templateName": "name",
  "subject": "subject",
  "body": "Hello",
  "triggered_by": "API"
}
```

`type` must be `sms`, `email`, or `whatsapp`. `recipients` may be a string of comma-, semicolon-, or newline-separated destinations, or an array; a request is limited to 100 recipients, and WhatsApp accepts one phone number. `body` and `recipients` are required, and email also requires `subject`. `templateId` and `templateName` are optional metadata echoed in the response; the supplied `body` is sent as the message content. The endpoint records each delivery attempt in `message_history`.
