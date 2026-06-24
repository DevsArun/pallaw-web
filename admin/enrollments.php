<?php
require_once __DIR__ . '/_layout.php';
$d = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) { flash('error','Session expired.'); redirect('admin/enrollments.php'); }
    $do = $_POST['do'] ?? '';
    if ($do === 'delete') {
        $d->prepare("DELETE FROM enrollments WHERE id=?")->execute([(int)$_POST['id']]);
        flash('success','Enrollment removed.');
    } elseif ($do === 'status') {
        $d->prepare("UPDATE enrollments SET status=? WHERE id=?")->execute([$_POST['status'], (int)$_POST['id']]);
        flash('success','Status updated.');
    } elseif ($do === 'save') {
        $sid = (int)$_POST['student_id']; $cid = (int)$_POST['course_id'];
        $batch = trim($_POST['batch'] ?? ''); $status = $_POST['status'] ?? 'active';
        if (!$sid || !$cid) { flash('error','Select student and course.'); redirect('admin/enrollments.php'); }
        $d->prepare("INSERT INTO enrollments (student_id,course_id,batch,status) VALUES (?,?,?,?)")->execute([$sid,$cid,$batch,$status]);
        flash('success','Student enrolled.');
    }
    redirect('admin/enrollments.php');
}

$students = $d->query("SELECT id,name,student_code FROM students ORDER BY name")->fetchAll();
$courses  = $d->query("SELECT id,title FROM courses ORDER BY title")->fetchAll();
$rows = $d->query("SELECT e.*, s.name AS student, s.student_code, c.title AS course FROM enrollments e JOIN students s ON s.id=e.student_id JOIN courses c ON c.id=e.course_id ORDER BY e.id DESC")->fetchAll();

admin_layout_top('enrollments', 'Enrollments');
?>
<div class="grid lg:grid-cols-3 gap-6">
  <div class="rounded-2xl bg-white border border-slate-100 p-6 h-fit">
    <h3 class="font-display text-lg font-bold text-slate-900 mb-4">New Enrollment</h3>
    <form method="post" class="space-y-4">
      <?= csrf_field() ?><input type="hidden" name="do" value="save">
      <div><?= field_label('Student') ?><select name="student_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"><option value="">Select student…</option><?php foreach($students as $s): ?><option value="<?= $s['id'] ?>"><?= e($s['name']) ?> (<?= e($s['student_code']) ?>)</option><?php endforeach; ?></select></div>
      <div><?= field_label('Course') ?><select name="course_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"><option value="">Select course…</option><?php foreach($courses as $c): ?><option value="<?= $c['id'] ?>"><?= e($c['title']) ?></option><?php endforeach; ?></select></div>
      <div><?= field_label('Batch (optional)') ?><input name="batch" placeholder="e.g. Morning-A" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"></div>
      <div><?= field_label('Status') ?><select name="status" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"><?php foreach(['active','completed','dropped'] as $st): ?><option><?= $st ?></option><?php endforeach; ?></select></div>
      <button class="w-full px-6 py-3 rounded-xl bg-gradient-to-r from-brand-500 to-violet-600 text-white font-semibold hover:opacity-90 transition">Enroll Student</button>
    </form>
  </div>

  <div class="lg:col-span-2 rounded-2xl bg-white border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-500 text-left"><tr><th class="px-5 py-3 font-semibold">Student</th><th class="px-5 py-3 font-semibold">Course</th><th class="px-5 py-3 font-semibold">Batch</th><th class="px-5 py-3 font-semibold">Status</th><th class="px-5 py-3 font-semibold text-right">Action</th></tr></thead>
        <tbody class="divide-y divide-slate-100">
          <?php foreach ($rows as $r): ?>
            <tr class="hover:bg-slate-50">
              <td class="px-5 py-4"><p class="font-medium text-slate-900"><?= e($r['student']) ?></p><p class="text-xs text-slate-400"><?= e($r['student_code']) ?></p></td>
              <td class="px-5 py-4 text-slate-600"><?= e($r['course']) ?></td>
              <td class="px-5 py-4 text-slate-500"><?= e($r['batch'] ?: '—') ?></td>
              <td class="px-5 py-4">
                <form method="post" class="inline"><?= csrf_field() ?><input type="hidden" name="do" value="status"><input type="hidden" name="id" value="<?= $r['id'] ?>">
                  <select name="status" onchange="this.form.submit()" class="px-2 py-1 rounded-lg border border-slate-200 text-xs capitalize"><?php foreach(['active','completed','dropped'] as $st): ?><option <?= $r['status']===$st?'selected':'' ?>><?= $st ?></option><?php endforeach; ?></select>
                </form>
              </td>
              <td class="px-5 py-4 text-right"><form method="post" class="inline" onsubmit="return confirm('Remove enrollment?')"><?= csrf_field() ?><input type="hidden" name="do" value="delete"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button class="text-rose-600 font-semibold">Remove</button></form></td>
            </tr>
          <?php endforeach; ?>
          <?php if(!$rows): ?><tr><td colspan="5" class="px-5 py-10 text-center text-slate-400">No enrollments yet.</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php admin_layout_bottom(); ?>
