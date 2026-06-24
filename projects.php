<?php
require_once __DIR__ . '/includes/functions.php';
$page = 'projects';
$page_title = 'Student Projects';

$projects = db()->query(
  "SELECT p.*, c.title AS course, s.name AS student
   FROM projects p
   LEFT JOIN courses c ON c.id = p.course_id
   LEFT JOIN students s ON s.id = p.student_id
   WHERE p.status='published' ORDER BY p.id DESC"
)->fetchAll();

include __DIR__ . '/includes/header.php';
?>

<section class="relative overflow-hidden bg-ink text-white">
  <div class="hero-aurora"><span></span><span></span><span></span></div>
  <div class="absolute inset-0 bg-grid"></div>
  <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-16 text-center">
    <span class="inline-flex items-center gap-2 text-sm font-semibold text-brand-400"><?= icon('code','w-4 h-4') ?> Project Showcase</span>
    <h1 class="mt-3 font-display text-4xl sm:text-5xl font-bold tracking-tightest">Real work by our students</h1>
    <p class="mt-4 text-slate-300 max-w-2xl mx-auto text-lg">Every program ends with a portfolio-ready project. Here's what our learners have built.</p>
  </div>
</section>

<section class="bg-slate-50 py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <?php if (!$projects): ?>
      <div class="text-center py-20 rounded-3xl bg-white border border-slate-100">
        <span class="inline-grid place-items-center w-16 h-16 rounded-2xl bg-slate-100 text-slate-400 mx-auto"><?= icon('layers','w-7 h-7') ?></span>
        <p class="mt-5 text-lg font-semibold text-ink">No projects published yet</p>
        <p class="text-slate-500">Check back soon to see student work.</p>
      </div>
    <?php else: ?>
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($projects as $i => $p): ?>
          <article class="reveal card-lift rounded-2xl bg-white border border-slate-100 overflow-hidden flex flex-col shadow-card" data-delay="<?= $i % 3 ?>">
            <div class="h-44 bg-gradient-to-br from-ink to-slate-800 grid place-items-center text-white/20 relative overflow-hidden">
              <div class="absolute inset-0 bg-dots opacity-50"></div>
              <span class="relative"><?= icon('code','w-14 h-14') ?></span>
            </div>
            <div class="p-5 flex flex-col flex-1">
              <?php if ($p['course']): ?><span class="text-xs font-semibold text-brand-600 uppercase tracking-wide"><?= e($p['course']) ?></span><?php endif; ?>
              <h3 class="mt-2 font-semibold text-ink"><?= e($p['title']) ?></h3>
              <p class="mt-2 text-sm text-slate-500 line-clamp-3 flex-1"><?= e($p['description']) ?></p>
              <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-sm">
                <span class="text-slate-400 inline-flex items-center gap-1.5"><?= icon('users','w-4 h-4') ?> <?= $p['student'] ? e($p['student']) : 'Student Project' ?></span>
                <?php if ($p['project_url'] && $p['project_url'] !== '#'): ?>
                  <a href="<?= e($p['project_url']) ?>" target="_blank" rel="noopener" class="inline-flex items-center gap-1 font-semibold text-brand-600 hover:text-brand-700">View <?= icon('arrow-up-right','w-4 h-4') ?></a>
                <?php endif; ?>
              </div>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
