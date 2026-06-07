<section class="space-y-6">
  <div class="space-y-2">
    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-brand-secondaryDark/55">Donasi Warga</p>
    <h2 class="font-display text-3xl font-semibold text-brand-forest">Riwayat Donasi</h2>
    <p class="text-sm text-brand-secondaryDark/70">Form donasi dilakukan oleh admin. Di halaman ini Anda dapat melihat points dan riwayat donasi Anda.</p>
  </div>

  <div class="grid lg:grid-cols-3 gap-6 items-start">
    <div class="lg:col-span-2 bg-white rounded-2xl border border-brand-secondaryLight/70 shadow-sm p-6 md:p-7">
      <div class="flex items-center justify-between gap-3 mb-4">
        <h3 class="font-semibold text-brand-forest">List History Donasi</h3>
        <div class="flex items-center gap-2">
          <button type="button" data-donation-prev class="w-9 h-9 rounded-lg border border-brand-secondaryLight/70 text-brand-secondaryDark hover:bg-brand-secondaryLight/40 transition-colors" aria-label="Riwayat sebelumnya">‹</button>
          <button type="button" data-donation-next class="w-9 h-9 rounded-lg border border-brand-secondaryLight/70 text-brand-secondaryDark hover:bg-brand-secondaryLight/40 transition-colors" aria-label="Riwayat berikutnya">›</button>
        </div>
      </div>

      <div data-donation-slider class="overflow-x-auto scroll-smooth snap-x snap-mandatory">
        <div class="flex gap-4 min-w-max pr-2">
          <?php if (empty($donationHistory)): ?>
            <div class="min-w-[280px] sm:min-w-[320px] snap-start rounded-2xl border border-dashed border-brand-secondaryLight/70 bg-brand-bg p-5 text-sm text-brand-secondaryDark/60">
              Belum ada riwayat donasi.
            </div>
          <?php else: ?>
            <?php foreach ($donationHistory as $donation): ?>
              <article class="min-w-[280px] sm:min-w-[320px] snap-start rounded-2xl border border-brand-secondaryLight/70 bg-brand-bg p-5 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                  <div>
                    <p class="text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/50">Tanggal</p>
                    <p class="mt-1 text-sm font-semibold text-brand-secondaryDark"><?= htmlspecialchars(date('d M Y H:i', strtotime((string) $donation['created_at']))) ?></p>
                  </div>
                  <span class="inline-flex rounded-lg bg-brand-secondaryLight/70 text-brand-secondaryDark px-2.5 py-1 text-xs font-semibold">+<?= (int) $donation['points_awarded'] ?> pts</span>
                </div>
                <div class="mt-4 grid gap-3">
                  <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-brand-secondaryDark/45">Jenis Donasi</p>
                    <p class="mt-1 font-medium text-brand-secondaryDark"><?= htmlspecialchars((string) $donation['donation_type']) ?></p>
                  </div>
                  <div>
                    <p class="text-[11px] font-semibold uppercase tracking-wider text-brand-secondaryDark/45">Barang</p>
                    <p class="mt-1 text-brand-secondaryDark/80"><?= htmlspecialchars((string) $donation['item_name']) ?></p>
                  </div>
                  <div class="flex items-center justify-between gap-3">
                    <div>
                      <p class="text-[11px] font-semibold uppercase tracking-wider text-brand-secondaryDark/45">Jumlah</p>
                      <p class="mt-1 text-brand-secondaryDark/80"><?= (int) $donation['quantity'] . ' ' . htmlspecialchars((string) $donation['unit']) ?></p>
                    </div>
                    <div class="text-right">
                      <p class="text-[11px] font-semibold uppercase tracking-wider text-brand-secondaryDark/45">Points</p>
                      <p class="mt-1 font-semibold text-brand-heading"><?= (int) $donation['points_awarded'] ?></p>
                    </div>
                  </div>
                </div>
              </article>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <div class="bg-white rounded-2xl border border-brand-secondaryLight/70 shadow-sm p-5">
      <p class="text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1">Telkomsel Points Anda</p>
      <p class="text-3xl font-display font-semibold text-brand-heading"><?= (int) ($userPoints ?? 0) ?></p>
      <p class="text-sm text-brand-secondaryDark/60 mt-1">Points berasal dari donasi, pengambilan barang, dan aktivitas volunteer.</p>
    </div>
  </div>

  <?php if (!empty($message)): ?>
    <p class="rounded-xl bg-brand-secondaryLight/70 border border-brand-secondaryDark/25 px-4 py-3 text-sm text-brand-secondaryDark"><?= htmlspecialchars((string) $message) ?></p>
  <?php endif; ?>
</section>

<script>
(() => {
  const slider = document.querySelector('[data-donation-slider]');
  const prev = document.querySelector('[data-donation-prev]');
  const next = document.querySelector('[data-donation-next]');
  if (!slider || !prev || !next) return;

  const getStep = () => {
    const card = slider.querySelector('article, .min-w-\\[280px\\]');
    if (!card) return 320;
    const styles = window.getComputedStyle(slider.querySelector('.flex') || slider);
    const gap = parseFloat(styles.columnGap || styles.gap || '0') || 0;
    return card.getBoundingClientRect().width + gap;
  };

  prev.addEventListener('click', () => {
    slider.scrollBy({ left: -getStep(), behavior: 'smooth' });
  });

  next.addEventListener('click', () => {
    slider.scrollBy({ left: getStep(), behavior: 'smooth' });
  });
})();
</script>
