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
<section class="relative overflow-hidden bg-ink text-white">
  <div class="hero-aurora"><span></span><span></span><span></span></div>
  <div class="absolute inset-0 bg-grid"></div>
  <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-16 lg:py-20">
    <span class="inline-flex items-center gap-2 text-sm font-semibold text-brand-400"><?= icon('book-open','w-4 h-4') ?> Course Catalog</span>
    <h1 class="mt-3 font-display text-4xl sm:text-5xl font-bold tracking-tightest">Explore our courses</h1>
    <p class="mt-4 text-slate-300 max-w-2xl text-lg">Mentor-led, project-based programs with verified certificates and placement support.</p>

    <form method="get" class="mt-8 max-w-xl flex gap-2">
      <?php if ($catSlug): ?><input type="hidden" name="cat" value="<?= e($catSlug) ?>"><?php endif; ?>
      <div class="relative flex-1">
        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"><?= icon('search','w-5 h-5') ?></span>
        <input name="q" value="<?= e($q) ?>" placeholder="Search courses, software, skills…" class="w-full pl-12 pr-4 py-3.5 rounded-full bg-white/10 border border-white/15 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-400 backdrop-blur">
      </div>
      <button class="btn-shine px-6 py-3.5 rounded-full bg-white text-ink font-semibold hover:bg-brand-50 transition">Search</button>
    </form>
  </div>
</section>

<section class="bg-slate-50 py-12 lg:py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="flex flex-wrap gap-2 mb-10">
      <a href="<?= url('courses.php' . ($q ? '?q=' . urlencode($q) : '')) ?>"
         class="px-4 py-2 rounded-full text-sm font-medium transition <?= $catSlug==='' ? 'bg-ink text-white' : 'bg-white text-slate-600 border border-slate-200 hover:border-brand-300 hover:text-brand-600' ?>">All courses</a>
      <?php foreach ($categories as $cat): ?>
        <a href="<?= url('courses.php?cat=' . urlencode($cat['slug']) . ($q ? '&q=' . urlencode($q) : '')) ?>"
           class="px-4 py-2 rounded-full text-sm font-medium transition <?= $catSlug===$cat['slug'] ? 'bg-ink text-white' : 'bg-white text-slate-600 border border-slate-200 hover:border-brand-300 hover:text-brand-600' ?>"><?= e($cat['name']) ?></a>
      <?php endforeach; ?>
    </div>

    <?php if (!$courses): ?>
      <div class="text-center py-20 rounded-3xl bg-white border border-slate-100">
        <span class="inline-grid place-items-center w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 mx-auto"><?= icon('search','w-7 h-7') ?></span>
        <p class="mt-5 text-lg font-semibold text-ink">No courses found</p>
        <p class="text-slate-500">Try a different search or category.</p>
        <a href="<?= url('courses.php') ?>" class="inline-block mt-5 px-6 py-3 rounded-full bg-ink text-white font-semibold">Reset filters</a>
      </div>
    <?php else: ?>
      <p class="text-sm text-slate-400 mb-5"><?= count($courses) ?> course<?= count($courses)>1?'s':'' ?> found</p>
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
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
              <?php if ($c['software']): ?><p class="mt-3 text-xs text-slate-400 inline-flex items-center gap-1.5"><?= icon('cpu','w-3.5 h-3.5') ?> <?= e($c['software']) ?></p><?php endif; ?>
              <div class="mt-3 flex flex-wrap gap-2 text-xs">
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
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
