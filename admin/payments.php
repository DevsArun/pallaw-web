<?php
require_once __DIR__ . '/_layout.php';
$d = db();

/* ---- Print a receipt (reuse shared template) ---- */
$printId = (int)($_GET['print'] ?? 0);
if ($printId) {
    $stmt = $d->prepare("SELECT p.*, c.title AS course_title FROM payments p LEFT JOIN courses c ON c.id=p.course_id WHERE p.id=?");
    $stmt->execute([$printId]); $pay = $stmt->fetch();
    if ($pay) {
        $st = $d->prepare("SELECT * FROM students WHERE id=?"); $st->execute([$pay['student_id']]); $stu = $st->fetch();
        require __DIR__ . '/../includes/receipt.php';
        render_receipt($pay, $stu); exit;
    }
    redirect('admin/payments.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) { flash('error','Session expired.'); redirect('admin/payments.php'); }
    $do = $_POST['do'] ?? '';
    if ($do === 'delete') {
        $d->prepare("DELETE FROM payments WHERE id=?")->execute([(int)$_POST['id']]);
        flash('success','Receipt deleted.');
    } elseif ($do === 'save') {
        $sid = (int)$_POST['student_id'];
        $cid = $_POST['course_id'] ?: null;
        $amount = (float)$_POST['amount'];
        $mode = $_POST['mode'] ?? 'Cash';
        $status = $_POST['status'] ?? 'paid';
        $paid_on = $_POST['paid_on'] ?: date('Y-m-d');
        $remarks = trim($_POST['remarks'] ?? '');
        if (!$sid || $amount <= 0) { flash('error','Select student and enter a valid amount.'); redirect('admin/payments.php'); }
        $receipt = next_sequence('payments','receipt_no','RCPT');
        $d->prepare("INSERT INTO payments (receipt_no,student_id,course_id,amount,mode,status,paid_on,remarks,generated_by) VALUES (?,?,?,?,?,?,?,?,?)")
          ->execute([$receipt,$sid,$cid,$amount,$mode,$status,$paid_on,$remarks,$_SESSION['admin_id']]);
        flash('success', "Fee receipt $receipt generated.");
    }
    redirect('admin/payments.php');
}

$students = $d->query("SELECT id,name,student_code FROM students ORDER BY name")->fetchAll();
$courses  = $d->query("SELECT id,title FROM courses ORDER BY title")->fetchAll();
$rows = $d->query("SELECT p.*, s.name AS student, c.title AS course FROM payments p JOIN students s ON s.id=p.student_id LEFT JOIN courses c ON c.id=p.course_id ORDER BY p.id DESC")->fetchAll();
$total = $d->query("SELECT COALESCE(SUM(amount),0) FROM payments WHERE status IN('paid','partial')")->fetchColumn();

admin_layout_top('payments', 'Fee Receipts');
?>
<div class="grid lg:grid-cols-3 gap-6">
  <!-- Generate -->
  <div class="rounded-2xl bg-white border border-slate-100 p-6 h-fit">
    <h3 class="font-display text-lg font-bold text-slate-900 mb-1">Generate Receipt</h3>
    <p class="text-xs text-slate-400 mb-4">Receipt number is auto-generated.</p>
    <form method="post" class="space-y-4">
      <?= csrf_field() ?><input type="hidden" name="do" value="save">
      <div><?= field_label('Student') ?><select name="student_id" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"><option value="">Select student…</option><?php foreach($students as $s): ?><option value="<?= $s['id'] ?>"><?= e($s['name']) ?> (<?= e($s['student_code']) ?>)</option><?php endforeach; ?></select></div>
      <div><?= field_label('Course (optional)') ?><select name="course_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"><option value="">— General —</option><?php foreach($courses as $c): ?><option value="<?= $c['id'] ?>"><?= e($c['title']) ?></option><?php endforeach; ?></select></div>
      <div class="grid grid-cols-2 gap-3">
        <div><?= field_label('Amount (₹)') ?><input type="number" step="0.01" name="amount" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"></div>
        <div><?= field_label('Date') ?><input type="date" name="paid_on" value="<?= date('Y-m-d') ?>" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"></div>
      </div>
      <div class="grid grid-cols-2 gap-3">
        <div><?= field_label('Mode') ?><select name="mode" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"><?php foreach(['Cash','UPI','Card','Bank Transfer','Cheque'] as $m): ?><option><?= $m ?></option><?php endforeach; ?></select></div>
        <div><?= field_label('Status') ?><select name="status" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"><?php foreach(['paid','partial','pending'] as $st): ?><option><?= $st ?></option><?php endforeach; ?></select></div>
      </div>
      <div><?= field_label('Remarks') ?><input name="remarks" placeholder="e.g. Installment 1 of 3" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"></div>
      <button class="w-full px-6 py-3 rounded-xl bg-gradient-to-r from-brand-500 to-violet-600 text-white font-semibold hover:opacity-90 transition">Generate Receipt</button>
    </form>
  </div>

  <!-- List -->
  <div class="lg:col-span-2 space-y-4">
    <div class="rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 text-white p-5 flex items-center justify-between">
      <div><p class="text-white/80 text-sm">Total Collected</p><p class="font-display text-2xl font-bold"><?= money($total) ?></p></div><span class="text-3xl">💰</span>
    </div>
    <div class="rounded-2xl bg-white border border-slate-100 overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-slate-50 text-slate-500 text-left"><tr><th class="px-5 py-3 font-semibold">Receipt</th><th class="px-5 py-3 font-semibold">Student</th><th class="px-5 py-3 font-semibold">Amount</th><th class="px-5 py-3 font-semibold">Status</th><th class="px-5 py-3 font-semibold text-right">Action</th></tr></thead>
          <tbody class="divide-y divide-slate-100">
            <?php foreach ($rows as $r): $sb=$r['status']==='paid'?'bg-emerald-100 text-emerald-700':($r['status']==='partial'?'bg-amber-100 text-amber-700':'bg-rose-100 text-rose-700'); ?>
              <tr class="hover:bg-slate-50">
                <td class="px-5 py-4"><p class="font-mono text-xs text-slate-700"><?= e($r['receipt_no']) ?></p><p class="text-xs text-slate-400"><?= fmt_date($r['paid_on']) ?></p></td>
                <td class="px-5 py-4"><p class="font-medium text-slate-900"><?= e($r['student']) ?></p><p class="text-xs text-slate-400"><?= e($r['course'] ?: '—') ?></p></td>
                <td class="px-5 py-4 font-semibold text-slate-900"><?= money($r['amount']) ?></td>
                <td class="px-5 py-4"><span class="px-2.5 py-1 rounded-full text-xs font-semibold <?= $sb ?> capitalize"><?= e($r['status']) ?></span></td>
                <td class="px-5 py-4 text-right whitespace-nowrap">
                  <a href="<?= url('admin/payments.php?print=' . $r['id']) ?>" target="_blank" class="text-brand-600 font-semibold">Print</a>
                  <form method="post" class="inline" onsubmit="return confirm('Delete receipt?')"><?= csrf_field() ?><input type="hidden" name="do" value="delete"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button class="ml-3 text-rose-600 font-semibold">Delete</button></form>
                </td>
              </tr>
            <?php endforeach; ?>
            <?php if(!$rows): ?><tr><td colspan="5" class="px-5 py-10 text-center text-slate-400">No receipts yet.</td></tr><?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php admin_layout_bottom(); ?>
