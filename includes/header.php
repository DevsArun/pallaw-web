<?php
require_once __DIR__ . '/functions.php';
$page        = $page        ?? '';
$page_title  = $page_title  ?? '';
$site        = setting('site_name', 'Nexora Institute');
$title       = $page_title ? "$page_title · $site" : "$site · " . setting('tagline', 'Industry-Ready Skills');

$navLinks = [
  'home'     => ['Home',    'index.php'],
  'courses'  => ['Courses', 'courses.php'],
  'projects' => ['Projects','projects.php'],
  'verify'   => ['Verify',  'verify.php'],
  'about'    => ['About',   'about.php'],
  'contact'  => ['Contact', 'contact.php'],
];
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?= e(setting('tagline')) ?>">
<meta name="theme-color" content="#0b1020">
<meta property="og:title" content="<?= e($title) ?>">
<meta property="og:description" content="<?= e(setting('tagline')) ?>">
<meta property="og:type" content="website">
<title><?= e($title) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
tailwind.config = {
  theme: {
    extend: {
      fontFamily: {
        sans: ['Inter', 'system-ui', 'sans-serif'],
        display: ['Space Grotesk', 'Inter', 'sans-serif'],
      },
      colors: {
        brand: {
          50:'#eef2ff',100:'#e0e7ff',200:'#c7d2fe',300:'#a5b4fc',400:'#818cf8',
          500:'#6366f1',600:'#4f46e5',700:'#4338ca',800:'#3730a3',900:'#312e81',950:'#1e1b4b',
        },
        ink: '#0b1020',
      },
      boxShadow: {
        'glow': '0 0 0 1px rgba(99,102,241,.1), 0 20px 60px -20px rgba(79,70,229,.45)',
        'card': '0 1px 2px rgba(16,24,40,.04), 0 8px 24px -12px rgba(16,24,40,.12)',
      },
      letterSpacing: { tightest: '-.04em' },
    },
  },
};
</script>
<link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
</head>
<body class="font-sans bg-white text-slate-700 antialiased selection:bg-brand-100">

<!-- Announcement bar -->
<div class="bg-ink text-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 py-2.5 flex items-center justify-center gap-2 text-center text-[13px]">
    <span class="hidden sm:inline-flex items-center gap-1.5 font-medium"><?= icon('sparkles','w-3.5 h-3.5 text-brand-300') ?> Admissions open for Batch 2026 — limited seats.</span>
    <span class="text-slate-400 hidden sm:inline">·</span>
    <span class="text-slate-300">Talk to a counsellor</span>
    <a href="tel:<?= e(setting('phone')) ?>" class="font-semibold text-brand-300 hover:text-white transition inline-flex items-center gap-1"><?= icon('phone','w-3.5 h-3.5') ?><?= e(setting('phone')) ?></a>
  </div>
</div>

<!-- Navbar -->
<header id="navbar" class="sticky top-0 z-50 transition-all duration-300 border-b border-transparent">
  <div class="absolute inset-0 bg-white/70 backdrop-blur-xl -z-10" id="navbg"></div>
  <nav class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="flex items-center justify-between h-16 lg:h-[72px]">
      <a href="<?= url('index.php') ?>" class="flex items-center gap-2.5 group shrink-0">
        <span class="grid place-items-center w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-violet-600 text-white font-display font-bold text-lg shadow-glow group-hover:scale-105 transition-transform">N</span>
        <span class="font-display font-bold text-xl tracking-tightest text-ink"><?= e($site) ?></span>
      </a>

      <div class="hidden lg:flex items-center gap-1 text-[15px] font-medium">
        <?php foreach ($navLinks as $key => $l): $active = $key === $page; ?>
          <a href="<?= url($l[1]) ?>" class="relative px-4 py-2 rounded-lg transition <?= $active ? 'text-ink' : 'text-slate-500 hover:text-ink hover:bg-slate-100/70' ?>">
            <?= e($l[0]) ?>
            <?php if ($active): ?><span class="absolute left-4 right-4 -bottom-px h-0.5 rounded-full bg-gradient-to-r from-brand-500 to-violet-600"></span><?php endif; ?>
          </a>
        <?php endforeach; ?>
      </div>

      <div class="hidden lg:flex items-center gap-2">
        <a href="<?= url('student/login.php') ?>" class="text-[15px] font-semibold text-slate-600 hover:text-ink px-4 py-2 rounded-lg hover:bg-slate-100/70 transition">Student Login</a>
        <a href="<?= url('courses.php') ?>" class="btn-shine group inline-flex items-center gap-1.5 text-[15px] font-semibold text-white bg-ink hover:bg-brand-600 px-5 py-2.5 rounded-full shadow-card transition">
          Enroll Now <?= icon('arrow-right','w-4 h-4 group-hover:translate-x-0.5 transition-transform') ?>
        </a>
      </div>

      <button id="menuBtn" class="lg:hidden grid place-items-center w-10 h-10 rounded-lg text-ink hover:bg-slate-100 transition" aria-label="Open menu">
        <?= icon('menu','w-6 h-6') ?>
      </button>
    </div>
  </nav>

  <!-- Mobile menu -->
  <div id="mobileMenu" class="lg:hidden fixed inset-0 z-50 hidden">
    <div id="mmOverlay" class="absolute inset-0 bg-ink/40 backdrop-blur-sm"></div>
    <div id="mmPanel" class="absolute top-0 right-0 h-full w-[82%] max-w-sm bg-white shadow-2xl translate-x-full transition-transform duration-300 flex flex-col">
      <div class="flex items-center justify-between px-5 h-16 border-b border-slate-100">
        <span class="font-display font-bold text-lg text-ink"><?= e($site) ?></span>
        <button id="menuClose" class="grid place-items-center w-10 h-10 rounded-lg hover:bg-slate-100" aria-label="Close menu"><?= icon('x','w-6 h-6') ?></button>
      </div>
      <div class="flex-1 overflow-y-auto px-4 py-4 space-y-1 text-[15px] font-medium">
        <?php foreach ($navLinks as $key => $l): ?>
          <a href="<?= url($l[1]) ?>" class="flex items-center justify-between px-4 py-3 rounded-xl <?= $key===$page ? 'bg-brand-50 text-brand-700' : 'text-slate-600 hover:bg-slate-50' ?>">
            <?= e($l[0]) ?> <?= icon('chevron-right','w-4 h-4 text-slate-300') ?>
          </a>
        <?php endforeach; ?>
      </div>
      <div class="p-4 border-t border-slate-100 space-y-2">
        <a href="<?= url('student/login.php') ?>" class="block text-center px-4 py-3 rounded-xl border border-slate-200 font-semibold text-slate-700">Student Login</a>
        <a href="<?= url('courses.php') ?>" class="block text-center px-4 py-3 rounded-xl bg-ink text-white font-semibold">Enroll Now</a>
        <a href="tel:<?= e(setting('phone')) ?>" class="flex items-center justify-center gap-2 px-4 py-3 text-sm text-slate-500"><?= icon('phone','w-4 h-4') ?><?= e(setting('phone')) ?></a>
      </div>
    </div>
  </div>
</header>

<main>
