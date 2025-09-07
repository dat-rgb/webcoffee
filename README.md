## Features
- Online ordering and order status tracking.
- Menu management (add, edit, delete).
- Integrated shopping cart and payment via PayOS.
- Customer & staff account management.
- Intuitive dashboard for shop management.
...

## Installation
# 1. Clone repo
git clone https://github.com/dat-rgb/webcoffee.git

# 2. Install dependencies
composer install
npm install && npm run dev

# 3. Configure environment
cp .env.example .env
php artisan key:generate

# 4. Setup database
php artisan migrate --seed

# 5. Run the server
php artisan serve
