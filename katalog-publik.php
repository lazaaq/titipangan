<!doctype html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Katalog Publik - Titipangan</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=Playfair+Display:wght@500;600&display=swap" rel="stylesheet">
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = { theme: { extend: { fontFamily: { sans: ['DM Sans','sans-serif'], display: ['Playfair Display','serif'] }, colors: { brand: { bg:'#F5F3E6', heading:'#007E34', btn:'#C61313', secondaryDark:'#005B21', secondaryLight:'#CFFFA4', forest:'#01361A' } } } } };
  </script>
</head>
<body class="font-sans text-brand-secondaryDark bg-white" style="min-height:100vh; background-image:url('/assets/backgrounds/texture.png'); background-repeat:repeat; background-position:center top; background-size:720px auto;">
  <main class="min-h-screen bg-white/80">
    <div class="p-6 lg:p-10">
      <div class="w-full max-w-[1600px] mx-auto">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
          <div>
            <p class="text-xs text-brand-secondaryDark/45 font-semibold uppercase tracking-[0.18em] mb-2">Publik · Real Time</p>
            <h2 class="font-display text-3xl font-semibold text-brand-forest">Katalog Inventori</h2>
            <p class="text-sm text-brand-secondaryDark/60 mt-2">Data stok terkini tanpa perlu login.</p>
          </div>
          <?php if (isUserLoggedIn()): ?>
            <a href="/user/" class="inline-flex items-center gap-1.5 rounded-xl bg-brand-heading text-white text-sm font-semibold px-4 py-2.5 hover:bg-brand-secondaryDark transition-colors self-start sm:self-auto">← Dashboard</a>
          <?php else: ?>
            <a href="/user/login" class="inline-flex items-center gap-1.5 rounded-xl bg-brand-heading text-white text-sm font-semibold px-4 py-2.5 hover:bg-brand-secondaryDark transition-colors self-start sm:self-auto">Login User →</a>
          <?php endif; ?>
        </div>

        <div class="mt-8 grid gap-6 lg:grid-cols-[320px_minmax(0,1fr)]">
          <aside class="lg:sticky lg:top-6 self-start rounded-2xl border border-white/10 bg-brand-forest p-4 sm:p-5 text-white shadow-[0_18px_40px_rgba(1,54,26,0.18)]">
            <div class="mb-4">
              <p class="text-xs text-white/55 font-semibold uppercase tracking-[0.18em] mb-2">Sidebar</p>
              <h3 class="font-display text-xl font-semibold text-white">Filter</h3>
              <p class="text-sm text-white/75 mt-2">Pilih lokasi, kategori, dan nama barang untuk menyaring inventori yang tersedia.</p>
            </div>
            <?php
              $inventoryCategoryOptions = $inventoryCategoryOptions ?? inventoryCategoryMap();
              $resolvedSelectedLocationName = '';
              foreach (($inventoryLocations ?? []) as $location) {
                if ((int) ($selectedLocationId ?? 0) === (int) $location['id']) {
                  $resolvedSelectedLocationName = (string) $location['location_name'];
                  if (!empty($location['city'])) {
                    $resolvedSelectedLocationName .= ' - ' . (string) $location['city'];
                  }
                  break;
                }
              }
            ?>
            <form method="GET" action="/katalog-publik" class="space-y-3">
              <div>
                <label class="block text-xs text-white/70 font-semibold uppercase tracking-[0.16em] mb-1.5">Pilih Lokasi</label>
                <select name="location_id" class="w-full rounded-xl border border-white/15 px-3 py-2.5 bg-white/95 text-sm text-brand-secondaryDark" required>
                  <option value="">Pilih lokasi terlebih dulu</option>
                  <?php foreach (($inventoryLocations ?? []) as $location): ?>
                    <option value="<?= (int) $location['id'] ?>" <?= ((int) ($selectedLocationId ?? 0) === (int) $location['id']) ? 'selected' : '' ?>>
                      <?= htmlspecialchars((string) $location['location_name']) ?>
                      <?php if (!empty($location['city'])): ?>
                        - <?= htmlspecialchars((string) $location['city']) ?>
                      <?php endif; ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div>
                <label class="block text-xs text-white/70 font-semibold uppercase tracking-[0.16em] mb-1.5">Cari Barang</label>
                <input
                  type="search"
                  name="q"
                  value="<?= htmlspecialchars((string) ($searchQuery ?? '')) ?>"
                  placeholder="Cari nama barang"
                  class="w-full rounded-xl border border-white/15 px-3 py-2.5 bg-white/95 text-sm text-brand-secondaryDark placeholder:text-brand-secondaryDark/45"
                >
              </div>
              <div>
                <label class="block text-xs text-white/70 font-semibold uppercase tracking-[0.16em] mb-1.5">Kategori Barang</label>
                <select name="category" class="w-full rounded-xl border border-white/15 px-3 py-2.5 bg-white/95 text-sm text-brand-secondaryDark">
                  <option value="">Semua kategori</option>
                  <?php foreach (($inventoryCategoryOptions ?? []) as $value => $label): ?>
                    <option value="<?= htmlspecialchars((string) $value) ?>" <?= (($selectedCategory ?? '') === (string) $value) ? 'selected' : '' ?>>
                      <?= htmlspecialchars((string) $label) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl bg-white text-brand-forest text-sm font-semibold px-4 py-2.5 hover:bg-brand-secondaryLight transition-colors">
                Terapkan Filter
              </button>
            </form>
          </aside>

          <section class="space-y-6">
            <?php if ((int) ($selectedLocationId ?? 0) <= 0): ?>
              <div class="rounded-2xl border border-dashed border-brand-secondaryDark/25 bg-white p-8 text-center text-sm text-brand-secondaryDark/65">
                Pilih lokasi dulu untuk melihat item inventori yang tersedia.
              </div>
            <?php elseif (empty($inventory)): ?>
              <div class="rounded-2xl border border-dashed border-brand-secondaryDark/25 bg-white p-8 text-center text-sm text-brand-secondaryDark/65">
                Belum ada item untuk lokasi ini.
              </div>
            <?php else: ?>
              <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                <?php foreach ($inventory as $item): ?>
                  <?php
                    $name = strtolower((string) $item['item_name']);
                    $icon = 'box';
                    if (str_contains($name, 'beras') || str_contains($name, 'makanan') || str_contains($name, 'mie')) { $icon = 'food'; }
                    elseif (str_contains($name, 'sayur')) { $icon = 'leaf'; }
                    elseif (str_contains($name, 'minum') || str_contains($name, 'air') || str_contains($name, 'susu')) { $icon = 'drink'; }
                  ?>
                  <?php
                    $modalImage = !empty($item['image_path']) ? (string) $item['image_path'] : '';
                    $modalUpdatedAt = date('d M Y, H:i', strtotime((string) $item['updated_at']));
                    $modalPerPcsInfo = trim((string) ($item['per_pcs_info'] ?? ''));
                  ?>
                  <button
                    type="button"
                    class="group w-full overflow-hidden rounded-2xl border border-[#e8efe5] bg-white text-left shadow-[0_12px_30px_rgba(1,54,26,0.06)] transition-all hover:-translate-y-1 hover:shadow-[0_16px_40px_rgba(1,54,26,0.10)] focus:outline-none focus:ring-2 focus:ring-brand-heading/25"
                    data-item-modal
                    data-item-name="<?= htmlspecialchars((string) $item['item_name']) ?>"
                    data-item-category="<?= htmlspecialchars((string) ($item['category'] ?? 'Lainnya')) ?>"
                    data-item-stock="<?= htmlspecialchars((string) ((int) $item['stock'] . ' ' . $item['unit'])) ?>"
                    data-item-updated="<?= htmlspecialchars((string) $modalUpdatedAt) ?>"
                    data-item-location="<?= htmlspecialchars($resolvedSelectedLocationName !== '' ? $resolvedSelectedLocationName : '-') ?>"
                    data-item-per-pcs="<?= htmlspecialchars($modalPerPcsInfo !== '' ? $modalPerPcsInfo : '-') ?>"
                    data-item-image="<?= htmlspecialchars($modalImage) ?>"
                    data-item-has-image="<?= $modalImage !== '' ? 'true' : 'false' ?>"
                  >
                    <div class="h-40 flex items-center justify-center text-5xl bg-[#f7fbf4]">
                      <?php if (!empty($item['image_path'])): ?>
                        <img src="<?= htmlspecialchars((string) $item['image_path']) ?>" alt="<?= htmlspecialchars((string) $item['item_name']) ?>" class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105">
                      <?php else: ?>
                        <span class="text-brand-secondaryDark/70"><?= appIcon($icon, 'w-12 h-12') ?></span>
                      <?php endif; ?>
                    </div>
                    <div class="p-5 space-y-2.5">
                      <div class="flex items-start justify-between gap-3">
                        <h3 class="font-semibold text-brand-forest leading-tight"><?= htmlspecialchars($item['item_name']) ?></h3>
                        <span class="shrink-0 rounded-lg bg-brand-secondaryLight/70 px-2.5 py-1 text-[11px] font-semibold text-brand-secondaryDark">
                          <?= htmlspecialchars((string) ($item['category'] ?? 'Lainnya')) ?>
                        </span>
                      </div>
                      <div class="flex items-center justify-between">
                        <span class="text-xs text-brand-secondaryDark/55 uppercase tracking-wide">Stok</span>
                        <span class="inline-flex items-center rounded-lg px-2.5 py-1 text-xs font-semibold <?= ((int) $item['stock']) > 10 ? 'bg-brand-secondaryLight/70 text-brand-secondaryDark' : 'bg-red-50 text-red-600' ?>">
                          <?= (int) $item['stock'] . ' ' . htmlspecialchars($item['unit']) ?>
                        </span>
                      </div>
                      <p class="text-xs text-brand-secondaryDark/45">Diperbarui: <?= htmlspecialchars($modalUpdatedAt) ?></p>
                    </div>
                  </button>
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
          </section>
        </div>
      </div>
    </div>

    <div id="inventory-item-modal" class="fixed inset-0 z-50 hidden">
      <div class="absolute inset-0 bg-[#071208]/70 backdrop-blur-sm" data-item-modal-close></div>
      <div class="relative flex min-h-screen items-center justify-center p-3 sm:p-5 lg:p-6">
        <div class="relative w-full max-w-6xl overflow-hidden rounded-[28px] bg-white shadow-[0_30px_90px_rgba(0,0,0,0.28)]">
          <button type="button" class="absolute right-4 top-4 z-10 inline-flex h-10 w-10 items-center justify-center rounded-full bg-white/90 text-brand-secondaryDark shadow-md transition hover:bg-white" data-item-modal-close aria-label="Tutup detail item">
            <span class="text-xl leading-none">&times;</span>
          </button>
          <div class="grid grid-cols-1 md:grid-cols-[minmax(0,1.35fr)_minmax(0,0.9fr)]">
            <div class="relative min-h-[320px] bg-[#f4f8f1] sm:min-h-[420px] md:min-h-[620px]">
              <img id="inventory-item-modal-image" src="" alt="" class="hidden h-full w-full object-cover">
              <div id="inventory-item-modal-fallback" class="flex h-full min-h-[280px] items-center justify-center bg-[radial-gradient(circle_at_top_left,rgba(0,126,52,0.10),transparent_36%),linear-gradient(180deg,#f7fbf4,#edf5ea)] px-8 text-center text-brand-secondaryDark/45">
                <div>
                  <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full bg-white shadow-sm">
                    <span class="text-3xl">📦</span>
                  </div>
                  <p class="mt-4 text-sm font-medium">Belum ada gambar untuk item ini.</p>
                </div>
              </div>
            </div>
            <div class="p-6 sm:p-8">
              <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-brand-secondaryDark/45">Detail Inventori</p>
              <h3 id="inventory-item-modal-name" class="mt-3 font-display text-2xl font-semibold text-brand-forest sm:text-3xl"></h3>
              <div class="mt-4 flex flex-wrap items-center gap-2">
                <span id="inventory-item-modal-category" class="inline-flex rounded-lg bg-brand-secondaryLight/70 px-3 py-1 text-[11px] font-semibold text-brand-secondaryDark"></span>
                <span id="inventory-item-modal-stock" class="inline-flex rounded-lg bg-brand-heading/8 px-3 py-1 text-[11px] font-semibold text-brand-heading"></span>
              </div>

              <div class="mt-6 space-y-4">
                <div class="rounded-2xl border border-[#e8efe5] bg-[#f8fbf6] p-4">
                  <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-brand-secondaryDark/45">Lokasi</p>
                  <p id="inventory-item-modal-location" class="mt-2 text-sm leading-relaxed text-brand-secondaryDark/80"></p>
                </div>
                <div class="rounded-2xl border border-[#e8efe5] bg-white p-4">
                  <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-brand-secondaryDark/45">Isi Per Item</p>
                  <p id="inventory-item-modal-per-pcs" class="mt-2 text-sm leading-relaxed text-brand-secondaryDark/80"></p>
                </div>
                <div class="rounded-2xl border border-[#e8efe5] bg-white p-4">
                  <p class="text-[11px] font-semibold uppercase tracking-[0.16em] text-brand-secondaryDark/45">Terakhir Diperbarui</p>
                  <p id="inventory-item-modal-updated" class="mt-2 text-sm leading-relaxed text-brand-secondaryDark/80"></p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const modal = document.getElementById('inventory-item-modal');
      const modalImage = document.getElementById('inventory-item-modal-image');
      const modalFallback = document.getElementById('inventory-item-modal-fallback');
      const modalName = document.getElementById('inventory-item-modal-name');
      const modalCategory = document.getElementById('inventory-item-modal-category');
      const modalStock = document.getElementById('inventory-item-modal-stock');
      const modalLocation = document.getElementById('inventory-item-modal-location');
      const modalPerPcs = document.getElementById('inventory-item-modal-per-pcs');
      const modalUpdated = document.getElementById('inventory-item-modal-updated');
      const triggers = Array.from(document.querySelectorAll('[data-item-modal]'));
      const closeTriggers = Array.from(document.querySelectorAll('[data-item-modal-close]'));

      if (!modal || !modalImage || !modalFallback || !modalName || !modalCategory || !modalStock || !modalLocation || !modalPerPcs || !modalUpdated || !triggers.length) {
        return;
      }

      const closeModal = () => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
      };

      const openModal = (trigger) => {
        const hasImage = trigger.dataset.itemHasImage === 'true';
        const imageSrc = trigger.dataset.itemImage || '';

        modalName.textContent = trigger.dataset.itemName || 'Item Inventori';
        modalCategory.textContent = trigger.dataset.itemCategory || 'Lainnya';
        modalStock.textContent = trigger.dataset.itemStock || '-';
        modalLocation.textContent = trigger.dataset.itemLocation || '-';
        modalPerPcs.textContent = trigger.dataset.itemPerPcs || '-';
        modalUpdated.textContent = trigger.dataset.itemUpdated || '-';

        if (hasImage && imageSrc !== '') {
          modalImage.src = imageSrc;
          modalImage.alt = trigger.dataset.itemName || 'Gambar item inventori';
          modalImage.classList.remove('hidden');
          modalFallback.classList.add('hidden');
        } else {
          modalImage.src = '';
          modalImage.alt = '';
          modalImage.classList.add('hidden');
          modalFallback.classList.remove('hidden');
        }

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
      };

      triggers.forEach((trigger) => {
        trigger.addEventListener('click', () => openModal(trigger));
      });

      closeTriggers.forEach((trigger) => {
        trigger.addEventListener('click', closeModal);
      });

      document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
          closeModal();
        }
      });
    });
  </script>
</body>
</html>
