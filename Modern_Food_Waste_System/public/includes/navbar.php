<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../src/functions.php';

function getActiveStyle($page)
{
    return basename($_SERVER['PHP_SELF']) === $page ? 'active' : '';
}

// Logic: Logged in users get the Sidebar, Guests get the Navbar
$current_dir = basename(dirname($_SERVER['PHP_SELF']));
$is_admin_dir = ($current_dir === 'admin');
$base_path = $is_admin_dir ? '../' : '';

if (!isset($_SESSION['user_id'])):

?>
<!-- TOP NAVBAR (PUBLIC/GUEST) -->
<nav class="navbar container">
    <a href="<?php echo $base_path; ?>index.php" class="sidebar-logo" style="margin-bottom: 0;">
        <img src="<?php echo $base_path; ?>assets/images/admin_logo_3d.gif" alt="Logo" style="height: 40px; border-radius: 5px;">
        <span>ZeroWaste-<span style="color:var(--primary)">ZeroHunger</span></span>
    </a>
    
    <div class="nav-links">
        <a href="<?php echo $base_path; ?>index.php" class="<?php echo getActiveStyle('index.php'); ?>">Home</a>
        <a href="<?php echo $base_path; ?>about.php" class="<?php echo getActiveStyle('about.php'); ?>">About</a>
        <a href="<?php echo $base_path; ?>impact.php" class="<?php echo getActiveStyle('impact.php'); ?>">Impact</a>
        <a href="<?php echo $base_path; ?>leaderboard.php" class="<?php echo getActiveStyle('leaderboard.php'); ?>">Leaderboard</a>
        
        <div style="width: 1px; height: 20px; background: var(--glass-border); margin: 0 15px;"></div>
        
        <a href="<?php echo $base_path; ?>login.php" style="font-weight: 600;">Sign In</a>
        <a href="<?php echo $base_path; ?>register.php" class="btn btn-primary">Join Mission</a>
        
        <button id="theme-toggle" class="theme-toggle-btn" aria-label="Toggle Dark Mode" style="margin-left: 10px;"></button>
    </div>
</nav>

<?php
else:
    // DASHBOARD SIDEBAR (AUTHENTICATED)
    $unread_count = getUnreadCount($pdo);
    $role = $_SESSION['role'];
    $username = $_SESSION['username'];
?>
<aside class="sidebar-nav">
    <a href="<?php echo $base_path; ?>index.php" class="sidebar-logo">
        <img src="<?php echo $base_path; ?>assets/images/admin_logo_3d.gif" alt="Logo" style="height: 45px; border-radius: 5px; box-shadow: 0 0 15px var(--primary-glow);">
        <span>ZeroWaste<span style="color:var(--primary)">.</span></span>
    </a>

    <ul class="sidebar-menu">
        <li>
            <a href="<?php echo($role === 'admin') ? ($base_path . 'admin/dashboard.php') : ($base_path . 'dashboard.php'); ?>" class="sidebar-link <?php echo getActiveStyle('dashboard.php'); ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                Dashboard
            </a>
        </li>

        <?php if ($role === 'donor'): ?>
            <li>
                <a href="<?php echo $base_path; ?>donate.php" class="sidebar-link <?php echo getActiveStyle('donate.php'); ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"></line><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"></path></svg>
                    Direct Donation
                </a>
            </li>
        <?php
    endif; ?>

        <?php if ($role === 'admin'): ?>
            <li>
                <a href="<?php echo $base_path; ?>admin/users.php" class="sidebar-link <?php echo getActiveStyle('users.php'); ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    Users & KYC
                </a>
            </li>
        <?php
    endif; ?>

        <?php if ($role === 'volunteer'): ?>
            <li>
                <a href="<?php echo $base_path; ?>dashboard_volunteer.php" class="sidebar-link <?php echo getActiveStyle('dashboard_volunteer.php'); ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="3" width="15" height="13"></rect><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon><circle cx="5.5" cy="18.5" r="2.5"></circle><circle cx="18.5" cy="18.5" r="2.5"></circle></svg>
                    Missions
                </a>
            </li>
            <li>
                <a href="<?php echo $base_path; ?>directory.php" class="sidebar-link <?php echo getActiveStyle('directory.php'); ?>">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                    Directory
                </a>
            </li>
        <?php
    endif; ?>

        <li>
            <a href="<?php echo $base_path; ?>chat.php" class="sidebar-link <?php echo getActiveStyle('chat.php'); ?>" style="position: relative;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                Messages
                <?php if ($unread_count > 0): ?>
                    <span style="position: absolute; top: 12px; right: 15px; width: 8px; height: 8px; background: var(--primary); border-radius: 50%; box-shadow: 0 0 10px var(--primary-glow);"></span>
                <?php
    endif; ?>
            </a>
        </li>

        <li>
            <a href="<?php echo $base_path; ?>leaderboard.php" class="sidebar-link <?php echo getActiveStyle('leaderboard.php'); ?>">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="7"></circle><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline></svg>
                Ranking
            </a>
        </li>
    </ul>

    <div class="sidebar-footer">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 20px; padding: 0 10px;">
            <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($username); ?>&background=00ffa2&color=0a0a0c" style="width: 40px; height: 40px; border-radius: 12px;">
            <div style="overflow: hidden;">
                <p style="font-weight: 700; font-size: 0.9rem; color: var(--text-main); white-space: nowrap; text-overflow: ellipsis;"><?php echo htmlspecialchars($username); ?></p>
                <p style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;"><?php echo $role; ?></p>
            </div>
        </div>
        <a href="<?php echo $base_path; ?>src/auth.php?logout=true" class="sidebar-link" style="color: var(--accent);">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
            Sign Out
        </a>
    </div>
</aside>

<?php
endif; ?>
<script src="<?php echo $base_path; ?>assets/js/theme.js"></script>
<?php include_once __DIR__ . '/sathi_widget.php'; ?>
