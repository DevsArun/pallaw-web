<?php
require_once __DIR__ . '/_layout.php';
$d = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) { flash('error','Session expired.'); redirect('admin/certificates.php'); }
    $do = $_POST['do'] ?? '';
    if ($do === 'delete') {
        $d->prepare("DELETE FROM certificates WHERE id=?")->execute([(int)$_POST['id']]);
        flash('success','Certificate deleted.');
    } elseif ($do === 'save') {
        $sid = (int)$_POST['student_id'];
        $cid = (int)$_POST['course_id'];
        $grade = trim($_POST['grade'] ?? 'A');
        $issue = $_POST['issue_date'] ?: date('Y-m-d');
        $remarks = trim($_POST['remarks'] ?? '');
        if (!$sid || !$cid) { flash('error','Select student and course.'); redirect('admin/certificates.php'); }
        $no = next_sequence('certificates','certificate_no','NEX-CERT');
        $d->prepare("INSERT INTO certificates (certificate_no,student_id,course_id,grade,issue_date,remarks,generated_by) VALUES (?,?,?,?,?,?,?)")
          ->execute([$no,$sid,$cid,$grade,$issue,$remarks,$_SESSION['admin_id']]);
        // Lock the student's personal details now that a certificate exists
        $d->prepare("UPDATE students SET details_locked=1 WHERE id=?")->execute([$sid]);
        // Mark enrollment completed if exists
        $d->prepare("UPDATE enrollments SET status='completed' WHERE student_id=? AND course_id=?")->execute([$sid,$cid]);
        flash('success', "Certificate $no generated. Student's personal details are now locked.");
    }
    redirect('admin/certificates.php');
}

$students = $d->query("SELECT id,name,student_code FROM students ORDER BY name")->fetchAll();
$courses  = $d->query("SELECT id,title FROM courses ORDER BY title")->fetchAll();
$rows = $d->query("SELECT cert.*, s.name AS student, s.student_code, c.title AS course FROM certificates cert JOIN students s ON s.id=cert.student_id JOIN courses c ON c.id=cert.course_id ORDER BY cert.id DESC")->fetchAll();

admin_layout_top('certificates', 'Certificates');
?>
<div class="mb-6 rounded-xl border border-amber-200 bg-amber-50 text-amber-800 px-4 py-3 text-sm flex items-center gap-2">
  ⚠️ Generating a certificate <strong>locks</strong> the student's personal details. Verify the student's name and course before issuing.
</div>

<div class="grid lg:grid-cols-3 gap-6">
  <div class="rounded-2xl bg-white border border-slate-100 p-6 h-fit">
    <h3 class="font-display text-lg font-bold text-slate-900 mb-1">Issue Certificate</h3>
    <p class="text-xs text-slate-400 mb-4">Certificate number is auto-generated.</p>
    <form method="post" class="space-y-4">
      <?= csrf_field() ?><input type="hidden" name="do" value="save">
      <div><?= field_label('Student') ?><select name="student_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"><option value="">Select student…</option><?php foreach($students as $s): ?><option value="<?= $s['id'] ?>"><?= e($s['name']) ?> (<?= e($s['student_code']) ?>)</option><?php endforeach; ?></select></div>
      <div><?= field_label('Course') ?><select name="course_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"><option value="">Select course…</option><?php foreach($courses as $c): ?><option value="<?= $c['id'] ?>"><?= e($c['title']) ?></option><?php endforeach; ?></select></div>
      <div class="grid grid-cols-2 gap-3">
        <div><?= field_label('Grade') ?><input name="grade" value="A" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"></div>
        <div><?= field_label('Issue Date') ?><input type="date" name="issue_date" value="<?= date('Y-m-d') ?>" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"></div>
      </div>
      <div><?= field_label('Remarks') ?><input name="remarks" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"></div>
      <button class="w-full px-6 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold hover:opacity-90 transition">Issue Certificate</button>
    </form>
  </div>

  <div class="lg:col-span-2 rounded-2xl bg-white border border-slate-100 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead class="bg-slate-50 text-slate-500 text-left"><tr><th class="px-5 py-3 font-semibold">Certificate</th><th class="px-5 py-3 font-semibold">Student</th><th class="px-5 py-3 font-semibold">Course</th><th class="px-5 py-3 font-semibold">Grade</th><th class="px-5 py-3 font-semibold text-right">Action</th></tr></thead>
        <tbody class="divide-y divide-slate-100">
          <?php foreach ($rows as $r): ?>
            <tr class="hover:bg-slate-50">
              <td class="px-5 py-4"><p class="font-mono text-xs text-slate-700"><?= e($r['certificate_no']) ?></p><p class="text-xs text-slate-400"><?= fmt_date($r['issue_date']) ?></p></td>
              <td class="px-5 py-4"><p class="font-medium text-slate-900"><?= e($r['student']) ?></p><p class="text-xs text-slate-400"><?= e($r['student_code']) ?></p></td>
              <td class="px-5 py-4 text-slate-600"><?= e($r['course']) ?></td>
              <td class="px-5 py-4"><span class="px-2.5 py-1 rounded-full bg-emerald-100 text-emerald-700 text-xs font-semibold"><?= e($r['grade']) ?></span></td>
              <td class="px-5 py-4 text-right whitespace-nowrap">
                <a href="<?= url('verify.php?code=' . urlencode($r['certificate_no'])) ?>" target="_blank" class="text-brand-600 font-semibold">Verify</a>
                <form method="post" class="inline" onsubmit="return confirm('Delete certificate?')"><?= csrf_field() ?><input type="hidden" name="do" value="delete"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button class="ml-3 text-rose-600 font-semibold">Delete</button></form>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if(!$rows): ?><tr><td colspan="5" class="px-5 py-10 text-center text-slate-400">No certificates issued yet.</td></tr><?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php admin_layout_bottom(); ?>
