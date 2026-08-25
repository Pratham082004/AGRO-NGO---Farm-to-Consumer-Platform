import os
import pymysql

def _load_env(filepath):
    if os.path.exists(filepath):
        with open(filepath, 'r', encoding='utf-8') as f:
            for line in f:
                line = line.strip()
                if line and not line.startswith('#') and '=' in line:
                    k, v = line.split('=', 1)
                    k = k.strip()
                    v = v.strip().strip("'").strip('"')
                    if k:
                        os.environ[k] = v

_load_env(os.path.abspath(os.path.join(os.path.dirname(__file__), '..', '.env')))

db_host = os.getenv("DB_HOST", "127.0.0.1")
db_user = os.getenv("DB_USER", "root")
db_pass = os.getenv("DB_PASS", "")
db_name = os.getenv("DB_NAME", "impulse102")

print("=" * 60)
print("  AgroNGO Database Seeder")
print("=" * 60)
print(f"[*] Connecting to MySQL Database: {db_name} at {db_host}...")

try:
    conn = pymysql.connect(host=db_host, user=db_user, password=db_pass, database=db_name, autocommit=True)
    cur = conn.cursor()

    # 1. Truncate / Clear target tables safely (Leaving admin untouched)
    print("[*] Clearing existing farmer, product, and buyer tables...")
    cur.execute("SET FOREIGN_KEY_CHECKS = 0;")
    cur.execute("TRUNCATE TABLE farmerregistration;")
    cur.execute("TRUNCATE TABLE products;")
    cur.execute("TRUNCATE TABLE buyerregistration;")
    cur.execute("SET FOREIGN_KEY_CHECKS = 1;")
    print("    -> Cleared `farmerregistration`, `products`, and `buyerregistration`.")

    # Check farmerregistration columns
    cur.execute("SHOW COLUMNS FROM farmerregistration LIKE 'farmer_email'")
    if cur.fetchone() is None:
        cur.execute("ALTER TABLE farmerregistration ADD COLUMN farmer_email VARCHAR(100) DEFAULT NULL")

    cur.execute("SHOW COLUMNS FROM farmerregistration LIKE 'farmer_state'")
    has_farmer_state = cur.fetchone() is not None

    # 2. Seed Farmer Database
    print("[*] Seeding `farmerregistration` with 2 distinct emails...")
    # Password ciphertext for 'farmer123' via AES-128-CTR with key 'DE' and IV '2345678910111211' is 'nMPA4+0b4FMz'
    farmer_pass_cipher = 'nMPA4+0b4FMz'
    if has_farmer_state:
        sql_farmer = f"""
        INSERT INTO farmerregistration 
        (farmer_id, farmer_name, farmer_phone, farmer_address, farmer_state, farmer_district, farmer_pan, farmer_bank, farmer_password, farmer_email)
        VALUES 
        (1, 'Rajesh Patil (Patil Organic Farm)', '9876543210', 'Plot 42, Green Valley Farm, Nashik', 'MAHARASHTRA', 'Nashik', 'ABCDE1234F', 1234567890123456, '{farmer_pass_cipher}', 'prathampatil.sit.comp@gmail.com'),
        (2, 'Suresh Patil (Patil Agro Farm)', '9876543211', 'Kisan Colony, Ratnagiri', 'MAHARASHTRA', 'Ratnagiri', 'FGHIJ5678K', 9876543210987654, '{farmer_pass_cipher}', 'patilpratham1947@gmail.com');
        """
    else:
        sql_farmer = f"""
        INSERT INTO farmerregistration 
        (farmer_id, farmer_name, farmer_phone, farmer_address, farmer_pan, farmer_bank, farmer_password, farmer_conf_pswd, farmer_email)
        VALUES 
        (1, 'Rajesh Patil (Patil Organic Farm)', '9876543210', 'Plot 42, Green Valley Farm, Nashik', 'ABCDE1234F', 1234567890123456, '{farmer_pass_cipher}', '{farmer_pass_cipher}', 'prathampatil.sit.comp@gmail.com'),
        (2, 'Suresh Patil (Patil Agro Farm)', '9876543211', 'Kisan Colony, Ratnagiri', 'FGHIJ5678K', 9876543210987654, '{farmer_pass_cipher}', '{farmer_pass_cipher}', 'patilpratham1947@gmail.com');
        """
    cur.execute(sql_farmer)
    print("    -> Inserted 2 Farmer Accounts.")

    # 3. Seed Buyer Database
    print("[*] Seeding `buyerregistration` with 2 distinct emails...")
    # Expand buyer_mail column length to VARCHAR(100) to prevent truncation of long emails
    cur.execute("ALTER TABLE buyerregistration MODIFY COLUMN buyer_mail VARCHAR(100) NOT NULL;")

    # Password ciphertext for 'buyer123' via AES-128-CTR with key 'DE' and IV '2345678910111211' is 'mNfL6/pY41I='
    buyer_pass_cipher = 'mNfL6/pY41I='
    cur.execute("SHOW COLUMNS FROM buyerregistration LIKE 'buyer_comp'")
    has_buyer_comp = cur.fetchone() is not None

    if has_buyer_comp:
        sql_buyer = f"""
        INSERT INTO buyerregistration 
        (buyer_id, buyer_name, buyer_phone, buyer_addr, buyer_comp, buyer_license, buyer_bank, buyer_pan, buyer_mail, buyer_username, buyer_password)
        VALUES 
        (1, 'Pratham Patil', 9123456789, '101 Agro Towers, Pune', 'Patil Organic Wholesale', 'LIC-998877', 1122334455, 'PQRS1234T', 'prathampatil.sit.comp@gmail.com', 'buyer1', '{buyer_pass_cipher}'),
        (2, 'Patil Pratham', 9876123456, '45 Food Processing Zone, Mumbai', 'Metro Juice Co.', 'LIC-112233', 9988776655, 'UVWX5678Y', 'patilpratham1947@gmail.com', 'buyer2', '{buyer_pass_cipher}');
        """
    else:
        sql_buyer = f"""
        INSERT INTO buyerregistration 
        (buyer_id, buyer_name, buyer_phone, buyer_addr, buyer_mail, buyer_username, buyer_password)
        VALUES 
        (1, 'Pratham Patil', 9123456789, '101 Agro Towers, Pune', 'prathampatil.sit.comp@gmail.com', 'buyer1', '{buyer_pass_cipher}'),
        (2, 'Patil Pratham', 9876123456, '45 Food Processing Zone, Mumbai', 'patilpratham1947@gmail.com', 'buyer2', '{buyer_pass_cipher}');
        """
    cur.execute(sql_buyer)
    print("    -> Inserted 2 Buyer Accounts.")

    # 4. Seed Products Database with Nearly Expired Goods
    print("[*] Seeding `products` with short shelf-life / nearly expired produce...")
    cur.execute("SHOW COLUMNS FROM products LIKE 'storage_condition'")
    has_prod_extra = cur.fetchone() is not None

    if has_prod_extra:
        sql_products = """
        INSERT INTO products 
        (product_id, farmer_fk, product_title, product_cat, product_type, product_expiry, product_image, product_stock, product_price, product_desc, product_keywords, product_delivery, storage_condition, is_processed)
        VALUES 
        (1, 1, 'Ratnagiri Premium Alphonso Mangoes', '3', 'Mango', DATE_ADD(CURDATE(), INTERVAL 1 DAY), 'Mango.jpg', 1200, 180, 'GI certified premium Alphonso mangoes. Soft & sweet. Expiring in 1 day - ideal for juice and puree processors.', 'alphonso, mango, ratnagiri, fresh fruit', 'yes', 'Ambient', 0),
        (2, 1, 'Sweet Mahabaleshwar Strawberries', '3', 'Strawberry', DATE_ADD(CURDATE(), INTERVAL 1 DAY), 'strawberry.jpg', 350, 140, 'Grade A fresh strawberries from Mahabaleshwar. Critical 1-day expiry clearance sale.', 'strawberry, mahabaleshwar, fresh, berries', 'yes', 'Refrigerated', 0),
        (3, 1, 'Juicy Flame Seedless Green Grapes', '3', 'Grapes', DATE_ADD(CURDATE(), INTERVAL 3 DAY), 'Green Grapes.jpg', 2800, 65, 'Export quality seedless green grapes. Expiring in 3 days.', 'grapes, green grapes, seedless', 'yes', 'Cold Storage', 0),
        (4, 2, 'Organic Country Tomatoes (Desi)', '2', 'Tomato', DATE_ADD(CURDATE(), INTERVAL 2 DAY), 'Tomato.jpg', 1800, 18, 'Sun-ripened organic tomatoes. High pulp content. Expiring in 2 days - perfect for ketchup & puree.', 'tomato, organic, desi tomato', 'yes', 'Ambient', 0),
        (5, 2, 'Fresh Nashik Red Onions', '2', 'Onion', DATE_ADD(CURDATE(), INTERVAL 15 DAY), 'Onion.jpg', 4500, 24, 'High-grade Nashik red onions with long shelf storage.', 'onion, nashik red, vegetable', 'yes', 'Ambient', 0),
        (6, 1, 'Fresh Shimla Carrots', '2', 'Carrot', DATE_ADD(CURDATE(), INTERVAL 2 DAY), 'Carrot.jpg', 900, 32, 'Deep orange juicy sweet carrots. Expiring in 2 days - clearance rate.', 'carrot, fresh, root vegetable', 'yes', 'Refrigerated', 0),
        (7, 2, 'Sugar Ripe Yellow Bananas', '3', 'Bananas', DATE_ADD(CURDATE(), INTERVAL 1 DAY), 'Bananas.jpg', 950, 22, 'Grand Naine bananas at peak ripeness. Expiring in 1 day - ideal for bakeries and juice outlets.', 'banana, ripe, yellow, fruit', 'yes', 'Ambient', 0);
        """
    else:
        sql_products = """
        INSERT INTO products 
        (product_id, farmer_fk, product_title, product_cat, product_image, product_stock, product_price, product_desc, product_keywords, product_delivery)
        VALUES 
        (1, 1, 'Ratnagiri Premium Alphonso Mangoes', '3', 'Mango.jpg', 1200, 180, 'GI certified premium Alphonso mangoes. Soft & sweet. Expiring in 1 day - ideal for juice and puree.', 'alphonso, mango, ratnagiri', 'yes'),
        (2, 1, 'Sweet Mahabaleshwar Strawberries', '3', 'strawberry.jpg', 350, 140, 'Grade A fresh strawberries. Critical 1-day expiry clearance sale.', 'strawberry, mahabaleshwar', 'yes'),
        (3, 1, 'Juicy Flame Seedless Green Grapes', '3', 'Green Grapes.jpg', 2800, 65, 'Export quality seedless green grapes. Expiring in 3 days.', 'grapes, green grapes', 'yes'),
        (4, 2, 'Organic Country Tomatoes (Desi)', '2', 'Tomato.jpg', 1800, 18, 'Sun-ripened organic tomatoes. High pulp content. Expiring in 2 days.', 'tomato, organic', 'yes'),
        (5, 2, 'Fresh Nashik Red Onions', '2', 'Onion.jpg', 4500, 24, 'High-grade Nashik red onions.', 'onion, nashik red', 'yes'),
        (6, 1, 'Fresh Shimla Carrots', '2', 'Carrot.jpg', 900, 32, 'Deep orange juicy sweet carrots. Expiring in 2 days.', 'carrot, fresh', 'yes'),
        (7, 2, 'Sugar Ripe Yellow Bananas', '3', 'Bananas.jpg', 950, 22, 'Grand Naine bananas at peak ripeness. Expiring in 1 day.', 'banana, ripe, yellow', 'yes');
        """
    cur.execute(sql_products)
    print("    -> Inserted 7 Produce Listings with Nearly Expired Goods.")

    conn.commit()

    print("\n" + "=" * 60)
    print("  [SUCCESS] Database Seeding Completed Cleanly!")
    print("=" * 60)
    print("  FARMER 1 CREDENTIALS:")
    print("     * Email        : prathampatil.sit.comp@gmail.com")
    print("     * Phone Number : 9876543210")
    print("     * Farmer Name  : Rajesh Patil (Patil Organic Farm)")
    print("-" * 60)
    print("  FARMER 2 CREDENTIALS:")
    print("     * Email        : patilpratham1947@gmail.com")
    print("     * Phone Number : 9876543211")
    print("     * Farmer Name  : Suresh Patil (Patil Agro Farm)")
    print("=" * 60)

except Exception as e:
    print(f"\n[!] Error during database seeding: {e}")
