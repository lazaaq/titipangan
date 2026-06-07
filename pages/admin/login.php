<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin - Titipangan</title>
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
      border: 1px solid rgba(0, 91, 33, 0.2);
      background: rgba(255, 255, 255, 0.94);
      padding: 12px 16px;
      outline: none;
      font-size: 14px;
    }
    .input-field:focus {
      border-color: rgba(0, 91, 33, 0.5);
      box-shadow: 0 0 0 3px rgba(0, 91, 33, 0.08);
    }
  </style>
</head>
<body class="min-h-screen font-sans text-brand-secondaryDark bg-brand-bg">
  <main class="min-h-screen grid md:grid-cols-2">
    <section class="relative min-h-screen flex items-center justify-center bg-[#F8F7EF] overflow-hidden">
      <div class="absolute inset-0 opacity-[0.08] pointer-events-none" style="background-image:url('/assets/illustrations/bg.avif');background-size:cover;background-position:center;"></div>
      <div class="relative z-10 w-full max-w-md rounded-2xl mx-4 md:mx-0 border border-brand-secondaryLight/70 bg-white shadow-xl p-8 space-y-6">
        <div class="space-y-2">
          <p class="text-[10px] font-semibold tracking-[0.2em] uppercase text-brand-secondaryDark/45">Admin Access</p>
          <h2 class="font-display text-3xl font-semibold text-brand-forest">Login Admin</h2>
          <p class="text-sm text-brand-secondaryDark/60 leading-relaxed">Masuk ke panel admin untuk memantau inventori, donasi, klaim, dan volunteer.</p>
        </div>

        <form method="POST" action="/admin/login" class="space-y-4">
          <div class="space-y-1.5">
            <label class="text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/50">Username</label>
            <input type="text" name="username" placeholder="Masukkan username" class="input-field" required>
          </div>

          <div class="space-y-1.5">
            <label class="text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/50">Password</label>
            <input type="password" name="password" placeholder="••••••••" class="input-field" required>
          </div>

          <button type="submit" class="w-full rounded-xl bg-brand-btn text-white text-sm font-semibold py-3 hover:bg-red-800 active:scale-[0.99] transition-all duration-150">Masuk Dashboard Admin</button>
        </form>

        <?php if (!empty($error)): ?>
          <p class="rounded-xl bg-red-50 border border-red-200 px-4 py-3 text-sm text-brand-btn"><?= htmlspecialchars((string) $error) ?></p>
        <?php endif; ?>

        <div class="pt-3 border-t border-brand-secondaryDark/15">
          <a href="/katalog-publik" class="w-full inline-flex items-center justify-center rounded-xl bg-brand-secondaryDark text-white font-semibold px-4 py-3 hover:bg-green-900 transition-colors">Lihat Katalog Publik</a>
        </div>
      </div>
    </section>

    <section class="relative min-h-screen text-white overflow-hidden" style="background:linear-gradient(165deg, #012D16 0%, #00491A 55%, #006D2D 100%);">
      <div class="absolute inset-0 opacity-25" style="background-image:url('/assets/illustrations/bg.avif');background-size:cover;background-position:center;"></div>
      <div class="relative z-10 h-full p-10 lg:p-14 flex flex-col justify-between">
        <div class="flex items-center gap-2">
          <span class="w-7 h-7 rounded-lg bg-white/15 flex items-center justify-center text-sm"><?= appIcon('recycle', 'w-4 h-4') ?></span>
          <p class="text-xs font-semibold tracking-[0.22em] uppercase text-white/70">Titipangan Admin</p>
        </div>

        <div class="space-y-4 max-w-lg">
          <p class="text-xs font-semibold tracking-[0.2em] uppercase text-white/65">Control Center</p>
          <h3 class="font-display text-4xl lg:text-5xl leading-tight font-semibold">Panel operasional distribusi</h3>
          <p class="text-base leading-relaxed text-white/80">Kelola verifikasi volunteer, pencatatan pengambilan, inventori real-time, dan donasi warga dalam satu dashboard terintegrasi.</p>
        </div>

        <p class="text-xs text-white/55">Akurat, terstruktur, dan siap scale.</p>
      </div>
    </section>
  </main>
</body>
</html>
