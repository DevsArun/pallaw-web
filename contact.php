<?php
require_once __DIR__ . '/includes/functions.php';
$page = 'contact';
$page_title = 'Contact';

$prefillCourse = trim($_GET['course'] ?? '');
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) {
        $errors[] = 'Session expired. Please try again.';
    } else {
        $name    = trim($_POST['name'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $phone   = trim($_POST['phone'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if ($name === '')  $errors[] = 'Please enter your name.';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email.';
        if ($message === '') $errors[] = 'Please enter a message.';

        if (!$errors) {
            $stmt = db()->prepare("INSERT INTO contacts (name, email, phone, subject, message) VALUES (?,?,?,?,?)");
            $stmt->execute([$name, $email, $phone, $subject, $message]);
            flash('success', 'Thank you! Your message has been received. Our team will reach out shortly.');
            redirect('contact.php');
        }
    }
}

include __DIR__ . '/includes/header.php';
?>

<section class="relative overflow-hidden bg-ink text-white">
  <div class="hero-aurora"><span></span><span></span><span></span></div>
  <div class="absolute inset-0 bg-grid"></div>
  <div class="relative max-w-7xl mx-auto px-4 sm:px-6 py-16 text-center">
    <span class="inline-flex items-center gap-2 text-sm font-semibold text-brand-400"><?= icon('headset','w-4 h-4') ?> Get in touch</span>
    <h1 class="mt-3 font-display text-4xl sm:text-5xl font-bold tracking-tightest">Let's talk about your goals</h1>
    <p class="mt-4 text-slate-300 max-w-2xl mx-auto text-lg">Have a question about a course, fees or admissions? Send us a message or call directly.</p>
  </div>
</section>

<section class="bg-slate-50 py-16">
  <div class="max-w-6xl mx-auto px-4 sm:px-6 grid lg:grid-cols-5 gap-8">
    <div class="lg:col-span-2 space-y-4">
      <?php foreach ([
        ['phone','Call us', setting('phone'), 'tel:' . setting('phone')],
        ['mail','Email', setting('email'), 'mailto:' . setting('email')],
      ] as $info): ?>
        <a href="<?= e($info[3]) ?>" class="card-lift flex items-center gap-4 p-5 rounded-2xl bg-white border border-slate-100 shadow-card">
          <span class="grid place-items-center w-12 h-12 rounded-xl bg-brand-50 text-brand-600 shrink-0"><?= icon($info[0],'w-5 h-5') ?></span>
          <div><p class="text-xs text-slate-400"><?= $info[1] ?></p><p class="font-semibold text-ink"><?= e($info[2]) ?></p></div>
        </a>
      <?php endforeach; ?>
      <div class="flex items-center gap-4 p-5 rounded-2xl bg-white border border-slate-100 shadow-card">
        <span class="grid place-items-center w-12 h-12 rounded-xl bg-brand-50 text-brand-600 shrink-0"><?= icon('map-pin','w-5 h-5') ?></span>
        <div><p class="text-xs text-slate-400">Visit us</p><p class="font-semibold text-ink text-sm"><?= e(setting('address')) ?></p></div>
      </div>
      <div class="rounded-2xl overflow-hidden border border-slate-100 h-56 shadow-card">
        <iframe src="<?= e(setting('map_embed')) ?>" class="w-full h-full" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Map"></iframe>
      </div>
    </div>

    <div class="lg:col-span-3">
      <div class="rounded-2xl bg-white border border-slate-100 p-6 sm:p-8 shadow-glow">
        <?= render_flashes() ?>
        <?php if ($errors): ?>
          <div class="mb-4 rounded-xl border border-rose-200 bg-rose-50 text-rose-800 px-4 py-3 text-sm space-y-1">
            <?php foreach ($errors as $err): ?><p class="flex items-center gap-2"><?= icon('x-circle','w-4 h-4') ?> <?= e($err) ?></p><?php endforeach; ?>
          </div>
        <?php endif; ?>
        <form method="post" class="space-y-4">
          <?= csrf_field() ?>
          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1.5">Full Name *</label>
              <input name="name" value="<?= e($_POST['name'] ?? '') ?>" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400 transition">
            </div>
            <div>
              <label class="block text-sm font-medium text-slate-700 mb-1.5">Phone</label>
              <input name="phone" value="<?= e($_POST['phone'] ?? '') ?>" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400 transition">
            </div>
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Email *</label>
            <input type="email" name="email" value="<?= e($_POST['email'] ?? '') ?>" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400 transition">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Subject</label>
            <input name="subject" value="<?= e($_POST['subject'] ?? ($prefillCourse ? 'Enquiry: ' . $prefillCourse : '')) ?>" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400 transition">
          </div>
          <div>
            <label class="block text-sm font-medium text-slate-700 mb-1.5">Message *</label>
            <textarea name="message" rows="4" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400 focus:border-brand-400 transition"><?= e($_POST['message'] ?? '') ?></textarea>
          </div>
          <button class="btn-shine inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-gradient-to-r from-brand-500 to-violet-600 text-white font-semibold hover:opacity-95 transition">Send Message <?= icon('arrow-right','w-4 h-4') ?></button>
        </form>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
