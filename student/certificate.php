<?php
require_once __DIR__ . '/_layout.php';
$sid = $student['id'];

/* ---- Printable single certificate view ---- */
$viewId = (int)($_GET['view'] ?? 0);
if ($viewId) {
    $stmt = db()->prepare(
      "SELECT cert.*, c.title AS course_title FROM certificates cert
       JOIN courses c ON c.id=cert.course_id
       WHERE cert.id=? AND cert.student_id=? LIMIT 1"
    );
    $stmt->execute([$viewId, $sid]);
    $cert = $stmt->fetch();
    if (!$cert) { redirect('student/certificate.php'); }
    $site = setting('site_name');
    ?>
    <!DOCTYPE html><html lang="en"><head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($cert['certificate_no']) ?> · Certificate</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
    <style>.serif{font-family:'Playfair Display',serif}.disp{font-family:'Space Grotesk',sans-serif}body{font-family:'Inter',sans-serif}</style>
    </head><body class="bg-slate-100 p-4 sm:p-8">
      <div class="max-w-4xl mx-auto mb-4 flex justify-between items-center no-print">
        <a href="<?= url('student/certificate.php') ?>" class="text-sm font-semibold text-slate-600 hover:text-slate-900">← Back</a>
        <button onclick="window.print()" class="px-5 py-2.5 rounded-full bg-slate-900 text-white text-sm font-semibold hover:bg-brand-600">🖨 Print / Download PDF</button>
      </div>
      <div class="print-area max-w-4xl mx-auto bg-white shadow-2xl">
        <div class="m-3 border-[3px] border-brand-600 p-8 sm:p-14 text-center relative overflow-hidden">
          <div class="absolute inset-0 bg-grid opacity-30"></div>
          <div class="relative">
            <div class="flex items-center justify-center gap-3">
              <span class="grid place-items-center w-12 h-12 rounded-xl bg-gradient-to-br from-brand-500 to-violet-600 text-white disp font-bold text-xl">N</span>
              <span class="disp font-bold text-2xl text-slate-900"><?= e($site) ?></span>
            </div>
            <p class="mt-8 text-sm tracking-[0.3em] text-slate-400 uppercase">Certificate of Completion</p>
            <p class="mt-6 text-slate-500">This is proudly presented to</p>
            <h1 class="serif text-4xl sm:text-5xl text-slate-900 mt-3 mb-4"><?= e($student['name']) ?></h1>
            <p class="text-slate-500 max-w-xl mx-auto">for successfully completing the program</p>
            <h2 class="disp text-2xl font-bold text-brand-700 mt-2"><?= e($cert['course_title']) ?></h2>
            <p class="mt-3 text-slate-500">with grade <span class="font-bold text-slate-900"><?= e($cert['grade']) ?></span></p>

            <div class="mt-12 grid grid-cols-3 gap-4 items-end text-xs">
              <div class="text-center"><p class="font-semibold text-slate-900 border-t border-slate-300 pt-2"><?= fmt_date($cert['issue_date']) ?></p><p class="text-slate-400">Date of Issue</p></div>
              <div class="text-center">
                <div class="w-20 h-20 mx-auto rounded-full border-2 border-brand-600 grid place-items-center text-brand-600 disp font-bold text-[10px] leading-tight">OFFICIAL<br>SEAL</div>
              </div>
              <div class="text-center"><p class="font-semibold text-slate-900 border-t border-slate-300 pt-2">Director</p><p class="text-slate-400">Authorized Signatory</p></div>
            </div>

            <div class="mt-10 pt-5 border-t border-slate-200 flex flex-col sm:flex-row justify-between gap-2 text-xs text-slate-400">
              <span>Certificate No: <span class="font-mono text-slate-700"><?= e($cert['certificate_no']) ?></span></span>
              <span>Verify at: <?= e($_SERVER['HTTP_HOST'] ?? 'your-domain.com') ?><?= url('verify.php?code=' . urlencode($cert['certificate_no'])) ?></span>
            </div>
          </div>
        </div>
      </div>
    </body></html>
    <?php
    exit;
}

/* ---- List ---- */
$certs = db()->prepare(
  "SELECT cert.*, c.title AS course_title FROM certificates cert
   JOIN courses c ON c.id=cert.course_id
   WHERE cert.student_id=? ORDER BY cert.id DESC"
);
$certs->execute([$sid]);
$certs = $certs->fetchAll();

student_layout_top('certificate', 'Certificates');
?>

<?php if (!$certs): ?>
  <div class="rounded-2xl bg-white border border-slate-100 p-12 text-center">
    <p class="text-5xl">📜</p>
    <p class="mt-4 text-lg font-semibold text-slate-700">No certificates yet</p>
    <p class="text-slate-500 mt-1">Certificates are issued by admin once you complete a course. They'll show up here.</p>
  </div>
<?php else: ?>
  <div class="grid sm:grid-cols-2 gap-5">
    <?php foreach ($certs as $c): ?>
      <div class="rounded-2xl bg-white border border-slate-100 overflow-hidden">
        <div class="h-24 bg-gradient-to-br from-emerald-500 to-teal-600 p-4 flex items-center justify-between text-white">
          <span class="text-3xl">📜</span>
          <span class="px-2.5 py-1 rounded-full bg-white/20 text-xs font-semibold">Grade <?= e($c['grade']) ?></span>
        </div>
        <div class="p-5">
          <h3 class="font-semibold text-slate-900"><?= e($c['course_title']) ?></h3>
          <p class="mt-1 text-xs text-slate-400 font-mono"><?= e($c['certificate_no']) ?></p>
          <p class="mt-1 text-xs text-slate-400">Issued <?= fmt_date($c['issue_date']) ?></p>
          <div class="mt-4 flex gap-2">
            <a href="<?= url('student/certificate.php?view=' . $c['id']) ?>" class="flex-1 text-center px-4 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-semibold hover:bg-brand-600 transition">View / Download</a>
            <a href="<?= url('verify.php?code=' . urlencode($c['certificate_no'])) ?>" target="_blank" class="px-4 py-2.5 rounded-xl border border-slate-200 text-sm font-semibold text-slate-700 hover:border-brand-300 transition">Verify</a>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php student_layout_bottom(); ?>
