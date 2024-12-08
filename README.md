# Fuel Delivery Service

## Overview

This project is a web-based Fuel Delivery Service application that allows customers to order petrol or diesel for their vehicles and have it delivered to their location. The application includes a user-friendly frontend for customers to place orders and an admin panel for managing deliveries.

## Features

### Customer Interface
- User registration with username and mobile number
- Geolocation support for easy location input
- Fuel quantity selection using interactive sliders
- Multiple payment method options
- Real-time order status tracking

### Admin Panel
- View all customer orders
- Update order status (Accepted, Out for Delivery, Delivered)
- Sort orders by timestamp

## Technologies Used

- Frontend: HTML, CSS (Tailwind CSS), JavaScript
- Backend: PHP
- Database: JSON file storage
- Styling: Custom Neumorphic design

## Setup Instructions

1. Clone the repository
2. Set up a PHP server (e.g., Apache, Nginx)
3. Place the project files in the server's web root directory
4. Ensure write permissions for the `orders.json` file
5. Access the customer interface via `index.html`
6. Access the admin panel via `admin_panel.php`

## File Structure

- `index.html`: Main customer interface
- `app.js`: JavaScript for customer interface functionality
- `location.js`: JavaScript for geolocation features
- `submit_order.php`: PHP script to handle order submissions
- `admin_panel.php`: Admin interface for managing orders
- `update_status.php`: PHP script to update order statuses
- `orders.json`: JSON file for storing order data

## Security Considerations

- Implement proper authentication for the admin panel
- Use HTTPS for all communications
- Sanitize and validate all user inputs
- Implement rate limiting to prevent abuse

## Future Enhancements

- Integrate with a proper database system
- Add user authentication for customers
- Implement real-time notifications
- Develop mobile applications for both customers and delivery personnel

## Contributing

Contributions are welcome! Please feel free to submit a Pull Request.
