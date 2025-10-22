# Quick Start: Vercel Deployment Guide

This guide will help you deploy the Angel Court Lounge frontend to Vercel in minutes.

## Prerequisites

✅ Vercel account ([Sign up free](https://vercel.com))
✅ Backend hosted online (or use ngrok for testing)
✅ Code pushed to GitHub

## 🚀 Quick Deploy Steps

### 1. Get Your Backend URL

**For Testing (Ngrok):**
```bash
ngrok http 80
# Copy the https URL: https://abc123.ngrok.io
# Your API URL: https://abc123.ngrok.io/template/TechStartup/backend/api
```

**For Production:**
Upload your `backend/` folder to any PHP hosting and note the URL.

### 2. Deploy to Vercel

#### Option A: Via Dashboard (Recommended)

1. Go to [https://vercel.com/new](https://vercel.com/new)
2. Click **"Import Git Repository"**
3. Select your GitHub repository
4. Configure:
   - **Root Directory**: `frontend`
   - **Framework**: Vite
   - **Build Command**: `npm run build`
   - **Output Directory**: `dist`
5. Add Environment Variable:
   - **Name**: `VITE_API_BASE_URL`
   - **Value**: `https://your-backend-url/api` (your actual backend URL)
6. Click **"Deploy"**

#### Option B: Via CLI

```bash
cd frontend
vercel login
vercel --prod

# Set environment variable
vercel env add VITE_API_BASE_URL
# Paste your backend URL when prompted
```

### 3. Update Backend CORS

Edit `backend/config/config.php`:

```php
function setCorsHeaders() {
    $allowedOrigins = [
        'http://localhost:5173',
        'https://your-project.vercel.app'  // Add your Vercel URL
    ];

    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

    if (in_array($origin, $allowedOrigins)) {
        header("Access-Control-Allow-Origin: $origin");
    }

    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");
    header("Content-Type: application/json; charset=UTF-8");

    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }
}
```

### 4. Test Your Deployment

1. Visit your Vercel URL: `https://your-project.vercel.app`
2. Try logging in
3. Create a test booking
4. Verify everything works

## 🔧 Configuration Files

The following files are already configured for Vercel deployment:

- ✅ `frontend/vercel.json` - Vercel configuration
- ✅ `frontend/.vercelignore` - Files to exclude
- ✅ `frontend/.env.example` - Environment variable template
- ✅ `frontend/src/api.js` - Configured for environment variables

## 📝 Environment Variables

| Variable | Required | Description | Example |
|----------|----------|-------------|---------|
| `VITE_API_BASE_URL` | Yes | Backend API URL | `https://your-domain.com/backend/api` |

## 🔄 Continuous Deployment

Once deployed, Vercel automatically redeploys when you push to GitHub:

```bash
git add .
git commit -m "Update frontend"
git push

# Vercel automatically deploys the changes!
```

## 🌐 Custom Domain (Optional)

1. In Vercel project settings → **Domains**
2. Add your custom domain
3. Update DNS records as shown
4. Update backend CORS to include new domain

## ⚠️ Common Issues

### Issue: "Failed to fetch" error

**Solution:** Check that:
- Backend is running and accessible
- `VITE_API_BASE_URL` is set correctly in Vercel
- CORS is configured with your Vercel domain

### Issue: CORS error

**Solution:**
- Add your Vercel URL to `$allowedOrigins` in `backend/config/config.php`
- Redeploy backend

### Issue: Environment variable not working

**Solution:**
- Verify variable name starts with `VITE_`
- Redeploy after adding environment variables
- Check Vercel project settings → Environment Variables

## 📚 Resources

- [Vercel Documentation](https://vercel.com/docs)
- [Vite Environment Variables](https://vitejs.dev/guide/env-and-mode.html)
- [Main README.md](./README.md)

## 🎉 You're Done!

Your Angel Court Lounge app should now be live on Vercel!

**Live URL**: `https://your-project.vercel.app`

Share the link and start taking bookings! 🎊
