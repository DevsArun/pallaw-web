</main>

<!-- CTA strip -->
<section class="bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 pb-2">
    <div class="reveal relative overflow-hidden rounded-[28px] bg-ink px-6 py-14 sm:px-14 sm:py-16">
      <div class="hero-aurora"><span></span><span></span><span></span></div>
      <div class="absolute inset-0 bg-dots opacity-30"></div>
      <div class="relative grid lg:grid-cols-2 gap-8 items-center">
        <div>
          <h2 class="font-display text-3xl sm:text-4xl font-bold text-white tracking-tightest">Ready to upgrade your career?</h2>
          <p class="mt-3 text-slate-300 max-w-md">Book a free counselling session and find the program that fits your goals.</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 lg:justify-end">
          <a href="<?= url('contact.php') ?>" class="btn-shine inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-full bg-white text-ink font-semibold hover:bg-brand-50 transition">Book Free Demo</a>
          <a href="tel:<?= e(setting('phone')) ?>" class="inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-full bg-white/10 border border-white/20 text-white font-semibold hover:bg-white/15 transition backdrop-blur"><?= icon('phone','w-4 h-4') ?> Call Now</a>
        </div>
      </div>
    </div>
  </div>
</section>

<footer class="bg-white pt-16 pb-10 border-t border-slate-100 mt-8">
  <div class="max-w-7xl mx-auto px-4 sm:px-6">
    <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-12">
      <div class="lg:col-span-4">
        <a href="<?= url('index.php') ?>" class="flex items-center gap-2.5 mb-4">
          <span class="grid place-items-center w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-violet-600 text-white font-display font-bold text-lg">N</span>
          <span class="font-display font-bold text-xl text-ink tracking-tightest"><?= e(setting('site_name')) ?></span>
        </a>
        <p class="text-sm leading-relaxed text-slate-500 max-w-xs"><?= e(setting('about_short')) ?></p>
        <div class="flex gap-2.5 mt-6">
          <?php foreach (['facebook','instagram','linkedin','youtube'] as $k): ?>
            <a href="<?= e(setting($k, '#')) ?>" target="_blank" rel="noopener" aria-label="<?= e($k) ?>" class="grid place-items-center w-10 h-10 rounded-xl bg-slate-100 text-slate-500 hover:bg-ink hover:text-white transition"><?= icon($k,'w-[18px] h-[18px]') ?></a>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="lg:col-span-2">
        <h4 class="text-ink font-semibold mb-4 text-sm">Explore</h4>
        <ul class="space-y-3 text-sm text-slate-500">
          <li><a href="<?= url('courses.php') ?>" class="hover:text-brand-600 transition">All Courses</a></li>
          <li><a href="<?= url('projects.php') ?>" class="hover:text-brand-600 transition">Student Projects</a></li>
          <li><a href="<?= url('about.php') ?>" class="hover:text-brand-600 transition">About Us</a></li>
          <li><a href="<?= url('contact.php') ?>" class="hover:text-brand-600 transition">Contact</a></li>
        </ul>
      </div>

      <div class="lg:col-span-2">
        <h4 class="text-ink font-semibold mb-4 text-sm">Portals</h4>
        <ul class="space-y-3 text-sm text-slate-500">
          <li><a href="<?= url('student/login.php') ?>" class="hover:text-brand-600 transition">Student Login</a></li>
          <li><a href="<?= url('admin/login.php') ?>" class="hover:text-brand-600 transition">Admin Login</a></li>
          <li><a href="<?= url('verify.php') ?>" class="hover:text-brand-600 transition">Verify Certificate</a></li>
        </ul>
      </div>

      <div class="lg:col-span-4">
        <h4 class="text-ink font-semibold mb-4 text-sm">Get in touch</h4>
        <ul class="space-y-3 text-sm text-slate-500">
          <li><a href="tel:<?= e(setting('phone')) ?>" class="flex items-start gap-3 hover:text-brand-600 transition"><span class="text-brand-500 mt-0.5"><?= icon('phone','w-4 h-4') ?></span><?= e(setting('phone')) ?></a></li>
          <li><a href="mailto:<?= e(setting('email')) ?>" class="flex items-start gap-3 hover:text-brand-600 transition"><span class="text-brand-500 mt-0.5"><?= icon('mail','w-4 h-4') ?></span><?= e(setting('email')) ?></a></li>
          <li class="flex items-start gap-3"><span class="text-brand-500 mt-0.5"><?= icon('map-pin','w-4 h-4') ?></span><?= e(setting('address')) ?></li>
        </ul>
      </div>
    </div>

    <div class="mt-12 pt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-400">
      <p>&copy; <?= date('Y') ?> <?= e(setting('site_name')) ?>. All rights reserved.</p>
      <p class="flex items-center gap-1.5">Crafted with <span class="text-rose-400"><?= icon('heart','w-3.5 h-3.5') ?></span> · Built on PHP &amp; MySQL</p>
    </div>
  </div>
</footer>

<!-- Floating WhatsApp -->
<a href="https://wa.me/<?= e(preg_replace('/\D/', '', setting('whatsapp'))) ?>" target="_blank" rel="noopener"
   class="fixed bottom-5 right-5 z-40 grid place-items-center w-14 h-14 rounded-full bg-[#25D366] text-white shadow-xl shadow-green-500/40 hover:scale-110 transition" aria-label="Chat on WhatsApp">
  <?= icon('whatsapp','w-7 h-7') ?>
</a>

<!-- Back to top -->
<button id="toTop" class="fixed bottom-5 right-[5.5rem] z-40 grid place-items-center w-12 h-12 rounded-full bg-white text-ink border border-slate-200 shadow-card opacity-0 pointer-events-none transition hover:bg-slate-50" aria-label="Back to top">
  <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 15-6-6-6 6"/></svg>
</button>

<script src="<?= url('assets/js/main.js') ?>"></script>
</body>
</html>
