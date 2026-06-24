<?php
require_once __DIR__ . '/includes/functions.php';
$page = 'about';
$page_title = 'About Us';
include __DIR__ . '/includes/header.php';
?>

<section class="relative overflow-hidden bg-ink text-white">
  <div class="hero-aurora"><span></span><span></span><span></span></div>
  <div class="absolute inset-0 bg-grid"></div>
  <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-16 lg:py-24">
    <div class="max-w-3xl">
      <span class="inline-flex items-center gap-2 text-sm font-semibold text-brand-400"><?= icon('building','w-4 h-4') ?> About <?= e(setting('site_name')) ?></span>
      <h1 class="mt-3 font-display text-4xl sm:text-5xl font-bold leading-[1.08] tracking-tightest">We turn ambition into <span class="text-gradient">employable skills.</span></h1>
      <p class="mt-6 text-lg text-slate-300"><?= e(setting('about_short')) ?></p>
    </div>
  </div>
</section>

<section class="bg-white py-20 lg:py-24">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 grid md:grid-cols-2 gap-6">
    <div class="reveal rounded-2xl border border-slate-100 p-8 bg-slate-50">
      <span class="grid place-items-center w-12 h-12 rounded-xl bg-brand-600 text-white"><?= icon('target','w-6 h-6') ?></span>
      <h2 class="mt-5 font-display text-2xl font-bold text-ink tracking-tightest">Our Mission</h2>
      <p class="mt-3 text-slate-600 leading-relaxed">To make world-class, practical education accessible — equipping every learner with the real skills, projects and confidence to launch a thriving career.</p>
    </div>
    <div class="reveal rounded-2xl border border-slate-100 p-8 bg-slate-50" data-delay="1">
      <span class="grid place-items-center w-12 h-12 rounded-xl bg-violet-600 text-white"><?= icon('rocket','w-6 h-6') ?></span>
      <h2 class="mt-5 font-display text-2xl font-bold text-ink tracking-tightest">Our Vision</h2>
      <p class="mt-3 text-slate-600 leading-relaxed">To be the most trusted skill-development institute, recognized globally for the quality of our graduates and the strength of our industry network.</p>
    </div>
  </div>
</section>

<section class="bg-slate-50 py-20 lg:py-24">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="text-center max-w-2xl mx-auto reveal">
      <span class="inline-flex items-center gap-2 text-sm font-semibold text-brand-600"><?= icon('heart','w-4 h-4') ?> Our Values</span>
      <h2 class="mt-3 font-display text-3xl sm:text-[40px] font-bold text-ink tracking-tightest">What we stand for</h2>
    </div>
    <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
      <?php foreach ([
        ['sparkles','Practical First','Learn by building real things, not memorizing slides.'],
        ['users','Mentorship','Small batches with mentors who genuinely care.'],
        ['trophy','Outcome Driven','Everything maps to a career outcome.'],
        ['shield-check','Integrity','Honest guidance and verified credentials.'],
      ] as $i => $v): ?>
        <div class="reveal card-lift rounded-2xl bg-white border border-slate-100 p-6 shadow-card" data-delay="<?= $i ?>">
          <span class="grid place-items-center w-12 h-12 rounded-xl bg-brand-50 text-brand-600"><?= icon($v[0],'w-6 h-6') ?></span>
          <h3 class="mt-4 font-semibold text-ink"><?= $v[1] ?></h3>
          <p class="mt-2 text-sm text-slate-500"><?= $v[2] ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section class="bg-ink text-white py-16 relative overflow-hidden">
  <div class="absolute inset-0 bg-grid"></div>
  <div class="relative max-w-7xl mx-auto px-4 sm:px-6 grid grid-cols-2 lg:grid-cols-4 gap-6 text-center">
    <?php foreach ([['hero_stat_students','Students Trained'],['hero_stat_courses','Courses'],['hero_stat_partners','Hiring Partners'],['hero_stat_rating','Learner Rating']] as $s): ?>
      <div class="reveal"><p class="font-display text-4xl font-bold tracking-tightest"><?= e(setting($s[0])) ?></p><p class="mt-1 text-sm text-slate-400"><?= $s[1] ?></p></div>
    <?php endforeach; ?>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
