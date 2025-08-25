# Vehicle Management System API Documentation

## Overview
This API provides comprehensive endpoints for managing all aspects of the Vehicle Management System, including users, vehicles, reservations, departments, positions, posts, passengers, QR code scanning, and chat functionality.

## Base URL
```
http://your-domain.com/api/v1
```

## Authentication
Currently, all endpoints are public. For production use, consider implementing authentication middleware.

## API Response Format
All API responses follow this standard format:
```json
{
    "success": true/false,
    "message": "Response message",
    "data": {...},
    "errors": {...} // Only present when validation fails
}
```

## Endpoints

### 1. User Management

#### Get All Users
```http
GET /api/v1/users
```
**Query Parameters:**
- `per_page` (optional): Number of users per page (default: 15)

**Response:**
```json
{
    "success": true,
    "message": "Users retrieved successfully",
    "data": {
        "current_page": 1,
        "data": [...],
        "total": 50,
        "per_page": 15
    }
}
```

#### Get User by ID
```http
GET /api/v1/users/{id}
```

#### Create User
```http
POST /api/v1/users
```
**Request Body:**
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "department_id": 1,
    "position_id": 1,
    "date_of_birth": "1990-01-01",
    "gender": "male",
    "contact_number": "+1234567890",
    "address": "123 Main St",
    "active": true
}
```

#### Update User
```http
PUT /api/v1/users/{id}
```

#### Delete User
```http
DELETE /api/v1/users/{id}
```

### 2. Vehicle Management

#### Get All Vehicles
```http
GET /api/v1/vehicles
```

#### Get Vehicle by ID
```http
GET /api/v1/vehicles/{id}
```

#### Create Vehicle
```http
POST /api/v1/vehicles
```
**Request Body:**
```json
{
    "vehicle_name": "Toyota Camry",
    "vehicle_color": "White",
    "model": "2023",
    "plate_number": "ABC123",
    "capacity": 5,
    "date_acquired": "2023-01-01",
    "vehicle_image": "camry.jpg",
    "status": "available"
}
```

#### Update Vehicle
```http
PUT /api/v1/vehicles/{id}
```

#### Delete Vehicle
```http
DELETE /api/v1/vehicles/{id}
```

#### Get Vehicle Availability Status
```http
GET /api/v1/vehicles/availability/status
```

### 3. Vehicle Reservations

#### Get All Reservations
```http
GET /api/v1/reservations
```

#### Get Reservation by ID
```http
GET /api/v1/reservations/{id}
```

#### Create Reservation
```http
POST /api/v1/reservations
```
**Request Body:**
```json
{
    "vehicle_id": 1,
    "user_id": 1,
    "requested_name": "John Doe",
    "destination": "Downtown Office",
    "longitude": 121.5654,
    "latitude": 25.0330,
    "driver": "Mike Johnson",
    "driver_user_id": 2,
    "start_datetime": "2024-01-15 09:00:00",
    "end_datetime": "2024-01-15 17:00:00",
    "reason": "Business meeting",
    "remarks": "Need parking space",
    "reservation_type_id": 1,
    "status": "pending"
}
```

#### Update Reservation
```http
PUT /api/v1/reservations/{id}
```

#### Delete Reservation
```http
DELETE /api/v1/reservations/{id}
```

### 4. Department Management

#### Get All Departments
```http
GET /api/v1/departments
```

#### Create Department
```http
POST /api/v1/departments
```
**Request Body:**
```json
{
    "department_name": "IT Department",
    "description": "Information Technology Department",
    "status": "active"
}
```

#### Update Department
```http
PUT /api/v1/departments/{id}
```

### 5. Position Management

#### Get All Positions
```http
GET /api/v1/positions
```

#### Create Position
```http
POST /api/v1/positions
```
**Request Body:**
```json
{
    "position_name": "Software Engineer",
    "description": "Develops software applications",
    "status": "active"
}
```

### 6. Posts/Announcements

#### Get All Posts
```http
GET /api/v1/posts
```

#### Create Post
```http
POST /api/v1/posts
```
**Request Body:**
```json
{
    "announcement_title": "System Maintenance",
    "description": "System will be down for maintenance on Sunday",
    "status": "active"
}
```

### 7. Reservation Types

#### Get All Reservation Types
```http
GET /api/v1/reservation-types
```

### 8. Passenger Management

#### Get Passengers for Reservation
```http
GET /api/v1/passengers/reservation/{reservationId}
```

#### Add Passenger to Reservation
```http
POST /api/v1/passengers
```
**Request Body:**
```json
{
    "reserve_vehicle_id": 1,
    "passenger_id": 3,
    "passenger_name": "Jane Smith",
    "status": "confirmed"
}
```

### 9. QR Code Scanning

#### Get Scan Records for Reservation
```http
GET /api/v1/scans/reservation/{reservationId}
```

#### Record QR Code Scan
```http
POST /api/v1/scans
```
**Request Body:**
```json
{
    "reserve_vehicle_id": 1,
    "workstate": "started",
    "logtime": "2024-01-15 09:00:00"
}
```

### 10. Chat Messages

#### Get Chat Messages Between Users
```http
GET /api/v1/chat/messages?from_user_id=1&to_user_id=2
```

#### Send Chat Message
```http
POST /api/v1/chat/send
```
**Request Body:**
```json
{
    "from_user_id": 1,
    "to_user_id": 2,
    "message": "Hello! How are you?",
    "status": "sent"
}
```

### 11. Dashboard & Analytics

#### Get Dashboard Statistics
```http
GET /api/v1/dashboard/stats
```
**Response:**
```json
{
    "success": true,
    "message": "Dashboard statistics retrieved successfully",
    "data": {
        "total_users": 150,
        "total_vehicles": 25,
        "active_reservations": 12,
        "pending_reservations": 5,
        "total_departments": 8,
        "total_positions": 15,
        "active_posts": 3,
        "total_reservation_types": 4
    }
}
```

### 12. Search Functionality

#### Search Across All Models
```http
GET /api/v1/search?q=john&type=all
```
**Query Parameters:**
- `q` (required): Search query
- `type` (optional): Search type - 'all', 'users', 'vehicles', 'reservations' (default: 'all')

### 13. Health Check

#### API Health Check
```http
GET /api/v1/health
```
**Response:**
```json
{
    "status": "success",
    "message": "Vehicle Management System API is running",
    "timestamp": "2024-01-15T10:30:00.000000Z",
    "version": "1.0.0"
}
```

### 14. API Documentation

#### Get API Documentation
```http
GET /api/docs
```

## Error Handling

### Validation Errors (422)
```json
{
    "success": false,
    "message": "Validation failed",
    "data": null,
    "errors": {
        "email": ["The email field is required."],
        "password": ["The password must be at least 6 characters."]
    }
}
```

### Not Found Errors (404)
```json
{
    "success": false,
    "message": "User not found",
    "data": null
}
```

### Server Errors (500)
```json
{
    "success": false,
    "message": "Error retrieving users",
    "data": null
}
```

## Pagination
For endpoints that return lists, pagination is implemented with the following response structure:
```json
{
    "current_page": 1,
    "data": [...],
    "first_page_url": "...",
    "from": 1,
    "last_page": 5,
    "last_page_url": "...",
    "next_page_url": "...",
    "path": "...",
    "per_page": 15,
    "prev_page_url": null,
    "to": 15,
    "total": 75
}
```

## Relationships
The API automatically loads related data for better performance:
- Users include department and position information
- Reservations include vehicle, user, reservation type, passengers, and driver information
- Passengers include user and reservation information
- Scan records include reservation information

## Testing the API

### Using cURL
```bash
# Get all users
curl -X GET "http://your-domain.com/api/v1/users"

# Create a user
curl -X POST "http://your-domain.com/api/v1/users" \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "department_id": 1,
    "position_id": 1
  }'
```

### Using Postman
1. Import the collection
2. Set the base URL to `http://your-domain.com/api/v1`
3. Test each endpoint with appropriate request bodies

## Rate Limiting
Currently, no rate limiting is implemented. Consider adding rate limiting for production use.

## CORS
Ensure your Laravel application has proper CORS configuration if you plan to use this API from frontend applications.

## Security Considerations
1. Implement authentication and authorization
2. Add input validation and sanitization
3. Implement rate limiting
4. Add API key management
5. Enable HTTPS in production
6. Add request logging and monitoring

## Support
For API support and questions, please refer to your development team or create an issue in the project repository.
