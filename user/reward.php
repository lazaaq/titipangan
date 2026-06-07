<section class="space-y-6">
  <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
    <div>
      <p class="text-xs font-semibold tracking-[0.2em] uppercase text-brand-secondaryDark/55">Rewards</p>
      <h2 class="font-display text-3xl font-semibold text-brand-forest">Reward Telkomsel Points</h2>
      <p class="text-sm text-brand-secondaryDark/70 mt-1">Tukarkan points Anda dengan voucher produk Telkomsel dan ekosistemnya.</p>
    </div>
    <div class="bg-white rounded-xl border border-brand-secondaryLight/70 shadow-sm px-4 py-3">
      <p class="text-xs uppercase tracking-wider text-brand-secondaryDark/55 font-semibold">Points Anda</p>
      <p class="text-2xl font-display font-semibold text-brand-heading"><?= (int) ($userPoints ?? 0) ?></p>
    </div>
  </div>

  <?php if (!empty($message)): ?>
    <p class="rounded-xl bg-brand-secondaryLight/70 border border-brand-secondaryDark/25 px-4 py-3 text-sm text-brand-secondaryDark"><?= htmlspecialchars((string) $message) ?></p>
  <?php endif; ?>

  <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-4">
    <?php foreach (($rewardCatalog ?? []) as $reward): ?>
      <?php $pointsNeeded = (int) ($reward['points'] ?? 0); ?>
      <?php $canRedeem = ((int) ($userPoints ?? 0) >= $pointsNeeded); ?>
      <article class="bg-white rounded-2xl border border-brand-secondaryLight/70 shadow-sm overflow-hidden flex flex-col">
        <div class="h-40 bg-[#f5faef] flex items-center justify-center">
          <?php if (!empty($reward['image_path'])): ?>
            <img src="<?= htmlspecialchars((string) $reward['image_path']) ?>" alt="<?= htmlspecialchars((string) ($reward['name'] ?? 'Reward')) ?>" class="h-full w-full object-cover">
          <?php else: ?>
            <span class="text-brand-secondaryDark/70"><?= appIcon('reward', 'w-12 h-12') ?></span>
          <?php endif; ?>
        </div>
        <div class="p-5 flex flex-col gap-3 h-full">
        <div class="flex items-start justify-between gap-2">
          <div>
            <p class="text-xs uppercase tracking-wider text-brand-secondaryDark/55 font-semibold"><?= htmlspecialchars((string) ($reward['category'] ?? 'Reward')) ?></p>
            <h3 class="font-semibold text-brand-forest leading-tight mt-1"><?= htmlspecialchars((string) ($reward['name'] ?? '')) ?></h3>
          </div>
          <span class="inline-flex rounded-lg bg-brand-secondaryLight/75 text-brand-secondaryDark px-2.5 py-1 text-xs font-semibold">
            <?= $pointsNeeded ?> pts
          </span>
        </div>
        <p class="text-sm text-brand-secondaryDark/65"><?= htmlspecialchars((string) ($reward['description'] ?? '')) ?></p>
        <form method="POST" action="/user/reward/redeem" class="mt-auto">
          <input type="hidden" name="reward_code" value="<?= htmlspecialchars((string) ($reward['code'] ?? '')) ?>">
          <button
            type="submit"
            class="w-full rounded-xl px-4 py-2.5 text-sm font-semibold text-white <?= $canRedeem ? 'bg-brand-btn hover:bg-red-800' : 'bg-gray-400 cursor-not-allowed' ?>"
            <?= $canRedeem ? '' : 'disabled' ?>>
            <?= $canRedeem ? 'Redeem Sekarang' : 'Points Belum Cukup' ?>
          </button>
        </form>
        </div>
      </article>
    <?php endforeach; ?>
  </div>

  <div class="bg-white rounded-2xl border border-brand-secondaryLight/70 shadow-sm p-6">
    <h3 class="text-lg font-semibold text-brand-heading mb-3">Riwayat Redeem</h3>
    <div class="overflow-x-auto">
      <table class="w-full text-sm border-collapse min-w-[640px]">
        <thead>
          <tr class="bg-brand-secondaryLight/65 text-left">
            <th class="p-2.5">Tanggal</th>
            <th class="p-2.5">Reward</th>
            <th class="p-2.5">Points Terpakai</th>
          </tr>
        </thead>
        <tbody>
          <?php if (empty($rewardHistory)): ?>
            <tr><td colspan="3" class="p-3 text-brand-secondaryDark/65">Belum ada riwayat redeem.</td></tr>
          <?php else: ?>
            <?php foreach ($rewardHistory as $history): ?>
              <tr class="border-b border-brand-secondaryLight/45">
                <td class="p-2.5 text-brand-secondaryDark/70"><?= htmlspecialchars(date('d M Y H:i', strtotime((string) $history['created_at']))) ?></td>
                <td class="p-2.5 font-medium text-brand-secondaryDark"><?= htmlspecialchars((string) $history['reward_name']) ?></td>
                <td class="p-2.5 text-brand-heading font-semibold">-<?= (int) $history['points_spent'] ?></td>
              </tr>
            <?php endforeach; ?>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</section>
