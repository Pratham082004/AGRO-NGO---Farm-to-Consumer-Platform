# 🌾 AgroNGO — Direct Farm-to-Consumer Marketplace with AI Insights

AgroNGO is a modern, full-stack web platform designed to bridge the gap between Indian farmers and consumers (buyers). By eliminating middleman exploitation, it empowers farmers to sell fresh produce directly at fair prices while offering consumers transparent, farm-fresh produce with integrated **Machine Learning shelf-life prediction** and **dynamic price recommendations**.

---

## ✨ Key Features & Modern Architecture

### 🎨 Modernized UI/UX Design System
- **Centralized Design Token System (`Styles/agronogo-design.css`)**: Built with Vanilla CSS, HSL color tokens, Google Fonts (`Inter` & `Outfit`), glassmorphism containers, smooth CSS keyframe animations, and custom badges.
- **Interactive JS Component Suite (`Styles/agronogo-components.js`)**: Scroll-reveal animations, sticky navbar state handlers, mobile navigation drawers, toast notification engine (`AgroToast`), password toggles, and quantity steppers.
- **Unified Navigation Components**: Reusable PHP header (`agro_navbar()`) and footer (`agro_footer()`) integrated across all public, buyer, farmer, and admin pages.
- **High-Contrast Dark Glass Authentication**: High-contrast dark glassmorphic cards (`rgba(17, 24, 39, 0.88)`) with crisp typography, field labels, and action buttons for Farmer, Buyer, and Admin sign-in.

---

### 🤖 Machine Learning Subsystem (`/ML` & `/models`)
- **Produce Shelf-Life Prediction (`expiry_model.joblib`)**: Random Forest model predicting freshness duration (in days) based on produce category, harvest date, temperature, and storage condition (Ambient, Cold Storage, Refrigerated).
- **Dynamic Price Recommendation (`price_model.joblib`)**: Machine learning model suggesting fair per-kg market pricing based on crop stock, state location, processing level, and seasonal demand.
- **PHP ORM Integration (`Includes/ORM.php`, `Includes/Models/AgroModels.php`)**: Object-Relational Mapping layer connecting PHP portal logic with Python ML inference.

---

### 🚜 Farmer Portal (`/FarmerPortal`)
- **Produce Management**: Upload fruits, vegetables, and crops with storage conditions and processing states.
- **AI Price Assistant**: Interactive `#suggestPriceBtn` on product upload (`InsertProduct.php`) predicting optimal market pricing.
- **Order Tracker & Transactions**: Visual order status timeline (Packing → Dispatched → In Transit → Delivered) and detailed transaction history.
- **Farmer Profile**: Verified details card with bank account info, PAN, address, and district location.

---

### 🛒 Buyer Portal (`/BuyerPortal2`)
- **Fresh Produce Marketplace (`bhome.php`)**: Browse fruits, vegetables, and grain crops with state/district filters and search.
- **Product Details (`productdetails.php`)**: 3-column view with produce photos, freshness indicators, fulfillment options, and direct farmer contact cards.
- **Shopping Cart (`cartpage.php`)**: Quantity stepper controls, item removal, and sticky order summary.
- **Checkout & Payments (`checkout.php`)**: Address verification, fulfillment selection (Farmer delivery, Buyer self-pickup, Courier), and payment options (Cash on Delivery, Paytm UPI).

---

### 🛡️ Admin Panel (`/admins`)
- **Admin Control Portal**: Modernized sign-in interface (`admins/index.php`) and administration panel for managing users, listings, and site analytics.

---

## 🛠️ Technology Stack

- **Frontend**: HTML5, Vanilla CSS3 (Custom Design System), JavaScript (ES6+), FontAwesome 6.5
- **Backend**: PHP 8+
- **Database**: MySQL (`impulse102` database)
- **Machine Learning**: Python 3.x, `scikit-learn`, `joblib`, `pandas`, `numpy`
- **Security**: AES-128-CTR password encryption with OpenSSL

---

## 🚀 Installation & Local Setup

1. **Prerequisites**:
   - Local web server environment like **XAMPP** (Apache + MySQL).
   - Python 3.8+ (for Machine Learning inference and retraining).

2. **Database Setup**:
   - Open phpMyAdmin (`http://localhost/phpmyadmin`).
   - Create a database named `impulse102`.
   - Import `AgroCraft.sql` (or `migrate_db.sql`) into the `impulse102` database.

3. **Repository Setup**:
   - Clone or extract this workspace into your local server root (e.g., `C:\xampp\htdocs\AgroNGO` or create a directory junction pointing to this repo).

4. **Database Configuration**:
   - Database credentials are managed in:
     - `Includes/db.php`
     - `Functions/functions.php`
     - `admins/includes/config.php`

5. **Access Application**:
   - Open browser and navigate to:
     - **Main Landing Page**: `http://localhost/AgroNGO/index.html`
     - **Farmer Login**: `http://localhost/AgroNGO/auth/FarmerLogin.php`
     - **Buyer Login**: `http://localhost/AgroNGO/auth/BuyerLogin.php`
     - **Admin Portal**: `http://localhost/AgroNGO/admins/index.php`

---

## 📁 Repository Directory Structure

```
agrnongo-pmp/
├── index.html                  # Upgraded Landing Page with Hero V2 & AI Banner
├── Styles/
│   ├── agronogo-design.css     # Centralized Design Tokens & UI Styles
│   └── agronogo-components.js  # Interactive JS Component Engine
├── Includes/
│   ├── components/
│   │   ├── navbar.php          # Shared Header Component (agro_navbar)
│   │   └── footer.php          # Shared Footer Component (agro_footer)
│   ├── ORM.php                 # PHP Database ORM Layer
│   └── db.php                  # MySQL Database Connection
├── auth/                       # Auth Pages (FarmerLogin, BuyerLogin, Registers, Forgot Passwords)
├── FarmerPortal/               # Farmer Dashboard, Product Management, Orders & Profile
├── BuyerPortal2/               # Buyer Marketplace, Cart, Checkout & Product Details
├── admins/                     # Admin Control Portal
└── ML/                         # Machine Learning Pipeline & Trained Models
    ├── models/                 # Trained .joblib sklearn models
    ├── GFG1_realistic.csv      # Botanical Produce Dataset
    └── train_enhanced_ml.py    # Retraining Script
```

---

© 2026 AgroNGO — Direct Farm-to-Consumer Platform. All rights reserved.
