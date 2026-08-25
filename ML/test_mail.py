import os
import smtplib
from email.mime.multipart import MIMEMultipart
from email.mime.text import MIMEText
from dotenv import load_dotenv

# Load root .env file
ROOT_ENV = os.path.abspath(os.path.join(os.path.dirname(__file__), '..', '.env'))
load_dotenv(ROOT_ENV, override=True)

sender = os.getenv("SMTP_EMAIL", "prathampatil1771@gmail.com").strip()
receiver = os.getenv("RECIPIENT_EMAIL", "prathampatil.sit.comp@gmail.com").strip()
app_password = os.getenv("SMTP_PASSWORD", "").replace(" ", "").strip()
smtp_server = os.getenv("SMTP_SERVER", "smtp.gmail.com").strip()
smtp_port = int(os.getenv("SMTP_PORT", 465))

print(f"[*] Dispatching premium test email from {sender} to {receiver} via {smtp_server}:{smtp_port}...")

msg = MIMEMultipart('alternative')
msg['From'] = f"AgroNGO Marketplace <{sender}>"
msg['To'] = receiver
msg['Subject'] = "⚡ Short Shelf Clearance Deal: Ratnagiri Premium Alphonso Mangoes"

html = f"""
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {{ font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f3f4f6; margin: 0; padding: 24px 12px; }}
        .email-container {{ max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; overflow: hidden; border: 1px solid #e5e7eb; box-shadow: 0 10px 25px rgba(0,0,0,0.06); }}
        .email-header {{ background: linear-gradient(135deg, #16a34a 0%, #15803d 100%); padding: 32px 28px; text-align: center; color: #ffffff; }}
        .brand-pill {{ display: inline-block; background: rgba(255,255,255,0.2); padding: 4px 14px; border-radius: 20px; font-size: 13px; font-weight: 600; text-transform: uppercase; margin-bottom: 8px; }}
        .header-title {{ font-size: 26px; font-weight: 800; margin: 0; color: #ffffff; font-family: Georgia, serif; }}
        .email-body {{ padding: 32px 28px; }}
        .deal-card {{ background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; margin: 20px 0; }}
        .cta-btn {{ display: block; text-align: center; background: #16a34a; color: #ffffff !important; text-decoration: none; padding: 14px 28px; border-radius: 10px; font-weight: 700; font-size: 16px; margin-top: 24px; }}
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
            <span style="background:#fee2e2; color:#dc2626; padding:6px 12px; border-radius:20px; font-weight:700; font-size:12px; text-transform:uppercase;">🚨 Critical Expiry — 1 Day Left</span>
            
            <h2 style="font-size: 22px; color: #111827; margin-top: 16px; margin-bottom: 4px;">Ratnagiri Premium Alphonso Mangoes</h2>
            <p style="color: #4b5563; font-size: 14px; margin-top: 0;">Category: <strong>Fruits</strong> | Expiry Horizon: <strong>1 Day Left</strong></p>

            <div class="deal-card">
                <div style="font-size: 14px; color: #374151;">
                    <strong>Stock Available:</strong> 150 Dozen<br>
                    <strong>Recommended Deal:</strong> 25% Off Bulk Purchase (Ideal for Mango Puree & Desserts)
                </div>
            </div>

            <div class="farmer-info">
                <strong>👨‍🌾 Producer Details:</strong><br>
                Farmer: <strong>Rajesh Patil (Patil Agro Organic Farm)</strong><br>
                Contact / Hotline: <strong>9876543210</strong>
            </div>

            <a href="http://localhost/AgroNGO/BuyerPortal2/bhome.php" class="cta-btn">
                🛒 View & Purchase Harvest on AgroNGO
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

if smtp_port == 465:
    with smtplib.SMTP_SSL(smtp_server, smtp_port) as smtp:
        smtp.login(sender, app_password)
        smtp.send_message(msg)
else:
    with smtplib.SMTP(smtp_server, smtp_port) as smtp:
        smtp.starttls()
        smtp.login(sender, app_password)
        smtp.send_message(msg)

print("[SUCCESS] Premium email sent successfully!")