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
    ROOT_ENV = os.path.abspath(os.path.join(os.path.dirname(__file__), '..', '.env'))
    load_dotenv(ROOT_ENV, override=True)
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

# 2. Email Dispatch Helper
def get_smtp_session():
    fromaddr = os.getenv("SMTP_EMAIL", "").strip()
    password = os.getenv("SMTP_PASSWORD", "").replace(" ", "").strip()
    smtp_server = os.getenv("SMTP_SERVER", "smtp.gmail.com").strip()
    smtp_port = int(os.getenv("SMTP_PORT", 587))

    if not fromaddr or not password:
        print("  [-] Warning: SMTP_EMAIL or SMTP_PASSWORD not configured in .env.")
        return None, fromaddr

    try:
        if smtp_port == 465:
            s = smtplib.SMTP_SSL(smtp_server, smtp_port, timeout=15)
        else:
            s = smtplib.SMTP(smtp_server, smtp_port, timeout=15)
            s.starttls()
        s.login(fromaddr, password)
        return s, fromaddr
    except smtplib.SMTPAuthenticationError as e:
        print(f"  [-] SMTP Authentication Error (535 Bad Credentials). Please update SMTP_PASSWORD in .env: {e}")
        return None, fromaddr
    except Exception as e:
        print(f"  [-] SMTP Connection Error: {e}")
        return None, fromaddr

def send_mail_with_session(s, fromaddr, to_email, product_name, category, days_left, farmer_name, farmer_phone, image_path):
    if not s or not fromaddr:
        return False

    try:
        msg = MIMEMultipart('related')
        msg['From'] = f"AgroNGO Clearance Alerts <{fromaddr}>"
        msg['To'] = to_email
        msg['Subject'] = f"⚡ Clearance Deal: {product_name} ({days_left} Days Remaining)"

        img_html = ""
        if image_path and os.path.isfile(image_path):
            img_html = f'<div style="text-align: center; margin-bottom: 20px;"><img src="cid:productimage" alt="{product_name}" style="width:100%; max-width:440px; height:240px; object-fit:cover; border-radius:12px; border:1px solid #e5e7eb;"/></div>'

        urgency_badge = '<span style="background:#fee2e2; color:#dc2626; padding:6px 12px; border-radius:20px; font-weight:700; font-size:12px; text-transform:uppercase;">🚨 Critical Expiry — Clearance Recommended</span>'
        if days_left > 3:
            urgency_badge = '<span style="background:#fef3c7; color:#d97706; padding:6px 12px; border-radius:20px; font-weight:700; font-size:12px; text-transform:uppercase;">⚠️ Short Shelf-Life Deal</span>'

        html = f"""
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <style>
                body {{ font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 24px 12px; -webkit-font-smoothing: antialiased; }}
                .email-container {{ max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e5e7eb; box-shadow: 0 10px 25px rgba(0,0,0,0.06); }}
                .email-header {{ background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); padding: 32px 28px; text-align: center; color: #ffffff; }}
                .brand-pill {{ display: inline-block; background: rgba(255,255,255,0.2); backdrop-filter: blur(4px); padding: 4px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }}
                .header-title {{ font-size: 26px; font-weight: 800; margin: 0; color: #ffffff; font-family: Georgia, serif; }}
                .email-body {{ padding: 32px 28px; }}
                .deal-card {{ background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin: 20px 0; }}
                .deal-grid {{ display: table; width: 100%; border-collapse: collapse; }}
                .deal-col {{ display: table-cell; vertical-align: top; width: 50%; padding-right: 10px; }}
                .deal-label {{ font-size: 12px; color: #6b7280; text-transform: uppercase; font-weight: 600; margin-bottom: 4px; }}
                .deal-val {{ font-size: 18px; color: #111827; font-weight: 800; }}
                .cta-btn {{ display: block; text-align: center; background: #16a34a; color: #ffffff !important; text-decoration: none; padding: 14px 28px; border-radius: 10px; font-weight: 700; font-size: 16px; margin-top: 24px; box-shadow: 0 4px 12px rgba(22, 163, 74, 0.3); }}
                .farmer-info {{ background: #eff6ff; border-left: 4px solid #3b82f6; padding: 14px; border-radius: 6px; font-size: 14px; color: #1e40af; margin-top: 20px; }}
                .email-footer {{ background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 20px; text-align: center; font-size: 12px; color: #9ca3af; line-height: 1.6; }}
            </style>
        </head>
        <body>
            <div class="email-container">
                <div class="email-header">
                    <span class="brand-pill">🌾 AgroNGO AI Expiry Engine</span>
                    <h1 class="header-title">Direct Harvest Clearance Deal</h1>
                </div>

                <div class="email-body">
                    {urgency_badge}
                    
                    <h2 style="font-size: 22px; color: #111827; margin-top: 16px; margin-bottom: 4px;">{product_name}</h2>
                    <p style="color: #4b5563; font-size: 14px; margin-top: 0;">Category: <strong>{category}</strong> | Expiry Horizon: <strong>{days_left} Days</strong></p>

                    {img_html}

                    <div class="deal-card">
                        <div class="deal-grid">
                            <div class="deal-col">
                                <div class="deal-label">Estimated Days Left</div>
                                <div class="deal-val" style="color: #dc2626;">{days_left} Days</div>
                            </div>
                            <div class="deal-col">
                                <div class="deal-label">Recommended Strategy</div>
                                <div class="deal-val" style="color: #16a34a;">Direct Bulk Clearance</div>
                            </div>
                        </div>
                    </div>

                    <div class="farmer-info">
                        <strong>👨‍🌾 Producer Details:</strong><br>
                        Farmer: <strong>{farmer_name}</strong><br>
                        Contact / Hotline: <strong>{farmer_phone}</strong>
                    </div>

                    <a href="http://localhost/AgroNGO/BuyerPortal2/bhome.php" class="cta-btn">
                        🛒 View & Order Harvest on AgroNGO
                    </a>
                </div>

                <div class="email-footer">
                    Sent via AgroNGO Short Shelf-Life Clearance Advisory Service.<br>
                    Connecting local growers directly with juice processors, bakeries, and wholesale buyers.<br>
                    &copy; 2026 AgroNGO Platform. All rights reserved.
                </div>
            </div>
        </body>
        </html>
        """

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

        s.sendmail(fromaddr, to_email, msg.as_string())
        print(f"  [OK] Alert sent to {to_email}")
        return True
    except Exception as e:
        print(f"  [FAIL] Failed sending to {to_email}: {e}")
        return False

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

    # Fetch registered buyer emails from main database
    email_list = []
    try:
        db_b = mdb.connect(host=db_host, user=db_user, password=db_pass, database=db_name)
        cb = db_b.cursor()
        cb.execute("SELECT buyer_mail FROM buyerregistration WHERE buyer_mail IS NOT NULL AND buyer_mail != ''")
        email_list = [row[0] for row in cb.fetchall() if row[0]]
        db_b.close()
    except Exception as e:
        print(f"[-] Note: Could not fetch buyer emails from {db_name}: {e}")

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

        smtp_session, fromaddr = get_smtp_session()

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

                if 0 <= days_to_expiry <= 5:
                    recipients = list(email_list)
                    farmer_name = "Local Farmer"
                    farmer_phone = "N/A"
                    
                    # Fetch owner farmer details
                    try:
                        f_db = mdb.connect(host=db_host, user=db_user, password=db_pass, database=db_name)
                        f_cur = f_db.cursor()
                        f_cur.execute("SELECT f.farmer_name, f.farmer_phone FROM farmerregistration f JOIN products p ON f.farmer_id = p.farmer_fk WHERE p.product_id = %s", (p_id,))
                        f_row = f_cur.fetchone()
                        f_db.close()
                        if f_row:
                            farmer_name, farmer_phone = f_row[0], str(f_row[1])
                            print(f"  [!] Expiring Harvest Owner: {farmer_name} (Tel: {farmer_phone})")
                    except Exception:
                        pass

                    if recipients:
                        if smtp_session:
                            print(f"  [!] Alerting {len(recipients)} subscribers for {p_title}...")
                            img_path = os.path.join(base_img_folder, p_img) if p_img else None
                            if img_path and not os.path.isfile(img_path):
                                img_path = None

                            for email in recipients:
                                send_mail_with_session(smtp_session, fromaddr, email, p_title, p_cat, days_to_expiry, farmer_name, farmer_phone, img_path)
                        else:
                            print(f"  [-] Skipped sending email alerts for '{p_title}' — SMTP connection/auth not active.")

            except Exception as e:
                print(f"  [-] Date parsing error for '{p_title}' ({p_expiry}): {e}")

        if smtp_session:
            try:
                smtp_session.quit()
            except Exception:
                pass

    except Exception as e:
        print(f"[-] Database Error: {e}")

if __name__ == "__main__":
    run_expiry_audit()
