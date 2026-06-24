<?php
require_once __DIR__ . '/includes/functions.php';
$page = 'about';
$page_title = 'About Us';
include __DIR__ . '/includes/header.php';
?>

<section class="bg-slate-950 text-white relative overflow-hidden">
  <div class="hero-aurora"><span></span><span></span><span></span></div>
  <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-16 lg:py-24">
    <div class="max-w-3xl">
      <span class="text-sm font-semibold text-brand-400 uppercase tracking-wider">About <?= e(setting('site_name')) ?></span>
      <h1 class="mt-3 font-display text-4xl sm:text-5xl font-bold leading-tight">We turn ambition into <span class="text-gradient">employable skills.</span></h1>
      <p class="mt-6 text-lg text-slate-300"><?= e(setting('about_short')) ?></p>
    </div>
  </div>
</section>

<!-- Mission / Vision -->
<section class="bg-white py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 grid md:grid-cols-2 gap-6">
    <div class="reveal rounded-2xl border border-slate-100 p-8 bg-slate-50">
      <span class="text-3xl">🎯</span>
      <h2 class="mt-4 font-display text-2xl font-bold text-slate-900">Our Mission</h2>
      <p class="mt-3 text-slate-600 leading-relaxed">To make world-class, practical education accessible — equipping every learner with the real skills, projects and confidence needed to launch a thriving career.</p>
    </div>
    <div class="reveal rounded-2xl border border-slate-100 p-8 bg-slate-50">
      <span class="text-3xl">🚀</span>
      <h2 class="mt-4 font-display text-2xl font-bold text-slate-900">Our Vision</h2>
      <p class="mt-3 text-slate-600 leading-relaxed">To be the most trusted skill-development institute, recognized globally for the quality of our graduates and the strength of our industry network.</p>
    </div>
  </div>
</section>

<!-- Values -->
<section class="bg-slate-50 py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="text-center max-w-2xl mx-auto reveal">
      <span class="text-sm font-semibold text-brand-600 uppercase tracking-wider">Our Values</span>
      <h2 class="mt-3 font-display text-3xl sm:text-4xl font-bold text-slate-900">What we stand for</h2>
    </div>
    <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
      <?php foreach ([
        ['💡','Practical First','Learn by building real things, not memorizing slides.'],
        ['🤝','Mentorship','Small batches with mentors who genuinely care.'],
        ['📈','Outcome Driven','Everything maps to a career outcome.'],
        ['🌍','Integrity','Honest guidance and verified credentials.'],
      ] as $v): ?>
        <div class="reveal card-lift rounded-2xl bg-white border border-slate-100 p-6">
          <span class="text-3xl"><?= $v[0] ?></span>
          <h3 class="mt-4 font-semibold text-slate-900"><?= $v[1] ?></h3>
          <p class="mt-2 text-sm text-slate-500"><?= $v[2] ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Stats band -->
<section class="bg-slate-950 text-white py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 grid grid-cols-2 lg:grid-cols-4 gap-6 text-center">
    <div><p class="font-display text-4xl font-bold"><?= e(setting('hero_stat_students')) ?></p><p class="mt-1 text-sm text-slate-400">Students Trained</p></div>
    <div><p class="font-display text-4xl font-bold"><?= e(setting('hero_stat_courses')) ?></p><p class="mt-1 text-sm text-slate-400">Courses</p></div>
    <div><p class="font-display text-4xl font-bold"><?= e(setting('hero_stat_partners')) ?></p><p class="mt-1 text-sm text-slate-400">Hiring Partners</p></div>
    <div><p class="font-display text-4xl font-bold"><?= e(setting('hero_stat_rating')) ?></p><p class="mt-1 text-sm text-slate-400">Learner Rating</p></div>
  </div>
</section>

<!-- CTA -->
<section class="bg-white py-20">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 text-center reveal">
    <h2 class="font-display text-3xl sm:text-4xl font-bold text-slate-900">Join thousands of successful graduates</h2>
    <p class="mt-4 text-slate-500">Your career upgrade starts with a single conversation.</p>
    <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
      <a href="<?= url('courses.php') ?>" class="px-7 py-3.5 rounded-full bg-slate-900 text-white font-semibold hover:bg-brand-600 transition">Browse Courses</a>
      <a href="<?= url('contact.php') ?>" class="px-7 py-3.5 rounded-full border border-slate-200 font-semibold text-slate-700 hover:border-brand-300 transition">Contact Us</a>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
