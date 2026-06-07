<section class="space-y-6">
  <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
    <h2 class="text-2xl sm:text-3xl font-bold text-brand-heading">Halaman Dashboard</h2>
    <div class="flex flex-col sm:flex-row sm:flex-wrap items-stretch sm:items-center gap-2">
      <?php if (!empty($isGuest)): ?>
        <a href="/user/login" class="bg-brand-btn text-white rounded-lg px-4 py-2.5 hover:bg-red-800 text-center w-full sm:w-auto">Ambil Makanan Minggu Ini</a>
      <?php else: ?>
        <form method="POST" action="/claim">
          <button
            class="rounded-lg px-4 py-2.5 text-white w-full sm:w-auto <?= !empty($alreadyClaimed) ? 'bg-gray-400 cursor-not-allowed' : 'bg-brand-btn hover:bg-red-800' ?>"
            <?= !empty($alreadyClaimed) ? 'disabled' : '' ?>
          >
            <?= !empty($alreadyClaimed) ? 'Sudah Ambil Minggu Ini' : 'Ambil Makanan Minggu Ini' ?>
          </button>
        </form>
      <?php endif; ?>
      <a href="/katalog-publik" class="bg-brand-secondaryDark text-white rounded-lg px-4 py-2.5 hover:bg-green-900 text-center w-full sm:w-auto">Lihat Katalog</a>
    </div>
  </div>

  <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
    <article class="bg-white rounded-xl shadow p-5 border border-brand-secondaryLight/60">
      <p class="text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55">Total Bantuan Diterima</p>
      <p class="mt-2 text-3xl font-display font-semibold text-brand-heading"><?= (int) ($dashboardStats['total_received'] ?? 0) ?></p>
      <p class="mt-1 text-xs text-brand-secondaryDark/60">Akumulasi seluruh pengambilan bantuan.</p>
    </article>
    <article class="bg-white rounded-xl shadow p-5 border border-brand-secondaryLight/60">
      <p class="text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55">Total Pengambilan Bulan Ini</p>
      <p class="mt-2 text-3xl font-display font-semibold text-brand-heading"><?= (int) ($dashboardStats['claims_this_month'] ?? 0) ?></p>
      <p class="mt-1 text-xs text-brand-secondaryDark/60">Jumlah pengambilan pada bulan berjalan.</p>
    </article>
    <article class="bg-white rounded-xl shadow p-5 border border-brand-secondaryLight/60">
      <p class="text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55">Telkomsel Points</p>
      <p class="mt-2 text-3xl font-display font-semibold text-brand-heading"><?= (int) ($dashboardStats['telkomsel_points'] ?? 0) ?></p>
      <p class="mt-1 text-xs text-brand-secondaryDark/60">Akumulasi points dari donasi dan aktivitas volunteer.</p>
    </article>
  </div>

  <div class="bg-white rounded-xl shadow p-6">
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-2 mb-4">
      <div>
        <h3 class="text-lg font-semibold text-brand-heading">3 Pos Pengambilan Terdekat</h3>
        <p id="nearest-info" class="text-xs text-brand-secondaryDark/60">Menampilkan lokasi terdekat berdasarkan data koordinat pos.</p>
      </div>
    </div>

    <?php
      $initialLocations = array_slice($pickupLocations ?? [], 0, 3);
    ?>
    <div id="pickup-location-list" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
      <?php if (empty($initialLocations)): ?>
        <div class="lg:col-span-3 rounded-xl border border-dashed border-brand-secondaryDark/20 p-5 text-sm text-brand-secondaryDark/65">
          Belum ada data lokasi pos dengan koordinat.
        </div>
      <?php else: ?>
        <?php foreach ($initialLocations as $location): ?>
          <article class="rounded-xl border border-brand-secondaryLight/70 overflow-hidden bg-white">
            <iframe
              class="w-full h-48 sm:h-44 border-0"
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
              src="https://maps.google.com/maps?q=<?= urlencode((string) $location['latitude'] . ',' . (string) $location['longitude']) ?>&z=15&output=embed"></iframe>
            <div class="p-4 space-y-1.5">
              <p class="font-semibold text-brand-forest"><?= htmlspecialchars((string) $location['location_name']) ?></p>
              <p class="text-xs text-brand-secondaryDark/70">
                <?= htmlspecialchars(trim(((string) ($location['city'] ?? '')) . ' ' . ((string) ($location['province'] ?? '')))) ?>
              </p>
              <?php if (!empty($location['address'])): ?>
                <p class="text-xs text-brand-secondaryDark/60"><?= htmlspecialchars((string) $location['address']) ?></p>
              <?php endif; ?>
              <?php if (!empty($location['google_maps_url'])): ?>
                <a href="<?= htmlspecialchars((string) $location['google_maps_url']) ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-xs font-semibold text-brand-secondaryDark hover:text-brand-heading">Buka di Google Maps</a>
              <?php endif; ?>
            </div>
          </article>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
  </div>

  <?php if (empty($isGuest)): ?>
    <div class="bg-white rounded-xl shadow p-6">
      <h2 class="text-lg font-semibold text-brand-heading mb-4">Riwayat Pengambilan Makanan</h2>
      <div class="overflow-x-auto rounded-xl border border-brand-secondaryLight/50">
        <table class="w-full text-sm border-collapse">
          <thead>
            <tr class="bg-brand-secondaryLight text-left">
              <th class="p-2">No</th>
              <th class="p-2">Tanggal Pengambilan</th>
              <th class="p-2">Minggu Ke</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!empty($claimHistory)): ?>
              <?php foreach ($claimHistory as $index => $history): ?>
                <tr class="border-b">
                  <td class="p-2"><?= $index + 1 ?></td>
                  <td class="p-2"><?= htmlspecialchars(date('d M Y H:i', strtotime($history['claimed_at']))) ?></td>
                  <td class="p-2"><?= htmlspecialchars($history['week_key']) ?></td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td class="p-3 text-brand-secondaryDark" colspan="3">Belum ada riwayat pengambilan.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  <?php endif; ?>

</section>

<script>
(() => {
  const allLocations = <?= json_encode($pickupLocations ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>;
  const list = document.getElementById('pickup-location-list');
  const info = document.getElementById('nearest-info');
  if (!list || !Array.isArray(allLocations) || allLocations.length === 0) return;

  const esc = (value) => String(value ?? '').replace(/[&<>"']/g, (ch) => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[ch]));
  const distanceKm = (lat1, lon1, lat2, lon2) => {
    const toRad = (deg) => deg * (Math.PI / 180);
    const dLat = toRad(lat2 - lat1);
    const dLon = toRad(lon2 - lon1);
    const a = Math.sin(dLat / 2) ** 2 + Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) * Math.sin(dLon / 2) ** 2;
    return 6371 * (2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a)));
  };

  const render = (locations) => {
    list.innerHTML = locations.map((loc) => {
      const lat = Number(loc.latitude || 0);
      const lng = Number(loc.longitude || 0);
      const cityProvince = `${loc.city || ''} ${loc.province || ''}`.trim();
      const distanceLabel = typeof loc._distance === 'number' ? `<p class="text-xs font-semibold text-brand-heading">${loc._distance.toFixed(2)} km dari posisi Anda</p>` : '';
      const mapsUrl = loc.google_maps_url ? `<a href="${esc(loc.google_maps_url)}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-xs font-semibold text-brand-secondaryDark hover:text-brand-heading">Buka di Google Maps</a>` : '';
      return `
        <article class="rounded-xl border border-brand-secondaryLight/70 overflow-hidden">
          <iframe class="w-full h-44 border-0" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://maps.google.com/maps?q=${encodeURIComponent(`${lat},${lng}`)}&z=15&output=embed"></iframe>
          <div class="p-4 space-y-1.5">
            <p class="font-semibold text-brand-forest">${esc(loc.location_name)}</p>
            <p class="text-xs text-brand-secondaryDark/70">${esc(cityProvince)}</p>
            ${loc.address ? `<p class="text-xs text-brand-secondaryDark/60">${esc(loc.address)}</p>` : ''}
            ${distanceLabel}
            ${mapsUrl}
          </div>
        </article>
      `;
    }).join('');
  };

  if (!navigator.geolocation) {
    info.textContent = 'Browser tidak mendukung geolokasi. Menampilkan 3 lokasi terbaru.';
    return;
  }

  navigator.geolocation.getCurrentPosition(
    (position) => {
      const { latitude, longitude } = position.coords;
      const nearest = [...allLocations]
        .map((loc) => ({ ...loc, _distance: distanceKm(latitude, longitude, Number(loc.latitude || 0), Number(loc.longitude || 0)) }))
        .sort((a, b) => (a._distance || 0) - (b._distance || 0))
        .slice(0, 3);
      render(nearest);
      info.textContent = 'Lokasi diurutkan berdasarkan posisi Anda saat ini.';
    },
    () => {
      info.textContent = 'Izin lokasi tidak diberikan. Menampilkan 3 lokasi terbaru.';
    },
    { enableHighAccuracy: true, timeout: 7000, maximumAge: 120000 }
  );
})();
</script>

<?php if (!empty($pickupQr['code'])): ?>
  <div id="pickup-modal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/45" onclick="document.getElementById('pickup-modal').classList.add('hidden')"></div>
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl border border-brand-secondaryLight/70 p-6">
      <h3 class="text-xl font-semibold text-brand-heading mb-1">Kode Pengambilan</h3>
      <p class="text-sm text-brand-secondaryDark/70 mb-4">Tunjukkan QR atau kode ini ke admin untuk redeem. Kode berubah setiap hari.</p>
      <div class="flex flex-col items-center gap-3">
        <img
          src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode((string) $pickupQr['code']) ?>"
          alt="QR Kode Pengambilan"
          class="w-44 h-44 rounded-lg border border-brand-secondaryLight/70 bg-white p-2">
        <p class="font-mono text-3xl tracking-[0.22em] text-brand-forest font-semibold"><?= htmlspecialchars((string) $pickupQr['code']) ?></p>
        <p class="text-xs text-brand-secondaryDark/55">Tanggal: <?= htmlspecialchars((string) ($pickupQr['date'] ?? '')) ?></p>
      </div>
      <div class="mt-5 flex justify-end">
        <button type="button" class="rounded-lg bg-brand-secondaryDark text-white px-4 py-2 hover:bg-green-900" onclick="document.getElementById('pickup-modal').classList.add('hidden')">Tutup</button>
      </div>
    </div>
  </div>
<?php endif; ?>
