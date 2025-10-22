// API Service Layer

// Use environment variable for production, fallback to localhost for development
const API_BASE_URL = import.meta.env.VITE_API_BASE_URL || 'http://localhost/template/TechStartup/backend/api';

// Helper function for API calls
async function apiCall(endpoint, options = {}) {
  try {
    const response = await fetch(`${API_BASE_URL}/${endpoint}`, {
      ...options,
      headers: {
        'Content-Type': 'application/json',
        ...options.headers,
      },
      credentials: 'include',
    });

    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message || 'Something went wrong');
    }

    return data;
  } catch (error) {
    throw error;
  }
}

// Authentication API
export const authAPI = {
  login: async (userData) => {
    return apiCall('auth.php', {
      method: 'POST',
      body: JSON.stringify(userData),
    });
  },
};

// Bookings API
export const bookingsAPI = {
  // Get all bookings or filter by email/date
  getBookings: async (filters = {}) => {
    const queryParams = new URLSearchParams();

    if (filters.email) {
      queryParams.append('email', filters.email);
    }

    if (filters.date) {
      queryParams.append('date', filters.date);
    }

    const queryString = queryParams.toString();
    const endpoint = queryString ? `bookings.php?${queryString}` : 'bookings.php';

    return apiCall(endpoint, {
      method: 'GET',
    });
  },

  // Create new booking
  createBooking: async (bookingData) => {
    return apiCall('bookings.php', {
      method: 'POST',
      body: JSON.stringify(bookingData),
    });
  },

  // Update booking
  updateBooking: async (bookingData) => {
    return apiCall('bookings.php', {
      method: 'PUT',
      body: JSON.stringify(bookingData),
    });
  },

  // Cancel booking (soft delete)
  cancelBooking: async (bookingId) => {
    return apiCall(`bookings.php?id=${bookingId}`, {
      method: 'DELETE',
    });
  },
};

// Local Storage Helpers
export const storage = {
  setUser: (userData) => {
    localStorage.setItem('user', JSON.stringify(userData));
  },

  getUser: () => {
    const user = localStorage.getItem('user');
    return user ? JSON.parse(user) : null;
  },

  removeUser: () => {
    localStorage.removeItem('user');
  },

  isAuthenticated: () => {
    return !!storage.getUser();
  },
};
