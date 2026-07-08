# Time Tracking API - Quick Reference Guide

## Quick Start Examples

### 1. Start Tracking Activity
```bash
curl -X POST "http://api.1menus.com/pages/getRoomRequest.php" \
  -H "Content-Type: application/json" \
  -d '{
    "action": "trackRequestTimeStart",
    "outletId": 1,
    "requestId": 42,
    "requestCode": "RSR-001",
    "staffId": 5
  }'
```

**Success Response:**
```json
{
  "status": "true",
  "value": "Time tracking started",
  "trackId": 123,
  "startTime": "2026-06-10 14:30:00"
}
```

---

### 2. End Tracking Activity
```bash
curl -X POST "http://api.1menus.com/pages/getRoomRequest.php" \
  -H "Content-Type: application/json" \
  -d '{
    "action": "trackRequestTimeEnd",
    "outletId": 1,
    "requestId": 42,
    "staffId": 5,
    "trackId": 123
  }'
```

**Success Response:**
```json
{
  "status": "true",
  "value": "Time tracking ended",
  "trackId": 123,
  "startTime": "2026-06-10 14:30:00",
  "endTime": "2026-06-10 14:45:30",
  "durationSeconds": 930,
  "durationMinutes": 15.5,
  "durationFormatted": "15m 30s"
}
```

---

### 3. Get Request Details with Time Data
```bash
curl -X GET "http://api.1menus.com/pages/getRequestDetails.php?action=getRequestDetails&outletId=1&requestId=42"
```

**Response Includes:**
```json
{
  "status": "true",
  "value": {...request details...},
  "activity": [...enriched with time data...],
  "timeTracking": [...all time records...],
  "timeMetrics": {
    "totalTimeMinutes": 45.75,
    "totalTimeFormatted": "45m 45s",
    "activitiesCount": 3,
    "staffInvolved": [5, 6, 7],
    "averageTimePerActivityMinutes": 15.25,
    "timeline": [...]
  }
}
```

---

### 4. Get All Requests with Time Metrics
```bash
curl -X GET "http://api.1menus.com/pages/getRoomRequest.php?action=getRoomRequest&outletId=1"
```

**Response Includes:**
```json
{
  "status": "true",
  "count": 5,
  "roomRequest": [
    {
      "id": 42,
      "roomNumber": "305",
      "description": "Extra towels",
      "timeMetrics": {
        "totalTimeMinutes": 15.5,
        "totalTimeFormatted": "15m 30s",
        "activitiesCount": 1,
        "staffInvolved": [5]
      }
    }
  ]
}
```

---

### 5. Get Staff Time Report
```bash
curl -X GET "http://api.1menus.com/pages/getStaffTimeReport.php?action=getStaffTimeReport&outletId=1&staffId=5&startDate=2026-06-01&endDate=2026-06-10"
```

**Response:**
```json
{
  "status": "true",
  "value": "Staff time report",
  "period": {"startDate": "2026-06-01", "endDate": "2026-06-10"},
  "totalTimeMinutes": 485.25,
  "totalTimeFormatted": "8h 5m",
  "recordCount": 28,
  "staffMetrics": {
    "5": {
      "totalSeconds": 29115,
      "taskCount": 15,
      "totalTimeMinutes": 485.25,
      "totalTimeFormatted": "8h 5m",
      "avgTimePerTask": 1941,
      "avgTimePerTaskFormatted": "32m 21s"
    }
  }
}
```

---

## Mobile App Integration Example (React/Flutter)

### Start Timer
```javascript
async function startTaskTimer(outletId, requestId, staffId) {
  const response = await fetch('/api.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      action: 'trackRequestTimeStart',
      outletId: outletId,
      requestId: requestId,
      staffId: staffId
    })
  });
  
  const data = await response.json();
  if (data.status === 'true') {
    localStorage.setItem('trackId_' + requestId, data.trackId);
    return data.trackId;
  }
  return null;
}
```

### Stop Timer
```javascript
async function stopTaskTimer(outletId, requestId, staffId, trackId) {
  const response = await fetch('/api.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      action: 'trackRequestTimeEnd',
      outletId: outletId,
      requestId: requestId,
      staffId: staffId,
      trackId: trackId
    })
  });
  
  const data = await response.json();
  if (data.status === 'true') {
    console.log('Duration:', data.durationFormatted);
    localStorage.removeItem('trackId_' + requestId);
  }
  return data;
}
```

### Display Request with Time Info
```javascript
async function loadRequestWithTime(outletId, requestId) {
  const response = await fetch(`/pages/getRequestDetails.php?action=getRequestDetails&outletId=${outletId}&requestId=${requestId}`);
  const data = await response.json();
  
  if (data.status === 'true') {
    console.log('Total Time:', data.timeMetrics.totalTimeFormatted);
    console.log('Staff Involved:', data.timeMetrics.staffInvolved);
    console.log('Avg Time per Activity:', data.timeMetrics.averageTimePerActivityMinutes, 'minutes');
  }
}
```

---

## Dashboard Display Examples

### Request Card with Time Summary
```
┌─────────────────────────────────┐
│ Room 305 - Extra Towels         │
│ Status: DONE                    │
├─────────────────────────────────┤
│ ⏱️  Total Time: 15m 30s          │
│ 👥 Staff: John Smith            │
│ 📊 Avg/Task: 15m 30s            │
└─────────────────────────────────┘
```

### Staff Performance Widget
```
┌──────────────────────────────────┐
│ Staff Performance (Jun 1-10)     │
├──────────────────────────────────┤
│ John Smith (ID: 5)               │
│ • Tasks: 15                      │
│ • Total: 8h 5m                   │
│ • Avg: 32m 21s per task          │
└──────────────────────────────────┘
```

---

## Common Scenarios

### Scenario 1: Multi-staff on Single Request
```
Request ID: 42 (Room 305, Extra Towels)

1. John (Staff 5) starts task       → trackId 100, 14:30:00
2. John completes → 15:00:00        → Duration: 30 minutes
3. Manager (Staff 7) reviews        → trackId 101, 15:05:00
4. Manager completes → 15:10:00     → Duration: 5 minutes

Total Request Time: 35 minutes
Staff Involved: [5, 7]
Avg per Activity: 17.5 minutes
```

### Scenario 2: Daily Staff Report
```
Staff: John (ID: 5)
Date Range: June 1-10

Activities: 28 tasks
Total Time: 8 hours 5 minutes
Average per Task: 32 minutes 21 seconds

Top Activity Times:
- Highest: 1 hour 15 minutes
- Lowest: 5 minutes
```

### Scenario 3: Request Status Tracking
```
Room 305 Request:
- NEW (14:00:00)
- ASSIGN to John (14:15:00) → Start Timer (trackId: 100)
- START (14:30:00)
- HOLD (14:45:00) → Stop Timer → Duration: 15m 30s
- START again (15:00:00) → Start Timer (trackId: 101)
- DONE (15:20:00) → Stop Timer → Duration: 20m

Total Time: 35m 30s
Multiple segments tracked separately
```

---

## Best Practices

### ✅ DO
- Call trackRequestTimeStart when assigning task to staff
- Call trackRequestTimeEnd immediately when task completes
- Use trackId returned from start to match end call
- Include requestCode for better tracking
- Query reports periodically to monitor performance

### ❌ DON'T
- Forget to pass trackId when ending timer
- Use same trackId for multiple end calls
- Manually calculate durations (let API handle it)
- Leave timers running without ending them
- Mix up staff IDs between requests

---

## Time Format Conversion Reference

| Seconds | Minutes | Formatted | Use Case |
|---------|---------|-----------|----------|
| 30 | 0.5 | 30s | Quick tasks |
| 300 | 5 | 5m | Small jobs |
| 1800 | 30 | 30m | Medium tasks |
| 3600 | 60 | 1h | Large projects |
| 5400 | 90 | 1h 30m | Complex work |
| 7200 | 120 | 2h | Full shift tasks |

---

## Error Codes & Responses

| Error | Cause | Solution |
|-------|-------|----------|
| "Missing required parameters" | Missing outletId/requestId/staffId | Check all params provided |
| "Track record not found" | Invalid trackId | Use trackId from start call |
| "No request found for this request id" | Invalid requestId | Verify request exists |
| Failed database operation | Connection/syntax error | Check server logs |

---

## Testing Commands

### Test with cURL
```bash
# Test Start Tracking
curl -X POST http://localhost/api.php \
  -H "Content-Type: application/json" \
  -d '{"action":"trackRequestTimeStart","outletId":1,"requestId":42,"staffId":5}' | jq

# Test End Tracking
curl -X POST http://localhost/api.php \
  -H "Content-Type: application/json" \
  -d '{"action":"trackRequestTimeEnd","outletId":1,"requestId":42,"staffId":5,"trackId":123}' | jq

# Test Get Details
curl "http://localhost/pages/getRequestDetails.php?action=getRequestDetails&outletId=1&requestId=42" | jq

# Test Get All Requests
curl "http://localhost/pages/getRoomRequest.php?action=getRoomRequest&outletId=1" | jq

# Test Staff Report
curl "http://localhost/pages/getStaffTimeReport.php?action=getStaffTimeReport&outletId=1&staffId=5" | jq
```

---

## Support & Debugging

### Enable API Logging
Add to api_class.php for debugging:
```php
error_log("Time tracking: " . json_encode($data));
```

### Verify Database
```sql
-- Check time tracking records
SELECT * FROM room_service_request_staff_time_track 
WHERE date = CURDATE() 
ORDER BY start_time DESC;

-- Calculate totals
SELECT 
  assigned,
  COUNT(*) as count,
  SUM(TIMESTAMPDIFF(MINUTE, start_time, end_time)) as total_minutes
FROM room_service_request_staff_time_track
WHERE date = CURDATE()
GROUP BY assigned;
```

---
