<?php
/** @var string|null $error */
$B = $baseUrl ?? (defined("BASE_URL") ? BASE_URL : "");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAG MSASANI - Church Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        html, body { width: 100%; height: 100%; font-family: "Poppins", sans-serif; overflow: hidden; }
        .login-body { background: #0f0f23; }
        .login-bg-container { position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 0; overflow: hidden; }
        .login-gradient-bg { position: absolute; width: 200%; height: 200%; background: linear-gradient(135deg, #4C1D95 0%, #3b0764 25%, #1e3a8a 50%, #1e3a8a 75%, #4C1D95 100%); animation: gradientShift 15s ease infinite; filter: blur(80px); }
        @keyframes gradientShift { 0% { transform: translate(0, 0); } 50% { transform: translate(-50px, -50px); } 100% { transform: translate(0, 0); } }
        .login-particles { position: absolute; width: 100%; height: 100%; background-image: radial-gradient(2px 2px at 20px 30px, #eab308, rgba(234, 179, 8, 0)), radial-gradient(2px 2px at 60px 70px, #d4af37, rgba(212, 175, 55, 0)); background-repeat: repeat; background-size: 200px 200px; animation: particleFloat 20s linear infinite; opacity: 0.6; }
        @keyframes particleFloat { 0% { transform: translate(0, 0); } 100% { transform: translate(-200px, -200px); } }
        .login-container { position: relative; z-index: 10; display: flex; width: 100%; height: 100%; animation: containerFadeIn 0.8s ease-out; }
        @keyframes containerFadeIn { from { opacity: 0; } to { opacity: 1; } }
        .login-left-panel { flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px; background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(20px); border-right: 1px solid rgba(255, 255, 255, 0.1); }
        .login-left-content { max-width: 400px; text-align: center; animation: slideInLeft 0.8s ease-out 0.1s both; }
        @keyframes slideInLeft { from { opacity: 0; transform: translateX(-40px); } to { opacity: 1; transform: translateX(0); } }
        .login-logo-large { width: 100px; height: 100px; margin: 0 auto 30px; animation: floatIcon 3s ease-in-out infinite; }
        @keyframes floatIcon { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-20px); } }
        .login-logo-large svg { width: 100%; height: 100%; filter: drop-shadow(0 0 20px rgba(250, 204, 21, 0.4)); }
        .login-left-title { font-size: 36px; font-weight: 700; color: #FACC15; margin-bottom: 8px; font-family: "Playfair Display", serif; letter-spacing: 1px; }
        .login-left-tagline { font-size: 14px; color: rgba(255, 255, 255, 0.6); margin-bottom: 20px; text-transform: uppercase; letter-spacing: 2px; }
        .login-left-description { font-size: 16px; color: rgba(255, 255, 255, 0.8); line-height: 1.6; margin-bottom: 40px; font-weight: 300; }
        .login-features-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .login-feature-card { padding: 20px; background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(250, 204, 21, 0.2); border-radius: 15px; text-align: center; transition: all 0.3s ease; }
        .login-feature-card:hover { background: rgba(255, 255, 255, 0.08); border-color: rgba(250, 204, 21, 0.4); transform: translateY(-5px); }
        .feature-icon { font-size: 28px; margin-bottom: 10px; }
        .login-feature-card p { font-size: 13px; color: rgba(255, 255, 255, 0.8); font-weight: 500; }
        .login-right-panel { flex: 1; display: flex; align-items: center; justify-content: center; padding: 40px; }
        .login-glass-card { width: 100%; max-width: 420px; background: rgba(255, 255, 255, 0.08); backdrop-filter: blur(20px); border: 1px solid rgba(255, 255, 255, 0.15); border-radius: 24px; padding: 50px 40px; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3), inset 0 1px 1px rgba(255, 255, 255, 0.1); animation: slideInRight 0.8s ease-out 0.2s both; }
        @keyframes slideInRight { from { opacity: 0; transform: translateX(40px); } to { opacity: 1; transform: translateX(0); } }
        .glass-card-header { margin-bottom: 30px; }
        .glass-title { font-size: 32px; font-weight: 700; color: #ffffff; margin-bottom: 8px; font-family: "Playfair Display", serif; }
        .glass-subtitle { font-size: 14px; color: rgba(255, 255, 255, 0.7); font-weight: 300; }
        .login-error-message { display: flex; align-items: center; gap: 12px; padding: 12px 16px; background: rgba(239, 68, 68, 0.15); border: 1px solid rgba(239, 68, 68, 0.3); border-radius: 12px; color: #fca5a5; font-size: 13px; margin-bottom: 20px; animation: shakeError 0.5s ease-out; }
        @keyframes shakeError { 0%, 100% { transform: translateX(0); } 25% { transform: translateX(-10px); } 75% { transform: translateX(10px); } }
        .login-form { display: flex; flex-direction: column; gap: 20px; }
        .form-group { display: flex; flex-direction: column; gap: 8px; }
        .form-label { font-size: 13px; font-weight: 500; color: rgba(255, 255, 255, 0.8); text-transform: uppercase; letter-spacing: 0.5px; }
        .input-wrapper { position: relative; display: flex; align-items: center; }
        .input-icon { position: absolute; left: 15px; width: 18px; height: 18px; color: rgba(250, 204, 21, 0.6); pointer-events: none; transition: color 0.3s ease; }
        .glass-input { width: 100%; background: rgba(255, 255, 255, 0.05); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 12px; padding: 14px 15px 14px 45px; color: #ffffff; font-size: 14px; font-family: "Poppins", sans-serif; transition: all 0.3s ease; }
        .glass-input::placeholder { color: rgba(255, 255, 255, 0.4); }
        .glass-input:focus { outline: none; background: rgba(255, 255, 255, 0.08); border-color: rgba(250, 204, 21, 0.5); box-shadow: 0 0 20px rgba(250, 204, 21, 0.2); }
        .login-button { position: relative; margin-top: 10px; padding: 14px 24px; background: linear-gradient(135deg, #FACC15 0%, #d4af37 100%); color: #1a1a2e; border: none; border-radius: 12px; font-size: 15px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; overflow: hidden; font-family: "Poppins", sans-serif; box-shadow: 0 4px 15px rgba(250, 204, 21, 0.3); }
        .login-button:hover { transform: translateY(-2px); box-shadow: 0 8px 25px rgba(250, 204, 21, 0.4); }
        .login-button:active { transform: translateY(0); }
        .button-glow { position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: radial-gradient(circle, rgba(255, 255, 255, 0.3) 0%, transparent 70%); pointer-events: none; opacity: 0; animation: buttonGlow 2s ease-in-out infinite; }
        @keyframes buttonGlow { 0%, 100% { opacity: 0; } 50% { opacity: 1; } }
        .login-demo-info { margin-top: 30px; padding-top: 20px; border-top: 1px solid rgba(255, 255, 255, 0.1); text-align: center; animation: fadeIn 0.8s ease-out 0.4s both; }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        .demo-label { font-size: 12px; color: rgba(255, 255, 255, 0.5); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px; }
        .demo-text { font-size: 13px; color: #FACC15; font-weight: 500; font-family: "Courier New", monospace; }
        @media (max-width: 768px) {
            .login-container { flex-direction: column; }
            .login-left-panel { display: none; }
            .login-right-panel { padding: 20px; }
            .login-glass-card { padding: 40px 24px; max-width: 100%; }
            .glass-title { font-size: 26px; }
        }
    </style>
</head>
<body class="login-body">
    <div class="login-bg-container">
        <div class="login-gradient-bg"></div>
        <div class="login-particles"></div>
    </div>
    <div class="login-container">
        <div class="login-left-panel">
            <div class="login-left-content">
                <div class="login-logo-large">
                    <svg viewBox="0 0 100 100" fill="none">
                        <path d="M50 10 L60 30 L80 35 L65 50 L70 70 L50 60 L30 70 L35 50 L20 35 L40 30 Z" fill="#FACC15" opacity="0.8"/>
                        <circle cx="50" cy="50" r="45" stroke="#FACC15" stroke-width="1" opacity="0.3"/>
                    </svg>
                </div>
                <h2 class="login-left-title">TAG MSASANI</h2>
                <p class="login-left-tagline">Spiritual Church Management</p>
                <p class="login-left-description">A warm, organized, and prayerful space to serve your congregation with excellence.</p>
                <div class="login-features-grid">
                    <div class="login-feature-card"><div class="feature-icon"></div><p>Members</p></div>
                    <div class="login-feature-card"><div class="feature-icon"></div><p>Events</p></div>
                    <div class="login-feature-card"><div class="feature-icon"></div><p>Finance</p></div>
                    <div class="login-feature-card"><div class="feature-icon"></div><p>Reports</p></div>
                </div>
            </div>
        </div>
        <div class="login-right-panel">
            <div class="login-glass-card">
                <div class="glass-card-header">
                    <h1 class="glass-title">Welcome back</h1>
                    <p class="glass-subtitle">Sign in to your account to continue</p>
                </div>
                <?php if ($error): ?>
                    <div class="login-error-message">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>
                <form method="POST" action="<?= htmlspecialchars($B . '/login') ?>" class="login-form">
                    <div class="form-group">
                        <label for="phone" class="form-label">Phone Number</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                            </svg>
                            <input type="tel" id="phone" name="phone" placeholder="+255 700 000 001" class="glass-input" value="+255700000001" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-wrapper">
                            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                            <input type="password" id="password" name="password" placeholder="" class="glass-input" value="password123" required>
                        </div>
                    </div>
                    <button type="submit" class="login-button">
                        <span>Sign in</span>
                        <div class="button-glow"></div>
                    </button>
                </form>
                <div class="login-demo-info">
                    <p class="demo-label"> Demo Credentials:</p>
                    <p class="demo-text">+255700000001 / password123</p>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const formGroups = document.querySelectorAll(".form-group");
            formGroups.forEach((group, index) => {
                group.style.opacity = "0";
                group.style.animation = `slideInRight 0.6s ease-out ${0.3 + (index * 0.1)}s forwards`;
            });
        });
    </script>
</body>
</html>

