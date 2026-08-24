<?php
include("../Functions/functions.php");
include("../Includes/components/navbar.php");
include("../Includes/components/footer.php");
include("../Includes/db.php");
include("../Includes/OllamaAdvisor.php");

$sess_phone_number = $_SESSION['phonenumber'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AI Smart Buyer Advisory — AgroNGO</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .advisory-hero {
            background: linear-gradient(135deg, rgba(22, 163, 74, 0.08) 0%, rgba(34, 197, 94, 0.03) 100%);
            border-bottom: 1px solid var(--gray-200);
            padding: var(--space-10) 0;
        }

        .badge-urgency {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: var(--radius-full);
            font-size: var(--text-xs);
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .badge-urgency--danger {
            background: rgba(239, 68, 68, 0.12);
            color: var(--agro-red-600);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }
        .badge-urgency--warning {
            background: rgba(245, 158, 11, 0.12);
            color: var(--agro-amber-600);
            border: 1px solid rgba(245, 158, 11, 0.3);
        }
        .badge-urgency--success {
            background: rgba(34, 197, 94, 0.12);
            color: var(--agro-green-700);
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .buyer-card {
            background: var(--surface-primary);
            border: 1px solid var(--gray-200);
            border-radius: var(--radius-xl);
            padding: var(--space-5);
            transition: all var(--duration-normal) var(--ease-default);
            position: relative;
        }
        .buyer-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-lg);
            border-color: var(--color-primary);
        }
        .fit-score-bar {
            height: 6px;
            background: var(--gray-100);
            border-radius: var(--radius-full);
            overflow: hidden;
            margin-top: 8px;
        }
        .fit-score-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--agro-green-500), var(--agro-green-600));
            border-radius: var(--radius-full);
        }

        .pitch-box {
            background: linear-gradient(135deg, #1f2937, #111827);
            color: #f3f4f6;
            border-radius: var(--radius-xl);
            padding: var(--space-6);
            box-shadow: var(--shadow-xl);
            position: relative;
        }
        .pitch-box__title {
            color: var(--agro-green-400);
            font-size: var(--text-base);
            font-weight: 700;
            margin-bottom: var(--space-2);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .sim-container {
            display: grid;
            grid-template-columns: 1fr 1.3fr;
            gap: var(--space-8);
        }
        @media (max-width: 992px) {
            .sim-container {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <!-- Shared Navbar -->
    <?php agro_navbar('farmer', 'advisory'); ?>

    <!-- Hero Header -->
    <div class="advisory-hero">
        <div class="agro-container">
            <div class="agro-flex-between" style="flex-wrap: wrap; gap: var(--space-4);">
                <div style="max-width: 750px;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 8px;">
                        <span class="badge-urgency badge-urgency--success"><i class="fas fa-brain"></i> Ollama AI Powered</span>
                        <span style="font-size: var(--text-xs); color: var(--text-tertiary);">Agri-Decay Analytics v2.4</span>
                    </div>
                    <h1 style="font-size: var(--text-3xl); margin-bottom: var(--space-2);">Smart Buyer Advisory & Shelf-Life Engine</h1>
                    <p style="color: var(--text-secondary); font-size: var(--text-base);">
                        Never let expiring crops go to waste. Our AI analyzes produce decay stages to match short shelf-life items (e.g. Bananas expiring in 3 days) with target buyers like juice vendors, bakeries, puree processors, and discount wholesalers!
                    </p>
                </div>
                <div>
                    <a href="#simulator" class="agro-btn agro-btn--primary agro-btn--lg">
                        <i class="fas fa-magic"></i> Try Crop AI Simulator
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Container -->
    <div class="agro-container agro-section">

        <!-- My Inventory Expiry Watchlist (If Farmer Logged In) -->
        <?php if ($sess_phone_number): ?>
            <div style="margin-bottom: var(--space-12);">
                <div class="agro-flex-between" style="margin-bottom: var(--space-6);">
                    <div>
                        <h2 style="font-size: var(--text-2xl);"><i class="fas fa-boxes-packing text-success"></i> My Listed Produce Expiry Watchlist</h2>
                        <p style="color: var(--text-secondary);">Real-time AI buyer recommendations for your current inventory.</p>
                    </div>
                    <a href="MyProducts.php" class="agro-btn agro-btn--outline agro-btn--sm">View All My Products</a>
                </div>

                <div class="agro-grid agro-grid-3">
                    <?php
                    $q = "SELECT * FROM products WHERE farmer_fk IN (SELECT farmer_id FROM farmerregistration WHERE farmer_phone = '$sess_phone_number') ORDER BY product_id DESC LIMIT 6";
                    $run_q = mysqli_query($con, $q);
                    if ($run_q && mysqli_num_rows($run_q) > 0) {
                        while ($p = mysqli_fetch_assoc($run_q)) {
                            // Calculate expiry days
                            $expiryDays = 3;
                            if (!empty($p['product_expiry'])) {
                                if (is_numeric($p['product_expiry'])) {
                                    $expiryDays = intval($p['product_expiry']);
                                } else {
                                    $expTime = strtotime($p['product_expiry']);
                                    if ($expTime) {
                                        $diff = ceil(($expTime - time()) / 86400);
                                        $expiryDays = max(1, $diff);
                                    }
                                }
                            }

                            $uBadge = ($expiryDays <= 3) ? 'danger' : (($expiryDays <= 7) ? 'warning' : 'success');
                            $uText = ($expiryDays <= 3) ? "🚨 $expiryDays Days Left (Juice/Bakery Match)" : (($expiryDays <= 7) ? "⚠️ $expiryDays Days (Retail Match)" : "✅ $expiryDays Days (Safe Shelf)");

                            echo '
                            <div class="agro-card">
                                <div class="agro-card__image-wrapper">
                                    <img src="../Admin/product_images/' . htmlspecialchars($p['product_image']) . '" class="agro-card__image" alt="Product" onerror="this.src=\'../Images/Website/noimage.jpg\'">
                                    <div style="position: absolute; top: 12px; left: 12px;">
                                        <span class="badge-urgency badge-urgency--' . $uBadge . '">' . $uText . '</span>
                                    </div>
                                </div>
                                <div class="agro-card__body">
                                    <h3 class="agro-card__title">' . htmlspecialchars($p['product_title']) . '</h3>
                                    <p class="agro-card__subtitle">Stock: ' . intval($p['product_stock']) . ' kg | Price: ₹' . intval($p['product_price']) . '/kg</p>
                                    <button onclick="analyzeProduct(' . $p['product_id'] . ', \'' . htmlspecialchars(addslashes($p['product_title']), ENT_QUOTES) . '\')" class="agro-btn agro-btn--primary agro-btn--full agro-btn--sm">
                                        <i class="fas fa-robot"></i> Get AI Buyer Strategy
                                    </button>
                                </div>
                            </div>';
                        }
                    } else {
                        echo '<div style="grid-column: 1 / -1; padding: var(--space-8);" class="agro-card agro-text-center">
                            <i class="fas fa-seedling" style="font-size: 3rem; color: var(--agro-green-500); margin-bottom: 12px;"></i>
                            <h3>No Active Inventory Found</h3>
                            <p>List your produce items to get real-time Ollama LLM buyer recommendations.</p>
                            <a href="InsertProduct.php" class="agro-btn agro-btn--primary style="margin-top: 12px;">Add New Product</a>
                        </div>';
                    }
                    ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Interactive AI Buyer Match Simulator -->
        <div id="simulator" style="scroll-margin-top: 100px;">
            <div style="margin-bottom: var(--space-6);">
                <h2 style="font-size: var(--text-2xl);"><i class="fas fa-sliders text-success"></i> Interactive AI Buyer Advisory Simulator</h2>
                <p style="color: var(--text-secondary);">Test any crop and decay scenario to see real-time target buyer channels and discount pricing.</p>
            </div>

            <div class="sim-container">
                <!-- Form Inputs -->
                <div class="agro-card agro-card__body">
                    <h3 style="font-size: var(--text-lg); margin-bottom: var(--space-4); display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-pen-to-square text-success"></i> Crop Inventory Details
                    </h3>

                    <form id="simForm" onsubmit="event.preventDefault(); runSimulation();">
                        <div class="agro-form-group">
                            <label class="agro-label">Item / Crop Name</label>
                            <input type="text" id="simItem" class="agro-input" value="Bananas" placeholder="e.g. Bananas, Tomatoes, Mangoes, Spinach" required>
                        </div>

                        <div class="agro-grid agro-grid-2">
                            <div class="agro-form-group">
                                <label class="agro-label">Category</label>
                                <select id="simCat" class="agro-select">
                                    <option value="Fruits" selected>Fruits</option>
                                    <option value="Vegetables">Vegetables</option>
                                    <option value="Dairy">Dairy</option>
                                    <option value="Grains">Grains & Crops</option>
                                </select>
                            </div>

                            <div class="agro-form-group">
                                <label class="agro-label">Days to Expiry</label>
                                <input type="number" id="simExpiry" class="agro-input" value="3" min="1" max="60" required>
                            </div>
                        </div>

                        <div class="agro-grid agro-grid-2">
                            <div class="agro-form-group">
                                <label class="agro-label">Stock Quantity (kg)</label>
                                <input type="number" id="simStock" class="agro-input" value="250" min="1" required>
                            </div>

                            <div class="agro-form-group">
                                <label class="agro-label">Base Price (₹/kg)</label>
                                <input type="number" id="simPrice" class="agro-input" value="40" min="1" required>
                            </div>
                        </div>

                        <div class="agro-grid agro-grid-2">
                            <div class="agro-form-group">
                                <label class="agro-label">Storage Condition</label>
                                <select id="simStorage" class="agro-select">
                                    <option value="Ambient" selected>Ambient (Room Temp)</option>
                                    <option value="Cold Storage">Cold Storage</option>
                                    <option value="Dry Storage">Dry Storage</option>
                                </select>
                            </div>

                            <div class="agro-form-group">
                                <label class="agro-label">Farmer State</label>
                                <input type="text" id="simState" class="agro-input" value="MAHARASHTRA">
                            </div>
                        </div>

                        <button type="submit" id="btnSubmit" class="agro-btn agro-btn--primary agro-btn--full agro-btn--lg">
                            <i class="fas fa-wand-magic-sparkles"></i> Generate AI Buyer Recommendation
                        </button>
                    </form>
                </div>

                <!-- AI Output Display -->
                <div>
                    <div id="aiLoading" style="display: none;" class="agro-card agro-card__body agro-text-center">
                        <i class="fas fa-circle-notch fa-spin text-success" style="font-size: 3rem; margin-bottom: 16px;"></i>
                        <h3>Analyzing Crop Shelf-Life with Ollama LLM...</h3>
                        <p style="color: var(--text-tertiary);">Evaluating decay profile, sugar concentration, and optimal buyer fit...</p>
                    </div>

                    <div id="aiResults" style="display: none;" class="agro-flex" style="flex-direction: column; gap: var(--space-6);">
                        <!-- Status Banner -->
                        <div id="resBanner" class="agro-card agro-card__body" style="border-left: 5px solid var(--color-danger);">
                            <div class="agro-flex-between" style="flex-wrap: wrap; gap: 12px;">
                                <div>
                                    <span id="resBadge" class="badge-urgency badge-urgency--danger">CRITICAL (3 DAYS REMAINING)</span>
                                    <h3 id="resTitle" style="margin-top: 8px; font-size: var(--text-xl);">Urgent Clearance Recommended</h3>
                                </div>
                                <div class="agro-text-right">
                                    <div style="font-size: var(--text-xs); color: var(--text-tertiary);">Clearance Price</div>
                                    <div id="resPrice" style="font-size: var(--text-2xl); font-weight: 800; color: var(--color-primary);">₹34.0 <span style="font-size: var(--text-sm); font-weight: 400; color: var(--text-tertiary);">(15% off)</span></div>
                                </div>
                            </div>
                        </div>

                        <!-- Target Buyer Cards -->
                        <div>
                            <h4 style="margin-bottom: var(--space-3); font-size: var(--text-base); color: var(--text-secondary);"><i class="fas fa-bullseye text-success"></i> Recommended Buyer Segments</h4>
                            <div id="buyerList" style="display: flex; flex-direction: column; gap: 12px;">
                                <!-- Dynamically filled -->
                            </div>
                        </div>

                        <!-- Sales Pitch Box -->
                        <div class="pitch-box">
                            <div class="pitch-box__title">
                                <i class="fas fa-comment-dots"></i> Actionable AI Sales Pitch
                                <button onclick="copyPitch()" class="agro-btn agro-btn--ghost agro-btn--sm" style="margin-left: auto; color: white;">
                                    <i class="fas fa-copy"></i> Copy Pitch
                                </button>
                            </div>
                            <p id="resPitch" style="color: #e5e7eb; font-size: var(--text-sm); line-height: 1.6;"></p>
                        </div>

                        <!-- Agronomy Insight -->
                        <div class="agro-card agro-card__body" style="background: var(--agro-green-50); border-color: var(--agro-green-200);">
                            <h4 style="color: var(--agro-green-800); font-size: var(--text-sm); margin-bottom: 4px;">
                                <i class="fas fa-flask"></i> Shelf-Life Agronomy Insight
                            </h4>
                            <p id="resInsight" style="color: var(--agro-green-900); font-size: var(--text-sm); margin: 0;"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Shared Footer -->
    <?php agro_footer('farmer'); ?>

    <script src="../Styles/agronogo-components.js"></script>
    <script>
        function runSimulation() {
            const item = document.getElementById('simItem').value;
            const cat = document.getElementById('simCat').value;
            const expiry = document.getElementById('simExpiry').value;
            const stock = document.getElementById('simStock').value;
            const price = document.getElementById('simPrice').value;
            const storage = document.getElementById('simStorage').value;
            const state = document.getElementById('simState').value;

            document.getElementById('aiLoading').style.display = 'block';
            document.getElementById('aiResults').style.display = 'none';

            const url = `api_buyer_recommendation.php?item=${encodeURIComponent(item)}&cat=${encodeURIComponent(cat)}&expiry=${expiry}&stock=${stock}&price=${price}&storage=${encodeURIComponent(storage)}&state=${encodeURIComponent(state)}`;

            fetch(url)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('aiLoading').style.display = 'none';
                    if (data.status === 'success') {
                        renderResults(data);
                    } else {
                        alert('Error fetching recommendation: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(err => {
                    document.getElementById('aiLoading').style.display = 'none';
                    alert('Network error connecting to AI service.');
                });
        }

        function analyzeProduct(productId, title) {
            document.getElementById('simItem').value = title;
            document.getElementById('simulator').scrollIntoView({ behavior: 'smooth' });

            document.getElementById('aiLoading').style.display = 'block';
            document.getElementById('aiResults').style.display = 'none';

            fetch(`api_buyer_recommendation.php?product_id=${productId}`)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('aiLoading').style.display = 'none';
                    if (data.status === 'success') {
                        renderResults(data);
                    }
                })
                .catch(err => {
                    document.getElementById('aiLoading').style.display = 'none';
                });
        }

        function renderResults(data) {
            document.getElementById('aiResults').style.display = 'flex';

            const uBadge = document.getElementById('resBadge');
            const uBanner = document.getElementById('resBanner');

            uBadge.className = `badge-urgency badge-urgency--${data.urgency_color}`;
            uBadge.textContent = `${data.urgency_level} (${data.expiry_days} DAYS REMAINING)`;
            uBanner.style.borderLeftColor = `var(--color-${data.urgency_color})`;

            document.getElementById('resPrice').innerHTML = `₹${data.recommended_clearance_price} <span style="font-size: var(--text-sm); font-weight: 400; color: var(--text-tertiary);">(${data.discount_percent}% off)</span>`;
            document.getElementById('resPitch').textContent = data.actionable_pitch;
            document.getElementById('resInsight').textContent = data.shelf_life_insight;

            // Render buyer cards
            const bList = document.getElementById('buyerList');
            bList.innerHTML = '';
            if (data.target_buyers && data.target_buyers.length > 0) {
                data.target_buyers.forEach(b => {
                    const card = document.createElement('div');
                    card.className = 'buyer-card';
                    card.innerHTML = `
                        <div class="agro-flex-between">
                            <h4 style="font-size: var(--text-base); color: var(--text-primary);"><i class="fas fa-store text-success"></i> ${b.name}</h4>
                            <span style="font-weight: 700; color: var(--color-primary); font-size: var(--text-sm);">${b.fit_score}% Match</span>
                        </div>
                        <p style="font-size: var(--text-sm); color: var(--text-secondary); margin-top: 6px;">${b.reason}</p>
                        <div class="fit-score-bar">
                            <div class="fit-score-fill" style="width: ${b.fit_score}%;"></div>
                        </div>
                    `;
                    bList.appendChild(card);
                });
            }
        }

        function copyPitch() {
            const txt = document.getElementById('resPitch').textContent;
            navigator.clipboard.writeText(txt);
            alert('Sales pitch copied to clipboard!');
        }

        // Auto run on load
        window.addEventListener('DOMContentLoaded', () => {
            runSimulation();
        });
    </script>
</body>
</html>
