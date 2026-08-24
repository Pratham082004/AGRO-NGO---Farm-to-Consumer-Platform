<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['alogin']) == 0) {
    header('location:index.php');
    exit;
}

@ini_set('output_buffering', 'off');
@ini_set('zlib.output_compression', false);
@ini_set('implicit_flush', true);
ob_implicit_flush(true);
while (ob_get_level() > 0) ob_end_flush();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>AI Expiry Console — AgroNGO Admin</title>
    <link rel="stylesheet" href="../Styles/agronogo-design.css?v=2">
    <link rel="stylesheet" href="css/admin-modern.css?v=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        .console-box {
            background: #0f172a;
            color: #38bdf8;
            border-radius: 12px;
            padding: 24px;
            height: 480px;
            overflow-y: auto;
            white-space: pre-wrap;
            font-family: 'Courier New', Courier, monospace;
            font-size: 14px;
            line-height: 1.6;
            box-shadow: inset 0 2px 10px rgba(0,0,0,0.5);
            border: 1px solid #1e293b;
        }
        .loader {
            margin-bottom: 16px;
            color: #22c55e;
            font-weight: 700;
            animation: blink 1s infinite;
        }
        @keyframes blink {
            0% { opacity: 1; }
            50% { opacity: 0.4; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>

<div class="admin-layout">
    <!-- Sidebar -->
    <aside class="admin-sidebar">
        <div class="admin-sidebar__brand">
            <div class="admin-sidebar__brand-icon">🌾</div>
            <span class="admin-sidebar__brand-text">AgroNGO Admin</span>
        </div>

        <div class="admin-sidebar__menu">
            <div class="admin-sidebar__label">Main Menu</div>
            <a href="dashboard.php" class="admin-sidebar__link">
                <i class="fas fa-chart-pie"></i> Dashboard
            </a>
            <a href="manage-users.php" class="admin-sidebar__link">
                <i class="fas fa-users"></i> Manage Users
            </a>
            <a href="manage-products.php" class="admin-sidebar__link">
                <i class="fas fa-wheat-awn"></i> Manage Products
            </a>
            <a href="manage-orders.php" class="admin-sidebar__link">
                <i class="fas fa-shopping-cart"></i> Manage Orders
            </a>

            <div class="admin-sidebar__label">AI & Automation</div>
            <a href="runml.php" class="admin-sidebar__link active">
                <i class="fas fa-robot"></i> AI Expiry Audit
            </a>

            <div class="admin-sidebar__label">Account</div>
            <a href="change-password.php" class="admin-sidebar__link">
                <i class="fas fa-lock"></i> Change Password
            </a>
            <a href="logout.php" class="admin-sidebar__link" style="color: var(--agro-red-500);">
                <i class="fas fa-sign-out-alt"></i> Sign Out
            </a>
        </div>
    </aside>

    <!-- Main Content Area -->
    <main class="admin-main">
        <!-- Top Bar -->
        <header class="admin-topbar">
            <div class="admin-topbar__title">AI Expiry & Email Alerts Console</div>
            <div class="admin-topbar__user">
                <div class="admin-topbar__avatar">A</div>
                <div>
                    <div style="font-weight: 600; font-size: 14px; color: #0f172a;"><?php echo htmlspecialchars($_SESSION['alogin']); ?></div>
                    <div style="font-size: 12px; color: #64748b;">System Administrator</div>
                </div>
            </div>
        </header>

        <!-- Content Body -->
        <div class="admin-content">

            <div class="admin-card" style="padding: 28px;">
                <div class="loader" id="loader">
                    <i class="fas fa-microchip"></i> Executing AI Expiry Engine <span id="dots">.</span>
                </div>

                <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                    <div class="admin-card__title">
                        <i class="fas fa-terminal" style="color: #38bdf8;"></i> Execution Terminal
                    </div>
                    <span class="admin-badge admin-badge--green"><i class="fas fa-bolt" style="margin-right: 4px;"></i> Live Output</span>
                </div>

                <div class="console-box" id="console">
<?php
$baseDir = dirname(__DIR__);
$mlDir = $baseDir . DIRECTORY_SEPARATOR . 'ML';
$script = $mlDir . DIRECTORY_SEPARATOR . 'mlscript.py';

// Candidate Python binaries
$possiblePythons = [
    $mlDir . DIRECTORY_SEPARATOR . 'venv' . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . 'python.exe',
    $mlDir . DIRECTORY_SEPARATOR . 'venv' . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . 'python3',
    $mlDir . DIRECTORY_SEPARATOR . 'venv' . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . 'python',
    $mlDir . DIRECTORY_SEPARATOR . 'venv' . DIRECTORY_SEPARATOR . 'Scripts' . DIRECTORY_SEPARATOR . 'python.exe',
    'C:\\Users\\Pratham\\AppData\\Local\\Programs\\Python\\Python313\\python.exe',
    'python3',
    'python'
];

$python = 'python';
foreach ($possiblePythons as $cand) {
    if (file_exists($cand)) {
        $python = $cand;
        break;
    }
}

echo ">>> Using Python Interpreter: " . htmlspecialchars($python) . "\n";
echo ">>> Executing Script: " . htmlspecialchars($script) . "\n\n";

$descriptorspec = [
    1 => ['pipe', 'w'], 
    2 => ['pipe', 'w']  
];

$process = proc_open("\"$python\" \"$script\"", $descriptorspec, $pipes);

if (is_resource($process)) {
    while (!feof($pipes[1]) || !feof($pipes[2])) {
        $stdout = fgets($pipes[1]);
        $stderr = fgets($pipes[2]);

        if ($stdout !== false) {
            echo htmlentities($stdout);
            flush();
        }

        if ($stderr !== false) {
            echo "<span style='color:#ef4444;'>ERROR: " . htmlentities($stderr) . "</span>";
            flush();
        }

        usleep(100000);
    }

    fclose($pipes[1]);
    fclose($pipes[2]);
    proc_close($process);
}

echo "\n>>> All tasks completed successfully.\n";
?>
                </div>

                <div style="margin-top: 20px;">
                    <a href="dashboard.php" class="agro-btn agro-btn--outline">
                        <i class="fas fa-arrow-left"></i> Return to Dashboard
                    </a>
                </div>
            </div>

        </div>
    </main>
</div>

<script>
    const consoleBox = document.getElementById("console");
    const observer = new MutationObserver(() => {
        consoleBox.scrollTop = consoleBox.scrollHeight;
    });
    observer.observe(consoleBox, { childList: true, subtree: true });

    setInterval(() => {
        consoleBox.scrollTop = consoleBox.scrollHeight;
    }, 200);

    let dots = document.getElementById("dots");
    let count = 1;
    setInterval(() => {
        count = (count % 5) + 1;
        dots.textContent = ".".repeat(count);
    }, 500);

    window.onload = () => {
        setTimeout(() => {
            document.getElementById("loader").style.display = "none";
        }, 1500);
    };
</script>

</body>
</html>
