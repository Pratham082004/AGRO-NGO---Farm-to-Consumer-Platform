# AgroNGO API & Integration Reference

This document provides technical details for HTTP endpoints, Machine Learning CLI tools, internal PHP ORM methods, and helper modules within the AgroNGO platform.

---

## 1. HTTP REST & JSON Endpoints

### Target Buyer AI Advisory API

Endpoint Path: `/FarmerPortal/api_buyer_recommendation.php`  
HTTP Methods: `GET`, `POST`  
Content-Type: `application/json`

#### Description
Generates tailored commercial buyer channel recommendations, clearance price suggestions, and marketing pitch scripts based on produce parameters. Accepts either a database `product_id` or explicit produce property fields.

#### Request Parameters

| Parameter | Type | Required | Default | Description |
| :--- | :--- | :--- | :--- | :--- |
| `product_id` | Integer | Optional | null | Database ID of the produce listing. If provided, overrides manual fields. |
| `item` | String | Optional | "Bananas" | Produce item title (e.g. "Tomatoes", "Mangoes"). |
| `cat` | String | Optional | "Fruits" | Category title (e.g. "Fruits", "Vegetables"). |
| `expiry` | Integer | Optional | 3 | Estimated days until produce expiry. |
| `stock` | Float | Optional | 200.0 | Available stock volume in kilograms. |
| `storage` | String | Optional | "Ambient" | Storage condition ("Ambient", "Cold Storage", "Refrigerated"). |
| `state` | String | Optional | "MAHARASHTRA" | State location of the farm. |
| `price` | Float | Optional | 40.0 | Base price per kilogram in INR. |

#### Response Schema

```json
{
  "status": "success",
  "engine": "ollama_llama3 | fallback_rules | php_emergency_fallback",
  "item": "Bananas",
  "expiry_days": 3,
  "stock_kg": 200.0,
  "original_price": 40.0,
  "urgency_level": "CRITICAL | WARNING | SAFE",
  "urgency_color": "danger | warning | success",
  "discount_percent": 20,
  "recommended_clearance_price": 32.0,
  "target_buyers": [
    {
      "name": "Juice Vendors & Smoothie Outlets",
      "fit_score": 95,
      "reason": "Bananas nearing end of shelf-life have peak sugar concentration, perfect for fresh juices and milkshakes."
    }
  ],
  "actionable_pitch": "Urgent Sale: High-sugar fresh Bananas (200 kg available) expiring in 3 days. Offered at 32/kg (20% clearance discount).",
  "shelf_life_insight": "At 3 days remaining, Bananas enter peak flavor ripeness. Direct offloading avoids spoilage loss."
}
```

#### Example Usage

Using cURL:
```bash
curl -X GET "http://localhost/AgroNGO/FarmerPortal/api_buyer_recommendation.php?product_id=12"
```

Using JavaScript (fetch):
```javascript
fetch('/AgroNGO/FarmerPortal/api_buyer_recommendation.php?item=Tomatoes&expiry=2&stock=150&price=30')
  .then(response => response.json())
  .then(data => {
    console.log("Urgency Level:", data.urgency_level);
    console.log("Clearance Price:", data.recommended_clearance_price);
    console.log("Target Buyers:", data.target_buyers);
  });
```

---

## 2. Machine Learning CLI Interfaces

### 2.1 Dynamic Price Recommendation CLI

Script Path: `ML/predict_price.py`  
Interpreter: Python 3.8+

#### Description
Invokes the Scikit-Learn price regression pipeline (`ML/models/price_model.joblib`) to estimate optimal produce pricing per kilogram.

#### Command Line Arguments

| Argument | Type | Default | Description |
| :--- | :--- | :--- | :--- |
| `--item` | String | "Fresh Produce" | Item name string. |
| `--cat` | Integer | 2 | Numeric category ID (1: Grains, 2: Fruits, 3: Vegetables). |
| `--storage` | String | "Ambient" | Storage condition ("Ambient", "Cold Storage"). |
| `--processed` | Integer | 0 | Binary flag indicating value-added processing (0: Raw, 1: Processed). |
| `--state` | String | "MAHARASHTRA" | State location identifier. |
| `--stock` | Float | 500.0 | Stock quantity in kilograms. |
| `--delivery` | String | "yes" | Delivery option availability ("yes", "no"). |

#### Execution Example

```bash
python ML/predict_price.py --item "Alphonso Mango" --cat 2 --storage "Cold Storage" --processed 0 --state "MAHARASHTRA" --stock 300 --delivery "yes"
```

#### Output Schema

```json
{
  "status": "success",
  "recommended_price": 65,
  "price_min": 60,
  "price_max": 75
}
```

#### Fallback Behavior
If `price_model.joblib` is missing, the script executes a heuristic pricing calculation based on category baselines and storage adjustments:
```json
{
  "status": "fallback",
  "recommended_price": 50,
  "price_min": 45,
  "price_max": 60
}
```

---

### 2.2 Target Buyer Advisory CLI

Script Path: `ML/recommend_buyer_ollama.py`  
Interpreter: Python 3.8+

#### Description
Queries a local Ollama LLM endpoint or runs embedded domain-specific agricultural decision rules to identify high-fit commercial buyer categories.

#### Command Line Arguments

| Argument | Type | Default | Description |
| :--- | :--- | :--- | :--- |
| `--item` | String | "Bananas" | Produce name. |
| `--cat` | String | "Fruits" | Produce category. |
| `--expiry` | Integer | 3 | Remaining days to expiry. |
| `--stock` | Float | 200.0 | Available stock in kilograms. |
| `--storage` | String | "Ambient" | Storage environment. |
| `--state` | String | "MAHARASHTRA" | Farm location state. |
| `--price` | Float | 40.0 | Base market price per kg. |
| `--model` | String | "llama3" | Ollama model name identifier. |

#### Environment Variables

| Variable | Default | Description |
| :--- | :--- | :--- |
| `OLLAMA_API_URL` | `http://localhost:11434/api/generate` | HTTP endpoint for Ollama generation service. |
| `OLLAMA_MODEL` | `llama3` | Default Ollama model name. |

#### Execution Example

```bash
python ML/recommend_buyer_ollama.py --item "Tomatoes" --expiry 2 --stock 500 --price 25
```

---

### 2.3 Expiry Audit & Email Clearance Dispatcher CLI

Script Path: `ML/mlscript.py`  
Interpreter: Python 3.8+

#### Description
Scans all active product listings in the database, executes shelf-life inference using `expiry_model.joblib`, identifies produce nearing expiry (0-5 days remaining), and dispatches HTML clearance emails to registered buyers via SMTP.

#### Environment Configuration (`.env` or system variables)

| Parameter | Default | Description |
| :--- | :--- | :--- |
| `DB_HOST` | `127.0.0.1` | MySQL server host. |
| `DB_USER` | `root` | Database username. |
| `DB_PASS` | `""` | Database password. |
| `DB_NAME` | `impulse102` | Database name. |
| `SMTP_SERVER` | `smtp.gmail.com` | SMTP mail server address. |
| `SMTP_PORT` | `587` | SMTP port (587 for TLS, 465 for SSL). |
| `SMTP_EMAIL` | `""` | Sender email address. |
| `SMTP_PASSWORD` | `""` | App-specific SMTP authentication password. |

#### Execution Example

```bash
python ML/mlscript.py
```

#### Admin Integration
Called asynchronously from the Admin Portal using PHP `proc_open` (`admins/runml.php`), streaming console output to the web interface in real time.

---

## 3. Internal PHP Active Record ORM API

Defined in `Includes/ORM.php` and `Includes/Models/AgroModels.php`.

### Database Class (`Database`)

#### `getConnection(): PDO`
Returns a shared singleton PDO database connection instance.

```php
$pdo = Database::getConnection();
```

---

### Model Abstract Class (`Model`)

Base class inherited by `ProductModel`, `FarmerModel`, `BuyerModel`, `CategoryModel`, `OrderModel`, and `CartModel`.

#### Static Methods

##### `find(mixed $id): ?static`
Fetches a single record by its primary key.

```php
$product = ProductModel::find(12);
if ($product) {
    echo $product->product_title;
}
```

##### `where(string $field, mixed $value): array`
Fetches all records matching a specific field condition.

```php
$freshProducts = ProductModel::where('storage_condition', 'Cold Storage');
```

##### `all(): array`
Fetches all records from the target table.

```php
$categories = CategoryModel::all();
```

##### `create(array $data): ?static`
Inserts a new record into the database and returns the model instance.

```php
$newFarmer = FarmerModel::create([
    'farmer_name' => 'Ramesh Patil',
    'farmer_phone' => '9876543210',
    'farmer_email' => 'ramesh@example.com',
    'farmer_state' => 'MAHARASHTRA',
    'farmer_district' => 'NASHIK'
]);
```

#### Instance Methods

##### `save(): bool`
Updates an existing record if the primary key exists, or inserts a new record if it does not.

```php
$product = ProductModel::find(15);
$product->product_price = 45.00;
$product->save();
```

##### `delete(): bool`
Deletes the record corresponding to the primary key.

```php
$product = ProductModel::find(20);
if ($product) {
    $product->delete();
}
```

---

## 4. PHP Integration Helper API

Defined in `Includes/OllamaAdvisor.php`.

### `OllamaAdvisor::getRecommendations(array $productData): array`

Static wrapper function that accepts produce data arrays, executes `ML/recommend_buyer_ollama.py` via `shell_exec`, and parses the JSON result. Includes a PHP fallback method `getPhpEmergencyFallback()` if shell execution fails.

```php
require_once __DIR__ . '/Includes/OllamaAdvisor.php';

$recommendation = OllamaAdvisor::getRecommendations([
    'item' => 'Mangoes',
    'cat' => 'Fruits',
    'expiry' => 4,
    'stock' => 100,
    'storage' => 'Ambient',
    'state' => 'MAHARASHTRA',
    'price' => 80
]);

echo $recommendation['actionable_pitch'];
```

---

## 5. E-Commerce Cart & Action Endpoints

Located in `BuyerPortal2/`. These endpoints handle session-authenticated buyer shopping operations.

| Endpoint | Method | Parameter | Description |
| :--- | :--- | :--- | :--- |
| `AddQty.php` | `GET` | `id` (product_id) | Increments item quantity in the active cart by 1. |
| `MinusQty.php` | `GET` | `id` (product_id) | Decrements item quantity in cart by 1 (minimum 1). |
| `DeleteProductCart.php` | `GET` | `id` (product_id) | Removes specific product item from cart. |
| `emptyCart.php` | `GET` / `POST` | None | Flushes all cart items belonging to the current session user. |
| `saveforlater.php` | `GET` / `POST` | `id` (product_id) | Moves an item from cart to saved-for-later status. |
