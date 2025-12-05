# Laravel API

## Setup
1. Clone repo
2. composer install
3. cp .env.example .env
4. Set DB credentials
5. php artisan migrate
6. php artisan jwt:secret
7. php artisan serve

## API Usage

### Auth
POST /api/auth/register
POST /api/auth/login
POST /api/auth/logout (JWT)
GET /api/auth/me (JWT)

### Products (JWT)
GET /api/products
POST /api/products
PUT /api/products/{id}
DELETE /api/products/{id}

### Orders (JWT)
POST /api/orders
