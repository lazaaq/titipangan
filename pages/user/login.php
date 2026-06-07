<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login User - Titipangan</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['DM Sans', 'sans-serif'],
            display: ['Playfair Display', 'serif']
          },
          colors: {
            brand: {
              bg: '#F5F3E6',
              heading: '#007E34',
              btn: '#C61313',
              secondaryDark: '#005B21',
              secondaryLight: '#CFFFA4',
              forest: '#01361A'
            }
          }
        }
      }
    }
  </script>
  <style>
    html, body { min-height: 100%; }
    @media (min-width: 768px) { html, body { height: 100%; overflow: hidden; } }
    .input-field {
      width: 100%;
      border-radius: 10px;
      border: 1px solid rgba(1, 129, 54, 0.2);
      background: rgba(255, 255, 255, 0.9);
      padding: 12px 16px;
      outline: none;
      font-size: 14px;
    }
    .input-field:focus {
      border-color: rgba(1, 129, 54, 0.5);
      box-shadow: 0 0 0 3px rgba(1, 129, 54, 0.08);
    }
    .auth-panel-left, .auth-panel-right, .auth-form-card { will-change: transform, opacity; }
    .from-register .auth-panel-left { animation: slideInLeft .45s ease both; }
    .from-register .auth-panel-right { animation: slideInRight .45s ease both; }
    .from-register .auth-form-card { animation: popIn .4s ease .05s both; }
    @keyframes slideInLeft { from { opacity:.25; transform: translateX(-26px);} to { opacity:1; transform: translateX(0);} }
    @keyframes slideInRight { from { opacity:.25; transform: translateX(26px);} to { opacity:1; transform: translateX(0);} }
    @keyframes popIn { from { opacity:.2; transform: translateY(12px) scale(.98);} to { opacity:1; transform: translateY(0) scale(1);} }
  </style>
</head>
<body class="min-h-screen font-sans text-brand-secondaryDark bg-brand-bg">
  <main class="min-h-screen grid md:grid-cols-2">
    <section class="auth-panel-left relative min-h-screen flex items-center justify-center bg-brand-bg overflow-hidden">
      <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image:url('/assets/illustrations/bg.avif');background-size:cover;background-position:center;"></div>
      <div class="relative z-10 w-full max-w-md mx-4 md:mx-0">
        <a href="/" class="mb-4 inline-flex items-center gap-2 rounded-xl border border-brand-secondaryDark/20 bg-white/90 px-4 py-2.5 text-sm font-semibold text-brand-secondaryDark hover:bg-white transition-colors shadow-sm">
          <span aria-hidden="true">←</span>
          Kembali ke Halaman Utama Titipangan
        </a>
      <div class="auth-form-card w-full rounded-2xl border border-brand-secondaryLight/60 bg-white shadow-xl p-8 space-y-6">
        <div class="space-y-2">
          <p class="text-[10px] font-semibold tracking-[0.2em] uppercase text-brand-secondaryDark/45">FoodBank Access</p>
          <h2 class="font-display text-3xl font-semibold text-brand-forest">Login User</h2>
          <p class="text-sm text-brand-secondaryDark/60 leading-relaxed">Masukkan nomor handphone terdaftar untuk menerima OTP via WhatsApp.</p>
        </div>

        <form method="POST" action="/user/login/send-otp" class="space-y-4">
          <div class="space-y-1.5">
            <label class="text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/50">Nomor Handphone</label>
            <input type="text" name="phone" placeholder="081234567890" class="input-field" required>
          </div>
          <button type="submit" class="w-full rounded-xl bg-brand-btn text-white text-sm font-semibold py-3 hover:bg-red-800 active:scale-[0.99] transition-all duration-150">Kirim OTP</button>
        </form>

        <p class="text-sm text-brand-secondaryDark text-center">Belum punya akun? <a href="/user/register" data-auth-link="register" class="font-semibold text-brand-heading hover:underline">Daftar sekarang</a></p>

        <div class="pt-3 border-t border-brand-secondaryDark/15">
          <a href="/katalog-publik" class="w-full inline-flex items-center justify-center rounded-xl bg-brand-secondaryDark text-white font-semibold px-4 py-3 hover:bg-green-900 transition-colors">Lihat Katalog Publik</a>
        </div>

        <?php if (!empty($message)): ?>
          <p class="rounded-xl bg-brand-secondaryLight/80 border border-brand-secondaryDark/30 px-4 py-3 text-sm text-brand-secondaryDark"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>
      </div>
      </div>
    </section>

    <section class="auth-panel-right relative min-h-screen text-white overflow-hidden" style="background:linear-gradient(165deg, #01361A 0%, #005B21 55%, #007E34 100%);">
      <div class="absolute inset-0 opacity-20" style="background-image:url('/assets/illustrations/bg.avif');background-size:cover;background-position:center;"></div>
      <div class="relative z-10 h-full p-10 lg:p-14 flex flex-col justify-between">
        <div class="flex items-center gap-2">
          <span class="w-7 h-7 rounded-lg bg-white/15 flex items-center justify-center text-sm"><?= appIcon('recycle', 'w-4 h-4') ?></span>
          <p class="text-xs font-semibold tracking-[0.22em] uppercase text-white/70">Titipangan</p>
        </div>
        <div class="space-y-4 max-w-lg">
          <p class="text-xs font-semibold tracking-[0.2em] uppercase text-white/65">Sistem Distribusi Pangan</p>
          <h3 class="font-display text-4xl lg:text-5xl leading-tight font-semibold">Sistem distribusi pangan</h3>
          <p class="text-base leading-relaxed text-white/80">Platform kolaboratif untuk distribusi bantuan makanan yang lebih tepat sasaran, transparan, dan berkelanjutan.</p>
        </div>
        <p class="text-xs text-white/55">Aman, cepat, dan berkelanjutan.</p>
      </div>
    </section>
  </main>
  <script>
    document.querySelectorAll('[data-auth-link]').forEach((el) => {
      el.addEventListener('click', () => sessionStorage.setItem('authTransitionFrom', 'login'));
    });
    const from = sessionStorage.getItem('authTransitionFrom');
    if (from === 'register') document.body.classList.add('from-register');
    sessionStorage.removeItem('authTransitionFrom');
  </script>
</body>
</html>
