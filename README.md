# Angel Court Lounge - Booking Application

A modern lounge visitation booking web application built with React (Vite) frontend and PHP backend, featuring a minimalist design system with multiple Google Fonts.

## Features

- **Email-only Authentication** - No password required, auto-creates accounts
- **Booking Management** - Create, view, and cancel lounge bookings
- **Time Slot System** - 9 available time slots from 09:00 to 18:00
- **Real-time Validation** - Prevents double-booking and past date bookings
- **Responsive Design** - Mobile-first approach with elegant UI
- **Minimalist Design** - Multi-font typography system (Inter, Playfair Display, Space Mono)

## Tech Stack

### Frontend
- React 18
- Vite
- CSS3 with CSS Variables
- Google Fonts (Inter, Playfair Display, Space Mono)

### Backend
- PHP (native, no frameworks)
- JSON file-based storage
- RESTful API architecture

### Server
- XAMPP (Apache, PHP)

## Project Structure

```
angelmp/
├── backend/
│   ├── api/
│   │   ├── auth.php          # Authentication endpoint
│   │   └── bookings.php      # Booking management endpoint
│   ├── config/
│   │   └── config.php        # Configuration and helper functions
│   ├── data/
│   │   ├── users.json        # User storage
│   │   ├── bookings.json     # Booking storage
│   │   └── .htaccess         # Access protection
│   └── index.php             # API documentation page
├── frontend/
│   ├── src/
│   │   ├── App.jsx           # Main app component
│   │   ├── Login.jsx         # Login component
│   │   ├── Login.css         # Login styles
│   │   ├── Dashboard.jsx     # Dashboard component
│   │   ├── Dashboard.css     # Dashboard styles
│   │   ├── api.js            # API service layer
│   │   ├── index.css         # Design system & global styles
│   │   └── main.jsx          # Entry point
│   ├── index.html
│   ├── package.json
│   └── vite.config.js
└── README.md
```

## Setup Instructions

### Prerequisites

- XAMPP (with Apache and PHP 7.4+)
- Node.js (v18+)
- npm or yarn

### Backend Setup

1. **Install XAMPP**
   - Download and install XAMPP from [https://www.apachefriends.org/](https://www.apachefriends.org/)

2. **Copy Backend Files**
   ```bash
   # Copy the backend folder to XAMPP's htdocs directory
   # On Windows: C:\xampp\htdocs\template\TechStartup\backend
   # On Mac: /Applications/XAMPP/htdocs/template/TechStartup/backend
   # On Linux: /opt/lampp/htdocs/template/TechStartup/backend

   cp -r backend /path/to/xampp/htdocs/template/TechStartup/
   ```

3. **Set Permissions** (Linux/Mac only)
   ```bash
   chmod 755 /path/to/xampp/htdocs/template/TechStartup/backend/data
   chmod 666 /path/to/xampp/htdocs/template/TechStartup/backend/data/*.json
   ```

4. **Start XAMPP**
   - Open XAMPP Control Panel
   - Start Apache

5. **Verify Backend**
   - Open browser and navigate to: `http://localhost/template/TechStartup/backend/`
   - You should see the API documentation page

### Frontend Setup

1. **Navigate to Frontend Directory**
   ```bash
   cd frontend
   ```

2. **Install Dependencies**
   ```bash
   npm install
   ```

3. **Configure API URL** (if needed)
   - Open `frontend/src/api.js`
   - Verify/update the `API_BASE_URL` constant:
   ```javascript
   const API_BASE_URL = 'http://localhost/template/TechStartup/backend/api';
   ```

4. **Start Development Server**
   ```bash
   npm run dev
   ```

5. **Access Application**
   - Open browser and navigate to: `http://localhost:5173`

## Usage

### Authentication

1. Enter your full name, email address, and contact number
2. Click "Continue" to login
3. An account will be created automatically on first login
4. Subsequent logins will update your information

### Creating a Booking

1. Click "+ New Booking" button
2. Select a date (today or future)
3. Choose a time slot
4. Optionally add guest name and purpose
5. Click "Create Booking"

### Managing Bookings

- View all your bookings in a card grid layout
- Each card shows date, time, status, guest name (if any), and purpose
- Cancel confirmed bookings by clicking the "Cancel" button
- Cancelled bookings appear with reduced opacity

### Available Time Slots

- 09:00 - 10:00
- 10:00 - 11:00
- 11:00 - 12:00
- 12:00 - 13:00
- 13:00 - 14:00
- 14:00 - 15:00
- 15:00 - 16:00
- 16:00 - 17:00
- 17:00 - 18:00

## API Endpoints

### Authentication
- **POST** `/api/auth.php`
  - Login/register user
  - Required: email, name, contact

### Bookings
- **GET** `/api/bookings.php?email={email}`
  - Retrieve user's bookings

- **POST** `/api/bookings.php`
  - Create new booking
  - Required: userEmail, date, timeSlot
  - Optional: guestName, purpose

- **PUT** `/api/bookings.php`
  - Update booking
  - Required: id
  - Optional: date, timeSlot, guestName, purpose

- **DELETE** `/api/bookings.php?id={bookingId}`
  - Cancel booking (soft delete)

## Design System

### Color Palette
- Primary: #1a1a1a (Deep Black)
- Accent: #0066ff (Blue)
- Background: #fafafa (Light Gray)
- Success: #00c853
- Error: #ff3d00

### Typography
- **Body**: Inter (400, 500, 600)
- **Headings**: Playfair Display (600, 700)
- **Labels/Mono**: Space Mono (400, 700)

### Spacing System
- XS: 0.5rem
- SM: 1rem
- MD: 1.5rem
- LG: 2rem
- XL: 3rem
- 2XL: 4rem

## Development

### Build Frontend for Production
```bash
cd frontend
npm run build
```

### Preview Production Build
```bash
npm run preview
```

## Troubleshooting

### CORS Issues
- Ensure the backend `config.php` has correct CORS headers
- Verify frontend is running on `http://localhost:5173`

### API Connection Failed
- Check XAMPP Apache is running
- Verify backend path matches the API URL in `frontend/src/api.js`
- Check browser console for detailed error messages

### File Permission Errors (Backend)
- Ensure `backend/data/` folder has write permissions
- On Linux/Mac: `chmod 755 backend/data`
- On Linux/Mac: `chmod 666 backend/data/*.json`

## License

MIT License - feel free to use this project for personal or commercial purposes.

## Support

For issues or questions, please create an issue in the repository.
