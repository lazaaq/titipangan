<?php $role = $role ?? 'user'; ?>
<?php $isUserTheme = $role !== 'admin'; ?>

<header class="relative min-h-14 flex items-center px-4 sm:px-6 py-3 border-b flex-shrink-0 z-30
  <?= $isUserTheme
    ? 'border-brand-secondaryLight/40 bg-brand-bg'
    : 'border-brand-secondaryLight/50 bg-white/95 backdrop-blur-sm' ?>">

  <div class="flex items-center justify-between gap-3 w-full min-w-0">
    <div class="flex items-center gap-3 min-w-0">
    <?php if (!$isUserTheme): ?>
      <span class="inline-flex items-center rounded-full bg-brand-secondaryLight/70 text-brand-secondaryDark text-[10px] font-semibold px-2.5 py-1 tracking-wide uppercase">Admin</span>
    <?php endif; ?>
    <h1 class="font-sans font-semibold text-sm sm:text-base tracking-tight text-brand-secondaryDark truncate">
      <?= htmlspecialchars($title ?? 'Titipangan') ?>
    </h1>
    </div>

    <div class="flex items-center gap-2 sm:gap-3">
      <a href="/katalog-publik"
        class="hidden md:inline-flex items-center justify-center text-[11px] sm:text-xs font-medium px-3 py-2 rounded-lg transition-all duration-150 whitespace-nowrap
        <?= $isUserTheme
          ? 'text-brand-secondaryDark/70 hover:bg-brand-secondaryLight/50 hover:text-brand-secondaryDark bg-white/70 sm:bg-transparent'
          : 'text-brand-secondaryDark/60 hover:bg-brand-secondaryLight/50 hover:text-brand-secondaryDark bg-white/70 sm:bg-transparent' ?>">
        Katalog Publik →
      </a>
      <button
        type="button"
        class="inline-flex md:hidden items-center justify-center w-10 h-10 rounded-lg border border-brand-secondaryLight/60 bg-white/70 text-brand-secondaryDark hover:bg-white transition-colors"
        data-mobile-nav-toggle
        aria-expanded="false"
        aria-controls="mobile-nav-panel"
        aria-label="Buka menu navigasi">
        <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
          <path d="M4 6h16" />
          <path d="M4 12h16" />
          <path d="M4 18h16" />
        </svg>
      </button>
    </div>
  </div>
</header>
