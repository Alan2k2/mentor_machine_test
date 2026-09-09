# Mini ERP Inventory & Purchase Order Management

This is a production-grade Mini ERP Module built with Laravel 11. It provides a web interface for back-office users and a secure RESTful API using Laravel Sanctum.

## Setup Instructions

Clone the repository or extract the provided `.zip` archive, then run the following commands to get started:

```bash
composer install
php artisan key:generate
php artisan migrate --seed
```

*(Note: The environment is configured by default to use SQLite for easy evaluation. To use MySQL, update the `.env` file with `DB_CONNECTION=mysql` and your database credentials before running the migration).*

## Testing

The project uses the Pest testing framework for automated unit and feature tests.

Run the test suite using:
```bash
./vendor/bin/pest
# or
php artisan test
```

## Authentication

**Default Admin Credentials (for both Web and API):**
- **Email:** `admin@gmail.com`
- **Password:** `Admin@123`

## Architecture & Optimization Decisions

1. **Domain-Driven Actions:** Business logic for PO creation and status transitions are extracted into Action classes (`CreatePurchaseOrderAction`, `UpdatePurchaseOrderStatusAction`) to ensure controllers remain thin and logic can be reused by both Web and API layers.
2. **Database Transactions:** The inventory mutation during PO status transition to `RECEIVED` is wrapped in `DB::transaction()` with `lockForUpdate()` pessimistic locking on the `PurchaseOrder` and `Product` models to prevent race conditions during high-concurrency stock adjustments.
3. **Immutability Rules:** Strict lifecycle states are enforced inside the Action classes. Once an order is `RECEIVED` or `CANCELLED`, it throws domain exceptions on further modification attempts.
4. **Indexing:**
   - A standard index is added to `status` in the `purchase_orders` table since it is frequently queried for aggregate reports.
   - A composite index `[purchase_order_id, product_id]` is added to the `purchase_order_items` table.
5. **N+1 Prevention:** Controllers eagerly load relationships using `with('supplier')` and `with('items.product')` to avoid N+1 query problems in lists and details views.

## API Documentation

Below are sample cURL commands for testing the API. 

### 1. Login to obtain Sanctum Token
```bash
curl -X POST http://localhost:8000/api/login \
-H "Content-Type: application/json" \
-d '{"email":"admin@gmail.com", "password":"Admin@123"}'
```
*Note the `access_token` returned from this response to use in the Authorization header for subsequent requests.*

### 2. Get Inventory
```bash
curl -X GET http://localhost:8000/api/inventory \
-H "Authorization: Bearer YOUR_TOKEN_HERE" \
-H "Accept: application/json"
```

### 3. Get Low Stock Alerts
```bash
curl -X GET http://localhost:8000/api/inventory/low-stock \
-H "Authorization: Bearer YOUR_TOKEN_HERE" \
-H "Accept: application/json"
```

### 4. Create Purchase Order
```bash
curl -X POST http://localhost:8000/api/purchase-orders \
-H "Authorization: Bearer YOUR_TOKEN_HERE" \
-H "Content-Type: application/json" \
-H "Accept: application/json" \
-d '{
  "supplier_id": 1,
  "items": [
    {
      "product_id": 1,
      "quantity": 5,
      "unit_price": 1200.00
    }
  ]
}'
```

### 5. Update Purchase Order Status (e.g. to RECEIVED)
```bash
curl -X PATCH http://localhost:8000/api/purchase-orders/1/status \
-H "Authorization: Bearer YOUR_TOKEN_HERE" \
-H "Content-Type: application/json" \
-H "Accept: application/json" \
-d '{"status":"RECEIVED"}'
```

### 6. Get Supplier Spend Report
```bash
curl -X GET http://localhost:8000/api/reports/supplier-spend \
-H "Authorization: Bearer YOUR_TOKEN_HERE" \
-H "Accept: application/json"
```
