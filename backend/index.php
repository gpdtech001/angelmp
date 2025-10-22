<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Angel Court Lounge API Documentation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background: #f5f5f5;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem;
        }

        header {
            background: #1a1a1a;
            color: #fff;
            padding: 2rem 0;
            margin-bottom: 2rem;
        }

        h1 {
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            color: #ccc;
            font-size: 1.1rem;
        }

        .endpoint {
            background: #fff;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .endpoint h2 {
            color: #1a1a1a;
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
        }

        .method {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 4px;
            font-weight: bold;
            font-size: 0.875rem;
            margin-right: 0.5rem;
        }

        .method.post { background: #4CAF50; color: #fff; }
        .method.get { background: #2196F3; color: #fff; }
        .method.put { background: #FF9800; color: #fff; }
        .method.delete { background: #F44336; color: #fff; }

        .url {
            font-family: 'Courier New', monospace;
            background: #f5f5f5;
            padding: 0.5rem;
            border-radius: 4px;
            margin: 0.5rem 0;
            display: inline-block;
        }

        .section {
            margin: 1rem 0;
        }

        .section h3 {
            color: #555;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        code {
            background: #f5f5f5;
            padding: 0.2rem 0.4rem;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
        }

        pre {
            background: #2d2d2d;
            color: #f8f8f2;
            padding: 1rem;
            border-radius: 4px;
            overflow-x: auto;
            margin: 0.5rem 0;
        }

        pre code {
            background: none;
            color: inherit;
            padding: 0;
        }

        ul {
            margin-left: 1.5rem;
            margin-top: 0.5rem;
        }

        li {
            margin-bottom: 0.25rem;
        }

        .note {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 1rem;
            margin: 1rem 0;
            border-radius: 4px;
        }

        .time-slots {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 0.5rem;
            margin-top: 0.5rem;
        }

        .time-slot {
            background: #e3f2fd;
            padding: 0.5rem;
            border-radius: 4px;
            text-align: center;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Angel Court Lounge API</h1>
            <p class="subtitle">RESTful API for lounge visitation booking system</p>
        </div>
    </header>

    <div class="container">
        <!-- Authentication Endpoint -->
        <div class="endpoint">
            <h2><span class="method post">POST</span> Authentication</h2>
            <div class="url">/api/auth.php</div>

            <div class="section">
                <h3>Description</h3>
                <p>Email-only authentication. Auto-creates account on first login and updates user info on subsequent logins.</p>
            </div>

            <div class="section">
                <h3>Request Body</h3>
                <pre><code>{
  "email": "user@example.com",
  "name": "John Doe",
  "contact": "+1234567890"
}</code></pre>
            </div>

            <div class="section">
                <h3>Response</h3>
                <pre><code>{
  "success": true,
  "message": "Login successful",
  "data": {
    "id": "unique_user_id",
    "email": "user@example.com",
    "name": "John Doe",
    "contact": "+1234567890",
    "createdAt": "2025-01-15 10:30:00",
    "lastLogin": "2025-01-15 10:30:00"
  }
}</code></pre>
            </div>
        </div>

        <!-- Get Bookings Endpoint -->
        <div class="endpoint">
            <h2><span class="method get">GET</span> Get Bookings</h2>
            <div class="url">/api/bookings.php</div>

            <div class="section">
                <h3>Description</h3>
                <p>Retrieve all bookings or filter by email/date.</p>
            </div>

            <div class="section">
                <h3>Query Parameters</h3>
                <ul>
                    <li><code>email</code> (optional) - Filter bookings by user email</li>
                    <li><code>date</code> (optional) - Filter bookings by date (YYYY-MM-DD)</li>
                </ul>
            </div>

            <div class="section">
                <h3>Example</h3>
                <div class="url">/api/bookings.php?email=user@example.com</div>
            </div>

            <div class="section">
                <h3>Response</h3>
                <pre><code>{
  "success": true,
  "message": "Bookings retrieved successfully",
  "data": [
    {
      "id": "unique_booking_id",
      "userEmail": "user@example.com",
      "date": "2025-01-20",
      "timeSlot": "10:00 - 11:00",
      "guestName": "Jane Smith",
      "purpose": "Business meeting",
      "status": "confirmed",
      "createdAt": "2025-01-15 10:35:00"
    }
  ]
}</code></pre>
            </div>
        </div>

        <!-- Create Booking Endpoint -->
        <div class="endpoint">
            <h2><span class="method post">POST</span> Create Booking</h2>
            <div class="url">/api/bookings.php</div>

            <div class="section">
                <h3>Description</h3>
                <p>Create a new booking. Validates time slot availability and prevents double-booking.</p>
            </div>

            <div class="section">
                <h3>Request Body</h3>
                <pre><code>{
  "userEmail": "user@example.com",
  "date": "2025-01-20",
  "timeSlot": "10:00 - 11:00",
  "guestName": "Jane Smith",
  "purpose": "Business meeting"
}</code></pre>
                <p><strong>Required fields:</strong> userEmail, date, timeSlot</p>
                <p><strong>Optional fields:</strong> guestName, purpose</p>
            </div>

            <div class="section">
                <h3>Available Time Slots</h3>
                <div class="time-slots">
                    <div class="time-slot">09:00 - 10:00</div>
                    <div class="time-slot">10:00 - 11:00</div>
                    <div class="time-slot">11:00 - 12:00</div>
                    <div class="time-slot">12:00 - 13:00</div>
                    <div class="time-slot">13:00 - 14:00</div>
                    <div class="time-slot">14:00 - 15:00</div>
                    <div class="time-slot">15:00 - 16:00</div>
                    <div class="time-slot">16:00 - 17:00</div>
                    <div class="time-slot">17:00 - 18:00</div>
                </div>
            </div>
        </div>

        <!-- Update Booking Endpoint -->
        <div class="endpoint">
            <h2><span class="method put">PUT</span> Update Booking</h2>
            <div class="url">/api/bookings.php</div>

            <div class="section">
                <h3>Description</h3>
                <p>Update an existing booking's details.</p>
            </div>

            <div class="section">
                <h3>Request Body</h3>
                <pre><code>{
  "id": "unique_booking_id",
  "date": "2025-01-21",
  "timeSlot": "11:00 - 12:00",
  "guestName": "Updated Name",
  "purpose": "Updated purpose"
}</code></pre>
                <p><strong>Required:</strong> id</p>
                <p><strong>Optional:</strong> date, timeSlot, guestName, purpose</p>
            </div>
        </div>

        <!-- Cancel Booking Endpoint -->
        <div class="endpoint">
            <h2><span class="method delete">DELETE</span> Cancel Booking</h2>
            <div class="url">/api/bookings.php?id=unique_booking_id</div>

            <div class="section">
                <h3>Description</h3>
                <p>Cancel a booking (soft delete - changes status to 'cancelled').</p>
            </div>

            <div class="section">
                <h3>Query Parameters</h3>
                <ul>
                    <li><code>id</code> (required) - The booking ID to cancel</li>
                </ul>
            </div>
        </div>

        <!-- CORS Configuration -->
        <div class="note">
            <h3>CORS Configuration</h3>
            <p>The API is configured to allow requests from <code>http://localhost:5173</code> (React frontend)</p>
            <p><strong>Allowed Methods:</strong> GET, POST, PUT, DELETE, OPTIONS</p>
            <p><strong>Content-Type:</strong> application/json</p>
        </div>
    </div>
</body>
</html>
