# AgroNGO

AgroNGO is a comprehensive web-based platform designed to bridge the gap between farmers and consumers (buyers). By cutting out the middlemen, it allows farmers to sell their agricultural products directly to consumers at fair prices, ensuring profitability for farmers and fresh produce for buyers.

## Features

### 🚜 Farmer Portal
- **Direct Selling:** Farmers can upload their crops, vegetables, and fruits directly to the marketplace.
- **Order Management:** View and manage incoming orders and transactions from buyers.
- **Machine Learning Integration:** Includes predictive ML models to assist farmers with crop recommendations and yield predictions.
- **Secure Registration:** Secure authentication and profile management for farmers.

### 🛒 Buyer Portal
- **Marketplace:** Consumers and wholesale buyers can browse a wide variety of fresh produce directly from farmers.
- **Shopping Cart:** Add products to a cart, adjust quantities, and save items for later.
- **Direct Communication:** Get farmer details and communicate with them directly.
- **Search & Filter:** Search for specific crops, filter by category, or find products by state and district.

### ⚙️ Admin Dashboard
- **Platform Management:** Admins can oversee all users (buyers and farmers).
- **Product Oversight:** Manage listed products, categories, and site activity.
- **Data & Analytics:** Monitor transactions, users, and overall platform health.

## Technology Stack

- **Frontend:** HTML5, CSS3, JavaScript, Bootstrap 4
- **Backend:** PHP 8+
- **Database:** MySQL
- **Machine Learning / Scripts:** Python (scikit-learn, pandas, etc.)
- **Security:** AES-128-CTR encryption for passwords

## Installation & Setup

1. **Prerequisites:**
   - Install a local server environment like XAMPP or MAMP (which includes Apache and MySQL).
   - Python 3.x (if you plan to run the ML scripts).

2. **Database Setup:**
   - Open phpMyAdmin (usually `http://localhost/phpmyadmin`).
   - Create a new database named `impulse102`.
   - Import the `AgroCraft.sql` file provided in the root directory into the `impulse102` database.

3. **Project Setup:**
   - Clone or extract this repository into your local server's document root (e.g., `htdocs` for XAMPP).
   - Ensure the folder is named `AgroNGO`.

4. **Configuration:**
   - The database configuration files are located at:
     - `Includes/db.php`
     - `Admin/includes/db.php`
     - `admins/includes/config.php`
   - Ensure the credentials match your local MySQL setup (default for XAMPP is usually `root` with no password).

5. **Run the Application:**
   - Open your web browser and navigate to `http://localhost/AgroNGO/`.
   - You can access the Buyer Portal, Farmer Portal, and Admin Dashboard from the main homepage.

## File Structure Highlights

- `/BuyerPortal2` & `/BuyerPortal` - Contains all frontend and backend logic for the buyer's shopping experience.
- `/FarmerPortal` - Dashboard and product management for farmers.
- `/admins` & `/Admin` - Administrative control panel.
- `/ML` - Python scripts for machine learning features (e.g., `mlscript.py`).
- `/auth` - Handles user registration and secure login.
- `/Includes` & `/Functions` - Core PHP logic, database connection, and reusable functions.

## Developed In
Developed in 2026. All rights reserved.
