<?php
require_once __DIR__ . '/../includes/functions.php';
if (admin_logged_in()) redirect('admin/index.php');

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $error = 'Session expired. Please try again.';
    } else {
        $u = trim($_POST['username'] ?? '');
        $p = $_POST['password'] ?? '';
        $stmt = db()->prepare("SELECT * FROM admins WHERE username = ? OR email = ? LIMIT 1");
        $stmt->execute([$u, $u]);
        $a = $stmt->fetch();
        if ($a && password_verify($p, $a['password_hash'])) {
            session_regenerate_id(true);
            $_SESSION['admin_id'] = $a['id'];
            redirect('admin/index.php');
        }
        $error = 'Invalid username or password.';
    }
}
$site = setting('site_name');
?>
<!DOCTYPE html><html lang="en"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login · <?= e($site) ?></title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Space+Grotesk:wght@600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config={theme:{extend:{fontFamily:{sans:['Inter','sans-serif'],display:['Space Grotesk','sans-serif']},colors:{brand:{50:'#eef2ff',400:'#818cf8',500:'#6366f1',600:'#4f46e5',700:'#4338ca'}}}}};</script>
<link rel="stylesheet" href="<?= url('assets/css/style.css') ?>">
</head>
<body class="font-sans bg-slate-950 min-h-screen grid place-items-center p-4 relative overflow-hidden">
  <div class="hero-aurora"><span></span><span></span><span></span></div>
  <div class="relative w-full max-w-sm">
    <div class="text-center mb-6">
      <span class="grid place-items-center w-14 h-14 rounded-2xl bg-gradient-to-br from-brand-500 to-violet-600 text-white font-display font-bold text-2xl mx-auto">N</span>
      <h1 class="mt-4 font-display text-2xl font-bold text-white"><?= e($site) ?></h1>
      <p class="text-sm text-slate-400">Admin Console</p>
    </div>
    <div class="rounded-2xl bg-white p-7 shadow-2xl">
      <?php if ($error): ?>
        <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 text-rose-800 px-4 py-3 text-sm"><?= e($error) ?></div>
      <?php endif; ?>
      <form method="post" class="space-y-4">
        <?= csrf_field() ?>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Username or Email</label>
          <input name="username" value="<?= e($_POST['username'] ?? '') ?>" required autofocus class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400">
        </div>
        <div>
          <label class="block text-sm font-medium text-slate-700 mb-1.5">Password</label>
          <input type="password" name="password" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400">
        </div>
        <button class="w-full px-5 py-3.5 rounded-xl bg-gradient-to-r from-brand-500 to-violet-600 text-white font-semibold hover:opacity-90 transition">Sign In</button>
      </form>
      <div class="mt-5 rounded-xl bg-slate-50 border border-slate-100 p-3 text-xs text-slate-500">
        <p class="font-semibold text-slate-700 mb-0.5">Demo credentials</p>
        Username: <span class="font-mono">admin</span> · Password: <span class="font-mono">admin@123</span>
      </div>
    </div>
    <p class="mt-6 text-center text-sm text-slate-500"><a href="<?= url('index.php') ?>" class="hover:text-white">← Back to website</a></p>
  </div>
</body></html>
