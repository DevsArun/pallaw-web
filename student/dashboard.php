<?php
require_once __DIR__ . '/_layout.php';
$sid = $student['id'];

$enrollCount = db()->prepare("SELECT COUNT(*) FROM enrollments WHERE student_id=?"); $enrollCount->execute([$sid]); $enrollCount = $enrollCount->fetchColumn();
$certCount   = db()->prepare("SELECT COUNT(*) FROM certificates WHERE student_id=?"); $certCount->execute([$sid]); $certCount = $certCount->fetchColumn();
$paid        = db()->prepare("SELECT COALESCE(SUM(amount),0) FROM payments WHERE student_id=? AND status IN ('paid','partial')"); $paid->execute([$sid]); $paid = $paid->fetchColumn();
$receipts    = db()->prepare("SELECT COUNT(*) FROM payments WHERE student_id=?"); $receipts->execute([$sid]); $receipts = $receipts->fetchColumn();

$myCourses = db()->prepare(
  "SELECT e.*, c.title, c.duration, c.level FROM enrollments e
   JOIN courses c ON c.id=e.course_id WHERE e.student_id=? ORDER BY e.id DESC"
);
$myCourses->execute([$sid]);
$myCourses = $myCourses->fetchAll();

student_layout_top('dashboard', 'Dashboard');
?>

<div class="rounded-2xl bg-gradient-to-r from-brand-600 to-violet-600 text-white p-6 sm:p-8 mb-6">
  <h2 class="font-display text-2xl sm:text-3xl font-bold">Hi <?= e(explode(' ', $student['name'])[0]) ?> 👋</h2>
  <p class="mt-2 text-white/80">Here's a snapshot of your learning journey at <?= e(setting('site_name')) ?>.</p>
</div>

<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
  <?php
    $cards = [
      ['Enrolled Courses', $enrollCount, '📚', 'bg-blue-50 text-blue-600'],
      ['Certificates',     $certCount,   '📜', 'bg-emerald-50 text-emerald-600'],
      ['Fee Receipts',     $receipts,    '🧾', 'bg-amber-50 text-amber-600'],
      ['Total Paid',       money($paid), '💳', 'bg-violet-50 text-violet-600'],
    ];
    foreach ($cards as $c): ?>
    <div class="rounded-2xl bg-white border border-slate-100 p-5">
      <span class="grid place-items-center w-11 h-11 rounded-xl <?= $c[3] ?> text-xl"><?= $c[2] ?></span>
      <p class="mt-4 font-display text-2xl font-bold text-slate-900"><?= e((string)$c[1]) ?></p>
      <p class="text-sm text-slate-500"><?= $c[0] ?></p>
    </div>
  <?php endforeach; ?>
</div>

<div class="rounded-2xl bg-white border border-slate-100 p-6">
  <div class="flex items-center justify-between mb-4">
    <h3 class="font-display text-lg font-bold text-slate-900">My Courses</h3>
    <a href="<?= url('student/courses.php') ?>" class="text-sm font-semibold text-brand-600 hover:text-brand-700">View all →</a>
  </div>
  <?php if (!$myCourses): ?>
    <p class="text-slate-500 text-sm py-6 text-center">You're not enrolled in any course yet. <a href="<?= url('courses.php') ?>" class="text-brand-600 font-semibold">Browse courses</a></p>
  <?php else: ?>
    <div class="space-y-3">
      <?php foreach (array_slice($myCourses, 0, 4) as $c):
        $status = $c['status'];
        $badge = $status==='completed' ? 'bg-emerald-100 text-emerald-700' : ($status==='dropped' ? 'bg-rose-100 text-rose-700' : 'bg-blue-100 text-blue-700');
      ?>
        <div class="flex items-center gap-4 p-4 rounded-xl bg-slate-50">
          <span class="grid place-items-center w-11 h-11 rounded-xl bg-gradient-to-br from-brand-500 to-violet-600 text-white font-bold shrink-0"><?= e(strtoupper(substr($c['title'],0,1))) ?></span>
          <div class="min-w-0 flex-1">
            <p class="font-semibold text-slate-900 truncate"><?= e($c['title']) ?></p>
            <p class="text-xs text-slate-400"><?= e($c['duration']) ?> · <?= e($c['level']) ?> <?= $c['batch'] ? '· Batch ' . e($c['batch']) : '' ?></p>
          </div>
          <span class="px-2.5 py-1 rounded-full text-xs font-semibold <?= $badge ?> capitalize"><?= e($status) ?></span>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

<?php student_layout_bottom(); ?>
