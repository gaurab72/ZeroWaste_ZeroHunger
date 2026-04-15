<?php
require_once '../src/functions.php';
session_start();

// Access Control: Only for logged-in donors
if (!isLoggedIn() || $_SESSION['role'] !== 'donor') {
    setFlash('error', 'Please login as a donor to access this page.');
    redirect('login.php');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donate | ZeroWaste-ZeroHunger</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="dashboard-layout">
        <main class="main-content">
            <div class="container" style="max-width: 1000px; padding: 60px 20px;">
                <div style="text-align: center; margin-bottom: 60px;">
                    <h1 class="text-gradient" style="font-size: 3rem; margin-bottom: 20px;">The Power of Giving</h1>
                    <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 600px; margin: 0 auto;">
                        Your contribution, whether surplus or financial, serves as a lifeline for our community. How would you like to make an impact today?
                    </p>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 30px;">
                    <!-- Option 1: Food -->
                    <div class="glass-card" onclick="window.location.href='donate_food.php'" style="cursor: pointer; padding: 50px; text-align: center; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); border: 1px solid rgba(255,255,255,0.05);">
                        <div style="width: 80px; height: 80px; background: rgba(0, 255, 162, 0.1); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; border: 1px solid rgba(0, 255, 162, 0.2); box-shadow: 0 0 20px rgba(0, 255, 162, 0.1);">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2L2 7l10 5 10-5-10-5z"></path><path d="M2 17l10 5 10-5"></path><path d="M2 12l10 5 10-5"></path></svg>
                        </div>
                        <h2 style="font-size: 1.8rem; margin-bottom: 15px;">Donate Surplus Food</h2>
                        <p style="color: var(--text-muted); line-height: 1.6; margin-bottom: 30px;">
                            Share your extra meals directly with local NGOs. Reduce waste and bridge the hunger gap in real-time.
                        </p>
                        <button class="btn btn-primary" style="width: 100%;">Initiate Food Rescue</button>
                    </div>

                    <!-- Option 2: Funds -->
                    <div class="glass-card" onclick="window.location.href='donate_money.php'" style="cursor: pointer; padding: 50px; text-align: center; transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); border: 1px solid rgba(255,255,255,0.05);">
                        <div style="width: 80px; height: 80px; background: rgba(255, 215, 0, 0.1); border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 30px; border: 1px solid rgba(255, 215, 0, 0.2); box-shadow: 0 0 20px rgba(255, 215, 0, 0.1);">
                            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#ffd700" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                        </div>
                        <h2 style="font-size: 1.8rem; margin-bottom: 15px;">Contribute Funds</h2>
                        <p style="color: var(--text-muted); line-height: 1.6; margin-bottom: 30px;">
                            Empower our logistics and operations. Your financial support allows us to scale our impact across the nation.
                        </p>
                        <button class="btn btn-outline" style="width: 100%; border-color: #ffd700; color: #ffd700;">Donate Funds</button>
                    </div>
                </div>

                <div class="glass-card" style="margin-top: 60px; display: flex; align-items: center; gap: 30px; background: rgba(0, 255, 162, 0.03);">
                    <div style="font-size: 2rem;">🛡️</div>
                    <div>
                        <h4 style="margin-bottom: 5px;">Secure & Transparent</h4>
                        <p style="color: var(--text-muted); font-size: 0.9rem;">All contributions are tracked and verified. You'll receive a full impact report once your donation is processed.</p>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
