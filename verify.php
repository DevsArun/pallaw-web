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

<section class="bg-slate-950 text-white relative overflow-hidden">
  <div class="hero-aurora"><span></span><span></span><span></span></div>
  <div class="relative max-w-3xl mx-auto px-4 sm:px-6 py-16 text-center">
    <span class="text-sm font-semibold text-brand-400 uppercase tracking-wider">Trust & Authenticity</span>
    <h1 class="mt-3 font-display text-4xl sm:text-5xl font-bold">Verify a Certificate</h1>
    <p class="mt-4 text-slate-300">Enter the certificate number printed on the document to confirm its authenticity.</p>
    <form method="get" class="mt-8 flex flex-col sm:flex-row gap-2 max-w-xl mx-auto">
      <input name="code" value="<?= e($code) ?>" placeholder="e.g. NEX-CERT-2026-0001" required
             class="flex-1 px-5 py-3.5 rounded-full bg-white/10 border border-white/15 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-400 backdrop-blur">
      <button class="px-7 py-3.5 rounded-full bg-white text-slate-900 font-semibold hover:bg-brand-50 transition">Verify</button>
    </form>
  </div>
</section>

<section class="bg-slate-50 py-16 min-h-[40vh]">
  <div class="max-w-3xl mx-auto px-4 sm:px-6">
    <?php if ($searched && $cert): ?>
      <div class="rounded-2xl bg-white border border-emerald-200 overflow-hidden shadow-xl shadow-emerald-900/5">
        <div class="bg-emerald-500 text-white px-6 py-4 flex items-center gap-3">
          <span class="grid place-items-center w-10 h-10 rounded-full bg-white/20 text-xl">✓</span>
          <div><p class="font-bold text-lg">Certificate Verified</p><p class="text-sm text-emerald-50">This is an authentic certificate issued by <?= e(setting('site_name')) ?>.</p></div>
        </div>
        <div class="p-6 sm:p-8 grid sm:grid-cols-2 gap-5">
          <div><p class="text-xs text-slate-400 uppercase tracking-wide">Certificate No.</p><p class="font-semibold text-slate-900"><?= e($cert['certificate_no']) ?></p></div>
          <div><p class="text-xs text-slate-400 uppercase tracking-wide">Issue Date</p><p class="font-semibold text-slate-900"><?= fmt_date($cert['issue_date']) ?></p></div>
          <div><p class="text-xs text-slate-400 uppercase tracking-wide">Student Name</p><p class="font-semibold text-slate-900"><?= e($cert['student_name']) ?></p></div>
          <div><p class="text-xs text-slate-400 uppercase tracking-wide">Enrollment ID</p><p class="font-semibold text-slate-900"><?= e($cert['student_code']) ?></p></div>
          <div><p class="text-xs text-slate-400 uppercase tracking-wide">Course</p><p class="font-semibold text-slate-900"><?= e($cert['course_title']) ?></p></div>
          <div><p class="text-xs text-slate-400 uppercase tracking-wide">Grade</p><p class="font-semibold text-slate-900"><?= e($cert['grade']) ?></p></div>
        </div>
      </div>
    <?php elseif ($searched && !$cert): ?>
      <div class="rounded-2xl bg-white border border-rose-200 p-8 text-center shadow-xl shadow-rose-900/5">
        <span class="grid place-items-center w-14 h-14 rounded-full bg-rose-100 text-rose-600 text-2xl mx-auto">✕</span>
        <h2 class="mt-4 font-display text-2xl font-bold text-slate-900">No matching certificate</h2>
        <p class="mt-2 text-slate-500">We couldn't find a certificate with the number <span class="font-semibold text-slate-700">"<?= e($code) ?>"</span>. Please double-check and try again.</p>
      </div>
    <?php else: ?>
      <div class="rounded-2xl bg-white border border-slate-100 p-8 text-center">
        <span class="text-5xl">🔐</span>
        <p class="mt-4 text-slate-500">Enter a certificate number above to verify its authenticity.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
