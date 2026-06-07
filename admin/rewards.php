<section class="space-y-6">
  <div class="admin-surface p-6">
    <h2 class="admin-section-title">Kelola Reward</h2>
    <p class="admin-subtitle mt-1 mb-5">Tambah, edit, dan hapus reward beserta gambar untuk halaman user.</p>

    <form method="POST" action="/admin/rewards/create" enctype="multipart/form-data" class="grid md:grid-cols-6 gap-3">
      <input name="reward_code" placeholder="Kode (contoh: PULSA_5K)" class="admin-input" required>
      <input name="reward_name" placeholder="Nama Reward" class="admin-input md:col-span-2" required>
      <input name="category" placeholder="Kategori" class="admin-input" required>
      <input name="points_needed" type="number" min="1" placeholder="Points" class="admin-input" required>
      <label class="relative rounded-xl border border-dashed border-brand-secondaryDark/30 bg-[#f8fcf4] px-3 py-2.5 hover:border-brand-secondaryDark/55 hover:bg-[#f3faeb] transition-colors cursor-pointer">
        <input name="image" type="file" accept=".jpg,.jpeg,.png,.webp,.avif,image/*" class="absolute inset-0 opacity-0 cursor-pointer">
        <div class="flex items-center gap-2">
          <span class="w-8 h-8 rounded-lg bg-white border border-brand-secondaryLight flex items-center justify-center text-brand-secondaryDark/75"><?= appIcon('image', 'w-4 h-4') ?></span>
          <div class="leading-tight">
            <p class="text-xs font-semibold text-brand-secondaryDark">Upload Foto</p>
            <p class="text-[11px] text-brand-secondaryDark/60">Maks 2MB</p>
          </div>
        </div>
      </label>
      <textarea name="description" rows="2" placeholder="Deskripsi reward" class="admin-input md:col-span-4"></textarea>
      <label class="inline-flex items-center gap-2 text-sm text-brand-secondaryDark md:col-span-1">
        <input type="checkbox" name="is_active" value="1" checked class="rounded border-brand-secondaryLight">
        <span>Aktif</span>
      </label>
      <button class="admin-btn-primary md:col-span-1">Tambah Reward</button>
    </form>
  </div>

  <div class="admin-surface p-6">
    <h3 class="admin-section-title mb-5">Daftar Reward</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
      <?php if (empty($rewardCatalog)): ?>
        <div class="col-span-full rounded-2xl border border-dashed border-brand-secondaryDark/25 p-8 text-center text-sm text-brand-secondaryDark/60">
          Belum ada reward.
        </div>
      <?php endif; ?>
      <?php foreach ($rewardCatalog as $reward): ?>
        <article class="rounded-2xl border border-[#e4ecdf] bg-white shadow-[0_12px_30px_rgba(1,54,26,0.06)] overflow-hidden">
          <div class="h-40 bg-[#f5faef] flex items-center justify-center">
            <?php if (!empty($reward['image_path'])): ?>
              <img src="<?= htmlspecialchars((string) $reward['image_path']) ?>" alt="<?= htmlspecialchars((string) $reward['reward_name']) ?>" class="h-full w-full object-cover">
            <?php else: ?>
              <span class="text-brand-secondaryDark/70"><?= appIcon('reward', 'w-12 h-12') ?></span>
            <?php endif; ?>
          </div>
          <div class="p-4 space-y-2.5">
            <div class="flex items-start justify-between gap-2">
              <div>
                <p class="text-[11px] uppercase tracking-wider text-brand-secondaryDark/55 font-semibold"><?= htmlspecialchars((string) $reward['category']) ?></p>
                <h4 class="font-semibold text-brand-forest leading-tight"><?= htmlspecialchars((string) $reward['reward_name']) ?></h4>
              </div>
              <span class="inline-flex rounded-lg bg-brand-secondaryLight/75 text-brand-secondaryDark px-2.5 py-1 text-xs font-semibold"><?= (int) $reward['points_needed'] ?> pts</span>
            </div>
            <p class="text-xs text-brand-secondaryDark/70"><?= htmlspecialchars((string) ($reward['description'] ?? '-')) ?></p>
            <p class="text-[11px] text-brand-secondaryDark/60">Kode: <?= htmlspecialchars((string) $reward['reward_code']) ?> • <?= ((int) $reward['is_active'] === 1) ? 'Aktif' : 'Nonaktif' ?></p>

            <details class="pt-1">
              <summary class="cursor-pointer text-sm font-semibold text-brand-secondaryDark hover:text-brand-heading">Edit Reward</summary>
              <form method="POST" action="/admin/rewards/update" enctype="multipart/form-data" class="space-y-2.5 mt-3">
                <input type="hidden" name="id" value="<?= (int) $reward['id'] ?>">
                <input name="reward_code" value="<?= htmlspecialchars((string) $reward['reward_code']) ?>" class="admin-input" required>
                <input name="reward_name" value="<?= htmlspecialchars((string) $reward['reward_name']) ?>" class="admin-input" required>
                <div class="grid grid-cols-2 gap-2">
                  <input name="category" value="<?= htmlspecialchars((string) $reward['category']) ?>" class="admin-input" required>
                  <input name="points_needed" type="number" min="1" value="<?= (int) $reward['points_needed'] ?>" class="admin-input" required>
                </div>
                <textarea name="description" rows="2" class="admin-input"><?= htmlspecialchars((string) ($reward['description'] ?? '')) ?></textarea>
                <label class="relative w-full block rounded-xl border border-dashed border-brand-secondaryDark/30 bg-[#f8fcf4] px-3 py-2.5 hover:border-brand-secondaryDark/55 hover:bg-[#f3faeb] transition-colors cursor-pointer">
                  <input name="image" type="file" accept=".jpg,.jpeg,.png,.webp,.avif,image/*" class="absolute inset-0 opacity-0 cursor-pointer">
                  <div class="w-full flex items-center gap-2.5">
                    <span class="w-8 h-8 rounded-lg bg-white border border-brand-secondaryLight flex items-center justify-center text-brand-secondaryDark/75"><?= appIcon('image', 'w-4 h-4') ?></span>
                    <div class="leading-tight">
                      <p class="text-xs font-semibold text-brand-secondaryDark">Ganti Foto Reward</p>
                      <p class="text-[11px] text-brand-secondaryDark/60">JPG/PNG/WEBP/AVIF</p>
                    </div>
                  </div>
                </label>
                <label class="inline-flex items-center gap-2 text-sm text-brand-secondaryDark">
                  <input type="checkbox" name="is_active" value="1" <?= ((int) $reward['is_active'] === 1) ? 'checked' : '' ?> class="rounded border-brand-secondaryLight">
                  <span>Aktif</span>
                </label>
                <button class="admin-btn-primary text-sm">Simpan</button>
              </form>
              <form method="POST" action="/admin/rewards/delete" onsubmit="return confirm('Hapus reward ini?')" class="mt-2">
                <input type="hidden" name="id" value="<?= (int) $reward['id'] ?>">
                <button class="admin-btn-danger text-sm">Hapus</button>
              </form>
            </details>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>
