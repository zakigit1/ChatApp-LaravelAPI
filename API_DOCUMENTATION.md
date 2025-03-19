```markdown
# ChatApp-LaravelAPI Documentation

## Introduction

This document provides comprehensive information for developers looking to integrate with the ChatApp-LaravelAPI. This API allows users to register, authenticate, manage chats, and send/retrieve chat messages. Key features include:

*   User registration and authentication.
*   Private and public chat management.
*   Real-time messaging using Pusher.
*   Push notifications via OneSignal.
*   RESTful API design.

### Intended Use Cases

*   Building chat functionality into web or mobile applications.
*   Creating a platform for real-time communication between users.

## Authentication

This API utilizes Laravel Sanctum for authentication.  This provides a lightweight authentication system for Single Page Applications (SPAs) and simple APIs.
### Authentication Flow
  * Register a user using /api/register-user endpoint and get a token.
  * Pass the token in the `Authorization` header of every API request.
### Obtaining an API Token
  * **Endpoint**: `/api/register-user`
  * **Method**: `POST`
  * **Request Body**:
    ```json
    {
      "email": "user@example.com",
      "password": "password",
      "password_confirmation": "password"
    }
    ```
  * **Response**:
    ```json
    {
        "data": {
            "userData": {
                "username": "user",
                "email": "user@example.com",
            },
            "token": "1|XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"
        },
        "status": "sucess",
        "message": "User has been register successfully."
    }
    ```
### Usage
Include the token in the `Authorization` header of your API requests.

```
Authorization: Bearer 1|XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
```

## Base URL & Versioning

*   **Base URL:** `http://localhost/api` (This will need to change according to deployment environment)
*   **Versioning:**  The API uses URI versioning. All endpoints are prefixed with `/api`. There is no specified versioning such as `/api/v1/`.

## Endpoints & Methods

### 1. User Authentication

#### 1.1 Register User
*   **Method:** `POST`
*   **Endpoint URL:** `/register-user`
*   **Request Body (JSON):**
    ```json
    {
        "email": "newuser@example.com",
        "password": "securepassword",
        "password_confirmation": "securepassword"
    }
    ```
*   **Response Format (Success):**
    ```json
    {
        "data": {
            "userData": {
                "username": "newuser",
                "email": "newuser@example.com"
            },
            "token": "1|XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"
        },
        "status": "sucess",
        "message": "User has been register successfully."
    }
    ```
*   **Response Format (Error):**
    ```json
    {
        "data": null,
        "success": false,
        "message": "The email field is required. (and other validation errors)"
    }
    ```
*   **Status Codes:**
    *   `200 OK`: User registered successfully.
    *   `422 Unprocessable Entity`: Validation errors.

#### 1.2 Login User

*   **Method:** `POST`
*   **Endpoint URL:** `/login-user`
*   **Request Body (JSON):**
    ```json
    {
        "email": "newuser@example.com",
        "password": "securepassword"
    }
    ```
*   **Response Format (Success):**
    ```json
    {
        "data": {
            "userData": {
                "id": 1,
                "username": "newuser",
                "email": "newuser@example.com",
                "email_verified_at": null
            },
            "token": "1|XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX"
        },
        "status": "success",
        "message": "Login successfully!"
    }
    ```
*   **Response Format (Error):**
    ```json
    {
        "data": null,
        "success": false,
        "message": "Invalid Credential"
    }
    ```
*   **Status Codes:**
    *   `200 OK`: User logged in successfully.
    *   `422 Unprocessable Entity`: Invalid credentials.

#### 1.3 Login User With Token

*   **Method:** `POST`
*   **Endpoint URL:** `/login-user-WithToken`
*   **Request Headers:**
Authorization: Bearer 1|XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
*   **Response Format (Success):**
    ```json
    {
        "data": {
           "id": 1,
            "username": "newuser",
            "email": "newuser@example.com",
            "email_verified_at": null
        },
        "status": "success",
        "message": "login successfully!"
    }
    ```
*   **Status Codes:**
    *   `200 OK`: User logged in successfully.
    *   `401`: Unauthenticated.

#### 1.4 Logout User
*   **Method:** `POST`
*   **Endpoint URL:** `/logout-user`
*   **Request Headers:**
Authorization: Bearer 1|XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
*   **Response Format (Success):**
    ```json
    {
        "data": null,
        "status": "*",
        "message": "Logout successfully!"
    }
    ```
*   **Status Codes:**
    *   `200 OK`: User logged out successfully.
    *   `401`: Unauthenticated.

### 2. Chat Management

#### 2.1 List Chats

*   **Method:** `GET`
*   **Endpoint URL:** `/chat`
*   **Request Headers:**
Authorization: Bearer 1|XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
*   **Query Parameters:**
    *   `is_private` (optional, boolean): Filter by chat privacy (true or false). If not sent, default to private chat
*   **Response Format (Success):**
    ```json
    {
      "data": [
        {
          "id": 1,
          "name": null,
          "is_private": 1,
          "created_by": 1,
          "updated_at": "2024-03-18T17:19:00.000000Z",
          "last_message": {
            "user_id": 1,
            "chat_id": 1,
            "message": "hello world",
            "user": {
              "id": 1,
              "username": "user"
            }
          },
          "participants": [
            {
              "user_id": 1,
              "user": {
                "id": 1,
                "username": "user"
              }
            },
            {
              "user_id": 2,
              "user": {
                "id": 2,
                "username": "zaki"
              }
            }
          ]
        }
      ],
      "success": true,
      "message": "okay"
    }
    ```
*   **Status Codes:**
    *   `200 OK`: Successfully retrieved chat list.
    *   `401`: Unauthenticated.

#### 2.2 Create Chat

*   **Method:** `POST`
*   **Endpoint URL:** `/chat`
*   **Request Headers:**
Authorization: Bearer 1|XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
*   **Request Body (JSON):**
    ```json
    {
        "user_id": 2, // ID of the other user to chat with
        "name": "Optional chat name",
        "is_private": true
    }
    ```
*   **Response Format (Success):**
    ```json
{
    "data": {
        "name": null,
        "is_private": 1,
        "created_by": 1,
        "updated_at": "2024-03-19T00:15:30.000000Z",
        "participants": [
            {
                "user_id": 1,
                "user": {
                    "id": 1,
                    "username": "user"
                }
            },
            {
                "user_id": 2,
                "user": {
                    "id": 2,
                    "username": "zaki"
                }
            }
        ],
        "id": 2,
        "created_at": "2024-03-19T00:15:30.000000Z"
    },
    "success": true,
    "message": "okay"
}

    ```
*   **Response Format (Error):**
    ```json
    {
      "data": null,
      "success": false,
      "message": "The user id field is required."
    }
    ```

    *   **Status Codes:**
    *   `200 OK`: Chat created successfully.
    *   `400 Bad Request` : "You can not create a chat with yourself" , Validation Error
    *   `401`: Unauthenticated.
#### 2.3 Get Chat Details

*   **Method:** `GET`
*   **Endpoint URL:** `/chat/{chat}` (where `{chat}` is the chat ID)
*   **Request Headers:**
Authorization: Bearer 1|XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX

*   **Response Format (Success):**
    ```json
{
    "data": {
        "id": 2,
        "is_private": 1,
        "created_by": 1,
        "updated_at": "2024-03-19T00:15:30.000000Z",
        "last_message": null,
        "participants": [
            {
                "user_id": 1,
                "user": {
                    "id": 1,
                    "username": "user"
                }
            },
            {
                "user_id": 2,
                "user": {
                    "id": 2,
                    "username": "zaki"
                }
            }
        ]
    },
    "success": true,
    "message": "okay"
}
    ```
*   **Status Codes:**
    *   `200 OK`: Successfully retrieved chat details.
    *   `401`: Unauthenticated.
    *   `404 Not Found`: Chat not found.

### 3. Chat Message Management

#### 3.1 List Chat Messages

*   **Method:** `GET`
*   **Endpoint URL:** `/chat_message`
*   **Request Headers:**
Authorization: Bearer 1|XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
*   **Query Parameters:**
    *   `chat_id` (required): ID of the chat to retrieve messages from.
    *   `page` (required) : page number to return

    *   `page_size` (optional): Number of messages per page (defaults to 15).
*   **Response Format (Success):**
    ```json
    {
    "data": [
        {
            "message": "hello world",
            "created_at": "2024-03-18T17:19:00.000000Z",
            "updated_at": "2024-03-18T17:19:00.000000Z",
            "user": {
                "id": 1,
                "username": "user"
            }
        },
        {
            "message": "hi",
            "created_at": "2024-03-18T17:20:23.000000Z",
            "updated_at": "2024-03-18T17:20:23.000000Z",
            "user": {
                "id": 2,
                "username": "zaki"
            }
        }
    ],
    "success": true,
    "message": "okay"
}
    ```

*   **Status Codes:**
    *   `200 OK`: Successfully retrieved messages.
    *   `401`: Unauthenticated.
    *   `422`: unprocessable entity (you need to specify chat_id and page )

#### 3.2 Create Chat Message

*   **Method:** `POST`
*   **Endpoint URL:** `/chat_message`
*   **Request Headers:**
Authorization: Bearer 1|XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
*   **Request Body (JSON):**
    ```json
    {
        "chat_id": 1,
        "message": "Hello from user auth!"
    }
    ```
*   **Response Format (Success):**
    ```json
   {
    "data": {
        "message": "Hello from user auth!",
        "created_at": "2024-03-19T00:24:55.000000Z",
        "updated_at": "2024-03-19T00:24:55.000000Z",
        "user": {
            "id": 1,
            "username": "user"
        }
    },
    "success": true,
    "message": "Message has been sent successfully."
}
    ```
*   **Status Codes:**
    *   `200 OK`: Message created successfully.
    *   `401`: Unauthenticated.

## Rate Limiting & Throttling

The API is rate-limited to 60 requests per minute per user, enforced by the `throttle:api` middleware. This helps prevent abuse and ensures fair usage.

## Error Handling

The API returns JSON responses for errors. Common error codes and messages include:

| Status Code | Message                                | Description                                                                                                  |
| :---------- | :------------------------------------- | :----------------------------------------------------------------------------------------------------------- |
| 400         | "Validation error message"             | Request parameters failed validation. Check the response for details.                                     |
| 401         | "Unauthenticated"                      | Authentication is required and has failed or has not yet been provided.                                      |
| 403         | "Forbidden"                            | The user does not have permission to access the resource.                                                   |
| 404         | "Not Found"                            | The requested resource was not found.                                                                         |
| 422         | "The ... field is required"            | Validation failed ,the column name field is missing in you Request                                       |

## Examples & Use Cases

### Example: Creating a new chat with a user
```bash
curl -X POST \
  http://localhost/api/chat \
  -H 'Authorization: Bearer 1|XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX' \
  -H 'Content-Type: application/json' \
  -d '{
    "user_id": 2,
    "name": "Personal Chat",
    "is_private": true
}'
```
### Example: Retrieving messages from a chat
```bash
curl -X GET \
  'http://localhost/api/chat_message?chat_id=1&page=1' \
  -H 'Authorization: Bearer 1|XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX'
```

## SDKs & Libraries
There are no currently available SDKs but you can use Http client like Axios to interact with this API.

## Changelog

| Version | Date       | Changes                                                |
| :------ | :--------- | :----------------------------------------------------- |
| 1.0     | 2024-03-18 | Initial release.                                    |

## Contact & Support

For questions or support, please contact:

*   [Link to Support Forum](https://example.com/support)
*   [Email Support](support@example.com)
```

