<img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" height="50">

# Codesome URL Shortener 

A RESTful API built with Laravel that provides a robust backend for a URL shortening service. It allows for the creation and management of short links, tracks detailed click statistics, and provides geolocation data for each click through an asynchronous job.

---

## Features

-   **Full URL Management:** Complete CRUD functionality for creating, reading, updating, and deleting links.
-   **Custom & Random Short Codes:** Supports both user-defined short codes and automatic generation of unique random codes.
-   **Soft Deletes & Restoration:** Deleted links are moved to a "trash" and can be restored or viewed separately.
-   **Detailed Statistics:** Provides both global and per-link statistics, including total clicks and clicks over various time periods.
-   **Asynchronous Geolocation Tracking:** When a link is clicked, the user is redirected instantly. A background job processes the user's IP address to fetch and store their country and city without impacting performance.
-   **Scheduled Weekly Reports:** An automated weekly command sends an email digest with key statistics to a designated admin.

---

## Architectural Choices

To ensure the application is clean, scalable, and maintainable, several key design patterns were implemented:

-   **Fat Models, Skinny Controllers:** Business logic is encapsulated within the Eloquent models (`Link`, `Click`), leaving controllers with the sole responsibility of handling HTTP requests.
-   **API Resources:** API responses are consistently formatted using Laravel's dedicated Resource classes. This provides a transformation layer that ensures a stable and controlled data structure.
-   **Service Layer:** Complex external interactions, like geolocation lookups, are delegated to a dedicated service for reusability and easy testing.
-   **Asynchronous Jobs:** To ensure a fast user experience, the slow process of fetching geolocation data is handled by a background job, allowing the user to be redirected instantly.

---

## Tech Stack & Requirements

-   PHP 8.4+
-   MySQL or MariaDB
-   Composer
-   A local mail catcher like [MailHog](https://github.com/mailhog/MailHog) or [Mailpit](https://github.com/axllent/mailpit) for testing emails.

---

## Installation Guide

Follow these steps to set up the project locally.

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/minun96/codesome-shortener.git
    cd codesome-shortener
    ```

2.  **Install dependencies:**
    ```bash
    composer install
    ```

3.  **Create your environment file:**
    ```bash
    cp .env.example .env
    ```

4.  **Generate an application key:**
    ```bash
    php artisan key:generate
    ```

5.  **Configure your `.env` file:**
    Open the `.env` file and set up your database and mail credentials.
    ```env
    # --- Database Configuration ---
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=codesome_shortener
    DB_USERNAME=root
    DB_PASSWORD=

    # --- MailHog/Mailpit Configuration for local testing ---
    MAIL_MAILER=smtp
    MAIL_HOST=localhost
    MAIL_PORT=1025
    MAIL_USERNAME=null
    MAIL_PASSWORD=null
    MAIL_ENCRYPTION=null
    MAIL_FROM_ADDRESS="no-reply@shortener.com"

    # --- Queue Configuration for background jobs ---
    QUEUE_CONNECTION=database
    ```

6.  **Run database migrations and seeders:**
    This will create all necessary tables (including the `jobs` table for the queue) and populate them with sample data.
    ```bash
    php artisan migrate --seed
    ```

---

## Running the Application

To run the application, you need to start two processes in separate terminals.

1.  **Start the web server:**
    ```bash
    php artisan serve
    ```

2.  **Start the queue worker:**
    This process is **essential**. It listens for and processes background jobs, such as fetching geolocation data after a link is clicked.
    ```bash
    php artisan queue:work
    ```

---

## Running Tests

To run the full suite of automated tests, use the following command:
```bash
php artisan test
```
## API Endpoints

All API endpoints are prefixed with `/api`.

### Link Management

| Method | Endpoint | Description | Example Body (JSON) |
| :--- | :--- | :--- | :--- |
| `GET` | `/links` | Returns a paginated list of all links. | |
| `POST`| `/links` | Creates a new short link. `short_code` is optional. | `{"long_url": "https://www.google.com", "short_code": "my-link"}` |
| `GET` | `/links/{link}` | Shows the details of a specific link. | |
| `PATCH`| `/links/{link}` | Updates a link's `short_code`. | `{"short_code": "new-code"}` |
| `DELETE`| `/links/{link}` | Soft deletes a link (moves it to the trash). | |
| `GET` | `/links/trashed`| Returns a paginated list of soft-deleted links. | |
| `PUT` | `/links/{link}/restore`| Restores a soft-deleted link. | |

---

### Statistics

| Method | Endpoint | Description |
| :--- | :--- | :--- |
| `GET` | `/stats` | Returns global statistics (total links, total clicks, last click, top 5 links). |
| `GET` | `/stats/{link}` | Returns detailed statistics for a specific link. |

---

### Public Redirect

This route is in `web.php` and is not prefixed with `/api`.

| Method | Endpoint | Description |
| :--- | :--- | :--- |
| `GET` | `/{short_code}` | Redirects the user to the original long URL and records the click. |

---

## API Usage Examples

Here are some examples of how to interact with the API using `curl`.

### Creating a New Link with an Auto-Generated Short Code

To let the server generate a unique short code for you, simply omit the `short_code` field from the request body.

**Request:**
```bash
curl -X POST http://127.0.0.1:8000/api/links \
  -H "Content-Type: application/json" \
  -d '{
        "long_url": "https://www.google.com"
      }'
```

**Successful Response (201 Created)**
```JSON
{
    "data": {
        "id": 1,
        "original_url": "https://www.google.com",
        "short_code": "aB7xYz1",
        "full_short_url": "http://127.0.0.1:8000/aB7xYz1",
        "created_at": "2025-10-27T10:00:00.000000Z"
    }
}
```

### Creating a New Link with a Custom Short Code

The provided `short_code` must be unique and respect the validation rules (`min:6`, `max:12`).

**Request:**
```bash
curl -X POST http://127.0.0.1:8000/api/links \
  -H "Content-Type: application/json" \
  -d '{
        "long_url": "https://www.laravel.com",
        "short_code": "laravel"
      }'
```

**Successful Response (201 Created)**
```JSON
{
    "data": {
        "id": 2,
        "original_url": "https://www.laravel.com",
        "short_code": "laravel",
        "full_short_url": "http://127.0.0.1:8000/laravel",
        "created_at": "2025-10-27T10:00:00.000000Z"
    }
}
```

### Updating a Link's Short Code

Use this endpoint to change the `short_code` of an existing link. Keep in mind the validation rules: the new field must be unique and respect the standard length (`min:6`, `max:12`).

**Request:**
```bash
curl -X PATCH http://127.0.0.1:8000/api/links/1 \
  -H "Content-Type: application/json" \
  -d '{
        "short_code": "googlesearch"
      }'
```

**Successful Response (200 OK)**
```JSON
{
    "data": {
        "id": 1,
        "original_url": "https://www.google.com",
        "short_code": "googlesearch",
        "full_short_url": "http://127.0.0.1:8000/googlesearch",
        "created_at": "2025-10-27T10:00:00.000000Z"
    }
}
```

---

## Scheduled Tasks

-   **Weekly Digest:** A weekly report with global statistics is sent every Monday at 8:00 AM.

-   **Recipient:** `admin@example.com` (captured by MailHog/Mailpit).

-   **To test manually**, run the following command:
    ```bash
    php artisan app:send-weekly-digest
    ```