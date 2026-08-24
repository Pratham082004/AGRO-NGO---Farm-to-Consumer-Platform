import os
import pandas as pd
import numpy as np

ML_DIR = os.path.dirname(os.path.abspath(__file__))
input_csv = os.path.join(ML_DIR, 'GFG1.csv')
output_csv = os.path.join(ML_DIR, 'GFG1_realistic.csv')

print(f"[+] Reading base dataset: {input_csv}...")
df = pd.read_csv(input_csv)
df = df.dropna(subset=['item'])
df['item'] = df['item'].astype(str)

# Botanical shelf-life assignment logic (in days)
def assign_realistic_shelf_life(item_name):
    name = item_name.lower()
    
    # 1. Grains, Oils, Dry Fruits, Pickles & Processed Goods (90 - 365 days)
    if any(k in name for k in ['oil', 'pickle', 'dry fruit', 'dates', 'cashew', 'badami', 'pista', 'rice bran', 'copra', 'stevia']):
        return np.random.randint(120, 365)
    if any(k in name for k in ['jam', 'paste', 'honey', 'gulkan', 'chocolates']):
        return np.random.randint(90, 180)
        
    # 2. Tubers & Hard Vegetables (15 - 45 days)
    if any(k in name for k in ['potato', 'onion', 'garlic', 'ginger', 'yam', 'raddish', 'tamarind']):
        return np.random.randint(15, 45)
        
    # 3. Hardy Fruits (Apples, Citrus, Pomegranates, Melons) (10 - 25 days)
    if any(k in name for k in ['apple', 'orange', 'pomegranate', 'watermellon', 's.mellon', 'pomello', 'lemon', 'lime']):
        return np.random.randint(10, 25)
        
    # 4. Standard Fresh Produce (Tomatoes, Capsicum, Brinjal, Cucumbers, Carrots, Bananas, Mangoes) (5 - 12 days)
    if any(k in name for k in ['tomoto', 'tomato', 'capsicum', 'brinjal', 'cucumber', 'carrot', 'banana', 'mango', 'papaya', 'guava', 'chicco', 'sapota', 'sweet corn']):
        return np.random.randint(5, 12)
        
    # 5. Soft Fruits & Berries (3 - 7 days)
    if any(k in name for k in ['berry', 'straw berry', 'grapes', 'plum', 'peaches', 'litchi', 'cherry', 'kiwi', 'fig', 'anjura']):
        return np.random.randint(3, 7)
        
    # 6. Leafy Greens, Mushrooms & Perishables (2 - 5 days)
    if any(k in name for k in ['greens', 'leave', 'corriander', 'mint', 'palak', 'methi', 'mushroom', 'spinach', 'lettuce', 'juice', 'jelly', 'eggs']):
        return np.random.randint(2, 5)
        
    # Default fallback for general vegetables/crops
    return np.random.randint(5, 15)

print("[+] Applying realistic agricultural domain rules to produce items...")
np.random.seed(42)
df['shelf_life_days'] = df['item'].apply(assign_realistic_shelf_life)

# Keep standard schema
df_out = df[['item', 'shelf_life_days']].rename(columns={'shelf_life_days': 'day'})
df_out.to_csv(output_csv, index=False)

print(f"[OK] Realistic dataset created at: {output_csv}")
print(f"[+] Total records processed: {len(df_out)}")
print("[+] Sample realistic shelf lives:")
print(df_out.drop_duplicates(subset=['item']).head(15).to_string(index=False))
