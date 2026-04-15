<?php
// Session and dependencies are handled by navbar.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZeroWaste-ZeroHunger | Sustainable Food Rescue</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <!-- 3D Background Container -->
    <div id="canvas-container"></div>

    <!-- Navigation -->
<?php
// Database and Helpers
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../src/functions.php';

// Fetch Top Donors for Home Page Display
try {
    $home_top_donors = $pdo->query("SELECT * FROM money_donations WHERE is_anonymous = 0 ORDER BY amount DESC LIMIT 3")->fetchAll();
    $total_money_raised = $pdo->query("SELECT SUM(amount) FROM money_donations")->fetchColumn() ?: 0;

    $home_top_food = $pdo->query("
        SELECT u.username as donor_name, COUNT(f.id) as donation_count 
        FROM food_listings f 
        JOIN users u ON f.donor_id = u.id 
        GROUP BY f.donor_id 
        ORDER BY donation_count DESC 
        LIMIT 3
    ")->fetchAll();

    $home_top_volunteers = $pdo->query("
        SELECT u.username as volunteer_name, COUNT(c.id) as delivery_count 
        FROM users u 
        LEFT JOIN claims c ON u.id = c.volunteer_id AND c.status = 'completed'
        WHERE u.role = 'volunteer' AND u.availability_status = 'active'
        GROUP BY u.id 
        ORDER BY delivery_count DESC 
        LIMIT 6
    ")->fetchAll();
}
catch (PDOException $e) {
    $home_top_donors = [];
    $total_money_raised = 0;
    $home_top_food = [];
    $home_top_volunteers = [];
    error_log("Home Stats Error: " . $e->getMessage());
}
?>
    <?php require_once 'includes/navbar.php'; ?>

    <!-- Hero Section -->
    <section class="hero-section container" style="margin-top: 50px; position:relative; z-index:10;">
        <div class="hero-content" style="max-width: 800px; margin: 0 auto; text-align: center;">
            <div style="position:absolute; top:-50px; left:50%; transform:translateX(-50%); width:400px; height:400px; background:radial-gradient(circle, rgba(0,230,153,0.15) 0%, transparent 60%); z-index:-1; border-radius:50%;"></div>
            <h1 class="text-gradient" style="font-size: 5rem; margin-bottom: 20px; line-height: 1.05; font-weight: 800; letter-spacing: -2.5px;">
                Zero Waste.<br>Zero Hunger.<br><span style="color: var(--primary); opacity: 0.8;">Total Impact.</span>
            </h1>
            <p class="hero-subtitle" style="font-size: 1.25rem; max-width: 650px; margin: 0 auto 40px; color: var(--text-muted); line-height: 1.6; font-weight: 400;">
                The operating system for social good. We connect surplus to survival through real-time logistics and verified community networks.
            </p>
            <div style="display: flex; gap: 20px; justify-content: center; margin-bottom: 20px;">
                <a href="register.php" class="btn btn-primary" style="padding: 16px 45px; font-size: 1.05rem; border-radius: 50px; font-weight: 700; box-shadow: 0 10px 30px var(--primary-glow);">Start Donating</a>
                <a href="register.php?role=ngo" class="btn btn-outline" style="padding: 16px 45px; font-size: 1.05rem; border-radius: 50px; border-width: 2px; font-weight: 600;">I Need Food</a>
            </div>

            <!-- Live Impact Ticker -->
            <div class="ticker-wrap">
                <div class="ticker">
                    <div class="ticker-item">🟢 Live Impact: <strong>Rs. <?php echo number_format($total_money_raised); ?></strong> Raised</div>
                    <div class="ticker-item">📦 Total Saved: <strong>1,240+ Meals</strong> This Month</div>
                    <div class="ticker-item">🛡️ Verified: <strong>15 Active NGOs</strong> Protected</div>
                    <div class="ticker-item">📍 Network: <strong>Pokhara & Kathmandu</strong> Real-Time</div>
                    <!-- Duplicate for seamless loop -->
                    <div class="ticker-item">🟢 Live Impact: <strong>Rs. <?php echo number_format($total_money_raised); ?></strong> Raised</div>
                    <div class="ticker-item">📦 Total Saved: <strong>1,240+ Meals</strong> This Month</div>
                    <div class="ticker-item">🛡️ Verified: <strong>15 Active NGOs</strong> Protected</div>
                    <div class="ticker-item">📍 Network: <strong>Pokhara & Kathmandu</strong> Real-Time</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats / Features (Glass Cards) -->
    <section class="container" style="padding-bottom: 60px; position: relative;">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 30px;">
            <div class="glass-card" style="text-align: center; padding: 2.5rem 2rem;">
                <div style="width: 50px; height: 50px; background: rgba(0, 230, 153, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; border: 1px solid rgba(0, 230, 153, 0.2);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
                </div>
                <h3 style="color:var(--text-main); font-size: 1.3rem;">Real-Time</h3>
                <p style="margin-top:12px; color:var(--text-muted); line-height:1.6; font-size: 0.95rem;">Live food listings map and instant notifications for immediate rescue logistics.</p>
            </div>
            <div class="glass-card" style="text-align: center; padding: 2.5rem 2rem;">
                <div style="width: 50px; height: 50px; background: rgba(99, 102, 241, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; border: 1px solid rgba(99, 102, 241, 0.2);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--secondary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                </div>
                <h3 style="color:var(--text-main); font-size: 1.3rem;">Secure</h3>
                <p style="margin-top:12px; color:var(--text-muted); line-height:1.6; font-size: 0.95rem;">Verified non-profit organizations and fully transparent distribution tracking.</p>
            </div>
            <div class="glass-card" style="text-align: center; padding: 2.5rem 2rem;">
                <div style="width: 50px; height: 50px; background: rgba(244, 63, 94, 0.1); border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; border: 1px solid rgba(244, 63, 94, 0.2);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="var(--accent)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.21 15.89A10 10 0 1 1 8 2.83"></path><path d="M22 12A10 10 0 0 0 12 2v10z"></path></svg>
                </div>
                <h3 style="color:var(--text-main); font-size: 1.3rem;">Impact</h3>
                <p style="margin-top:12px; color:var(--text-muted); line-height:1.6; font-size: 0.95rem;">Detailed analytics on total meals recovered and carbon emission reduction.</p>
            </div>
        </div>
    </section>

    <!-- Visual Showcase Section -->
    <section id="about" class="container" style="padding: 50px 0;">
        <h2 class="text-gradient" style="text-align:center; margin-bottom: 40px; font-size: 2.5rem;">Making a Difference in Nepal</h2>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center; margin-bottom: 80px;">
            <div class="glass-card" style="padding: 10px;">
                <img src="assets/images/nepali_volunteers.png" alt="Nepali Volunteers in Kathmandu" style="width:100%; border-radius: 8px; opacity: 0.9;">
            </div>
            <div>
            <div>
                <style>
                    .donor-card {
                        background: var(--glass-bg);
                        border: 1px solid var(--glass-border);
                        border-top: 1px solid rgba(255, 255, 255, 0.1);
                        border-radius: 24px;
                        padding: 30px;
                        backdrop-filter: blur(var(--glass-blur));
                        transition: transform 0.4s ease, box-shadow 0.4s ease;
                        box-shadow: 0 15px 35px var(--glass-shadow);
                        position: relative;
                        overflow: hidden;
                    }
                    .donor-card:hover {
                        transform: translateY(-8px);
                        box-shadow: 0 25px 50px rgba(0,0,0,0.7), 0 0 30px rgba(0, 255, 170, 0.1);
                        border-color: rgba(255, 255, 255, 0.15);
                    }
                    .champion-badge {
                        background: linear-gradient(135deg, #FFD700, #FDB931);
                        color: #000;
                        padding: 6px 14px;
                        border-radius: 20px;
                        font-size: 0.75rem;
                        font-weight: 800;
                        display: inline-flex;
                        align-items: center;
                        gap: 5px;
                        box-shadow: 0 4px 15px rgba(253, 185, 49, 0.4);
                        text-transform: uppercase;
                        letter-spacing: 0.5px;
                    }
                    .donor-list-item {
                        display: flex;
                        justify-content: space-between;
                        padding: 15px 10px;
                        border-bottom: 1px solid rgba(255,255,255,0.03);
                        font-size: 0.95rem;
                        align-items: center;
                        transition: background 0.3s, padding-left 0.3s;
                        border-radius: 8px;
                    }
                    .donor-list-item:hover {
                        background: rgba(255, 255, 255, 0.02);
                        padding-left: 15px;
                    }
                    .donor-list-item:last-child {
                        border-bottom: none;
                    }
                    .section-title-gold {
                        background: linear-gradient(135deg, #FFD700, #fff7cc);
                        -webkit-background-clip: text;
                        -webkit-text-fill-color: transparent;
                        text-shadow: 0 0 20px rgba(255, 215, 0, 0.3);
                        margin-bottom: 30px;
                        font-size: 1.8rem;
                        text-align: center;
                        font-weight: 800;
                        letter-spacing: -0.5px;
                    }
                    .custom-tabs {
                        display: flex;
                        gap: 10px;
                        margin-bottom: 20px;
                        justify-content: center;
                    }
                    .tab-btn {
                        background: transparent;
                        border: 1px solid var(--glass-border);
                        color: var(--text-muted);
                        padding: 8px 16px;
                        border-radius: 20px;
                        cursor: pointer;
                        transition: all 0.3s;
                    }
                    .tab-btn.active {
                        background: var(--primary);
                        color: white;
                        border-color: var(--primary);
                    }
                </style>

                <h3>Community Powered (Sewa)</h3>
                <p style="color:var(--text-muted); margin-top:20px; line-height: 1.6; margin-bottom: 30px;">
                    From the streets of Kathmandu to the rural districts, our network of dedicated volunteers ensures that surplus food reaches those who need it most.
                    We believe in the Nepali spirit of "Sewa" (Service).
                </p>
                
                <!-- NEW TOP DONORS SECTION -->
                <div class="donor-card">
                    <h4 style="font-size: 1.8rem; margin-bottom: 30px; margin-top: 10px; text-align: center; font-weight: 800; letter-spacing: -0.5px;">Global Leaderboard</h4>
                    
                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px;">
                        
                        <!-- Money Donors Column -->
                        <div>
                            <h5 style="color: var(--text-muted); margin-bottom: 15px; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px;">
                                Financial Supporters
                            </h5>
                            <div style="display: flex; flex-direction: column; gap: 5px;">
                                <?php
$rank = 1;
foreach ($home_top_donors as $hd):
    $is_champion = $rank === 1;
?>
                                <div class="donor-list-item" style="<?php echo $is_champion ? 'background: rgba(255,215,0,0.03); padding: 10px; border-radius: 8px;' : ''; ?>">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <?php if ($is_champion): ?>
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#FFD700" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                        <?php
    else: ?>
                                            <span style="color: var(--text-muted); width: 20px; font-size: 0.8rem;">#<?php echo $rank; ?></span>
                                        <?php
    endif; ?>
                                        
                                        <div>
                                            <div style="font-weight: <?php echo $is_champion ? 'bold' : 'normal'; ?>; color: <?php echo $is_champion ? '#FFD700' : 'var(--text-main)'; ?>">
                                                <?php echo htmlspecialchars($hd['donor_name']); ?>
                                            </div>
                                            <?php if ($is_champion): ?>
                                                <div style="font-size: 0.7rem; color: var(--text-muted);">Generosity Champion</div>
                                            <?php
    endif; ?>
                                        </div>
                                    </div>
                                    <span style="color: var(--success); font-weight: bold;">Rs. <?php echo number_format($hd['amount']); ?></span>
                                </div>
                                <?php $rank++;
endforeach; ?>
                            </div>
                        </div>

                        <!-- Food Donors Column -->
                        <div style="border-left: 1px solid rgba(255,255,255,0.05); padding-left: 20px;">
                            <h5 style="color: var(--text-muted); margin-bottom: 15px; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px;">
                                Top Food Donors
                            </h5>
                            <div style="display: flex; flex-direction: column; gap: 5px;">
                                <?php
$rank_food = 1;
if (empty($home_top_food)) {
    echo '<p style="color:var(--text-muted); font-size:0.8rem;">No food donations yet. Be the first!</p>';
}
foreach ($home_top_food as $fd):
    $is_champion = $rank_food === 1;
?>
                                <div class="donor-list-item" style="<?php echo $is_champion ? 'background: rgba(0,230,153,0.05); padding: 10px; border-radius: 8px;' : ''; ?>">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <?php if ($is_champion): ?>
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                        <?php
    else: ?>
                                            <span style="color: var(--text-muted); width: 20px; font-size: 0.8rem;">#<?php echo $rank_food; ?></span>
                                        <?php
    endif; ?>
                                        
                                        <div>
                                            <div style="font-weight: <?php echo $is_champion ? 'bold' : 'normal'; ?>; color: <?php echo $is_champion ? 'var(--primary)' : 'var(--text-main)'; ?>">
                                                <?php echo htmlspecialchars($fd['donor_name']); ?>
                                            </div>
                                            <?php if ($is_champion): ?>
                                                <div style="font-size: 0.7rem; color: var(--text-muted);">Masu Ko Jhol King</div>
                                            <?php
    endif; ?>
                                        </div>
                                    </div>
                                    <span style="color: var(--accent); font-weight: bold;"><?php echo $fd['donation_count']; ?> <span style="font-size: 0.7rem; font-weight: normal; color: var(--text-muted);">Listings</span></span>
                                </div>
                                <?php $rank_food++;
endforeach; ?>
                            </div>
                        </div>

                        <div style="border-left: 1px solid rgba(255,255,255,0.05); padding-left: 20px;">
                            <h5 style="color: var(--text-muted); margin-bottom: 15px; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 1px;">
                                Top Logistics Partners
                            </h5>
                            <div style="display: flex; flex-direction: column; gap: 5px;">
                                <?php
$rank_vol = 1;
if (empty($home_top_volunteers)) {
    echo '<p style="color:var(--text-muted); font-size:0.8rem;">Ready to help? Join as a volunteer!</p>';
}
foreach ($home_top_volunteers as $vd):
    $is_champion = $rank_vol === 1;
?>
                                <div class="donor-list-item" style="<?php echo $is_champion ? 'background: rgba(99,102,241,0.05); padding: 10px; border-radius: 8px;' : ''; ?>">
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <?php if ($is_champion): ?>
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--secondary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
                                        <?php
    else: ?>
                                            <span style="color: var(--text-muted); width: 20px; font-size: 0.8rem;">#<?php echo $rank_vol; ?></span>
                                        <?php
    endif; ?>
                                        
                                        <div>
                                            <div style="font-weight: <?php echo $is_champion ? '700' : '500'; ?>; color: var(--text-main);">
                                                <?php echo htmlspecialchars($vd['volunteer_name']); ?>
                                            </div>
                                            <?php if ($is_champion): ?>
                                                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform:uppercase; letter-spacing:0.5px; opacity:0.8;">Logistics Legend</div>
                                            <?php
    endif; ?>
                                        </div>
                                    </div>
                                    <span style="color: var(--secondary); font-weight: 600; font-size:1.05rem;"><?php echo $vd['delivery_count']; ?> <span style="font-size: 0.75rem; font-weight: 500; color: var(--text-muted); text-transform:uppercase;">Trips</span></span>
                                </div>
                                <?php $rank_vol++;
endforeach; ?>
                            </div>
                        </div>

                    </div>
                    
                    <div style="margin-top: 20px; text-align: center;">
                        <a href="leaderboard.php" style="color: var(--text-muted); font-size: 0.8rem; text-decoration: none; border-bottom: 1px dotted var(--text-muted);">View Full Leaderboard</a>
                    </div>
                </div>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; align-items: center;">
            <div>
                <h3>Fresh & Nutritious (Suddha Khana)</h3>
                <p style="color:var(--text-muted); margin-top:20px; line-height: 1.6;">
                    We focus on recovering high-quality, fresh ingredients. From wedding party surplus to restaurant prepared meals (Dal Bhat, Momos), 
                    we ensure nutrition isn't wasted.
                </p>
                <a href="contact.php" class="btn btn-outline" style="margin-top: 20px;">Join the Movement</a>
            </div>
            <div class="glass-card" style="padding: 10px;">
                <img src="assets/images/nepali_food.png" alt="Fresh Nepali Food" style="width:100%; border-radius: 8px; opacity: 0.9;">
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="container" style="padding: 40px 0; border-top: 1px solid var(--glass-border); margin-top: 50px; text-align: center; color: var(--text-muted);">
        <p>&copy; <?php echo date('Y'); ?> ZeroWaste-ZeroHunger. Bridging Hunger & Hope.</p>
    </footer>

    <!-- Three.js Module -->
    <script type="module" src="assets/js/hero-3d.js"></script>
    <script src="assets/js/main.js"></script>
</body>
</html>
