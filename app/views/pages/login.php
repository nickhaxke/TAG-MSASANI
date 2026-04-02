<?php
/** @var string|null $error */
$B = $baseUrl ?? (defined("BASE_URL") ? BASE_URL : "");
$cn = htmlspecialchars($churchName ?? 'Church CMS', ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $cn ?> - Church Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --gold: #FACC15; --gold-dim: rgba(250,204,21,.35); --gold-glow: rgba(250,204,21,.18);
            --surface: rgba(255,255,255,.06); --surface-hover: rgba(255,255,255,.10);
            --border: rgba(255,255,255,.10); --border-focus: rgba(250,204,21,.50);
            --text: #fff; --text-dim: rgba(255,255,255,.55); --text-mid: rgba(255,255,255,.75);
            --danger-bg: rgba(239,68,68,.12); --danger-border: rgba(239,68,68,.28); --danger-text: #fca5a5;
        }
        html, body { width: 100%; height: 100%; font-family: "Inter", system-ui, sans-serif; overflow: hidden; background: #0c0c1d; color: var(--text); }

        /* ── Animated background ── */
        .bg-wrap { position: fixed; inset: 0; z-index: 0; overflow: hidden; }
        .bg-gradient { position: absolute; inset: -50%; width: 200%; height: 200%;
            background: conic-gradient(from 160deg at 55% 45%, #4C1D95 0deg, #1e3a8a 120deg, #3b0764 240deg, #4C1D95 360deg);
            animation: bgSpin 30s linear infinite; filter: blur(100px); opacity: .7; }
        @keyframes bgSpin { to { transform: rotate(360deg); } }
        .bg-noise { position: absolute; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.85' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            background-repeat: repeat; background-size: 200px; opacity: .5; }

        /* ── Layout ── */
        .login-shell { position: relative; z-index: 1; display: grid; grid-template-columns: 1fr 1fr; width: 100%; height: 100%; }

        /* ── Left: Brand panel ── */
        .brand-panel { display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 3rem 2.5rem; position: relative; overflow: hidden; }
        .brand-panel::after { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(76,29,149,.35), rgba(30,58,138,.25)); pointer-events: none; }
        .brand-inner { position: relative; z-index: 2; text-align: center; max-width: 380px; animation: fadeUp .9s ease-out both; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(28px); } to { opacity: 1; transform: translateY(0); } }

        /* Decorative rings */
        .brand-rings { position: absolute; z-index: 1; width: 480px; height: 480px; top: 50%; left: 50%; transform: translate(-50%,-50%); pointer-events: none; }
        .brand-rings .ring { position: absolute; border-radius: 50%; border: 1px solid var(--gold-dim); }
        .ring-1 { inset: 0; animation: ringPulse 6s ease-in-out infinite; }
        .ring-2 { inset: 60px; animation: ringPulse 6s ease-in-out 1s infinite; }
        .ring-3 { inset: 120px; animation: ringPulse 6s ease-in-out 2s infinite; }
        @keyframes ringPulse { 0%,100% { opacity: .18; transform: scale(1); } 50% { opacity: .35; transform: scale(1.04); } }

        .brand-logo { width: 88px; height: 88px; margin: 0 auto 1.75rem; animation: logoFloat 4s ease-in-out infinite; }
        .brand-logo img { width: 100%; height: 100%; object-fit: contain; border-radius: 18px; filter: drop-shadow(0 4px 24px var(--gold-glow)); }
        @keyframes logoFloat { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        .brand-logo svg { width: 100%; height: 100%; filter: drop-shadow(0 4px 24px var(--gold-glow)); }

        .brand-name { font-family: "Playfair Display", serif; font-size: 2.25rem; font-weight: 700; color: var(--gold); letter-spacing: .5px; line-height: 1.2; margin-bottom: .5rem; }
        .brand-tagline { font-size: .8rem; text-transform: uppercase; letter-spacing: 3px; color: var(--text-dim); }

        /* ── Right: Form panel ── */
        .form-panel { display: flex; align-items: center; justify-content: center; padding: 2rem 2.5rem; }
        .form-card { width: 100%; max-width: 400px; animation: cardIn .8s ease-out .15s both; }
        @keyframes cardIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        .form-card-box { background: var(--surface); backdrop-filter: blur(24px) saturate(1.4); border: 1px solid var(--border); border-radius: 20px; padding: 2.5rem 2rem; box-shadow: 0 8px 40px rgba(0,0,0,.35), inset 0 1px 0 rgba(255,255,255,.06); }

        .form-heading { font-family: "Playfair Display", serif; font-size: 1.65rem; font-weight: 700; margin-bottom: .25rem; }
        .form-subheading { font-size: .85rem; color: var(--text-dim); margin-bottom: 1.75rem; }

        /* Error banner */
        .error-banner { display: flex; align-items: center; gap: .65rem; padding: .7rem 1rem; background: var(--danger-bg); border: 1px solid var(--danger-border); border-radius: 10px; color: var(--danger-text); font-size: .8rem; margin-bottom: 1.25rem; animation: shake .45s ease-out; }
        @keyframes shake { 0%,100% { transform: translateX(0); } 20% { transform: translateX(-8px); } 60% { transform: translateX(6px); } }

        /* Login method toggle */
        .login-toggle { display: flex; background: rgba(255,255,255,.04); border: 1px solid var(--border); border-radius: 10px; padding: 3px; margin-bottom: 1.5rem; }
        .login-toggle-btn { flex: 1; display: flex; align-items: center; justify-content: center; gap: .4rem; padding: .55rem .5rem; border: none; border-radius: 8px; background: transparent; color: var(--text-dim); font-size: .78rem; font-weight: 500; font-family: inherit; cursor: pointer; transition: all .25s; }
        .login-toggle-btn.active { background: rgba(250,204,21,.12); color: var(--gold); box-shadow: 0 1px 6px rgba(250,204,21,.12); }
        .login-toggle-btn:not(.active):hover { color: var(--text-mid); background: rgba(255,255,255,.04); }
        .login-toggle-btn svg { width: 15px; height: 15px; flex-shrink: 0; }

        /* Form elements */
        .field { margin-bottom: 1.25rem; }
        .field-label { display: block; font-size: .72rem; font-weight: 600; text-transform: uppercase; letter-spacing: .6px; color: var(--text-mid); margin-bottom: .45rem; }
        .field-input-wrap { position: relative; }
        .field-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 17px; height: 17px; color: var(--gold-dim); pointer-events: none; transition: color .25s; }
        .field-input { width: 100%; background: rgba(255,255,255,.04); border: 1.5px solid var(--border); border-radius: 10px; padding: .8rem .85rem .8rem 2.7rem; color: var(--text); font-size: .88rem; font-family: inherit; transition: border-color .25s, box-shadow .25s, background .25s; }
        .field-input::placeholder { color: rgba(255,255,255,.3); }
        .field-input:focus { outline: none; border-color: var(--border-focus); background: rgba(255,255,255,.07); box-shadow: 0 0 0 3px var(--gold-glow); }
        .field-input:focus ~ .field-icon,
        .field-input:focus + .field-icon { color: var(--gold); }
        /* Also target the preceding sibling via wrapper */
        .field-input-wrap:focus-within .field-icon { color: var(--gold); }

        /* Show password toggle */
        .pw-toggle { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: none; border: none; padding: 4px; cursor: pointer; color: var(--text-dim); transition: color .2s; }
        .pw-toggle:hover { color: var(--text); }
        .pw-toggle svg { width: 18px; height: 18px; display: block; }

        /* Submit button */
        .btn-submit { position: relative; display: flex; align-items: center; justify-content: center; gap: .5rem; width: 100%; margin-top: 1.5rem; padding: .85rem 1.5rem; background: linear-gradient(135deg, #FACC15, #d4a017); color: #1a1a2e; border: none; border-radius: 11px; font-size: .92rem; font-weight: 700; font-family: inherit; cursor: pointer; letter-spacing: .2px; transition: transform .2s, box-shadow .2s; box-shadow: 0 4px 18px rgba(250,204,21,.25); overflow: hidden; }
        .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(250,204,21,.35); }
        .btn-submit:active { transform: translateY(0); box-shadow: 0 2px 10px rgba(250,204,21,.2); }
        .btn-submit:disabled { opacity: .7; cursor: not-allowed; transform: none !important; }
        .btn-submit .btn-text { transition: opacity .2s; }
        .btn-submit.is-loading .btn-text { opacity: 0; }
        .btn-submit .spinner { position: absolute; width: 20px; height: 20px; border: 2.5px solid rgba(26,26,46,.2); border-top-color: #1a1a2e; border-radius: 50%; opacity: 0; transition: opacity .2s; }
        .btn-submit.is-loading .spinner { opacity: 1; animation: spin .6s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        /* shimmer */
        .btn-submit::after { content: ''; position: absolute; top: 0; left: -100%; width: 60%; height: 100%; background: linear-gradient(90deg, transparent, rgba(255,255,255,.25), transparent); transition: none; }
        .btn-submit:hover::after { animation: shimmer .75s; }
        @keyframes shimmer { to { left: 120%; } }

        /* Forgot password link */
        .forgot-link { display: block; text-align: center; margin-top: 1.1rem; font-size: .8rem; color: var(--text-dim); text-decoration: none; transition: color .2s; }
        .forgot-link:hover { color: var(--text); }

        /* Demo strip */
        .demo-strip { margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--border); text-align: center; }
        .demo-strip small { display: block; font-size: .65rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-dim); margin-bottom: .35rem; }
        .demo-strip code { font-size: .78rem; color: var(--gold); font-weight: 500; }

        /* ── Mobile: stack vertically ── */
        @media (max-width: 900px) {
            .login-shell { grid-template-columns: 1fr; grid-template-rows: auto 1fr; }
            .brand-panel { padding: 2rem 1.5rem 1.5rem; }
            .brand-rings { display: none; }
            .brand-logo { width: 56px; height: 56px; margin-bottom: 1rem; }
            .brand-name { font-size: 1.5rem; margin-bottom: .25rem; }
            .brand-tagline { font-size: .7rem; }
            .form-panel { padding: 0 1.25rem 2rem; }
            .form-card-box { padding: 2rem 1.5rem; }
            .form-heading { font-size: 1.35rem; }
        }
        @media (max-width: 480px) {
            .form-card-box { border-radius: 16px; padding: 1.75rem 1.25rem; }
        }
    </style>
</head>
<body>
    <!-- Animated background -->
    <div class="bg-wrap">
        <div class="bg-gradient"></div>
        <div class="bg-noise"></div>
    </div>

    <div class="login-shell">
        <!-- ── Left: Brand ── -->
        <div class="brand-panel">
            <div class="brand-rings">
                <div class="ring ring-1"></div>
                <div class="ring ring-2"></div>
                <div class="ring ring-3"></div>
            </div>
            <div class="brand-inner">
                <div class="brand-logo">
                    <?php if (!empty($churchLogo)): ?>
                        <img src="<?= htmlspecialchars($B . $churchLogo) ?>" alt="<?= $cn ?>">
                    <?php else: ?>
                        <svg viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <circle cx="40" cy="40" r="38" stroke="#FACC15" stroke-width="1.2" opacity=".25"/>
                            <rect x="37" y="12" width="6" height="56" rx="3" fill="#FACC15" opacity=".85"/>
                            <rect x="20" y="26" width="40" height="6" rx="3" fill="#FACC15" opacity=".85"/>
                        </svg>
                    <?php endif; ?>
                </div>
                <h1 class="brand-name"><?= $cn ?></h1>
                <p class="brand-tagline">Church Management System</p>
            </div>
        </div>

        <!-- ── Right: Form ── -->
        <div class="form-panel">
            <div class="form-card">
                <div class="form-card-box">
                    <h2 class="form-heading">Welcome back</h2>
                    <p class="form-subheading">Sign in to continue to your dashboard</p>

                    <?php if ($error): ?>
                        <div class="error-banner">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            <?= htmlspecialchars($error) ?>
                        </div>
                    <?php endif; ?>

                    <form id="loginForm" method="POST" action="<?= htmlspecialchars($B . '/login') ?>">
                        <input type="hidden" name="_csrf_token" value="<?= htmlspecialchars(\App\Core\Auth::getCsrfToken()) ?>">

                        <!-- Login method toggle -->
                        <div class="login-toggle">
                            <button type="button" class="login-toggle-btn active" id="togEmail" onclick="switchLogin('email')">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/></svg>
                                Email
                            </button>
                            <button type="button" class="login-toggle-btn" id="togPhone" onclick="switchLogin('phone')">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"/><line x1="12" y1="18" x2="12.01" y2="18"/></svg>
                                Phone
                            </button>
                        </div>

                        <!-- Email field -->
                        <div class="field" id="emailField">
                            <label for="email" class="field-label">Email Address</label>
                            <div class="field-input-wrap">
                                <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 7L2 7"/>
                                </svg>
                                <input type="email" id="email" name="email" class="field-input" placeholder="you@example.com" autocomplete="email">
                            </div>
                        </div>

                        <!-- Phone field -->
                        <div class="field" id="phoneField" style="display:none">
                            <label for="phone" class="field-label">Phone Number</label>
                            <div class="field-input-wrap">
                                <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 16.92z"/>
                                </svg>
                                <input type="tel" id="phone" name="phone" class="field-input" placeholder="+255 7XX XXX XXX" autocomplete="tel">
                            </div>
                        </div>

                        <div class="field">
                            <label for="password" class="field-label">Password</label>
                            <div class="field-input-wrap">
                                <svg class="field-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                                </svg>
                                <input type="password" id="password" name="password" class="field-input" style="padding-right:2.8rem" placeholder="Enter your password" autocomplete="current-password" required>
                                <button type="button" class="pw-toggle" onclick="togglePassword()" aria-label="Toggle password visibility">
                                    <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"/><circle cx="12" cy="12" r="3"/>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit" id="btnSubmit">
                            <span class="btn-text">Sign In</span>
                            <span class="spinner"></span>
                        </button>
                    </form>

                    <a href="<?= htmlspecialchars($B . '/forgot-password') ?>" class="forgot-link">Forgot your password?</a>

                    <div class="demo-strip">
                        <small>Demo Credentials</small>
                        <code>admin@kanisa.local / password123</code>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    /* Toggle between Email and Phone login */
    let loginMode = 'email';
    function switchLogin(mode) {
        loginMode = mode;
        const emailField = document.getElementById('emailField');
        const phoneField = document.getElementById('phoneField');
        const togEmail = document.getElementById('togEmail');
        const togPhone = document.getElementById('togPhone');
        const emailInput = document.getElementById('email');
        const phoneInput = document.getElementById('phone');

        if (mode === 'email') {
            emailField.style.display = '';
            phoneField.style.display = 'none';
            togEmail.classList.add('active');
            togPhone.classList.remove('active');
            emailInput.required = true;
            phoneInput.required = false;
            phoneInput.value = '';
            emailInput.focus();
        } else {
            emailField.style.display = 'none';
            phoneField.style.display = '';
            togPhone.classList.add('active');
            togEmail.classList.remove('active');
            phoneInput.required = true;
            emailInput.required = false;
            emailInput.value = '';
            phoneInput.focus();
        }
    }
    // Initialize
    switchLogin('email');

    /* Show / hide password */
    function togglePassword() {
        const inp = document.getElementById('password');
        const ico = document.getElementById('eyeIcon');
        const show = inp.type === 'password';
        inp.type = show ? 'text' : 'password';
        ico.innerHTML = show
            ? '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>'
            : '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z"/><circle cx="12" cy="12" r="3"/>';
    }

    /* Loading state on submit */
    document.getElementById('loginForm').addEventListener('submit', function (e) {
        // Ensure the active field is filled
        if (loginMode === 'email' && !document.getElementById('email').value.trim()) {
            e.preventDefault(); document.getElementById('email').focus(); return;
        }
        if (loginMode === 'phone' && !document.getElementById('phone').value.trim()) {
            e.preventDefault(); document.getElementById('phone').focus(); return;
        }
        const btn = document.getElementById('btnSubmit');
        btn.classList.add('is-loading');
        btn.disabled = true;
    });
    </script>
</body>
</html>

