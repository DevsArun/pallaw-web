<?php
require_once __DIR__ . '/functions.php';
$page        = $page        ?? '';
$page_title  = $page_title  ?? '';
$site        = setting('site_name', 'Nexora Institute');
$title       = $page_title ? "$page_title · $site" : $site;
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?= e(setting('tagline')) ?>">
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
          500:'#6366f1',600:'#4f46e5',700:'#4338ca',800:'#3730a3',900:'#312e81',
        },
      },
      keyframes: {
        floaty: { '0%,100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-12px)' } },
        shimmer: { '100%': { transform: 'translateX(100%)' } },
        fadeUp: { '0%': { opacity: 0, transform: 'translateY(24px)' }, '100%': { opacity: 1, transform: 'translateY(0)' } },
      },
      animation: {
        floaty: 'floaty 6s ease-in-out infinite',
        fadeUp: 'fadeUp .7s ease-out both',
      },
    },
  },
};
</script>
<link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
</head>
<body class="font-sans bg-white text-slate-800 antialiased">

<!-- Announcement bar -->
<div class="bg-slate-900 text-white text-xs sm:text-sm">
  <div class="max-w-7xl mx-auto px-4 py-2 flex items-center justify-center gap-2 text-center">
    <span class="hidden sm:inline">🎓 Admissions Open 2026 — Limited Seats.</span>
    <span>Talk to a counsellor:</span>
    <a href="tel:<?= e(setting('phone')) ?>" class="font-semibold text-brand-300 hover:text-brand-200"><?= e(setting('phone')) ?></a>
  </div>
</div>

<!-- Navbar -->
<header id="navbar" class="sticky top-0 z-50 transition-all duration-300 bg-white/80 backdrop-blur-lg border-b border-slate-100">
  <nav class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="flex items-center justify-between h-16">
      <a href="<?= url('index.php') ?>" class="flex items-center gap-2.5 group">
        <span class="grid place-items-center w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-violet-600 text-white font-display font-bold text-lg shadow-lg shadow-brand-500/30 group-hover:scale-105 transition">N</span>
        <span class="font-display font-bold text-xl tracking-tight text-slate-900"><?= e($site) ?></span>
      </a>

      <div class="hidden lg:flex items-center gap-8 text-sm font-medium">
        <a href="<?= url('index.php') ?>" class="<?= active_class('home', $page) ?> transition">Home</a>
        <a href="<?= url('courses.php') ?>" class="<?= active_class('courses', $page) ?> transition">Courses</a>
        <a href="<?= url('projects.php') ?>" class="<?= active_class('projects', $page) ?> transition">Projects</a>
        <a href="<?= url('verify.php') ?>" class="<?= active_class('verify', $page) ?> transition">Verify Certificate</a>
        <a href="<?= url('about.php') ?>" class="<?= active_class('about', $page) ?> transition">About</a>
        <a href="<?= url('contact.php') ?>" class="<?= active_class('contact', $page) ?> transition">Contact</a>
      </div>

      <div class="hidden lg:flex items-center gap-3">
        <a href="<?= url('student/login.php') ?>" class="text-sm font-semibold text-slate-700 hover:text-brand-600 px-4 py-2 transition">Student Login</a>
        <a href="<?= url('courses.php') ?>" class="text-sm font-semibold text-white bg-slate-900 hover:bg-brand-600 px-5 py-2.5 rounded-full shadow-sm transition">Enroll Now</a>
      </div>

      <button id="menuBtn" class="lg:hidden grid place-items-center w-10 h-10 rounded-lg hover:bg-slate-100" aria-label="Menu">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
    </div>
  </nav>

  <!-- Mobile menu -->
  <div id="mobileMenu" class="lg:hidden hidden border-t border-slate-100 bg-white">
    <div class="px-4 py-4 space-y-1 text-sm font-medium">
      <a href="<?= url('index.php') ?>" class="block px-3 py-2.5 rounded-lg hover:bg-slate-50">Home</a>
      <a href="<?= url('courses.php') ?>" class="block px-3 py-2.5 rounded-lg hover:bg-slate-50">Courses</a>
      <a href="<?= url('projects.php') ?>" class="block px-3 py-2.5 rounded-lg hover:bg-slate-50">Projects</a>
      <a href="<?= url('verify.php') ?>" class="block px-3 py-2.5 rounded-lg hover:bg-slate-50">Verify Certificate</a>
      <a href="<?= url('about.php') ?>" class="block px-3 py-2.5 rounded-lg hover:bg-slate-50">About</a>
      <a href="<?= url('contact.php') ?>" class="block px-3 py-2.5 rounded-lg hover:bg-slate-50">Contact</a>
      <div class="pt-3 flex gap-2">
        <a href="<?= url('student/login.php') ?>" class="flex-1 text-center px-4 py-2.5 rounded-full border border-slate-200 font-semibold">Student Login</a>
        <a href="<?= url('courses.php') ?>" class="flex-1 text-center px-4 py-2.5 rounded-full bg-slate-900 text-white font-semibold">Enroll</a>
      </div>
    </div>
  </div>
</header>

<main>
