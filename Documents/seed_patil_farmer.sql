-- AgroNGO Seed File for Rajesh Patil (Patil Agro Organic Farm)
-- Inserts 1 Farmer Profile and 24 diverse stock items (fruits, vegetables, grains, processed foods)

START TRANSACTION;

-- 0. Insert Admin Credentials
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `UserName` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `updationDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `admin` (`id`, `UserName`, `Password`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3')
ON DUPLICATE KEY UPDATE `UserName` = VALUES(`UserName`);

-- 1. Insert Farmer Profile
INSERT INTO `farmerregistration` (`farmer_id`, `farmer_name`, `farmer_phone`, `farmer_address`, `farmer_state`, `farmer_district`, `farmer_pan`, `farmer_bank`, `farmer_password`) VALUES
(999, 'Rajesh Patil (Patil Agro Organic Farm)', 9876543210, 'Gat No. 142, Green Valley Estate, Dindori Road', 'MAHARASHTRA', 'Nashik', 'RJPAT9876F', 98765432, 'yw==')
ON DUPLICATE KEY UPDATE `farmer_name` = VALUES(`farmer_name`);

-- 2. Insert 24 Stock Items
INSERT INTO `products` (`farmer_fk`, `product_title`, `product_cat`, `product_type`, `product_expiry`, `product_image`, `product_stock`, `product_price`, `product_desc`, `product_keywords`, `product_delivery`, `storage_condition`, `is_processed`) VALUES
(999, 'Ratnagiri Premium Alphonso Mangoes', '3', 'Mango', DATE_ADD(CURDATE(), INTERVAL 3 DAY), 'Mango.jpg', 1200, 180, 'Geographical Indication (GI) certified premium Alphonso mangoes. Soft, aromatic, and rich in natural Brix sweetness. Expiring soon—ideal for fresh juice vendors and bakeries.', 'alphonso, mango, ratnagiri, fresh fruit, sweet', 'yes', 'Ambient', 0),
(999, 'Fresh Nashik Red Onions', '2', 'Onion', DATE_ADD(CURDATE(), INTERVAL 15 DAY), 'Onion.jpg', 4500, 24, 'High-grade Nashik red onions with long shelf life, solid outer skins, and strong pungent flavor. Perfect for wholesale mandi buyers and restaurant chains.', 'onion, nashik red, vegetable, bulk stock', 'yes', 'Ambient', 0),
(999, 'Sweet Mahabaleshwar Strawberries', '3', 'Strawberry', DATE_ADD(CURDATE(), INTERVAL 2 DAY), 'strawberry.jpg', 350, 140, 'Freshly handpicked Grade A strawberries from Mahabaleshwar plateau. Peak sweetness and vibrant crimson color. Urgent clearance sale.', 'strawberry, mahabaleshwar, fresh, berries', 'yes', 'Refrigerated', 0),
(999, 'Juicy Flame Seedless Green Grapes', '3', 'Grapes', DATE_ADD(CURDATE(), INTERVAL 5 DAY), 'Green Grapes.jpg', 2800, 65, 'Crisp, seedless export-quality green grapes harvested from Nashik vineyards. Great balance of tartness and sugar.', 'grapes, green grapes, seedless, vineyard', 'yes', 'Cold Storage', 0),
(999, 'Organic Country Tomatoes (Desi)', '2', 'Tomato', DATE_ADD(CURDATE(), INTERVAL 3 DAY), 'Tomato.jpg', 1800, 18, 'Sun-ripened organic tomatoes with high pulp content. Ideal for direct kitchen cooking, sauce manufacturers, and ketchup processing plants.', 'tomato, organic, desi tomato, sauce', 'yes', 'Ambient', 0),
(999, 'Kashmiri Crisp Royal Red Apples', '3', 'Apple', DATE_ADD(CURDATE(), INTERVAL 12 DAY), 'Apple.jpg', 1500, 110, 'Cold-stored Kashmiri Red Delicious apples with high crunch factor and natural wax coat.', 'apple, kashmiri, red apple, fruit', 'no', 'Cold Storage', 0),
(999, 'Golden Sharbati Durum Wheat Grains', '1', 'Wheat', DATE_ADD(CURDATE(), INTERVAL 180 DAY), 'Wheat.jpg', 5000, 38, 'Premium Sharbati wheat grains, sun-dried to optimal moisture (<10%). Excellent for soft rotis, flour mills, and grain trade.', 'wheat, sharbati, grain, crop, flour', 'yes', 'Ambient', 0),
(999, 'Aromatic Indrayani Basmati Rice', '1', 'Rice', DATE_ADD(CURDATE(), INTERVAL 240 DAY), 'Rice.jpg', 3200, 62, 'Fragrant long-grain Indrayani rice aged for 1 year. Low starch, non-sticky cooking profile.', 'rice, basmati, indrayani, crop, grain', 'yes', 'Ambient', 0),
(999, 'Fresh Shimla Carrots', '2', 'Carrot', DATE_ADD(CURDATE(), INTERVAL 6 DAY), 'Carrot.jpg', 900, 32, 'Deep orange, juicy sweet carrots washed and cleaned. Ideal for daily culinary use and halwa prep.', 'carrot, fresh, root vegetable, orange', 'yes', 'Refrigerated', 0),
(999, 'Fresh Green Cabbage Heads', '2', 'Cabbage', DATE_ADD(CURDATE(), INTERVAL 4 DAY), 'Cabbage.jpg', 1400, 15, 'Tightly packed heads of fresh green cabbage harvested this week. Great for canteens, street food stalls, and salads.', 'cabbage, green, vegetable, leaf', 'yes', 'Ambient', 0),
(999, 'Organic Jyoti Potatoes', '2', 'Potato', DATE_ADD(CURDATE(), INTERVAL 20 DAY), 'Potato.webp', 6000, 20, 'Smooth-skinned Kufri Jyoti potatoes, dry-cleaned and sorted by size. High solid content suitable for chips and daily cooking.', 'potato, organic, jyoti, vegetable', 'yes', 'Ambient', 0),
(999, 'Nagpur Juicy Sweet Oranges (Santra)', '3', 'Orange', DATE_ADD(CURDATE(), INTERVAL 7 DAY), 'orange.jpg', 2200, 45, 'Direct from Nagpur orchards. Thin rind, full of juice and Vitamin C. Recommended for fresh beverage bars.', 'orange, nagpur, santra, fruit, juice', 'yes', 'Ambient', 0),
(999, 'Golden Sweet Corn Cobs', '1', 'Maize', DATE_ADD(CURDATE(), INTERVAL 5 DAY), 'Maize.jpg', 1600, 28, 'Tender yellow sweet corn cobs in husk. High sugar yield, ready for boiling, roasting, or food processing.', 'maize, sweet corn, corn, crop', 'yes', 'Ambient', 0),
(999, 'Fresh Tender Coconut (Nariyal)', '1', 'Coconut', DATE_ADD(CURDATE(), INTERVAL 10 DAY), 'Coconut.jpg', 800, 35, 'Green tender coconuts loaded with refreshing electrolyte water (400ml+ per nut). Direct from coastal plantations.', 'coconut, tender coconut, water, crop', 'no', 'Ambient', 0),
(999, 'Sugar Ripe Yellow Bananas', '3', 'Bananas', DATE_ADD(CURDATE(), INTERVAL 2 DAY), 'Bananas.jpg', 950, 22, 'Grand Naine variety bananas at peak ripeness. High Brix sweetness, perfect for smoothie outlets and immediate retail sale.', 'banana, ripe, yellow, fruit', 'yes', 'Ambient', 0),
(999, 'Ripe Sweet Custard Apples (Sitaphal)', '3', 'Custard Apple', DATE_ADD(CURDATE(), INTERVAL 2 DAY), 'custartapple.cms', 400, 75, 'Creamy, sweet Sitaphal pulp fruits harvested from dryland orchards. Urgent buyer needed for ice-cream and dessert processors.', 'custard apple, sitaphal, ripe fruit', 'yes', 'Ambient', 0),
(999, 'Artisanal Cold-Pressed Peanut Oil', '1', 'Oil', DATE_ADD(CURDATE(), INTERVAL 120 DAY), '', 500, 195, 'Traditional Wood-Pressed (Kachi Ghani) pure groundnut oil without added chemicals or preservatives. Processed on farm.', 'oil, peanut oil, kachi ghani, processed', 'yes', 'Ambient', 1),
(999, 'Concentrated Organic Tomato Puree', '2', 'Tomato', DATE_ADD(CURDATE(), INTERVAL 90 DAY), 'Tomato.jpg', 750, 85, 'Farm-processed thick tomato paste canned in food-grade tin containers. Ideal for hotel chains, pizzerias, and caterers.', 'tomato puree, paste, processed, vegetable', 'yes', 'Ambient', 1),
(999, 'Dehydrated Raw Mango Slices (Amchur)', '3', 'Mango', DATE_ADD(CURDATE(), INTERVAL 150 DAY), 'Mango.jpg', 300, 220, 'Solar-dehydrated sour green mango slices. Used for dry mango powder processing and pickle flavoring.', 'amchur, dehydrated mango, processed, spice', 'yes', 'Ambient', 1),
(999, 'Fresh Red Bhagwa Pomegranates', '3', 'Pomegranate', DATE_ADD(CURDATE(), INTERVAL 10 DAY), '', 1100, 130, 'Deep red Bhagwa variety pomegranates with soft seeds and high juice volume. Export quality.', 'pomegranate, bhagwa, red arils, fruit', 'yes', 'Cold Storage', 0),
(999, 'Fresh Green Spinach Bundles (Palak)', '2', 'Spinach', DATE_ADD(CURDATE(), INTERVAL 1 DAY), '', 250, 14, 'Hydroponic & soil grown fresh green leafy spinach. Same-day harvest, zero chemical spray. CRITICAL URGENCY.', 'spinach, palak, leafy vegetable, fresh', 'yes', 'Refrigerated', 0),
(999, 'Organic Fresh Ginger Roots (Adrak)', '2', 'Ginger', DATE_ADD(CURDATE(), INTERVAL 25 DAY), '', 850, 90, 'Firm, aromatic ginger rhizomes with low fibre and strong zest. High essential oil content.', 'ginger, adrak, spice, vegetable', 'yes', 'Ambient', 0),
(999, 'High-Curcumin Fresh Turmeric Finger Roots', '1', 'Turmeric', DATE_ADD(CURDATE(), INTERVAL 40 DAY), '', 1250, 70, 'Waigaon variety raw turmeric fingers with 5.2% natural curcumin content. Ideal for extraction and powder milling.', 'turmeric, haldi, curcumin, crop', 'yes', 'Ambient', 0),
(999, 'Pure Organic Sugarcane Jaggery Powder (Gud)', '1', 'Jaggery', DATE_ADD(CURDATE(), INTERVAL 180 DAY), '', 1500, 58, 'Chemical-free natural sugarcane jaggery powder produced on farm using traditional crushed cane juice boiling.', 'jaggery, gud, sugarcane, processed, organic', 'yes', 'Ambient', 1);

COMMIT;
