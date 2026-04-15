<?php
// Dependencies and session are handled by navbar.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us | ZeroWaste-ZeroHunger</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

    <?php include 'includes/navbar.php'; ?>

    <div class="container" style="margin-top: 50px; padding-bottom: 50px;">
        <div class="glass-card" style="max-width: 800px; margin: 0 auto;">
            <h1 class="text-gradient" style="text-align: center; margin-bottom: 30px;">Get in Touch</h1>
            <p style="text-align: center; color: var(--text-muted); margin-bottom: 40px;">
                Have questions about food donation or volunteering? Reach out to our team directly.
            </p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <!-- Contact Info -->
                <div>
                    <h3 style="color: var(--primary); margin-bottom: 25px;">Direct Channels</h3>
                    
                    <div class="glass-card" style="margin-bottom: 15px; padding: 15px; display: flex; align-items: center; gap: 15px; border-color: rgba(255,255,255,0.05);">
                        <div style="color: var(--primary); background: rgba(0,255,162,0.1); padding: 10px; border-radius: 8px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Email</span>
                            <a href="mailto:gaurabhamal23@gmail.com" style="color: #f8fafc; font-size: 1rem; text-decoration: none;">gaurabhamal23@gmail.com</a>
                        </div>
                    </div>

                    <div class="glass-card" style="margin-bottom: 15px; padding: 15px; display: flex; align-items: center; gap: 15px; border-color: rgba(255,255,255,0.05);">
                        <div style="color: var(--secondary); background: rgba(99,102,241,0.1); padding: 10px; border-radius: 8px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path></svg>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Phone</span>
                            <a href="tel:9815114901" style="color: #f8fafc; font-size: 1rem; text-decoration: none;">9815114901</a>
                        </div>
                    </div>

                    <div class="glass-card" style="margin-bottom: 15px; padding: 15px; display: flex; align-items: center; gap: 15px; border-color: rgba(255,255,255,0.05);">
                        <div style="color: #ffd700; background: rgba(255,215,0,0.1); padding: 10px; border-radius: 8px;">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                        </div>
                        <div>
                            <span style="display: block; font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase;">Location</span>
                            <span style="color: #f8fafc; font-size: 1rem;">Pokhara-17, Chhorepatan</span>
                        </div>
                    </div>
                </div>

                <!-- Map / Image Area -->
                <div style="background: rgba(255,255,255,0.05); border-radius: 12px; display: flex; align-items: center; justify-content: center; min-height: 250px;">
                   <img src="assets/images/contact_hero.jpg" alt="Contact Support" style="width: 100%; height: 100%; object-fit: cover; border-radius: 12px; opacity: 0.8;">
                </div>
            </div>
            
            <hr style="border: 0; border-top: 1px solid var(--glass-border); margin: 40px 0;">

            <!-- Simple Form -->
            <h3 style="margin-bottom: 20px;">Send a Message</h3>
            <form>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" class="form-input" placeholder="John Doe">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-input" placeholder="john@example.com">
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Message</label>
                    <textarea class="form-input" rows="4" placeholder="How can we help?"></textarea>
                </div>
                <button type="button" class="btn btn-primary">Send Message</button>
            </form>

        </div>
    </div>

</body>
</html>
