<?php
require_once __DIR__ . '/includes/functions.php';
$page = 'courses';
$page_title = 'Courses';

$catSlug = trim($_GET['cat'] ?? '');
$q       = trim($_GET['q'] ?? '');

$sql = "SELECT c.*, cat.name AS category, cat.slug AS cat_slug
        FROM courses c LEFT JOIN categories cat ON cat.id = c.category_id
        WHERE c.status='active'";
$params = [];
if ($catSlug !== '') { $sql .= " AND cat.slug = ?"; $params[] = $catSlug; }
if ($q !== '')       { $sql .= " AND (c.title LIKE ? OR c.short_desc LIKE ? OR c.software LIKE ?)"; $like = "%$q%"; array_push($params, $like, $like, $like); }
$sql .= " ORDER BY c.is_featured DESC, c.id ASC";

$stmt = db()->prepare($sql);
$stmt->execute($params);
$courses = $stmt->fetchAll();

$categories = db()->query("SELECT * FROM categories ORDER BY name")->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<!-- Page header -->
<section class="bg-slate-950 text-white relative overflow-hidden">
  <div class="hero-aurora"><span></span><span></span><span></span></div>
  <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-16 lg:py-20">
    <span class="text-sm font-semibold text-brand-400 uppercase tracking-wider">Catalog</span>
    <h1 class="mt-3 font-display text-4xl sm:text-5xl font-bold">Explore our courses</h1>
    <p class="mt-4 text-slate-300 max-w-2xl">Mentor-led, project-based programs with verified certificates and placement support.</p>

    <!-- Search -->
    <form method="get" class="mt-8 max-w-xl flex gap-2">
      <?php if ($catSlug): ?><input type="hidden" name="cat" value="<?= e($catSlug) ?>"><?php endif; ?>
      <div class="relative flex-1">
        <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="m21 21-4.3-4.3"/></svg>
        <input name="q" value="<?= e($q) ?>" placeholder="Search courses, software, skills…" class="w-full pl-12 pr-4 py-3.5 rounded-full bg-white/10 border border-white/15 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-400 backdrop-blur">
      </div>
      <button class="px-6 py-3.5 rounded-full bg-white text-slate-900 font-semibold hover:bg-brand-50 transition">Search</button>
    </form>
  </div>
</section>

<!-- Filters + grid -->
<section class="bg-slate-50 py-12 lg:py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <!-- Category pills -->
    <div class="flex flex-wrap gap-2 mb-10">
      <a href="<?= url('courses.php' . ($q ? '?q=' . urlencode($q) : '')) ?>"
         class="px-4 py-2 rounded-full text-sm font-medium transition <?= $catSlug==='' ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:border-brand-300' ?>">All</a>
      <?php foreach ($categories as $cat): ?>
        <a href="<?= url('courses.php?cat=' . urlencode($cat['slug']) . ($q ? '&q=' . urlencode($q) : '')) ?>"
           class="px-4 py-2 rounded-full text-sm font-medium transition <?= $catSlug===$cat['slug'] ? 'bg-slate-900 text-white' : 'bg-white text-slate-600 border border-slate-200 hover:border-brand-300' ?>"><?= e($cat['name']) ?></a>
      <?php endforeach; ?>
    </div>

    <?php if (!$courses): ?>
      <div class="text-center py-20">
        <p class="text-5xl">🔍</p>
        <p class="mt-4 text-lg font-semibold text-slate-700">No courses found</p>
        <p class="text-slate-500">Try a different search or category.</p>
      </div>
    <?php else: ?>
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($courses as $c):
          $price = $c['discount_price'] ?: $c['price'];
          $hasDiscount = $c['discount_price'] && $c['discount_price'] < $c['price'];
        ?>
          <article class="card-lift group rounded-2xl overflow-hidden bg-white border border-slate-100 flex flex-col">
            <div class="relative h-44 bg-gradient-to-br from-brand-500 via-violet-600 to-fuchsia-600 p-5 flex flex-col justify-between">
              <div class="flex items-center justify-between">
                <span class="px-2.5 py-1 rounded-full bg-white/20 text-white text-xs font-medium backdrop-blur"><?= e($c['category'] ?? 'Course') ?></span>
                <?php if ($c['is_featured']): ?><span class="px-2.5 py-1 rounded-full bg-amber-400 text-amber-950 text-xs font-bold">★ Featured</span><?php endif; ?>
              </div>
              <h3 class="font-display text-xl font-bold text-white leading-tight"><?= e($c['title']) ?></h3>
            </div>
            <div class="p-5 flex flex-col flex-1">
              <p class="text-sm text-slate-500 line-clamp-2"><?= e($c['short_desc']) ?></p>
              <?php if ($c['software']): ?><p class="mt-3 text-xs text-slate-400">🧰 <?= e($c['software']) ?></p><?php endif; ?>
              <div class="mt-3 flex flex-wrap gap-2 text-xs">
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
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
