<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Autentikasi') — TanamanObat.id</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🌿</text></svg>">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(160deg, #0f172a 0%, #052e16 50%, #14532d 100%);
            position: relative;
            overflow-y: auto;
            padding: 40px 0;
        }
        .auth-bg-particles { position: fixed; inset: 0; pointer-events: none; z-index: 0; }
        .auth-wrap {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 440px;
            padding: 24px;
        }
        .auth-card {
            background: rgba(255,255,255,0.96);
            backdrop-filter: blur(30px);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 32px 80px rgba(0,0,0,0.4), 0 0 0 1px rgba(255,255,255,0.1);
        }
        .auth-logo {
            text-align: center;
            margin-bottom: 32px;
        }
        .auth-logo a {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-family: 'Playfair Display', serif;
            font-size: 22px;
            font-weight: 800;
            color: var(--green-700);
            text-decoration: none;
        }
        .auth-logo-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, var(--green-500), var(--green-700));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            box-shadow: 0 6px 16px rgba(34,197,94,0.35);
        }
        .auth-title {
            font-size: 26px;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 6px;
        }
        .auth-subtitle {
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            color: var(--text-muted);
            margin-bottom: 28px;
        }
        .auth-divider {
            display: flex;
            align-items: center;
            gap: 12px;
            margin: 20px 0;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            color: var(--text-muted);
        }
        .auth-divider::before, .auth-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }
        .auth-footer {
            text-align: center;
            margin-top: 24px;
            font-family: 'Inter', sans-serif;
            font-size: 14px;
            color: var(--text-muted);
        }
        .auth-footer a {
            color: var(--green-600);
            font-weight: 600;
        }
        .auth-footer a:hover { color: var(--green-700); text-decoration: underline; }
    </style>
</head>
<body>
    <div class="auth-bg-particles" id="authParticles"></div>

    <div class="auth-wrap">
        <div class="auth-card">
            <div class="auth-logo">
                <a href="{{ route('home') }}">
                    <div class="auth-logo-icon">🌿</div>
                    TanamanObat.id
                </a>
            </div>
            @yield('content')
        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        // Auth page particles
        const container = document.getElementById('authParticles');
        const emojis = ['🌿','🍃','🌱','🌾','🍀','🌵','🪴'];
        for(let i=0;i<20;i++){
            const el = document.createElement('span');
            el.className = 'particle';
            el.textContent = emojis[Math.floor(Math.random()*emojis.length)];
            el.style.cssText = `left:${Math.random()*100}%;animation-duration:${10+Math.random()*12}s;animation-delay:${Math.random()*8}s;font-size:${18+Math.random()*18}px;`;
            container.appendChild(el);
        }
    </script>
    @stack('scripts')
</body>
</html>
