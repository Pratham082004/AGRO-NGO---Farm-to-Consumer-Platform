<?php

class OllamaAdvisor {
    
    /**
     * Get buyer recommendations for a product using Ollama / Python Rule Engine
     */
    public static function getRecommendations($productData) {
        $item    = escapeshellarg($productData['item'] ?? 'Produce');
        $cat     = escapeshellarg($productData['cat'] ?? 'Fruits');
        $expiry  = intval($productData['expiry'] ?? 3);
        $stock   = floatval($productData['stock'] ?? 100);
        $storage = escapeshellarg($productData['storage'] ?? 'Ambient');
        $state   = escapeshellarg($productData['state'] ?? 'MAHARASHTRA');
        $price   = floatval($productData['price'] ?? 40);

        $pythonBin = 'python'; // Standard python binary in environment
        $scriptPath = __DIR__ . '/../ML/recommend_buyer_ollama.py';

        $command = "$pythonBin " . escapeshellarg($scriptPath) . " --item $item --cat $cat --expiry $expiry --stock $stock --storage $storage --state $state --price $price";

        $output = @shell_exec($command);

        if ($output) {
            $json = json_decode($output, true);
            if (is_array($json) && isset($json['status']) && $json['status'] === 'success') {
                return $json;
            }
        }

        // Emergency fallback in PHP if python fails
        return self::getPhpEmergencyFallback($productData);
    }

    private static function getPhpEmergencyFallback($data) {
        $item = $data['item'] ?? 'Produce';
        $expiry = intval($data['expiry'] ?? 3);
        $stock = floatval($data['stock'] ?? 100);
        $price = floatval($data['price'] ?? 40);
        
        $discount = ($expiry <= 3) ? 20 : (($expiry <= 7) ? 10 : 0);
        $clearancePrice = round($price * (1 - $discount / 100), 2);
        
        return [
            "status" => "success",
            "engine" => "php_emergency_fallback",
            "item" => $item,
            "expiry_days" => $expiry,
            "stock_kg" => $stock,
            "original_price" => $price,
            "urgency_level" => ($expiry <= 3) ? "CRITICAL" : (($expiry <= 7) ? "WARNING" : "SAFE"),
            "urgency_color" => ($expiry <= 3) ? "danger" : (($expiry <= 7) ? "warning" : "success"),
            "discount_percent" => $discount,
            "recommended_clearance_price" => $clearancePrice,
            "target_buyers" => [
                [
                    "name" => "Juice Vendors & Smoothie Bars",
                    "fit_score" => 92,
                    "reason" => "$item near expiry has optimal sweetness and juice yield for fresh beverage stalls."
                ],
                [
                    "name" => "Bakeries & Food Processing Mills",
                    "fit_score" => 85,
                    "reason" => "Ideal for immediate puree processing, jams, or baking flavorings."
                ]
            ],
            "actionable_pitch" => "Fast offload: Fresh $item ($stock kg) expiring in $expiry days. Available at ₹$clearancePrice/kg ($discount% off).",
            "shelf_life_insight" => "Produce approaching expiry should be targeted to high-consumption beverage and kitchen channels."
        ];
    }
}
