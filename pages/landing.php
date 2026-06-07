<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Titipangan - Foodsharing Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        forest: {
                            950: '#071208',
                            900: '#0d1f10',
                            800: '#152a18',
                            700: '#1a3a1f',
                            600: '#1f4a26',
                            500: '#2d6b40',
                            400: '#3d8a52',
                            300: '#5aaa6e',
                            200: '#8ecf9a',
                            100: '#c5e8cc',
                            50: '#f0f7f1',
                        },
                        amber: {
                            brand: '#C8883A',
                            light: '#E8A84E',
                            pale: '#F5E8D0',
                        },
                        cream: '#F5F0E8',
                    }
                }
            }
        }
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        html,
        body {
            background-color: #ffffff;
            scrollbar-width: none;
            -ms-overflow-style: none;
            scroll-behavior: smooth;
        }

        html::-webkit-scrollbar,
        body::-webkit-scrollbar {
            width: 0;
            height: 0;
        }

        body.landing-page h2 {
            font-size: 3rem !important;
        }

        @media (max-width: 640px) {
            body.landing-page h2 {
                font-size: 2rem !important;
            }

            .map-embed-frame {
                height: clamp(320px, 82vw, 420px);
            }
        }

        @media (max-width: 420px) {
            body.landing-page h2 {
                font-size: 1.75rem !important;
            }
        }

        .hero-bg {
            position: relative;
            isolation: isolate;
            background-color: #071208;
        }

        .hero-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url('/assets/backgrounds/hero.png');
            background-position: center right;
            background-repeat: no-repeat;
            background-size: cover;
            z-index: 0;
        }

        .hero-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(0, 0, 0, 0.9) 0%, rgba(0, 0, 0, 0.78) 34%, rgba(0, 0, 0, 0.42) 62%, rgba(0, 0, 0, 0) 100%);
            z-index: 1;
            pointer-events: none;
        }

        .stat-section-bg {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            background-color: #fff;
        }

        .stat-section-bg::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url('/assets/backgrounds/statistics.jpg');
            background-position: top center;
            background-repeat: no-repeat;
            background-size: 120% auto;
            opacity: 1;
            filter: saturate(1.08) contrast(1.05) brightness(1);
            z-index: 0;
            pointer-events: none;
        }

        .stat-section-bg::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(255, 255, 255, 0) 0%, rgba(255, 255, 255, 0.02) 16%, rgba(0, 0, 0, 0.14) 32%, rgba(0, 0, 0, 0.58) 64%, rgba(0, 0, 0, 0.72) 100%);
            z-index: 1;
            pointer-events: none;
        }

        .hero-leaf-bg {
            position: absolute;
            right: -2%;
            top: 0;
            bottom: 0;
            transform: none;
            height: 100%;
            width: auto;
            max-width: none;
            object-fit: contain;
            object-position: right bottom;
            opacity: .2;
            mix-blend-mode: screen;
            pointer-events: none;
            z-index: 0;
            -webkit-mask-image: linear-gradient(to right, transparent 0%, transparent 12%, rgba(0, 0, 0, 0.35) 30%, rgba(0, 0, 0, 0.8) 50%, #000 100%);
            mask-image: linear-gradient(to right, transparent 0%, transparent 12%, rgba(0, 0, 0, 0.35) 30%, rgba(0, 0, 0, 0.8) 50%, #000 100%);
        }

        .dot-pattern {
            background-image: radial-gradient(circle at 1px 1px, rgba(255, 255, 255, 0.07) 1px, transparent 0);
            background-size: 28px 28px;
        }

        .leaderboard-texture {
            background-image:
                radial-gradient(circle at 1px 1px, rgba(45, 107, 64, 0.08) 1px, transparent 0),
                radial-gradient(circle at 18% 20%, rgba(200, 232, 204, 0.28) 0, transparent 38%),
                radial-gradient(circle at 82% 0%, rgba(184, 149, 106, 0.12) 0, transparent 32%);
            background-size: 24px 24px, auto, auto;
            background-repeat: repeat, no-repeat, no-repeat;
        }

        /* Hero earth visual */
        .hero-earth-wrap {
            position: relative;
            display: inline-block;
        }

        .hero-earth-wrap::before {
            content: none;
        }

        .hero-earth-wrap::after {
            content: none;
        }

        .hero-earth-img {
            width: 320px;
            height: auto;
            max-width: 100%;
            display: block;
        }

        /* Map styling */
        .map-bg {
            background:
                linear-gradient(rgba(200, 220, 200, 0.4) 1px, transparent 1px),
                linear-gradient(90deg, rgba(200, 220, 200, 0.4) 1px, transparent 1px),
                #dce8dc;
            background-size: 36px 36px, 36px 36px;
        }

        .map-road {
            background: #c8d8c4;
        }

        /* Podium */
        .podium-1 {
            background: linear-gradient(135deg, #1a3a1f, #1f4a26);
        }

        .podium-base-1 {
            background: linear-gradient(to bottom, #1a3a1f, #0d2010);
        }

        .podium-base-2 {
            background: linear-gradient(to bottom, #9ca3af, #6b7280);
        }

        .podium-base-3 {
            background: linear-gradient(to bottom, #b8956a, #9b7a4d);
        }

        /* Circular progress */
        .ring-bg {
            stroke: #e5e7eb;
        }

        .ring-fg {
            stroke: #2d6b40;
            stroke-linecap: round;
            transition: stroke-dashoffset 1s ease;
        }

        /* Pulse dot */
        @keyframes pulse-dot {

            0%,
            100% {
                opacity: 1;
                transform: scale(1)
            }

            50% {
                opacity: .6;
                transform: scale(.8)
            }
        }

        .pulse-dot {
            animation: pulse-dot 1.8s ease infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-6px)
            }
        }

        .float {
            animation: float 4s ease-in-out infinite;
        }

        /* Card hover */
        .hover-lift {
            transition: transform .25s, box-shadow .25s;
        }

        .hover-lift:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 32px rgba(0, 0, 0, .1);
        }

        /* Journey dashed line */
        .dashed-connect {
            border-top: 2px dashed #c5e8cc;
            flex: 1;
            margin-top: -28px;
        }

        .medal-badge {
            width: 36px;
            height: 36px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 18px rgba(0, 0, 0, .15);
            border: 1px solid rgba(255, 255, 255, .7);
        }

        .medal-badge svg {
            width: 20px;
            height: 20px;
        }

        .medal-gold {
            background: linear-gradient(145deg, #f5d87a, #c8883a);
            color: #5b3b10;
        }

        .medal-silver {
            background: linear-gradient(145deg, #f3f4f6, #9ca3af);
            color: #4b5563;
        }

        .medal-bronze {
            background: linear-gradient(145deg, #e3b88d, #9b6b3c);
            color: #5a3418;
        }

        .podium-top3 {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            align-items: end;
            gap: 0.75rem;
        }

        .podium-entry {
            min-width: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .podium-name,
        .podium-stats {
            min-width: 0;
            line-height: 1.15;
            text-align: center;
            word-break: break-word;
        }

        .podium-base {
            width: 100%;
        }

        @media (max-width: 420px) {
            .podium-top3 {
                gap: 0.5rem;
            }

            .podium-entry {
                gap: 0.375rem;
            }
        }

        .stat-tab {
            border: 1px solid rgba(226, 232, 240, 0.95);
            background: rgba(255, 255, 255, 0.96);
            color: #475569;
            border-radius: 9999px;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            box-shadow: 0 12px 28px rgba(15, 23, 42, 0.07);
            transition: transform .2s ease, background-color .2s ease, color .2s ease, border-color .2s ease, box-shadow .2s ease;
        }

        .stat-tab:hover {
            transform: translateY(-1px);
            border-color: rgba(148, 163, 184, 0.5);
            color: #111827;
            background: rgba(255, 255, 255, 1);
        }

        .stat-tab.is-active {
            background: linear-gradient(135deg, #5f9449 0%, #4f7f3a 100%);
            color: #fff;
            border-color: transparent;
            box-shadow: 0 14px 28px rgba(79, 127, 58, 0.28);
        }

        .stat-tab.is-active:hover {
            color: #fff;
        }

        .stat-tab-group {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            margin-bottom: 0;
        }

        .stat-chart-frame {
            background: transparent;
        }

        .stat-chart-glass {
            background: transparent;
            border: 0;
            box-shadow: none;
            backdrop-filter: none;
            -webkit-backdrop-filter: none;
        }

        .stat-chart-point-label {
            font-size: 11px;
            font-weight: 700;
            fill: #0f172a;
            paint-order: stroke;
            stroke: rgba(255, 255, 255, 0.88);
            stroke-width: 3px;
            stroke-linejoin: round;
        }

        .map-embed-frame {
            width: 100%;
            max-width: 80rem;
            margin: 0 auto;
            height: clamp(420px, 42vw, 620px);
            background: transparent;
            box-sizing: border-box;
        }

        .map-embed-frame iframe {
            width: 100%;
            height: 100%;
            border: 0;
        }

        @media (max-width: 640px) {
            .stat-tab-group {
                width: max-content;
                flex-wrap: nowrap;
                padding-right: 1rem;
            }
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #2d6b40;
            border-radius: 3px;
        }
    </style>
</head>

<body class="landing-page">
    <?php
    if (!function_exists('retailBrandIconPath')) {
        function retailBrandIconPath(string $brandName): ?string
        {
            static $cache = [];
            $key = strtolower(trim($brandName));
            if (array_key_exists($key, $cache)) {
                return $cache[$key];
            }

            $aliases = [
                'indomaret' => 'indomaret',
                'alfamart' => 'alfamart',
                'hypermart' => 'hypermart',
                'transmart carrefour' => 'transmart_carrefour',
                'giant supermarket' => 'giant',
                'giant' => 'giant',
                'ranch market' => 'ranch_market',
                'lotte mart' => 'lottemart',
                'super indo' => 'superindo',
            ];

            $baseCandidates = [];
            if (isset($aliases[$key])) {
                $baseCandidates[] = $aliases[$key];
            }

            $normalized = preg_replace('/[^a-z0-9]+/i', '_', strtolower($brandName));
            $normalized = trim((string) $normalized, '_');
            if ($normalized !== '') {
                $baseCandidates[] = $normalized;
                $baseCandidates[] = str_replace('_', '', $normalized);
            }

            $baseCandidates[] = strtolower(str_replace(' ', '', trim($brandName)));
            $baseCandidates = array_values(array_unique(array_filter($baseCandidates)));

            $extensions = ['png', 'jpg', 'jpeg', 'webp', 'svg', 'avif'];
            $baseDir = __DIR__ . '/../public/assets/retail';
            $webDir = '/assets/retail';

            foreach ($baseCandidates as $base) {
                foreach ($extensions as $ext) {
                    $file = $baseDir . '/' . $base . '.' . $ext;
                    if (is_file($file)) {
                        return $cache[$key] = $webDir . '/' . $base . '.' . $ext;
                    }
                }
            }

            return $cache[$key] = null;
        }
    }

    $navCtaHref = '/user/login';
    $navCtaLabel = 'Masuk';
    $navCtaIcon = 'M10 17l5-5-5-5M15 12H3';
    if (function_exists('isUserLoggedIn') && isUserLoggedIn()) {
        $navCtaHref = '/user/';
        $navCtaLabel = 'Dashboard';
        $navCtaIcon = 'M3 12h18M12 3v18M5 5l14 14M19 5L5 19';
    }
    ?>

    <!-- ============================
     NAVBAR
============================== -->
    <nav id="site-navbar" class="absolute inset-x-0 top-0 z-50 bg-transparent border-b border-transparent transition-all duration-300 pt-3 sm:pt-4">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 min-h-16 flex items-center justify-between gap-3">
            <!-- Logo -->
            <div class="flex min-w-0 items-center gap-3">
                <img src="/assets/logo/navbar.png" alt="Titipangan" class="h-12 sm:h-16 w-auto max-w-none object-contain">
            </div>
            <!-- Links -->
            <div class="flex items-center gap-3 sm:gap-4">
                <div class="hidden lg:flex items-center gap-2">
                    <a href="#program-section" class="rounded-full px-3 py-2 text-[15px] font-semibold text-white transition hover:bg-white/10 whitespace-nowrap">Program</a>
                    <a href="#statistik-section" class="rounded-full px-3 py-2 text-[15px] font-semibold text-white transition hover:bg-white/10 whitespace-nowrap">Statistik</a>
                    <a href="#lokasi-section" class="rounded-full px-3 py-2 text-[15px] font-semibold text-white transition hover:bg-white/10 whitespace-nowrap">Lokasi</a>
                    <a href="#regulasi-section" class="rounded-full px-3 py-2 text-[15px] font-semibold text-white transition hover:bg-white/10 whitespace-nowrap">Regulasi</a>
                </div>
                <button type="button" id="mobile-nav-toggle" class="inline-flex h-11 w-11 items-center justify-center rounded-full border border-white/12 bg-white/8 text-white transition hover:bg-white/14 lg:hidden" aria-expanded="false" aria-controls="mobile-nav-menu" aria-label="Buka navigasi">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 12h16M4 17h16" />
                    </svg>
                </button>
                <a href="<?= htmlspecialchars($navCtaHref) ?>" class="bg-gradient-to-r from-forest-700 to-forest-900 hover:from-forest-600 hover:to-forest-800 text-white text-sm font-semibold px-4 sm:px-5 py-2.5 rounded-full flex items-center gap-2 transition-all shadow-lg whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= htmlspecialchars($navCtaIcon) ?>" />
                    </svg>
                    <?= htmlspecialchars($navCtaLabel) ?>
                </a>
            </div>
        </div>
        <div id="mobile-nav-menu" class="hidden lg:hidden">
            <div class="mx-4 mt-3 rounded-[24px] border border-white/10 bg-forest-900/95 p-3 shadow-[0_24px_50px_rgba(0,0,0,0.32)] backdrop-blur-md sm:mx-6">
                <div class="grid grid-cols-2 gap-2">
                    <a href="#program-section" class="rounded-2xl px-4 py-3 text-center text-[14px] font-semibold text-white transition hover:bg-white/10">Program</a>
                    <a href="#statistik-section" class="rounded-2xl px-4 py-3 text-center text-[14px] font-semibold text-white transition hover:bg-white/10">Statistik</a>
                    <a href="#lokasi-section" class="rounded-2xl px-4 py-3 text-center text-[14px] font-semibold text-white transition hover:bg-white/10">Lokasi</a>
                    <a href="#regulasi-section" class="rounded-2xl px-4 py-3 text-center text-[14px] font-semibold text-white transition hover:bg-white/10">Regulasi</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ============================
     HERO SECTION
============================== -->
    <section class="hero-bg relative overflow-hidden" style="min-height:80vh;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 sm:pt-40 lg:pt-44 pb-0 relative z-10">
            <div class="max-w-2xl pt-4 sm:pt-6 lg:pt-8 pb-10 lg:pb-16">
                <h1 class="text-[30px] sm:text-[44px] lg:text-[5rem] font-extrabold text-white leading-[1.08] tracking-tight mb-5 lg:mb-6">
                    Pangan Layak<br>
                    Untuk <span class="text-amber-brand">Semua</span>
                </h1>

                <p class="text-gray-200 text-[1rem] sm:text-[1.08rem] lg:text-[1.2rem] leading-[1.8] mb-6 lg:mb-8 max-w-[560px]">
                    Titipangan by Telkomsel adalah inisiatif sosial-kemanusiaan yang menyelamatkan dan mendistribusikan pangan layak konsumsi dari surplus produksi, industri, restoran, dan individu kepada masyarakat rentan, guna mengurangi pemborosan pangan serta memperkuat ketahanan pangan.
                </p>

                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4">
                    <a href="#program-section" class="bg-amber-brand hover:bg-amber-light text-white font-semibold text-[14px] px-7 py-3.5 rounded-xl flex items-center justify-center gap-2 transition-all shadow-lg hover:shadow-amber-brand/30">
                        Lihat Program <span class="ml-1">→</span>
                    </a>
                    <a href="#statistik-section" class="border border-white/20 bg-white/8 hover:bg-white/14 text-white font-semibold text-[14px] px-7 py-3.5 rounded-xl flex items-center justify-center gap-2 transition-all shadow-lg">
                        Statistik <span class="ml-1">→</span>
                    </a>
                </div>
            </div>
        </div>

    </section>

    <!-- ============================
     HERO STATS SECTION
============================== -->
    <section class="relative overflow-hidden bg-forest-900 py-10 sm:py-12 lg:py-14">
        <div class="absolute inset-0 opacity-35" style="background-image: radial-gradient(circle at 20% 20%, rgba(255,255,255,0.10) 0, rgba(255,255,255,0) 28%), radial-gradient(circle at 80% 0%, rgba(200,136,58,0.18) 0, rgba(200,136,58,0) 26%), linear-gradient(180deg, rgba(255,255,255,0.04), rgba(255,255,255,0));"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 sm:gap-5">
                <article class="bg-transparent px-0 py-0">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-white/60">Penerima Manfaat</p>
                    <div class="mt-2 text-[30px] sm:text-[34px] font-extrabold leading-none text-white">12.480</div>
                    <p class="mt-1.5 text-[12px] text-white/70 leading-snug">Orang yang terbantu melalui distribusi pangan.</p>
                </article>

                <article class="bg-transparent px-0 py-0">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-white/60">Kota Terjangkau</p>
                    <div class="mt-2 text-[30px] sm:text-[34px] font-extrabold leading-none text-white">48</div>
                    <p class="mt-1.5 text-[12px] text-white/70 leading-snug">Wilayah yang sudah tersentuh layanan Titipangan.</p>
                </article>

                <article class="bg-transparent px-0 py-0">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-white/60">Jumlah Hasil Donatur</p>
                    <div class="mt-2 text-[30px] sm:text-[34px] font-extrabold leading-none text-white">3.720</div>
                    <p class="mt-1.5 text-[12px] text-white/70 leading-snug">Kontribusi pangan dari para donatur yang telah disalurkan.</p>
                </article>

                <article class="bg-transparent px-0 py-0">
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-white/60">Ton Pangan Tersalurkan</p>
                    <div class="mt-2 text-[30px] sm:text-[34px] font-extrabold leading-none text-white">28,4</div>
                    <p class="mt-1.5 text-[12px] text-white/70 leading-snug">Pangan tersalurkan secara efektif ke titik distribusi.</p>
                </article>
            </div>
        </div>
    </section>

    <!-- ============================
     PROGRAM SECTION
============================== -->
    <section id="program-section" class="relative overflow-hidden bg-[#eef4ee] py-16 sm:py-20 lg:py-24">
        <div class="absolute inset-0 opacity-70" style="background-image: radial-gradient(circle at top left, rgba(255,255,255,0.9) 0, rgba(255,255,255,0) 30%), radial-gradient(circle at bottom right, rgba(45,107,64,0.08) 0, rgba(45,107,64,0) 28%);"></div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <span class="inline-flex items-center gap-2 rounded-full bg-forest-100 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.18em] text-forest-700">
                    <span class="inline-block h-2 w-2 rounded-full bg-forest-600"></span>
                    Program Kami
                </span>
                <h2 class="mt-5 text-[34px] sm:text-[42px] lg:text-[54px] font-extrabold tracking-tight text-forest-700 leading-[1.05]">
                    Program Titipangan
                </h2>
                <p class="mt-4 text-[16px] sm:text-[18px] leading-[1.8] text-slate-600">
                    Enam inisiatif utama untuk menyelamatkan pangan layak konsumsi, menggerakkan kolaborasi, dan memperluas dampak sosial di berbagai komunitas.
                </p>
            </div>

            <div class="mt-12 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
                <article class="group rounded-[28px] bg-white px-6 py-7 sm:px-8 sm:py-9 text-center shadow-[0_18px_55px_rgba(67,98,72,0.10)] ring-1 ring-forest-100/80 transition-all duration-300 ease-out hover:scale-[1.03] hover:-translate-y-1 hover:ring-2 hover:ring-forest-900 hover:shadow-[0_24px_70px_rgba(20,37,19,0.18)]">
                    <div class="mx-auto flex h-[72px] w-[72px] items-center justify-center rounded-[22px] bg-forest-100 text-forest-700 transition-all duration-300 group-hover:bg-forest-200">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 7.5 12 4l8 3.5M5 8v8.5L12 20l7-3.5V8M8.5 10.5h7" />
                        </svg>
                    </div>
                    <h3 class="mt-6 text-[18px] sm:text-[20px] font-bold text-forest-800">Food Rescue</h3>
                    <p class="mt-3 text-[15px] leading-[1.8] text-slate-500">
                        Penyelamatan pangan surplus dari produksi, retail, restoran, dan individu agar tetap bernilai guna.
                    </p>
                </article>

                <article class="group rounded-[28px] bg-white px-6 py-7 sm:px-8 sm:py-9 text-center shadow-[0_18px_55px_rgba(67,98,72,0.10)] ring-1 ring-forest-100/80 transition-all duration-300 ease-out hover:scale-[1.03] hover:-translate-y-1 hover:ring-2 hover:ring-forest-900 hover:shadow-[0_24px_70px_rgba(20,37,19,0.18)]">
                    <div class="mx-auto flex h-[72px] w-[72px] items-center justify-center rounded-[22px] bg-forest-100 text-forest-700 transition-all duration-300 group-hover:bg-forest-200">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 8h10M8 12h8M9 16h6M5 4h14a1 1 0 0 1 1 1v14l-3-2-3 2-2-2-2 2-3-2-3 2V5a1 1 0 0 1 1-1Z" />
                        </svg>
                    </div>
                    <h3 class="mt-6 text-[18px] sm:text-[20px] font-bold text-forest-800">Zero Waste Food</h3>
                    <p class="mt-3 text-[15px] leading-[1.8] text-slate-500">
                        Edukasi pengurangan limbah pangan melalui kebiasaan konsumsi yang lebih bijak dan berkelanjutan.
                    </p>
                </article>

                <article class="group rounded-[28px] bg-white px-6 py-7 sm:px-8 sm:py-9 text-center shadow-[0_18px_55px_rgba(67,98,72,0.10)] ring-1 ring-forest-100/80 transition-all duration-300 ease-out hover:scale-[1.03] hover:-translate-y-1 hover:ring-2 hover:ring-forest-900 hover:shadow-[0_24px_70px_rgba(20,37,19,0.18)]">
                    <div class="mx-auto flex h-[72px] w-[72px] items-center justify-center rounded-[22px] bg-forest-100 text-forest-700 transition-all duration-300 group-hover:bg-forest-200">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 11a3 3 0 1 1 0-6 3 3 0 0 1 0 6Zm8 2a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM3.5 19a4.5 4.5 0 0 1 9 0M11.5 19a4.5 4.5 0 0 1 9 0" />
                        </svg>
                    </div>
                    <h3 class="mt-6 text-[18px] sm:text-[20px] font-bold text-forest-800">Relawan &amp; Komunitas</h3>
                    <p class="mt-3 text-[15px] leading-[1.8] text-slate-500">
                        Penguatan jejaring relawan dan komunitas lokal untuk distribusi, edukasi, dan gerakan gotong royong.
                    </p>
                </article>

                <article class="group rounded-[28px] bg-white px-6 py-7 sm:px-8 sm:py-9 text-center shadow-[0_18px_55px_rgba(67,98,72,0.10)] ring-1 ring-forest-100/80 transition-all duration-300 ease-out hover:scale-[1.03] hover:-translate-y-1 hover:ring-2 hover:ring-forest-900 hover:shadow-[0_24px_70px_rgba(20,37,19,0.18)]">
                    <div class="mx-auto flex h-[72px] w-[72px] items-center justify-center rounded-[22px] bg-forest-100 text-forest-700 transition-all duration-300 group-hover:bg-forest-200">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4 6.5A2.5 2.5 0 0 1 6.5 4h11A2.5 2.5 0 0 1 20 6.5v11a2.5 2.5 0 0 1-2.5 2.5h-11A2.5 2.5 0 0 1 4 17.5v-11ZM8 9h8M8 12h8M8 15h5" />
                        </svg>
                    </div>
                    <h3 class="mt-6 text-[18px] sm:text-[20px] font-bold text-forest-800">Program Mahasiswa</h3>
                    <p class="mt-3 text-[15px] leading-[1.8] text-slate-500">
                        Keterlibatan mahasiswa dalam aksi sosial, riset lapangan, dan kampanye pangan berkelanjutan.
                    </p>
                </article>

                <article class="group rounded-[28px] bg-white px-6 py-7 sm:px-8 sm:py-9 text-center shadow-[0_18px_55px_rgba(67,98,72,0.10)] ring-1 ring-forest-100/80 transition-all duration-300 ease-out hover:scale-[1.03] hover:-translate-y-1 hover:ring-2 hover:ring-forest-900 hover:shadow-[0_24px_70px_rgba(20,37,19,0.18)]">
                    <div class="mx-auto flex h-[72px] w-[72px] items-center justify-center rounded-[22px] bg-forest-100 text-forest-700 transition-all duration-300 group-hover:bg-forest-200">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7h8M7 4h10a2 2 0 0 1 2 2v12l-4-2-3 3-3-3-4 2V6a2 2 0 0 1 2-2Zm2.5 6 1.5 1.5L14.5 9" />
                        </svg>
                    </div>
                    <h3 class="mt-6 text-[18px] sm:text-[20px] font-bold text-forest-800">Rewards Point Telkomsel</h3>
                    <p class="mt-3 text-[15px] leading-[1.8] text-slate-500">
                        Aktivasi partisipasi publik melalui penukaran poin Telkomsel untuk mendukung aksi pangan bermakna.
                    </p>
                </article>

                <article class="group rounded-[28px] bg-white px-6 py-7 sm:px-8 sm:py-9 text-center shadow-[0_18px_55px_rgba(67,98,72,0.10)] ring-1 ring-forest-100/80 transition-all duration-300 ease-out hover:scale-[1.03] hover:-translate-y-1 hover:ring-2 hover:ring-forest-900 hover:shadow-[0_24px_70px_rgba(20,37,19,0.18)]">
                    <div class="mx-auto flex h-[72px] w-[72px] items-center justify-center rounded-[22px] bg-forest-100 text-forest-700 transition-all duration-300 group-hover:bg-forest-200">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12h11m0 0-3.5-3.5M14 12l-3.5 3.5M14 7h3a4 4 0 0 1 4 4v2a4 4 0 0 1-4 4h-3" />
                        </svg>
                    </div>
                    <h3 class="mt-6 text-[18px] sm:text-[20px] font-bold text-forest-800">Distribusi Pangan</h3>
                    <p class="mt-3 text-[15px] leading-[1.8] text-slate-500">
                        Penyaluran pangan yang cepat, tepat sasaran, dan menjangkau masyarakat rentan di berbagai wilayah.
                    </p>
                </article>
            </div>
        </div>
    </section>

    <!-- ============================
     MAP + STATS + PDF + JOURNEY
============================== -->

    <div id="statistik-section" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-28 sm:pt-32 lg:pt-40">
        <h2 class="font-bold text-black text-[22px] sm:text-[26px] tracking-tight">
            Statistik Lingkungan
        </h2>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-5">
        <div class="relative h-1 w-40 overflow-hidden rounded-full bg-gradient-to-r from-forest-300/80 to-transparent">
        </div>
    </div>
    <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
        <div class="-mx-4 overflow-x-auto px-4 pb-1 sm:mx-0 sm:px-0">
            <div class="stat-tab-group" role="tablist" aria-label="Statistik lingkungan">
                <button type="button" class="stat-tab is-active text-[13px] sm:text-[14px] font-semibold px-4 py-2.5 whitespace-nowrap" data-stat-tab="food" aria-pressed="true">
                    Pangan Diselamatkan Hari Ini
                </button>
                <button type="button" class="stat-tab text-[13px] sm:text-[14px] font-semibold px-4 py-2.5 whitespace-nowrap" data-stat-tab="carbon" aria-pressed="false">
                    Emisi Karbon Dicegah Hari Ini
                </button>
                <button type="button" class="stat-tab text-[13px] sm:text-[14px] font-semibold px-4 py-2.5 whitespace-nowrap" data-stat-tab="distribution" aria-pressed="false">
                    Distribusi Lokal Bulan Ini
                </button>
            </div>
        </div>
    </div>
    <section class="stat-section-bg pt-16 sm:pt-20 lg:pt-24 pb-7">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col gap-6">
                <div class="mt-4 lg:mt-6 grid gap-8 lg:grid-cols-[minmax(0,1fr)_minmax(0,3fr)] lg:items-start">
                    <div class="flex flex-col items-center text-center mx-auto lg:mx-0 lg:items-start lg:text-left">
                        <span id="stat-metric-kicker"></span>

                        <div class="mt-5 flex flex-wrap items-end justify-center gap-2 sm:gap-3 lg:justify-start">
                            <div class="text-[48px] sm:text-[84px] lg:text-[96px] font-extrabold leading-none text-white drop-shadow-[0_8px_24px_rgba(0,0,0,0.22)]" id="stat-metric-value">1.248</div>
                            <div class="pb-2 sm:pb-3 text-[14px] sm:text-[18px] font-semibold text-white" id="stat-metric-unit">kg</div>
                        </div>

                        <div class="mt-3 text-[17px] sm:text-[22px] font-semibold text-white" id="stat-metric-title">Pangan telah diselamatkan</div>

                        <p class="mt-2 max-w-[360px] text-[14px] sm:text-[16px] text-white/85" id="stat-metric-desc">Setara dengan 8,7 ton CO₂ terserap</p>

                        <div class="hidden" aria-hidden="true">
                            <div id="stat-metric-avg">1.031</div>
                            <div id="stat-metric-max">1.248</div>
                        </div>
                    </div>

                    <div class="w-full">
                        <div class="relative stat-chart-glass p-0" data-stat-chart-shell>
                            <svg id="stat-line-chart" viewBox="0 0 700 240" class="w-full h-auto block" role="img" aria-label="Grafik statistik lingkungan"></svg>
                            <div id="stat-chart-tooltip" class="pointer-events-none absolute z-20 hidden -translate-x-1/2 -translate-y-full rounded-xl bg-gray-900 px-3 py-2 text-white shadow-lg">
                                <div class="text-[10px] uppercase tracking-[0.18em] text-white/60">Value</div>
                                <div class="mt-0.5 text-sm font-bold leading-none" id="stat-chart-tooltip-value">1.248</div>
                            </div>
                        </div>
                        <div id="stat-line-labels" class="mt-3 grid grid-cols-7 gap-1 text-center text-[10px] sm:text-[11px] text-gray-600"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================
     LEADERBOARD SECTION
============================== -->
    <section class="pt-[5.5rem] sm:pt-[7.5rem] pb-[3.25rem] sm:pb-[3.75rem] relative overflow-hidden bg-white" style="background-image:url('/assets/backgrounds/texture.png'); background-repeat:repeat; background-position:center top; background-size:720px auto;">
        <img src="/assets/backgrounds/texture-blob-1.svg" alt="" aria-hidden="true" class="pointer-events-none absolute inset-y-0 right-0 z-0 h-full w-auto max-w-none opacity-60">
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative grid grid-cols-1 xl:grid-cols-2 gap-6">

            <!-- ---- BRAND PARTNER TERBAIK ---- -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 hover-lift">
                <div class="px-5 py-5 sm:px-6 flex flex-col items-start gap-2 sm:flex-row sm:items-center sm:justify-between border-b border-gray-100">
                    <div class="flex flex-col gap-1">
                        <h3 class="font-bold text-gray-900 text-[1.45rem] sm:text-[2rem] leading-tight">Brand Partner Terbaik</h3>
                        <p class="text-[12px] sm:text-[13px] text-gray-500">Peringkat ini diperbarui setiap tahun agar siklus apresiasi tetap relevan dan berkelanjutan.</p>
                    </div>
                    <!-- <button class="text-forest-500 text-sm font-semibold hover:text-forest-600 transition-colors">Lihat Semua</button> -->
                </div>

                <!-- Top 3 Podium -->
                <div class="bg-gray-50/80 px-4 sm:px-6 pt-6 pb-0 flex flex-wrap sm:flex-nowrap items-end justify-between gap-3 sm:gap-4">

                    <!-- Rank 2 — Alfamart -->
                    <div class="flex flex-col items-center gap-2 pb-0 flex-1 basis-0 min-w-0" style="margin-bottom:0">
                        <div class="medal-badge medal-silver" aria-label="Juara 2">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M8 3h3l1 3h0L13 3h3l-2 5h-4L8 3z" fill="currentColor" opacity=".35" />
                                <circle cx="12" cy="14" r="5" fill="currentColor" />
                            </svg>
                        </div>
                        <div class="w-[92px] sm:w-[106px] lg:w-[120px] h-[74px] sm:h-[82px] lg:h-[88px] bg-white rounded-xl border border-gray-200 flex flex-col items-center justify-center shadow-sm gap-1">
                            <?php $brandIcon = retailBrandIconPath('Alfamart'); ?>
                            <?php if ($brandIcon): ?>
                                <img src="<?= htmlspecialchars($brandIcon) ?>" alt="Logo Alfamart" class="h-14 sm:h-16 lg:h-[72px] w-auto object-contain">
                            <?php else: ?>
                                <div class="w-8 h-8 bg-red-50 rounded-lg flex items-center justify-center">
                                    <span class="text-[10px] font-extrabold text-red-600 leading-none text-center">ALF</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="text-center w-full">
                            <div class="text-[12px] sm:text-[13px] font-semibold text-gray-700">Alfamart</div>
                            <div class="text-[12px] sm:text-[13px] text-forest-500 font-bold">4.050 kg</div>
                            <div class="text-[10px] sm:text-[11px] text-gray-400">Total Disalurkan</div>
                        </div>
                        <div class="podium-base-2 text-white w-full max-w-[118px] sm:max-w-[140px] text-center py-2.5 rounded-t-lg font-bold text-sm">2</div>
                    </div>

                    <!-- Rank 1 — Indomaret (elevated center) -->
                    <div class="flex flex-col items-center gap-2 pb-0 flex-[1.18] basis-0 min-w-0" style="margin-bottom:0">
                        <div class="medal-badge medal-gold" aria-label="Juara 1">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M8 3h3l1 3h0L13 3h3l-2 5h-4L8 3z" fill="currentColor" opacity=".35" />
                                <circle cx="12" cy="14" r="5" fill="currentColor" />
                            </svg>
                        </div>
                        <div class="w-[106px] sm:w-[120px] lg:w-[134px] h-[84px] sm:h-[94px] lg:h-[102px] bg-forest-700 rounded-xl border-2 border-amber-brand/60 flex flex-col items-center justify-center shadow-lg gap-1">
                            <?php $brandIcon = retailBrandIconPath('Indomaret'); ?>
                            <?php if ($brandIcon): ?>
                                <img src="<?= htmlspecialchars($brandIcon) ?>" alt="Logo Indomaret" class="h-16 sm:h-20 lg:h-[88px] w-auto object-contain">
                            <?php else: ?>
                                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                    <span class="text-[9px] font-extrabold text-white leading-none text-center">IND</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="text-center w-full">
                            <div class="text-[13px] sm:text-[14px] font-bold text-gray-900">Indomaret</div>
                            <div class="text-[13px] sm:text-[14px] text-amber-brand font-bold">4.800 kg</div>
                            <div class="text-[10px] sm:text-[11px] text-gray-400">Total Disalurkan</div>
                        </div>
                        <div class="podium-base-1 text-white w-full max-w-[134px] sm:max-w-[154px] text-center py-3.5 rounded-t-lg font-bold text-sm">1</div>
                    </div>

                    <!-- Rank 3 — Hypermart -->
                    <div class="flex flex-col items-center gap-2 pb-0 flex-1 basis-0 min-w-0" style="margin-bottom:0">
                        <div class="medal-badge medal-bronze" aria-label="Juara 3">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M8 3h3l1 3h0L13 3h3l-2 5h-4L8 3z" fill="currentColor" opacity=".35" />
                                <circle cx="12" cy="14" r="5" fill="currentColor" />
                            </svg>
                        </div>
                        <div class="w-[92px] sm:w-[106px] lg:w-[120px] h-[74px] sm:h-[82px] lg:h-[88px] bg-white rounded-xl border border-gray-200 flex flex-col items-center justify-center shadow-sm gap-1">
                            <?php $brandIcon = retailBrandIconPath('Hypermart'); ?>
                            <?php if ($brandIcon): ?>
                                <img src="<?= htmlspecialchars($brandIcon) ?>" alt="Logo Hypermart" class="h-14 sm:h-16 lg:h-[72px] w-auto object-contain">
                            <?php else: ?>
                                <div class="w-8 h-8 bg-blue-50 rounded-lg flex items-center justify-center">
                                    <span class="text-[9px] font-extrabold text-blue-700 leading-none text-center">HYP</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="text-center w-full">
                            <div class="text-[12px] sm:text-[13px] font-semibold text-gray-700">Hypermart</div>
                            <div class="text-[12px] sm:text-[13px] text-forest-500 font-bold">3.450 kg</div>
                            <div class="text-[10px] sm:text-[11px] text-gray-400">Total Disalurkan</div>
                        </div>
                        <div class="podium-base-3 text-white w-full max-w-[118px] sm:max-w-[140px] text-center py-2.5 rounded-t-lg font-bold text-sm">3</div>
                    </div>
                </div>

                <!-- List 4–8 -->
                <div class="px-5 py-4 space-y-2">
                    <!-- Item helper template (written out) -->
                    <div class="flex flex-wrap items-center gap-3 py-2 px-3 rounded-xl hover:bg-gray-50 transition-colors sm:flex-nowrap">
                        <span class="text-xs text-gray-400 font-semibold w-5 text-center">4</span>
                        <?php $brandIcon = retailBrandIconPath('Transmart Carrefour'); ?>
                        <div class="w-7 h-7 bg-red-50 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                            <?php if ($brandIcon): ?>
                                <img src="<?= htmlspecialchars($brandIcon) ?>" alt="Logo Transmart Carrefour" class="h-6 w-auto object-contain">
                            <?php else: ?>
                                <span class="text-[8px] font-bold text-red-600">TRC</span>
                            <?php endif; ?>
                        </div>
                        <span class="min-w-0 flex-1 text-[13px] font-medium text-gray-700">Transmart Carrefour</span>
                        <span class="w-full text-[12px] font-bold text-gray-600 sm:w-20 sm:text-right">2.780 kg</span>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 py-2 px-3 rounded-xl hover:bg-gray-50 transition-colors sm:flex-nowrap">
                        <span class="text-xs text-gray-400 font-semibold w-5 text-center">5</span>
                        <?php $brandIcon = retailBrandIconPath('Giant Supermarket'); ?>
                        <div class="w-7 h-7 bg-orange-50 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                            <?php if ($brandIcon): ?>
                                <img src="<?= htmlspecialchars($brandIcon) ?>" alt="Logo Giant Supermarket" class="h-6 w-auto object-contain">
                            <?php else: ?>
                                <span class="text-[8px] font-bold text-orange-600">GSM</span>
                            <?php endif; ?>
                        </div>
                        <span class="min-w-0 flex-1 text-[13px] font-medium text-gray-700">Giant Supermarket</span>
                        <span class="w-full text-[12px] font-bold text-gray-600 sm:w-20 sm:text-right">1.950 kg</span>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 py-2 px-3 rounded-xl hover:bg-gray-50 transition-colors sm:flex-nowrap">
                        <span class="text-xs text-gray-400 font-semibold w-5 text-center">6</span>
                        <?php $brandIcon = retailBrandIconPath('Ranch Market'); ?>
                        <div class="w-7 h-7 bg-green-50 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                            <?php if ($brandIcon): ?>
                                <img src="<?= htmlspecialchars($brandIcon) ?>" alt="Logo Ranch Market" class="h-6 w-auto object-contain">
                            <?php else: ?>
                                <span class="text-[8px] font-bold text-green-600">RNM</span>
                            <?php endif; ?>
                        </div>
                        <span class="min-w-0 flex-1 text-[13px] font-medium text-gray-700">Ranch Market</span>
                        <span class="w-full text-[12px] font-bold text-gray-600 sm:w-20 sm:text-right">1.620 kg</span>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 py-2 px-3 rounded-xl hover:bg-gray-50 transition-colors sm:flex-nowrap">
                        <span class="text-xs text-gray-400 font-semibold w-5 text-center">7</span>
                        <?php $brandIcon = retailBrandIconPath('Lotte Mart'); ?>
                        <div class="w-7 h-7 bg-red-50 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                            <?php if ($brandIcon): ?>
                                <img src="<?= htmlspecialchars($brandIcon) ?>" alt="Logo Lotte Mart" class="h-6 w-auto object-contain">
                            <?php else: ?>
                                <span class="text-[8px] font-bold text-red-700">LTM</span>
                            <?php endif; ?>
                        </div>
                        <span class="min-w-0 flex-1 text-[13px] font-medium text-gray-700">Lotte Mart</span>
                        <span class="w-full text-[12px] font-bold text-gray-600 sm:w-20 sm:text-right">1.280 kg</span>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 py-2 px-3 rounded-xl hover:bg-gray-50 transition-colors sm:flex-nowrap">
                        <span class="text-xs text-gray-400 font-semibold w-5 text-center">8</span>
                        <?php $brandIcon = retailBrandIconPath('Super Indo'); ?>
                        <div class="w-7 h-7 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                            <?php if ($brandIcon): ?>
                                <img src="<?= htmlspecialchars($brandIcon) ?>" alt="Logo Super Indo" class="h-6 w-auto object-contain">
                            <?php else: ?>
                                <span class="text-[8px] font-bold text-blue-700">SI</span>
                            <?php endif; ?>
                        </div>
                        <span class="min-w-0 flex-1 text-[13px] font-medium text-gray-700">Super Indo</span>
                        <span class="w-full text-[12px] font-bold text-gray-600 sm:w-20 sm:text-right">980 kg</span>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-5 py-4 sm:px-6 border-t border-gray-100 bg-gray-50/60 flex flex-col items-start justify-between gap-3 sm:flex-row sm:items-center">
                    <div>
                        <div class="text-[11px] text-gray-400 uppercase tracking-wide font-medium">Total Partner</div>
                        <div class="text-[13px] font-bold text-gray-700 mt-0.5">8 Brand</div>
                    </div>
                    <div class="text-right">
                        <div class="text-[11px] text-gray-400 uppercase tracking-wide font-medium">Total Pangan Tersalurkan</div>
                        <div class="text-[15px] font-extrabold text-forest-500 mt-0.5">12,4 Ton</div>
                    </div>
                </div>
            </div>


            <!-- ---- DONATUR PERORANGAN TERBAIK ---- -->
            <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 hover-lift">
                <div class="px-5 py-5 sm:px-6 flex flex-col items-start gap-2 sm:flex-row sm:items-center sm:justify-between border-b border-gray-100">
                    <div class="flex flex-col gap-1">
                        <h3 class="font-bold text-gray-900 text-[1.45rem] sm:text-[2rem] leading-tight">Donatur Perorangan Terbaik</h3>
                        <p class="text-[12px] sm:text-[13px] text-gray-500">Peringkat ini akan diperbarui secara real time agar data selalu mencerminkan aktivitas terbaru.</p>
                    </div>
                    <!-- <button class="text-forest-500 text-sm font-semibold hover:text-forest-600 transition-colors">Lihat Semua</button> -->
                </div>

                <!-- Top 3 Podium -->
                <div class="bg-gray-50/80 px-4 sm:px-6 pt-6 pb-0 podium-top3">

                    <!-- Rank 2 — Budi Santoso -->
                    <div class="podium-entry">
                        <div class="medal-badge medal-silver" aria-label="Juara 2">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M8 3h3l1 3h0L13 3h3l-2 5h-4L8 3z" fill="currentColor" opacity=".35" />
                                <circle cx="12" cy="14" r="5" fill="currentColor" />
                            </svg>
                        </div>
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center border-2 border-white shadow-md">
                            <span class="text-white text-[11px] sm:text-lg font-bold">BS</span>
                        </div>
                        <div class="podium-stats">
                            <div class="podium-name text-[10px] sm:text-[12px] font-semibold text-gray-700">Budi Santoso</div>
                            <div class="text-[9px] sm:text-[11px] text-gray-500">387 Donasi</div>
                            <div class="text-[10px] sm:text-[12px] text-forest-500 font-bold">1.034 kg</div>
                        </div>
                        <div class="podium-base podium-base-2 text-white max-w-[76px] sm:max-w-[90px] text-center py-2 sm:py-2.5 rounded-t-lg font-bold text-[11px] sm:text-sm">2</div>
                    </div>

                    <!-- Rank 1 — Sari Dewi Rahayu (center, elevated) -->
                    <div class="podium-entry">
                        <div class="medal-badge medal-gold" aria-label="Juara 1">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M8 3h3l1 3h0L13 3h3l-2 5h-4L8 3z" fill="currentColor" opacity=".35" />
                                <circle cx="12" cy="14" r="5" fill="currentColor" />
                            </svg>
                        </div>
                        <div class="w-14 h-14 sm:w-20 sm:h-20 rounded-full bg-gradient-to-br from-forest-400 to-forest-700 flex items-center justify-center border-3 shadow-xl" style="border: 3px solid #C8883A;">
                            <span class="text-white text-[12px] sm:text-xl font-bold">SDR</span>
                        </div>
                        <div class="podium-stats">
                            <div class="podium-name text-[11px] sm:text-[14px] font-bold text-gray-900">Sari Dewi Rahayu</div>
                            <div class="text-[9px] sm:text-[12px] text-gray-500">452 Donasi</div>
                            <div class="text-[11px] sm:text-[14px] text-amber-brand font-bold">1.248 kg</div>
                        </div>
                        <div class="podium-base podium-base-1 text-white max-w-[84px] sm:max-w-[100px] text-center py-2.5 sm:py-3.5 rounded-t-lg font-bold text-[11px] sm:text-sm">1</div>
                    </div>

                    <!-- Rank 3 — Anita Wulandari -->
                    <div class="podium-entry">
                        <div class="medal-badge medal-bronze" aria-label="Juara 3">
                            <svg viewBox="0 0 24 24" fill="none">
                                <path d="M8 3h3l1 3h0L13 3h3l-2 5h-4L8 3z" fill="currentColor" opacity=".35" />
                                <circle cx="12" cy="14" r="5" fill="currentColor" />
                            </svg>
                        </div>
                        <div class="w-12 h-12 sm:w-16 sm:h-16 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center border-2 border-white shadow-md">
                            <span class="text-white text-[11px] sm:text-lg font-bold">AW</span>
                        </div>
                        <div class="podium-stats">
                            <div class="podium-name text-[10px] sm:text-[12px] font-semibold text-gray-700">Anita Wulandari</div>
                            <div class="text-[9px] sm:text-[11px] text-gray-500">316 Donasi</div>
                            <div class="text-[10px] sm:text-[12px] text-forest-500 font-bold">864 kg</div>
                        </div>
                        <div class="podium-base podium-base-3 text-white max-w-[76px] sm:max-w-[90px] text-center py-2 sm:py-2.5 rounded-t-lg font-bold text-[11px] sm:text-sm">3</div>
                    </div>
                </div>

                <!-- List 4–8 -->
                <div class="px-5 py-4 space-y-1">
                    <!-- Row helper -->
                    <div class="flex flex-wrap items-center gap-3 py-2 px-3 rounded-xl hover:bg-gray-50 transition-colors sm:flex-nowrap">
                        <span class="text-xs text-gray-400 font-semibold w-5 text-center">4</span>
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-teal-400 to-teal-600 flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-[9px] font-bold">RF</span>
                        </div>
                        <span class="min-w-0 flex-1 text-[13px] font-medium text-gray-700">Rasyi Firmansyah</span>
                        <span class="text-[11px] text-gray-400 sm:w-16 sm:text-right">298 Donasi</span>
                        <span class="w-full text-[12px] font-bold text-gray-600 sm:w-14 sm:text-right">786 kg</span>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 py-2 px-3 rounded-xl hover:bg-gray-50 transition-colors sm:flex-nowrap">
                        <span class="text-xs text-gray-400 font-semibold w-5 text-center">5</span>
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-rose-400 to-rose-600 flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-[9px] font-bold">FH</span>
                        </div>
                        <span class="min-w-0 flex-1 text-[13px] font-medium text-gray-700">Fikri Handayani</span>
                        <span class="text-[11px] text-gray-400 sm:w-16 sm:text-right">264 Donasi</span>
                        <span class="w-full text-[12px] font-bold text-gray-600 sm:w-14 sm:text-right">612 kg</span>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 py-2 px-3 rounded-xl hover:bg-gray-50 transition-colors sm:flex-nowrap">
                        <span class="text-xs text-gray-400 font-semibold w-5 text-center">6</span>
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-[9px] font-bold">AF</span>
                        </div>
                        <span class="min-w-0 flex-1 text-[13px] font-medium text-gray-700">Ahmad Fauzi</span>
                        <span class="text-[11px] text-gray-400 sm:w-16 sm:text-right">228 Donasi</span>
                        <span class="w-full text-[12px] font-bold text-gray-600 sm:w-14 sm:text-right">556 kg</span>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 py-2 px-3 rounded-xl hover:bg-gray-50 transition-colors sm:flex-nowrap">
                        <span class="text-xs text-gray-400 font-semibold w-5 text-center">7</span>
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-400 to-indigo-600 flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-[9px] font-bold">DK</span>
                        </div>
                        <span class="min-w-0 flex-1 text-[13px] font-medium text-gray-700">Desi Kurniawati</span>
                        <span class="text-[11px] text-gray-400 sm:w-16 sm:text-right">198 Donasi</span>
                        <span class="w-full text-[12px] font-bold text-gray-600 sm:w-14 sm:text-right">482 kg</span>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 py-2 px-3 rounded-xl hover:bg-gray-50 transition-colors sm:flex-nowrap">
                        <span class="text-xs text-gray-400 font-semibold w-5 text-center">8</span>
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-slate-400 to-slate-600 flex items-center justify-center flex-shrink-0">
                            <span class="text-white text-[9px] font-bold">HG</span>
                        </div>
                        <span class="min-w-0 flex-1 text-[13px] font-medium text-gray-700">Hendra Gunawan</span>
                        <span class="text-[11px] text-gray-400 sm:w-16 sm:text-right">175 Donasi</span>
                        <span class="w-full text-[12px] font-bold text-gray-600 sm:w-14 sm:text-right">398 kg</span>
                    </div>
                </div>

                <!-- Footer -->
                <div class="px-5 py-4 sm:px-6 border-t border-gray-100 bg-gray-50/60 flex flex-col items-start justify-between gap-3 sm:flex-row sm:items-center">
                    <div>
                        <div class="text-[11px] text-gray-400 uppercase tracking-wide font-medium">Total Donatur</div>
                        <div class="text-[13px] font-bold text-gray-700 mt-0.5">4.201 Orang</div>
                    </div>
                    <div class="text-right">
                        <div class="text-[11px] text-gray-400 uppercase tracking-wide font-medium">Total Pangan Didonasikan</div>
                        <div class="text-[15px] font-extrabold text-forest-500 mt-0.5">12,4 Ton</div>
                    </div>
                </div>
            </div>

        </div><!-- /grid -->
    </section>

    <!-- ============================
    MAP FULL WIDTH
   ============================== -->
    <section id="lokasi-section" class="py-14 sm:py-20 lg:py-16 relative overflow-hidden bg-white" style="background-image:url('/assets/backgrounds/texture.png'); background-repeat:repeat; background-position:center top; background-size:720px auto;">
        <img src="/assets/backgrounds/texture-blob-2.svg" alt="" aria-hidden="true" class="pointer-events-none absolute inset-y-0 left-0 z-0 h-full w-auto max-w-none opacity-55">
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-6">
            <h2 class="font-bold text-forest-950 text-[22px] sm:text-[26px] tracking-tight">Jaringan Pos Titipangan</h2>
            <p class="text-[12px] sm:text-[13px] text-forest-700/70 mt-1 max-w-2xl">Pantau sebaran pos titipangan dan titik layanan di berbagai wilayah.</p>
        </div>
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="map-embed-frame">
                <iframe
                    src="https://www.google.com/maps/d/embed?mid=1AvY6oww_LdJ1HcJeU9qP8KQ5fsz3Ylc&ehbc=2E312F"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>

    <!-- ============================
     BERITA + INSPIRASI + JOURNEY
============================== -->
    <section id="regulasi-section" class="pt-0 sm:pt-0 lg:pt-0 pb-16 sm:pb-20 lg:pb-16 relative overflow-hidden bg-gradient-to-br from-forest-700 via-forest-900 to-forest-900 mt-12 sm:mt-16 lg:mt-20">
        <div class="absolute inset-0 pointer-events-none opacity-45" style="background-image: radial-gradient(circle at 18% 18%, rgba(255,255,255,0.10) 0, rgba(255,255,255,0) 30%), radial-gradient(circle at 85% 12%, rgba(200,136,58,0.10) 0, rgba(200,136,58,0) 24%), linear-gradient(180deg, rgba(255,255,255,0.04), rgba(255,255,255,0));"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 sm:pt-10 lg:pt-20 pb-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h2 class="text-[22px] sm:text-[26px] font-bold text-white tracking-tight pb-3">Regulasi</h2>
                    <p class="text-[13px] sm:text-[14px] leading-[1.8] text-white pb-4">Panduan regulasi ini membantu seluruh pihak memahami peran, tanggung jawab, dan alur partisipasi dalam ekosistem Titipangan.</p>
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <a href="/assets/regulasi-konsumen.pdf" target="_blank" rel="noopener noreferrer" class="group flex min-h-[380px] sm:min-h-[420px] flex-col overflow-hidden rounded-2xl bg-forest-900 ring-1 ring-transparent shadow-[0_18px_40px_rgba(15,23,42,0.18)] transition-all duration-300 ease-out hover:scale-[1.03] hover:-translate-y-1 hover:ring-white hover:shadow-[0_24px_60px_rgba(0,0,0,0.28)]">
                    <div class="relative aspect-square overflow-hidden bg-forest-900">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.16),transparent_38%)]"></div>
                        <img src="/assets/illustrations/konsumen.png" alt="Ilustrasi regulasi konsumen" class="relative z-10 h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                    </div>
                    <div class="flex flex-1 flex-col bg-forest-900 px-5 py-5 sm:px-6 sm:py-6">
                        <div class="flex flex-col gap-2">
                            <span class="inline-flex w-fit rounded-full bg-forest-500/90 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.14em] text-white shadow-sm">Konsumen</span>
                            <span class="text-[12px] font-medium text-white">Regulasi PDF</span>
                        </div>
                        <div class="mt-5 flex items-end justify-between gap-4">
                            <h3 class="max-w-[14rem] text-[18px] sm:text-[19px] font-semibold leading-[1.22] text-white">Regulasi Konsumen</h3>
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white text-gray-900 shadow-lg transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5">
                                <span class="text-sm">→</span>
                            </span>
                        </div>
                        <p class="mt-3 text-[13px] leading-[1.75] text-white">
                            Regulasi konsumen mengatur mekanisme akses, pengambilan, dan pemanfaatan pangan agar bantuan diterima secara adil, aman, dan sesuai kebutuhan.
                        </p>
                    </div>
                </a>

                <a href="/assets/regulasi-donatur.pdf" target="_blank" rel="noopener noreferrer" class="group flex min-h-[380px] sm:min-h-[420px] flex-col overflow-hidden rounded-2xl bg-forest-900 ring-1 ring-transparent shadow-[0_18px_40px_rgba(15,23,42,0.18)] transition-all duration-300 ease-out hover:scale-[1.03] hover:-translate-y-1 hover:ring-white hover:shadow-[0_24px_60px_rgba(0,0,0,0.28)]">
                    <div class="relative aspect-square overflow-hidden bg-forest-900">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.16),transparent_38%)]"></div>
                        <img src="/assets/illustrations/donatur.png" alt="Ilustrasi regulasi donatur" class="relative z-10 h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                    </div>
                    <div class="flex flex-1 flex-col bg-forest-900 px-5 py-5 sm:px-6 sm:py-6">
                        <div class="flex flex-col gap-2">
                            <span class="inline-flex w-fit rounded-full bg-amber-brand/90 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.14em] text-white shadow-sm">Donatur</span>
                            <span class="text-[12px] font-medium text-white">Regulasi PDF</span>
                        </div>
                        <div class="mt-5 flex items-end justify-between gap-4">
                            <h3 class="max-w-[14rem] text-[18px] sm:text-[19px] font-semibold leading-[1.22] text-white">Regulasi Donatur</h3>
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white text-gray-900 shadow-lg transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5">
                                <span class="text-sm">→</span>
                            </span>
                        </div>
                        <p class="mt-3 text-[13px] leading-[1.75] text-white">
                            Regulasi donatur menjelaskan standar kelayakan pangan, alur penyerahan, tanggung jawab data, dan prinsip transparansi proses donasi.
                        </p>
                    </div>
                </a>

                <a href="/assets/regulasi-volunteer.pdf" target="_blank" rel="noopener noreferrer" class="group flex min-h-[380px] sm:min-h-[420px] flex-col overflow-hidden rounded-2xl bg-forest-900 ring-1 ring-transparent shadow-[0_18px_40px_rgba(15,23,42,0.18)] transition-all duration-300 ease-out hover:scale-[1.03] hover:-translate-y-1 hover:ring-white hover:shadow-[0_24px_60px_rgba(0,0,0,0.28)]">
                    <div class="relative aspect-square overflow-hidden bg-forest-900">
                        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(255,255,255,0.16),transparent_38%)]"></div>
                        <img src="/assets/illustrations/volunteer.png" alt="Ilustrasi regulasi volunteer" class="relative z-10 h-full w-full object-cover transition-transform duration-500 group-hover:scale-105">
                    </div>
                    <div class="flex flex-1 flex-col bg-forest-900 px-5 py-5 sm:px-6 sm:py-6">
                        <div class="flex flex-col gap-2">
                            <span class="inline-flex w-fit rounded-full bg-forest-600/90 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.14em] text-white shadow-sm">Volunteer</span>
                            <span class="text-[12px] font-medium text-white">Regulasi PDF</span>
                        </div>
                        <div class="mt-5 flex items-end justify-between gap-4">
                            <h3 class="max-w-[14rem] text-[18px] sm:text-[19px] font-semibold leading-[1.22] text-white">Regulasi Volunteer</h3>
                            <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white text-gray-900 shadow-lg transition-transform duration-300 group-hover:translate-x-0.5 group-hover:-translate-y-0.5">
                                <span class="text-sm">→</span>
                            </span>
                        </div>
                        <p class="mt-3 text-[13px] leading-[1.75] text-white">
                            Regulasi volunteer menjadi panduan etika lapangan, distribusi, dokumentasi, dan koordinasi agar peran relawan tetap aman dan profesional.
                        </p>
                    </div>
                </a>
            </div>
            </div>
        </div>
    </section>

    <section class="bg-white mt-14 sm:mt-20 py-8 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative overflow-hidden rounded-[28px] border border-forest-800/40 bg-forest-950 shadow-[0_24px_60px_rgba(7,18,8,0.24)]">
                <div class="absolute inset-0 bg-[radial-gradient(circle_at_left_top,rgba(90,170,110,0.20),transparent_34%),radial-gradient(circle_at_right_center,rgba(200,136,58,0.16),transparent_26%),linear-gradient(90deg,rgba(7,18,8,0.96),rgba(13,31,16,0.92))]"></div>
                <div class="absolute inset-y-0 right-0 hidden w-[42%] bg-[radial-gradient(circle_at_70%_50%,rgba(255,255,255,0.12),transparent_34%)] lg:block"></div>
                <div class="absolute -right-10 bottom-0 h-40 w-40 rounded-full bg-forest-400/10 blur-2xl"></div>
                <div class="relative z-10 flex flex-col gap-8 px-5 py-6 sm:px-8 sm:py-8 lg:flex-row lg:items-end lg:justify-between lg:px-10">
                    <div class="max-w-2xl">
                        <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-3 py-1 text-[10px] font-semibold uppercase tracking-[0.18em] text-forest-100">
                            Katalog Publik
                        </span>
                        <h3 class="mt-4 text-[24px] sm:text-[34px] font-extrabold leading-[1.08] tracking-tight text-white">
                            Temukan pangan layak yang siap disalurkan.
                        </h3>
                        <p class="mt-3 max-w-xl text-[14px] sm:text-[15px] leading-[1.8] text-white">
                            Jelajahi katalog Titipangan untuk melihat ketersediaan bantuan, jenis pangan, dan distribusi yang sedang berjalan di berbagai wilayah.
                        </p>
                        <a href="/katalog-publik" class="mt-6 inline-flex w-full sm:w-auto items-center justify-center gap-2 rounded-2xl bg-gradient-to-r from-forest-300 to-forest-400 px-5 py-3 text-[14px] font-semibold text-forest-950 shadow-[0_12px_28px_rgba(90,170,110,0.32)] transition-all hover:translate-y-[-1px] hover:from-forest-200 hover:to-forest-300 whitespace-nowrap">
                            Lihat Katalog
                            <span aria-hidden="true">→</span>
                        </a>
                    </div>
                    <div class="relative hidden min-h-[180px] flex-1 lg:block">
                        <img src="/assets/illustrations/katalog.png" alt="Ilustrasi katalog Titipangan" class="absolute right-0 bottom-0 h-[220px] w-auto max-w-none object-contain">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bg-white leading-none overflow-hidden">
        <img src="/assets/backgrounds/transisi-white-to-green.svg" alt="" aria-hidden="true" class="block w-full h-auto">
    </section>


    <!-- ============================
     FOOTER
============================== -->
    <footer class="bg-forest-900" style="background:#0d1f10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-2 gap-8 sm:gap-10 pb-10 border-b border-forest-700/50">

                <!-- Brand -->
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-9 h-9 bg-forest-500 rounded-lg flex items-center justify-center">
                            <span class="text-base">🌾</span>
                        </div>
                        <span class="text-white font-bold text-[17px]">Titipangan</span>
                    </div>
                    <p class="text-forest-200/60 text-[13px] leading-relaxed mb-5">
                        Platform kolaborasi untuk mengurangi pangan terbuang dan menciptakan dampak positif bagi bumi.
                    </p>
                    <!-- Social -->
                    <div class="flex flex-wrap gap-3">
                        <a href="#" class="w-8 h-8 bg-forest-700 hover:bg-forest-500 rounded-lg flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                            </svg>
                        </a>
                        <a href="#" class="w-8 h-8 bg-forest-700 hover:bg-forest-500 rounded-lg flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                            </svg>
                        </a>
                        <a href="#" class="w-8 h-8 bg-forest-700 hover:bg-forest-500 rounded-lg flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                            </svg>
                        </a>
                        <a href="#" class="w-8 h-8 bg-forest-700 hover:bg-forest-500 rounded-lg flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.495 6.205a3.007 3.007 0 00-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 00.527 6.205a31.247 31.247 0 00-.522 5.805 31.247 31.247 0 00.522 5.783 3.007 3.007 0 002.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 002.088-2.088 31.247 31.247 0 00.5-5.783 31.247 31.247 0 00-.5-5.805zM9.609 15.601V8.408l6.264 3.602z" />
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Bantuan -->
                <div>
                    <h3 class="text-white font-bold text-[14px] mb-5 tracking-wide">Bantuan</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-forest-200/60 hover:text-white text-[13px] transition-colors">FAQ</a></li>
                        <li><a href="#" class="text-forest-200/60 hover:text-white text-[13px] transition-colors">Hubungi Kami</a></li>
                        <li><a href="#" class="text-forest-200/60 hover:text-white text-[13px] transition-colors">Kebijakan Privasi</a></li>
                        <li><a href="#" class="text-forest-200/60 hover:text-white text-[13px] transition-colors">Syarat & Ketentuan</a></li>
                    </ul>
                </div>

            </div>

            <!-- Bottom bar -->
            <div class="pt-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 sm:gap-0">
                <p class="text-forest-200/40 text-[12px]">@ 2026 Titipangan. All rights reserved</p>
                <p class="text-forest-200/40 text-[12px] flex items-center gap-1">
                    Dibuat dengan <span class="text-red-400">♥</span> untuk Indonesia
                </p>
            </div>

        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const navbar = document.getElementById('site-navbar');
            const mobileNavToggle = document.getElementById('mobile-nav-toggle');
            const mobileNavMenu = document.getElementById('mobile-nav-menu');
            const tabs = Array.from(document.querySelectorAll('[data-stat-tab]'));
            const chart = document.getElementById('stat-line-chart');
            const labels = document.getElementById('stat-line-labels');
            const chartShell = document.querySelector('[data-stat-chart-shell]');
            const tooltip = document.getElementById('stat-chart-tooltip');
            const tooltipValue = document.getElementById('stat-chart-tooltip-value');
            const kicker = document.getElementById('stat-metric-kicker');
            const value = document.getElementById('stat-metric-value');
            const unit = document.getElementById('stat-metric-unit');
            const title = document.getElementById('stat-metric-title');
            const desc = document.getElementById('stat-metric-desc');
            const avg = document.getElementById('stat-metric-avg');
            const max = document.getElementById('stat-metric-max');
            if (!tabs.length || !chart || !labels || !chartShell || !tooltip || !tooltipValue || !kicker || !value || !unit || !title || !desc || !avg || !max) {
                return;
            }

            const updateNavbar = () => {
                if (!navbar) {
                    return;
                }

                navbar.classList.add('bg-transparent', 'border-transparent');
                navbar.classList.remove('bg-forest-900/95', 'border-white/10', 'backdrop-blur-md', 'shadow-lg');
            };

            const closeMobileNav = () => {
                if (!mobileNavMenu || !mobileNavToggle) {
                    return;
                }

                mobileNavMenu.classList.add('hidden');
                mobileNavToggle.setAttribute('aria-expanded', 'false');
            };

            if (mobileNavToggle && mobileNavMenu) {
                mobileNavToggle.addEventListener('click', () => {
                    const isHidden = mobileNavMenu.classList.contains('hidden');
                    mobileNavMenu.classList.toggle('hidden', !isHidden);
                    mobileNavToggle.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
                });

                mobileNavMenu.querySelectorAll('a').forEach((link) => {
                    link.addEventListener('click', closeMobileNav);
                });

                window.addEventListener('resize', () => {
                    if (window.innerWidth >= 1024) {
                        closeMobileNav();
                    }
                });
            }

            updateNavbar();
            window.addEventListener('scroll', updateNavbar, {
                passive: true
            });

            const seriesByYear = {
                2026: {
                    food: {
                        title: '',
                        unit: 'kg',
                        desc: 'Setara dengan 8,7 ton CO₂ terserap',
                        headline: 'Pangan telah diselamatkan',
                        accent: '#8ecf9a',
                        data: [820, 910, 980, 1040, 1120, 1200, 1248]
                    },
                    carbon: {
                        title: '',
                        unit: 'ton CO₂',
                        desc: 'Mengurangi jejak emisi dalam 7 hari terakhir.',
                        headline: 'Emisi telah dicegah',
                        accent: '#7dd3fc',
                        data: [1.6, 1.8, 1.9, 2.1, 2.3, 2.4, 2.5]
                    },
                    distribution: {
                        title: '',
                        unit: 'distribusi',
                        desc: 'Menjangkau lebih banyak penerima manfaat.',
                        headline: 'Distribusi lokal berjalan',
                        accent: '#fbbf24',
                        data: [760, 830, 910, 980, 1040, 1120, 1200]
                    }
                },
                2025: {
                    food: {
                        title: '',
                        unit: 'kg',
                        desc: 'Setara dengan 7,9 ton CO₂ terserap',
                        headline: 'Pangan telah diselamatkan',
                        accent: '#8ecf9a',
                        data: [760, 820, 870, 940, 1010, 1080, 1160]
                    },
                    carbon: {
                        title: '',
                        unit: 'ton CO₂',
                        desc: 'Mengurangi jejak emisi dalam 7 hari terakhir.',
                        headline: 'Emisi telah dicegah',
                        accent: '#7dd3fc',
                        data: [1.3, 1.5, 1.6, 1.8, 2.0, 2.1, 2.2]
                    },
                    distribution: {
                        title: 'Distribusi Lokal Bulan Ini',
                        unit: 'distribusi',
                        desc: 'Menjangkau lebih banyak penerima manfaat.',
                        headline: 'Distribusi lokal berjalan',
                        accent: '#fbbf24',
                        data: [680, 720, 790, 850, 920, 980, 1040]
                    }
                },
                2024: {
                    food: {
                        title: '',
                        unit: 'kg',
                        desc: 'Setara dengan 6,8 ton CO₂ terserap',
                        headline: 'Pangan telah diselamatkan',
                        accent: '#8ecf9a',
                        data: [640, 700, 760, 820, 890, 950, 1020]
                    },
                    carbon: {
                        title: '',
                        unit: 'ton CO₂',
                        desc: 'Mengurangi jejak emisi dalam 7 hari terakhir.',
                        headline: 'Emisi telah dicegah',
                        accent: '#7dd3fc',
                        data: [1.1, 1.2, 1.3, 1.5, 1.6, 1.8, 1.9]
                    },
                    distribution: {
                        title: 'Distribusi Lokal Bulan Ini',
                        unit: 'distribusi',
                        desc: 'Menjangkau lebih banyak penerima manfaat.',
                        headline: 'Distribusi lokal berjalan',
                        accent: '#fbbf24',
                        data: [560, 610, 660, 720, 780, 840, 900]
                    }
                }
            };

            const dayLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
            let activeYear = 2026;

            const formatNumber = (number) => new Intl.NumberFormat('id-ID', {
                maximumFractionDigits: number >= 10 ? 0 : 1,
                minimumFractionDigits: number < 10 && !Number.isInteger(number) ? 1 : 0
            }).format(number);

            const buildPath = (points, bottomY) => {
                if (!points.length) {
                    return {
                        line: '',
                        area: ''
                    };
                }

                const lastPoint = points[points.length - 1];
                const firstPoint = points[0];
                const line = points.map((point, index) => `${index === 0 ? 'M' : 'L'} ${point.x.toFixed(1)} ${point.y.toFixed(1)}`).join(' ');
                const area = `${line} L ${lastPoint.x.toFixed(1)} ${bottomY.toFixed(1)} L ${firstPoint.x.toFixed(1)} ${bottomY.toFixed(1)} Z`;
                return {
                    line,
                    area
                };
            };

            const renderChart = (config, animate = false) => {
                const width = 700;
                const height = 240;
                const paddingX = 28;
                const paddingY = 24;
                const innerWidth = width - (paddingX * 2);
                const innerHeight = height - (paddingY * 2);
                const bottomY = height - paddingY;
                const min = Math.min(...config.data);
                const maxValue = Math.max(...config.data);
                const range = maxValue - min || 1;
                const points = config.data.map((item, index) => {
                    const x = paddingX + ((innerWidth / (config.data.length - 1)) * index);
                    const y = paddingY + innerHeight - (((item - min) / range) * innerHeight);
                    return {
                        x,
                        y,
                        value: item
                    };
                });
                const paths = buildPath(points, bottomY);
                const fillId = 'stat-chart-fill';
                const gridLines = [0.25, 0.5, 0.75].map((fraction, index) => {
                    const y = paddingY + (innerHeight * fraction);
                    return `<line x1="28" y1="${y}" x2="672" y2="${y}" stroke="rgba(15,23,42,0.08)" stroke-width="1" stroke-dasharray="4 8" />`;
                }).join('');
                const pointNodes = points.map((point) => `
                    <g data-stat-point data-value="${point.value}" data-x="${point.x.toFixed(1)}" data-y="${point.y.toFixed(1)}">
                        <circle cx="${point.x.toFixed(1)}" cy="${point.y.toFixed(1)}" r="14" fill="transparent" pointer-events="all"></circle>
                        <circle cx="${point.x.toFixed(1)}" cy="${point.y.toFixed(1)}" r="7" fill="${config.accent}" fill-opacity="0.18"></circle>
                        <circle cx="${point.x.toFixed(1)}" cy="${point.y.toFixed(1)}" r="4" fill="#fff"></circle>
                        <circle cx="${point.x.toFixed(1)}" cy="${point.y.toFixed(1)}" r="2.2" fill="${config.accent}"></circle>
                    </g>
                `).join('');
                const labelNodes = points.map((point) => {
                    const label = formatNumber(point.value);
                    const labelWidth = Math.max(34, (label.length * 7.5) + 16);
                    const labelX = Math.max(labelWidth / 2 + 8, Math.min(width - (labelWidth / 2) - 8, point.x));
                    const labelY = Math.max(18, point.y - 18);
                    return `
                        <g aria-hidden="true" pointer-events="none">
                            <rect x="${(labelX - (labelWidth / 2)).toFixed(1)}" y="${(labelY - 14).toFixed(1)}" rx="10" ry="10" width="${labelWidth.toFixed(1)}" height="20" fill="rgba(255,255,255,0.82)" stroke="rgba(255,255,255,0.42)" />
                            <text x="${labelX.toFixed(1)}" y="${labelY.toFixed(1)}" class="stat-chart-point-label" text-anchor="middle">${label}</text>
                        </g>
                    `;
                }).join('');

                chart.innerHTML = `
                    <defs>
                        <linearGradient id="${fillId}" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="${config.accent}" stop-opacity="0.42" />
                            <stop offset="100%" stop-color="${config.accent}" stop-opacity="0.02" />
                        </linearGradient>
                    </defs>
                    ${gridLines}
                    <path data-stat-area d="${paths.area}" fill="url(#${fillId})"></path>
                    <path data-stat-line d="${paths.line}" fill="none" stroke="${config.accent}" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"></path>
                    ${labelNodes}
                    ${pointNodes}
                `;

                const linePath = chart.querySelector('[data-stat-line]');
                const areaPath = chart.querySelector('[data-stat-area]');
                const pointGroups = Array.from(chart.querySelectorAll('[data-stat-point]'));
                const hideTooltip = () => {
                    tooltip.classList.add('hidden');
                };

                const showTooltipForPoint = (group) => {
                    const shellRect = chartShell.getBoundingClientRect();
                    const chartRect = chart.getBoundingClientRect();
                    const pointX = Number(group.dataset.x || 0);
                    const pointY = Number(group.dataset.y || 0);
                    const pointValue = Number(group.dataset.value || 0);
                    const x = (chartRect.left - shellRect.left) + (pointX * (chartRect.width / 700));
                    const y = (chartRect.top - shellRect.top) + (pointY * (chartRect.height / 240));
                    const tooltipLabel = `${formatNumber(pointValue)} ${config.unit}`.trim();

                    tooltipValue.textContent = tooltipLabel;
                    tooltip.style.left = `${Math.max(18, Math.min(shellRect.width - 18, x))}px`;
                    tooltip.style.top = `${Math.max(18, y - 12)}px`;
                    tooltip.classList.remove('hidden');
                };

                pointGroups.forEach((group) => {
                    group.addEventListener('mouseenter', () => showTooltipForPoint(group));
                    group.addEventListener('mousemove', () => showTooltipForPoint(group));
                    group.addEventListener('mouseleave', hideTooltip);
                });

                chart.onmouseleave = hideTooltip;

                if (animate && linePath && areaPath) {
                    const lineLength = linePath.getTotalLength();
                    linePath.style.strokeDasharray = `${lineLength}`;
                    linePath.style.strokeDashoffset = `${lineLength}`;
                    linePath.style.transition = 'none';
                    areaPath.style.opacity = '0';
                    areaPath.style.transition = 'opacity 0.35s ease';
                    pointGroups.forEach((group) => {
                        group.style.opacity = '0';
                        group.style.transformOrigin = 'center';
                        group.style.transform = 'translateY(6px) scale(0.96)';
                        group.style.transition = 'opacity 0.25s ease, transform 0.35s ease';
                    });

                    requestAnimationFrame(() => {
                        linePath.style.transition = 'stroke-dashoffset 1.1s cubic-bezier(0.22, 1, 0.36, 1)';
                        linePath.style.strokeDashoffset = '0';
                        areaPath.style.opacity = '1';
                        pointGroups.forEach((group, index) => {
                            window.setTimeout(() => {
                                group.style.opacity = '1';
                                group.style.transform = 'translateY(0) scale(1)';
                            }, 140 + (index * 90));
                        });
                    });
                }

                labels.innerHTML = dayLabels.map((label) => `<span>${label}</span>`).join('');
                kicker.textContent = config.title;
                value.textContent = formatNumber(config.data[config.data.length - 1]);
                unit.textContent = config.unit;
                title.textContent = config.headline;
                desc.textContent = config.desc;
                avg.textContent = formatNumber(config.data.reduce((sum, item) => sum + item, 0) / config.data.length);
                max.textContent = formatNumber(Math.max(...config.data));
            };

            let activeTabKey = 'food';

            const activateTab = (tabKey, animate = false) => {
                activeTabKey = tabKey;
                tabs.forEach((tab) => {
                    const isActive = tab.dataset.statTab === tabKey;
                    tab.classList.toggle('is-active', isActive);
                    tab.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                });
                if (tooltip) {
                    tooltip.classList.add('hidden');
                }
                renderChart(seriesByYear[activeYear][tabKey], animate);
            };

            tabs.forEach((tab) => {
                tab.addEventListener('click', () => activateTab(tab.dataset.statTab, true));
            });
            renderChart(seriesByYear[activeYear][activeTabKey], true);
        });
    </script>

</body>

</html>
