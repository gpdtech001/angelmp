import { useState, useEffect } from 'react';
import { bookingsAPI, storage } from './api';
import './Dashboard.css';

const TIME_SLOTS = [
  '09:00 - 10:00',
  '10:00 - 11:00',
  '11:00 - 12:00',
  '12:00 - 13:00',
  '13:00 - 14:00',
  '14:00 - 15:00',
  '15:00 - 16:00',
  '16:00 - 17:00',
  '17:00 - 18:00',
];

function Dashboard({ user, onLogout }) {
  const [bookings, setBookings] = useState([]);
  const [showBookingForm, setShowBookingForm] = useState(false);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [successMessage, setSuccessMessage] = useState('');

  const [formData, setFormData] = useState({
    date: '',
    timeSlot: '',
    guestName: '',
    purpose: '',
  });

  useEffect(() => {
    fetchBookings();
  }, []);

  const fetchBookings = async () => {
    try {
      const response = await bookingsAPI.getBookings({ email: user.email });
      if (response.success) {
        setBookings(response.data || []);
      }
    } catch (err) {
      console.error('Error fetching bookings:', err);
    }
  };

  const handleLogout = () => {
    storage.removeUser();
    onLogout();
  };

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({
      ...prev,
      [name]: value,
    }));
  };

  const resetForm = () => {
    setFormData({
      date: '',
      timeSlot: '',
      guestName: '',
      purpose: '',
    });
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setSuccessMessage('');

    if (!formData.date || !formData.timeSlot) {
      setError('Please select date and time slot');
      return;
    }

    setLoading(true);

    try {
      const bookingData = {
        userEmail: user.email,
        date: formData.date,
        timeSlot: formData.timeSlot,
        guestName: formData.guestName,
        purpose: formData.purpose,
      };

      const response = await bookingsAPI.createBooking(bookingData);

      if (response.success) {
        setSuccessMessage('Booking created successfully!');
        resetForm();
        setShowBookingForm(false);
        fetchBookings();

        // Auto-dismiss success message
        setTimeout(() => setSuccessMessage(''), 3000);
      } else {
        setError(response.message || 'Failed to create booking');
      }
    } catch (err) {
      setError(err.message || 'An error occurred. Please try again.');
    } finally {
      setLoading(false);
    }
  };

  const handleCancelBooking = async (bookingId) => {
    if (!confirm('Are you sure you want to cancel this booking?')) {
      return;
    }

    try {
      const response = await bookingsAPI.cancelBooking(bookingId);

      if (response.success) {
        setSuccessMessage('Booking cancelled successfully!');
        fetchBookings();

        // Auto-dismiss success message
        setTimeout(() => setSuccessMessage(''), 3000);
      } else {
        setError(response.message || 'Failed to cancel booking');
      }
    } catch (err) {
      setError(err.message || 'An error occurred. Please try again.');
    }
  };

  const formatDate = (dateString) => {
    const date = new Date(dateString + 'T00:00:00');
    return date.toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'long',
      day: 'numeric',
    });
  };

  const getTodayDate = () => {
    const today = new Date();
    return today.toISOString().split('T')[0];
  };

  return (
    <div className="dashboard">
      {/* Header */}
      <header className="dashboard-header">
        <div className="header-content">
          <div className="header-left">
            <h1>Angel Court Lounge</h1>
            <span className="user-name">{user.name}</span>
          </div>
          <button onClick={handleLogout} className="logout-button">
            Logout
          </button>
        </div>
      </header>

      {/* Main Content */}
      <main className="dashboard-main">
        {/* Messages */}
        {successMessage && (
          <div className="success-message">{successMessage}</div>
        )}

        {error && <div className="error-message">{error}</div>}

        {/* Actions Bar */}
        <div className="actions-bar">
          <h2>Your Bookings</h2>
          <button
            onClick={() => setShowBookingForm(!showBookingForm)}
            className="new-booking-button"
          >
            {showBookingForm ? 'Cancel' : '+ New Booking'}
          </button>
        </div>

        {/* Booking Form */}
        {showBookingForm && (
          <div className="booking-form-container">
            <form onSubmit={handleSubmit} className="booking-form">
              <div className="form-row">
                <div className="form-group">
                  <label htmlFor="date">Date</label>
                  <input
                    type="date"
                    id="date"
                    name="date"
                    value={formData.date}
                    onChange={handleChange}
                    min={getTodayDate()}
                    disabled={loading}
                    required
                  />
                </div>

                <div className="form-group">
                  <label htmlFor="timeSlot">Time Slot</label>
                  <select
                    id="timeSlot"
                    name="timeSlot"
                    value={formData.timeSlot}
                    onChange={handleChange}
                    disabled={loading}
                    required
                  >
                    <option value="">Select a time slot</option>
                    {TIME_SLOTS.map((slot) => (
                      <option key={slot} value={slot}>
                        {slot}
                      </option>
                    ))}
                  </select>
                </div>
              </div>

              <div className="form-group">
                <label htmlFor="guestName">Guest Name (Optional)</label>
                <input
                  type="text"
                  id="guestName"
                  name="guestName"
                  value={formData.guestName}
                  onChange={handleChange}
                  placeholder="Name of guest (if any)"
                  disabled={loading}
                />
              </div>

              <div className="form-group">
                <label htmlFor="purpose">Purpose (Optional)</label>
                <textarea
                  id="purpose"
                  name="purpose"
                  value={formData.purpose}
                  onChange={handleChange}
                  placeholder="Purpose of visit"
                  rows="3"
                  disabled={loading}
                />
              </div>

              <button
                type="submit"
                className="submit-button"
                disabled={loading}
              >
                {loading ? 'Creating...' : 'Create Booking'}
              </button>
            </form>
          </div>
        )}

        {/* Bookings Grid */}
        <div className="bookings-grid">
          {bookings.length === 0 ? (
            <div className="empty-state">
              <p>No bookings yet</p>
              <p className="empty-state-subtitle">
                Click "New Booking" to reserve your visit
              </p>
            </div>
          ) : (
            bookings.map((booking) => (
              <div
                key={booking.id}
                className={`booking-card ${
                  booking.status === 'cancelled' ? 'cancelled' : ''
                }`}
              >
                <div className="booking-header">
                  <div className="booking-date">{formatDate(booking.date)}</div>
                  <span className={`status-badge ${booking.status}`}>
                    {booking.status}
                  </span>
                </div>

                <div className="booking-time">{booking.timeSlot}</div>

                {booking.guestName && (
                  <div className="booking-detail">
                    <span className="detail-label">Guest:</span>
                    <span className="detail-value">{booking.guestName}</span>
                  </div>
                )}

                {booking.purpose && (
                  <div className="booking-detail">
                    <span className="detail-label">Purpose:</span>
                    <span className="detail-value">{booking.purpose}</span>
                  </div>
                )}

                <div className="booking-footer">
                  <span className="created-date">
                    Created: {new Date(booking.createdAt).toLocaleDateString()}
                  </span>

                  {booking.status === 'confirmed' && (
                    <button
                      onClick={() => handleCancelBooking(booking.id)}
                      className="cancel-button"
                    >
                      Cancel
                    </button>
                  )}
                </div>
              </div>
            ))
          )}
        </div>
      </main>
    </div>
  );
}

export default Dashboard;
