<?php
// public/admin/layout.php
if (session_status() === PHP_SESSION_NONE)
    session_start();
require_once '../../config/db.php';
require_once '../../src/functions.php';

// Security Check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
// redirect('../admin_login.php'); // Uncomment in prod
}

$page_title = isset($page_title) ? $page_title : 'Admin Experience';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> | ZeroWaste Admin</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Global Style & Theme System -->
    <link rel="stylesheet" href="../assets/css/style.css">
    <!-- Admin Specifics -->
    <link rel="stylesheet" href="../assets/css/admin.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="admin-body">

    <?php include '../includes/navbar.php'; ?>

    <div class="dashboard-layout">
        <main class="main-content">
        <div class="top-bar">
            <div style="display: flex; align-items: center; gap: 15px;">
                <button class="mobile-toggle" style="display: none; background: none; border: none; color: var(--text-main); cursor: pointer; padding: 5px;" onclick="toggleSidebar()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>
                <div>
                    <h2 style="font-weight: 700; font-size: 1.8rem;"><?php echo $page_title; ?></h2>
                    <p style="color: var(--text-muted); font-size: 0.9rem;">Overview of system performance and impact.</p>
                </div>
            </div>
            <div class="user-profile">
                <!-- System Status Indicator -->
                <div style="display: flex; align-items: center; gap: 8px; padding: 6px 12px; background: rgba(0, 255, 162, 0.05); border: 1px solid rgba(0, 255, 162, 0.1); border-radius: 50px; margin-right: 15px;">
                    <span class="pulse-glow"></span>
                    <span style="font-size: 0.75rem; font-weight: 600; color: var(--primary); text-transform: uppercase; letter-spacing: 0.5px;">Network Active</span>
                </div>

                <!-- Theme Toggle -->
                <button id="theme-toggle" class="theme-toggle-btn" aria-label="Toggle Dark Mode" style="margin:0; width:35px; height:35px;">
                    <!-- Icon Injected by JS -->
                </button>
                
                <?php
$unread_count = getUnreadCount($pdo);
$notifications = getUnreadNotifications($pdo, 5);
?>
                <div class="notification-wrapper" style="position: relative; margin-left:10px;">
                    <div style="position: relative; cursor: pointer;" onclick="toggleNotifications()">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-muted);"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"></path><path d="M13.73 21a2 2 0 0 1-3.46 0"></path></svg>
                        <?php if ($unread_count > 0): ?>
                            <span id="notif-badge" style="position: absolute; top:-2px; right:-2px; background:var(--primary); width:8px; height:8px; border-radius:50%; box-shadow: 0 0 10px var(--primary-glow); border: 2px solid var(--bg-panel);">
                            </span>
                        <?php
endif; ?>
                    </div>
                    
                    <!-- Notification Dropdown -->
                    <div id="notif-dropdown" style="display:none; position: absolute; right: 0; top: 45px; width: 320px; background: var(--glass-bg); backdrop-filter: blur(var(--glass-blur)); -webkit-backdrop-filter: blur(var(--glass-blur)); border: 1px solid var(--glass-border); border-top: 1px solid rgba(255,255,255,0.15); border-radius: 16px; box-shadow: 0 15px 35px var(--glass-shadow); z-index: 1000; overflow: hidden; transition: opacity 0.3s ease;">
                        <div style="padding: 15px 20px; border-bottom: 1px solid var(--glass-border); display:flex; justify-content:space-between; align-items:center; background: rgba(0,0,0,0.2);">
                            <span style="font-weight:700; font-size:0.95rem; color: var(--text-main);">Notifications</span>
                            <?php if ($unread_count > 0): ?>
                                <button onclick="markAllRead()" style="background:none; border:none; color:var(--primary); font-size:0.75rem; cursor:pointer; font-weight: 600;">Mark all read</button>
                            <?php
endif; ?>
                        </div>
                        <div id="notif-list" style="max-height: 300px; overflow-y: auto;">
                            <?php if (empty($notifications)): ?>
                                <div style="padding: 20px; text-align: center; color: var(--text-muted); font-size: 0.85rem;">
                                    No new notifications
                                </div>
                            <?php
else: ?>
                                <?php foreach ($notifications as $notif): ?>
                                    <div class="notif-item" style="padding: 12px 15px; border-bottom: 1px solid var(--glass-border); cursor:pointer;" onclick="markRead(<?php echo $notif['id']; ?>, this)">
                                        <div style="display:flex; justify-content:space-between; margin-bottom:4px;">
                                            <span style="font-size:0.75rem; font-weight:700; color:var(--primary);"><?php echo htmlspecialchars($notif['type']); ?></span>
                                            <span style="font-size:0.7rem; color:var(--text-muted);"><?php echo date('M d, H:i', strtotime($notif['created_at'])); ?></span>
                                        </div>
                                        <div style="font-size:0.85rem; color:var(--text-main); line-height:1.4;">
                                            <?php echo htmlspecialchars($notif['message']); ?>
                                        </div>
                                    </div>
                                <?php
    endforeach; ?>
                            <?php
endif; ?>
                        </div>
                        <div style="padding: 10px; text-align: center; background: rgba(0,0,0,0.02); border-top: 1px solid var(--glass-border);">
                            <a href="#" style="font-size: 0.8rem; color: var(--text-muted);">View All</a>
                        </div>
                    </div>
                </div>

                <!-- Professional Avatar -->
                <a href="profile.php" title="My Profile" style="text-decoration: none; margin-left: 20px; display: block;">
                    <div style="width: 42px; height: 42px; border-radius: 12px; border: 1px solid var(--glass-border); background: linear-gradient(135deg, rgba(255,255,255,0.05), rgba(255,255,255,0.01)); display: flex; align-items: center; justify-content: center; position: relative; overflow: hidden; transition: var(--transition);">
                        <img src="https://ui-avatars.com/api/?name=Admin&background=00ffa2&color=0a0a0c&bold=true" alt="Admin" style="width: 100%; height: 100%; object-fit: cover;">
                        <div style="position: absolute; bottom: 0; left: 0; width: 100%; height: 2px; background: var(--primary); opacity: 0.5;"></div>
                    </div>
                </a>
            </div>
        </div>

        <script>
            function toggleNotifications() {
                const drop = document.getElementById('notif-dropdown');
                drop.style.display = drop.style.display === 'none' ? 'block' : 'none';
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                const notifWrapper = document.querySelector('.notification-wrapper');
                
                if (notifWrapper && !notifWrapper.contains(e.target)) {
                    document.getElementById('notif-dropdown').style.display = 'none';
                }
            });

            function markRead(id, el) {
                fetch('mark_read.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ id: id })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        el.style.opacity = '0.5';
                        setTimeout(() => el.remove(), 300);
                        updateBadge(-1);
                    }
                });
            }

            function markAllRead() {
                fetch('mark_read.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'mark_all' })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const list = document.getElementById('notif-list');
                        list.innerHTML = '<div style="padding: 20px; text-align: center; color: var(--text-muted); font-size: 0.85rem;">No new notifications</div>';
                        const badge = document.getElementById('notif-badge');
                        if (badge) badge.remove();
                    }
                });
            }

            function updateBadge(change) {
                const badge = document.getElementById('notif-badge');
                if (badge) {
                    let count = parseInt(badge.innerText) + change;
                    if (count <= 0) {
                        badge.remove();
                    } else {
                        badge.innerText = count;
                    }
                }
            }
        </script>
        
        <!-- Flash Messages -->
        <?php if ($flash = getFlash()): ?>
            <div style="padding: 15px; border-radius: 10px; margin-bottom: 25px; background: <?php echo $flash['type'] == 'error' ? 'rgba(239,68,68,0.1)' : 'rgba(16,185,129,0.1)'; ?>; border: 1px solid <?php echo $flash['type'] == 'error' ? 'var(--danger)' : 'var(--success)'; ?>; color: <?php echo $flash['type'] == 'error' ? 'var(--danger)' : 'var(--success)'; ?>;">
                <?php echo $flash['message']; ?>
            </div>
        <?php
endif; ?>
        
        </main>
    </div>
    
    <script src="../assets/js/theme.js"></script>
</body>
</html>
