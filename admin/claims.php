<section class="space-y-6">
  <div class="admin-surface p-6">
    <h2 class="admin-section-title">Redeem Kode Pengambilan</h2>
    <p class="admin-subtitle mt-1 mb-5">Input kode dari QR user. Data pengambilan baru tercatat setelah kode berhasil diredeem.</p>
    <form id="redeem-form" method="POST" action="/admin/claims/redeem" class="grid md:grid-cols-5 gap-3">
      <input
        id="redeem-code-input"
        name="redeem_code"
        placeholder="Kode 6 digit (contoh: A9B2CD)"
        class="admin-input md:col-span-3 uppercase tracking-widest"
        maxlength="6"
        required>
      <button class="admin-btn-primary w-full md:col-span-1">Redeem Kode</button>
      <button
        type="button"
        id="open-qr-scanner"
        class="rounded-xl bg-brand-secondaryDark text-white px-4 py-2 text-sm font-semibold hover:bg-green-900 transition-colors w-full md:col-span-1">
        Scan QR
      </button>
    </form>
    <?php if (!empty($message)): ?>
      <p class="mt-4 rounded-xl border border-brand-secondaryDark/20 bg-brand-secondaryLight/45 px-4 py-3 text-sm text-brand-secondaryDark">
        <?= htmlspecialchars((string) $message) ?>
      </p>
    <?php endif; ?>
  </div>

  <div class="admin-surface p-6">
    <h2 class="admin-section-title mb-4">Riwayat Pengambilan</h2>
    <div class="admin-table-wrap">
    <table class="admin-table">
    <thead>
      <tr>
        <th>Nama</th>
        <th>No HP</th>
        <th>Tanggal Ambil</th>
        <th>Minggu</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($claims as $claim): ?>
        <tr>
          <td class="font-medium"><?= htmlspecialchars($claim['full_name']) ?></td>
          <td><?= htmlspecialchars($claim['phone']) ?></td>
          <td><?= htmlspecialchars(date('d M Y H:i', strtotime($claim['claimed_at']))) ?></td>
          <td><?= htmlspecialchars($claim['week_key']) ?></td>
          <td>
            <form method="POST" action="/admin/claims/delete" class="delete-claim-form">
              <input type="hidden" name="claim_id" value="<?= (int) $claim['id'] ?>">
              <button type="submit" class="rounded-lg border border-red-200 bg-red-50 text-red-700 px-3 py-1.5 text-xs font-semibold hover:bg-red-100">Hapus</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
      </tbody>
    </table>
    </div>
  </div>
</section>

<div id="qr-scanner-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
  <div class="absolute inset-0 bg-black/45" id="qr-scanner-backdrop"></div>
  <div class="relative w-full max-w-lg rounded-2xl bg-white border border-brand-secondaryLight/70 shadow-2xl p-5">
    <div class="flex items-center justify-between mb-3">
      <h3 class="text-lg font-semibold text-brand-heading">Scan QR Pengambilan</h3>
      <button type="button" id="close-qr-scanner" class="text-brand-secondaryDark/70 hover:text-brand-secondaryDark">Tutup</button>
    </div>
    <p class="text-xs text-brand-secondaryDark/60 mb-3">Izinkan akses kamera saat diminta, lalu arahkan kamera ke QR user.</p>
    <video id="qr-video" class="w-full rounded-xl bg-black/80 aspect-video" autoplay playsinline muted></video>
    <p id="qr-scan-status" class="text-xs text-brand-secondaryDark/60 mt-3">Menunggu kamera aktif...</p>
  </div>
</div>

<div id="delete-confirm-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
  <div class="absolute inset-0 bg-black/45" id="delete-confirm-backdrop"></div>
  <div class="relative w-full max-w-sm rounded-2xl bg-white border border-brand-secondaryLight/70 shadow-2xl p-5">
    <h3 class="text-lg font-semibold text-brand-heading">Konfirmasi Hapus</h3>
    <p class="text-sm text-brand-secondaryDark/75 mt-2">Yakin ingin menghapus riwayat pengambilan ini?</p>
    <div class="mt-5 flex items-center justify-end gap-2">
      <button type="button" id="delete-cancel-btn" class="rounded-lg border border-brand-secondaryDark/25 px-3 py-2 text-sm text-brand-secondaryDark hover:bg-brand-secondaryLight/25">Batal</button>
      <button type="button" id="delete-confirm-btn" class="rounded-lg bg-brand-btn text-white px-3 py-2 text-sm font-semibold hover:bg-red-800">Ya, Hapus</button>
    </div>
  </div>
</div>

<script>
(() => {
  const openBtn = document.getElementById('open-qr-scanner');
  const closeBtn = document.getElementById('close-qr-scanner');
  const backdrop = document.getElementById('qr-scanner-backdrop');
  const modal = document.getElementById('qr-scanner-modal');
  const video = document.getElementById('qr-video');
  const status = document.getElementById('qr-scan-status');
  const codeInput = document.getElementById('redeem-code-input');
  const redeemForm = document.getElementById('redeem-form');
  const deleteModal = document.getElementById('delete-confirm-modal');
  const deleteBackdrop = document.getElementById('delete-confirm-backdrop');
  const deleteCancelBtn = document.getElementById('delete-cancel-btn');
  const deleteConfirmBtn = document.getElementById('delete-confirm-btn');
  const deleteForms = document.querySelectorAll('.delete-claim-form');

  let stream = null;
  let rafId = null;
  let detector = null;
  let isSubmitting = false;
  let pendingDeleteForm = null;

  async function stopScanner() {
    if (rafId) cancelAnimationFrame(rafId);
    rafId = null;
    if (stream) {
      stream.getTracks().forEach(track => track.stop());
      stream = null;
    }
    video.srcObject = null;
  }

  async function scanLoop() {
    if (!detector || !video || video.readyState < 2) {
      rafId = requestAnimationFrame(scanLoop);
      return;
    }
    try {
      const codes = await detector.detect(video);
      if (codes && codes.length > 0) {
        const raw = (codes[0].rawValue || '').trim();
        if (raw) {
          codeInput.value = raw.toUpperCase();
          status.textContent = 'QR terbaca. Memproses redeem otomatis...';
          if (!isSubmitting && redeemForm) {
            isSubmitting = true;
            redeemForm.submit();
            return;
          }
          await stopScanner();
          setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
          }, 400);
          return;
        }
      }
    } catch (err) {}
    rafId = requestAnimationFrame(scanLoop);
  }

  async function startScanner() {
    if (!('BarcodeDetector' in window)) {
      status.textContent = 'Browser ini belum mendukung scan QR kamera. Gunakan input kode manual.';
      return;
    }
    try {
      detector = new BarcodeDetector({ formats: ['qr_code'] });
      stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' }, audio: false });
      video.srcObject = stream;
      status.textContent = 'Arahkan kamera ke QR code user...';
      scanLoop();
    } catch (err) {
      status.textContent = 'Gagal mengakses kamera. Pastikan izin kamera diberikan.';
    }
  }

  function openModal() {
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    startScanner();
  }

  async function closeModal() {
    await stopScanner();
    modal.classList.add('hidden');
    modal.classList.remove('flex');
  }

  openBtn?.addEventListener('click', openModal);
  closeBtn?.addEventListener('click', closeModal);
  backdrop?.addEventListener('click', closeModal);

  function openDeleteModal(form) {
    pendingDeleteForm = form;
    deleteModal?.classList.remove('hidden');
    deleteModal?.classList.add('flex');
  }

  function closeDeleteModal() {
    pendingDeleteForm = null;
    deleteModal?.classList.add('hidden');
    deleteModal?.classList.remove('flex');
  }

  deleteForms.forEach((form) => {
    form.addEventListener('submit', (event) => {
      event.preventDefault();
      openDeleteModal(form);
    });
  });

  deleteCancelBtn?.addEventListener('click', closeDeleteModal);
  deleteBackdrop?.addEventListener('click', closeDeleteModal);
  deleteConfirmBtn?.addEventListener('click', () => {
    if (pendingDeleteForm) pendingDeleteForm.submit();
  });
})();
</script>
