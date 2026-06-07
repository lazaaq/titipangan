<section class="space-y-6">
  <div class="admin-surface p-6">
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-5">
      <div>
        <h2 class="admin-section-title">Form Donasi Admin</h2>
        <p class="admin-subtitle mt-1">Catat donasi warga menggunakan nomor handphone sebagai identitas user.</p>
      </div>
      <button type="button" id="open-add-item-modal" class="admin-btn-primary">+ Tambah Nama Barang</button>
    </div>
    <form method="POST" action="/admin/donasi/create" class="grid md:grid-cols-2 gap-4">
      <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1.5">Nomor HP User</label>
        <input name="phone" placeholder="Contoh: 0812xxxx" class="admin-input" required>
      </div>

      <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1.5">Jenis Donasi</label>
        <select name="donation_type" class="admin-input" required>
          <option value="">Pilih jenis donasi</option>
          <option value="Makanan">Makanan</option>
          <option value="Sayuran">Sayuran</option>
          <option value="Minuman">Minuman</option>
          <option value="Non Konsumsi">Non Konsumsi</option>
        </select>
      </div>

      <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1.5">Nama Barang</label>
        <input id="item-search" type="text" placeholder="Cari nama barang..." class="admin-input mb-2">
        <select id="item-select" name="item_id" class="admin-input" required>
          <option value="">Pilih nama barang</option>
          <?php foreach (($donationCatalogItems ?? []) as $catalogItem): ?>
            <option
              value="<?= (int) $catalogItem['id'] ?>"
              data-item-name="<?= htmlspecialchars((string) $catalogItem['item_name']) ?>"
              data-item-unit="<?= htmlspecialchars((string) $catalogItem['unit']) ?>">
              <?= htmlspecialchars((string) $catalogItem['item_name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1.5">Jumlah</label>
        <input name="quantity" type="number" min="1" value="1" class="admin-input" required>
      </div>

      <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1.5">Satuan</label>
        <input id="item-unit-display" placeholder="Satuan otomatis dari katalog" class="admin-input bg-gray-100" disabled>
      </div>

      <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1.5">Kondisi Barang</label>
        <input name="item_condition" placeholder="Contoh: Baru / Layak pakai" class="admin-input">
      </div>

      <div class="md:col-span-2">
        <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1.5">Alamat Penjemputan</label>
        <textarea name="pickup_address" rows="2" class="admin-input" placeholder="Opsional"></textarea>
      </div>

      <div class="md:col-span-2">
        <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1.5">Catatan</label>
        <textarea name="notes" rows="2" class="admin-input" placeholder="Opsional"></textarea>
      </div>

      <div class="md:col-span-2">
        <button class="admin-btn-primary">Simpan Donasi</button>
      </div>
    </form>

    <?php if (!empty($message)): ?>
      <p class="mt-4 rounded-xl border border-brand-secondaryDark/20 bg-brand-secondaryLight/45 px-4 py-3 text-sm text-brand-secondaryDark">
        <?= htmlspecialchars((string) $message) ?>
      </p>
    <?php endif; ?>
  </div>

  <div class="admin-surface p-6">
    <h3 class="admin-section-title mb-4">Riwayat Donasi</h3>
    <div class="admin-table-wrap">
    <table class="admin-table min-w-[900px]">
      <thead>
        <tr>
          <th>Nama</th>
          <th>No HP</th>
          <th>Jenis</th>
          <th>Barang</th>
          <th>Qty</th>
          <th>Poin</th>
          <th>Waktu</th>
        </tr>
      </thead>
      <tbody>
        <?php if (empty($adminDonations)): ?>
          <tr>
            <td colspan="7" class="text-center text-brand-secondaryDark/65">Belum ada data donasi.</td>
          </tr>
        <?php else: ?>
          <?php foreach ($adminDonations as $donation): ?>
            <tr>
              <td class="font-medium"><?= htmlspecialchars((string) $donation['full_name']) ?></td>
              <td><?= htmlspecialchars((string) $donation['phone']) ?></td>
              <td><?= htmlspecialchars((string) $donation['donation_type']) ?></td>
              <td><?= htmlspecialchars((string) $donation['item_name']) ?></td>
              <td><?= (int) $donation['quantity'] . ' ' . htmlspecialchars((string) $donation['unit']) ?></td>
              <td class="font-semibold text-brand-heading">+<?= (int) $donation['points_awarded'] ?></td>
              <td><?= htmlspecialchars(date('d M Y H:i', strtotime((string) $donation['created_at']))) ?></td>
            </tr>
          <?php endforeach; ?>
        <?php endif; ?>
      </tbody>
    </table>
    </div>
  </div>
</section>

<div id="add-item-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
  <div class="absolute inset-0 bg-black/45" id="add-item-backdrop"></div>
  <div class="relative w-full max-w-md rounded-2xl bg-white border border-brand-secondaryLight/70 shadow-2xl p-6">
    <h3 class="text-lg font-semibold text-brand-heading mb-1">Tambah Nama Barang Baru</h3>
    <p class="text-xs text-brand-secondaryDark/70 mb-4">Tambahkan barang ke katalog agar bisa dipilih di form donasi.</p>
    <form method="POST" action="/admin/donasi/items/create" class="space-y-3">
      <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1">Nama Barang</label>
        <input name="item_name" class="admin-input" placeholder="Contoh: Telur Ayam 1 Tray" required>
      </div>
      <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/55 mb-1">Satuan</label>
        <input name="unit" class="admin-input" placeholder="Contoh: pcs / box / tray" value="pcs" required>
      </div>
      <div class="pt-2 flex items-center justify-end gap-2">
        <button type="button" id="close-add-item-modal" class="rounded-lg border border-brand-secondaryDark/25 px-4 py-2 text-sm text-brand-secondaryDark hover:bg-brand-secondaryLight/20">Batal</button>
        <button class="admin-btn-primary">Simpan Barang</button>
      </div>
    </form>
  </div>
</div>

<script>
(() => {
  const searchInput = document.getElementById('item-search');
  const itemSelect = document.getElementById('item-select');
  const unitDisplay = document.getElementById('item-unit-display');
  const openModalBtn = document.getElementById('open-add-item-modal');
  const closeModalBtn = document.getElementById('close-add-item-modal');
  const modal = document.getElementById('add-item-modal');
  const backdrop = document.getElementById('add-item-backdrop');

  function refreshUnit() {
    if (!itemSelect || !unitDisplay) return;
    const opt = itemSelect.options[itemSelect.selectedIndex];
    unitDisplay.value = (opt && opt.dataset && opt.dataset.itemUnit) ? opt.dataset.itemUnit : '';
  }

  function filterOptions() {
    if (!searchInput || !itemSelect) return;
    const q = searchInput.value.toLowerCase().trim();
    for (const opt of itemSelect.options) {
      if (!opt.value) continue;
      const name = (opt.dataset.itemName || opt.textContent || '').toLowerCase();
      opt.hidden = (q !== '' && !name.includes(q));
    }
  }

  function openModal() {
    modal?.classList.remove('hidden');
    modal?.classList.add('flex');
  }

  function closeModal() {
    modal?.classList.add('hidden');
    modal?.classList.remove('flex');
  }

  searchInput?.addEventListener('input', filterOptions);
  itemSelect?.addEventListener('change', refreshUnit);
  refreshUnit();

  openModalBtn?.addEventListener('click', openModal);
  closeModalBtn?.addEventListener('click', closeModal);
  backdrop?.addEventListener('click', closeModal);
})();
</script>
