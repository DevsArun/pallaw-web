<?php
require_once __DIR__ . '/includes/functions.php';
$page = 'courses';

$slug = trim($_GET['slug'] ?? '');
$stmt = db()->prepare(
  "SELECT c.*, cat.name AS category FROM courses c
   LEFT JOIN categories cat ON cat.id = c.category_id
   WHERE c.slug = ? AND c.status='active' LIMIT 1"
);
$stmt->execute([$slug]);
$c = $stmt->fetch();

if (!$c) {
    http_response_code(404);
    $page_title = 'Course not found';
    include __DIR__ . '/includes/header.php';
    echo '<section class="max-w-3xl mx-auto px-4 py-28 text-center"><p class="text-6xl">😕</p><h1 class="mt-4 font-display text-3xl font-bold">Course not found</h1><p class="mt-3 text-slate-500">The course you are looking for doesn\'t exist.</p><a href="' . url('courses.php') . '" class="inline-block mt-6 px-6 py-3 rounded-full bg-slate-900 text-white font-semibold">Back to courses</a></section>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

$page_title = $c['title'];
$price = $c['discount_price'] ?: $c['price'];
$hasDiscount = $c['discount_price'] && $c['discount_price'] < $c['price'];
$off = $hasDiscount ? round(100 - ($c['discount_price'] / $c['price'] * 100)) : 0;
$syllabus = array_filter(array_map('trim', explode('|', (string)$c['syllabus'])));

$related = db()->prepare("SELECT * FROM courses WHERE category_id = ? AND id <> ? AND status='active' LIMIT 3");
$related->execute([$c['category_id'], $c['id']]);
$related = $related->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<section class="bg-slate-950 text-white relative overflow-hidden">
  <div class="hero-aurora"><span></span><span></span><span></span></div>
  <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-14 lg:py-20">
    <nav class="text-sm text-slate-400 mb-6">
      <a href="<?= url('index.php') ?>" class="hover:text-white">Home</a> /
      <a href="<?= url('courses.php') ?>" class="hover:text-white">Courses</a> /
      <span class="text-white"><?= e($c['title']) ?></span>
    </nav>
    <div class="grid lg:grid-cols-3 gap-10">
      <div class="lg:col-span-2">
        <span class="inline-block px-3 py-1 rounded-full bg-white/10 border border-white/15 text-xs font-medium"><?= e($c['category'] ?? 'Course') ?></span>
        <h1 class="mt-4 font-display text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight"><?= e($c['title']) ?></h1>
        <p class="mt-4 text-lg text-slate-300 max-w-2xl"><?= e($c['short_desc']) ?></p>
        <div class="mt-6 flex flex-wrap gap-3 text-sm">
          <span class="px-3 py-1.5 rounded-full bg-white/10">⏱ <?= e($c['duration']) ?></span>
          <span class="px-3 py-1.5 rounded-full bg-white/10">📶 <?= e($c['level']) ?></span>
          <?php if ($c['software']): ?><span class="px-3 py-1.5 rounded-full bg-white/10">🧰 <?= e($c['software']) ?></span><?php endif; ?>
          <span class="px-3 py-1.5 rounded-full bg-emerald-500/20 text-emerald-300">📜 Verified Certificate</span>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="bg-slate-50 py-12 lg:py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 grid lg:grid-cols-3 gap-8">
    <!-- Main -->
    <div class="lg:col-span-2 space-y-6">
      <div class="rounded-2xl bg-white border border-slate-100 p-6 sm:p-8">
        <h2 class="font-display text-2xl font-bold text-slate-900">About this course</h2>
        <p class="mt-4 text-slate-600 leading-relaxed"><?= nl2br(e($c['description'])) ?></p>
      </div>

      <?php if ($syllabus): ?>
      <div class="rounded-2xl bg-white border border-slate-100 p-6 sm:p-8">
        <h2 class="font-display text-2xl font-bold text-slate-900">What you'll learn</h2>
        <div class="mt-5 grid sm:grid-cols-2 gap-3">
          <?php foreach ($syllabus as $i => $mod): ?>
            <div class="flex items-start gap-3 p-3 rounded-xl bg-slate-50">
              <span class="grid place-items-center w-7 h-7 rounded-lg bg-brand-100 text-brand-700 text-sm font-bold shrink-0"><?= $i + 1 ?></span>
              <span class="text-sm text-slate-700 font-medium"><?= e($mod) ?></span>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>

      <div class="rounded-2xl bg-white border border-slate-100 p-6 sm:p-8">
        <h2 class="font-display text-2xl font-bold text-slate-900">Why this program</h2>
        <ul class="mt-5 space-y-3 text-slate-600">
          <?php foreach (['Mentor-led live training with doubt support','Hands-on, portfolio-ready capstone project','Industry-recognized verified certificate','Placement assistance & interview prep'] as $b): ?>
            <li class="flex gap-3"><span class="text-emerald-500 mt-0.5">✓</span><span><?= $b ?></span></li>
          <?php endforeach; ?>
        </ul>
      </div>
    </div>

    <!-- Sticky enroll card -->
    <aside class="lg:sticky lg:top-24 h-fit">
      <div class="rounded-2xl bg-white border border-slate-100 p-6 shadow-xl shadow-slate-900/5">
        <div class="flex items-end gap-3">
          <p class="font-display text-4xl font-bold text-slate-900"><?= money($price) ?></p>
          <?php if ($hasDiscount): ?>
            <span class="text-slate-400 line-through pb-1"><?= money($c['price']) ?></span>
            <span class="ml-auto px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-bold"><?= $off ?>% OFF</span>
          <?php endif; ?>
        </div>
        <p class="mt-1 text-sm text-slate-400">One-time fee · EMI available</p>

        <a href="<?= url('contact.php?course=' . urlencode($c['title'])) ?>" class="mt-5 block text-center px-5 py-3.5 rounded-xl bg-gradient-to-r from-brand-500 to-violet-600 text-white font-semibold hover:opacity-90 transition">Enroll Now</a>
        <a href="tel:<?= e(setting('phone')) ?>" class="mt-3 block text-center px-5 py-3.5 rounded-xl border border-slate-200 font-semibold text-slate-700 hover:border-brand-300 transition">📞 Talk to Counsellor</a>

        <dl class="mt-6 space-y-3 text-sm">
          <div class="flex justify-between"><dt class="text-slate-500">Duration</dt><dd class="font-semibold text-slate-900"><?= e($c['duration']) ?></dd></div>
          <div class="flex justify-between"><dt class="text-slate-500">Level</dt><dd class="font-semibold text-slate-900"><?= e($c['level']) ?></dd></div>
          <div class="flex justify-between"><dt class="text-slate-500">Category</dt><dd class="font-semibold text-slate-900"><?= e($c['category'] ?? '-') ?></dd></div>
          <div class="flex justify-between"><dt class="text-slate-500">Certificate</dt><dd class="font-semibold text-emerald-600">Included</dd></div>
        </dl>
      </div>
    </aside>
  </div>
</section>

<?php if ($related): ?>
<section class="bg-white py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <h2 class="font-display text-2xl font-bold text-slate-900 mb-8">Related courses</h2>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($related as $r): $rp = $r['discount_price'] ?: $r['price']; ?>
        <a href="<?= url('course.php?slug=' . urlencode($r['slug'])) ?>" class="card-lift block rounded-2xl border border-slate-100 p-5 bg-white">
          <h3 class="font-semibold text-slate-900"><?= e($r['title']) ?></h3>
          <p class="mt-2 text-sm text-slate-500 line-clamp-2"><?= e($r['short_desc']) ?></p>
          <p class="mt-4 font-display text-xl font-bold text-slate-900"><?= money($rp) ?></p>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>
<?php endif; ?>

<?php include __DIR__ . '/includes/footer.php'; ?>
