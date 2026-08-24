<?php
/**
 * AgroNGO - Farmer Profile & Stock Seeder Script
 * Creates a complete farmer profile with 24+ realistic produce listings across fruits, vegetables, grains, and processed items.
 */

require_once __DIR__ . '/../Includes/db.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$farmerPhone = "9876543210";
$farmerName = "Rajesh Patil (Patil Agro Organic Farm)";
$farmerAddress = "Gat No. 142, Green Valley Estate, Dindori Road";
$farmerState = "MAHARASHTRA";
$farmerDistrict = "Nashik";
$farmerPan = "RJPAT9876F";
$farmerBank = 98765432;
$farmerPass = "yw=="; // Standard password hash in system

echo "<!DOCTYPE html><html><head><title>Seeder — AgroNGO</title>";
echo "<link rel='stylesheet' href='../Styles/agronogo-design.css'>";
echo "<style>body{font-family:sans-serif; padding: 40px; background:#f4f6f8;} .card{background:#fff; padding:30px; border-radius:12px; max-width:800px; margin:0 auto; box-shadow:0 4px 12px rgba(0,0,0,0.08);}</style></head><body>";
echo "<div class='card'>";
echo "<h2 style='color:#2e7d32;'>🌱 AgroNGO Database Seeder</h2>";

if (!$con) {
    echo "<div style='color:red;'>Database Connection Failed: " . mysqli_connect_error() . "</div>";
    echo "<p>Please ensure MySQL is running on localhost with database <code>impulse102</code>.</p>";
} else {
    // 0. Ensure Admin Table & Default Admin Account Exist
    $createAdminTbl = "CREATE TABLE IF NOT EXISTS `admin` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `UserName` varchar(100) NOT NULL,
      `Password` varchar(100) NOT NULL,
      `updationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    @mysqli_query($con, $createAdminTbl);

    $adminPassMd5 = md5('admin');
    $insAdmin = "INSERT INTO `admin` (`id`, `UserName`, `Password`) VALUES (1, 'admin', '$adminPassMd5')
                 ON DUPLICATE KEY UPDATE `Password` = '$adminPassMd5';";
    @mysqli_query($con, $insAdmin);

    // 1. Check or Insert Farmer
    $checkFarmer = "SELECT farmer_id FROM farmerregistration WHERE farmer_phone = '$farmerPhone'";
    $resFarmer = mysqli_query($con, $checkFarmer);

    if ($resFarmer && mysqli_num_rows($resFarmer) > 0) {
        $fRow = mysqli_fetch_assoc($resFarmer);
        $farmerId = $fRow['farmer_id'];
        echo "<p>✅ Existing Farmer Profile Found: <strong>$farmerName</strong> (ID: $farmerId, Phone: $farmerPhone)</p>";
    } else {
        $insFarmer = "INSERT INTO farmerregistration (farmer_name, farmer_phone, farmer_address, farmer_state, farmer_district, farmer_pan, farmer_bank, farmer_password) 
                      VALUES ('" . mysqli_real_escape_string($con, $farmerName) . "', 
                              '$farmerPhone', 
                              '" . mysqli_real_escape_string($con, $farmerAddress) . "', 
                              '$farmerState', 
                              '$farmerDistrict', 
                              '$farmerPan', 
                              '$farmerBank', 
                              '$farmerPass')";
        if (mysqli_query($con, $insFarmer)) {
            $farmerId = mysqli_insert_id($con);
            echo "<p>🎉 Created New Farmer Profile: <strong>$farmerName</strong> (ID: $farmerId, Phone: $farmerPhone)</p>";
        } else {
            echo "<p style='color:red;'>Error creating farmer: " . mysqli_error($con) . "</p>";
            exit;
        }
    }

    // Set session for immediate login preview
    $_SESSION['phonenumber'] = $farmerPhone;

    // Check columns of products table
    $hasStorageCol = false;
    $hasProcessedCol = false;
    $colRes = mysqli_query($con, "SHOW COLUMNS FROM products");
    if ($colRes) {
        while ($cRow = mysqli_fetch_assoc($colRes)) {
            if ($cRow['Field'] === 'storage_condition') $hasStorageCol = true;
            if ($cRow['Field'] === 'is_processed') $hasProcessedCol = true;
        }
    }

    if (!$hasStorageCol) {
        @mysqli_query($con, "ALTER TABLE products ADD COLUMN storage_condition VARCHAR(50) DEFAULT 'Ambient'");
    }
    if (!$hasProcessedCol) {
        @mysqli_query($con, "ALTER TABLE products ADD COLUMN is_processed TINYINT(1) DEFAULT 0");
    }

    // 2. Define 24 Detailed Stock Items
    $items = [
        [
            'title' => 'Ratnagiri Premium Alphonso Mangoes',
            'cat' => '3',
            'type' => 'Mango',
            'expiry' => date('Y-m-d', strtotime('+3 days')),
            'image' => 'Mango.jpg',
            'stock' => 1200,
            'price' => 180,
            'desc' => 'Geographical Indication (GI) certified premium Alphonso mangoes. Soft, aromatic, and rich in natural Brix sweetness. Expiring soon—ideal for fresh juice vendors and bakeries.',
            'keywords' => 'alphonso, mango, ratnagiri, fresh fruit, sweet',
            'delivery' => 'yes',
            'storage' => 'Ambient',
            'processed' => 0
        ],
        [
            'title' => 'Fresh Nashik Red Onions',
            'cat' => '2',
            'type' => 'Onion',
            'expiry' => date('Y-m-d', strtotime('+15 days')),
            'image' => 'Onion.jpg',
            'stock' => 4500,
            'price' => 24,
            'desc' => 'High-grade Nashik red onions with long shelf life, solid outer skins, and strong pungent flavor. Perfect for wholesale mandi buyers and restaurant chains.',
            'keywords' => 'onion, nashik red, vegetable, bulk stock',
            'delivery' => 'yes',
            'storage' => 'Ambient',
            'processed' => 0
        ],
        [
            'title' => 'Sweet Mahabaleshwar Strawberries',
            'cat' => '3',
            'type' => 'Strawberry',
            'expiry' => date('Y-m-d', strtotime('+2 days')),
            'image' => 'strawberry.jpg',
            'stock' => 350,
            'price' => 140,
            'desc' => 'Freshly handpicked Grade A strawberries from Mahabaleshwar plateau. Peak sweetness and vibrant crimson color. Urgent clearance sale.',
            'keywords' => 'strawberry, mahabaleshwar, fresh, berries',
            'delivery' => 'yes',
            'storage' => 'Refrigerated',
            'processed' => 0
        ],
        [
            'title' => 'Juicy Flame Seedless Green Grapes',
            'cat' => '3',
            'type' => 'Grapes',
            'expiry' => date('Y-m-d', strtotime('+5 days')),
            'image' => 'Green Grapes.jpg',
            'stock' => 2800,
            'price' => 65,
            'desc' => 'Crisp, seedless export-quality green grapes harvested from Nashik vineyards. Great balance of tartness and sugar.',
            'keywords' => 'grapes, green grapes, seedless, vineyard',
            'delivery' => 'yes',
            'storage' => 'Cold Storage',
            'processed' => 0
        ],
        [
            'title' => 'Organic Country Tomatoes (Desi)',
            'cat' => '2',
            'type' => 'Tomato',
            'expiry' => date('Y-m-d', strtotime('+3 days')),
            'image' => 'Tomato.jpg',
            'stock' => 1800,
            'price' => 18,
            'desc' => 'Sun-ripened organic tomatoes with high pulp content. Ideal for direct kitchen cooking, sauce manufacturers, and ketchup processing plants.',
            'keywords' => 'tomato, organic, desi tomato, sauce',
            'delivery' => 'yes',
            'storage' => 'Ambient',
            'processed' => 0
        ],
        [
            'title' => 'Kashmiri Crisp Royal Red Apples',
            'cat' => '3',
            'type' => 'Apple',
            'expiry' => date('Y-m-d', strtotime('+12 days')),
            'image' => 'Apple.jpg',
            'stock' => 1500,
            'price' => 110,
            'desc' => 'Cold-stored Kashmiri Red Delicious apples with high crunch factor and natural wax coat.',
            'keywords' => 'apple, kashmiri, red apple, fruit',
            'delivery' => 'no',
            'storage' => 'Cold Storage',
            'processed' => 0
        ],
        [
            'title' => 'Golden Sharbati Durum Wheat Grains',
            'cat' => '1',
            'type' => 'Wheat',
            'expiry' => date('Y-m-d', strtotime('+180 days')),
            'image' => 'Wheat.jpg',
            'stock' => 5000,
            'price' => 38,
            'desc' => 'Premium Sharbati wheat grains, sun-dried to optimal moisture (<10%). Excellent for soft rotis, flour mills, and grain trade.',
            'keywords' => 'wheat, sharbati, grain, crop, flour',
            'delivery' => 'yes',
            'storage' => 'Ambient',
            'processed' => 0
        ],
        [
            'title' => 'Aromatic Indrayani Basmati Rice',
            'cat' => '1',
            'type' => 'Rice',
            'expiry' => date('Y-m-d', strtotime('+240 days')),
            'image' => 'Rice.jpg',
            'stock' => 3200,
            'price' => 62,
            'desc' => 'Fragrant long-grain Indrayani rice aged for 1 year. Low starch, non-sticky cooking profile.',
            'keywords' => 'rice, basmati, indrayani, crop, grain',
            'delivery' => 'yes',
            'storage' => 'Ambient',
            'processed' => 0
        ],
        [
            'title' => 'Fresh Shimla Carrots',
            'cat' => '2',
            'type' => 'Carrot',
            'expiry' => date('Y-m-d', strtotime('+6 days')),
            'image' => 'Carrot.jpg',
            'stock' => 900,
            'price' => 32,
            'desc' => 'Deep orange, juicy sweet carrots washed and cleaned. Ideal for daily culinary use and halwa prep.',
            'keywords' => 'carrot, fresh, root vegetable, orange',
            'delivery' => 'yes',
            'storage' => 'Refrigerated',
            'processed' => 0
        ],
        [
            'title' => 'Fresh Green Cabbage Heads',
            'cat' => '2',
            'type' => 'Cabbage',
            'expiry' => date('Y-m-d', strtotime('+4 days')),
            'image' => 'Cabbage.jpg',
            'stock' => 1400,
            'price' => 15,
            'desc' => 'Tightly packed heads of fresh green cabbage harvested this week. Great for canteens, street food stalls, and salads.',
            'keywords' => 'cabbage, green, vegetable, leaf',
            'delivery' => 'yes',
            'storage' => 'Ambient',
            'processed' => 0
        ],
        [
            'title' => 'Organic Jyoti Potatoes',
            'cat' => '2',
            'type' => 'Potato',
            'expiry' => date('Y-m-d', strtotime('+20 days')),
            'image' => 'Potato.webp',
            'stock' => 6000,
            'price' => 20,
            'desc' => 'Smooth-skinned Kufri Jyoti potatoes, dry-cleaned and sorted by size. High solid content suitable for chips and daily cooking.',
            'keywords' => 'potato, organic, jyoti, vegetable',
            'delivery' => 'yes',
            'storage' => 'Ambient',
            'processed' => 0
        ],
        [
            'title' => 'Nagpur Juicy Sweet Oranges (Santra)',
            'cat' => '3',
            'type' => 'Orange',
            'expiry' => date('Y-m-d', strtotime('+7 days')),
            'image' => 'orange.jpg',
            'stock' => 2200,
            'price' => 45,
            'desc' => 'Direct from Nagpur orchards. Thin rind, full of juice and Vitamin C. Recommended for fresh beverage bars.',
            'keywords' => 'orange, nagpur, santra, fruit, juice',
            'delivery' => 'yes',
            'storage' => 'Ambient',
            'processed' => 0
        ],
        [
            'title' => 'Golden Sweet Corn Cobs',
            'cat' => '1',
            'type' => 'Maize',
            'expiry' => date('Y-m-d', strtotime('+5 days')),
            'image' => 'Maize.jpg',
            'stock' => 1600,
            'price' => 28,
            'desc' => 'Tender yellow sweet corn cobs in husk. High sugar yield, ready for boiling, roasting, or food processing.',
            'keywords' => 'maize, sweet corn, corn, crop',
            'delivery' => 'yes',
            'storage' => 'Ambient',
            'processed' => 0
        ],
        [
            'title' => 'Fresh Tender Coconut (Nariyal)',
            'cat' => '1',
            'type' => 'Coconut',
            'expiry' => date('Y-m-d', strtotime('+10 days')),
            'image' => 'Coconut.jpg',
            'stock' => 800,
            'price' => 35,
            'desc' => 'Green tender coconuts loaded with refreshing electrolyte water (400ml+ per nut). Direct from coastal plantations.',
            'keywords' => 'coconut, tender coconut, water, crop',
            'delivery' => 'no',
            'storage' => 'Ambient',
            'processed' => 0
        ],
        [
            'title' => 'Sugar Ripe Yellow Bananas',
            'cat' => '3',
            'type' => 'Bananas',
            'expiry' => date('Y-m-d', strtotime('+2 days')),
            'image' => 'Bananas.jpg',
            'stock' => 950,
            'price' => 22,
            'desc' => 'Grand Naine variety bananas at peak ripeness. High Brix sweetness, perfect for smoothie outlets and immediate retail sale.',
            'keywords' => 'banana, ripe, yellow, fruit',
            'delivery' => 'yes',
            'storage' => 'Ambient',
            'processed' => 0
        ],
        [
            'title' => 'Ripe Sweet Custard Apples (Sitaphal)',
            'cat' => '3',
            'type' => 'Custard Apple',
            'expiry' => date('Y-m-d', strtotime('+2 days')),
            'image' => 'custartapple.cms',
            'stock' => 400,
            'price' => 75,
            'desc' => 'Creamy, sweet Sitaphal pulp fruits harvested from dryland orchards. Urgent buyer needed for ice-cream and dessert processors.',
            'keywords' => 'custard apple, sitaphal, ripe fruit',
            'delivery' => 'yes',
            'storage' => 'Ambient',
            'processed' => 0
        ],
        [
            'title' => 'Artisanal Cold-Pressed Peanut Oil',
            'cat' => '1',
            'type' => 'Oil',
            'expiry' => date('Y-m-d', strtotime('+120 days')),
            'image' => '',
            'stock' => 500,
            'price' => 195,
            'desc' => 'Traditional Wood-Pressed (Kachi Ghani) pure groundnut oil without added chemicals or preservatives. Processed on farm.',
            'keywords' => 'oil, peanut oil, kachi ghani, processed',
            'delivery' => 'yes',
            'storage' => 'Ambient',
            'processed' => 1
        ],
        [
            'title' => 'Concentrated Organic Tomato Puree',
            'cat' => '2',
            'type' => 'Tomato',
            'expiry' => date('Y-m-d', strtotime('+90 days')),
            'image' => 'Tomato.jpg',
            'stock' => 750,
            'price' => 85,
            'desc' => 'Farm-processed thick tomato paste canned in food-grade tin containers. Ideal for hotel chains, pizzerias, and caterers.',
            'keywords' => 'tomato puree, paste, processed, vegetable',
            'delivery' => 'yes',
            'storage' => 'Ambient',
            'processed' => 1
        ],
        [
            'title' => 'Dehydrated Raw Mango Slices (Amchur)',
            'cat' => '3',
            'type' => 'Mango',
            'expiry' => date('Y-m-d', strtotime('+150 days')),
            'image' => 'Mango.jpg',
            'stock' => 300,
            'price' => 220,
            'desc' => 'Solar-dehydrated sour green mango slices. Used for dry mango powder processing and pickle flavoring.',
            'keywords' => 'amchur, dehydrated mango, processed, spice',
            'delivery' => 'yes',
            'storage' => 'Ambient',
            'processed' => 1
        ],
        [
            'title' => 'Fresh Red Bhagwa Pomegranates',
            'cat' => '3',
            'type' => 'Pomegranate',
            'expiry' => date('Y-m-d', strtotime('+10 days')),
            'image' => '',
            'stock' => 1100,
            'price' => 130,
            'desc' => 'Deep red Bhagwa variety pomegranates with soft seeds and high juice volume. Export quality.',
            'keywords' => 'pomegranate, bhagwa, red arils, fruit',
            'delivery' => 'yes',
            'storage' => 'Cold Storage',
            'processed' => 0
        ],
        [
            'title' => 'Fresh Green Spinach Bundles (Palak)',
            'cat' => '2',
            'type' => 'Spinach',
            'expiry' => date('Y-m-d', strtotime('+1 days')),
            'image' => '',
            'stock' => 250,
            'price' => 14,
            'desc' => 'Hydroponic & soil grown fresh green leafy spinach. Same-day harvest, zero chemical spray. CRITICAL URGENCY.',
            'keywords' => 'spinach, palak, leafy vegetable, fresh',
            'delivery' => 'yes',
            'storage' => 'Refrigerated',
            'processed' => 0
        ],
        [
            'title' => 'Organic Fresh Ginger Roots (Adrak)',
            'cat' => '2',
            'type' => 'Ginger',
            'expiry' => date('Y-m-d', strtotime('+25 days')),
            'image' => '',
            'stock' => 850,
            'price' => 90,
            'desc' => 'Firm, aromatic ginger rhizomes with low fibre and strong zest. High essential oil content.',
            'keywords' => 'ginger, adrak, spice, vegetable',
            'delivery' => 'yes',
            'storage' => 'Ambient',
            'processed' => 0
        ],
        [
            'title' => 'High-Curcumin Fresh Turmeric Finger Roots',
            'cat' => '1',
            'type' => 'Turmeric',
            'expiry' => date('Y-m-d', strtotime('+40 days')),
            'image' => '',
            'stock' => 1250,
            'price' => 70,
            'desc' => 'Waigaon variety raw turmeric fingers with 5.2% natural curcumin content. Ideal for extraction and powder milling.',
            'keywords' => 'turmeric, haldi, curcumin, crop',
            'delivery' => 'yes',
            'storage' => 'Ambient',
            'processed' => 0
        ],
        [
            'title' => 'Pure Organic Sugarcane Jaggery Powder (Gud)',
            'cat' => '1',
            'type' => 'Jaggery',
            'expiry' => date('Y-m-d', strtotime('+180 days')),
            'image' => '',
            'stock' => 1500,
            'price' => 58,
            'desc' => 'Chemical-free natural sugarcane jaggery powder produced on farm using traditional crushed cane juice boiling.',
            'keywords' => 'jaggery, gud, sugarcane, processed, organic',
            'delivery' => 'yes',
            'storage' => 'Ambient',
            'processed' => 1
        ]
    ];

    $insertedCount = 0;
    foreach ($items as $item) {
        $title     = mysqli_real_escape_string($con, $item['title']);
        $cat       = mysqli_real_escape_string($con, $item['cat']);
        $type      = mysqli_real_escape_string($con, $item['type']);
        $expiry    = mysqli_real_escape_string($con, $item['expiry']);
        $image     = mysqli_real_escape_string($con, $item['image']);
        $stock     = intval($item['stock']);
        $price     = intval($item['price']);
        $desc      = mysqli_real_escape_string($con, $item['desc']);
        $keywords  = mysqli_real_escape_string($con, $item['keywords']);
        $delivery  = mysqli_real_escape_string($con, $item['delivery']);
        $storage   = mysqli_real_escape_string($con, $item['storage']);
        $processed = intval($item['processed']);

        $insSql = "INSERT INTO products (farmer_fk, product_title, product_cat, product_type, product_expiry, product_image, product_stock, product_price, product_desc, product_keywords, product_delivery, storage_condition, is_processed) 
                   VALUES ('$farmerId', '$title', '$cat', '$type', '$expiry', '$image', '$stock', '$price', '$desc', '$keywords', '$delivery', '$storage', '$processed')";

        if (mysqli_query($con, $insSql)) {
            $insertedCount++;
        }
    }

    echo "<p>📦 Successfully seeded <strong>$insertedCount produce listings</strong> for farmer <strong>$farmerName</strong>!</p>";
    
    // Self-delete option
    if (isset($_GET['autodelete']) && $_GET['autodelete'] === '1') {
        @unlink(__FILE__);
        echo "<div style='margin:15px 0; padding:12px; background:#e8f5e9; border:1px solid #a5d6a7; border-radius:6px; color:#1b5e20;'>";
        echo "🗑️ <strong>Cleanup Complete:</strong> <code>seed_farmer_profile.php</code> has been automatically deleted from your server for security.";
        echo "</div>";
    }

    echo "<hr style='margin:20px 0;'>";
    echo "<div style='display:flex; flex-wrap:wrap; gap:12px; align-items:center;'>";
    echo "<a href='farmerHomepage.php' style='padding:10px 18px; background:#2e7d32; color:#fff; text-decoration:none; border-radius:6px;'>Go to Farmer Homepage</a>";
    echo "<a href='MyProducts.php' style='padding:10px 18px; background:#1976d2; color:#fff; text-decoration:none; border-radius:6px;'>View My Products</a>";
    echo "<a href='SmartAdvisory.php' style='padding:10px 18px; background:#ed6c02; color:#fff; text-decoration:none; border-radius:6px;'>Open ML Smart Advisory</a>";

    if (!isset($_GET['autodelete'])) {
        echo "<a href='seed_farmer_profile.php?autodelete=1' onclick=\"return confirm('Are you sure you want to delete seed_farmer_profile.php from the server?')\" style='padding:10px 18px; background:#d32f2f; color:#fff; text-decoration:none; border-radius:6px; margin-left:auto;'>🗑️ Delete Seeder File</a>";
    }
    echo "</div>";
}

echo "</div></body></html>";
