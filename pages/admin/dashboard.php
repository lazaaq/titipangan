<section class="space-y-6 fade-up">
  <div class="admin-surface p-6 md:p-7">
    <p class="text-xs font-semibold uppercase tracking-[0.18em] text-brand-secondaryDark/50 mb-2">Admin Overview</p>
    <h2 class="admin-section-title">Dashboard Admin Titipangan</h2>
    <p class="admin-subtitle mt-2 max-w-2xl">Pantau ringkasan operasional distribusi pangan harian: pengguna aktif, ketersediaan inventori, dan jumlah pengambilan dalam minggu berjalan.</p>
  </div>

  <div class="grid md:grid-cols-3 gap-4">
    <article class="admin-surface p-5">
      <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-brand-secondaryDark/50">Total User</p>
      <p class="mt-3 text-4xl font-display font-semibold text-brand-forest leading-none"><?= (int) $stats['users'] ?></p>
      <p class="mt-2 text-xs text-brand-secondaryDark/55">Pengguna terdaftar di sistem.</p>
    </article>
    <article class="admin-surface p-5">
      <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-brand-secondaryDark/50">Item Inventori</p>
      <p class="mt-3 text-4xl font-display font-semibold text-brand-forest leading-none"><?= (int) $stats['inventory'] ?></p>
      <p class="mt-2 text-xs text-brand-secondaryDark/55">Jumlah jenis barang di gudang.</p>
    </article>
    <article class="admin-surface p-5">
      <p class="text-[11px] font-semibold uppercase tracking-[0.14em] text-brand-secondaryDark/50">Klaim Minggu Ini</p>
      <p class="mt-3 text-4xl font-display font-semibold text-brand-forest leading-none"><?= (int) $stats['claims_week'] ?></p>
      <p class="mt-2 text-xs text-brand-secondaryDark/55">Pengambilan oleh warga minggu ini.</p>
    </article>
  </div>
</section>
