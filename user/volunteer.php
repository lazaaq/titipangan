<section class="space-y-6">
  <div class="space-y-2">
    <p class="text-xs font-semibold tracking-[0.2em] uppercase text-brand-secondaryDark/55">Kontribusi Sosial</p>
    <h2 class="font-display text-3xl font-semibold text-brand-forest">Form Volunteer</h2>
    <p class="text-sm text-brand-secondaryDark/70">Daftarkan aktivitas volunteering Anda untuk membantu penyaluran barang. Setiap aktivitas volunteer akan mendapatkan Telkomsel Points.</p>
  </div>

  <div class="grid lg:grid-cols-3 gap-6 items-start">
    <div class="lg:col-span-2 bg-white rounded-2xl border border-brand-secondaryLight/70 shadow-sm p-6 md:p-8">
      <form method="POST" action="/user/volunteer" enctype="multipart/form-data" class="grid md:grid-cols-2 gap-4">
      <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1.5">Jenis Aktivitas</label>
        <select name="activity_type" class="w-full rounded-xl border border-brand-secondaryLight px-3 py-2.5" required>
          <option value="">Pilih aktivitas volunteer</option>
          <option value="Frontdesk Layanan Donasi">Frontdesk Layanan Donasi</option>
          <option value="Penerimaan Donasi Barang">Penerimaan Donasi Barang</option>
          <option value="Sortir Inventori Barang">Sortir Inventori Barang</option>
        </select>
      </div>

      <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1.5">Tanggal Aktivitas</label>
        <input type="date" name="volunteer_date" class="w-full rounded-xl border border-brand-secondaryLight px-3 py-2.5" required>
      </div>

      <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1.5">Lokasi</label>
        <select name="location_id" class="w-full rounded-xl border border-brand-secondaryLight px-3 py-2.5 bg-white" required>
          <option value="">Pilih lokasi volunteer</option>
          <?php foreach (($volunteerLocations ?? []) as $location): ?>
            <option value="<?= (int) $location['id'] ?>">
              <?= htmlspecialchars((string) $location['location_name']) ?>
              <?php if (!empty($location['city'])): ?>
                - <?= htmlspecialchars((string) $location['city']) ?>
              <?php endif; ?>
              <?php if (!empty($location['province'])): ?>
                (<?= htmlspecialchars((string) $location['province']) ?>)
              <?php endif; ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div class="md:col-span-2 space-y-3">
        <div class="flex items-center justify-between gap-2">
          <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55">Pengalaman Volunteer (Opsional)</label>
          <button type="button" id="add-experience-btn" class="rounded-lg bg-brand-secondaryDark text-white text-xs font-semibold px-3 py-2 hover:bg-green-900">Tambah Pengalaman</button>
        </div>
        <div id="experience-list" class="space-y-3">
          <div class="experience-item rounded-xl border border-black p-3 bg-white">
            <p class="experience-title text-sm font-semibold text-brand-secondaryDark mb-2">Pengalaman 1</p>
            <textarea name="experience_texts[]" rows="3" class="w-full rounded-xl border border-brand-secondaryLight px-3 py-2.5" placeholder="Ceritakan pengalaman volunteer yang relevan (opsional)"></textarea>
            <div class="mt-2">
              <input type="file" name="experience_photos[]" accept=".jpg,.jpeg,.png,.webp,.avif,image/*" class="w-full rounded-xl border border-brand-secondaryLight px-3 py-2.5 bg-white">
            </div>
          </div>
        </div>
      </div>

      <div class="md:col-span-2">
        <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1.5">Catatan Aktivitas</label>
        <textarea name="notes" rows="3" class="w-full rounded-xl border border-brand-secondaryLight px-3 py-2.5" placeholder="Jelaskan kontribusi volunteer Anda (opsional)"></textarea>
      </div>

      <div class="md:col-span-2 flex flex-wrap gap-2 pt-2">
        <button type="submit" class="rounded-xl bg-brand-btn text-white font-semibold px-5 py-2.5 hover:bg-red-800 transition-colors">Kirim Aktivitas Volunteer</button>
        <a href="/user/" class="rounded-xl bg-white text-brand-secondaryDark font-semibold px-5 py-2.5 border border-brand-secondaryDark hover:bg-brand-bg transition-colors">Kembali ke Dashboard</a>
      </div>
      </form>
    </div>

    <div class="bg-white rounded-2xl border border-brand-secondaryLight/70 shadow-sm p-5">
      <p class="text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1">Telkomsel Points Anda</p>
      <p class="text-3xl font-display font-semibold text-brand-heading"><?= (int) ($userPoints ?? 0) ?></p>
      <p class="text-sm text-brand-secondaryDark/60 mt-1">Kumpulkan points dari donasi dan kegiatan volunteer.</p>
    </div>
  </div>

  <?php if (!empty($message)): ?>
    <p class="rounded-xl bg-brand-secondaryLight/70 border border-brand-secondaryDark/25 px-4 py-3 text-sm text-brand-secondaryDark"><?= htmlspecialchars($message) ?></p>
  <?php endif; ?>
</section>

<script>
(() => {
  const addBtn = document.getElementById('add-experience-btn');
  const list = document.getElementById('experience-list');
  if (!addBtn || !list) return;

  function refreshTitles() {
    const items = list.querySelectorAll('.experience-item');
    items.forEach((item, idx) => {
      const title = item.querySelector('.experience-title');
      if (title) title.textContent = `Pengalaman ${idx + 1}`;
    });
  }

  function createItem() {
    const wrap = document.createElement('div');
    wrap.className = 'experience-item rounded-xl border border-black p-3 bg-white';
    wrap.innerHTML = `
      <p class="experience-title text-sm font-semibold text-brand-secondaryDark mb-2"></p>
      <div class="flex items-center justify-end mb-2">
        <button type="button" class="remove-exp rounded-lg border border-red-200 bg-red-50 text-red-700 px-2.5 py-1 text-xs font-semibold hover:bg-red-100">Hapus</button>
      </div>
      <textarea name="experience_texts[]" rows="3" class="w-full rounded-xl border border-brand-secondaryLight px-3 py-2.5" placeholder="Ceritakan pengalaman volunteer yang relevan (opsional)"></textarea>
      <div class="mt-2">
        <input type="file" name="experience_photos[]" accept=".jpg,.jpeg,.png,.webp,.avif,image/*" class="w-full rounded-xl border border-brand-secondaryLight px-3 py-2.5 bg-white">
      </div>
    `;
    wrap.querySelector('.remove-exp')?.addEventListener('click', () => {
      wrap.remove();
      refreshTitles();
    });
    return wrap;
  }

  addBtn.addEventListener('click', () => {
    list.appendChild(createItem());
    refreshTitles();
  });

  refreshTitles();
})();
</script>
