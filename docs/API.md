# 📘 HostControl API Docs

This file explains the main API routes of the HostControl backend.
If a route needs login, you must send a token in the headers like this:

```
Authorization: Bearer <TOKEN>
```

Some routes are public, but most require authentication via Laravel Sanctum.

---

## Index

### 🆎 Important info
### 👤 Users
### 🏠 Accommodations
### 🗓️ Reservations
### 👮 Auth

---
---

## 🆎 Important info

### 📌 Regular Expressions used in validation

| Field      | Regex Pattern                                               | Description                          |
|------------|-------------------------------------------------------------|--------------------------------------|
| `DNI`      | `/^[0-9]{8}[TRWAGMYFPDXBNJZSQVHLCKET]$/`                    | 8 digits followed by a capital letter |
| `NIE`      | `/^[XYZ][0-9]{7}[TRWAGMYFPDXBNJZSQVHLCKET]$/`               | Starts with X, Y, or Z + 7 digits + letter |
| `Passport` | `/^[A-Za-z0-9]{5,20}$/`                                        | Alphanumeric, 5 to 20 characters      |
| `Phone`    | `/^\+?[0-9\s\-]{7,20}$/`                                    | Digits, spaces or hyphens, optional `+` |

> These are **api validation formats**. If frontend needs stricter pre-validation, use these as a base.

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

**Auth required:** ✅ Yes

**Authorization:** Admins only (`viewAny` policy)

**Success response (200):**
```json
{
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
  "links": {
    "first": "http://localhost/api/users?page=1",
    "last": "http://localhost/api/users?page=1",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "links": [
      {
        "url": null,
        "label": "&laquo; Previous",
        "active": false
      },
      {
        "url": "http://localhost/api/users?page=1",
        "label": "1",
        "active": true
      }
    ],
    "path": "http://localhost/api/users",
    "per_page": 10,
    "to": 1,
    "total": 1
  }
}
```

---

### GET /api/users/{id}

**Description:** Get data for a specific user. This endpoint is intended for admin use.

**Auth required:** ✅ Yes

**Authorization:** Admins (`view` policy)

**Success response (200):**
```json
{
  "data": {
    "id": 17,
    "first_name": "Monte",
    "last_name_1": "Batz",
    "last_name_2": "Marks",
    "email": "ruecker.margaretta@example.com",
    "email_verified_at": "2025-04-10T15:15:52.000000Z",
    "birthdate": "1981-06-17T00:00:00.000000Z",
    "address": "8846 Kling Ramp Apt. 979\nNakiachester, DE 61157",
    "document_type": "Passport",
    "document_number": "67801875ll",
    "phone": "+1-470-475-0669",
    "role": "user",
    "comments": "Id rerum ullam tempora fugit. Illo eos delectus dolorem magni numquam fuga. Non magni odit qui.",
    "created_at": "2025-04-10T15:16:01.000000Z",
    "updated_at": "2025-04-10T15:16:01.000000Z"
  }
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
  "comments": "Optional note" // optional
}
```

**Success response (201):** Returns created user (excluding hidden fields like password)

**Errors:**
- 422: Missing required fields, wrong format or email/document already in use

**Error Response example:**
```json
{
  "message": "The first name field is required. (and 4 more errors)",
  "errors": {
    "first_name": [
      "The first name field is required."
    ],
    "email": [
      "The email has already been taken."
    ],
    "birthdate": [
      "The birthdate field must be a valid date."
    ],
    "document_number": [
      "The document number has already been taken."
    ],
    "phone": [
      "Phone number can start with +, must be 7 to 20 characters long and can include digits(0-9), spaces( ), or hyphens(-)."
    ]
  }
}
```

---

### PUT /api/users/{id}

**Description:** Update a user. This endpoint is intended for admin use

**Auth required:** ✅ Yes

**Authorization:** Admins (`update` policy)

**Body (same as POST):** Send only fields you want to update

**Success response (200):** Updated user data

**Errors:**
- 404: User not found
- 422: Validation error

**Error Response example:**
```json
{
  "message": "The last name 1 field is required. (and 1 more error)",
  "errors": {
    "last_name_1": [
      "The last name 1 field is required."
    ],
    "email": [
      "The email field must be a valid email address."
    ]
  }
}
```
---

### DELETE /api/users/{id}

**Description:** Delete a user.

**Auth required:** ✅ Yes

**Authorization:** Admins only (`delete` policy)

**Success response (204):** No content

**Errors:**
- 404: User not found

---

### GET /api/user

**Description:** Get data for athenticated user.

**Auth required:** ✅ Yes

**Authorization:** The authenticated user only

**Success response (200):** 
```json
{
  "data": {
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
}
```

**Errors:**
- 401: Unauthenticated (if token is missing or invalid)
---

### PUT /api/user

**Description:** Update the authenticated user's profile.

**Auth required:** ✅ Yes

**Authorization:** The authenticated user only

**Body (same as POST):** Send only fields you want to update, such as:

```json
{
  "first_name": "John",
  "last_name_1": "Doe",
  "last_name_2": "Smith",
  "birthdate": "1990-01-01",
  "address": "123 Main St",
  "phone": "+34123456789",
  "comments": "Some comments"
}
```

**Success response (200):** 
```json
{
  "data": {
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
}
```

**Errors:**
- 401: Unauthenticated (if token is missing or invalid)
- 422: Validation error (if any of the fields are invalid or required fields are missing)

**Error Response example:**
```json
{
  "message": "The last name 1 field is required.",
  "errors": {
    "last_name_1": [
      "The last name 1 field is required."
    ]
  }
}
```

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
  "links": {
    "first": "http://localhost/api/accommodations?page=1",
    "last": "http://localhost/api/accommodations?page=4",
    "prev": null,
    "next": "http://localhost/api/accommodations?page=2"
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 4,
    "links": [
      {
        "url": null,
        "label": "&laquo; Previous",
        "active": false
      },
      {
        "url": "http://localhost/api/accommodations?page=1",
        "label": "1",
        "active": true
      },
      {
        "url": "http://localhost/api/accommodations?page=2",
        "label": "2",
        "active": false
      },
      {
        "url": "http://localhost/api/accommodations?page=3",
        "label": "3",
        "active": false
      },
      {
        "url": "http://localhost/api/accommodations?page=4",
        "label": "4",
        "active": false
      },
      {
        "url": "http://localhost/api/accommodations?page=2",
        "label": "Next &raquo;",
        "active": false
      }
    ],
    "path": "http://localhost/api/accommodations",
    "per_page": 10,
    "to": 10,
    "total": 40
  }
}
```

---

### GET /api/accommodations/{id}

**Description:** Get one accommodation with all its type-specific details.

**Auth required:** ❌ No

**Success response (200):**
```json
{
  "data": {
    "accommodation_code": "AC-296",
    "section": "D",
    "capacity": 2,
    "price_per_day": 180,
    "is_available": true,
    "comments": "Casa de lujo con vista al mar",
    "type": "bungalow",
    "updated_at": "2025-04-11T15:50:49.000000Z",
    "created_at": "2025-04-11T15:50:49.000000Z",
    "id": 43,
    "house": null,
    "bungalow": {
      "id": 13,
      "accommodation_id": 43,
      "bed_amount": 0,
      "has_air_conditioning": true,
      "has_kitchen": false,
      "created_at": "2025-04-11T15:50:49.000000Z",
      "updated_at": "2025-04-11T15:50:49.000000Z"
    },
    "camping_spot": null,
    "room": null
  }
}
```

**Errors:**
- 404: Accommodation not found

---

### POST /api/accommodations

**Description:** Create a new accommodation.

**Auth required:** ✅ Yes

**Authorization:** Admins only (`create` policy)

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

---

### PUT /api/accommodations/{id}

**Description:** Update general or type-specific fields of an accommodation.

**Auth required:** ✅ Yes

**Authorization:** Admins only (`update` policy)

**Body:** Same format as POST (you can send only fields to update - type prohibited)

**Success response (200):** Updated accommodation with details

**Errors:**
- 404: Accommodation not found
- 422: Validation error

---

### DELETE /api/accommodations/{id}

**Description:** Delete an accommodation and its type-specific record.

**Auth required:** ✅ Yes

**Authorization:** Admins only (`delete` policy)

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

**Auth required:** ✅ Yes

**Authorization:** Admins only (`viewAny` policy)

**Success response (200):** Testing recomended for full comprehension.
```json
{
  "data": [
    {
      "id": 1,
      "booked_by_id": 17,
      "guest_id": 38,
      "accommodation_id": 29,
      "check_in_date": "2025-06-15T00:00:00.000000Z",
      "check_out_date": "2025-12-07T00:00:00.000000Z",
      "status": "confirmed",
      "comments": null,
      "booked_by": {
        "id": 17,
        "first_name": "Monte",
        "last_name_1": "Batz",
        "email": "ruecker.margaretta@example.com"
      },
      "guest": {
        "id": 38,
        "first_name": "Jasper",
        "last_name_1": "Rodriguez",
        "email": "ggreen@example.net"
      },
      "accommodation": {
        "id": 29,
        "accommodation_code": "ml34",
        "capacity": 3,
        "price_per_day": 25791,
        "type": "camping_spot"
      },
      "companions": [
        {
          "id": 1,
          "first_name": "Waylon",
          "last_name_1": "Wiza",
          "birthdate": "1993-10-03T00:00:00.000000Z"
        }
      ]
    }
  ],
  "links": {
    "first": "http://localhost:8000/api/reservations?page=1",
    "last": "http://localhost:8000/api/reservations?page=3",
    "next": "http://localhost:8000/api/reservations?page=2"
  },
  "meta": {
    "current_page": 1,
    "last_page": 3,
    "total": 25
  }
}
```

---

### GET /api/reservations/{id}

**Description:** Get full details of a reservation by ID.

**Auth required:** ✅ Yes

**Authorization:** Admins only (`viewAny` policy)

**Success response (200):** Same structure as GET all

**Errors:**
- 404: Reservation not found

---

### POST /api/reservations

**Description:** Create a new reservation. You must include `companions` if there are any.

**Auth required:** ✅ Yes

**Authorization:** Any authenticated user

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
      "document_number": "12345678A",
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

**Auth required:** ✅ Yes

**Authorization:** Admins only (`update` policy)

**Body (JSON):** Same as POST. Sending companions will replace all existing ones.

**Success response (200):** Updated reservation with details

**Errors:**
- 404: Reservation not found
- 422: Validation error

---

### DELETE /api/reservations/{id}

### 🔒 This endpoint is currently disabled.

**Description:** Delete a reservation and its companions.

**Auth required:** ✅ Yes

**Authorization:** Admins only (`delete` policy)

**Success response (204):** No content

**Errors:**
- 404: Reservation not found

---

### GET /api/user/reservations

**Description:** Retrieve all reservations for the authenticated user where they are the guest.

**Auth required:** ✅ Yes

**Authorization:** The authenticated user only

**Success response (200):** 
```json
{
  "data": [
    {
      "id": 9,
      "accommodation_id": 13,
      "check_in_date": "2025-12-24",
      "check_out_date": "2025-06-06",
      "status": "pending",
      "comments": null,
      "companions": [
        {
          "id": 11,
          "document_number": null,
          "document_type": "Passport",
          "first_name": "Beryl",
          "last_name_1": "Sporer",
          "last_name_2": null,
          "birthdate": "1979-10-02",
          "comments": "Aut sunt voluptates et. Aliquid ut molestiae quia cumque ea ea. Vel cupiditate neque commodi vel."
        },
        {
          "id": 12,
          "document_number": null,
          "document_type": "NIE",
          "first_name": "Mozell",
          "last_name_1": "Bosco",
          "last_name_2": "Heathcote",
          "birthdate": "1987-02-05",
          "comments": "Possimus culpa earum accusamus qui dolores. Quas impedit veniam unde inventore."
        }
      ]
    }
  ]
}
```

**Errors:**
- 401: Unauthenticated (if token is missing or invalid)
- 422: Validation error (if data is invalid, e.g., missing parameters)

**Error Response example:**
```json
{
  "message": "Unauthorized",
  "error": "You must be logged in to view your reservations."
}
```

---

## 👮 Auth

These endpoints manage user authentication using Laravel Sanctum.

> For protected routes, include your token in the request headers:  
> `Authorization: Bearer <TOKEN>`

### POST /api/login

**Description:** Log in a user and receive an access token.

**Auth required:** ❌ No

**Body (JSON):**
```json
{
  "email": "user@example.com",
  "password": "password"
}
```

**Success response (200):**

```json
{
  "token": "1|XyzAbc123456..."
}
```

**Errors:**
- 401: Invalid credentials

---

### POST /api/logout

**Description:** Log out the current user by revoking their API token.

**Auth required:** ✅ Yes

**Authorization:** Self

**Success response (204):** No content

**Errors:**
- 401: Unauthenticated (if no valid token)

## 🛠️ More endpoints coming soon...