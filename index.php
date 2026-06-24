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

$catIcons = ['cad-design'=>'compass','software-it'=>'code','data-ai'=>'cpu','business-sap'=>'briefcase','digital-marketing'=>'megaphone'];
?>

<!-- ============ HERO ============ -->
<section class="relative overflow-hidden bg-ink text-white">
  <div class="hero-aurora"><span></span><span></span><span></span></div>
  <div class="absolute inset-0 bg-grid"></div>
  <div class="relative max-w-7xl mx-auto px-4 sm:px-6 pt-16 pb-20 lg:pt-24 lg:pb-28">
    <div class="grid lg:grid-cols-12 gap-12 items-center">
      <div class="lg:col-span-6 animate-fadeUp">
        <span class="inline-flex items-center gap-2 pl-1.5 pr-3 py-1.5 rounded-full bg-white/5 border border-white/10 text-[13px] font-medium backdrop-blur">
          <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-emerald-400/20 text-emerald-300 pulse-ring"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span></span>
          Trusted by <?= e(setting('hero_stat_students')) ?> learners
        </span>
        <h1 class="mt-6 font-display text-[40px] leading-[1.04] sm:text-6xl font-bold tracking-tightest">
          Learn the skills that <span class="text-gradient">get you hired.</span>
        </h1>
        <p class="mt-6 text-lg text-slate-300 max-w-xl leading-relaxed">
          Mentor-led, project-based programs in design, engineering &amp; IT — built with hiring partners and backed by a verified certificate.
        </p>
        <div class="mt-8 flex flex-col sm:flex-row gap-3">
          <a href="<?= url('courses.php') ?>" class="btn-shine group inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-full bg-white text-ink font-semibold hover:bg-brand-50 transition shadow-lg shadow-black/20">
            Explore Courses <?= icon('arrow-right','w-4 h-4 group-hover:translate-x-0.5 transition-transform') ?>
          </a>
          <a href="<?= url('contact.php') ?>" class="inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-full bg-white/10 border border-white/15 font-semibold hover:bg-white/15 transition backdrop-blur">
            <?= icon('video','w-4 h-4') ?> Book Free Demo
          </a>
        </div>
        <div class="mt-10 flex flex-wrap items-center gap-x-8 gap-y-4">
          <div class="flex items-center gap-3">
            <div class="flex -space-x-2.5">
              <?php foreach (['f59e0b','6366f1','ec4899','10b981'] as $c): ?>
                <span class="w-9 h-9 rounded-full border-2 border-ink ring-1 ring-white/10" style="background:linear-gradient(135deg,#<?= $c ?>,#<?= $c ?>aa)"></span>
              <?php endforeach; ?>
            </div>
            <div class="text-sm">
              <div class="flex items-center gap-1 text-amber-400"><?php for($i=0;$i<5;$i++) echo icon('star','w-3.5 h-3.5'); ?></div>
              <p class="text-slate-400 text-xs mt-0.5"><?= e(setting('hero_stat_rating')) ?> average rating</p>
            </div>
          </div>
          <div class="h-8 w-px bg-white/10 hidden sm:block"></div>
          <div class="flex items-center gap-2 text-sm text-slate-300"><?= icon('shield-check','w-5 h-5 text-emerald-400') ?> Verified certificates</div>
        </div>
      </div>

      <!-- Hero product card -->
      <div class="lg:col-span-6 animate-fadeUp" style="animation-delay:.12s">
        <div class="relative" data-tilt>
          <div class="absolute -inset-4 bg-gradient-to-tr from-brand-500/30 to-fuchsia-500/20 blur-2xl rounded-[40px]"></div>
          <div class="relative rounded-3xl bg-white/[.06] border border-white/10 backdrop-blur-xl p-5 shadow-2xl">
            <div class="flex items-center justify-between mb-4">
              <div class="flex items-center gap-2">
                <span class="grid place-items-center w-9 h-9 rounded-lg bg-gradient-to-br from-brand-500 to-violet-600 text-sm font-display font-bold">N</span>
                <span class="text-sm font-semibold">Student Dashboard</span>
              </div>
              <span class="flex gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-white/20"></span><span class="w-2.5 h-2.5 rounded-full bg-white/20"></span><span class="w-2.5 h-2.5 rounded-full bg-emerald-400/70"></span></span>
            </div>
            <?php $f = $courses[0] ?? null; if ($f): $fp = $f['discount_price'] ?: $f['price']; ?>
            <div class="rounded-2xl bg-gradient-to-br from-brand-600 to-violet-700 p-5">
              <div class="flex items-center justify-between text-xs text-white/70">
                <span class="px-2 py-1 rounded-md bg-white/15"><?= e($f['category'] ?? 'Course') ?></span>
                <span class="inline-flex items-center gap-1"><?= icon('star','w-3 h-3 text-amber-300') ?> <?= e(setting('hero_stat_rating')) ?></span>
              </div>
              <h3 class="mt-3 font-display text-xl font-bold"><?= e($f['title']) ?></h3>
              <div class="mt-4 h-1.5 rounded-full bg-white/20 overflow-hidden"><span class="block h-full w-[72%] rounded-full bg-white"></span></div>
              <p class="mt-1.5 text-[11px] text-white/70">72% completed</p>
            </div>
            <div class="grid grid-cols-3 gap-3 mt-4">
              <?php
                $mini = [['clock',$f['duration'],'Duration'],['signal',$f['level'],'Level'],['wallet',money($fp),'Fee']];
                foreach ($mini as $m): ?>
                <div class="rounded-xl bg-white/[.04] border border-white/5 p-3">
                  <span class="text-brand-300"><?= icon($m[0],'w-4 h-4') ?></span>
                  <p class="mt-2 text-sm font-bold leading-none"><?= e($m[1]) ?></p>
                  <p class="text-[10px] text-slate-400 mt-1"><?= $m[2] ?></p>
                </div>
              <?php endforeach; ?>
            </div>
            <a href="<?= url('course.php?slug=' . urlencode($f['slug'])) ?>" class="mt-4 flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-white text-ink text-sm font-semibold hover:bg-brand-50 transition">View Program <?= icon('arrow-up-right','w-4 h-4') ?></a>
            <?php endif; ?>
          </div>
          <div class="absolute -bottom-4 -left-4 px-4 py-3 rounded-2xl bg-white text-ink shadow-xl animate-floaty flex items-center gap-2.5" style="animation-delay:-2s">
            <span class="grid place-items-center w-9 h-9 rounded-xl bg-emerald-100 text-emerald-600"><?= icon('award','w-5 h-5') ?></span>
            <div class="text-left"><p class="text-xs font-bold leading-none">Certificate</p><p class="text-[10px] text-slate-400 mt-1">Issued &amp; verifiable</p></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Logo cloud -->
  <div class="relative border-t border-white/10 py-7">
    <p class="text-center text-xs uppercase tracking-[0.2em] text-slate-500 mb-5">Our learners work with industry tools from</p>
    <div class="marquee-wrap overflow-hidden">
      <div class="marquee">
        <?php $brands = ['Autodesk','Microsoft','Google','SAP','Adobe','SolidWorks','Python','AWS']; foreach (array_merge($brands,$brands) as $b): ?>
          <span class="text-lg font-display font-semibold text-white/35 whitespace-nowrap"><?= $b ?></span>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>

<!-- ============ STATS ============ -->
<section class="bg-white py-16 lg:py-20 border-b border-slate-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 grid grid-cols-2 lg:grid-cols-4 gap-px bg-slate-100 rounded-3xl overflow-hidden border border-slate-100">
    <?php
      $stats = [
        ['12500','+','Students trained','users'],
        ['40','+','Expert-led courses','book-open'],
        ['200','+','Hiring partners','building'],
        ['98','%','Placement support','trophy'],
      ];
      foreach ($stats as $i => $s): ?>
      <div class="reveal bg-white p-7 text-center" data-delay="<?= $i ?>">
        <span class="inline-grid place-items-center w-11 h-11 rounded-xl bg-brand-50 text-brand-600 mb-3"><?= icon($s[3],'w-5 h-5') ?></span>
        <p class="font-display text-4xl font-bold text-ink tracking-tightest"><span data-count="<?= $s[0] ?>" data-suffix="<?= $s[1] ?>">0</span></p>
        <p class="mt-1.5 text-sm text-slate-500"><?= $s[2] ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- ============ CATEGORIES ============ -->
<section class="bg-slate-50 py-20 lg:py-24">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="max-w-2xl reveal">
      <span class="inline-flex items-center gap-2 text-sm font-semibold text-brand-600"><?= icon('layers','w-4 h-4') ?> Categories</span>
      <h2 class="mt-3 font-display text-3xl sm:text-[40px] font-bold text-ink tracking-tightest leading-tight">Find the path that fits your ambition</h2>
      <p class="mt-4 text-slate-500 text-lg">Hands-on tracks designed with industry mentors, from CAD to AI.</p>
    </div>
    <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
      <?php foreach ($categories as $i => $cat): $ic = $catIcons[$cat['slug']] ?? 'graduation'; ?>
        <a href="<?= url('courses.php?cat=' . urlencode($cat['slug'])) ?>" class="reveal card-lift group relative p-6 rounded-2xl bg-white border border-slate-100 overflow-hidden" data-delay="<?= $i % 3 ?>">
          <div class="absolute -right-8 -top-8 w-28 h-28 rounded-full bg-brand-50 group-hover:bg-brand-100 transition"></div>
          <div class="relative">
            <span class="grid place-items-center w-12 h-12 rounded-xl bg-gradient-to-br from-brand-500 to-violet-600 text-white shadow-glow"><?= icon($ic,'w-6 h-6') ?></span>
            <h3 class="mt-5 font-display text-lg font-bold text-ink group-hover:text-brand-600 transition"><?= e($cat['name']) ?></h3>
            <p class="mt-1.5 text-sm text-slate-500">Explore industry-ready courses in <?= e($cat['name']) ?>.</p>
            <span class="mt-4 inline-flex items-center gap-1 text-sm font-semibold text-brand-600">Browse <?= icon('arrow-right','w-4 h-4 group-hover:translate-x-1 transition-transform') ?></span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ FEATURED COURSES ============ -->
<section class="bg-white py-20 lg:py-24">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="flex flex-wrap items-end justify-between gap-4 reveal">
      <div class="max-w-xl">
        <span class="inline-flex items-center gap-2 text-sm font-semibold text-brand-600"><?= icon('sparkles','w-4 h-4') ?> Popular programs</span>
        <h2 class="mt-3 font-display text-3xl sm:text-[40px] font-bold text-ink tracking-tightest">Featured courses</h2>
      </div>
      <a href="<?= url('courses.php') ?>" class="inline-flex items-center gap-1.5 text-sm font-semibold text-ink hover:text-brand-600 transition">View all courses <?= icon('arrow-right','w-4 h-4') ?></a>
    </div>

    <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($courses as $i => $c):
        $price = $c['discount_price'] ?: $c['price'];
        $hasDiscount = $c['discount_price'] && $c['discount_price'] < $c['price'];
      ?>
        <article class="reveal card-lift group rounded-2xl overflow-hidden bg-white border border-slate-100 flex flex-col shadow-card" data-delay="<?= $i % 3 ?>">
          <div class="relative h-40 bg-gradient-to-br from-brand-500 via-violet-600 to-fuchsia-600 p-5 flex flex-col justify-between overflow-hidden">
            <div class="absolute inset-0 bg-dots opacity-40"></div>
            <div class="relative flex items-center justify-between">
              <span class="px-2.5 py-1 rounded-full bg-white/20 text-white text-xs font-medium backdrop-blur"><?= e($c['category'] ?? 'Course') ?></span>
              <?php if ($c['is_featured']): ?><span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-amber-400 text-amber-950 text-xs font-bold"><?= icon('star','w-3 h-3') ?> Featured</span><?php endif; ?>
            </div>
            <h3 class="relative font-display text-xl font-bold text-white leading-tight"><?= e($c['title']) ?></h3>
          </div>
          <div class="p-5 flex flex-col flex-1">
            <p class="text-sm text-slate-500 line-clamp-2"><?= e($c['short_desc']) ?></p>
            <div class="mt-4 flex flex-wrap gap-2 text-xs">
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-slate-600"><?= icon('clock','w-3.5 h-3.5') ?> <?= e($c['duration']) ?></span>
              <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-slate-600"><?= icon('signal','w-3.5 h-3.5') ?> <?= e($c['level']) ?></span>
            </div>
            <div class="mt-5 pt-4 border-t border-slate-100 flex items-center justify-between">
              <div>
                <?php if ($hasDiscount): ?><span class="text-xs text-slate-400 line-through"><?= money($c['price']) ?></span><?php endif; ?>
                <p class="font-display text-2xl font-bold text-ink tracking-tightest"><?= money($price) ?></p>
              </div>
              <a href="<?= url('course.php?slug=' . urlencode($c['slug'])) ?>" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-full bg-ink text-white text-sm font-semibold hover:bg-brand-600 transition">Details <?= icon('arrow-up-right','w-4 h-4') ?></a>
            </div>
          </div>
        </article>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ WHY US (bento) ============ -->
<section class="bg-ink text-white py-20 lg:py-24 relative overflow-hidden">
  <div class="absolute inset-0 bg-grid"></div>
  <div class="relative max-w-7xl mx-auto px-4 sm:px-6">
    <div class="max-w-2xl reveal">
      <span class="inline-flex items-center gap-2 text-sm font-semibold text-brand-400"><?= icon('zap','w-4 h-4') ?> Why Nexora</span>
      <h2 class="mt-3 font-display text-3xl sm:text-[40px] font-bold tracking-tightest">Built for outcomes, not just lectures</h2>
      <p class="mt-4 text-slate-300 text-lg">Everything maps to a real career result — from your first lesson to your first offer.</p>
    </div>
    <div class="mt-12 grid md:grid-cols-3 gap-4">
      <div class="reveal md:row-span-2 rounded-2xl bg-white/[.04] border border-white/10 p-7 flex flex-col">
        <span class="grid place-items-center w-12 h-12 rounded-xl bg-brand-500/20 text-brand-300"><?= icon('target','w-6 h-6') ?></span>
        <h3 class="mt-5 font-display text-xl font-bold">Industry-aligned curriculum</h3>
        <p class="mt-2 text-sm text-slate-400">Designed with hiring partners and refreshed every quarter so you learn what employers actually use.</p>
        <div class="mt-auto pt-6 grid grid-cols-2 gap-3 text-center">
          <div class="rounded-xl bg-white/5 p-4"><p class="font-display text-2xl font-bold"><?= e(setting('hero_stat_courses')) ?></p><p class="text-[11px] text-slate-400 mt-1">Live courses</p></div>
          <div class="rounded-xl bg-white/5 p-4"><p class="font-display text-2xl font-bold"><?= e(setting('hero_stat_partners')) ?></p><p class="text-[11px] text-slate-400 mt-1">Hiring partners</p></div>
        </div>
      </div>
      <?php
        $why = [
          ['rocket','Real, portfolio-ready projects','Graduate with work you can show recruiters — not just theory.'],
          ['shield-check','Verified digital certificates','Each certificate has a unique code anyone can verify online.'],
          ['headset','Dedicated placement support','Resume reviews, mock interviews and warm intros to partners.'],
          ['users','Mentor-led small batches','Personal attention and doubt-clearing from working professionals.'],
        ];
        foreach ($why as $i => $w): ?>
        <div class="reveal rounded-2xl bg-white/[.04] border border-white/10 p-6" data-delay="<?= $i % 3 ?>">
          <span class="grid place-items-center w-11 h-11 rounded-xl bg-white/10 text-brand-300"><?= icon($w[0],'w-5 h-5') ?></span>
          <h3 class="mt-4 font-semibold"><?= $w[1] ?></h3>
          <p class="mt-1.5 text-sm text-slate-400"><?= $w[2] ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ HOW IT WORKS ============ -->
<section class="bg-white py-20 lg:py-24">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="text-center max-w-2xl mx-auto reveal">
      <span class="inline-flex items-center gap-2 text-sm font-semibold text-brand-600"><?= icon('infinity','w-4 h-4') ?> Simple process</span>
      <h2 class="mt-3 font-display text-3xl sm:text-[40px] font-bold text-ink tracking-tightest">From enrollment to employment</h2>
    </div>
    <div class="mt-14 grid md:grid-cols-4 gap-6 relative">
      <div class="hidden md:block absolute top-7 left-[12%] right-[12%] h-px bg-gradient-to-r from-brand-200 via-violet-200 to-brand-200"></div>
      <?php
        $steps = [
          ['book-open','Choose a course','Pick from 40+ mentor-led programs that match your goals.'],
          ['video','Learn by building','Attend live sessions and ship real, portfolio-grade projects.'],
          ['award','Earn your certificate','Get a verified certificate the moment you complete the program.'],
          ['briefcase','Get placed','Use our placement support and partner network to land a role.'],
        ];
        foreach ($steps as $i => $s): ?>
        <div class="reveal relative text-center" data-delay="<?= $i ?>">
          <span class="relative z-10 inline-grid place-items-center w-14 h-14 rounded-2xl bg-white border border-slate-200 text-brand-600 shadow-card mx-auto"><?= icon($s[0],'w-6 h-6') ?></span>
          <span class="absolute top-0 left-1/2 -translate-x-1/2 -mt-2 ml-7 w-6 h-6 rounded-full bg-ink text-white text-xs font-bold grid place-items-center"><?= $i+1 ?></span>
          <h3 class="mt-5 font-display font-bold text-ink"><?= $s[1] ?></h3>
          <p class="mt-2 text-sm text-slate-500"><?= $s[2] ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ PROJECTS PREVIEW ============ -->
<?php if ($projects): ?>
<section class="bg-slate-50 py-20 lg:py-24">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="flex flex-wrap items-end justify-between gap-4 reveal">
      <div class="max-w-xl">
        <span class="inline-flex items-center gap-2 text-sm font-semibold text-brand-600"><?= icon('code','w-4 h-4') ?> Student work</span>
        <h2 class="mt-3 font-display text-3xl sm:text-[40px] font-bold text-ink tracking-tightest">Projects that speak louder than grades</h2>
      </div>
      <a href="<?= url('projects.php') ?>" class="inline-flex items-center gap-1.5 text-sm font-semibold text-ink hover:text-brand-600 transition">All projects <?= icon('arrow-right','w-4 h-4') ?></a>
    </div>
    <div class="mt-12 grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($projects as $i => $p): ?>
        <div class="reveal card-lift rounded-2xl border border-slate-100 overflow-hidden bg-white" data-delay="<?= $i % 3 ?>">
          <div class="h-40 bg-gradient-to-br from-ink to-slate-800 grid place-items-center text-white/20 relative overflow-hidden">
            <div class="absolute inset-0 bg-dots opacity-50"></div>
            <span class="relative"><?= icon('code','w-12 h-12') ?></span>
          </div>
          <div class="p-5">
            <h3 class="font-semibold text-ink"><?= e($p['title']) ?></h3>
            <p class="mt-2 text-sm text-slate-500 line-clamp-2"><?= e($p['description']) ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<!-- ============ TESTIMONIALS ============ -->
<section class="bg-white py-20 lg:py-24">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="text-center max-w-2xl mx-auto reveal">
      <span class="inline-flex items-center gap-2 text-sm font-semibold text-brand-600"><?= icon('heart','w-4 h-4') ?> Loved by learners</span>
      <h2 class="mt-3 font-display text-3xl sm:text-[40px] font-bold text-ink tracking-tightest">Real stories, real outcomes</h2>
    </div>
    <div class="mt-12 grid md:grid-cols-3 gap-6">
      <?php
        $tst = [
          ['Priya Sharma','AutoCAD Professional','PS','f59e0b','The projects made me job-ready. I was placed within a month of finishing the course.'],
          ['Rahul Mehta','Full Stack Web Dev','RM','6366f1','Mentors are incredible. I shipped three real apps and now work as a developer.'],
          ['Aisha Khan','SAP FICO','AK','ec4899','Clear, practical and well-structured. The verified certificate genuinely opened doors.'],
        ];
        foreach ($tst as $i => $t): ?>
        <figure class="reveal rounded-2xl bg-slate-50 border border-slate-100 p-7" data-delay="<?= $i % 3 ?>">
          <span class="text-brand-300"><?= icon('quote','w-8 h-8') ?></span>
          <blockquote class="mt-3 text-slate-700 leading-relaxed">"<?= $t[4] ?>"</blockquote>
          <div class="mt-4 flex items-center gap-1 text-amber-400"><?php for($j=0;$j<5;$j++) echo icon('star','w-4 h-4'); ?></div>
          <figcaption class="mt-5 flex items-center gap-3 pt-5 border-t border-slate-200">
            <span class="grid place-items-center w-11 h-11 rounded-full text-white font-bold text-sm" style="background:linear-gradient(135deg,#<?= $t[3] ?>,#<?= $t[3] ?>cc)"><?= $t[2] ?></span>
            <div><p class="font-semibold text-ink text-sm"><?= $t[0] ?></p><p class="text-xs text-slate-400"><?= $t[1] ?></p></div>
          </figcaption>
        </figure>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- ============ FAQ ============ -->
<section class="bg-slate-50 py-20 lg:py-24">
  <div class="max-w-3xl mx-auto px-4 sm:px-6">
    <div class="text-center reveal">
      <span class="inline-flex items-center gap-2 text-sm font-semibold text-brand-600"><?= icon('message','w-4 h-4') ?> FAQ</span>
      <h2 class="mt-3 font-display text-3xl sm:text-[40px] font-bold text-ink tracking-tightest">Questions, answered</h2>
    </div>
    <div class="mt-10 space-y-3">
      <?php
        $faqs = [
          ['Do I get a certificate after completing a course?','Yes. Every course ends with a verified digital certificate carrying a unique code that anyone can validate on our Verify page.'],
          ['Are the courses beginner friendly?','Absolutely. Most programs start from the fundamentals and progress to advanced, real-world projects.'],
          ['Can I pay the fee in installments?','Yes. Flexible installment options are available — our admin team generates an official fee receipt for every payment.'],
          ['Is placement support included?','We provide resume building, mock interviews and access to 200+ hiring partners.'],
        ];
        foreach ($faqs as $i => $q): ?>
        <div class="reveal rounded-2xl border border-slate-200 bg-white overflow-hidden" data-delay="<?= $i % 3 ?>">
          <button data-faq class="w-full flex items-center justify-between gap-4 px-5 py-4 text-left font-semibold text-ink hover:bg-slate-50 transition">
            <span><?= $q[0] ?></span>
            <span data-faq-icon class="text-brand-600 transition-transform duration-300 shrink-0"><?= icon('plus','w-5 h-5') ?></span>
          </button>
          <div class="overflow-hidden transition-all duration-300" style="max-height:0">
            <p class="px-5 pb-5 text-slate-500 leading-relaxed"><?= $q[1] ?></p>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
