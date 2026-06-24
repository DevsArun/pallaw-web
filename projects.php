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

<section class="bg-slate-950 text-white relative overflow-hidden">
  <div class="hero-aurora"><span></span><span></span><span></span></div>
  <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-16 text-center">
    <span class="text-sm font-semibold text-brand-400 uppercase tracking-wider">Project Showcase</span>
    <h1 class="mt-3 font-display text-4xl sm:text-5xl font-bold">Real work by our students</h1>
    <p class="mt-4 text-slate-300 max-w-2xl mx-auto">Every program ends with a portfolio-ready project. Here's what our learners have built.</p>
  </div>
</section>

<section class="bg-slate-50 py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <?php if (!$projects): ?>
      <div class="text-center py-20"><p class="text-5xl">📦</p><p class="mt-4 text-lg font-semibold text-slate-700">No projects published yet</p></div>
    <?php else: ?>
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($projects as $p): ?>
          <article class="card-lift rounded-2xl bg-white border border-slate-100 overflow-hidden flex flex-col">
            <div class="h-44 bg-gradient-to-br from-slate-800 to-slate-950 grid place-items-center text-white/20 text-6xl">⌘</div>
            <div class="p-5 flex flex-col flex-1">
              <?php if ($p['course']): ?><span class="text-xs font-semibold text-brand-600 uppercase tracking-wide"><?= e($p['course']) ?></span><?php endif; ?>
              <h3 class="mt-2 font-semibold text-slate-900"><?= e($p['title']) ?></h3>
              <p class="mt-2 text-sm text-slate-500 line-clamp-3 flex-1"><?= e($p['description']) ?></p>
              <div class="mt-4 pt-4 border-t border-slate-100 flex items-center justify-between text-sm">
                <span class="text-slate-400"><?= $p['student'] ? '👤 ' . e($p['student']) : 'Student Project' ?></span>
                <?php if ($p['project_url'] && $p['project_url'] !== '#'): ?>
                  <a href="<?= e($p['project_url']) ?>" target="_blank" class="font-semibold text-brand-600 hover:text-brand-700">View →</a>
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
