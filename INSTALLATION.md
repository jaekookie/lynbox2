# LynBox Installation & Configuration Guide

## Overview
Complete Laravel marketplace for monthly subscription boxes with modern glassmorphism design.

## Prerequisites
- PHP 8.3+
- Laravel 10+
- MySQL 8.0+
- Composer
- Node.js & NPM

## Installation Steps

### 1. Install Dependencies
```bash
composer install
npm install
npm run build
```

### 2. Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

### 3. Configure Database
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=lynbox
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Install Payment & Invoice Packages
```bash
composer require laravel/cashier
composer require barryvdh/laravel-dompdf
```

### 5. Configure Stripe (Cashier)
```env
STRIPE_PUBLIC_KEY=pk_test_...
STRIPE_SECRET_KEY=sk_test_...
STRIPE_WEBHOOK_SECRET=whsec_...
```

### 6. Run Migrations
```bash
php artisan migrate
```

### 7. Create Admin User
```bash
php artisan tinker
# In tinker shell:
App\Models\User::create(['name' => 'Admin', 'email' => 'admin@example.com', 'password' => bcrypt('password'), 'role' => 'admin']);
```

### 8. Seed Initial Data
Create `database/seeders/CategoriesSeeder.php`:
```php
namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Beauté', 'slug' => 'beaute', 'icon' => '💄'],
            ['name' => 'Alimentation', 'slug' => 'alimentation', 'icon' => '🍕'],
            ['name' => 'Livres', 'slug' => 'livres', 'icon' => '📚'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
```

Then run: `php artisan db:seed --class=CategoriesSeeder`

### 9. Configure Email (Optional - for notifications)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@lynbox.com
```

### 10. Queue Configuration (Optional - for async notifications)
```env
QUEUE_CONNECTION=database
```

Then: `php artisan queue:table` and `php artisan migrate`

## Project Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── DashboardController.php
│   │   ├── SubscriptionController.php
│   │   ├── BoxController.php
│   │   ├── DeliveryController.php
│   │   ├── ReviewController.php
│   │   ├── InvoiceController.php
│   │   ├── PaymentController.php
│   │   ├── WebhookController.php
│   │   └── Admin/
│   │       ├── DashboardController.php
│   │       └── BoxController.php
│   ├── Requests/
│   │   ├── StoreSubscriptionRequest.php
│   │   ├── PauseSubscriptionRequest.php
│   │   ├── StoreDeliveryAddressRequest.php
│   │   ├── StoreReviewRequest.php
│   │   └── StorePaymentMethodRequest.php
│   └── Middleware/
├── Models/
│   ├── User.php
│   ├── Box.php
│   ├── Category.php
│   ├── Subscription.php
│   ├── Delivery.php
│   ├── Review.php
│   ├── Invoice.php
│   ├── LoyaltyPoints.php
│   └── DeliveryAddress.php
├── Services/
│   ├── SubscriptionService.php
│   ├── PaymentService.php
│   ├── NotificationService.php
│   ├── LoyaltyService.php
│   ├── CashierService.php
│   └── InvoiceGenerator.php
├── Policies/
│   ├── SubscriptionPolicy.php
│   ├── DeliveryPolicy.php
│   ├── ReviewPolicy.php
│   ├── DeliveryAddressPolicy.php
│   └── InvoicePolicy.php
└── Notifications/
    ├── PaymentSuccessful.php
    ├── PaymentFailed.php
    ├── DeliveryShipped.php
    ├── DeliveryDelivered.php
    └── SubscriptionRenewalReminder.php

database/
├── migrations/
├── seeders/
└── factories/

resources/
└── views/
    ├── layouts/
    │   └── app.blade.php
    ├── dashboard.blade.php
    ├── catalog/
    ├── subscriptions/
    ├── deliveries/
    ├── reviews/
    ├── invoices/
    └── admin/

routes/
└── web.php
```

## API Endpoints Summary

### Authentication
- `Login/Register` - Laravel Breeze default routes

### Subscriptions
- `GET /subscriptions` - List user subscriptions
- `POST /subscriptions` - Create subscription
- `GET /subscriptions/{subscription}` - View details
- `POST /subscriptions/{subscription}/pause` - Pause subscription
- `POST /subscriptions/{subscription}/reactivate` - Reactivate
- `POST /subscriptions/{subscription}/cancel` - Cancel
- `POST /subscriptions/{subscription}/renewal-date` - Modify date

### Deliveries
- `GET /deliveries` - List deliveries
- `GET /deliveries/{delivery}` - View details
- `GET /deliveries/{delivery}/track` - Real-time tracking

### Invoices
- `GET /invoices` - List invoices
- `GET /invoices/{invoice}` - View details
- `GET /invoices/{invoice}/download` - Download PDF
- `POST /invoices/{invoice}/retry` - Retry payment

### Payments
- `POST /payments/setup-intent` - Create Stripe intent
- `POST /payments/attach-method` - Add payment method
- `POST /payments/delete-method` - Remove method
- `POST /payments/set-default` - Set default method

### Admin
- `GET /admin/dashboard` - Analytics dashboard
- `GET /admin/analytics` - Detailed analytics
- `GET/POST/PATCH/DELETE /admin/boxes/{box}` - Box management

### Webhooks
- `POST /webhooks/stripe` - Stripe events handling

## Important Notes

### Stripe Setup
1. Create Stripe account at stripe.com
2. Get API keys from dashboard
3. Create webhook endpoint at `https://yourdomain.com/webhooks/stripe`
4. Add events: `payment_intent.succeeded`, `payment_intent.payment_failed`

### Database Relationships
- Users can have multiple subscriptions
- Subscriptions link users to boxes
- Each subscription can have multiple deliveries and invoices
- Reviews are tied to both users and boxes (unique constraint)
- Loyalty points are 1-to-1 with users

### File Storage
PDFs are stored in `storage/app/invoices/YYYY/MM/`

### Security Features
- CSRF protection on all forms
- Explicit authorization checks via Policies
- Payment method security via Stripe
- Role-based access control (RBAC)
- Input validation on all requests

## Deployment Considerations
1. Set `APP_DEBUG=false` in .env
2. Run `php artisan config:cache`
3. Run `php artisan route:cache`
4. Use environment-specific Stripe keys
5. Configure proper email service
6. Set up HTTPS
7. Configure queue workers for email notifications

## Troubleshooting

### Migrations fail
- Check database credentials
- Ensure all PHP extensions are installed
- Try: `php artisan migrate:refresh` (data loss warning)

### Stripe integration issues
- Verify API keys are correct
- Check webhook endpoint is accessible
- Test with Stripe test cards

### Email not sending
- Check MAIL_ environment variables
- Verify SMTP credentials
- Check spam folder
- Enable "Less secure apps" if using Gmail

## Future Enhancements
- Filament admin panel for easier management
- API endpoints for mobile app
- Advanced analytics with ChartJS
- Email campaign system
- Referral program
- Product bundles
- Seasonal boxes
- Two-factor authentication
