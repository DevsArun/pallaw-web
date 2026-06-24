<?php
require_once __DIR__ . '/_layout.php';
$d = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) { flash('error','Session expired.'); redirect('admin/contacts.php'); }
    $do = $_POST['do'] ?? '';
    if ($do === 'delete') { $d->prepare("DELETE FROM contacts WHERE id=?")->execute([(int)$_POST['id']]); flash('success','Enquiry deleted.'); }
    elseif ($do === 'status') { $d->prepare("UPDATE contacts SET status=? WHERE id=?")->execute([$_POST['status'],(int)$_POST['id']]); flash('success','Status updated.'); }
    redirect('admin/contacts.php');
}

$rows = $d->query("SELECT * FROM contacts ORDER BY id DESC")->fetchAll();
admin_layout_top('contacts', 'Enquiries');
?>
<p class="text-slate-500 text-sm mb-6"><?= count($rows) ?> enquiries</p>
<?php if(!$rows): ?>
  <div class="rounded-2xl bg-white border border-slate-100 p-12 text-center"><p class="text-5xl">✉️</p><p class="mt-4 text-slate-500">No enquiries yet.</p></div>
<?php else: ?>
  <div class="space-y-4">
    <?php foreach ($rows as $c): $sb=$c['status']==='new'?'bg-amber-100 text-amber-700':($c['status']==='responded'?'bg-emerald-100 text-emerald-700':'bg-slate-100 text-slate-600'); ?>
      <div class="rounded-2xl bg-white border border-slate-100 p-5">
        <div class="flex flex-wrap items-start justify-between gap-3">
          <div>
            <div class="flex items-center gap-2"><h3 class="font-semibold text-slate-900"><?= e($c['name']) ?></h3><span class="px-2.5 py-1 rounded-full text-xs font-semibold <?= $sb ?> capitalize"><?= e($c['status']) ?></span></div>
            <p class="text-sm text-slate-400 mt-0.5"><a href="mailto:<?= e($c['email']) ?>" class="hover:text-brand-600"><?= e($c['email']) ?></a> <?php if($c['phone']): ?>· <a href="tel:<?= e($c['phone']) ?>" class="hover:text-brand-600"><?= e($c['phone']) ?></a><?php endif; ?></p>
          </div>
          <span class="text-xs text-slate-400"><?= fmt_date($c['created_at'],'d M Y, h:i A') ?></span>
        </div>
        <?php if($c['subject']): ?><p class="mt-3 text-sm font-medium text-slate-700"><?= e($c['subject']) ?></p><?php endif; ?>
        <p class="mt-1 text-sm text-slate-600 leading-relaxed"><?= nl2br(e($c['message'])) ?></p>
        <div class="mt-4 flex items-center gap-3">
          <form method="post" class="inline"><?= csrf_field() ?><input type="hidden" name="do" value="status"><input type="hidden" name="id" value="<?= $c['id'] ?>">
            <select name="status" onchange="this.form.submit()" class="px-3 py-1.5 rounded-lg border border-slate-200 text-xs capitalize"><?php foreach(['new','read','responded'] as $st): ?><option <?= $c['status']===$st?'selected':'' ?>><?= $st ?></option><?php endforeach; ?></select>
          </form>
          <a href="mailto:<?= e($c['email']) ?>" class="text-sm font-semibold text-brand-600">Reply →</a>
          <form method="post" class="inline ml-auto" onsubmit="return confirm('Delete enquiry?')"><?= csrf_field() ?><input type="hidden" name="do" value="delete"><input type="hidden" name="id" value="<?= $c['id'] ?>"><button class="text-sm text-rose-600 font-semibold">Delete</button></form>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
<?php admin_layout_bottom(); ?>
