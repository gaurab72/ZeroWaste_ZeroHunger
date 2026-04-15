<?php
// Dependencies are handled by navbar.php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | ZeroWaste-ZeroHunger</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .about-section {
            padding: 60px 0;
        }
        .team-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }
        .team-card {
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 20px;
            text-align: center;
        }
        .team-avatar {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: var(--glass-border);
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: var(--secondary);
        }
    </style>
</head>
<body>

    <?php include 'includes/navbar.php'; ?>
    <!-- RE-INSERTING NAV CORRECTLY -->
    <!-- Navbar is included at the top via requires -->

    <div class="container about-section">
        <div class="glass-card" style="margin-bottom: 50px;">
            <h1 class="text-gradient" style="text-align: center; margin-bottom: 30px;">Our Vision & Logistics</h1>
            <p style="font-size: 1.1rem; line-height: 1.8; color: var(--text-muted); text-align: justify;">
                ZeroWaste-ZeroHunger is more than a platform; it's a **Logistics Operating System for Social Good.** 
                We solve the inefficiency of urban food waste by providing the infrastructure to connect surplus resources directly with high-impact community centers, orphanages, and shelters.
            </p>
            <br>
            <p style="font-size: 1.1rem; line-height: 1.8; color: var(--text-muted); text-align: justify;">
                We bridge this gap. Our system connects event organizers and kind-hearted hosts directly with orphanages ("Bal Mandirs") and elderly care homes. We ensure that the joy of your celebration extends to children who have lost their parents and individuals who have no one else to care for them.
            </p>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px; margin-bottom: 80px;">
            <div>
                <h2 style="color: var(--secondary); margin-bottom: 20px;">Operational Vision</h2>
                <p style="line-height: 1.6; color: var(--text-muted);">
                    To create a friction-less, compassion-driven network where surplus food from every major celebration contributes directly to society's most vulnerable. We are building the infrastructure for a planet where zero waste equals zero hunger.
                </p>
            </div>
            <div>
                <h2 style="color: var(--primary); margin-bottom: 20px;">What We Rescue</h2>
                <ul style="list-style: none; color: var(--text-muted); line-height: 2;">
                    <li style="display:flex; align-items:center; gap:10px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        <strong>Wedding Feasts:</strong> High-quality surplus from marriage receptions.
                    </li>
                    <li style="display:flex; align-items:center; gap:10px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                        <strong>Birthday Treats:</strong> Cakes and meals from parties.
                    </li>
                    <li style="display:flex; align-items:center; gap:10px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <strong>Corporate Events:</strong> Excess food from large gatherings.
                    </li>
                    <li style="display:flex; align-items:center; gap:10px;">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <strong>Orphanage Support:</strong> Prioritizing homes for children without parents.
                    </li>
                </ul>
            </div>
        </div>

        <h2 style="text-align: center;">Meet The Founder & Developer</h2>
        <div class="team-grid" style="grid-template-columns: 1fr; max-width: 500px; margin: 50px auto 0;">
            <div class="team-card" style="padding: 40px; border: 2px solid var(--primary); box-shadow: 0 0 30px var(--primary-glow); border-radius: 30px; background: rgba(0, 255, 163, 0.02);">
                <div class="team-avatar-container" style="position: relative; width: 150px; height: 150px; margin: 0 auto 25px;">
                    <img src="assets/images/admin_profile.png" alt="Gaurab Hamal" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%; border: 3px solid var(--primary); box-shadow: 0 0 20px var(--primary-glow);">
                    <div style="position: absolute; bottom: 5px; right: 5px; background: var(--primary); width: 25px; height: 25px; border-radius: 50%; border: 3px solid var(--bg-panel); display: flex; align-items: center; justify-content: center;" title="Verified Developer">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"></polyline></svg>
                    </div>
                </div>
                <h3 style="font-size: 1.8rem; margin-bottom: 5px; letter-spacing: 1px;">Gaurab Hamal</h3>
                <p style="color:var(--primary); font-size: 1.1rem; font-weight: bold; letter-spacing: 2px; text-transform: uppercase;">Founder & Lead Developer</p>
                <p style="color:var(--text-muted); font-size: 1rem; margin-top: 20px; line-height: 1.6;">
                    "Driven by the vision of using technology to connect surplus resources with those who need them most. 
                    Building systems that combine modern tech with social impact."
                </p>
                <div style="margin-top: 25px; display: flex; justify-content: center; gap: 20px;">
                    <span style="cursor: pointer;" title="GitHub">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 19c-5 1.5-5-2.5-7-3m14 6v-3.87a3.37 3.37 0 0 0-.94-2.61c3.14-.35 6.44-1.54 6.44-7A5.44 5.44 0 0 0 20 4.77 5.07 5.07 0 0 0 19.91 1S18.73.65 16 2.48a13.38 13.38 0 0 0-7 0C6.27.65 5.09 1 5.09 1A5.07 5.07 0 0 0 5 4.77a5.44 5.44 0 0 0-1.5 3.78c0 5.42 3.3 6.61 6.44 7A3.37 3.37 0 0 0 9 18.13V22"></path></svg>
                    </span>
                    <span style="cursor: pointer;" title="LinkedIn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                    </span>
                    <span style="cursor: pointer;" title="Email">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    </span>
                </div>
            </div>
        </div>

        <div class="glass-card" style="margin-top: 50px;">
            <h2 style="color: var(--primary); text-align: center; margin-bottom: 30px;">Professional Impact & Experience</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 30px; margin-bottom: 40px;">
                <div style="text-align: center;">
                    <div style="margin-bottom: 15px; color: var(--primary);">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>
                    </div>
                    <h4>Unified Network</h4>
                    <p style="font-size: 0.9rem; color: var(--text-muted);">Real-time coordination between Donors, Volunteers, and NGOs across Nepal.</p>
                </div>
                <div style="text-align: center;">
                    <div style="margin-bottom: 15px; color: var(--secondary);">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path></svg>
                    </div>
                    <h4>Direct Coordination</h4>
                    <p style="font-size: 0.9rem; color: var(--text-muted);">A comprehensive directory for volunteers to proactively message and coordinate rescue missions.</p>
                </div>
                <div style="text-align: center;">
                    <div style="margin-bottom: 15px; color: var(--primary);">
                        <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    </div>
                    <h4>Live Tracking</h4>
                    <p style="font-size: 0.9rem; color: var(--text-muted);">Secure live location sharing for safe and efficient food transport and delivery.</p>
                </div>
            </div>
            <div style="text-align: center; border-top: 1px solid var(--glass-border); padding-top: 30px;">
                <a href="professional_experience.php" class="btn btn-primary" style="padding: 12px 40px;">
                    Explore Technical Showcase & Architecture →
                </a>
            </div>
        </div>

    </div>

    <footer class="container" style="padding: 40px 0; border-top: 1px solid var(--glass-border); margin-top: 50px; text-align: center; color: var(--text-muted);">
        <p>&copy; <?php echo date('Y'); ?> ZeroWaste System. Built with ❤️ for a sustainable future.</p>
    </footer>

</body>
</html>
