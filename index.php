<?php
require_once __DIR__ . '/includes/functions.php';
$page = 'home';

// Featured courses from DB
$courses = db()->query(
  "SELECT c.*, cat.name AS category FROM courses c
   LEFT JOIN categories cat ON cat.id = c.category_id
   WHERE c.status='active' ORDER BY c.is_featured DESC, c.id ASC LIMIT 6"
)->fetchAll();

$categories = db()->query("SELECT * FROM categories ORDER BY name")->fetchAll();
$projects   = db()->query("SELECT * FROM projects WHERE status='published' ORDER BY id DESC LIMIT 3")->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<!-- ============ HERO ============ -->
<section class="relative overflow-hidden bg-slate-950 text-white">
  <div class="hero-aurora"><span></span><span></span><span></span></div>
  <div class="absolute inset-0 bg-grid opacity-40"></div>
  <div class="relative max-w-7xl mx-auto px-4 sm:px-6 pt-20 pb-24 lg:pt-28 lg:pb-32">
    <div class="grid lg:grid-cols-2 gap-12 items-center">
      <div class="animate-fadeUp">
        <span class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 border border-white/15 text-xs font-medium backdrop-blur">
          <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
          Admissions Open · Batch 2026
        </span>
        <h1 class="mt-6 font-display text-4xl sm:text-5xl lg:text-6xl font-bold leading-[1.05] tracking-tight">
          Learn skills that <span class="text-gradient">get you hired.</span>
        </h1>
        <p class="mt-6 text-lg text-slate-300 max-w-xl leading-relaxed">
          <?= e(setting('about_short')) ?>
        </p>
        <div class="mt-8 flex flex-col sm:flex-row gap-3">
          <a href="<?= url('courses.php') ?>" class="inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-full bg-white text-slate-900 font-semibold hover:bg-brand-50 transition shadow-lg shadow-white/10">
            Explore Courses
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 12h14M13 6l6 6-6 6"/></svg>
          </a>
          <a href="<?= url('contact.php') ?>" class="inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-full bg-white/10 border border-white/20 font-semibold hover:bg-white/15 transition backdrop-blur">
            Book Free Demo
          </a>
        </div>
        <div class="mt-10 flex items-center gap-6 text-sm text-slate-400">
          <div class="flex -space-x-2">
            <?php foreach (['f59e0b','6366f1','ec4899','10b981'] as $c): ?>
              <span class="w-9 h-9 rounded-full border-2 border-slate-950" style="background:#<?= $c ?>"></span>
            <?php endforeach; ?>
          </div>
          <p>Joined by <span class="text-white font-semibold"><?= e(setting('hero_stat_students')) ?></span> learners</p>
        </div>
      </div>

      <!-- Floating glass card -->
      <div class="relative animate-fadeUp" style="animation-delay:.15s">
        <div class="relative rounded-3xl bg-white/5 border border-white/10 backdrop-blur-xl p-6 shadow-2xl animate-floaty">
          <div class="flex items-center justify-between mb-5">
            <span class="text-xs uppercase tracking-wider text-slate-400">Top Rated Program</span>
            <span class="px-2.5 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-semibold">★ <?= e(setting('hero_stat_rating')) ?></span>
          </div>
          <?php $f = $courses[0] ?? null; if ($f): ?>
          <h3 class="font-display text-2xl font-bold text-white"><?= e($f['title']) ?></h3>
          <p class="mt-2 text-sm text-slate-300"><?= e($f['short_desc']) ?></p>
          <div class="mt-5 grid grid-cols-3 gap-3 text-center">
            <div class="rounded-xl bg-white/5 p-3"><p class="text-lg font-bold text-white"><?= e($f['duration']) ?></p><p class="text-[11px] text-slate-400">Duration</p></div>
            <div class="rounded-xl bg-white/5 p-3"><p class="text-lg font-bold text-white"><?= e($f['level']) ?></p><p class="text-[11px] text-slate-400">Level</p></div>
            <div class="rounded-xl bg-white/5 p-3"><p class="text-lg font-bold text-emerald-300"><?= money($f['discount_price'] ?: $f['price']) ?></p><p class="text-[11px] text-slate-400">Fee</p></div>
          </div>
          <a href="<?= url('course.php?slug=' . urlencode($f['slug'])) ?>" class="mt-5 block text-center px-5 py-3 rounded-xl bg-gradient-to-r from-brand-500 to-violet-600 font-semibold hover:opacity-90 transition">View Program</a>
          <?php endif; ?>
        </div>
        <div class="absolute -top-5 -right-3 px-3 py-2 rounded-xl bg-white text-slate-900 text-xs font-bold shadow-xl animate-floaty" style="animation-delay:-3s">🎓 Verified Certificate</div>
      </div>
    </div>
  </div>

  <!-- Trust marquee -->
  <div class="relative border-t border-white/10 py-6 overflow-hidden">
    <div class="marquee">
      <?php $brands = ['Autodesk','Microsoft','Google','SAP','Adobe','Coursera','Autodesk','Microsoft','Google','SAP','Adobe','Coursera']; foreach ($brands as $b): ?>
        <span class="text-xl font-display font-semibold text-white/30 whitespace-nowrap"><?= $b ?></span>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ STATS ============ -->
<section class="bg-white py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 grid grid-cols-2 lg:grid-cols-4 gap-6">
    <?php
      $stats = [
        ['12500','+','Students Trained'],
        ['40','+','Expert-Led Courses'],
        ['200','+','Hiring Partners'],
        ['98','%','Placement Support'],
      ];
      foreach ($stats as $s): ?>
      <div class="reveal text-center p-6 rounded-2xl bg-slate-50 border border-slate-100">
        <p class="font-display text-4xl font-bold text-slate-900"><span data-count="<?= $s[0] ?>" data-suffix="<?= $s[1] ?>">0</span></p>
        <p class="mt-2 text-sm text-slate-500"><?= $s[2] ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ============ CATEGORIES ============ -->
<section class="bg-slate-50 py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="text-center max-w-2xl mx-auto reveal">
      <span class="text-sm font-semibold text-brand-600 uppercase tracking-wider">Categories</span>
      <h2 class="mt-3 font-display text-3xl sm:text-4xl font-bold text-slate-900">Find your path</h2>
      <p class="mt-4 text-slate-500">Hands-on, project-based tracks designed with industry mentors.</p>
    </div>
    <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
      <?php foreach ($categories as $i => $cat): ?>
        <a href="<?= url('courses.php?cat=' . urlencode($cat['slug'])) ?>" class="reveal card-lift group p-6 rounded-2xl bg-white border border-slate-100 flex items-start gap-4">
          <span class="grid place-items-center w-12 h-12 rounded-xl bg-gradient-to-br from-brand-500 to-violet-600 text-white text-xl shrink-0">◆</span>
          <div>
            <h3 class="font-semibold text-slate-900 group-hover:text-brand-600 transition"><?= e($cat['name']) ?></h3>
            <p class="mt-1 text-sm text-slate-500">Explore industry-ready courses in <?= e($cat['name']) ?>.</p>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ FEATURED COURSES ============ -->
<section class="bg-white py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="flex flex-wrap items-end justify-between gap-4 reveal">
      <div>
        <span class="text-sm font-semibold text-brand-600 uppercase tracking-wider">Popular Programs</span>
        <h2 class="mt-3 font-display text-3xl sm:text-4xl font-bold text-slate-900">Featured Courses</h2>
      </div>
      <a href="<?= url('courses.php') ?>" class="text-sm font-semibold text-brand-600 hover:text-brand-700">View all courses →</a>
    </div>

    <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($courses as $c):
        $price = $c['discount_price'] ?: $c['price'];
        $hasDiscount = $c['discount_price'] && $c['discount_price'] < $c['price'];
      ?>
        <article class="reveal card-lift group rounded-2xl overflow-hidden bg-white border border-slate-100 flex flex-col">
          <div class="relative h-44 bg-gradient-to-br from-brand-500 via-violet-600 to-fuchsia-600 p-5 flex flex-col justify-between">
            <div class="flex items-center justify-between">
              <span class="px-2.5 py-1 rounded-full bg-white/20 text-white text-xs font-medium backdrop-blur"><?= e($c['category'] ?? 'Course') ?></span>
              <?php if ($c['is_featured']): ?><span class="px-2.5 py-1 rounded-full bg-amber-400 text-amber-950 text-xs font-bold">★ Featured</span><?php endif; ?>
            </div>
            <h3 class="font-display text-xl font-bold text-white leading-tight"><?= e($c['title']) ?></h3>
          </div>
          <div class="p-5 flex flex-col flex-1">
            <p class="text-sm text-slate-500 line-clamp-2"><?= e($c['short_desc']) ?></p>
            <div class="mt-4 flex flex-wrap gap-2 text-xs">
              <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">⏱ <?= e($c['duration']) ?></span>
              <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">📶 <?= e($c['level']) ?></span>
            </div>
            <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between">
              <div>
                <?php if ($hasDiscount): ?><span class="text-xs text-slate-400 line-through"><?= money($c['price']) ?></span><?php endif; ?>
                <p class="font-display text-2xl font-bold text-slate-900"><?= money($price) ?></p>
              </div>
              <a href="<?= url('course.php?slug=' . urlencode($c['slug'])) ?>" class="px-4 py-2.5 rounded-full bg-slate-900 text-white text-sm font-semibold hover:bg-brand-600 transition">Details</a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ WHY US ============ -->
<section class="bg-slate-950 text-white py-20 relative overflow-hidden">
  <div class="absolute inset-0 bg-grid opacity-30"></div>
  <div class="relative max-w-7xl mx-auto px-4 sm:px-6">
    <div class="grid lg:grid-cols-2 gap-14 items-center">
      <div class="reveal">
        <span class="text-sm font-semibold text-brand-400 uppercase tracking-wider">Why Nexora</span>
        <h2 class="mt-3 font-display text-3xl sm:text-4xl font-bold">Built for outcomes, not just lectures.</h2>
        <p class="mt-4 text-slate-300">Every program blends mentor-led training, real projects and a verified certificate that employers trust.</p>
        <div class="mt-8 space-y-5">
          <?php
            $why = [
              ['🎯','Industry-aligned curriculum','Designed with hiring partners and updated every quarter.'],
              ['🛠️','Real, portfolio-ready projects','Graduate with work you can actually show recruiters.'],
              ['📜','Verified digital certificates','Each certificate carries a unique code anyone can verify online.'],
              ['🤝','Dedicated placement support','Resume reviews, mock interviews and 200+ hiring partners.'],
            ];
            foreach ($why as $w): ?>
            <div class="flex gap-4">
              <span class="grid place-items-center w-11 h-11 rounded-xl bg-white/10 text-xl shrink-0"><?= $w[0] ?></span>
              <div><h3 class="font-semibold"><?= $w[1] ?></h3><p class="text-sm text-slate-400 mt-1"><?= $w[2] ?></p></div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <div class="reveal grid grid-cols-2 gap-4">
        <div class="rounded-2xl bg-gradient-to-br from-brand-500 to-violet-600 p-6"><p class="font-display text-4xl font-bold"><?= e(setting('hero_stat_rating')) ?></p><p class="mt-2 text-sm text-white/80">Average learner rating</p></div>
        <div class="rounded-2xl bg-white/5 border border-white/10 p-6 mt-8"><p class="font-display text-4xl font-bold"><?= e(setting('hero_stat_partners')) ?></p><p class="mt-2 text-sm text-slate-400">Hiring partners</p></div>
        <div class="rounded-2xl bg-white/5 border border-white/10 p-6"><p class="font-display text-4xl font-bold"><?= e(setting('hero_stat_courses')) ?></p><p class="mt-2 text-sm text-slate-400">Live courses</p></div>
        <div class="rounded-2xl bg-gradient-to-br from-fuchsia-500 to-pink-600 p-6 mt-8"><p class="font-display text-4xl font-bold"><?= e(setting('hero_stat_students')) ?></p><p class="mt-2 text-sm text-white/80">Students trained</p></div>
      </div>
    </div>
  </div>
</section>

<!-- ============ PROJECTS PREVIEW ============ -->
<?php if ($projects): ?>
<section class="bg-white py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="text-center max-w-2xl mx-auto reveal">
      <span class="text-sm font-semibold text-brand-600 uppercase tracking-wider">Student Work</span>
      <h2 class="mt-3 font-display text-3xl sm:text-4xl font-bold text-slate-900">Projects that speak louder than grades</h2>
    </div>
    <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($projects as $p): ?>
        <div class="reveal card-lift rounded-2xl border border-slate-100 overflow-hidden">
          <div class="h-40 bg-gradient-to-br from-slate-800 to-slate-950 grid place-items-center text-white/30 text-5xl">⌘</div>
          <div class="p-5">
            <h3 class="font-semibold text-slate-900"><?= e($p['title']) ?></h3>
            <p class="mt-2 text-sm text-slate-500 line-clamp-2"><?= e($p['description']) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="text-center mt-10"><a href="<?= url('projects.php') ?>" class="inline-flex px-6 py-3 rounded-full bg-slate-900 text-white font-semibold hover:bg-brand-600 transition">See all projects</a></div>
  </div>
</section>
<?php endif; ?>

<!-- ============ TESTIMONIALS ============ -->
<section class="bg-slate-50 py-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="text-center max-w-2xl mx-auto reveal">
      <span class="text-sm font-semibold text-brand-600 uppercase tracking-wider">Testimonials</span>
      <h2 class="mt-3 font-display text-3xl sm:text-4xl font-bold text-slate-900">Loved by learners</h2>
    </div>
    <div class="mt-12 grid md:grid-cols-3 gap-6">
      <?php
        $tst = [
          ['Priya S.','AutoCAD Professional','The projects made me job-ready. Got placed within a month of finishing my course.'],
          ['Rahul M.','Full Stack Web Dev','Mentors are incredible. I shipped 3 real apps and now work as a developer.'],
          ['Aisha K.','SAP FICO','Clear, practical and well-structured. The certificate genuinely opened doors.'],
        ];
        foreach ($tst as $t): ?>
        <figure class="reveal rounded-2xl bg-white border border-slate-100 p-6">
          <div class="text-amber-400">★★★★★</div>
          <blockquote class="mt-4 text-slate-600 leading-relaxed">"<?= $t[2] ?>"</blockquote>
          <figcaption class="mt-5 flex items-center gap-3">
            <span class="grid place-items-center w-10 h-10 rounded-full bg-gradient-to-br from-brand-500 to-violet-600 text-white font-bold"><?= e($t[0][0]) ?></span>
            <div><p class="font-semibold text-slate-900 text-sm"><?= $t[0] ?></p><p class="text-xs text-slate-400"><?= $t[1] ?></p></div>
          </figcaption>
        </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ FAQ ============ -->
<section class="bg-white py-20">
  <div class="max-w-3xl mx-auto px-4 sm:px-6">
    <div class="text-center reveal">
      <span class="text-sm font-semibold text-brand-600 uppercase tracking-wider">FAQ</span>
      <h2 class="mt-3 font-display text-3xl sm:text-4xl font-bold text-slate-900">Questions, answered</h2>
    </div>
    <div class="mt-10 space-y-3">
      <?php
        $faqs = [
          ['Do I get a certificate after completing a course?','Yes. Every course ends with a verified digital certificate carrying a unique code that anyone can validate on our Verify page.'],
          ['Are the courses beginner friendly?','Absolutely. Most programs start from the fundamentals and progress to advanced, real-world projects.'],
          ['Can I pay the fee in installments?','Yes. Flexible installment options are available — our admin team generates an official fee receipt for every payment.'],
          ['Is placement support included?','We provide resume building, mock interviews and access to 200+ hiring partners.'],
        ];
        foreach ($faqs as $q): ?>
        <div class="reveal rounded-2xl border border-slate-100 overflow-hidden">
          <button data-faq class="w-full flex items-center justify-between gap-4 px-5 py-4 text-left font-semibold text-slate-900 hover:bg-slate-50">
            <span><?= $q[0] ?></span>
            <span data-faq-icon class="text-brand-600 text-xl transition-transform shrink-0">+</span>
          </button>
          <div class="hidden px-5 pb-5 text-sm text-slate-500 leading-relaxed"><?= $q[1] ?></div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ CTA ============ -->
<section class="bg-white pb-20">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="reveal relative overflow-hidden rounded-3xl bg-slate-950 px-6 py-14 sm:px-12 text-center">
      <div class="hero-aurora"><span></span><span></span><span></span></div>
      <div class="relative">
        <h2 class="font-display text-3xl sm:text-4xl font-bold text-white">Ready to start your journey?</h2>
        <p class="mt-4 text-slate-300 max-w-xl mx-auto">Talk to a counsellor today and find the perfect program for your goals.</p>
        <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
          <a href="tel:<?= e(setting('phone')) ?>" class="px-7 py-3.5 rounded-full bg-white text-slate-900 font-semibold hover:bg-brand-50 transition">📞 <?= e(setting('phone')) ?></a>
          <a href="<?= url('courses.php') ?>" class="px-7 py-3.5 rounded-full bg-gradient-to-r from-brand-500 to-violet-600 text-white font-semibold hover:opacity-90 transition">Browse Courses</a>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
