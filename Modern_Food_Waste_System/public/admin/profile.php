<?php
// public/admin/profile.php
require_once '../../config/db.php';
$page_title = 'My Profile';
require_once 'layout.php';

$user = getCurrentUser($pdo);
$error = '';
$success = '';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update Profile Info
    if (isset($_POST['update_profile'])) {
        $username = sanitize($_POST['username']);
        
        if (!empty($username)) {
            $stmt = $pdo->prepare("UPDATE users SET username = ? WHERE id = ?");
            if ($stmt->execute([$username, $_SESSION['user_id']])) {
                $_SESSION['username'] = $username; // Update session
                $success = "Profile updated successfully!";
                // Refresh user data
                $user = getCurrentUser($pdo);
            } else {
                $error = "Failed to update profile.";
            }
        }
    }

    // Change Password
    if (isset($_POST['change_password'])) {
        $current_pass = $_POST['current_password'];
        $new_pass = $_POST['new_password'];
        $confirm_pass = $_POST['confirm_password'];

        if (password_verify($current_pass, $user['password_hash'])) {
            if ($new_pass === $confirm_pass) {
                if (strlen($new_pass) >= 6) {
                    $new_hash = password_hash($new_pass, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
                    if ($stmt->execute([$new_hash, $_SESSION['user_id']])) {
                        $success = "Password changed successfully!";
                    } else {
                        $error = "Failed to update password.";
                    }
                } else {
                    $error = "New password must be at least 6 characters.";
                }
            } else {
                $error = "New passwords do not match.";
            }
        } else {
            $error = "Incorrect current password.";
        }
    }
}
?>

<div class="admin-card" style="max-width: 800px; margin: 0 auto;">
    <h3 style="margin-bottom: 25px; border-bottom: 1px solid var(--glass-border); padding-bottom: 15px;">Admin Details</h3>

    <?php if($success): ?>
        <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid var(--success); color: var(--success); padding: 10px; border-radius: 6px; margin-bottom: 20px;">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <?php if($error): ?>
        <div style="background: rgba(239, 68, 68, 0.1); border: 1px solid var(--danger); color: var(--danger); padding: 10px; border-radius: 6px; margin-bottom: 20px;">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
        <!-- Left Col: Personal Info -->
        <div>
            <form method="POST">
                <div class="form-group">
                    <label class="form-label" style="display:block; margin-bottom:5px; color:var(--text-muted);">Email Address</label>
                    <input type="email" class="form-input" value="<?php echo htmlspecialchars($user['email']); ?>" disabled style="width:100%; opacity:0.7; cursor:not-allowed;">
                    <small style="color:var(--text-muted);">Email cannot be changed directly.</small>
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label class="form-label" style="display:block; margin-bottom:5px; color:var(--text-muted);">Role</label>
                    <input type="text" class="form-input" value="<?php echo ucfirst(htmlspecialchars($user['role'])); ?>" disabled style="width:100%; opacity:0.7;">
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label class="form-label" style="display:block; margin-bottom:5px; color:var(--text-muted);">Username</label>
                    <input type="text" name="username" class="form-input" value="<?php echo htmlspecialchars($user['username']); ?>" style="width:100%;">
                </div>

                <button type="submit" name="update_profile" class="btn-action" style="background: var(--primary); color: white; margin-top: 20px;">Save Profile</button>
            </form>
        </div>

        <!-- Right Col: Security -->
        <div style="padding-left: 30px; border-left: 1px solid var(--glass-border);">
            <h4 style="margin-bottom: 20px; color: var(--text-main);">Change Password</h4>
            
            <form method="POST">
                <div class="form-group">
                    <label class="form-label" style="display:block; margin-bottom:5px; color:var(--text-muted);">Current Password</label>
                    <input type="password" name="current_password" class="form-input" style="width:100%;" required>
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label class="form-label" style="display:block; margin-bottom:5px; color:var(--text-muted);">New Password</label>
                    <input type="password" name="new_password" class="form-input" style="width:100%;" required>
                </div>

                <div class="form-group" style="margin-top: 15px;">
                    <label class="form-label" style="display:block; margin-bottom:5px; color:var(--text-muted);">Confirm New Password</label>
                    <input type="password" name="confirm_password" class="form-input" style="width:100%;" required>
                </div>

                <button type="submit" name="change_password" class="btn-action" style="background: var(--danger); color: white; margin-top: 20px;">Update Password</button>
            </form>
        </div>
    </div>
</div>

<!-- Close Divs from layout.php -->
    </div> <!-- End Main Content -->
</body>
</html>
