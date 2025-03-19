# Chat Application API with Laravel 📱

  [![Laravel Version](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
  [![PHP Version](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net)
  [![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
  [![Contributors](https://img.shields.io/github/contributors/zakigit1/Chat-App-Laravel-API)](https://github.com/zakigit1/ZAKA-eCommerce/graphs/contributors)

A robust and scalable **RESTful API** for building real-time chat applications powered by **Laravel**. This project provides the backend infrastructure for messaging systems, enabling seamless communication between users with features like real-time messaging and notifications, user authentication, and message history.


---

## Features

### Core Functionalities
- **Real-Time Messaging**: Leverages WebSockets (via Pusher) for instant message delivery.
- **User Authentication**: Secure user registration and login using Laravel Sanctum.
- **Message History**: Stores and retrieves chat history using a relational database (MySQL).
- **RESTful Endpoints**: Well-defined API endpoints for sending, receiving, and managing messages.
- **Scalable Architecture**: Designed to handle high concurrency and large-scale deployments.

### Unique Selling Points
- **Modular Design**: Easily extendable for custom features like group chats, file sharing, or chatbots.
- **Real-Time Notifications**: Notify users of new messages or events in real time using OneSignal.
- **Cross-Platform Compatibility**: Works seamlessly with web, mobile, and desktop clients.

### Technical Highlights
- Built with **Laravel 10.x** for robust backend development.
- Uses **Pusher** for real-time communication.
- Implements **OneSignal** for push notifications.
- Implements **RESTful API** standards for easy integration.
- Supports **MySQL** for reliable data storage.

<!--
---

## Visual Demonstration

 Add screenshots or GIFs here to showcase your project 
![Chat Interface](screenshots/chat-interface.png)  
*Example of the chat interface.*

![API Documentation](screenshots/api-docs.png)  
*API documentation generated using Swagger.*
-->

---

## Installation Instructions

### Prerequisites
- PHP 8.1 or higher
- Composer (for dependency management)
- MySQL 5.7 or higher
- Node.js and NPM (for front-end assets, if applicable)
- Pusher account (for real-time messaging)
- OneSignal account (for push notifications)

### Step-by-Step Setup
1. **Clone the Repository**:
   ```bash
   git clone https://github.com/zakigit1/Chat-App-Laravel-API.git
   cd Chat-App-Laravel-API
   ```

2. **Install Dependencies**:
   ```bash
   composer install
   npm install
   ```

3. **Configure Environment**:
   - Copy `.env.example` to `.env`:
     
     ```bash
     cp .env.example .env
     ```
   - Update `.env` with your database, Pusher, and OneSignal credentials:
     
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=chat_app
     DB_USERNAME=root
     DB_PASSWORD=

     BROADCAST_DRIVER=pusher
     
     PUSHER_APP_ID=your-pusher-app-id
     PUSHER_APP_KEY=your-pusher-app-key
     PUSHER_APP_SECRET=your-pusher-app-secret
     PUSHER_APP_CLUSTER=mt1
     ```

4. **Generate Application Key**:
   ```bash
   php artisan key:generate
   ```

5. **Run Migrations**:
   ```bash
   php artisan migrate
   ```

6. **Start the Development Server**:
   ```bash
   php artisan serve
   ```

---

## Usage Guide

### Getting Started
1. **Register a User**:
   - Use the `/api/register-user` endpoint to create a new user.
     
   ```bash
   curl -X POST http://localhost:8000/api/register-user \
        -H "Content-Type: application/json" \
        -d '{"email": "john@example.com", "password": "password", "password_confirmation": "password"}'
   ```

2. **Login**:
   - Use the `/api/login-user` endpoint to authenticate.
     
   ```bash
   curl -X POST http://localhost:8000/api/login-user \
        -H "Content-Type: application/json" \
        -d '{"email": "john@example.com", "password": "password"}'
   ```

3. **Create a Chat**:
   - Use the `/api/chat` endpoint to create a new chat with another user.
     
   ```bash
   curl -X POST http://localhost:8000/api/chat \
        -H "Authorization: Bearer [token]" \
        -H "Content-Type: application/json" \
        -d '{"user_id": 2, "is_private": true}'
   ```

4. **Send a Message**:
   - Use the `/api/chat_message` endpoint to send a message.
     
   ```bash
   curl -X POST http://localhost:8000/api/chat_message \
        -H "Authorization: Bearer [token]" \
        -H "Content-Type: application/json" \
        -d '{"chat_id": 2, "message": "Hello!"}'
   ```

### Example Workflow
1. Register and log in as a user.
2. Create a chat with another user.
3. Send and receive messages in real-time.
4. Integrate the API with a frontend application for a complete chat experience.

---

## API Documentation

This project includes comprehensive API documentation that details all available endpoints, request/response formats, and authentication methods.

### Documentation Access
- **Full Documentation**: See [API_DOCUMENTATION.md](API_DOCUMENTATION.md) for complete API reference.

### Key API Features
- **Authentication**: Secure token-based authentication using Laravel Sanctum.
- **Real-time Communication**: WebSocket integration via Pusher for instant messaging.
- **Push Notifications**: OneSignal integration for mobile and web notifications.
- **RESTful Design**: Consistent and intuitive API structure.

### Base URL & Versioning
- **Base URL:** `http://localhost/api` (Changes based on deployment environment)
- **Versioning:** All endpoints are prefixed with `/api`

### Essential Endpoints

#### User Management
- **POST `/api/register-user`**: Register a new user and receive authentication token.
  ```json
  // Request
  {
    "email": "user@example.com",
    "password": "password",
    "password_confirmation": "password"
  }
  
  // Response
  {
    "data": {
      "userData": {
        "username": "user",
        "email": "user@example.com"
      },
      "token": "1|XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"
    },
    "status": "sucess",
    "message": "User has been register successfully."
  }
  ```

- **POST `/api/login-user`**: Authenticate a user and receive token.
- **POST `/api/login-user-WithToken`**: Authenticate using an existing token.
- **POST `/api/logout-user`**: Invalidate user's authentication token.

#### Chat Management
- **GET `/api/chat`**: List all chats for the authenticated user.
- **POST `/api/chat`**: Create a new chat.
- **GET `/api/chat/{chat_id}`**: Get details of a specific chat.

#### Messaging
- **GET `/api/chat_message`**: Retrieve messages from a specific chat.
- **POST `/api/chat_message`**: Send a message to a specific chat.

### Authentication
- All endpoints (except `/api/register-user` and `/api/login-user`) require a **Bearer Token** for authentication.
- Include the token in the `Authorization` header of your API requests:
  ```
  Authorization: Bearer 1|XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
  ```

### Status Codes
- `200 OK`: Request successful
- `401 Unauthorized`: Authentication required or failed
- `422 Unprocessable Entity`: Validation errors

### For More Information
Refer to the [API_DOCUMENTATION.md](API_DOCUMENTATION.md) file for detailed endpoint descriptions, request/response formats, and usage examples.


---

## Contributing Guidelines

### Code Style
- Follow [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standards.
- Write clear and concise commit messages.

### Development Setup
1. Fork the repository.
2. Create a new branch for your feature or bug fix.
3. Submit a pull request with a detailed description of your changes.



---

## Troubleshooting

### Common Issues
1. **Real-Time Messaging Not Working**:
   - Ensure Pusher credentials are correctly configured in `.env`.
   - Verify the `BROADCAST_DRIVER` is set to `pusher`.
   - Check browser console for WebSocket connection errors.

2. **Database Connection Errors**:
   - Double-check your `.env` database credentials.
   - Ensure MySQL is running.

3. **Push Notifications Not Received**:
   - Verify OneSignal configuration.
   - Check if the user has granted notification permissions.

### Debug Tips
- Use `php artisan tinker` to interact with your application.
- Check Laravel logs in `storage/logs/laravel.log`.
- Enable debug mode in `.env` by setting `APP_DEBUG=true`.

---

## License

This project is licensed under the **MIT License**. See the [LICENSE](LICENSE) file for details.

---

## Contact and Support

- **Maintainer**: [Mohammed Ilyes Zakarian Bousbaa](https://github.com/zakigit1)
- **Email**: [mohammedilyeszakaria.bousbaa@gmail.com](mailto:mohammedilyeszakaria.bousbaa@gmail.com)
- **Support Options**: Open an issue on GitHub or contact the maintainer directly.

---

Thank you for using **Chat Application API**! We look forward to your contributions and feedback 💙.
