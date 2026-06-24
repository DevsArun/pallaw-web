<?php
require_once __DIR__ . '/functions.php';

/**
 * Render a printable fee receipt page.
 * @param array $pay     payment row (must include receipt_no, amount, mode, status, paid_on, remarks, course_title)
 * @param array $student student row (name, student_code, email, phone)
 */
function render_receipt(array $pay, array $student): void
{
    $site = setting('site_name');
    ?>
<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pay['receipt_no']) ?> · Fee Receipt</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
<style>body{font-family:'Inter',sans-serif}.disp{font-family:'Space Grotesk',sans-serif}</style>
</head><body class="bg-slate-100 p-4 sm:p-8">
  <div class="max-w-2xl mx-auto mb-4 flex justify-between items-center no-print">
    <button onclick="history.back()" class="text-sm font-semibold text-slate-600 hover:text-slate-900">← Back</button>
    <button onclick="window.print()" class="px-5 py-2.5 rounded-full bg-slate-900 text-white text-sm font-semibold hover:bg-brand-600">🖨 Print / Download PDF</button>
  </div>

  <div class="print-area max-w-2xl mx-auto bg-white shadow-2xl rounded-2xl overflow-hidden">
    <!-- Header -->
    <div class="bg-slate-950 text-white p-6 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <span class="grid place-items-center w-11 h-11 rounded-xl bg-gradient-to-br from-brand-500 to-violet-600 disp font-bold text-lg">N</span>
        <div><p class="disp font-bold text-lg"><?= e($site) ?></p><p class="text-xs text-slate-400"><?= e(setting('address')) ?></p></div>
      </div>
      <div class="text-right">
        <p class="text-xs uppercase tracking-wider text-slate-400">Fee Receipt</p>
        <?php $sb = $pay['status']==='paid' ? 'bg-emerald-500' : ($pay['status']==='partial' ? 'bg-amber-500' : 'bg-rose-500'); ?>
        <span class="inline-block mt-1 px-2.5 py-1 rounded-full <?= $sb ?> text-xs font-semibold capitalize"><?= e($pay['status']) ?></span>
      </div>
    </div>

    <div class="p-6 sm:p-8">
      <div class="grid sm:grid-cols-2 gap-4 text-sm">
        <div>
          <p class="text-xs text-slate-400 uppercase">Receipt No.</p>
          <p class="font-mono font-semibold text-slate-900"><?= e($pay['receipt_no']) ?></p>
        </div>
        <div class="sm:text-right">
          <p class="text-xs text-slate-400 uppercase">Date</p>
          <p class="font-semibold text-slate-900"><?= fmt_date($pay['paid_on']) ?></p>
        </div>
      </div>

      <div class="mt-6 p-4 rounded-xl bg-slate-50 grid sm:grid-cols-2 gap-3 text-sm">
        <div><p class="text-xs text-slate-400 uppercase">Received From</p><p class="font-semibold text-slate-900"><?= e($student['name']) ?></p></div>
        <div class="sm:text-right"><p class="text-xs text-slate-400 uppercase">Enrollment ID</p><p class="font-mono text-slate-900"><?= e($student['student_code']) ?></p></div>
        <div><p class="text-xs text-slate-400 uppercase">Email</p><p class="text-slate-900"><?= e($student['email']) ?></p></div>
        <div class="sm:text-right"><p class="text-xs text-slate-400 uppercase">Phone</p><p class="text-slate-900"><?= e($student['phone'] ?? '-') ?></p></div>
      </div>

      <table class="w-full mt-6 text-sm">
        <thead><tr class="text-left text-slate-400 border-b border-slate-200"><th class="py-2 font-medium">Description</th><th class="py-2 font-medium text-right">Amount</th></tr></thead>
        <tbody>
          <tr class="border-b border-slate-100"><td class="py-3"><?= e($pay['course_title'] ?? 'Course Fee') ?><?php if(!empty($pay['remarks'])): ?><br><span class="text-xs text-slate-400"><?= e($pay['remarks']) ?></span><?php endif; ?></td><td class="py-3 text-right font-semibold"><?= money($pay['amount']) ?></td></tr>
        </tbody>
        <tfoot>
          <tr><td class="py-3 text-right font-semibold">Total Paid</td><td class="py-3 text-right disp text-xl font-bold text-slate-900"><?= money($pay['amount']) ?></td></tr>
          <tr><td class="text-xs text-slate-400">Payment Mode</td><td class="text-right text-xs text-slate-500"><?= e($pay['mode']) ?></td></tr>
        </tfoot>
      </table>

      <div class="mt-10 flex items-end justify-between">
        <p class="text-xs text-slate-400 max-w-[60%]">This is a system-generated receipt issued by the institute administration. Please retain it for your records.</p>
        <div class="text-center"><div class="w-32 border-t border-slate-300 pt-2"></div><p class="text-xs text-slate-500">Authorized Signatory</p></div>
      </div>
    </div>
    <div class="bg-slate-50 px-6 py-3 text-center text-xs text-slate-400">Thank you · <?= e(setting('phone')) ?> · <?= e(setting('email')) ?></div>
  </div>
</body></html>
    <?php
}
