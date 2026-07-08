# Room Service Request Time Tracking API Documentation

## Overview
This document describes the new time tracking functionality added to the API for monitoring staff activity duration on room service requests.

## Database Table
```sql
CREATE TABLE `room_service_request_staff_time_track` (
  `id` int NOT NULL,
  `reqId` int DEFAULT NULL,
  `reqCode` varchar(30) DEFAULT NULL,
  `userId` int DEFAULT NULL,
  `date` date DEFAULT NULL,
  `assigned` int DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `end_time` timestamp NULL DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf32;
```

## New API Methods

### 1. Track Request Time Start
**Endpoint:** `trackRequestTimeStart`

**Purpose:** Start time tracking for a staff member on a request

**Parameters:**
- `outletId` (required): The outlet/user ID
- `requestId` (required): The room service request ID
- `requestCode` (optional): Request reference code
- `staffId` (required): Staff member ID assigned to the task
- `date` (optional): Date of tracking (default: current date)

**Request Example:**
```json
{
  "action": "trackRequestTimeStart",
  "outletId": 1,
  "requestId": 42,
  "requestCode": "RSR-001",
  "staffId": 5,
  "date": "2026-06-10"
}
```

**Response Example:**
```json
{
  "status": "true",
  "value": "Time tracking started",
  "trackId": 123,
  "startTime": "2026-06-10 14:30:00"
}
```

---

### 2. Track Request Time End
**Endpoint:** `trackRequestTimeEnd`

**Purpose:** End time tracking and calculate duration for a staff activity

**Parameters:**
- `outletId` (required): The outlet/user ID
- `requestId` (required): The room service request ID
- `staffId` (required): Staff member ID
- `trackId` (required): Tracking record ID (returned from trackRequestTimeStart)

**Request Example:**
```json
{
  "action": "trackRequestTimeEnd",
  "outletId": 1,
  "requestId": 42,
  "staffId": 5,
  "trackId": 123
}
```

**Response Example:**
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

### 3. Get Request Details with Time Metrics
**Endpoint:** `getRequestDetails`

**Purpose:** Retrieve complete request details including all time tracking data

**Parameters:**
- `outletId` (required): The outlet/user ID
- `requestId` (required): The room service request ID

**Response includes:**
- Request details
- Activity history enriched with time data
- Complete time tracking records
- Calculated time metrics (totals, averages, timeline)

**Response Example:**
```json
{
  "status": "true",
  "value": {
    "id": 42,
    "userId": 1,
    "roomNumber": "305",
    "description": "Extra towels",
    "status": "DONE",
    ...
  },
  "activity": [
    {
      "dateTime": "2026-06-10 14:20:00",
      "status": "ASSIGN",
      "assignedTo": "John Smith",
      "assignedMobile": "9876543210",
      "timeData": [
        {
          "id": 123,
          "start_time": "2026-06-10 14:30:00",
          "end_time": "2026-06-10 14:45:30"
        }
      ]
    }
  ],
  "timeTracking": [
    {
      "id": 123,
      "reqId": 42,
      "reqCode": "RSR-001",
      "userId": 1,
      "assigned": 5,
      "date": "2026-06-10",
      "start_time": "2026-06-10 14:30:00",
      "end_time": "2026-06-10 14:45:30",
      "created_date": "2026-06-10 14:30:00"
    }
  ],
  "timeMetrics": {
    "totalTimeMinutes": 15.5,
    "totalTimeFormatted": "15m 30s",
    "activitiesCount": 1,
    "staffInvolved": [5],
    "averageTimePerActivityMinutes": 15.5,
    "timeline": [
      {
        "userId": 1,
        "assignedTo": 5,
        "startTime": "2026-06-10 14:30:00",
        "endTime": "2026-06-10 14:45:30",
        "durationMinutes": 15.5,
        "durationFormatted": "15m 30s"
      }
    ]
  }
}
```

---

### 4. Get Room Request List with Time Metrics
**Endpoint:** `getRoomRequest`

**Purpose:** Retrieve all room requests with time metrics for each request

**Parameters:**
- `outletId` (required): The outlet/user ID
- `filterBy` (optional): Array of filter criteria

**Response includes:**
- List of room requests
- Each request includes `timeMetrics` object with:
  - `totalTimeMinutes`: Total time spent on request
  - `totalTimeFormatted`: Human-readable format (e.g., "2h 30m")
  - `activitiesCount`: Number of activities/staff assignments
  - `staffInvolved`: List of staff IDs involved
  - `averageTimePerActivityMinutes`: Average time per activity

**Response Example:**
```json
{
  "status": "true",
  "value": "result found",
  "count": 2,
  "roomRequest": [
    {
      "id": 42,
      "userId": 1,
      "roomNumber": "305",
      "description": "Extra towels",
      "status": "DONE",
      "timeMetrics": {
        "totalTimeMinutes": 45.75,
        "totalTimeFormatted": "45m 45s",
        "activitiesCount": 3,
        "staffInvolved": [5, 6, 7],
        "averageTimePerActivityMinutes": 15.25
      }
    },
    {
      "id": 43,
      "userId": 1,
      "roomNumber": "306",
      "description": "Bed sheets change",
      "status": "DONE",
      "timeMetrics": {
        "totalTimeMinutes": 30.5,
        "totalTimeFormatted": "30m 30s",
        "activitiesCount": 2,
        "staffInvolved": [5],
        "averageTimePerActivityMinutes": 15.25
      }
    }
  ]
}
```

---

### 5. Get Staff Time Report
**Endpoint:** `getStaffTimeReport`

**Purpose:** Generate time tracking report for staff members within a date range

**Parameters:**
- `outletId` (required): The outlet/user ID
- `staffId` (optional): Specific staff ID to filter by
- `startDate` (optional): Start date (default: 7 days ago)
- `endDate` (optional): End date (default: today)

**Request Example:**
```json
{
  "action": "getStaffTimeReport",
  "outletId": 1,
  "staffId": 5,
  "startDate": "2026-06-01",
  "endDate": "2026-06-10"
}
```

**Response Example:**
```json
{
  "status": "true",
  "value": "Staff time report",
  "period": {
    "startDate": "2026-06-01",
    "endDate": "2026-06-10"
  },
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
    },
    "6": {
      "totalSeconds": 18000,
      "taskCount": 10,
      "totalTimeMinutes": 300,
      "totalTimeFormatted": "5h",
      "avgTimePerTask": 1800,
      "avgTimePerTaskFormatted": "30m"
    }
  },
  "records": [
    {
      "id": 123,
      "reqId": 42,
      "reqCode": "RSR-001",
      "userId": 1,
      "assigned": 5,
      "date": "2026-06-10",
      "start_time": "2026-06-10 14:30:00",
      "end_time": "2026-06-10 14:45:30"
    }
  ]
}
```

---

## Helper Methods

### getRequestTimeMetrics($requestId, $outletId)
Calculates comprehensive time metrics for a specific request.

**Returns:**
```php
array(
  'totalTimeMinutes' => 15.5,
  'totalTimeFormatted' => '15m 30s',
  'activitiesCount' => 1,
  'staffInvolved' => [5],
  'averageTimePerActivityMinutes' => 15.5,
  'timeline' => array(...)
)
```

---

### secondsToTimeFormat($seconds)
Converts seconds into human-readable format (e.g., "2h 30m 15s").

**Example:**
```php
echo secondsToTimeFormat(3665); // Output: "1h 1m"
echo secondsToTimeFormat(125); // Output: "2m 5s"
```

---

### enrichActivityWithTimeTracking($activity, $timeTrackingData)
Adds time tracking information to activity records for better context.

---

## Integration Workflow

### Typical Usage Flow:

1. **When task is assigned to staff:**
   ```
   Call: trackRequestTimeStart
   Capture: trackId
   ```

2. **When task is completed:**
   ```
   Call: trackRequestTimeEnd
   Use: trackId from step 1
   Get: Duration metrics
   ```

3. **To view request details with timeline:**
   ```
   Call: getRequestDetails
   Get: Full activity history with time data
   ```

4. **For staff performance reports:**
   ```
   Call: getStaffTimeReport
   Analyze: Staff metrics, averages, patterns
   ```

---

## Time Format Examples

The API returns time in multiple formats:

| Seconds | Minutes | Formatted |
|---------|---------|-----------|
| 60 | 1 | 1m |
| 125 | 2.08 | 2m 5s |
| 3665 | 61.08 | 1h 1m |
| 7200 | 120 | 2h |
| 7325 | 122.08 | 2h 2m |

---

## Response Fields Explanation

### timeMetrics Object
- **totalTimeMinutes**: Total time spent (as decimal minutes)
- **totalTimeFormatted**: Total time in readable format
- **activitiesCount**: Number of separate time tracking entries
- **staffInvolved**: Array of staff IDs who worked on the request
- **averageTimePerActivityMinutes**: Average time per activity
- **timeline**: Array of detailed time entries with start/end times

### timeTracking Records
- **id**: Unique tracking record ID
- **reqId**: Room service request ID
- **reqCode**: Request reference code
- **userId**: Outlet/user ID
- **assigned**: Staff ID assigned to the task
- **date**: Date of activity
- **start_time**: When activity started (timestamp)
- **end_time**: When activity ended (timestamp)
- **created_date**: When record was created (timestamp)

---

## Notes

- All timestamps are in `Y-m-d H:i:s` format (24-hour)
- Time differences are automatically calculated when `end_time` is set
- The API ensures data consistency by validating request and staff IDs
- Historical data is preserved for reporting and analytics
- Multiple staff members can track time on the same request
- Date range queries support custom reporting periods

---

## Error Handling

All endpoints return consistent error responses:

```json
{
  "status": "false",
  "value": "Error description message"
}
```

Common errors:
- Missing required parameters
- Invalid request or outlet ID
- Track record not found
- Database operation failures

---

## Updates Made to Existing Methods

### getRoomRequest()
- ✅ Added time metrics calculation for each request
- ✅ Enhanced response with `timeMetrics` object
- ✅ Maintains backward compatibility

### getRequestDetails()
- ✅ Added time tracking data retrieval
- ✅ Added time metrics calculation
- ✅ Enriched activity data with time information
- ✅ Enhanced response with `timeTracking` and `timeMetrics` objects
- ✅ Maintains backward compatibility

---
