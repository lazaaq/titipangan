<?php $role = $role ?? 'user'; ?>
<?php $currentPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/'; ?>
<?php
if (!function_exists('mobileNavIconSvg')) {
  function mobileNavIconSvg(string $name): string
  {
    return match ($name) {
      'dashboard' => '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l9-8 9 8"/><path d="M5 10v10h14V10"/><path d="M9 20v-6h6v6"/></svg>',
      'catalog' => '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 3H20v18H6.5A2.5 2.5 0 0 0 4 23.5V5.5A2.5 2.5 0 0 1 6.5 3Z"/></svg>',
      'gift' => '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 12v8H4v-8"/><path d="M2 7h20v5H2z"/><path d="M12 22V7"/><path d="M12 7h4a2 2 0 1 0-2-2c-1.5 0-2 2-2 2Zm0 0H8a2 2 0 1 1 2-2c1.5 0 2 2 2 2Z"/></svg>',
      'reward' => '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 15l-3.5 2 1-3.9L6 10.6l4-.3L12 6.7l2 3.6 4 .3-3.5 2.5 1 3.9z"/><path d="M12 2v4"/><path d="M2 12h4"/><path d="M18 12h4"/></svg>',
      'handshake' => '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 13l3 3 7-7"/><path d="M3 12l4-4 4 4 3-3 7 7-4 4-3-3-7 7-4-4z"/></svg>',
      'book' => '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 3H20v18H6.5A2.5 2.5 0 0 0 4 23.5V5.5A2.5 2.5 0 0 1 6.5 3Z"/></svg>',
      'user' => '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21a8 8 0 0 0-16 0"/><circle cx="12" cy="7" r="4"/></svg>',
      'chevron' => '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="m9 18 6-6-6-6"/></svg>',
      default => '<svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20V4"/><path d="M5 12h14"/></svg>',
    };
  }
}
if (!function_exists('mobileNavItemClass')) {
  function mobileNavItemClass(string $path, string $currentPath, bool $matchPrefix = true): string
  {
    $isActive = $matchPrefix ? str_starts_with($currentPath, $path) : $currentPath === $path;
    return $isActive
      ? 'w-full px-4 py-3 rounded-xl text-sm font-semibold bg-brand-secondaryDark text-white flex items-center gap-3 shadow-sm'
      : 'w-full px-4 py-3 rounded-xl text-sm font-semibold bg-brand-secondaryLight/40 text-brand-secondaryDark flex items-center gap-3';
  }
}
?>
<nav id="mobile-nav-panel" data-mobile-nav-panel class="hidden md:hidden absolute left-0 right-0 top-full z-20 px-4 pb-4 pt-3 border-b border-brand-secondaryLight/35 bg-white/95 backdrop-blur-sm shadow-lg">
  <div class="flex flex-col gap-2">
    <?php if ($role === 'admin'): ?>
      <a href="/katalog-publik" class="<?= mobileNavItemClass('/katalog-publik', $currentPath, false) ?>">
        <span class="w-8 h-8 rounded-lg bg-brand-secondaryLight/60 flex items-center justify-center flex-shrink-0"><?= mobileNavIconSvg('catalog') ?></span>
        <span class="flex-1 text-left">Katalog</span>
        <span class="text-brand-secondaryDark/35"><?= mobileNavIconSvg('chevron') ?></span>
      </a>
      <a href="/admin" class="<?= mobileNavItemClass('/admin', $currentPath) ?>">
        <span class="w-8 h-8 rounded-lg bg-white/70 flex items-center justify-center flex-shrink-0"><?= mobileNavIconSvg('dashboard') ?></span>
        <span class="flex-1 text-left">Dashboard</span>
        <span class="text-brand-secondaryDark/35"><?= mobileNavIconSvg('chevron') ?></span>
      </a>
      <a href="/admin/inventory" class="<?= mobileNavItemClass('/admin/inventory', $currentPath) ?>">
        <span class="w-8 h-8 rounded-lg bg-white/70 flex items-center justify-center flex-shrink-0"><?= mobileNavIconSvg('catalog') ?></span>
        <span class="flex-1 text-left">Inventori</span>
        <span class="text-brand-secondaryDark/35"><?= mobileNavIconSvg('chevron') ?></span>
      </a>
      <a href="/admin/rewards" class="<?= mobileNavItemClass('/admin/rewards', $currentPath) ?>">
        <span class="w-8 h-8 rounded-lg bg-white/70 flex items-center justify-center flex-shrink-0"><?= mobileNavIconSvg('reward') ?></span>
        <span class="flex-1 text-left">Reward</span>
        <span class="text-brand-secondaryDark/35"><?= mobileNavIconSvg('chevron') ?></span>
      </a>
      <a href="/admin/donasi" class="<?= mobileNavItemClass('/admin/donasi', $currentPath) ?>">
        <span class="w-8 h-8 rounded-lg bg-white/70 flex items-center justify-center flex-shrink-0"><?= mobileNavIconSvg('gift') ?></span>
        <span class="flex-1 text-left">Donasi</span>
        <span class="text-brand-secondaryDark/35"><?= mobileNavIconSvg('chevron') ?></span>
      </a>
      <a href="/admin/claims" class="<?= mobileNavItemClass('/admin/claims', $currentPath) ?>">
        <span class="w-8 h-8 rounded-lg bg-white/70 flex items-center justify-center flex-shrink-0"><?= mobileNavIconSvg('gift') ?></span>
        <span class="flex-1 text-left">Pengambilan</span>
        <span class="text-brand-secondaryDark/35"><?= mobileNavIconSvg('chevron') ?></span>
      </a>
      <a href="/admin/lokasi-volunteer" class="<?= mobileNavItemClass('/admin/lokasi-volunteer', $currentPath) ?>">
        <span class="w-8 h-8 rounded-lg bg-white/70 flex items-center justify-center flex-shrink-0"><?= mobileNavIconSvg('handshake') ?></span>
        <span class="flex-1 text-left">Lokasi</span>
        <span class="text-brand-secondaryDark/35"><?= mobileNavIconSvg('chevron') ?></span>
      </a>
      <a href="/admin/volunteers" class="<?= mobileNavItemClass('/admin/volunteers', $currentPath) ?>">
        <span class="w-8 h-8 rounded-lg bg-white/70 flex items-center justify-center flex-shrink-0"><?= mobileNavIconSvg('handshake') ?></span>
        <span class="flex-1 text-left">Volunteer</span>
        <span class="text-brand-secondaryDark/35"><?= mobileNavIconSvg('chevron') ?></span>
      </a>
      <a href="/admin/dokumen-regulasi" class="<?= mobileNavItemClass('/admin/dokumen-regulasi', $currentPath) ?>">
        <span class="w-8 h-8 rounded-lg bg-white/70 flex items-center justify-center flex-shrink-0"><?= mobileNavIconSvg('book') ?></span>
        <span class="flex-1 text-left">Dokumen</span>
        <span class="text-brand-secondaryDark/35"><?= mobileNavIconSvg('chevron') ?></span>
      </a>
    <?php else: ?>
      <a href="/user/" class="<?= mobileNavItemClass('/user', $currentPath) ?>">
        <span class="w-8 h-8 rounded-lg bg-white/70 flex items-center justify-center flex-shrink-0"><?= mobileNavIconSvg('dashboard') ?></span>
        <span class="flex-1 text-left">Dashboard</span>
        <span class="text-brand-secondaryDark/35"><?= mobileNavIconSvg('chevron') ?></span>
      </a>
      <a href="/katalog-publik" class="<?= mobileNavItemClass('/katalog-publik', $currentPath, false) ?>">
        <span class="w-8 h-8 rounded-lg bg-brand-secondaryLight/60 flex items-center justify-center flex-shrink-0"><?= mobileNavIconSvg('catalog') ?></span>
        <span class="flex-1 text-left">Katalog</span>
        <span class="text-brand-secondaryDark/35"><?= mobileNavIconSvg('chevron') ?></span>
      </a>
      <a href="/user/donasi" class="<?= mobileNavItemClass('/user/donasi', $currentPath) ?>">
        <span class="w-8 h-8 rounded-lg bg-white/70 flex items-center justify-center flex-shrink-0"><?= mobileNavIconSvg('gift') ?></span>
        <span class="flex-1 text-left">Donasi</span>
        <span class="text-brand-secondaryDark/35"><?= mobileNavIconSvg('chevron') ?></span>
      </a>
      <a href="/user/reward" class="<?= mobileNavItemClass('/user/reward', $currentPath) ?>">
        <span class="w-8 h-8 rounded-lg bg-white/70 flex items-center justify-center flex-shrink-0"><?= mobileNavIconSvg('reward') ?></span>
        <span class="flex-1 text-left">Reward</span>
        <span class="text-brand-secondaryDark/35"><?= mobileNavIconSvg('chevron') ?></span>
      </a>
      <a href="/user/volunteer" class="<?= mobileNavItemClass('/user/volunteer', $currentPath) ?>">
        <span class="w-8 h-8 rounded-lg bg-white/70 flex items-center justify-center flex-shrink-0"><?= mobileNavIconSvg('handshake') ?></span>
        <span class="flex-1 text-left">Volunteer</span>
        <span class="text-brand-secondaryDark/35"><?= mobileNavIconSvg('chevron') ?></span>
      </a>
      <a href="/dokumen-regulasi" class="<?= mobileNavItemClass('/dokumen-regulasi', $currentPath, false) ?>">
        <span class="w-8 h-8 rounded-lg bg-white/70 flex items-center justify-center flex-shrink-0"><?= mobileNavIconSvg('book') ?></span>
        <span class="flex-1 text-left">Dokumen</span>
        <span class="text-brand-secondaryDark/35"><?= mobileNavIconSvg('chevron') ?></span>
      </a>
      <a href="/user/profil" class="<?= mobileNavItemClass('/user/profil', $currentPath) ?>">
        <span class="w-8 h-8 rounded-lg bg-white/70 flex items-center justify-center flex-shrink-0"><?= mobileNavIconSvg('user') ?></span>
        <span class="flex-1 text-left">Profil</span>
        <span class="text-brand-secondaryDark/35"><?= mobileNavIconSvg('chevron') ?></span>
      </a>
    <?php endif; ?>
  </div>
</nav>
