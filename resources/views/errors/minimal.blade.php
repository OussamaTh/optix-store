@php
    // $code is passed in by each errors/{code}.blade.php view below.
    // Falls back to the real exception's status code if present, then 500.
$code =
    $code ?? (isset($exception) && method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500);

// Per-status copy + lens state. Add more codes here as needed —
// anything not listed falls through to the generic "fogged" state.
$states = [
    404 => [
        'label' => 'Error 404',
        'heading' => "We couldn't find that page",
        'message' => "The page you're looking for isn't here. It may have been moved, or the link might be off.",
        'lens' => 'missing', // right lens empty/see-through
    ],
    403 => [
        'label' => 'Error 403',
        'heading' => 'This page is under lock and key',
        'message' => "You don't have access to view this page. If that seems wrong, get in touch with us.",
        'lens' => 'closed', // both lenses shaded dark
    ],
    419 => [
        'label' => 'Session expired',
        'heading' => 'Your session timed out',
        'message' =>
            'For your security, we signed you out after a period of inactivity. Please refresh and try again.',
        'lens' => 'fogged',
    ],
    503 => [
        'label' => "We'll be right back",
        'heading' => "We're polishing things up",
        'message' =>
            "OptixStore is down for scheduled maintenance. We'll be back shortly — thanks for your patience.",
        'lens' => 'fogged',
    ],
    500 => [
        'label' => 'Error 500',
        'heading' => 'Looks like our lenses fogged up',
        'message' =>
            "Something went wrong loading this page. It's on us — try again, or head back and keep browsing.",
        'lens' => 'cracked',
    ],
];

$state = $states[$code] ?? [
    'label' => 'Error ' . $code,
    'heading' => 'Something threw us off balance',
    'message' => "That didn't work as expected. Try again, or head back and keep browsing.",
        'lens' => 'cracked',
    ];
@endphp
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>OptixStore — {{ $state['label'] }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        :root {
            --bg: #FAFAF9;
            --surface: #FFFFFF;
            --ink: #16161A;
            --muted: #6B6B70;
            --border: #E8E6E1;
            --lens-fog: #EDEDEC;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            background: var(--bg);
            color: var(--ink);
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
        }

        .nav-inner {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            font-size: 22px;
            font-weight: 800;
            letter-spacing: -0.02em;
            text-decoration: none;
            color: var(--ink);
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 32px;
            margin: 0;
            padding: 0;
        }

        nav a {
            color: var(--ink);
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            opacity: 0.45;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 22px;
        }

        .icon {
            width: 19px;
            height: 19px;
            opacity: 0.35;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 11px 24px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
            transition: transform .15s ease, opacity .15s ease;
        }

        .pill:hover {
            transform: translateY(-1px);
        }

        .pill-solid {
            background: var(--ink);
            color: #fff;
        }

        .pill-solid:hover {
            opacity: 0.88;
        }

        .pill-outline {
            background: transparent;
            color: var(--ink);
            border: 1.5px solid var(--border);
        }

        .pill-outline:hover {
            border-color: var(--ink);
        }

        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 80px 24px;
        }

        .stage {
            text-align: center;
            max-width: 520px;
        }

        .frame-wrap {
            display: flex;
            justify-content: center;
            margin-bottom: 36px;
        }

        .code {
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 14px;
        }

        h1 {
            font-size: 32px;
            font-weight: 800;
            letter-spacing: -0.01em;
            margin: 0 0 14px 0;
            line-height: 1.25;
        }

        p.sub {
            font-size: 16px;
            color: var(--muted);
            line-height: 1.6;
            margin: 0 0 36px 0;
        }

        .actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        @media (max-width: 720px) {
            nav ul {
                display: none;
            }
        }

        @media (prefers-reduced-motion: no-preference) {
            .fog-pulse {
                animation: fogPulse 4s ease-in-out infinite;
            }
        }

        @keyframes fogPulse {

            0%,
            100% {
                opacity: 0.55;
            }

            50% {
                opacity: 0.85;
            }
        }
    </style>
</head>

<body>

    {{-- <header>
        <div class="nav-inner">
            <a href="/" class="logo">OO</a>
            <nav>
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/products">Products</a></li>
                    <li><a href="/customize">Customize</a></li>
                    <li><a href="/lenses">Lenses</a></li>
                    <li><a href="/contact">Contact</a></li>
                </ul>
            </nav>
            <div class="nav-right">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <circle cx="11" cy="11" r="7" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z" />
                    <path d="M3 6h18" />
                    <path d="M16 10a4 4 0 0 1-8 0" />
                </svg>
                <a href="/" class="pill pill-solid">Back to shop</a>
            </div>
        </div>
    </header> --}}

    <main>
        <div class="stage">

            <div class="frame-wrap">
                <svg width="180" height="90" viewBox="0 0 180 90" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    @if ($state['lens'] === 'missing')
                        {{-- left lens normal, right lens empty (page not found) --}}
                        <circle cx="46" cy="45" r="38" fill="var(--lens-fog)" stroke="#16161A"
                            stroke-width="4" />
                        <circle cx="134" cy="45" r="38" fill="none" stroke="#16161A" stroke-width="4"
                            stroke-dasharray="6 7" />
                    @elseif($state['lens'] === 'closed')
                        {{-- both lenses shaded dark (access denied) --}}
                        <circle cx="46" cy="45" r="38" fill="#16161A" stroke="#16161A" stroke-width="4" />
                        <circle cx="134" cy="45" r="38" fill="#16161A" stroke="#16161A" stroke-width="4" />
                    @elseif($state['lens'] === 'cracked')
                        {{-- left lens cracked, right lens fogged (server error) --}}
                        <circle cx="46" cy="45" r="38" fill="var(--lens-fog)" stroke="#16161A"
                            stroke-width="4" />
                        <path d="M46 12 L38 40 L58 44 L30 62 L46 45" stroke="#16161A" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" fill="none" />
                        <circle cx="134" cy="45" r="38" fill="var(--lens-fog)" stroke="#16161A"
                            stroke-width="4" class="fog-pulse" />
                    @else
                        {{-- default: both lenses gently fogged (maintenance / session / generic) --}}
                        <circle cx="46" cy="45" r="38" fill="var(--lens-fog)" stroke="#16161A"
                            stroke-width="4" class="fog-pulse" />
                        <circle cx="134" cy="45" r="38" fill="var(--lens-fog)" stroke="#16161A"
                            stroke-width="4" class="fog-pulse" />
                    @endif
                    <path d="M84 42 Q90 34 96 42" stroke="#16161A" stroke-width="4" fill="none"
                        stroke-linecap="round" />
                    <path d="M8 40 Q2 40 2 46" stroke="#16161A" stroke-width="4" fill="none"
                        stroke-linecap="round" />
                    <path d="M172 40 Q178 40 178 46" stroke="#16161A" stroke-width="4" fill="none"
                        stroke-linecap="round" />
                </svg>
            </div>

            <div class="code">{{ $state['label'] }}</div>
            <h1>{{ $state['heading'] }}</h1>
            <p class="sub">{{ $state['message'] }}</p>

            <div class="actions">
                <a href="/" class="pill pill-solid">Back to shop</a>
                <a href="/contact" class="pill pill-outline">Contact support</a>
            </div>

        </div>
    </main>

</body>

</html>
