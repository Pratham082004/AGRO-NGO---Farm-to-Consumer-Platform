import os
import json
import argparse
import joblib
import pandas as pd

ML_DIR = os.path.dirname(os.path.abspath(__file__))
MODEL_PATH = os.path.join(ML_DIR, 'models', 'price_model.joblib')

def predict_price(item, cat, storage, processed, state, stock, delivery):
    if not os.path.exists(MODEL_PATH):
        # Fallback heuristic if model file doesn't exist yet
        base_price = 35 if cat == 1 else (50 if cat == 2 else 75)
        if processed:
            base_price += 40
        if storage == 'Cold Storage':
            base_price += 15
        return {
            "status": "fallback",
            "recommended_price": base_price,
            "price_min": max(10, base_price - 5),
            "price_max": base_price + 10
        }

    try:
        pipeline = joblib.load(MODEL_PATH)
        input_df = pd.DataFrame([{
            'item': str(item),
            'product_cat': int(cat),
            'storage_condition': str(storage),
            'is_processed': int(processed),
            'farmer_state': str(state),
            'product_stock': float(stock),
            'product_delivery': str(delivery)
        }])

        predicted_price = float(pipeline.predict(input_df)[0])
        rec_price = int(round(predicted_price))
        return {
            "status": "success",
            "recommended_price": rec_price,
            "price_min": max(10, rec_price - 5),
            "price_max": rec_price + 10
        }
    except Exception as e:
        return {
            "status": "error",
            "message": str(e),
            "recommended_price": 40
        }

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="AgroNGO Dynamic Price Recommendation CLI")
    parser.add_argument("--item", type=str, default="Fresh Produce")
    parser.add_argument("--cat", type=int, default=2)
    parser.add_argument("--storage", type=str, default="Ambient")
    parser.add_argument("--processed", type=int, default=0)
    parser.add_argument("--state", type=str, default="MAHARASHTRA")
    parser.add_argument("--stock", type=float, default=500.0)
    parser.add_argument("--delivery", type=str, default="yes")

    args = parser.parse_args()
    result = predict_price(args.item, args.cat, args.storage, args.processed, args.state, args.stock, args.delivery)
    print(json.dumps(result))
