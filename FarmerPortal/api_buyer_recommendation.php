<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../Includes/db.php';
require_once __DIR__ . '/../Includes/OllamaAdvisor.php';

$response = [
    'status' => 'error',
    'message' => 'Invalid Request'
];

try {
    $product_id = $_GET['product_id'] ?? $_POST['product_id'] ?? null;
    
    if ($product_id) {
        global $con;
        $id = intval($product_id);
        $query = "SELECT * FROM products WHERE product_id = $id LIMIT 1";
        $run = mysqli_query($con, $query);

        if ($run && mysqli_num_rows($run) > 0) {
            $row = mysqli_fetch_assoc($run);

            // Parse expiry date if stored as date string or number of days
            $expiryDays = 3; // default
            if (!empty($row['product_expiry'])) {
                if (is_numeric($row['product_expiry'])) {
                    $expiryDays = intval($row['product_expiry']);
                } else {
                    $expTime = strtotime($row['product_expiry']);
                    if ($expTime) {
                        $diff = ceil(($expTime - time()) / 86400);
                        $expiryDays = max(1, $diff);
                    }
                }
            }

            // Get farmer state if available
            $farmerState = 'MAHARASHTRA';
            if (!empty($row['farmer_fk'])) {
                $fQuery = "SELECT farmer_state FROM farmerregistration WHERE farmer_id = " . intval($row['farmer_fk']);
                $fRun = mysqli_query($con, $fQuery);
                if ($fRun && $fRow = mysqli_fetch_assoc($fRun)) {
                    $farmerState = $fRow['farmer_state'];
                }
            }

            $productData = [
                'item'    => $row['product_title'] ?? 'Produce',
                'cat'     => $row['product_cat'] ?? 'Fruits',
                'expiry'  => $expiryDays,
                'stock'   => floatval($row['product_stock'] ?? 100),
                'storage' => $row['storage_condition'] ?? 'Ambient',
                'state'   => $farmerState,
                'price'   => floatval($row['product_price'] ?? 40)
            ];

            $res = OllamaAdvisor::getRecommendations($productData);
            echo json_encode($res, JSON_PRETTY_PRINT);
            exit;
        }
    }

    // Direct input parameters
    $productData = [
        'item'    => $_GET['item'] ?? $_POST['item'] ?? 'Bananas',
        'cat'     => $_GET['cat'] ?? $_POST['cat'] ?? 'Fruits',
        'expiry'  => intval($_GET['expiry'] ?? $_POST['expiry'] ?? 3),
        'stock'   => floatval($_GET['stock'] ?? $_POST['stock'] ?? 200),
        'storage' => $_GET['storage'] ?? $_POST['storage'] ?? 'Ambient',
        'state'   => $_GET['state'] ?? $_POST['state'] ?? 'MAHARASHTRA',
        'price'   => floatval($_GET['price'] ?? $_POST['price'] ?? 40)
    ];

    $res = OllamaAdvisor::getRecommendations($productData);
    echo json_encode($res, JSON_PRETTY_PRINT);
    exit;

} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
    exit;
}
