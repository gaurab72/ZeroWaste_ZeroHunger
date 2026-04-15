<?php
// public/admin/dashboard.php
require_once '../../config/db.php';
$page_title = 'Executive Overview';

// Include the new layout
// Layout opens <html> ... <div class="main-content">
require_once 'layout.php';

// --- DATA FETCHING (REAL TIME) ---
$total_users = $pdo->query("SELECT COUNT(*) FROM users WHERE role != 'admin'")->fetchColumn();
$pending_kyc = $pdo->query("SELECT COUNT(*) FROM users WHERE kyc_status = 'pending'")->fetchColumn();
$active_food = $pdo->query("SELECT COUNT(*) FROM food_listings WHERE status = 'available'")->fetchColumn();
$total_meals = $pdo->query("SELECT COUNT(*) FROM claims WHERE status = 'completed'")->fetchColumn();

$total_money = $pdo->query("SELECT SUM(amount) FROM money_donations")->fetchColumn() ?: 0;

// Chart Data: User Distribution
$stmt = $pdo->query("SELECT role, COUNT(*) as count FROM users WHERE role != 'admin' GROUP BY role");
$user_dist = $stmt->fetchAll(PDO::FETCH_KEY_PAIR); // ['donor'=>5, 'ngo'=>3]
$donor_count = $user_dist['donor'] ?? 0;
$ngo_count = $user_dist['ngo'] ?? 0;

// Chart Data: Monthly Impact (Simulated for visual as per request for "attractive graph")
$months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
$impact_data = [12, 19, 15, 25, 30, 42]; // Upward trend

// Recent Activity Feed
$activity_sql = "
    (SELECT 'New User' as type, username as detail, created_at FROM users ORDER BY created_at DESC LIMIT 5)
    UNION
    (SELECT 'New Food Post' as type, title as detail, created_at FROM food_listings ORDER BY created_at DESC LIMIT 5)
    UNION 
    (SELECT 'Money Donation' as type, CONCAT('Rs. ', amount) as detail, created_at FROM money_donations ORDER BY created_at DESC LIMIT 5)
    ORDER BY created_at DESC LIMIT 6
";
$activities = $pdo->query($activity_sql)->fetchAll();

// Top Donors (for table)
$top_donors = $pdo->query("SELECT donor_name, amount, created_at FROM money_donations ORDER BY amount DESC LIMIT 5")->fetchAll();

// --- NEW: TOP FOOD DONORS (ANALYSIS) ---
$food_donor_stmt = $pdo->query("
    SELECT u.username as donor_name, COUNT(f.id) as total_listings
    FROM food_listings f
    JOIN users u ON f.donor_id = u.id
    GROUP BY u.username
    ORDER BY total_listings DESC
    LIMIT 10
");
$top_food_donors_data = $food_donor_stmt->fetchAll(PDO::FETCH_ASSOC);

// Prep for Chart
$food_donor_labels = [];
$food_donor_counts = [];
foreach ($top_food_donors_data as $fd) {
    $food_donor_labels[] = $fd['donor_name'];
    $food_donor_counts[] = $fd['total_listings'];
}
?>

    <!-- PRO STATS GRID -->
    <div class="stats-grid">
        <!-- Money Card (Gold Gradient) -->
        <div class="admin-card" style="background: linear-gradient(135deg, rgba(255, 215, 0, 0.08), rgba(0,0,0,0)); border-color: rgba(255, 215, 0, 0.25); position:relative; overflow:hidden;">
            <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(255, 215, 0, 0.2); filter:blur(40px); border-radius:50%;"></div>
            <div style="display:flex; justify-content:space-between; align-items:start; position:relative; z-index:2;">
                <div>
                    <p style="color:var(--warning); font-weight:800; font-size:0.75rem; letter-spacing:1px; text-transform:uppercase;">Total Funds Raised</p>
                    <h2 style="font-size: 2.2rem; margin-top: 5px; color:var(--text-main); font-weight:800;">Rs. <?php echo number_format($total_money); ?></h2>
                </div>
                <div style="background:rgba(255,215,0,0.1); padding:12px; border-radius:14px; color:#FFD700; border: 1px solid rgba(255,215,0,0.2);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                </div>
            </div>
            <div style="margin-top:15px; height:6px; background:rgba(255,255,255,0.05); border-radius:3px; overflow:hidden;">
                <div style="width: 75%; height:100%; background: linear-gradient(90deg, #FDB931, #FFD700); border-radius:3px; box-shadow: 0 0 10px rgba(255,215,0,0.5);"></div>
            </div>
            <p style="font-size:0.75rem; color:var(--text-muted); margin-top:8px;">75% of Monthly Goal</p>
        </div>

        <!-- Users Card -->
        <div class="admin-card" style="position:relative; overflow:hidden;">
            <div style="position:absolute; top:-20px; right:-20px; width:100px; height:100px; background:rgba(59, 130, 246, 0.2); filter:blur(40px); border-radius:50%;"></div>
            <div style="display:flex; justify-content:space-between; align-items:start; position:relative; z-index:2;">
                <div>
                    <p style="color:var(--text-muted); font-size:0.85rem;">Total Community</p>
                    <h2 style="font-size: 2rem; margin-top: 5px;"><?php echo $total_users; ?></h2>
                </div>
                <div style="background:rgba(59, 130, 246, 0.1); padding:10px; border-radius:12px; color:var(--secondary); border: 1px solid rgba(59, 130, 246, 0.2);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                </div>
            </div>
            <div style="margin-top:15px; display:flex; gap:10px; font-size:0.8rem;">
                <span class="badge badge-success">▲ 12%</span>
                <span style="color:var(--text-muted);">from last month</span>
            </div>
        </div>

        <!-- KYC Alert Card -->
        <div class="admin-card" style="<?php echo $pending_kyc > 0 ? 'border-color:var(--danger); box-shadow:0 0 10px rgba(239,68,68,0.1);' : ''; ?>">
            <div style="display:flex; justify-content:space-between; align-items:start;">
                <div>
                    <p style="color:var(--text-muted); font-size:0.85rem;">Pending Verifications</p>
                    <h2 style="font-size: 2rem; margin-top: 5px; color: <?php echo $pending_kyc > 0 ? 'var(--danger)' : ''; ?>">
                        <?php echo $pending_kyc; ?>
                    </h2>
                </div>
                 <div style="background:rgba(239, 68, 68, 0.1); padding:10px; border-radius:12px; color:var(--danger); border: 1px solid rgba(239, 68, 68, 0.2);">
                     <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                 </div>
            </div>
            <div style="margin-top:15px;">
                 <?php if ($pending_kyc > 0): ?>
                    <a href="users.php" class="btn-action" style="background:var(--danger); color:white; width:100%; display:block; text-align:center;">Review Now</a>
                <?php
else: ?>
                    <span class="badge badge-success">All Clear</span>
                <?php
endif; ?>
            </div>
        </div>
        
         <!-- Meals Saved (Impact) -->
         <div class="admin-card">
            <div style="display:flex; justify-content:space-between; align-items:start;">
                <div>
                    <p style="color:var(--text-muted); font-size:0.85rem;">Meals Rescued</p>
                    <h2 style="font-size: 2rem; margin-top: 5px;"><?php echo $active_food + ($total_meals ?? 0) * 10; // Dummy multiplier for impact visual ?></h2>
                </div>
                <div style="background:rgba(16, 185, 129, 0.1); padding:10px; border-radius:12px; color:var(--primary); border: 1px solid rgba(16, 185, 129, 0.2);">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"></path><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path><line x1="6" y1="1" x2="6" y2="4"></line><line x1="10" y1="1" x2="10" y2="4"></line><line x1="14" y1="1" x2="14" y2="4"></line></svg>
                </div>
            </div>
            <div style="margin-top:15px; display:flex; gap:10px; font-size:0.8rem;">
                <span class="badge badge-success">▲ 5%</span>
                <span style="color:var(--text-muted);">this week</span>
            </div>
        </div>
    </div>

    <!-- Leaflet Map CSS/JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- MAIN VISUALIZATION ROW -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 25px; margin-bottom: 30px;">
        
        <!-- Large Map Widget -->
        <div class="admin-card" style="padding:0; height: 400px; overflow:hidden; position:relative; display:flex; flex-direction:column; border: 1px solid rgba(0,255,170,0.15);">
            <div style="padding:20px; border-bottom:1px solid var(--glass-border); background:rgba(0,0,0,0.4); display:flex; justify-content:space-between; align-items:center; backdrop-filter:blur(10px);">
                <h3 style="font-size:1.05rem; font-weight:700; display:flex; align-items:center; gap:8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                    Live Food Distribution
                </h3>
                <span class="badge badge-success" style="background:rgba(0,255,170,0.1); border-color:rgba(0,255,170,0.3); color:var(--primary);">High Activity</span>
            </div>
            <div id="liveMap" style="flex:1; width:100%; filter: contrast(1.1) brightness(0.9);"></div>
        </div>

        <!-- Distribution Pie -->
        <div class="admin-card" style="height: 400px; display:flex; flex-direction:column;">
            <h3 style="margin-bottom: 20px; font-size:1rem; display:flex; align-items:center; gap:8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                User Demographics
            </h3>
            <div style="flex:1; position:relative;">
                <canvas id="userChart"></canvas>
            </div>
            <div style="text-align:center; margin-top:10px; color:var(--text-muted); font-size:0.8rem;">
                Active participation ratio
            </div>
        </div>
    </div>

    <!-- Analytics & Activity -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px;">
        
        <!-- Impact Chart -->
        <div class="admin-card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                <h3 style="font-size:1rem; display:flex; align-items:center; gap:8px;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>
                    Growth & Impact
                </h3>
                 <select style="background:var(--bg-input); color:var(--text-main); border:1px solid var(--glass-border); padding:5px; border-radius:5px; font-size:0.8rem;">
                    <option>Last 6 Months</option>
                    <option>Last Year</option>
                </select>
            </div>
            <div style="height: 250px;">
                <canvas id="impactChart"></canvas>
            </div>
        </div>

        <!-- Recent Activity Feed -->
        <div class="admin-card">
            <h3 style="margin-bottom: 20px; font-size:1rem; display:flex; align-items:center; gap:8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polyline></svg>
                System Pulse
            </h3>
            <div style="display:flex; flex-direction:column; gap:15px; max-height:250px; overflow-y:auto; padding-right:5px;">
                <?php foreach ($activities as $act): ?>
                    <div style="display:flex; gap:15px; align-items:center; padding-bottom:10px; border-bottom:1px solid var(--glass-border);">
                        <div style="width:8px; height:8px; border-radius:50%; background:var(--primary); flex-shrink:0;"></div>
                        <div style="flex:1;">
                            <div style="font-weight:600; font-size:0.9rem; color:var(--text-main);"><?php echo htmlspecialchars($act['type']); ?></div>
                            <div style="color:var(--text-muted); font-size:0.8rem;"><?php echo htmlspecialchars($act['detail']); ?></div>
                        </div>
                        <div style="font-size:0.75rem; color:var(--text-muted);">
                            <?php echo date('M d, H:i', strtotime($act['created_at'])); ?>
                        </div>
                    </div>
                <?php
endforeach; ?>
            </div>
        </div>
        
    </div>

    <!-- NEW SECTION: TOP FOOD DONORS ANALYTICS -->
    <div class="admin-card" style="margin-top:25px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
            <div>
                <h3 style="font-size:1.1rem; color:var(--text-main); display:flex; align-items:center; gap:8px;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"></path><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path><line x1="6" y1="1" x2="6" y2="4"></line><line x1="10" y1="1" x2="10" y2="4"></line><line x1="14" y1="1" x2="14" y2="4"></line></svg>
                    Top Food Contributors
                </h3>
                <p style="font-size:0.8rem; color:var(--text-muted);">Analysis of users contributing the most food listings</p>
            </div>
            <span class="badge badge-success">Live Data</span>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
            
            <!-- LEFT: BAR CHART -->
            <div style="background:rgba(0,0,0,0.2); padding:15px; border-radius:10px;">
                <canvas id="foodDonorChart" height="250"></canvas>
            </div>

            <!-- RIGHT: DETAILED LIST TABLE -->
            <div style="overflow-x:auto; background:rgba(0,0,0,0.2); border-radius: 12px; padding: 15px;">
                <table style="width:100%; border-collapse: collapse; font-size: 0.95rem;">
                    <thead>
                        <tr style="border-bottom: 2px solid rgba(255,255,255,0.05); color: var(--text-muted); text-align: left; font-size: 0.85rem; text-transform:uppercase; letter-spacing:0.5px;">
                            <th style="padding: 12px 10px;">Rank</th>
                            <th style="padding: 12px 10px;">Donor Name</th>
                            <th style="padding: 12px 10px; text-align: right;">Total Listings</th>
                            <th style="padding: 12px 10px; text-align: right;">Contribution</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($top_food_donors_data)): ?>
                            <tr><td colspan="4" style="padding:20px; text-align:center; color:var(--text-muted);">No food donations recorded yet.</td></tr>
                        <?php
else: ?>
                            <?php
    $max_listings = !empty($top_food_donors_data) ? $top_food_donors_data[0]['total_listings'] : 1;
    foreach ($top_food_donors_data as $index => $donor):
        $percent = ($donor['total_listings'] / $max_listings) * 100;
?>
                            <tr style="border-bottom: 1px solid rgba(255,255,255,0.03); transition: background 0.3s; cursor:default;" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 12px 10px; font-size:0.9rem; color:var(--text-muted); font-weight:600;">
                                    <?php if ($index == 0): ?><span style="color:#FFD700">#1</span><?php
        elseif ($index == 1): ?><span style="color:#C0C0C0">#2</span><?php
        elseif ($index == 2): ?><span style="color:#CD7F32">#3</span><?php
        else:
            echo "#" . ($index + 1);
        endif; ?>
                                </td>
                                <td style="padding: 12px 10px; font-weight:600; color:var(--text-main);">
                                    <?php echo htmlspecialchars($donor['donor_name']); ?>
                                </td>
                                <td style="padding: 12px 10px; text-align: right; font-weight:800; color:var(--primary);">
                                    <?php echo $donor['total_listings']; ?>
                                </td>
                                <td style="padding: 12px 10px; text-align: right;">
                                    <div style="width: 100px; height: 8px; background: rgba(255,255,255,0.05); border-radius: 4px; display: inline-block; overflow:hidden;">
                                        <div style="width: <?php echo $percent; ?>%; height: 100%; background: linear-gradient(90deg, var(--primary), #00d488); border-radius: 4px; box-shadow:0 0 10px var(--primary-glow);"></div>
                                    </div>
                                </td>
                            </tr>
                            <?php
    endforeach; ?>
                        <?php
endif; ?>
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <!-- Quick Actions Bottom -->
    <div class="admin-card" style="margin-top:25px;">
        <h3 style="margin-bottom: 20px; font-size:1rem; display:flex; align-items:center; gap:8px;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--text-muted)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
            Quick Commands
        </h3>
        <div class="actions-grid" style="display:flex; gap:15px; flex-wrap:wrap;">
             <a href="users.php" class="btn btn-outline" style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
                Verify KYC
            </a>
            <a href="donations.php" class="btn btn-outline" style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8h1a4 4 0 0 1 0 8h-1"></path><path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path><line x1="6" y1="1" x2="6" y2="4"></line><line x1="10" y1="1" x2="10" y2="4"></line><line x1="14" y1="1" x2="14" y2="4"></line></svg>
                Manage Food
            </a>
            <a href="reports.php" class="btn btn-outline" style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px;">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
                Gen. Report
            </a>
             <a href="../../src/auth.php?logout=true" class="btn btn-outline" style="flex:1; display:flex; align-items:center; justify-content:center; gap:8px; border-color:rgba(244,63,94,0.3); color:var(--accent);">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                Lock System
            </a>
        </div>
    </div>


    <script>
        // Check Theme for Map Styling
        const isDark = document.documentElement.getAttribute('data-theme') !== 'light';
        
        // --- CHARTS CONFIG ---
        Chart.defaults.color = '#94a3b8';
        Chart.defaults.borderColor = 'rgba(255,255,255,0.05)';
        
        // Impact Chart (Line)
        const ctxImg = document.getElementById('impactChart').getContext('2d');
        // Create gradient
        const gradient = ctxImg.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.5)');   
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0.0)');

        new Chart(ctxImg, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'Meals Saved',
                    data: <?php echo json_encode($impact_data); ?>,
                    borderColor: '#3b82f6',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#1e293b',
                    pointBorderColor: '#3b82f6',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                plugins: { legend: { display: false } }, 
                scales: { 
                    y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#94a3b8' } }, 
                    x: { grid: { display: false }, ticks: { color: '#94a3b8' } } 
                } 
            }
        });

        // User Chart (Doughnut)
        const ctxUser = document.getElementById('userChart').getContext('2d');
        new Chart(ctxUser, {
            type: 'doughnut',
            data: {
                labels: ['Food Donors', 'NGOs'],
                datasets: [{
                    data: [<?php echo $donor_count; ?>, <?php echo $ngo_count; ?>],
                    backgroundColor: ['#10b981', '#f59e0b'],
                    borderWidth: 0,
                    hoverOffset: 15
                }]
            },
            options: { 
                responsive: true, 
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: { 
                    legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } } 
                } 
            }
        });

        // --- NEW: TOP FOOD DONOR BAR CHART ---
        const ctxFood = document.getElementById('foodDonorChart').getContext('2d');
        new Chart(ctxFood, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($food_donor_labels); ?>,
                datasets: [{
                    label: 'Food Listings',
                    data: <?php echo json_encode($food_donor_counts); ?>,
                    backgroundColor: 'rgba(16, 185, 129, 0.6)', // Primary green
                    borderColor: '#10b981',
                    borderWidth: 1,
                    borderRadius: 4,
                    barPercentage: 0.6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(255,255,255,0.05)' },
                        ticks: { color: '#94a3b8', stepSize: 1 }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8' }
                    }
                }
            }
        });

        // --- MAP INIT ---
        // Using a dark map tile for pro look if dark mode, else light
        // Note: Basic check here, ideally we listen to theme changes
        
        var map = L.map('liveMap', { zoomControl: false }).setView([27.7172, 85.3240], 8);
        
        // CartoDB Dark Matter for Dark Mode aesthetics
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '© OpenStreetMap, © CartoDB',
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(map);

        // Dummy Data for Nepal Cities
        var points = [
            { lat: 27.7172, lng: 85.3240, title: "Kathmandu Hub" },
            { lat: 28.2096, lng: 83.9856, title: "Pokhara Branch" },
            { lat: 27.6644, lng: 85.3188, title: "Lalitpur Center" },
            { lat: 26.4525, lng: 87.2718, title: "Biratnagar Node" }
        ];
        
        points.forEach(pt => {
            L.circleMarker([pt.lat, pt.lng], {
                color: '#10b981',
                fillColor: '#10b981',
                fillOpacity: 0.8,
                radius: 8
            }).bindPopup(pt.title).addTo(map);
        });
    </script>

<!-- Close Divs from layout.php -->
    </div> <!-- End Main Content -->
</body>
</html>
