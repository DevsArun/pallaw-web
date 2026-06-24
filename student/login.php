<?php
require_once __DIR__ . '/../includes/functions.php';
if (student_logged_in()) redirect('student/dashboard.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $error = 'Session expired. Please try again.';
    } else {
        $email = trim($_POST['email'] ?? '');
        $pass  = $_POST['password'] ?? '';
        $stmt = db()->prepare("SELECT * FROM students WHERE email = ? AND status='active' LIMIT 1");
        $stmt->execute([$email]);
        $s = $stmt->fetch();
        if ($s && password_verify($pass, $s['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['student_id'] = $s['id'];
            redirect('student/dashboard.php');
        }
        $error = 'Invalid email or password.';
    }
}
$site = setting('site_name');
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Login · <?= e($site) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config={theme:{extend:{fontFamily:{sans:['Inter','sans-serif'],display:['Space Grotesk','sans-serif']},colors:{brand:{50:'#eef2ff',400:'#818cf8',500:'#6366f1',600:'#4f46e5',700:'#4338ca'}}}}};</script>
<link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
</head>
<body class="font-sans bg-slate-950 min-h-screen grid lg:grid-cols-2">
  <!-- Brand side -->
  <div class="relative hidden lg:flex flex-col justify-between p-12 text-white overflow-hidden">
    <div class="hero-aurora"><span></span><span></span><span></span></div>
    <a href="<?= url('index.php') ?>" class="relative flex items-center gap-3">
      <span class="grid place-items-center w-11 h-11 rounded-xl bg-gradient-to-br from-brand-500 to-violet-600 font-display font-bold text-lg">N</span>
      <span class="font-display font-bold text-xl"><?= e($site) ?></span>
    </a>
    <div class="relative">
      <h2 class="font-display text-4xl font-bold leading-tight">Welcome back to your<br>learning journey.</h2>
      <p class="mt-4 text-slate-300 max-w-md">Access your courses, certificates and fee receipts — all in one place.</p>
    </div>
    <p class="relative text-sm text-slate-400">&copy; <?= date('Y') ?> <?= e($site) ?></p>
  </div>

  <!-- Form side -->
  <div class="flex items-center justify-center p-6 bg-white">
    <div class="w-full max-w-sm">
      <a href="<?= url('index.php') ?>" class="lg:hidden flex items-center gap-2 mb-8 justify-center">
        <span class="grid place-items-center w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-violet-600 text-white font-display font-bold">N</span>
        <span class="font-display font-bold text-lg text-slate-900"><?= e($site) ?></span>
      </a>
      <h1 class="font-display text-3xl font-bold text-slate-900">Student Login</h1>
      <p class="mt-2 text-slate-500">Enter your credentials to continue.</p>

      <?php if ($error): ?>
        <div class="mt-6 rounded-xl border border-rose-200 bg-rose-50 text-rose-800 px-4 py-3 text-sm"><?= e($error) ?></div>
      <?php endif; ?>

      <form method="post" class="mt-6 space-y-4">
        <?= csrf_field() ?>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
          <input type="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" required autofocus class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400">
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
          <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400">
        </div>
        <button class="w-full px-5 py-3.5 rounded-xl bg-gradient-to-r from-brand-500 to-violet-600 text-white font-semibold hover:opacity-90 transition">Sign In</button>
      </form>

      <div class="mt-6 rounded-xl bg-slate-50 border border-slate-100 p-4 text-xs text-slate-500">
        <p class="font-semibold text-slate-700 mb-1">Demo credentials</p>
        Email: <span class="font-mono">student@nexora.com</span><br>
        Password: <span class="font-mono">student@123</span>
      </div>
      <p class="mt-6 text-center text-sm text-slate-500">Not enrolled yet? <a href="<?= url('courses.php') ?>" class="font-semibold text-brand-600 hover:text-brand-700">Browse courses</a></p>
    </div>
  </div>
</body>
</html>
