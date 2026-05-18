# LynBox - Premium Subscription Box Marketplace

A modern Laravel-based platform for managing monthly subscription boxes with integrated payment processing, real-time delivery tracking, and comprehensive admin analytics.

## Features

### Customer Features
- **Subscription Management** - Subscribe, pause, reactivate, and cancel boxes
- **Real-time Delivery Tracking** - Track deliveries with status updates and tracking numbers
- **Invoice Management** - View and download invoices for all transactions
- **Reviews & Ratings** - Leave reviews and ratings for received boxes
- **Multiple Delivery Addresses** - Manage multiple delivery addresses with default address support
- **Loyalty Program** - Earn points and unlock membership tiers (Silver, Gold, Platinum)
- **Payment Methods** - Secure payment method management via Stripe

### Admin Features
- **Dashboard Analytics** - Comprehensive dashboard with key metrics and trends
- **Box Management** - Create, update, and manage subscription boxes
- **Subscription Monitoring** - Track all customer subscriptions and renewals
- **Delivery Management** - Monitor shipments and deliveries
- **User Management** - Manage customers and their accounts

### Technical Features
- **Modern UI** - Glassmorphism design with Tailwind CSS
- **Dark Mode** - Native dark mode support
- **Payment Processing** - Integrated Stripe payments via Laravel Cashier
- **PDF Invoices** - Automatic invoice generation via DomPDF
- **Email Notifications** - Real-time email notifications for all events
- **Role-based Authorization** - RBAC with policies for fine-grained access control
- **Type-safe Code** - Full PHP 8.3+ type hints and PHPStan compliance

## Technology Stack

- **Backend**: Laravel 10+
- **Database**: MySQL 8.0+
- **Frontend**: Blade templates with Tailwind CSS
- **Build Tool**: Vite
- **Payment**: Stripe (Laravel Cashier)
- **PHP Version**: 8.3+

## Installation

Refer to [INSTALLATION.md](INSTALLATION.md) for detailed setup instructions.

## Quick Start

```bash
# Install dependencies
composer install
npm install
npm run build

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate
php artisan db:seed

# Start development server
php artisan serve
```

## Test Accounts

- **Demo Account**: `demo@lynbox.com` / `demo123456`
- **Admin Account**: `admin@lynbox.com` / `admin123`
- **Premium Account**: `premium@lynbox.com` / `test123456`

## Project Structure

- `app/Models/` - Eloquent models
- `app/Services/` - Business logic services
- `app/Policies/` - Authorization policies
- `app/Http/Controllers/` - Request handlers
- `routes/` - Route definitions
- `resources/views/` - Blade templates
- `database/migrations/` - Database schema
- `database/seeders/` - Sample data

## API Endpoints

All endpoints are protected by authentication middleware. See `routes/api.php` for complete API documentation.

## License

This project is proprietary software.
