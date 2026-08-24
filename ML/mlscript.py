import os
import sys
from datetime import datetime
import smtplib
from email.mime.multipart import MIMEMultipart 
from email.mime.text import MIMEText 
from email.mime.base import MIMEBase 
from email import encoders
from mimetypes import guess_type

# Optional Third-Party Packages with Graceful Fallbacks
try:
    import pandas as pd
except ImportError:
    pd = None

try:
    import numpy as np
except ImportError:
    np = None

try:
    import pymysql as mdb
except ImportError:
    try:
        import mysql.connector as mdb
    except ImportError:
        mdb = None

try:
    from dotenv import load_dotenv
    load_dotenv(os.path.join(os.path.dirname(__file__), '.env'))
except ImportError:
    pass

try:
    import joblib
except ImportError:
    joblib = None

ML_DIR = os.path.dirname(os.path.abspath(__file__))
MODEL_PATH = os.path.join(ML_DIR, 'models', 'expiry_model.joblib')

# 1. Load Pre-trained Model or Fallback Pipeline
expiry_model = None
if os.path.exists(MODEL_PATH) and joblib is not None:
    try:
        expiry_model = joblib.load(MODEL_PATH)
        print("[+] Loaded pre-trained multi-parameter Expiry Model.")
    except Exception as e:
        print(f"[-] Warning: Failed to load ML model: {e}")

# 2. Email Dispatch Function
def send_mail(to_email, product_name, category, image_path):
    fromaddr = os.getenv("SMTP_EMAIL", "pranavagone080304@gmail.com")
    password = os.getenv("SMTP_PASSWORD", "mpct qlci ines uexr")
    smtp_server = os.getenv("SMTP_SERVER", "smtp.gmail.com")
    smtp_port = int(os.getenv("SMTP_PORT", 587))

    msg = MIMEMultipart('related')
    msg['From'] = fromaddr
    msg['To'] = to_email
    msg['Subject'] = f"Expiry Alert: {product_name}"

    html = f"""
    <html>
      <body>
        <h2 style="color: #2E86C1;">AgroNGO Expiry Alert</h2>
        <p>Product <strong>{product_name}</strong> (Category: <em>{category}</em>) is nearing expiry within the next 5 days.</p>
    """

    if image_path and os.path.isfile(image_path):
        html += f'<img src="cid:productimage" alt="{product_name}" style="width:300px; border:1px solid #ddd; padding:5px;"/>'
    else:
        html += "<p><i>[Image not available]</i></p>"

    html += "</body></html>"
    msg.attach(MIMEText(html, 'html'))

    if image_path and os.path.isfile(image_path):
        mime_type, _ = guess_type(image_path)
        main_type, sub_type = mime_type.split('/') if mime_type else ('image', 'jpeg')
        with open(image_path, 'rb') as img_file:
            mime_img = MIMEBase(main_type, sub_type)
            mime_img.set_payload(img_file.read())
            encoders.encode_base64(mime_img)
            mime_img.add_header('Content-ID', '<productimage>')
            mime_img.add_header('Content-Disposition', 'inline', filename=os.path.basename(image_path))
            msg.attach(mime_img)

    try:
        s = smtplib.SMTP(smtp_server, smtp_port)
        s.starttls()
        s.login(fromaddr, password)
        s.sendmail(fromaddr, to_email, msg.as_string())
        s.quit()
        print(f"  [OK] Mail sent to {to_email}")
    except Exception as e:
        print(f"  [FAIL] Mail sending failed: {e}")

# 3. Main Expiry Audit Logic
def run_expiry_audit():
    today = datetime.today().date()
    date_format = "%Y-%m-%d"

    db_host = os.getenv("DB_HOST", "127.0.0.1")
    db_user = os.getenv("DB_USER", "root")
    db_pass = os.getenv("DB_PASS", "")
    db_name = os.getenv("DB_NAME", "impulse102")

    if mdb is None:
        print("[-] Note: MySQL driver (pymysql / mysql.connector) not available in environment.")
        return

    # Fetch emails once to prevent redundant DB connections
    email_list = []
    try:
        db2 = mdb.connect(host=db_host, user=db_user, password=db_pass, database="tms2")
        c2 = db2.cursor()
        c2.execute("SELECT comm FROM churn")
        email_list = [row[0] for row in c2.fetchall() if row[0]]
        db2.close()
    except Exception as e:
        print(f"[-] Note: Could not fetch subscriber emails from tms2: {e}")

    try:
        mydb = mdb.connect(host=db_host, user=db_user, password=db_pass, database=db_name)
        mycursor = mydb.cursor()

        # Check if columns exist
        mycursor.execute("SHOW COLUMNS FROM products LIKE 'storage_condition'")
        has_extra_cols = mycursor.fetchone() is not None

        if has_extra_cols:
            mycursor.execute("SELECT product_id, product_title, product_expiry, product_cat, product_image, storage_condition, is_processed FROM products")
        else:
            mycursor.execute("SELECT product_id, product_title, product_expiry, product_cat, product_image FROM products")

        products = mycursor.fetchall()
        mydb.close()

        base_img_folder = os.path.abspath(os.path.join(ML_DIR, '..', 'Admin', 'product_images'))

        print("=" * 60)
        print(f"  AgroNGO Expiry Audit - {today}")
        print("=" * 60)

        for p in products:
            p_id, p_title, p_expiry, p_cat, p_img = p[0], str(p[1]), str(p[2]), str(p[3]), str(p[4])
            storage = str(p[5]) if has_extra_cols and len(p) > 5 and p[5] else 'Ambient'
            is_proc = int(p[6]) if has_extra_cols and len(p) > 6 and p[6] is not None else 0

            # Predict shelf life using ML model
            predicted_day = 3 # default
            if expiry_model is not None and pd is not None:
                try:
                    df_in = pd.DataFrame([{
                        'item': p_title,
                        'product_cat': int(p_cat) if p_cat.isdigit() else 1,
                        'storage_condition': storage,
                        'is_processed': is_proc
                    }])
                    predicted_day = int(round(float(expiry_model.predict(df_in)[0])))
                except Exception as ex:
                    pass

            try:
                exp_date = datetime.strptime(p_expiry, date_format).date()
                days_to_expiry = (exp_date - today).days

                print(f"Product: {p_title:<20} | Expiry: {p_expiry} | Days Left: {days_to_expiry:>2} | Predicted Shelf: {predicted_day}d")

                if 0 <= days_to_expiry <= 5 and email_list:
                    print(f"  [!] Alerting {len(email_list)} subscribers for {p_title}...")
                    img_path = os.path.join(base_img_folder, p_img) if p_img else None
                    if img_path and not os.path.isfile(img_path):
                        img_path = None

                    for email in email_list:
                        send_mail(email, p_title, p_cat, img_path)

            except Exception as e:
                print(f"  [-] Date parsing error for '{p_title}' ({p_expiry}): {e}")

    except Exception as e:
        print(f"[-] Database Error: {e}")

if __name__ == "__main__":
    run_expiry_audit()
