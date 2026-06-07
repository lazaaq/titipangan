<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($title ?? 'Titipangan') ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;1,9..40,300&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['DM Sans', 'sans-serif'],
            display: ['Playfair Display', 'serif'],
          },
          colors: {
            brand: {
              bg: '#F5F3E6',
              heading: '#007E34',
              btn: '#C61313',
              secondaryDark: '#005B21',
              secondaryLight: '#CFFFA4',
              forest: '#01361A',
              sage: '#E8F5E9',
              cream: '#FAFAF5',
            }
          }
        }
      }
    }
  </script>
  <style>
    * { -webkit-font-smoothing: antialiased; }
    .nav-link {
      display:flex; align-items:center; gap:10px; padding:9px 14px; border-radius:10px;
      font-size:13.5px; font-weight:500; letter-spacing:-0.01em;
      transition:all 0.18s ease; color:rgba(255,255,255,0.76); position:relative;
    }
    .nav-link:hover { background: rgba(255,255,255,0.12); color:#fff; }
    .nav-link.active { background: rgba(255,255,255,0.20); color:#fff; box-shadow: inset 0 0 0 1px rgba(255,255,255,0.24); }
    .admin-theme .nav-link { color: rgba(0,91,33,0.72); }
    .admin-theme .nav-link:hover { background: rgba(207,255,164,0.45); color:#005B21; }
    .admin-theme .nav-link.active { background: rgba(207,255,164,0.65); color:#005B21; box-shadow: inset 0 0 0 1px rgba(1,54,26,0.12); }
    .nav-icon { width:28px; height:28px; display:flex; align-items:center; justify-content:center; border-radius:7px; font-size:14px; flex-shrink:0; background:rgba(255,255,255,0.1); }
    .admin-theme .nav-icon { background: rgba(1,129,54,0.08); }
    .stat-card { background:#fff; border-radius:16px; padding:22px 24px; border:1px solid rgba(207,255,164,0.6); position:relative; overflow:hidden; transition:transform 0.2s ease, box-shadow 0.2s ease; }
    .stat-card:hover { transform:translateY(-2px); box-shadow:0 8px 32px rgba(0,91,33,0.08); }
    .stat-card::before { content:''; position:absolute; top:0; right:0; width:70px; height:70px; background:radial-gradient(circle at top right, rgba(207,255,164,0.5), transparent 70%); }
    @keyframes fadeUp { from { opacity:0; transform:translateY(10px); } to { opacity:1; transform:translateY(0); } }
    .fade-up { animation: fadeUp 0.38s ease forwards; }
    .d1 { animation-delay: 0.04s; opacity:0; } .d2 { animation-delay: 0.08s; opacity:0; } .d3 { animation-delay: 0.12s; opacity:0; }
    tr.hover-row:hover td { background: rgba(207,255,164,0.18); }
    .admin-theme {
      background: #f3f6ef !important;
      color: #143d24;
    }
    .admin-theme .admin-surface {
      background: #ffffff;
      border: 1px solid rgba(0, 91, 33, 0.1);
      border-radius: 16px;
      box-shadow: 0 8px 24px rgba(1, 54, 26, 0.05);
    }
    .admin-theme .admin-section-title {
      font-size: 22px;
      line-height: 1.2;
      color: #01361A;
      font-weight: 700;
      letter-spacing: -0.01em;
    }
    .admin-theme .admin-subtitle {
      color: rgba(0, 91, 33, 0.7);
      font-size: 13px;
      line-height: 1.5;
    }
    .admin-theme .admin-input {
      width: 100%;
      border: 1px solid rgba(0, 91, 33, 0.2);
      border-radius: 12px;
      padding: 10px 12px;
      background: #fff;
      font-size: 14px;
      color: #143d24;
      outline: none;
      transition: border-color 0.15s ease, box-shadow 0.15s ease;
    }
    .admin-theme .admin-input:focus {
      border-color: rgba(0, 91, 33, 0.5);
      box-shadow: 0 0 0 3px rgba(0, 91, 33, 0.1);
    }
    .admin-theme .admin-btn-primary {
      background: #005B21;
      color: #fff;
      border-radius: 12px;
      padding: 10px 14px;
      font-size: 13px;
      font-weight: 600;
      transition: background 0.15s ease;
    }
    .admin-theme .admin-btn-primary:hover { background: #01361A; }
    .admin-theme .admin-btn-danger {
      background: transparent;
      color: #C61313;
      font-weight: 600;
      transition: color 0.15s ease;
    }
    .admin-theme .admin-btn-danger:hover { color: #9f1010; }
    .admin-theme .admin-table-wrap {
      overflow-x: auto;
      border-radius: 14px;
      border: 1px solid rgba(0, 91, 33, 0.12);
    }
    .admin-theme .admin-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      font-size: 13px;
      min-width: 820px;
      background: #fff;
    }
    .admin-theme .admin-table thead th {
      background: #ecf9d8;
      color: #005B21;
      text-align: left;
      font-size: 11px;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      font-weight: 700;
      padding: 12px 14px;
      border-bottom: 1px solid rgba(0, 91, 33, 0.12);
    }
    .admin-theme .admin-table tbody td {
      padding: 12px 14px;
      border-bottom: 1px solid rgba(0, 91, 33, 0.08);
      color: #19462b;
      vertical-align: top;
    }
    .admin-theme .admin-table tbody tr:hover td {
      background: #f8fdf1;
    }
  </style>
</head>
<?php $role = $role ?? 'user'; ?>
<?php $isUserTheme = $role !== 'admin'; ?>
<body class="min-h-screen flex flex-col md:flex-row md:h-screen md:overflow-hidden font-sans <?= !$isUserTheme ? 'admin-theme' : '' ?>" style="background:#F5F3E6;">
  <?php require __DIR__ . '/../components/sidebar.php'; ?>
  <div class="flex-1 flex flex-col min-h-screen md:h-screen md:overflow-hidden min-w-0">
    <div class="relative z-30">
      <?php require __DIR__ . '/../components/header.php'; ?>
      <?php require __DIR__ . '/../components/mobile_nav.php'; ?>
    </div>
    <main class="flex-1 relative overflow-y-auto pb-6 md:pb-0">
      <?php if ($isUserTheme): ?>
        <div class="absolute inset-0 pointer-events-none overflow-hidden">
          <img src="/assets/illustrations/bg.avif" alt="" class="h-full w-full object-cover opacity-[0.09]">
        </div>
      <?php endif; ?>
      <div class="relative z-10 p-4 sm:p-5 lg:p-8 max-w-[1600px] mx-auto w-full">
        <?php require $contentView; ?>
      </div>
    </main>
    <?php require __DIR__ . '/../components/footer.php'; ?>
  </div>
  <script>
    (() => {
      const toggle = document.querySelector('[data-mobile-nav-toggle]');
      const panel = document.querySelector('[data-mobile-nav-panel]');
      if (!toggle || !panel) return;

      const setOpen = (open) => {
        panel.classList.toggle('hidden', !open);
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.setAttribute('aria-label', open ? 'Tutup menu navigasi' : 'Buka menu navigasi');
      };

      toggle.addEventListener('click', () => {
        setOpen(panel.classList.contains('hidden'));
      });

      document.addEventListener('click', (event) => {
        if (window.innerWidth >= 768) return;
        if (panel.classList.contains('hidden')) return;
        if (panel.contains(event.target) || toggle.contains(event.target)) return;
        setOpen(false);
      });

      window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) setOpen(false);
      });
    })();
  </script>
</body>
</html>
