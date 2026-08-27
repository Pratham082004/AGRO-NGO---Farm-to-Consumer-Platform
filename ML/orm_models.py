import os
from sqlalchemy import create_engine, Column, Integer, String, Text, BigInteger, SmallInteger
from sqlalchemy.orm import declarative_base, sessionmaker

Base = declarative_base()

class Product(Base):
    __tablename__ = 'products'

    product_id = Column(Integer, primary_key=True, autoincrement=True)
    farmer_fk = Column(Integer, nullable=False)
    product_title = Column(String(100), nullable=False)
    product_cat = Column(String(100), nullable=False)
    product_type = Column(String(100), nullable=False)
    product_expiry = Column(String(25), nullable=False)
    product_image = Column(Text, nullable=False)
    product_stock = Column(Integer, nullable=False)
    product_price = Column(Integer, nullable=False)
    product_desc = Column(Text, nullable=False)
    product_keywords = Column(Text, nullable=False)
    product_delivery = Column(String(5), nullable=False)
    storage_condition = Column(String(50), default='Ambient')
    is_processed = Column(SmallInteger, default=0)

class FarmerRegistration(Base):
    __tablename__ = 'farmerregistration'

    farmer_id = Column(Integer, primary_key=True, autoincrement=True)
    farmer_name = Column(String(255), nullable=False)
    farmer_phone = Column(BigInteger, nullable=False, unique=True)
    farmer_address = Column(Text, nullable=False)
    farmer_state = Column(String(50), nullable=False)
    farmer_district = Column(String(50), nullable=False)
    farmer_pan = Column(String(10), nullable=False)
    farmer_bank = Column(BigInteger, nullable=False)
    farmer_password = Column(String(100), nullable=False)

class BuyerRegistration(Base):
    __tablename__ = 'buyerregistration'

    buyer_id = Column(Integer, primary_key=True, autoincrement=True)
    buyer_name = Column(String(30), nullable=False)
    buyer_phone = Column(BigInteger, nullable=False, unique=True)
    buyer_addr = Column(Text, nullable=False)
    buyer_mail = Column(String(50), nullable=False)
    buyer_username = Column(String(20), nullable=False, unique=True)
    buyer_password = Column(String(100), nullable=False)

class Category(Base):
    __tablename__ = 'categories'

    cat_id = Column(Integer, primary_key=True, autoincrement=True)
    cat_title = Column(String(100), nullable=False)

def get_db_session(db_name=None, host=None, user=None, password=None):
    db_name = db_name or os.getenv("DB_NAME", "impulse102")
    host = host or os.getenv("DB_HOST", "127.0.0.1")
    user = user or os.getenv("DB_USER", "root")
    password = password if password is not None else os.getenv("DB_PASS", "")
    db_url = f"mysql+pymysql://{user}:{password}@{host}/{db_name}"
    engine = create_engine(db_url, echo=False, pool_pre_ping=True)
    Session = sessionmaker(bind=engine)
    return Session()
