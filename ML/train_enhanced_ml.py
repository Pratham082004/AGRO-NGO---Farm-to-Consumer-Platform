import os
import pandas as pd
import numpy as np
import joblib
from sklearn.feature_extraction.text import TfidfVectorizer
from sklearn.preprocessing import OneHotEncoder
from sklearn.compose import ColumnTransformer
from sklearn.pipeline import Pipeline
from sklearn.ensemble import RandomForestRegressor

# Define paths
ML_DIR = os.path.dirname(os.path.abspath(__file__))
MODELS_DIR = os.path.join(ML_DIR, 'models')
os.makedirs(MODELS_DIR, exist_ok=True)

csv_path = os.path.join(ML_DIR, 'GFG1_realistic.csv')
if not os.path.exists(csv_path):
    csv_path = os.path.join(ML_DIR, 'GFG1.csv')

print(f"[+] Loading dataset from {csv_path}...")
df = pd.read_csv(csv_path)
df = df.dropna(subset=['item', 'day'])

# Train on 50,000 rows for high precision
df = df.head(50000).copy()
df['item'] = df['item'].astype(str)
df['day'] = pd.to_numeric(df['day'], errors='coerce').fillna(3)

# 1. Enrich data with categories, storage conditions, and processing state
def get_category(item_name):
    name = item_name.lower()
    if any(k in name for k in ['apple', 'mango', 'banana', 'orange', 'fruit', 'berry', 'grapes', 'plum', 'guava', 'papaya', 'pineapple']):
        return 3 # Fruits
    elif any(k in name for k in ['potato', 'tomato', 'onion', 'carrot', 'cabbage', 'cucumber', 'chilli', 'greens', 'gourd', 'brinjal', 'beet']):
        return 2 # Vegetables
    else:
        return 1 # Crops / Staples / Oils

df['product_cat'] = df['item'].apply(get_category)

# Assign storage conditions deterministically based on item properties
def get_storage(item_name):
    name = item_name.lower()
    if any(k in name for k in ['juice', 'jelly', 'milk', 'mushroom', 'berry', 'greens', 'ice']):
        return 'Refrigerated'
    elif any(k in name for k in ['apple', 'grapes', 'mango', 'tomato', 'meat', 'peas']):
        return 'Cold Storage'
    else:
        return 'Ambient'

df['storage_condition'] = df['item'].apply(get_storage)

# Assign processing state (0 = Raw, 1 = Processed)
def get_processed(item_name):
    name = item_name.lower()
    if any(k in name for k in ['juice', 'jelly', 'jam', 'pickle', 'oil', 'paste', 'powder', 'drink', 'pack']):
        return 1
    return 0

df['is_processed'] = df['item'].apply(get_processed)

print(f"[+] Dataset enriched with features: product_cat, storage_condition, is_processed.")

# -------------------------------------------------------------
# 2. Build & Train Multi-Parameter Expiry Prediction Pipeline
# -------------------------------------------------------------
print("[+] Training Enhanced Expiry Model...")

X_expiry = df[['item', 'product_cat', 'storage_condition', 'is_processed']]
y_expiry = df['day']

preprocessor_expiry = ColumnTransformer(
    transformers=[
        ('text', TfidfVectorizer(max_features=500), 'item'),
        ('cat', OneHotEncoder(handle_unknown='ignore'), ['product_cat', 'storage_condition', 'is_processed'])
    ]
)

expiry_pipeline = Pipeline([
    ('preprocessor', preprocessor_expiry),
    ('regressor', RandomForestRegressor(n_estimators=100, random_state=42))
])

expiry_pipeline.fit(X_expiry, y_expiry)

expiry_model_path = os.path.join(MODELS_DIR, 'expiry_model.joblib')
joblib.dump(expiry_pipeline, expiry_model_path)
print(f"[OK] Expiry Model saved to {expiry_model_path}")

# -------------------------------------------------------------
# 3. Build & Train Dynamic Price Recommendation Model
# -------------------------------------------------------------
print("[+] Training Dynamic Price Recommendation Model...")

# Generate realistic baseline price data based on crop types and stock levels
states = ['MAHARASHTRA', 'KARNATAKA', 'KERALA', 'GUJARAT', 'PUNJAB', 'TAMIL NADU']
districts = ['Thane', 'Nagpur', 'Pune', 'Belgaum', 'Surat', 'Ludhiana']

np.random.seed(42)
df['farmer_state'] = np.random.choice(states, size=len(df))
df['farmer_district'] = np.random.choice(districts, size=len(df))
df['product_stock'] = np.random.randint(50, 5000, size=len(df))
df['product_delivery'] = np.random.choice(['yes', 'no'], size=len(df))

# Base price calculation (Rs/kg)
def generate_base_price(row):
    base = 30
    if row['product_cat'] == 3: # Fruits
        base += 40
    elif row['product_cat'] == 2: # Vegetables
        base += 15
    if row['is_processed'] == 1:
        base += 50
    if row['storage_condition'] == 'Cold Storage':
        base += 20
    # Stock discount factor
    stock_factor = max(0.8, 1.2 - (row['product_stock'] / 10000))
    return int(max(10, base * stock_factor + np.random.normal(0, 5)))

df['product_price'] = df.apply(generate_base_price, axis=1)

X_price = df[['item', 'product_cat', 'storage_condition', 'is_processed', 'farmer_state', 'product_stock', 'product_delivery']]
y_price = df['product_price']

preprocessor_price = ColumnTransformer(
    transformers=[
        ('text', TfidfVectorizer(max_features=500), 'item'),
        ('cat', OneHotEncoder(handle_unknown='ignore'), ['product_cat', 'storage_condition', 'is_processed', 'farmer_state', 'product_delivery']),
        ('num', 'passthrough', ['product_stock'])
    ]
)

price_pipeline = Pipeline([
    ('preprocessor', preprocessor_price),
    ('regressor', RandomForestRegressor(n_estimators=100, random_state=42))
])

price_pipeline.fit(X_price, y_price)

price_model_path = os.path.join(MODELS_DIR, 'price_model.joblib')
joblib.dump(price_pipeline, price_model_path)
print(f"[OK] Price Recommendation Model saved to {price_model_path}")
print("[+] All ML Models Trained & Exported Successfully!")
