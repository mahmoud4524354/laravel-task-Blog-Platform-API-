# Blog Platform API

A RESTful API for a blog platform built with **Laravel 12**, featuring user authentication, role-based permissions, blog posts, and comments system.

---

## 🚀 Features

- **User Authentication & Roles**
  - JWT-based authentication
  - Two user roles: `admin` and `author`
  - Role-based permissions:
    - Admin: Full CRUD on all posts
    - Author: Can create, update, delete their own posts

- **Blog Posts**
  - Create, read, update, delete posts
  - Categories: `Technology`, `Lifestyle`, `Education` (predefined)
  - Filter posts by category, author, date range
  - Search posts by title or author name
  - Paginated results

- **Comments**
  - Authenticated users can comment on posts
  - Retrieve all comments for a specific post

---

## 🛠 Installation

1. Clone the repository:
```bash
git clone https://github.com/your-username/blog-api.git
cd blog-api
```

2. Install dependencies:
```bash
composer install
```

3. Copy environment file and configure:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Configure your database in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog_api
DB_USERNAME=root
DB_PASSWORD=your_password
```

6. Generate JWT secret:
```bash
php artisan jwt:secret
```

7. Run migrations:
```bash
php artisan migrate 
```

8. Start the development server:
```bash
php artisan serve
```
