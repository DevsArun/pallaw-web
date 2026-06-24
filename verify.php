<?php
require_once __DIR__ . '/includes/functions.php';
$page = 'verify';
$page_title = 'Verify Certificate';

$code = trim($_GET['code'] ?? '');
$cert = null;
$searched = false;

if ($code !== '') {
    $searched = true;
    $stmt = db()->prepare(
      "SELECT cert.*, s.name AS student_name, s.student_code, c.title AS course_title, c.duration
       FROM certificates cert
       JOIN students s ON s.id = cert.student_id
       JOIN courses  c ON c.id = cert.course_id
       WHERE cert.certificate_no = ? LIMIT 1"
    );
    $stmt->execute([$code]);
    $cert = $stmt->fetch();
}

include __DIR__ . '/includes/header.php';
?>

<section class="relative overflow-hidden bg-ink text-white">
  <div class="hero-aurora"><span></span><span></span><span></span></div>
  <div class="absolute inset-0 bg-grid"></div>
  <div class="relative max-w-3xl mx-auto px-4 sm:px-6 py-16 text-center">
    <span class="inline-flex items-center gap-2 text-sm font-semibold text-brand-400"><?= icon('shield-check','w-4 h-4') ?> Trust &amp; Authenticity</span>
    <h1 class="mt-3 font-display text-4xl sm:text-5xl font-bold tracking-tightest">Verify a Certificate</h1>
    <p class="mt-4 text-slate-300">Enter the certificate number printed on the document to confirm its authenticity.</p>
    <form method="get" class="mt-8 flex flex-col sm:flex-row gap-2 max-w-xl mx-auto">
      <div class="relative flex-1">
        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"><?= icon('award','w-5 h-5') ?></span>
        <input name="code" value="<?= e($code) ?>" placeholder="e.g. NEX-CERT-2026-0001" required
               class="w-full pl-12 pr-4 py-3.5 rounded-full bg-white/10 border border-white/15 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-400 backdrop-blur">
      </div>
      <button class="btn-shine px-7 py-3.5 rounded-full bg-white text-ink font-semibold hover:bg-brand-50 transition">Verify</button>
    </form>
  </div>
</section>

<section class="bg-slate-50 py-16 min-h-[40vh]">
  <div class="max-w-3xl mx-auto px-4 sm:px-6">
    <?php if ($searched && $cert): ?>
      <div class="rounded-2xl bg-white border border-emerald-200 overflow-hidden shadow-glow">
        <div class="bg-emerald-500 text-white px-6 py-5 flex items-center gap-3">
          <span class="grid place-items-center w-11 h-11 rounded-full bg-white/20"><?= icon('check-circle','w-6 h-6') ?></span>
          <div><p class="font-bold text-lg">Certificate Verified</p><p class="text-sm text-emerald-50">Authentic certificate issued by <?= e(setting('site_name')) ?>.</p></div>
        </div>
        <div class="p-6 sm:p-8 grid sm:grid-cols-2 gap-5">
          <?php foreach ([
            ['Certificate No.', $cert['certificate_no']],
            ['Issue Date', fmt_date($cert['issue_date'])],
            ['Student Name', $cert['student_name']],
            ['Enrollment ID', $cert['student_code']],
            ['Course', $cert['course_title']],
            ['Grade', $cert['grade']],
          ] as $row): ?>
            <div><p class="text-xs text-slate-400 uppercase tracking-wide"><?= $row[0] ?></p><p class="font-semibold text-ink mt-0.5"><?= e($row[1]) ?></p></div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php elseif ($searched && !$cert): ?>
      <div class="rounded-2xl bg-white border border-rose-200 p-8 text-center shadow-card">
        <span class="grid place-items-center w-14 h-14 rounded-full bg-rose-100 text-rose-600 mx-auto"><?= icon('x-circle','w-7 h-7') ?></span>
        <h2 class="mt-4 font-display text-2xl font-bold text-ink">No matching certificate</h2>
        <p class="mt-2 text-slate-500">We couldn't find a certificate with the number <span class="font-semibold text-slate-700">"<?= e($code) ?>"</span>. Please double-check and try again.</p>
      </div>
    <?php else: ?>
      <div class="rounded-2xl bg-white border border-slate-100 p-10 text-center shadow-card">
        <span class="inline-grid place-items-center w-16 h-16 rounded-2xl bg-brand-50 text-brand-600 mx-auto"><?= icon('lock','w-7 h-7') ?></span>
        <p class="mt-5 text-slate-500">Enter a certificate number above to verify its authenticity.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
