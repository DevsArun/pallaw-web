<?php
require_once __DIR__ . '/_layout.php';
$sid = $student['id'];

$myCourses = db()->prepare(
  "SELECT e.*, c.title, c.slug, c.duration, c.level, c.short_desc, cat.name AS category
   FROM enrollments e
   JOIN courses c ON c.id=e.course_id
   LEFT JOIN categories cat ON cat.id=c.category_id
   WHERE e.student_id=? ORDER BY e.id DESC"
);
$myCourses->execute([$sid]);
$myCourses = $myCourses->fetchAll();

student_layout_top('courses', 'My Courses');
?>

<?php if (!$myCourses): ?>
  <div class="rounded-2xl bg-white border border-slate-100 p-12 text-center">
    <p class="text-5xl">📚</p>
    <p class="mt-4 text-lg font-semibold text-slate-700">No courses yet</p>
    <p class="text-slate-500 mt-1">Once admin enrolls you, your courses will appear here.</p>
    <a href="<?= url('courses.php') ?>" class="inline-block mt-5 px-6 py-3 rounded-full bg-slate-900 text-white font-semibold">Browse Catalog</a>
  </div>
<?php else: ?>
  <div class="grid sm:grid-cols-2 gap-5">
    <?php foreach ($myCourses as $c):
      $status = $c['status'];
      $badge = $status==='completed' ? 'bg-emerald-100 text-emerald-700' : ($status==='dropped' ? 'bg-rose-100 text-rose-700' : 'bg-blue-100 text-blue-700');
    ?>
      <div class="rounded-2xl bg-white border border-slate-100 overflow-hidden">
        <div class="h-28 bg-gradient-to-br from-brand-500 via-violet-600 to-fuchsia-600 p-4 flex items-start justify-between">
          <span class="px-2.5 py-1 rounded-full bg-white/20 text-white text-xs font-medium backdrop-blur"><?= e($c['category'] ?? 'Course') ?></span>
          <span class="px-2.5 py-1 rounded-full text-xs font-semibold <?= $badge ?> capitalize"><?= e($status) ?></span>
        </div>
        <div class="p-5">
          <h3 class="font-semibold text-slate-900"><?= e($c['title']) ?></h3>
          <p class="mt-1 text-sm text-slate-500 line-clamp-2"><?= e($c['short_desc']) ?></p>
          <div class="mt-4 flex flex-wrap gap-2 text-xs">
            <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">⏱ <?= e($c['duration']) ?></span>
            <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">📶 <?= e($c['level']) ?></span>
            <?php if ($c['batch']): ?><span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">👥 <?= e($c['batch']) ?></span><?php endif; ?>
            <span class="px-2.5 py-1 rounded-full bg-slate-100 text-slate-600">📅 Enrolled <?= fmt_date($c['enroll_date']) ?></span>
          </div>
          <a href="<?= url('course.php?slug=' . urlencode($c['slug'])) ?>" target="_blank" class="mt-4 inline-block text-sm font-semibold text-brand-600 hover:text-brand-700">View course details →</a>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php student_layout_bottom(); ?>
