<section class="admin-surface p-6 space-y-5">
  <div>
    <h2 class="admin-section-title">Master Lokasi Volunteer</h2>
    <p class="admin-subtitle">Kelola lokasi volunteer untuk dipilih user pada form pendaftaran volunteer.</p>
  </div>

  <div class="rounded-2xl border border-brand-secondaryLight/70 bg-white p-4 md:p-5">
    <h3 class="text-sm font-semibold text-brand-secondaryDark mb-3">Tambah Lokasi Baru</h3>
    <form method="POST" action="/admin/lokasi-volunteer/create" class="grid md:grid-cols-2 gap-3">
      <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1">Nama Tempat</label>
        <input type="text" name="location_name" class="admin-input" placeholder="Contoh: Gudang Titipangan Jakarta Timur" required>
      </div>
      <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1">Kota</label>
        <input type="text" name="city" class="admin-input" placeholder="Contoh: Jakarta Timur">
      </div>
      <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1">Provinsi</label>
        <input type="text" name="province" class="admin-input" placeholder="Contoh: DKI Jakarta">
      </div>
      <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1">Link Google Maps</label>
        <input type="url" name="google_maps_url" class="admin-input" placeholder="https://maps.google.com/...">
      </div>
      <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1">Latitude</label>
        <input type="number" step="0.0000001" name="latitude" class="admin-input" placeholder="-6.2000000">
      </div>
      <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1">Longitude</label>
        <input type="number" step="0.0000001" name="longitude" class="admin-input" placeholder="106.8166667">
      </div>
      <div class="md:col-span-2">
        <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1">Alamat Detail</label>
        <textarea name="address" rows="2" class="admin-input" placeholder="Alamat lengkap lokasi"></textarea>
      </div>
      <label class="inline-flex items-center gap-2 text-sm text-brand-secondaryDark">
        <input type="checkbox" name="is_active" value="1" checked class="rounded border-brand-secondaryLight">
        <span>Lokasi aktif (muncul di form user)</span>
      </label>
      <div class="md:col-span-2">
        <button type="submit" class="admin-btn-primary">Simpan Lokasi</button>
      </div>
    </form>
  </div>

  <div class="grid gap-4 lg:grid-cols-2">
    <?php if (empty($volunteerLocations)): ?>
      <div class="rounded-2xl border border-dashed border-brand-secondaryLight bg-white p-6 text-sm text-brand-secondaryDark/70">
        Belum ada data lokasi volunteer.
      </div>
    <?php else: ?>
      <?php foreach ($volunteerLocations as $location): ?>
        <article class="rounded-2xl border border-brand-secondaryLight/70 bg-white p-4 md:p-5">
          <form method="POST" action="/admin/lokasi-volunteer/update" class="grid gap-3">
            <input type="hidden" name="id" value="<?= (int) $location['id'] ?>">
            <div class="grid md:grid-cols-2 gap-3">
              <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1">Nama Tempat</label>
                <input type="text" name="location_name" class="admin-input" value="<?= htmlspecialchars((string) $location['location_name']) ?>" required>
              </div>
              <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1">Kota</label>
                <input type="text" name="city" class="admin-input" value="<?= htmlspecialchars((string) ($location['city'] ?? '')) ?>">
              </div>
              <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1">Provinsi</label>
                <input type="text" name="province" class="admin-input" value="<?= htmlspecialchars((string) ($location['province'] ?? '')) ?>">
              </div>
              <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1">Link Google Maps</label>
                <input type="url" name="google_maps_url" class="admin-input" value="<?= htmlspecialchars((string) ($location['google_maps_url'] ?? '')) ?>">
              </div>
              <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1">Latitude</label>
                <input type="number" step="0.0000001" name="latitude" class="admin-input" value="<?= htmlspecialchars((string) ($location['latitude'] ?? '')) ?>">
              </div>
              <div>
                <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1">Longitude</label>
                <input type="number" step="0.0000001" name="longitude" class="admin-input" value="<?= htmlspecialchars((string) ($location['longitude'] ?? '')) ?>">
              </div>
            </div>

            <div>
              <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1">Alamat Detail</label>
              <textarea name="address" rows="2" class="admin-input"><?= htmlspecialchars((string) ($location['address'] ?? '')) ?></textarea>
            </div>

            <div class="flex flex-wrap items-center justify-between gap-3">
              <label class="inline-flex items-center gap-2 text-sm text-brand-secondaryDark">
                <input type="checkbox" name="is_active" value="1" <?= ((int) ($location['is_active'] ?? 0) === 1) ? 'checked' : '' ?> class="rounded border-brand-secondaryLight">
                <span>Lokasi aktif</span>
              </label>
              <div class="flex items-center gap-2">
                <button type="submit" class="admin-btn-primary !px-4 !py-2">Update</button>
              </div>
            </div>
          </form>
          <form method="POST" action="/admin/lokasi-volunteer/delete" onsubmit="return confirm('Hapus lokasi ini?');" class="mt-2">
            <input type="hidden" name="id" value="<?= (int) $location['id'] ?>">
            <button type="submit" class="rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-100">Hapus</button>
          </form>
        </article>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</section>
