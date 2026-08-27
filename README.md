# AgroNGO — Direct Farm-to-Consumer Marketplace with AI Insights

AgroNGO is a full-stack web application designed to connect Indian farmers directly with commercial and individual consumers (buyers). By eliminating middleman exploitation, the platform empowers agricultural producers to sell fresh produce directly at fair prices while providing consumers with transparent access to farm-fresh produce backed by machine learning shelf-life predictions and dynamic price recommendations.

---

## Table of Contents

- [Documentation Links](#documentation-links)
- [Core Features & System Architecture](#core-features--system-architecture)
- [Technology Stack](#technology-stack)
- [Installation & Local Setup](#installation--local-setup)
- [Directory Structure](#directory-structure)
- [License & Copyright](#license--copyright)

---

## Documentation Links

For in-depth technical documentation, system design diagrams, and API specifications, refer to:

- [System Architecture](architecture.md) — Comprehensive guide to portal subsystems, data access layers, Active Record ORM design, security model, and machine learning components.
- [API & Integration Reference](api_reference.md) — REST endpoint documentation, Python ML CLI parameters, Active Record ORM syntax, and internal helper APIs.

---

## Core Features & System Architecture

### 1. Modernized Design Tokens & Component Engine
- Centralized Styling System (`Styles/agronogo-design.css`): Pure Vanilla CSS system utilizing HSL color variables, modern typography (Inter and Outfit fonts from Google Fonts), responsive layout grids, glassmorphism cards, and custom badges.
- Component Engine (`Styles/agronogo-components.js`): Provides interactive client-side behaviors including scroll animations, sticky header handlers, mobile navigation drawers, toast notifications (`AgroToast`), password visibility toggles, and quantity adjustment controls.
- Reusable Layout Headers and Footers: Reusable PHP components (`agro_navbar()` and `agro_footer()`) integrated across public, farmer, buyer, and admin pages.
- High-Contrast Authentication (`/auth`): High-contrast glassmorphic login and registration panels for Farmer, Buyer, and Admin access.

### 2. Machine Learning & Intelligent Advisory Subsystem (`/ML`)
- Produce Shelf-Life Prediction (`ML/models/expiry_model.joblib`): Scikit-Learn Random Forest regressor predicting freshness duration based on crop category, harvest date, storage condition (Ambient, Cold Storage, Refrigerated), and processing state.
- Dynamic Price Recommendation (`ML/models/price_model.joblib`): Machine learning pricing pipeline offering optimal market rate recommendations based on stock volume, state location, processing level, and storage method.
- Target Buyer AI Advisory Engine (`Includes/OllamaAdvisor.php` & `ML/recommend_buyer_ollama.py`): Connects to local Ollama LLM (`llama3`) or runs embedded domain-specific decision rules to recommend targeted commercial buyers (juice stalls, bakeries, puree processors, sauce plants) for short-horizon produce.
- PHP Active Record ORM (`Includes/ORM.php`, `Includes/Models/AgroModels.php`): Object-Relational Mapping engine connecting PHP portal interfaces with MySQL database models and Python ML scripts.

### 3. Farmer Portal (`/FarmerPortal`)
- Listing Management: Upload and update crop catalog items with storage conditions, harvest dates, and processing states.
- AI Price Assistant: Interactive price suggestion tool on product entry (`InsertProduct.php`) generating real-time market pricing recommendations.
- Order & Transaction Tracker: Order status timeline (Packing -> Dispatched -> In Transit -> Delivered) and transaction history logging.
- Farmer Profile: Profile view containing verified contact details, bank credentials, PAN, address, and district location.

### 4. Buyer Portal (`/BuyerPortal2`)
- Produce Marketplace (`bhome.php`, `products.php`): Browse fruits, vegetables, and grain crops with state and district filtering.
- Product Detail Inspection (`productdetails.php`): Multi-column layout featuring produce photos, freshness duration indicators, fulfillment options, and direct farmer details.
- Cart & Multi-Fulfillment Checkout (`cartpage.php`, `checkout.php`): Shopping cart management with interactive quantity steppers, fulfillment selection (Farmer Delivery, Buyer Self-Pickup, Courier Dispatch), and payment processing (Cash on Delivery, Paytm UPI).

### 5. Admin Panel (`/admins`)
- Administration Suite: Secured control portal (`admins/index.php`) and administration dashboard (`dashboard.php`) for monitoring platform activity, managing user credentials, reviewing product listings, and resolving issues.
- AI Expiry Audit Console (`admins/runml.php`): Real-time web terminal executing batch shelf-life audits and sending HTML email clearance alerts to registered buyers.

---

## Technology Stack

- Frontend: HTML5, Vanilla CSS3 (Custom Design Token System), JavaScript (ES6+), FontAwesome 6.5
- Backend: PHP 8.0+ (Procedural and Active Record ORM)
- Database: MySQL (`impulse102` database)
- Machine Learning: Python 3.8+, Scikit-Learn, Joblib, Pandas, NumPy, PyMySQL
- Artificial Intelligence: Ollama LLM integration (`llama3` model) with fallback agricultural rules engine
- Security: AES-128-CTR password encryption via OpenSSL, prepared PDO statements

---

## Installation & Local Setup

### 1. Prerequisites
- Web Server: Local web server environment such as XAMPP (Apache + MySQL) with PHP 8.0 or higher.
- Python Environment: Python 3.8+ for machine learning execution and automated audits.

### 2. Database Setup
1. Open phpMyAdmin (`http://localhost/phpmyadmin`) or your MySQL client.
2. Create a database named `impulse102`.
3. Import `AgroCraft.sql` (or `migrate_db.sql`) into the `impulse102` database.

### 3. Repository Setup
1. Clone or copy this repository into your local web server root directory (e.g., `C:\xampp\htdocs\AgroNGO` or equivalent directory junction).

### 4. Configuration
Database parameters are defined in:
- `Includes/db.php`
- `Includes/env.php`
- `Functions/functions.php`
- `admins/includes/config.php`
- `ML/.env`

### 5. Access Application
Navigate to the following URLs in your web browser:
- Main Landing Page: `http://localhost/AgroNGO/index.html`
- Farmer Sign-In: `http://localhost/AgroNGO/auth/FarmerLogin.php`
- Buyer Sign-In: `http://localhost/AgroNGO/auth/BuyerLogin.php`
- Admin Portal: `http://localhost/AgroNGO/admins/index.php`

---

## Directory Structure

```
agrnongo-pmp/
├── index.html                  # Main Landing Page
├── README.md                   # Project Overview & Quick Start
├── architecture.md             # Detailed Architecture Specification
├── api_reference.md            # API & Integration Reference Manual
├── Styles/
│   ├── agronogo-design.css     # Design Tokens & Core CSS Framework
│   └── agronogo-components.js  # Client-Side Component Utilities
├── Includes/
│   ├── components/
│   │   ├── navbar.php          # Shared Header Component (agro_navbar)
│   │   └── footer.php          # Shared Footer Component (agro_footer)
│   ├── Models/
│   │   └── AgroModels.php      # Active Record Domain Models
│   ├── ORM.php                 # PHP PDO Active Record ORM Layer
│   ├── OllamaAdvisor.php       # PHP Advisory Bridge for Python / Ollama
│   ├── db.php                  # MySQL Connection Script
│   └── env.php                 # Environment Configuration Handler
├── Functions/
│   └── functions.php           # Helper Functions & Business Logic
├── auth/                       # Farmer & Buyer Authentication Modules
├── FarmerPortal/               # Farmer Dashboard, Listing Management, Orders, Profile
├── BuyerPortal2/               # Buyer Marketplace, Cart, Checkout, Saved Items
├── admins/                     # Admin Portal, Dashboard, Order Audit, AI Console
└── ML/                         # Machine Learning Models & Inference Scripts
    ├── models/                 # Joblib Trained Pipeline Models
    │   ├── expiry_model.joblib # Shelf-Life Estimation Model
    │   └── price_model.joblib  # Dynamic Pricing Model
    ├── GFG1_realistic.csv      # Botanical Produce Dataset
    ├── predict_price.py        # Dynamic Pricing Inference CLI
    ├── recommend_buyer_ollama.py # Buyer Advisory LLM & Rules Script
    ├── mlscript.py             # Batch Expiry Audit & Email Dispatcher
    └── train_enhanced_ml.py    # Model Retraining Script
```

---

## License & Copyright

Copyright (c) 2026 AgroNGO Direct Farm-to-Consumer Platform. All rights reserved.
