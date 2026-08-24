<?php
require_once __DIR__ . '/../ORM.php';

class ProductModel extends Model {
    protected static string $table = 'products';
    protected static string $primaryKey = 'product_id';
}

class FarmerModel extends Model {
    protected static string $table = 'farmerregistration';
    protected static string $primaryKey = 'farmer_id';
}

class BuyerModel extends Model {
    protected static string $table = 'buyerregistration';
    protected static string $primaryKey = 'buyer_id';
}

class CategoryModel extends Model {
    protected static string $table = 'categories';
    protected static string $primaryKey = 'cat_id';
}

class OrderModel extends Model {
    protected static string $table = 'orders';
    protected static string $primaryKey = 'order_id';
}

class CartModel extends Model {
    protected static string $table = 'cart';
    protected static string $primaryKey = 'product_id';
}
