import os
import sys
import json
import argparse
import urllib.request
import urllib.error

OLLAMA_API_URL = os.getenv("OLLAMA_API_URL", "http://localhost:11434/api/generate")
DEFAULT_MODEL = os.getenv("OLLAMA_MODEL", "llama3")

def get_fallback_recommendation(item, cat, expiry, stock, storage, state, price):
    """
    Intelligent Agricultural Rule-Based Advisory Engine (Fallback when Ollama LLM is offline)
    """
    item_lower = str(item).lower()
    expiry = int(expiry)
    stock = float(stock)
    price = float(price)

    # Determine urgency level
    if expiry <= 3:
        urgency = "CRITICAL"
        urgency_color = "danger"
        discount_pct = 20 if expiry <= 2 else 15
    elif expiry <= 7:
        urgency = "WARNING"
        urgency_color = "warning"
        discount_pct = 10
    else:
        urgency = "SAFE"
        urgency_color = "success"
        discount_pct = 0

    clearance_price = round(price * (1 - discount_pct / 100.0), 2)

    # Categorize item dynamics
    if any(k in item_lower for k in ['banana', 'mango', 'papaya', 'strawberry', 'berry', 'orange', 'grape', 'apple', 'fruit']):
        if expiry <= 3:
            buyers = [
                {
                    "name": "Juice Vendors & Smoothie Outlets",
                    "fit_score": 95,
                    "reason": f"{item} nearing end of shelf-life has peak sugar concentration (high brix), perfect for fresh juices, smoothies, and milkshakes."
                },
                {
                    "name": "Bakeries & Confectionery Shops",
                    "fit_score": 88,
                    "reason": f"Soft, ripe {item} is ideal for fruit breads, pastries, jam toppings, and natural flavorings."
                },
                {
                    "name": "Puree & Fruit Pulp Processing Mills",
                    "fit_score": 82,
                    "reason": "Commercial pulping units buy short-expiry fruits in bulk at discounted rates for immediate batch freezing."
                }
            ]
            pitch = f"Urgent Sale: High-sugar fresh {item} ({stock} kg available) expiring in {expiry} days. Ideal for immediate juicing and bakery use. Offered at ₹{clearance_price}/kg ({discount_pct}% clearance discount)."
            insight = f"At {expiry} days remaining, {item} enters peak flavor ripeness. Direct offloading to juice vendors avoids complete spoilage while securing instant cash flow."
        elif expiry <= 7:
            buyers = [
                {
                    "name": "Local Retail Supermarkets & Greengrocers",
                    "fit_score": 92,
                    "reason": "Optimal 4-7 day window for shelf placement, home consumption, and daily retail sales."
                },
                {
                    "name": "Hotels, Restaurants & Catering (HoReCa)",
                    "fit_score": 85,
                    "reason": "Steady demand for fresh breakfast fruit platters and kitchen prep."
                },
                {
                    "name": "Juice Bars & Smoothie Chains",
                    "fit_score": 80,
                    "reason": "Good stock reserve for 3-5 days of high-volume drink preparation."
                }
            ]
            pitch = f"Fresh harvest {item} ({stock} kg) with {expiry} days prime shelf life. Great for daily retail distribution at ₹{clearance_price}/kg."
            insight = f"Product is in good condition. Target HoReCa and local retailers for fast turnover without deep discounting."
        else:
            buyers = [
                {
                    "name": "Wholesale Produce Distributors & Mandis",
                    "fit_score": 95,
                    "reason": "Long remaining shelf life allows long-distance transport and bulk wholesale distribution."
                },
                {
                    "name": "Export & Regional Supermarket Supply Chains",
                    "fit_score": 90,
                    "reason": "High shelf stability suitable for cold chain logistics and premium retail packaging."
                }
            ]
            pitch = f"Premium grade {item} with {expiry}+ days shelf life. Ready for bulk wholesale or regional dispatch."
            insight = f"Maximum shelf life stability. Command full market value (₹{price}/kg) with wholesale distributors."

    elif any(k in item_lower for k in ['tomato', 'spinach', 'lettuce', 'mushroom', 'cauliflower', 'cabbage', 'vegetable', 'veg']):
        if expiry <= 3:
            buyers = [
                {
                    "name": "Sauce, Ketchups & Pickle Manufacturers",
                    "fit_score": 94,
                    "reason": f"Ripe {item} is ideal for immediate cooking, tomato paste, ketchup, or vegetable puree processing."
                },
                {
                    "name": "Local Restaurants, Dhabas & Canteens",
                    "fit_score": 89,
                    "reason": "High daily vegetable consumption kitchens can consume bulk quantities within 24-48 hours."
                },
                {
                    "name": "Dehydration & Drying Units",
                    "fit_score": 80,
                    "reason": "Veggie drying plants process short-expiry vegetables into dried flakes and powders."
                }
            ]
            pitch = f"Clearance Offer: Fresh {item} ({stock} kg) expiring in {expiry} days. Ideal for restaurant cooking and sauce production. Price: ₹{clearance_price}/kg ({discount_pct}% off)."
            insight = f"Vegetables lose moisture rapidly after day 3. Selling to high-consumption kitchens or sauce makers eliminates waste."
        else:
            buyers = [
                {
                    "name": "Fresh Vegetable Markets & Retail Vendors",
                    "fit_score": 93,
                    "reason": "Crisp texture and fresh quality ideal for direct consumer sale."
                },
                {
                    "name": "Institutional Canteens & Hostel Messes",
                    "fit_score": 86,
                    "reason": "Bulk weekly food preparation for student and worker canteens."
                }
            ]
            pitch = f"Freshly harvested {item} ({stock} kg) with {expiry} days freshness guarantee."
            insight = "Stable produce stock. Maintain standard pricing with local retail partners."

    else:
        # General / Grain / Dairy / Other produce fallback
        if expiry <= 3:
            buyers = [
                {
                    "name": "Food Processing Units & Value-Add Mills",
                    "fit_score": 90,
                    "reason": "Fast conversion into processed goods before expiry."
                },
                {
                    "name": "Local Discount Clearance Outlets",
                    "fit_score": 85,
                    "reason": "Quick turnover discount sales to budget-conscious buyers."
                },
                {
                    "name": "Animal Feed & Organic Compost Units",
                    "fit_score": 75,
                    "reason": "Secondary buyer channel if human consumption window closes."
                }
            ]
            pitch = f"Urgent clearance for {item} ({stock} kg). Expiry in {expiry} days. Offered at ₹{clearance_price}/kg for quick offload."
            insight = f"Short shelf life remaining. Prioritize local fast-turnover buyers."
        else:
            buyers = [
                {
                    "name": "Standard Agricultural Wholesalers",
                    "fit_score": 92,
                    "reason": "Regular market distribution channel."
                },
                {
                    "name": "Direct Consumer Marketplace",
                    "fit_score": 88,
                    "reason": "Direct online listing for local buyers."
                }
            ]
            pitch = f"Quality {item} ({stock} kg) with {expiry} days shelf life available for purchase."
            insight = "Normal inventory condition. Standard market pricing applies."

    return {
        "status": "success",
        "engine": "fallback_rules",
        "item": item,
        "expiry_days": expiry,
        "stock_kg": stock,
        "original_price": price,
        "urgency_level": urgency,
        "urgency_color": urgency_color,
        "discount_percent": discount_pct,
        "recommended_clearance_price": clearance_price,
        "target_buyers": buyers,
        "actionable_pitch": pitch,
        "shelf_life_insight": insight
    }

def query_ollama(item, cat, expiry, stock, storage, state, price, model_name):
    prompt = f"""You are an expert Agricultural & Produce Supply-Chain AI Advisor for AgroNGO.
A farmer has produce with the following details:
- Item Name: {item}
- Category: {cat}
- Days until Expiry: {expiry} days
- Available Stock: {stock} kg
- Storage Condition: {storage}
- Location: {state}
- Base Price: ₹{price}/kg

Your goal is to suggest the BEST target buyer categories to sell to RIGHT NOW to avoid spoilage loss and maximize farmer earnings.
For example, if bananas expire in 3 days, suggest Juice Vendors, Smoothie Shops, Bakeries, or Puree Mills with urgent clearance pricing.

Return ONLY a raw valid JSON object (no markdown, no wrap formatting, no conversational text) with this exact structure:
{{
  "urgency_level": "CRITICAL" | "WARNING" | "SAFE",
  "urgency_color": "danger" | "warning" | "success",
  "discount_percent": integer (e.g. 20),
  "recommended_clearance_price": float (e.g. 32.0),
  "target_buyers": [
    {{
      "name": "Buyer Category Name",
      "fit_score": 95,
      "reason": "Detailed explanation why this buyer category is optimal for this decay/expiry stage."
    }}
  ],
  "actionable_pitch": "A 2-sentence marketing pitch script the farmer can use to contact these buyers.",
  "shelf_life_insight": "Agricultural science insight explaining produce behavior at this expiry stage."
}}"""

    payload = {
        "model": model_name,
        "prompt": prompt,
        "stream": False,
        "format": "json"
    }

    data = json.dumps(payload).encode('utf-8')
    req = urllib.request.Request(
        OLLAMA_API_URL,
        data=data,
        headers={"Content-Type": "application/json"}
    )

    try:
        with urllib.request.urlopen(req, timeout=8) as response:
            if response.status == 200:
                res_body = json.loads(response.read().decode('utf-8'))
                raw_response = res_body.get('response', '{}')
                parsed = json.loads(raw_response)

                parsed["status"] = "success"
                parsed["engine"] = f"ollama_{model_name}"
                parsed["item"] = item
                parsed["expiry_days"] = int(expiry)
                parsed["stock_kg"] = float(stock)
                parsed["original_price"] = float(price)

                if "target_buyers" in parsed and "urgency_level" in parsed:
                    return parsed
    except Exception as e:
        # Silently log/ignore and fall back
        pass

    return None

def get_recommendation(item="Bananas", cat="Fruits", expiry=3, stock=200, storage="Ambient", state="Maharashtra", price=40, model=DEFAULT_MODEL):
    # Try Ollama first
    result = query_ollama(item, cat, expiry, stock, storage, state, price, model)
    if result:
        return result

    # Fallback to rules engine
    return get_fallback_recommendation(item, cat, expiry, stock, storage, state, price)

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="AgroNGO Ollama Target Buyer Advisory System")
    parser.add_argument("--item", type=str, default="Bananas")
    parser.add_argument("--cat", type=str, default="Fruits")
    parser.add_argument("--expiry", type=int, default=3)
    parser.add_argument("--stock", type=float, default=200.0)
    parser.add_argument("--storage", type=str, default="Ambient")
    parser.add_argument("--state", type=str, default="MAHARASHTRA")
    parser.add_argument("--price", type=float, default=40.0)
    parser.add_argument("--model", type=str, default=DEFAULT_MODEL)

    args = parser.parse_args()
    res = get_recommendation(args.item, args.cat, args.expiry, args.stock, args.storage, args.state, args.price, args.model)
    print(json.dumps(res, indent=2))
