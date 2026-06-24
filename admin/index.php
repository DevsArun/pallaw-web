<?php
require_once __DIR__ . '/_layout.php';
$d = db();

$stats = [
  'students'     => $d->query("SELECT COUNT(*) FROM students")->fetchColumn(),
  'courses'      => $d->query("SELECT COUNT(*) FROM courses")->fetchColumn(),
  'certificates' => $d->query("SELECT COUNT(*) FROM certificates")->fetchColumn(),
  'enquiries'    => $d->query("SELECT COUNT(*) FROM contacts WHERE status='new'")->fetchColumn(),
  'revenue'      => $d->query("SELECT COALESCE(SUM(amount),0) FROM payments WHERE status IN('paid','partial')")->fetchColumn(),
  'enrollments'  => $d->query("SELECT COUNT(*) FROM enrollments")->fetchColumn(),
];

$recentStudents = $d->query("SELECT * FROM students ORDER BY id DESC LIMIT 5")->fetchAll();
$recentPayments = $d->query("SELECT p.*, s.name AS student FROM payments p JOIN students s ON s.id=p.student_id ORDER BY p.id DESC LIMIT 5")->fetchAll();
$recentEnquiries= $d->query("SELECT * FROM contacts ORDER BY id DESC LIMIT 5")->fetchAll();

admin_layout_top('dashboard', 'Dashboard');
?>

<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
  <?php
    $cards = [
      ['Students',     $stats['students'],     '👨‍🎓', 'from-blue-500 to-blue-600',     'students.php'],
      ['Courses',      $stats['courses'],      '📚', 'from-violet-500 to-violet-600', 'courses.php'],
      ['Enrollments',  $stats['enrollments'],  '📝', 'from-fuchsia-500 to-fuchsia-600','enrollments.php'],
      ['Certificates', $stats['certificates'], '📜', 'from-emerald-500 to-emerald-600','certificates.php'],
      ['New Enquiries',$stats['enquiries'],    '✉️', 'from-amber-500 to-amber-600',   'contacts.php'],
      ['Revenue',      money($stats['revenue']),'💰', 'from-rose-500 to-rose-600',    'payments.php'],
    ];
    foreach ($cards as $c): ?>
    <a href="<?= url('admin/' . $c[4]) ?>" class="rounded-2xl bg-gradient-to-br <?= $c[3] ?> text-white p-5 hover:scale-[1.02] transition shadow-lg">
      <div class="flex items-center justify-between"><span class="text-2xl"><?= $c[2] ?></span></div>
      <p class="mt-3 font-display text-2xl font-bold leading-none"><?= e((string)$c[1]) ?></p>
      <p class="mt-1 text-sm text-white/80"><?= $c[0] ?></p>
    </a>
  <?php endforeach; ?>
</div>

<div class="grid lg:grid-cols-2 gap-6">
  <!-- Recent students -->
  <div class="rounded-2xl bg-white border border-slate-100 p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-display text-lg font-bold text-slate-900">Recent Students</h3>
      <a href="<?= url('admin/students.php') ?>" class="text-sm font-semibold text-brand-600">View all →</a>
    </div>
    <div class="space-y-3">
      <?php foreach ($recentStudents as $s): ?>
        <div class="flex items-center gap-3">
          <span class="grid place-items-center w-9 h-9 rounded-full bg-gradient-to-br from-brand-500 to-violet-600 text-white text-sm font-bold"><?= e(strtoupper(substr($s['name'],0,1))) ?></span>
          <div class="min-w-0 flex-1"><p class="font-medium text-slate-900 text-sm truncate"><?= e($s['name']) ?></p><p class="text-xs text-slate-400"><?= e($s['student_code']) ?></p></div>
          <span class="text-xs text-slate-400"><?= fmt_date($s['created_at']) ?></span>
        </div>
      <?php endforeach; ?>
      <?php if(!$recentStudents): ?><p class="text-sm text-slate-400 text-center py-4">No students yet</p><?php endif; ?>
    </div>
  </div>

  <!-- Recent payments -->
  <div class="rounded-2xl bg-white border border-slate-100 p-6">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-display text-lg font-bold text-slate-900">Recent Payments</h3>
      <a href="<?= url('admin/payments.php') ?>" class="text-sm font-semibold text-brand-600">View all →</a>
    </div>
    <div class="space-y-3">
      <?php foreach ($recentPayments as $p): ?>
        <div class="flex items-center gap-3">
          <span class="grid place-items-center w-9 h-9 rounded-full bg-emerald-50 text-emerald-600 text-sm">🧾</span>
          <div class="min-w-0 flex-1"><p class="font-medium text-slate-900 text-sm truncate"><?= e($p['student']) ?></p><p class="text-xs text-slate-400 font-mono"><?= e($p['receipt_no']) ?></p></div>
          <span class="text-sm font-semibold text-slate-900"><?= money($p['amount']) ?></span>
        </div>
      <?php endforeach; ?>
      <?php if(!$recentPayments): ?><p class="text-sm text-slate-400 text-center py-4">No payments yet</p><?php endif; ?>
    </div>
  </div>

  <!-- Recent enquiries -->
  <div class="rounded-2xl bg-white border border-slate-100 p-6 lg:col-span-2">
    <div class="flex items-center justify-between mb-4">
      <h3 class="font-display text-lg font-bold text-slate-900">Latest Enquiries</h3>
      <a href="<?= url('admin/contacts.php') ?>" class="text-sm font-semibold text-brand-600">View all →</a>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="text-left text-slate-400"><tr><th class="py-2 font-medium">Name</th><th class="py-2 font-medium">Email</th><th class="py-2 font-medium">Subject</th><th class="py-2 font-medium">Date</th><th class="py-2 font-medium">Status</th></tr></thead>
        <tbody class="divide-y divide-slate-100">
          <?php foreach ($recentEnquiries as $c): $sb=$c['status']==='new'?'bg-amber-100 text-amber-700':($c['status']==='responded'?'bg-emerald-100 text-emerald-700':'bg-slate-100 text-slate-600'); ?>
            <tr><td class="py-3 font-medium text-slate-900"><?= e($c['name']) ?></td><td class="py-3 text-slate-500"><?= e($c['email']) ?></td><td class="py-3 text-slate-500"><?= e($c['subject'] ?: '—') ?></td><td class="py-3 text-slate-400"><?= fmt_date($c['created_at']) ?></td><td class="py-3"><span class="px-2.5 py-1 rounded-full text-xs font-semibold <?= $sb ?> capitalize"><?= e($c['status']) ?></span></td></tr>
          <?php endforeach; ?>
          <?php if(!$recentEnquiries): ?><tr><td colspan="5" class="py-4 text-center text-slate-400">No enquiries yet</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php admin_layout_bottom(); ?>
