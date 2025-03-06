# Chat Application API with Laravel 📱

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
- **Real-Time Notifications**: Notify users of new messages or events in real time.
- **Cross-Platform Compatibility**: Works seamlessly with web, mobile, and desktop clients.

### Technical Highlights
- Built with **Laravel 10.x** for robust backend development.
- Uses **Pusher** for real-time communication.
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
   - Update `.env` with your database and Pusher credentials:
     
     ```env
     DB_CONNECTION=mysql
     DB_HOST=127.0.0.1
     DB_PORT=3306
     DB_DATABASE=chat_app
     DB_USERNAME=root
     DB_PASSWORD=

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
<!--
7. **Run WebSocket Server** (for real-time messaging):
   ```bash
   php artisan websockets:serve
   ```
-->
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

3. **Login**:
   - Use the `/api/login-user` endpoint to authenticate.

     
   ```bash
   curl -X POST http://localhost:8000/api/login-user \
        -H "Content-Type: application/json" \
        -d '{"email": "john@example.com", "password": "password"}'
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
2. Use the `/api/chat_message` endpoint to send and retrieve messages.
3. Integrate the API with a frontend application for a complete chat experience.

---

## Some API Endpoints Information

### Essential Endpoints
- **POST `/api/register-user`**: Register a new user.
- **POST `/api/login-user`**: Authenticate a user.
- **GET `/api/chat_message`**: Retrieve messages.
- **POST `/api/chat_message`**: Send a message.

### Authentication
- All endpoints (except `/api/register-user` and `/api/login-user`) require a **Bearer Token** for authentication.


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
   - Verify the WebSocket server is running.

2. **Database Connection Errors**:
   - Double-check your `.env` database credentials.
   - Ensure MySQL is running.

### Debug Tips
- Use `php artisan tinker` to interact with your application.
- Check Laravel logs in `storage/logs/laravel.log`.

---

## License

This project is licensed under the **MIT License**. See the [LICENSE](LICENSE) file for details.

---

## Contact and Support

- **Maintainer**: [Mohammed Ilyes Zakarian Bousbaa](https://github.com/zakigit1)
- **Email**: [mohammedilyeszakaria.bousbaa@gmail.com](mailto:mohammedilyeszakaria.bousbaa@gmail.com)
- **Support Options**: Open an issue on GitHub or contact the maintainer directly.

---

Thank you for using **Chat-App-Laravel-API**! We look forward to your contributions and feedback.

<!--
---

### Notes:
1. Replace placeholders (e.g., `[Your Project Name]`, `[token]`) with actual values.
2. Add screenshots or GIFs to the **Visual Demonstration** section to make the README more engaging.
3. Update the **API Documentation** section with detailed endpoint descriptions and examples.
4. Customize the **Contact and Support** section with your preferred communication channels.

This README is designed to be professional, comprehensive, and accessible to developers of all skill levels. Let me know if you need further adjustments!
-->
