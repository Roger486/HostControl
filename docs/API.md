# 📘 HostControl API Docs

This file explains the main API routes of the HostControl backend.
If a route needs login, you must send a token in the headers like this:

```
Authorization: Bearer <TOKEN>
```

Some routes are public, but most require authentication via Laravel Sanctum.

---

## 👤 Users

These endpoints allow you to manage users (create, list, update, delete).

---

### 📌 User Field Options (Enums)

**document_type:**
- "DNI"
- "NIE"
- "Passport"

**role:**
- "user"
- "admin"

These values are required when creating or updating users.  
If you send something else, it may fail.

---

### GET /api/users

**Description:** Get a paginated list of all users. Testing is recomended to see the different responses and understand it.

**Auth required:** ❌ No

**Success response (200):**
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "first_name": "John",
      "last_name_1": "Doe",
      "last_name_2": "Smith",
      "email": "john@example.com",
      "email_verified_at": "2025-03-21T12:20:02.000000Z",
      "birthdate": "1990-01-01",
      "address": "123 Main St",
      "document_type": "DNI",
      "document_number": "12345678A",
      "phone": "+34123456789",
      "role": "user",
      "comments": null,
      "created_at": "2025-03-25T12:00:00.000000Z",
      "updated_at": "2025-03-25T12:00:00.000000Z"
    }
  ],
  "first_page_url": "http://localhost/api/users?page=1",
  "from": 1,
  "last_page": 1,
  "last_page_url": "http://localhost/api/users?page=1",
  "next_page_url": null,
  "path": "http://localhost/api/users",
  "per_page": 10,
  "prev_page_url": null,
  "to": 1,
  "total": 1
}

```

---

### GET /api/users/{id}

**Description:** Get data for a specific user.

**Auth required:** ❌ No

**Success response (200):**
```json
{
  "id": 1,
  "first_name": "John",
  "last_name_1": "Doe",
  "last_name_2": "Smith",
  "email": "john@example.com",
  "email_verified_at": "2025-03-21T12:20:02.000000Z",
  "birthdate": "1990-01-01",
  "address": "123 Main St",
  "document_type": "DNI",
  "document_number": "12345678A",
  "phone": "+34123456789",
  "role": "user",
  "comments": null,
  "created_at": "2025-03-25T12:00:00.000000Z",
  "updated_at": "2025-03-25T12:00:00.000000Z"
}
```

**Errors:**
- 404: User not found

---

### POST /api/users

**Description:** Create a new user.

**Auth required:** ❌ No

**Body (JSON):**
```json
{
  "first_name": "John",
  "last_name_1": "Doe",
  "last_name_2": "Smith", // optional
  "email": "john@example.com",
  "password": "secret123",
  "birthdate": "1990-01-01",
  "address": "123 Main St",
  "document_type": "DNI",
  "document_number": "12345678A",
  "phone": "+34123456789",
  "role": "user", // optional, default: "user"
  "comments": "Optional note" // optional
}
```

**Success response (201):** Returns created user (excluding hidden fields like password)

**Errors:**
- 422: Missing required fields or email/document already in use

---

### PUT /api/users/{id}

**Description:** Update a user.

**Auth required:** ❌ No

**Body (same as POST):** Send only fields you want to update

**Success response (200):** Updated user data

**Errors:**
- 404: User not found
- 422: Validation error

---

### DELETE /api/users/{id}

**Description:** Delete a user.

**Auth required:** ❌ No

**Success response (204):** No content

**Errors:**
- 404: User not found

---

## 🏠 Accommodations

These endpoints allow you to manage all types of accommodations (houses, bungalows, rooms, camping spots).

---

### 📌 Field Options (Enums)

Each type has its own specific fields.  
These fields are required when creating or updating an accommodation of that type.

| Type           | Extra Fields                                         | Data Types                     |
|----------------|------------------------------------------------------|--------------------------------|
| `bungalow`     | `bed_amount`, `has_air_conditioning`, `has_kitchen` | `int`, `bool`, `bool`          |
| `camping_spot` | `area_size_m2`, `has_electricity`, `accepts_caravan`| `int`, `bool`, `bool`          |
| `house`        | `bed_amount`, `room_amount`, `has_air_conditioning` | `int`, `int`, `bool`           |
| `room`         | `bed_amount`, `has_air_conditioning`, `has_private_wc` | `int`, `bool`, `bool`        |

---

### GET /api/accommodations

**Description:** Returns a paginated list of accommodationswith their type-specific details included. Use ?page=N to navigate pages.

**Auth required:** ❌ No

**Success response (200):**
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "accommodation_code": "wf72",
      "section": "dolor",
      "capacity": 10,
      "price_per_day": 36668,
      "is_available": false,
      "comments": "Example comment",
      "type": "house",
      "created_at": "2025-03-21T12:20:12.000000Z",
      "updated_at": "2025-03-21T12:20:12.000000Z",
      "house": {
        "bed_amount": 4,
        "room_amount": 3,
        "has_air_conditioning": true
      },
      "bungalow": null,
      "camping_spot": null,
      "room": null
    }
  ],
  "first_page_url": "http://localhost/api/accommodations?page=1",
  "from": 1,
  "last_page": 4,
  "last_page_url": "http://localhost/api/accommodations?page=4",
  "next_page_url": "http://localhost/api/accommodations?page=2",
  "path": "http://localhost/api/accommodations",
  "per_page": 10,
  "prev_page_url": null,
  "to": 1,
  "total": 40
}
```

---

### GET /api/accommodations/{id}

**Description:** Get one accommodation with all its type-specific details.

**Auth required:** ❌ No

**Success response (200):** Same format as GET all.

**Errors:**
- 404: Accommodation not found

---

### POST /api/accommodations

**Description:** Create a new accommodation.

**Auth required:** ❌ No

**Body (JSON):**
```json
{
  "accommodation_code": "A001",
  "section": "North",
  "capacity": 4,
  "price_per_day": 8500,
  "is_available": true,
  "comments": "Near the lake",
  "type": "bungalow",
  "bed_amount": 3,
  "has_air_conditioning": true,
  "has_kitchen": true
}
```

**Success response (201):** Created accommodation with details

**Errors:**
- 422: Missing or wrong fields
- 500: Wrong type class (not yet validated in controller)

---

### PUT /api/accommodations/{id}

**Description:** Update general or type-specific fields of an accommodation.

**Auth required:** ❌ No

**Body:** Same format as POST (you can send only fields to update)

**Success response (200):** Updated accommodation with details

**Errors:**
- 404: Accommodation not found
- 422: Validation error

---

### DELETE /api/accommodations/{id}

**Description:** Delete an accommodation and its type-specific record.

**Auth required:** ❌ No

**Success response (204):** No content

**Errors:**
- 404: Accommodation not found

---

## 🗓️ Reservations

These endpoints allow you to manage reservations made by users, including their companions and accommodation data.

---

### 📌 Field Options (Enums)

**status:**
- "pending"
- "confirmed"
- "cancelled"
- "checked_in"
- "checked_out"

**companion.document_type:**
- "DNI"
- "NIE"
- "Passport"

---

### GET /api/reservations

**Description:** Get a paginated list of all reservations with user, accommodation and companion data included.
See User for booked_by and guest structure.

**Auth required:** ❌ No

**Success response (200):**
```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "booked_by_id": 5,
      "guest_id": 7,
      "accommodation_id": 3,
      "check_in_date": "2025-08-01",
      "check_out_date": "2025-08-05",
      "status": "pending",
      "comments": null,
      "created_at": "2025-03-28T14:00:00.000000Z",
      "updated_at": "2025-03-28T14:00:00.000000Z",
      "booked_by": { "id": 5, "first_name": "Alice", "...": "..." },
      "guest": { "id": 7, "first_name": "Bob", "...": "..." },
      "accommodation": { "id": 3, "type": "bungalow", "...": "..." },
      "companions": [
        {
          "first_name": "Eva",
          "last_name_1": "Lopez",
          "document_type": "DNI",
          "birthdate": "1999-04-12"
        }
      ]
    }
  ],
  "per_page": 10,
  "total": 25,
  "last_page": 3
}
```

---

### GET /api/reservations/{id}

**Description:** Get full details of a reservation by ID.

**Auth required:** ❌ No

**Success response (200):** Same structure as GET all

**Errors:**
- 404: Reservation not found

---

### POST /api/reservations

**Description:** Create a new reservation. You must include `companions` if there are any.

**Auth required:** ✅ Yes

**Body (JSON):**
```json
{
  "booked_by_id": 5,
  "guest_id": 7,
  "accommodation_id": 3,
  "check_in_date": "2025-08-01",
  "check_out_date": "2025-08-05",
  "status": "pending",
  "comments": "Window side request",
  "companions": [
    {
      "first_name": "Eva",
      "last_name_1": "Lopez",
      "document_type": "DNI",
      "birthdate": "1999-04-12"
    }
  ]
}
```

**Success response (201):** Reservation with all relationships

**Errors:**
- 422: Missing required fields
- 500: Internal error (usually invalid `accommodation_id`)

---

### PUT /api/reservations/{id}

**Description:** Update a reservation and its companions.

**Auth required:** ❌ No

**Body (JSON):** Same as POST. Sending companions will replace all existing ones.

**Success response (200):** Updated reservation with details

**Errors:**
- 404: Reservation not found
- 422: Validation error

---

### DELETE /api/reservations/{id}

**Description:** Delete a reservation and its companions.

**Auth required:** ❌ No

**Success response (204):** No content

**Errors:**
- 404: Reservation not found

---

## 🛠️ More endpoints coming soon...