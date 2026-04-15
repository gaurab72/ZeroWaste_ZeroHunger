<?php
require_once '../config/db.php';
require_once '../src/functions.php';
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$flash = getFlash();

// Handle Donation Submission
if (!isLoggedIn() || $_SESSION['role'] !== 'donor') {
    setFlash('error', 'Access denied. Please login as a donor.');
    redirect('login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['initiate_pay_btn'])) {
    $name = !empty($_POST['name']) ? sanitize($_POST['name']) : 'Anonymous';
    $amount = (float) $_POST['amount'];
    $message = sanitize($_POST['message']);
    $anon = isset($_POST['anonymous']) ? 1 : 0;
    $receiver_id = !empty($_POST['receiver_id']) ? (int)$_POST['receiver_id'] : null;
    $method = $_POST['payment_method'] ?? 'card';

    // Store in session to persist across redirects/callbacks
    $_SESSION['pending_donation'] = [
        'donor_name' => $name,
        'amount' => $amount,
        'message' => $message,
        'is_anonymous' => $anon,
        'receiver_id' => $receiver_id
    ];

    if ($method === 'esewa') {
        // eSewa Form Redirect
        ?>
        <form id="esewa-form" action="https://uat.esewa.com.np/epay/main" method="POST">
            <input value="<?php echo $amount; ?>" name="tAmt" type="hidden">
            <input value="<?php echo $amount; ?>" name="amt" type="hidden">
            <input value="0" name="txAmt" type="hidden">
            <input value="0" name="psc" type="hidden">
            <input value="0" name="pdc" type="hidden">
            <input value="EPAYTEST" name="scd" type="hidden">
            <input value="DONATION_<?php echo time(); ?>" name="pid" type="hidden">
            <input value="http://localhost/Modern_Food_Waste_System/public/payment_verify.php?method=esewa&status=success" name="su" type="hidden">
            <input value="http://localhost/Modern_Food_Waste_System/public/payment_verify.php?method=esewa&status=failed" name="fu" type="hidden">
        </form>
        <script>document.getElementById('esewa-form').submit();</script>
        <?php
        exit;
    }
}

// Fetch Verified NGOs
$verified_ngos = $pdo->query("SELECT id, username, location FROM users WHERE role = 'ngo' AND kyc_status = 'approved'")->fetchAll();

// Fetch Top Donors
$top_donors = $pdo->query("SELECT * FROM money_donations WHERE is_anonymous = 0 ORDER BY amount DESC LIMIT 5")->fetchAll();
$recent_donors = $pdo->query("SELECT * FROM money_donations ORDER BY created_at DESC LIMIT 5")->fetchAll();

// Fetch Monthly Stats for Chart
$monthly_stats = $pdo->query("
    SELECT DATE_FORMAT(created_at, '%M') as month, SUM(amount) as total 
    FROM money_donations 
    GROUP BY YEAR(created_at), MONTH(created_at) 
    ORDER BY created_at ASC LIMIT 6
")->fetchAll();

$chart_labels = json_encode(array_column($monthly_stats, 'month'));
$chart_data = json_encode(array_column($monthly_stats, 'total'));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate Money | ZeroWaste-ZeroHunger</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>
    <script src="https://www.paypal.com/sdk/js?client-id=sb&currency=USD"></script>
    <style>
        .payment-method-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        .payment-option {
            background: rgba(255,255,255,0.05);
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            padding: 15px;
            cursor: pointer;
            text-align: center;
            transition: all 0.3s ease;
        }
        .payment-option:hover, .payment-option.active {
            border-color: var(--primary);
            background: rgba(0, 255, 136, 0.1);
        }
        .payment-option img {
            max-width: 100%;
            height: 30px;
            object-fit: contain;
            margin-bottom: 5px;
            filter: grayscale(100%);
        }
        .payment-option.active img {
            filter: grayscale(0%);
        }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container" style="padding: 60px 0;">
        <a href="donate.php" style="color: var(--text-muted); text-decoration: none; display: inline-flex; align-items: center; gap: 5px; margin-bottom: 20px;">
            &larr; Back to Options
        </a>
        <h1 class="text-gradient" style="text-align: center; margin-bottom: 40px;">Support Our Mission</h1>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px;">
            <!-- Donation Form -->
            <div class="glass-card">
                <h3 style="color: var(--primary); margin-bottom: 20px;">Make a Contribution</h3>
                
                <?php if($flash): ?>
                    <div style="padding: 10px; border-radius: 8px; margin-bottom: 20px; border: 1px solid var(--success); color: var(--success); background: rgba(16, 185, 129, 0.1);">
                        <?php echo $flash['message']; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" id="donation-form">
                    <input type="hidden" name="payment_method" id="payment_method" value="card">

                    <label class="form-label">Select Payment Method</label>
                    <div class="payment-method-grid">
                        <div class="payment-option active" onclick="selectPayment('card', this)">
                            <img src="assets/images/payment_card_processed.png" alt="Credit Card" style="height:35px; object-fit:contain;">
                            <div style="font-size:0.8rem;">Credit / Debit</div>
                        </div>
                        <div class="payment-option" onclick="selectPayment('esewa', this)">
                            <img src="assets/images/esewa_logo.png" alt="eSewa" style="height:30px; object-fit:contain;">
                            <div style="font-size:0.8rem;">eSewa</div>
                        </div>
                        <div class="payment-option" onclick="selectPayment('khalti', this)">
                            <img src="assets/images/khalti_logo.png" alt="Khalti" style="height:30px; object-fit:contain;">
                            <div style="font-size:0.8rem;">Khalti</div>
                        </div>
                        <div class="payment-option" onclick="selectPayment('bank', this)">
                            <img src="assets/images/bank_icon_modern.png" alt="Bank Transfer" style="height:35px; object-fit:contain;">
                            <div style="font-size:0.8rem;">Bank Transfer</div>
                        </div>
                        <div class="payment-option" onclick="selectPayment('paypal', this)">
                            <img src="assets/images/paypal_logo.svg" alt="PayPal" style="height:30px; object-fit:contain;">
                            <div style="font-size:0.8rem;">PayPal</div>
                        </div>
                        <div class="payment-option" onclick="selectPayment('connectips', this)">
                             <img src="assets/images/connectips_logo.png" alt="ConnectIPS" style="height:30px; object-fit:contain;">
                            <div style="font-size:0.8rem;">ConnectIPS</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Support a Specific Organization (Optional)</label>
                        <select name="receiver_id" class="form-input" style="background: var(--bg-input); color: #fff;">
                            <option value="">Donate to Platform (General Fund)</option>
                            <?php foreach($verified_ngos as $ngo): ?>
                                <option value="<?php echo $ngo['id']; ?>">
                                    <?php echo htmlspecialchars($ngo['username']); ?> (<?php echo htmlspecialchars($ngo['location']); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <p style="font-size: 0.75rem; color: var(--text-muted); margin-top: 5px;">
                            Only verified and active organizations appear in this list.
                        </p>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Amount (NPR / $)</label>
                        <input type="number" name="amount" class="form-input" placeholder="500.00" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Your Name (Optional)</label>
                        <input type="text" name="name" class="form-input" placeholder="Enter your name">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Message (Optional)</label>
                        <textarea name="message" class="form-input" rows="3" placeholder="Words of encouragement..."></textarea>
                    </div>
                    <div class="form-group" style="display: flex; align-items: center; gap: 10px;">
                        <input type="checkbox" name="anonymous" id="anon">
                        <label for="anon" style="cursor: pointer; color: var(--text-muted);">Donate Anonymously</label>
                    </div>
                    <div id="paypal-button-container" style="display: none; margin-bottom: 20px;"></div>
                    <button type="submit" name="initiate_pay_btn" id="donate_btn" class="btn btn-primary" style="width: 100%;">Proceed to Pay</button>
                     <p style="font-size: 0.8rem; color: var(--text-muted); margin-top: 10px; text-align: center;">
                        <span style="color: var(--secondary);">🔒 SSL Secured Payment</span> Gateway
                    </p>
                </form>
            </div>

            <!-- Leaderboard & Stats -->
            <div>
                <!-- Chart Card -->
                <div class="glass-card" style="margin-bottom: 30px;">
                    <h3 style="color: var(--primary); margin-bottom: 20px;">📈 Monthly Trends</h3>
                    <canvas id="donationChart" width="400" height="250"></canvas>
                </div>

                <div class="glass-card" style="margin-bottom: 30px;">
                    <h3 style="color: var(--gold, #ffd700); margin-bottom: 20px;">🏆 Top Heroes</h3>
                    <ul style="list-style: none;">
                        <?php foreach($top_donors as $index => $d): ?>
                        <li style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <span style="font-weight: bold; color: var(--text-main);">#<?php echo $index+1; ?> <?php echo htmlspecialchars($d['donor_name']); ?></span>
                            <span style="color: var(--success); font-weight: bold;">Rs. <?php echo number_format($d['amount']); ?></span>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="glass-card">
                    <h3 style="color: var(--secondary); margin-bottom: 20px;">⏱️ Recent Activity</h3>
                    <ul style="list-style: none;">
                        <?php foreach($recent_donors as $d): ?>
                        <li style="padding: 10px 0; border-bottom: 1px solid rgba(255,255,255,0.05);">
                            <div style="display: flex; justify-content: space-between;">
                                <span style="color: var(--text-muted);"><?php echo $d['is_anonymous'] ? 'Anonymous' : htmlspecialchars($d['donor_name']); ?></span>
                                <span style="color: var(--success);">+Rs. <?php echo number_format($d['amount']); ?></span>
                            </div>
                            <?php if(!empty($d['message'])): ?>
                                <p style="font-size: 0.85rem; color: rgba(255,255,255,0.5); font-style: italic;">"<?php echo htmlspecialchars($d['message']); ?>"</p>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Payment Selection Logic
        function selectPayment(method, el) {
            document.getElementById('payment_method').value = method;
            document.querySelectorAll('.payment-option').forEach(opt => opt.classList.remove('active'));
            el.classList.add('active');

            // Toggle PayPal
            const paypalContainer = document.getElementById('paypal-button-container');
            const nativeBtn = document.getElementById('donate_btn');
            
            if (method === 'paypal') {
                paypalContainer.style.display = 'block';
                nativeBtn.style.display = 'none';
            } else {
                paypalContainer.style.display = 'none';
                nativeBtn.style.display = 'block';
            }
        }

        // Khalti Setup
        const khaltiConfig = {
            "publicKey": "test_public_key_dc74e1d157db4d60b3511f592d3715f5",
            "productIdentity": "1234567890",
            "productName": "Donation",
            "productUrl": "http://localhost/Modern_Food_Waste_System",
            "paymentPreference": ["KHALTI", "EBANKING", "MOBILE_BANKING", "CONNECT_IPS", "SCT"],
            "eventHandler": {
                onSuccess(payload) {
                    window.location.href = `payment_verify.php?method=khalti&status=success&token=${payload.token}`;
                },
                onError(error) { console.log(error); },
                onClose() { console.log("widget is closing"); }
            }
        };
        const checkout = new KhaltiCheckout(khaltiConfig);

        // PayPal Setup
        paypal.Buttons({
            createOrder: function(data, actions) {
                const amount = document.querySelector('input[name="amount"]').value || 10;
                return actions.order.create({
                    purchase_units: [{ amount: { value: amount } }]
                });
            },
            onApprove: function(data, actions) {
                return actions.order.capture().then(function(details) {
                    window.location.href = "payment_verify.php?method=paypal&status=success";
                });
            }
        }).render('#paypal-button-container');

        // Form Submit Override for Khalti
        document.getElementById('donation-form').onsubmit = function(e) {
            const method = document.getElementById('payment_method').value;
            if (method === 'khalti') {
                e.preventDefault();
                const amount = document.querySelector('input[name="amount"]').value * 100; // Khalti expects paisa
                if (amount > 0) checkout.show({ amount: amount });
            }
        };

        // Chart.js Logic
        const ctx = document.getElementById('donationChart').getContext('2d');
        const labels = <?php echo $chart_labels; ?>;
        const data = <?php echo $chart_data; ?>;

        // Fallback data if empty
        const safeLabels = labels.length ? labels : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        const safeData = data.length ? data : [0, 0, 0, 0, 0, 0];

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: safeLabels,
                datasets: [{
                    label: 'Donations (NPR)',
                    data: safeData,
                    backgroundColor: 'rgba(0, 255, 136, 0.5)',
                    borderColor: '#00ff88',
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: '#00ff88'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255,255,255,0.05)' },
                        ticks: { color: '#888' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#888' }
                    }
                }
            }
        });
    </script>
</body>
</html>
