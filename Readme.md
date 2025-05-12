# WebStarter Plan Payment Application

This is a PHP-based web application that allows users to manage website subscriptions and payments. The app integrates with Paystack for secure payment processing.

## Features

- Search for website subscriptions by domain.
- View subscription details, including the owner and payment amount.
- Make secure payments using Paystack.
- Receive instant confirmation of successful payments.

## Prerequisites

- PHP 7.4 or higher
- Composer
- MySQL
- A web server (e.g., Apache or Nginx)

## Installation

1. Clone the repository:

   ```bash
   git clone https://github.com/your-repo/webstarter-payment.git
   cd webstarter-payment

2. Install dependencies using composer:
```
composer install
```

3. Set up the database:
  - Create a MySQL database (e.g., `nexora`)
  - Import the `database.sql` file into your database:
  ```
  mysql -u your_username
  ```
4. Configure environment variable:
  - Copy the `env.example` file to `.env`:
  ```
  cp .env.example .env
  ```