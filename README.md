# PHP Starter - Admin Panel & Users API

A modern, secure PHP admin panel with user management and REST API.

## 🚀 Features

- **Admin Panel** - Modern, mobile-first UI with dark/light themes
- **User Management** - Full CRUD operations for users
- **REST API** - Complete RESTful API with authentication
- **Security** - Password hashing, SQL injection protection, CSRF protection
- **Responsive Design** - Works on desktop and mobile devices

## 📋 Requirements

- PHP 7.4+ 
- MySQL 5.7+
- Composer

## ⚙️ Installation

1. Clone the repository
```bash
git clone https://github.com/yasuproject/php-starter.git
cd php-starter
```

2. Install dependencies
```bash
composer install
```

3. Configure environment
```bash
cp .env.example .env
# Edit .env with your database credentials
```

4. Run migrations
```bash
php database/migrations/create_admin_table.php
php database/migrations/create_users_table.php
```

## 🔐 Admin Panel

### Default Admin Credentials

- **URL:** `https://your-domain.com/login`
- **Username:** `admin`
- **Password:** `admin`

### Admin Panel Features

- **Dashboard** - Overview and statistics
- **Users** - Manage application users
- **Permissions** - Role-based permissions
- **Profile** - Admin profile management

## 🌐 REST API

### Base URL

```
https://your-domain.com/api
```

### Authentication

All API endpoints (except login) require authentication using an API key.

**Method 1: Query Parameter**
```
GET /api/users?api_key=YOUR_API_KEY
```

**Method 2: Authorization Header**
```
Authorization: Bearer YOUR_API_KEY
```

### API Key

```
YOUR_API_KEY_HERE
```

**Note:** Get your API key from the `.env` file (API_KEY variable)

---

## 📡 API Endpoints

### 1. List Users

Get all users with optional filtering and pagination.

**Endpoint:** `GET /api/users`

**Authentication:** Required

**Query Parameters:**

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `page` | integer | 1 | Page number |
| `limit` | integer | 20 | Items per page (max 100) |
| `role` | string | - | Filter by role (cashier, manager, sales, inventory, admin) |
| `status` | string | - | Filter by status (active, inactive) |
| `search` | string | - | Search in name, username, email |

**Example Request:**

```bash
curl "https://your-domain.com/api/users?api_key=YOUR_API_KEY&page=1&limit=10&role=cashier&status=active"
```

**Example Response:**

```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "full_name": "John Smith",
      "username": "john.smith",
      "email": "john@example.com",
      "phone_number": "+1234567890",
      "role": "cashier",
      "is_active": 1,
      "created_at": "2026-02-17 21:44:38",
      "last_login": null
    }
  ],
  "meta": {
    "total": 1,
    "page": 1,
    "limit": 10,
    "pages": 1
  }
}
```

---

### 2. Get Single User

Get details of a specific user.

**Endpoint:** `GET /api/users/show`

**Authentication:** Required

**Query Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | User ID |

**Example Request:**

```bash
curl "https://your-domain.com/api/users/show?id=1&api_key=YOUR_API_KEY"
```

**Example Response:**

```json
{
  "success": true,
  "data": {
    "id": 1,
    "full_name": "John Smith",
    "username": "john.smith",
    "email": "john@example.com",
    "phone_number": "+1234567890",
    "role": "cashier",
    "is_active": 1,
    "created_at": "2026-02-17 21:44:38",
    "last_login": null
  }
}
```

---

### 3. Create User

Create a new user.

**Endpoint:** `POST /api/users`

**Authentication:** Required

**Request Body:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `full_name` | string | Yes | User's full name |
| `username` | string | Yes | Unique username (min 3 chars) |
| `email` | string | Yes | Unique email address |
| `password` | string | Yes | Password (min 8 chars) |
| `phone_number` | string | No | Phone number |
| `role` | string | No | Role: cashier, manager, sales, inventory, admin (default: cashier) |
| `is_active` | boolean | No | Active status (default: true) |

**Example Request:**

```bash
curl -X POST "https://your-domain.com/api/users?api_key=YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "full_name": "John Doe",
    "username": "johndoe",
    "email": "john@example.com",
    "password": "password123",
    "phone_number": "+1234567890",
    "role": "cashier",
    "is_active": true
  }'
```

**Example Response:**

```json
{
  "success": true,
  "message": "User created successfully",
  "data": {
    "id": 5,
    "full_name": "John Doe",
    "username": "johndoe",
    "email": "john@example.com",
    "phone_number": "+1234567890",
    "role": "cashier",
    "is_active": 1,
    "created_at": "2026-02-17 22:45:33"
  }
}
```

---

### 4. Update User

Update an existing user.

**Endpoint:** `PUT /api/users` or `PATCH /api/users`

**Authentication:** Required

**Query Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | User ID |

**Request Body:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `full_name` | string | No | User's full name |
| `email` | string | No | Unique email address |
| `phone_number` | string | No | Phone number |
| `role` | string | No | Role: cashier, manager, sales, inventory, admin |
| `is_active` | boolean | No | Active status |
| `password` | string | No | New password (min 8 chars) |

**Example Request:**

```bash
curl -X PUT "https://your-domain.com/api/users?id=1&api_key=YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -d '{
    "full_name": "John Smith Updated",
    "role": "manager"
  }'
```

**Example Response:**

```json
{
  "success": true,
  "message": "User updated successfully",
  "data": {
    "id": 1,
    "full_name": "John Smith Updated",
    "username": "john.smith",
    "email": "john@example.com",
    "phone_number": "+1234567890",
    "role": "manager",
    "is_active": 1,
    "created_at": "2026-02-17 21:44:38",
    "last_login": "2026-02-17 22:45:33"
  }
}
```

---

### 5. Delete User

Delete a user.

**Endpoint:** `DELETE /api/users`

**Authentication:** Required

**Query Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `id` | integer | Yes | User ID |

**Example Request:**

```bash
curl -X DELETE "https://your-domain.com/api/users?id=5&api_key=YOUR_API_KEY"
```

**Example Response:**

```json
{
  "success": true,
  "message": "User deleted successfully"
}
```

---

### 6. User Login

Authenticate a user and get user details.

**Endpoint:** `POST /api/users/login`

**Authentication:** Not required (public endpoint)

**Request Body:**

| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `username` | string | Yes | Username |
| `password` | string | Yes | Password |

**Example Request:**

```bash
curl -X POST "https://your-domain.com/api/users/login" \
  -H "Content-Type: application/json" \
  -d '{
    "username": "john.smith",
    "password": "password123"
  }'
```

**Example Response:**

```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "id": 1,
    "username": "john.smith",
    "email": "john@example.com",
    "full_name": "John Smith",
    "role": "manager",
    "phone_number": "+1234567890",
    "is_active": 1,
    "created_at": "2026-02-17 21:44:38",
    "last_login": "2026-02-17 22:45:33"
  }
}
```

---

### 7. Get Current User

Get details of the authenticated user.

**Endpoint:** `GET /api/users/me`

**Authentication:** Required

**Query Parameters:**

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `user_id` | integer | Yes | User ID |

**Example Request:**

```bash
curl "https://your-domain.com/api/users/me?user_id=1&api_key=YOUR_API_KEY"
```

**Example Response:**

```json
{
  "success": true,
  "data": {
    "id": 1,
    "full_name": "John Smith",
    "username": "john.smith",
    "email": "john@example.com",
    "phone_number": "+1234567890",
    "role": "manager",
    "is_active": 1,
    "created_at": "2026-02-17 21:44:38",
    "last_login": "2026-02-17 22:45:33"
  }
}
```

---

## 📊 HTTP Status Codes

| Code | Description |
|------|-------------|
| 200 | Success |
| 201 | Created successfully |
| 400 | Bad request - missing required parameters |
| 401 | Unauthorized - invalid or missing API key |
| 403 | Forbidden - account inactive |
| 404 | Not found - user doesn't exist |
| 409 | Conflict - username or email already exists |
| 422 | Validation error - invalid input data |
| 500 | Internal server error |

---

## 🛡️ Security Features

- **API Key Authentication** - All endpoints protected with API keys
- **Password Hashing** - bcrypt algorithm for secure password storage
- **SQL Injection Protection** - PDO prepared statements
- **XSS Protection** - Input sanitization and output encoding
- **CORS Headers** - Configurable cross-origin policies
- **HTTPS Only** - All API calls must use HTTPS

---

## 📝 User Roles

| Role | Description |
|------|-------------|
| `admin` | Full system access |
| `manager` | Management access |
| `sales` | Sales operations |
| `inventory` | Inventory management |
| `cashier` | Basic cashier operations (default) |

---

## 💡 Usage Examples

### JavaScript (Fetch API)

```javascript
const API_KEY = 'YOUR_API_KEY_HERE';
const BASE_URL = 'https://your-domain.com/api';

// List users
fetch(`${BASE_URL}/users?api_key=${API_KEY}`)
  .then(response => response.json())
  .then(data => console.log(data));

// Create user
fetch(`${BASE_URL}/users?api_key=${API_KEY}`, {
  method: 'POST',
  headers: {'Content-Type': 'application/json'},
  body: JSON.stringify({
    full_name: 'John Doe',
    username: 'johndoe',
    email: 'john@example.com',
    password: 'password123',
    role: 'cashier'
  })
})
  .then(response => response.json())
  .then(data => console.log(data));
```

### PHP (cURL)

```php
$apiKey = 'YOUR_API_KEY_HERE';
$baseUrl = 'https://your-domain.com/api';

// List users
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/users?api_key=' . $apiKey);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

$users = json_decode($response, true);
print_r($users);
```

### Python (requests)

```python
import requests

API_KEY = 'YOUR_API_KEY_HERE'
BASE_URL = 'https://your-domain.com/api'

# List users
response = requests.get(f'{BASE_URL}/users', params={'api_key': API_KEY})
users = response.json()
print(users)

# Create user
data = {
    'full_name': 'John Doe',
    'username': 'johndoe',
    'email': 'john@example.com',
    'password': 'password123',
    'role': 'cashier'
}
response = requests.post(f'{BASE_URL}/users?api_key={API_KEY}', json=data)
print(response.json())
```

---

## 📁 Project Structure

```
php-starter/
├── app/
│   ├── Config/
│   │   ├── Database.php
│   │   └── Session.php
│   ├── Controllers/
│   │   ├── AuthController.php
│   │   ├── HomeController.php
│   │   ├── PermissionController.php
│   │   ├── UserController.php
│   │   └── UsersApiController.php
│   └── Views/
│       ├── dashboard.php
│       ├── login.php
│       ├── permissions.php
│       └── users/
├── database/
│   └── migrations/
├── public/
│   ├── css/
│   ├── index.php
│   └── .htaccess
├── vendor/
├── .env
├── .htaccess
├── composer.json
└── README.md
```

---

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

---

## 📄 License

This project is licensed under the MIT License.

---

## 📞 Support

For support, open an issue on GitHub.
