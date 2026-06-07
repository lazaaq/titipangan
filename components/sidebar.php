<?php $role = $role ?? 'user'; ?>
<?php $isUserTheme = $role !== 'admin'; ?>
<?php $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/'; ?>
<?php
function menuClass(string $path, string $currentPath, bool $matchPrefix = false): string {
  $isActive = $matchPrefix ? str_starts_with($currentPath, $path) : $currentPath === $path;
  return $isActive ? 'nav-link active' : 'nav-link';
}
?>

<aside class="hidden md:flex md:w-56 lg:w-60 flex-shrink-0 md:min-h-screen flex-col"
  <?php if ($isUserTheme): ?>
    style="background:linear-gradient(185deg,#01361A 0%,#005B21 55%,#018136 100%);"
  <?php else: ?>
    style="background:linear-gradient(180deg,#ffffff 0%, #f8fdf1 100%); border-right:1px solid rgba(0,91,33,0.14);"
  <?php endif; ?>>

  <div class="p-5 pb-4">
    <div class="flex items-center gap-3">
      <div class="w-9 h-9 rounded-xl flex items-center justify-center text-base flex-shrink-0
        <?= $isUserTheme ? 'bg-white/15' : 'bg-brand-secondaryLight/60' ?>">
        <?= appIcon('recycle', 'w-5 h-5') ?>
      </div>
      <div>
        <p class="text-[10px] uppercase tracking-[0.15em] font-medium <?= $isUserTheme ? 'text-white/45' : 'text-brand-secondaryDark/45' ?>">
          <?= $isUserTheme ? 'User' : 'Admin' ?>
        </p>
        <p class="font-display font-semibold text-base leading-tight <?= $isUserTheme ? 'text-white' : 'text-brand-forest' ?>">Titipangan</p>
      </div>
    </div>
  </div>

  <div class="mx-5 mb-3 border-t <?= $isUserTheme ? 'border-white/10' : 'border-brand-secondaryLight/60' ?>"></div>

  <nav class="flex-1 px-3 space-y-0.5">
    <?php if ($role === 'admin'): ?>
      <a class="<?= menuClass('/admin', $currentPath) ?>" href="/admin"><span class="nav-icon"><?= appIcon('dashboard', 'w-4 h-4') ?></span><span>Dashboard</span></a>
      <a class="<?= menuClass('/admin/inventory', $currentPath) ?>" href="/admin/inventory"><span class="nav-icon"><?= appIcon('box', 'w-4 h-4') ?></span><span>Inventori</span></a>
      <a class="<?= menuClass('/admin/rewards', $currentPath, true) ?>" href="/admin/rewards"><span class="nav-icon"><?= appIcon('reward', 'w-4 h-4') ?></span><span>Reward</span></a>
      <a class="<?= menuClass('/admin/donasi', $currentPath) ?>" href="/admin/donasi"><span class="nav-icon"><?= appIcon('gift', 'w-4 h-4') ?></span><span>Form Donasi</span></a>
      <a class="<?= menuClass('/admin/claims', $currentPath) ?>" href="/admin/claims"><span class="nav-icon"><?= appIcon('receipt', 'w-4 h-4') ?></span><span>Pengambilan</span></a>
      <a class="<?= menuClass('/admin/lokasi-volunteer', $currentPath, true) ?>" href="/admin/lokasi-volunteer"><span class="nav-icon"><?= appIcon('leaf', 'w-4 h-4') ?></span><span>Lokasi Volunteer</span></a>
      <a class="<?= menuClass('/admin/volunteers', $currentPath) ?>" href="/admin/volunteers"><span class="nav-icon"><?= appIcon('check-circle', 'w-4 h-4') ?></span><span>ACC Volunteer</span></a>
      <a class="<?= menuClass('/admin/dokumen-regulasi', $currentPath) ?>" href="/admin/dokumen-regulasi"><span class="nav-icon"><?= appIcon('book', 'w-4 h-4') ?></span><span>Dokumen & Regulasi</span></a>
    <?php else: ?>
      <a class="<?= menuClass('/user', $currentPath, true) ?>" href="/user/"><span class="nav-icon"><?= appIcon('home', 'w-4 h-4') ?></span><span>Dashboard</span></a>
      <a class="<?= menuClass('/user/donasi', $currentPath, true) ?>" href="/user/donasi"><span class="nav-icon"><?= appIcon('gift', 'w-4 h-4') ?></span><span>Donasi</span></a>
      <a class="<?= menuClass('/user/volunteer', $currentPath, true) ?>" href="/user/volunteer"><span class="nav-icon"><?= appIcon('handshake', 'w-4 h-4') ?></span><span>Volunteer</span></a>
      <a class="<?= menuClass('/user/reward', $currentPath, true) ?>" href="/user/reward"><span class="nav-icon"><?= appIcon('reward', 'w-4 h-4') ?></span><span>Reward</span></a>
      <a class="<?= menuClass('/dokumen-regulasi', $currentPath) ?>" href="/dokumen-regulasi"><span class="nav-icon"><?= appIcon('book', 'w-4 h-4') ?></span><span>Dokumen & Regulasi</span></a>
      <a class="<?= menuClass('/user/profil', $currentPath) ?>" href="/user/profil"><span class="nav-icon"><?= appIcon('user', 'w-4 h-4') ?></span><span>Profil</span></a>
    <?php endif; ?>
  </nav>

  <div class="p-3 mt-auto">
    <?php if ($role === 'admin'): ?>
      <a href="/admin/logout" class="nav-link" style="color:<?= $isUserTheme ? 'rgba(255,200,200,0.7)' : '#C61313' ?>;">
        <span class="nav-icon" style="background:<?= $isUserTheme ? 'rgba(198,19,19,0.15)' : 'rgba(198,19,19,0.08)' ?>;"><?= appIcon('logout', 'w-4 h-4') ?></span>
        <span>Logout</span>
      </a>
    <?php else: ?>
      <a href="/user/logout" class="nav-link" style="color:<?= $isUserTheme ? 'rgba(255,200,200,0.7)' : '#C61313' ?>;">
        <span class="nav-icon" style="background:<?= $isUserTheme ? 'rgba(198,19,19,0.15)' : 'rgba(198,19,19,0.08)' ?>;"><?= appIcon('logout', 'w-4 h-4') ?></span>
        <span>Logout</span>
      </a>
    <?php endif; ?>
  </div>
</aside>
