<?php
session_start();
require_once '../src/functions.php';
$flash = getFlash();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | ZeroWaste-ZeroHunger</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: radial-gradient(circle at top right, #0f172a, #020617);
            overflow: hidden;
        }
        
        /* Scan Line Animation */
        .scan-line {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 2px;
            background: linear-gradient(90deg, transparent, var(--primary), transparent);
            opacity: 0.15;
            z-index: 100;
            animation: scan 4s linear infinite;
        }
        @keyframes scan { 0% { top: -2%; } 100% { top: 102%; } }

        .auth-card {
            position: relative;
            z-index: 10;
            border: 1px solid rgba(255, 255, 255, 0.08);
            background: rgba(10, 10, 12, 0.7);
            backdrop-filter: blur(24px);
            padding: 40px;
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .input-group { position: relative; margin-bottom: 25px; }
        .form-input {
            width: 100%;
            padding: 16px 16px 16px 48px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: #f8fafc;
            font-size: 0.95rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .form-input:focus { border-color: var(--primary); background: rgba(255, 255, 255, 0.05); box-shadow: 0 0 20px rgba(0, 255, 162, 0.15); }
        
        .input-icon { position: absolute; left: 16px; top: 16px; color: #94a3b8; transition: color 0.3s; }
        .form-input:focus + .input-icon { color: var(--primary); }

        .social-btn {
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            cursor: pointer;
            color: #f8fafc;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.3s;
        }
        .social-btn:hover { background: rgba(255, 255, 255, 0.06); border-color: rgba(255, 255, 255, 0.2); transform: translateY(-1px); }
    </style>
</head>
<body>

    <div class="scan-line"></div>
    <div class="corner-toggle">
        <button id="theme-toggle" class="theme-toggle-btn" aria-label="Toggle Dark Mode"></button>
    </div>
    
    <link rel="stylesheet" href="assets/css/3d_logo.css">
    <div class="glass-card auth-card" style="width: 100%; max-width: 420px; animation: fadeInUp 0.5s ease;">
        <div class="auth-header">
            <a href="index.php" style="text-decoration:none;">
                <img src="assets/images/admin_logo_3d.gif" alt="ZeroWaste-ZeroHunger" class="logo-3d admin-logo-3d" style="width: 100px; height: 100px;">
            </a>
            <h2 style="font-size: 1.5rem; color: var(--text-main);">Welcome Back</h2>
            <p style="color: var(--text-muted); margin-top: 5px;">Enter your credentials to access your account</p>
        </div>
        
        <?php if ($flash): ?>
            <div style="background: <?php echo $flash['type'] == 'error' ? 'rgba(255,0,80,0.1)' : 'rgba(0,255,136,0.1)'; ?>; padding: 15px; border-radius: 8px; margin-bottom: 25px; color: var(--text-main); border: 1px solid <?php echo $flash['type'] == 'error' ? '#ff0055' : 'var(--primary)'; ?>; display: flex; align-items: center; gap: 10px;">
                <span><?php echo $flash['type'] == 'error' ? '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>' : '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>'; ?></span>
                <?php echo $flash['message']; ?>
            </div>
        <?php
endif; ?>

        <form action="../src/auth.php" method="POST">
            <?php echo csrfInput(); ?>

            <div class="input-group">
                <input type="email" name="email" class="form-input" placeholder=" " required>
                <span class="input-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg></span>
                <label style="position:absolute; left: 45px; top: 15px; pointer-events: none; color: var(--text-muted); transition: all 0.3s; opacity: 0.7;">Email Address</label>
            </div>
            <style>
                .form-input:focus ~ label,
                .form-input:not(:placeholder-shown) ~ label {
                    top: -10px;
                    left: 0;
                    font-size: 0.8rem;
                    color: var(--primary);
                }
            </style>
            
            <div class="input-group">
                <input type="password" name="password" class="form-input" placeholder=" " required>
                <span class="input-icon"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg></span>
                <label style="position:absolute; left: 45px; top: 15px; pointer-events: none; color: var(--text-muted); transition: all 0.3s; opacity: 0.7;">Password</label>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; font-size: 0.9rem;">
                <label style="display: flex; align-items: center; cursor: pointer; color: var(--text-muted);">
                    <input type="checkbox" style="margin-right: 8px; accent-color: var(--primary);"> Remember me
                </label>
                <a href="#" style="color: var(--primary); text-decoration: none;">Forgot Password?</a>
            </div>

            <button type="submit" name="login_btn" class="btn btn-primary" style="width: 100%; padding: 14px;">
                Sign In
            </button>
        </form>

        <div class="divider">
            <span>OR CONTINUE WITH</span>
        </div>

        <div class="social-login" style="flex-direction: column; gap: 10px;">
            <!-- Official Google Sign-In Button -->
            <div id="g_id_onload"
                 data-client_id="YOUR_GOOGLE_CLIENT_ID"
                 data-context="signin"
                 data-ux_mode="popup"
                 data-callback="handleCredentialResponse"
                 data-auto_prompt="false">
            </div>
            <div class="g_id_signin"
                 data-type="standard"
                 data-shape="rectangular"
                 data-theme="outline"
                 data-text="continue_with"
                 data-size="large"
                 data-logo_alignment="left"
                 style="width: 100%;">
            </div>

            <div style="display: flex; gap: 15px; width: 100%;">
                <a href="../src/social_auth.php?provider=facebook" class="social-btn" title="Sign in with Facebook" style="flex: 1;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/b/b8/2021_Facebook_icon.svg" alt="Facebook" style="width:20px;">
                </a>
                <a href="../src/social_auth.php?provider=linkedin" class="social-btn" title="Sign in with LinkedIn" style="flex: 1;">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/c/ca/LinkedIn_logo_initials.png" alt="LinkedIn" style="width:20px;">
                </a>
            </div>
        </div>

        <p style="text-align: center; margin-top: 20px; color: var(--text-muted); font-size: 0.9rem;">
            Don't have an account? <a href="register.php" style="color: var(--primary); font-weight: 600; text-decoration: none;">Create Account</a>
        </p>
    </div>

    <!-- Theme Script -->
    <!-- Google Identity Services SDK -->
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <script src="assets/js/theme.js"></script>
    <script>
        function handleCredentialResponse(response) {
            // Redirect to our social handler with the credential
            window.location.href = `../src/social_auth.php?provider=google&credential=${response.credential}`;
        }
    </script>
    <!-- Animation Keyframes -->
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</body>
</html>
