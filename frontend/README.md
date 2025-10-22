# Angel Court Lounge - Frontend

React + Vite frontend for the Angel Court Lounge booking application.

## Quick Start

### Development

```bash
# Install dependencies
npm install

# Start development server
npm run dev
```

Visit `http://localhost:5173` to view the app.

### Production Build

```bash
# Build for production
npm run build

# Preview production build
npm run preview
```

## Environment Variables

The app uses environment variables to configure the backend API URL.

### Setup

1. Copy the example environment file:
   ```bash
   cp .env.example .env
   ```

2. Edit `.env` and set your backend URL:
   ```env
   VITE_API_BASE_URL=http://localhost/template/TechStartup/backend/api
   ```

### Environment Files

- `.env.example` - Template with all available variables
- `.env` - Local development (not committed to git)
- `.env.production` - Production settings (not committed to git)

### Available Variables

| Variable | Description | Default |
|----------|-------------|---------|
| `VITE_API_BASE_URL` | Backend API URL | `http://localhost/template/TechStartup/backend/api` |

**Note:** All Vite environment variables must be prefixed with `VITE_` to be exposed to the app.

## Deployment

### Vercel

See [VERCEL_DEPLOYMENT.md](../VERCEL_DEPLOYMENT.md) for detailed instructions.

Quick deploy:
1. Push to GitHub
2. Import to Vercel from [https://vercel.com/new](https://vercel.com/new)
3. Set root directory to `frontend`
4. Add environment variable `VITE_API_BASE_URL`
5. Deploy

### Other Platforms

The app can be deployed to any static hosting service:

- Netlify
- Cloudflare Pages
- GitHub Pages
- AWS S3 + CloudFront

Just build with `npm run build` and deploy the `dist` folder.

## Project Structure

```
frontend/
├── public/              # Static assets
├── src/
│   ├── App.jsx         # Main app component
│   ├── Login.jsx       # Login page
│   ├── Login.css       # Login styles
│   ├── Dashboard.jsx   # Dashboard page
│   ├── Dashboard.css   # Dashboard styles
│   ├── api.js          # API service layer
│   ├── index.css       # Global styles & design system
│   └── main.jsx        # Entry point
├── .env.example        # Environment variables template
├── vercel.json         # Vercel configuration
└── vite.config.js      # Vite configuration
```

## Tech Stack

- **React 18** - UI library
- **Vite** - Build tool & dev server
- **CSS3** - Styling with CSS Variables
- **Google Fonts** - Inter, Playfair Display, Space Mono

## Design System

The app uses a minimalist design system defined in `src/index.css`:

### Colors
- Primary: #1a1a1a
- Accent: #0066ff
- Background: #fafafa
- Success: #00c853
- Error: #ff3d00

### Typography
- Body: Inter
- Headings: Playfair Display
- Labels: Space Mono

### Spacing
CSS variables: `--space-xs` through `--space-2xl`

## Development

### Code Style

The project uses ESLint for code quality. Configuration is in `eslint.config.js`.

### Hot Module Replacement (HMR)

Vite provides instant HMR. Changes appear immediately without full page reload.

### API Integration

API calls are handled in `src/api.js`:

```javascript
import { authAPI, bookingsAPI, storage } from './api';

// Login
const response = await authAPI.login({ email, name, contact });

// Get bookings
const bookings = await bookingsAPI.getBookings({ email });

// Create booking
await bookingsAPI.createBooking({ userEmail, date, timeSlot });
```

## Troubleshooting

### Issue: API calls failing

**Solution:**
- Check backend is running
- Verify `VITE_API_BASE_URL` is set correctly
- Check browser console for CORS errors

### Issue: Environment variables not working

**Solution:**
- Ensure variable names start with `VITE_`
- Restart dev server after changing `.env`
- Variables are only loaded at build time

### Issue: Build errors

**Solution:**
```bash
# Clear node_modules and reinstall
rm -rf node_modules package-lock.json
npm install
npm run build
```

## License

MIT License - See [main README](../README.md) for details.
