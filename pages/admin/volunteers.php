<section class="admin-surface p-6">
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3 mb-4">
    <div>
      <h2 class="admin-section-title">ACC Volunteer</h2>
      <p class="admin-subtitle">Admin memverifikasi pengajuan volunteer. Poin user ditambahkan setelah status disetujui.</p>
    </div>
  </div>

  <div class="admin-table-wrap">
  <table class="admin-table min-w-[900px]">
    <thead>
      <tr>
        <th>Nama</th>
        <th>No HP</th>
        <th>Aktivitas</th>
        <th>Tanggal</th>
        <th>Poin</th>
        <th>Status</th>
        <th>Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($volunteerSubmissions)): ?>
        <tr>
          <td colspan="8" class="text-center text-brand-secondaryDark/65">Belum ada pengajuan volunteer.</td>
        </tr>
      <?php else: ?>
        <?php foreach ($volunteerSubmissions as $submission): ?>
          <?php $isApproved = (($submission['approval_status'] ?? 'pending') === 'approved'); ?>
          <?php $expList = $volunteerExperiencesMap[(int) $submission['id']] ?? []; ?>
          <tr class="align-top">
            <td class="font-medium"><?= htmlspecialchars((string) $submission['full_name']) ?></td>
            <td><?= htmlspecialchars((string) $submission['phone']) ?></td>
            <td>
              <p class="font-medium text-brand-secondaryDark"><?= htmlspecialchars((string) $submission['activity_type']) ?></p>
              <?php if (!empty($submission['location'])): ?>
                <p class="text-xs text-brand-secondaryDark/60 mt-0.5">Lokasi: <?= htmlspecialchars((string) $submission['location']) ?></p>
              <?php endif; ?>
              <?php if (!empty($expList)): ?>
                <div class="mt-1 space-y-1">
                  <?php foreach ($expList as $exp): ?>
                    <?php if (!empty($exp['experience_text'])): ?>
                      <p class="text-xs text-brand-secondaryDark/60">Pengalaman: <?= htmlspecialchars((string) $exp['experience_text']) ?></p>
                    <?php endif; ?>
                    <?php if (!empty($exp['experience_photo_path'])): ?>
                      <p class="text-xs"><a href="<?= htmlspecialchars((string) $exp['experience_photo_path']) ?>" target="_blank" rel="noopener noreferrer" class="text-brand-heading hover:underline">Lihat Foto Pengalaman</a></p>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </div>
              <?php endif; ?>
            </td>
            <td><?= htmlspecialchars(date('d M Y', strtotime((string) $submission['volunteer_date']))) ?></td>
            <td class="font-semibold text-brand-heading">+<?= (int) $submission['points_awarded'] ?></td>
            <td>
              <span
                class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold volunteer-status-badge
                <?= $isApproved ? 'bg-green-100 text-green-800' : (($submission['approval_status'] ?? 'pending') === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-800') ?>"
                data-status-badge>
                <?= htmlspecialchars(ucfirst((string) ($submission['approval_status'] ?? 'pending'))) ?>
              </span>
              <p class="text-xs text-brand-secondaryDark/60 mt-1" data-approved-at>
                <?php if (!empty($submission['approved_at'])): ?>
                  <?= htmlspecialchars(date('d M Y H:i', strtotime((string) $submission['approved_at']))) ?>
                <?php endif; ?>
              </p>
            </td>
            <td>
              <select
                class="admin-input !py-1.5 !text-xs volunteer-status-select"
                data-volunteer-id="<?= (int) $submission['id'] ?>"
                data-current-status="<?= htmlspecialchars((string) ($submission['approval_status'] ?? 'pending')) ?>">
                <option value="pending" <?= (($submission['approval_status'] ?? 'pending') === 'pending') ? 'selected' : '' ?>>Pending</option>
                <option value="approved" <?= (($submission['approval_status'] ?? 'pending') === 'approved') ? 'selected' : '' ?>>Approved</option>
                <option value="rejected" <?= (($submission['approval_status'] ?? 'pending') === 'rejected') ? 'selected' : '' ?>>Rejected</option>
              </select>
              <p class="text-[11px] text-brand-secondaryDark/60 mt-1" data-status-msg></p>
            </td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
  </div>
</section>

<script>
(() => {
  const selects = document.querySelectorAll('.volunteer-status-select');

  function badgeClass(status) {
    if (status === 'approved') return 'inline-flex rounded-full px-2.5 py-1 text-xs font-semibold volunteer-status-badge bg-green-100 text-green-800';
    if (status === 'rejected') return 'inline-flex rounded-full px-2.5 py-1 text-xs font-semibold volunteer-status-badge bg-red-100 text-red-700';
    return 'inline-flex rounded-full px-2.5 py-1 text-xs font-semibold volunteer-status-badge bg-amber-100 text-amber-800';
  }

  selects.forEach((select) => {
    select.addEventListener('change', async () => {
      const volunteerId = select.getAttribute('data-volunteer-id');
      const nextStatus = select.value;
      const prevStatus = select.getAttribute('data-current-status') || 'pending';
      const row = select.closest('tr');
      const badge = row?.querySelector('[data-status-badge]');
      const approvedAt = row?.querySelector('[data-approved-at]');
      const statusMsg = row?.querySelector('[data-status-msg]');

      select.disabled = true;
      if (statusMsg) statusMsg.textContent = 'Menyimpan...';
      try {
        const body = new URLSearchParams();
        body.set('volunteer_id', volunteerId || '');
        body.set('approval_status', nextStatus);

        const res = await fetch('/admin/volunteers/status', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            'X-Requested-With': 'XMLHttpRequest'
          },
          body: body.toString()
        });
        const data = await res.json();
        if (!res.ok || !data.ok) {
          throw new Error(data.message || 'Gagal menyimpan status.');
        }

        select.setAttribute('data-current-status', nextStatus);
        if (badge) {
          badge.className = badgeClass(nextStatus);
          badge.textContent = nextStatus.charAt(0).toUpperCase() + nextStatus.slice(1);
        }
        if (approvedAt) {
          approvedAt.textContent = (nextStatus === 'approved' && data.approved_at) ? data.approved_at : '';
        }
        if (statusMsg) statusMsg.textContent = 'Tersimpan';
      } catch (err) {
        select.value = prevStatus;
        if (statusMsg) statusMsg.textContent = err.message || 'Gagal menyimpan status.';
      } finally {
        setTimeout(() => { if (statusMsg && statusMsg.textContent === 'Tersimpan') statusMsg.textContent = ''; }, 1200);
        select.disabled = false;
      }
    });
  });
})();
</script>
