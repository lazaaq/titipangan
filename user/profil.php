<section class="space-y-6">
  <div class="bg-white rounded-xl shadow p-6">
      <h2 class="text-lg font-semibold text-brand-heading mb-4">Profil User</h2>

      <form method="POST" action="/user/profil/update" class="space-y-4">
        <div>
          <label class="block text-sm font-semibold mb-1">Nama Lengkap</label>
          <input type="text" name="full_name" value="<?= htmlspecialchars((string) ($userProfile['full_name'] ?? '')) ?>" class="w-full border border-brand-secondaryLight rounded-lg px-3 py-2" required>
        </div>

        <div>
          <label class="block text-sm font-semibold mb-1">Nomor HP</label>
          <div class="flex flex-col sm:flex-row sm:items-stretch gap-2">
            <input id="phone-input" type="text" name="phone" value="<?= htmlspecialchars((string) ($userProfile['phone'] ?? '')) ?>" class="w-full border border-brand-secondaryLight rounded-lg px-3 py-2 bg-gray-100" disabled>
            <button type="button" id="open-phone-modal-btn" class="rounded-lg bg-brand-secondaryDark text-white px-4 h-10 text-sm font-semibold hover:bg-green-900 whitespace-nowrap sm:w-44 inline-flex items-center justify-center">Ubah Nomor HP</button>
          </div>
        </div>

        <div>
          <label class="block text-sm font-semibold mb-1">NIK</label>
          <div class="flex flex-col sm:flex-row sm:items-stretch gap-2">
            <input id="nik-input" type="text" value="<?= htmlspecialchars((string) ($userProfile['nik'] ?? '')) ?>" class="w-full border border-brand-secondaryLight rounded-lg px-3 py-2 bg-gray-100" disabled>
            <input type="hidden" name="nik" id="nik-hidden-input" value="<?= htmlspecialchars((string) ($userProfile['nik'] ?? '')) ?>">
            <button type="button" id="open-nik-modal-btn" class="rounded-lg bg-brand-secondaryDark text-white px-4 h-10 text-sm font-semibold hover:bg-green-900 whitespace-nowrap sm:w-44 inline-flex items-center justify-center">Ubah Nomor NIK</button>
          </div>
          <p id="nik-verify-msg" class="text-xs mt-1 text-brand-secondaryDark/70">Verifikasi NIK masih placeholder UI (belum terhubung ke database NIK nasional).</p>
        </div>

        <div>
          <label class="block text-sm font-semibold mb-1">Alamat</label>
          <textarea name="address" rows="3" class="w-full border border-brand-secondaryLight rounded-lg px-3 py-2" placeholder="Isi alamat"><?= htmlspecialchars((string) ($userProfile['address'] ?? '')) ?></textarea>
        </div>

        <div class="flex gap-2">
          <button type="submit" class="bg-brand-btn text-white rounded-lg px-4 py-2 hover:bg-red-800">Simpan Perubahan</button>
          <a href="/user/" class="bg-white text-brand-secondaryDark rounded-lg px-4 py-2 border border-brand-secondaryDark hover:bg-brand-bg">Kembali</a>
        </div>
      </form>
  </div>

  <div class="bg-white rounded-xl shadow p-6 border border-red-200">
    <h3 class="text-base font-semibold text-red-700 mb-2">Hapus Akun</h3>
    <p class="text-sm text-brand-secondaryDark mb-4">Aksi ini permanen dan akan menghapus data profil serta riwayat pengambilan makanan Anda.</p>
    <form method="POST" action="/user/profil/delete" onsubmit="return confirm('Yakin ingin menghapus akun? Tindakan ini tidak bisa dibatalkan.');">
      <button type="submit" class="bg-red-700 text-white rounded-lg px-4 py-2 hover:bg-red-800">Hapus Akun Saya</button>
    </form>
  </div>

  <?php if (!empty($message)): ?>
    <p class="text-sm text-brand-secondaryDark"><?= htmlspecialchars($message) ?></p>
  <?php endif; ?>
</section>

<div id="phone-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
  <div class="absolute inset-0 bg-black/45" id="phone-modal-backdrop"></div>
  <div class="relative w-full max-w-md rounded-2xl bg-white border border-brand-secondaryLight/70 shadow-2xl p-6">
    <h3 class="text-lg font-semibold text-brand-heading mb-1">Ubah Nomor HP</h3>
    <p class="text-xs text-brand-secondaryDark/70 mb-4">Masukkan nomor handphone baru. Klik verify untuk kirim OTP, lalu klik verify lagi setelah OTP diisi.</p>

    <div class="space-y-3">
      <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/60 mb-1">Nomor Handphone Baru</label>
        <input id="modal-phone-input" type="text" class="w-full border border-brand-secondaryLight rounded-lg px-3 py-2" placeholder="08xxxxxxxxxx">
      </div>
      <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/60 mb-1">Masukkan OTP</label>
        <input id="modal-phone-otp-input" type="text" maxlength="6" class="w-full border border-brand-secondaryLight rounded-lg px-3 py-2" placeholder="6 digit OTP">
      </div>
      <p id="phone-verify-msg" class="text-xs text-brand-secondaryDark/70"></p>
    </div>

    <div class="mt-5 flex items-center justify-end gap-2">
      <button type="button" id="close-phone-modal-btn" class="rounded-lg border border-brand-secondaryDark/25 px-4 py-2 text-sm text-brand-secondaryDark hover:bg-brand-secondaryLight/20">Batal</button>
      <button type="button" id="verify-phone-otp-btn" class="rounded-lg bg-brand-heading text-white px-4 py-2 text-sm font-semibold hover:bg-green-700">Verify</button>
    </div>
  </div>
</div>

<div id="nik-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
  <div class="absolute inset-0 bg-black/45" id="nik-modal-backdrop"></div>
  <div class="relative w-full max-w-md rounded-2xl bg-white border border-brand-secondaryLight/70 shadow-2xl p-6">
    <h3 class="text-lg font-semibold text-brand-heading mb-1">Ubah Nomor NIK</h3>
    <p class="text-xs text-brand-secondaryDark/70 mb-4">Masukkan NIK baru, lalu klik verify. Ini masih placeholder UI untuk alur verifikasi.</p>

    <div class="space-y-3">
      <div>
        <label class="block text-xs font-semibold uppercase tracking-wider text-brand-secondaryDark/60 mb-1">Nomor NIK Baru</label>
        <input id="modal-nik-input" type="text" maxlength="16" class="w-full border border-brand-secondaryLight rounded-lg px-3 py-2" placeholder="16 digit NIK">
      </div>
      <p id="nik-modal-msg" class="text-xs text-brand-secondaryDark/70"></p>
    </div>

    <div class="mt-5 flex items-center justify-end gap-2">
      <button type="button" id="close-nik-modal-btn" class="rounded-lg border border-brand-secondaryDark/25 px-4 py-2 text-sm text-brand-secondaryDark hover:bg-brand-secondaryLight/20">Batal</button>
      <button type="button" id="verify-nik-modal-btn" class="rounded-lg bg-brand-heading text-white px-4 py-2 text-sm font-semibold hover:bg-green-700">Verify</button>
    </div>
  </div>
</div>

<script>
(() => {
  const nikInput = document.getElementById('nik-input');
  const nikHiddenInput = document.getElementById('nik-hidden-input');
  const nameInput = document.querySelector('input[name="full_name"]');
  const msg = document.getElementById('nik-verify-msg');
  const openNikModalBtn = document.getElementById('open-nik-modal-btn');
  const closeNikModalBtn = document.getElementById('close-nik-modal-btn');
  const nikModalBackdrop = document.getElementById('nik-modal-backdrop');
  const nikModal = document.getElementById('nik-modal');
  const modalNikInput = document.getElementById('modal-nik-input');
  const verifyNikModalBtn = document.getElementById('verify-nik-modal-btn');
  const nikModalMsg = document.getElementById('nik-modal-msg');
  const phoneInput = document.getElementById('phone-input');
  const openPhoneModalBtn = document.getElementById('open-phone-modal-btn');
  const closePhoneModalBtn = document.getElementById('close-phone-modal-btn');
  const phoneModalBackdrop = document.getElementById('phone-modal-backdrop');
  const phoneModal = document.getElementById('phone-modal');
  const modalPhoneInput = document.getElementById('modal-phone-input');
  const phoneOtpInput = document.getElementById('modal-phone-otp-input');
  const verifyPhoneOtpBtn = document.getElementById('verify-phone-otp-btn');
  const phoneMsg = document.getElementById('phone-verify-msg');

  function openNikModal() {
    if (modalNikInput && nikHiddenInput) modalNikInput.value = nikHiddenInput.value;
    if (nikModalMsg) {
      nikModalMsg.textContent = '';
      nikModalMsg.className = 'text-xs text-brand-secondaryDark/70';
    }
    nikModal?.classList.remove('hidden');
    nikModal?.classList.add('flex');
  }

  function closeNikModal() {
    nikModal?.classList.add('hidden');
    nikModal?.classList.remove('flex');
  }

  openNikModalBtn?.addEventListener('click', openNikModal);
  closeNikModalBtn?.addEventListener('click', closeNikModal);
  nikModalBackdrop?.addEventListener('click', closeNikModal);

  verifyNikModalBtn?.addEventListener('click', () => {
    const nik = (modalNikInput?.value || '').trim();
    const fullName = (nameInput?.value || '').trim();
    if (!nik || nik.length !== 16) {
      if (nikModalMsg) {
        nikModalMsg.textContent = 'NIK baru harus 16 digit.';
        nikModalMsg.className = 'text-xs text-red-700';
      }
      return;
    }

    if (fullName === '') {
      if (nikModalMsg) {
        nikModalMsg.textContent = 'Nama lengkap wajib diisi sebelum verify.';
        nikModalMsg.className = 'text-xs text-red-700';
      }
      return;
    }

    if (nikInput) nikInput.value = nik;
    if (nikHiddenInput) nikHiddenInput.value = nik;
    if (msg) {
      msg.textContent = 'Placeholder: NIK baru telah diverifikasi di UI dan siap disimpan.';
      msg.className = 'text-xs mt-1 text-green-700';
    }
    if (nikModalMsg) {
      nikModalMsg.textContent = 'Placeholder verify berhasil.';
      nikModalMsg.className = 'text-xs text-green-700';
    }
    setTimeout(closeNikModal, 350);
  });

  function openPhoneModal() {
    if (modalPhoneInput && phoneInput) modalPhoneInput.value = phoneInput.value;
    if (phoneOtpInput) phoneOtpInput.value = '';
    if (phoneMsg) {
      phoneMsg.textContent = '';
      phoneMsg.className = 'text-xs text-brand-secondaryDark/70';
    }
    phoneModal?.classList.remove('hidden');
    phoneModal?.classList.add('flex');
  }

  function closePhoneModal() {
    phoneModal?.classList.add('hidden');
    phoneModal?.classList.remove('flex');
  }

  openPhoneModalBtn?.addEventListener('click', openPhoneModal);
  closePhoneModalBtn?.addEventListener('click', closePhoneModal);
  phoneModalBackdrop?.addEventListener('click', closePhoneModal);

  verifyPhoneOtpBtn?.addEventListener('click', async () => {
    const phone = (modalPhoneInput?.value || '').trim();
    const otp = (phoneOtpInput?.value || '').trim();

    // Step 1: jika OTP kosong, tombol verify dipakai untuk kirim OTP terlebih dulu.
    if (otp === '') {
      if (phoneMsg) phoneMsg.textContent = 'Mengirim OTP...';
      try {
        const body = new URLSearchParams();
        body.set('phone', phone);
        const res = await fetch('/user/profil/verify-phone/send-otp', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: body.toString()
        });
        const data = await res.json();
        if (!res.ok || !data.ok) throw new Error(data.message || 'Gagal kirim OTP.');
        if (phoneMsg) {
          phoneMsg.textContent = data.message || 'OTP berhasil dikirim.';
          phoneMsg.className = 'text-xs text-green-700';
        }
      } catch (err) {
        if (phoneMsg) {
          phoneMsg.textContent = err.message || 'Gagal kirim OTP.';
          phoneMsg.className = 'text-xs text-red-700';
        }
      }
      return;
    }

    if (phoneMsg) phoneMsg.textContent = 'Memverifikasi OTP...';
    try {
      const body = new URLSearchParams();
      body.set('phone', phone);
      body.set('otp', otp);
      const res = await fetch('/user/profil/verify-phone/confirm', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: body.toString()
      });
      const data = await res.json();
      if (!res.ok || !data.ok) throw new Error(data.message || 'Verifikasi OTP gagal.');
      if (phoneMsg) {
        phoneMsg.textContent = data.message || 'Nomor berhasil diverifikasi.';
        phoneMsg.className = 'text-xs text-green-700';
      }
      if (phoneInput) phoneInput.value = phone;
      setTimeout(closePhoneModal, 400);
    } catch (err) {
      if (phoneMsg) {
        phoneMsg.textContent = err.message || 'Verifikasi OTP gagal.';
        phoneMsg.className = 'text-xs text-red-700';
      }
    }
  });
})();
</script>
