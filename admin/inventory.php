<section class="space-y-6">
  <div class="admin-surface p-6">
    <h2 class="admin-section-title">Kelola Inventori</h2>
    <p class="admin-subtitle mt-1 mb-5">Tambah, edit, atau hapus data stok inventori dengan cepat.</p>
    <?php $inventoryCategoryOptions = $inventoryCategoryOptions ?? inventoryCategoryMap(); ?>
    <form method="POST" action="/admin/inventory/create" enctype="multipart/form-data" class="grid md:grid-cols-6 gap-3">
      <input name="item_name" placeholder="Nama Item" class="admin-input" required>
      <input name="stock" type="number" min="0" placeholder="Stok" class="admin-input" required>
      <select name="category" class="admin-input" required>
        <option value="">Pilih Kategori</option>
        <?php foreach (($inventoryCategoryOptions ?? []) as $value => $label): ?>
          <option value="<?= htmlspecialchars((string) $value) ?>"><?= htmlspecialchars((string) $label) ?></option>
        <?php endforeach; ?>
      </select>
      <input name="per_pcs_info" placeholder="Isi per pcs (contoh: 100 gram / 1 liter / 1 buah)" class="admin-input" required>
      <label class="relative rounded-xl border border-dashed border-brand-secondaryDark/30 bg-[#f8fcf4] px-3 py-2.5 hover:border-brand-secondaryDark/55 hover:bg-[#f3faeb] transition-colors cursor-pointer">
        <input name="image" type="file" accept=".jpg,.jpeg,.png,.webp,.avif,image/*" class="absolute inset-0 opacity-0 cursor-pointer">
        <div class="flex items-center gap-2.5">
          <span class="w-8 h-8 rounded-lg bg-white border border-brand-secondaryLight flex items-center justify-center text-brand-secondaryDark/75"><?= appIcon('image', 'w-4 h-4') ?></span>
          <div class="leading-tight">
            <p class="text-xs font-semibold text-brand-secondaryDark">Upload Gambar</p>
            <p class="text-[11px] text-brand-secondaryDark/60">JPG, PNG, WEBP, AVIF · max 2MB</p>
          </div>
        </div>
      </label>
      <button class="admin-btn-primary">Tambah Item</button>
    </form>
    <?php if (!empty($message)): ?>
      <p class="mt-4 rounded-xl border border-brand-secondaryDark/20 bg-brand-secondaryLight/45 px-4 py-3 text-sm text-brand-secondaryDark">
        <?= htmlspecialchars((string) $message) ?>
      </p>
    <?php endif; ?>
  </div>

  <div class="admin-surface p-6">
    <h3 class="admin-section-title mb-5">Daftar Item Inventori</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
      <?php if (empty($inventory)): ?>
        <div class="col-span-full rounded-2xl border border-dashed border-brand-secondaryDark/25 p-8 text-center text-sm text-brand-secondaryDark/60">
          Belum ada item inventori. Tambahkan item baru dari form di atas.
        </div>
      <?php endif; ?>
      <?php foreach ($inventory as $item): ?>
        <?php
          $name = strtolower((string) $item['item_name']);
          $icon = 'box';
          if (str_contains($name, 'beras') || str_contains($name, 'makanan') || str_contains($name, 'mie')) { $icon = 'food'; }
          elseif (str_contains($name, 'sayur')) { $icon = 'leaf'; }
          elseif (str_contains($name, 'minum') || str_contains($name, 'air') || str_contains($name, 'susu')) { $icon = 'drink'; }
        ?>
        <article class="rounded-2xl border border-[#e4ecdf] bg-white shadow-[0_12px_30px_rgba(1,54,26,0.06)] overflow-hidden">
          <div class="h-40 bg-[#f5faef] flex items-center justify-center text-5xl">
            <?php if (!empty($item['image_path'])): ?>
              <img src="<?= htmlspecialchars((string) $item['image_path']) ?>" alt="<?= htmlspecialchars((string) $item['item_name']) ?>" class="h-full w-full object-cover">
            <?php else: ?>
              <span class="text-brand-secondaryDark/70"><?= appIcon($icon, 'w-12 h-12') ?></span>
            <?php endif; ?>
          </div>
          <div class="p-4">
            <div class="flex items-start justify-between gap-2">
              <div>
                <h4 class="font-semibold text-brand-forest leading-tight"><?= htmlspecialchars($item['item_name']) ?></h4>
                <p class="text-[11px] uppercase tracking-wider text-brand-secondaryDark/55 font-semibold mt-1">
                  <?= htmlspecialchars((string) ($item['category'] ?? 'Lainnya')) ?>
                </p>
              </div>
              <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold <?= ((int) $item['stock']) > 10 ? 'bg-brand-secondaryLight/70 text-brand-secondaryDark' : 'bg-red-50 text-red-600' ?>">
                <?= (int) $item['stock'] . ' pcs' ?>
              </span>
            </div>
            <p class="text-xs text-brand-secondaryDark/65 mt-1">
              1 pcs = <?= htmlspecialchars((string) ($item['per_pcs_info'] ?? '-')) ?>
            </p>
            <p class="text-xs text-brand-secondaryDark/45 mt-2">Diperbarui: <?= htmlspecialchars(date('d M Y, H:i', strtotime((string) $item['updated_at']))) ?></p>
            <div class="flex items-center gap-4 mt-4">
              <button
                type="button"
                class="font-medium text-brand-secondaryDark hover:text-brand-heading text-sm"
                onclick="document.getElementById('edit-card-<?= (int) $item['id'] ?>').classList.toggle('hidden')">
                Edit
              </button>
              <form method="POST" action="/admin/inventory/delete" onsubmit="return confirm('Hapus item ini?')">
                <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                <button class="admin-btn-danger text-sm">Hapus</button>
              </form>
            </div>
            <div id="edit-card-<?= (int) $item['id'] ?>" class="hidden mt-4 pt-4 border-t border-brand-secondaryLight/70">
              <form method="POST" action="/admin/inventory/update" enctype="multipart/form-data" class="space-y-2.5">
                <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                <input name="item_name" value="<?= htmlspecialchars($item['item_name']) ?>" class="admin-input" required>
                <div class="grid grid-cols-2 gap-2">
                  <input name="stock" type="number" min="0" value="<?= (int) $item['stock'] ?>" class="admin-input" required>
                  <input name="per_pcs_info" value="<?= htmlspecialchars((string) ($item['per_pcs_info'] ?? '')) ?>" class="admin-input" placeholder="Isi per pcs (contoh: 100 gram)" required>
                </div>
                <select name="category" class="admin-input" required>
                  <option value="">Pilih Kategori</option>
                  <?php foreach (($inventoryCategoryOptions ?? []) as $value => $label): ?>
                    <option value="<?= htmlspecialchars((string) $value) ?>" <?= ((string) ($item['category'] ?? 'Lainnya') === (string) $value) ? 'selected' : '' ?>>
                      <?= htmlspecialchars((string) $label) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
                <label class="relative w-full block rounded-xl border border-dashed border-brand-secondaryDark/30 bg-[#f8fcf4] px-3 py-2.5 hover:border-brand-secondaryDark/55 hover:bg-[#f3faeb] transition-colors cursor-pointer">
                  <input name="image" type="file" accept=".jpg,.jpeg,.png,.webp,.avif,image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                  <div class="w-full flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-white border border-brand-secondaryLight flex items-center justify-center text-brand-secondaryDark/75"><?= appIcon('image', 'w-4 h-4') ?></span>
                    <div class="leading-tight min-w-0">
                      <p class="text-xs font-semibold text-brand-secondaryDark">Ganti Gambar</p>
                      <p class="text-[11px] text-brand-secondaryDark/60">JPG, PNG, WEBP, AVIF · max 2MB</p>
                    </div>
                  </div>
                </label>
                <div class="flex items-center gap-2">
                  <button class="admin-btn-primary text-sm">Simpan</button>
                  <button
                    type="button"
                    class="rounded-xl px-3 py-2 border border-brand-secondaryDark text-brand-secondaryDark hover:bg-white text-sm font-medium"
                    onclick="document.getElementById('edit-card-<?= (int) $item['id'] ?>').classList.add('hidden')">
                    Batal
                  </button>
                </div>
              </form>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
