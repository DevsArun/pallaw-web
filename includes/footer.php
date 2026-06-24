</main>

<!-- Footer -->
<footer class="bg-slate-950 text-slate-400">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 pt-16 pb-10">
    <div class="grid gap-10 md:grid-cols-2 lg:grid-cols-4">
      <div>
        <a href="<?= url('index.php') ?>" class="flex items-center gap-2.5 mb-4">
          <span class="grid place-items-center w-10 h-10 rounded-xl bg-gradient-to-br from-brand-500 to-violet-600 text-white font-display font-bold text-lg">N</span>
          <span class="font-display font-bold text-xl text-white"><?= e(setting('site_name')) ?></span>
        </a>
        <p class="text-sm leading-relaxed"><?= e(setting('about_short')) ?></p>
        <div class="flex gap-3 mt-5">
          <?php foreach (['facebook'=>'F','instagram'=>'In','linkedin'=>'Li','youtube'=>'YT'] as $k=>$lbl): ?>
            <a href="<?= e(setting($k, '#')) ?>" class="grid place-items-center w-9 h-9 rounded-lg bg-white/5 hover:bg-brand-600 text-white text-xs font-semibold transition"><?= $lbl ?></a>
          <?php endforeach; ?>
        </div>
      </div>

      <div>
        <h4 class="text-white font-semibold mb-4">Quick Links</h4>
        <ul class="space-y-2.5 text-sm">
          <li><a href="<?= url('courses.php') ?>" class="hover:text-white transition">All Courses</a></li>
          <li><a href="<?= url('projects.php') ?>" class="hover:text-white transition">Student Projects</a></li>
          <li><a href="<?= url('verify.php') ?>" class="hover:text-white transition">Verify Certificate</a></li>
          <li><a href="<?= url('about.php') ?>" class="hover:text-white transition">About Us</a></li>
          <li><a href="<?= url('contact.php') ?>" class="hover:text-white transition">Contact</a></li>
        </ul>
      </div>

      <div>
        <h4 class="text-white font-semibold mb-4">Portals</h4>
        <ul class="space-y-2.5 text-sm">
          <li><a href="<?= url('student/login.php') ?>" class="hover:text-white transition">Student Login</a></li>
          <li><a href="<?= url('admin/login.php') ?>" class="hover:text-white transition">Admin Login</a></li>
          <li><a href="<?= url('verify.php') ?>" class="hover:text-white transition">Certificate Check</a></li>
        </ul>
      </div>

      <div>
        <h4 class="text-white font-semibold mb-4">Get in Touch</h4>
        <ul class="space-y-3 text-sm">
          <li class="flex gap-3"><span>📍</span><span><?= e(setting('address')) ?></span></li>
          <li class="flex gap-3"><span>📞</span><a href="tel:<?= e(setting('phone')) ?>" class="hover:text-white"><?= e(setting('phone')) ?></a></li>
          <li class="flex gap-3"><span>✉️</span><a href="mailto:<?= e(setting('email')) ?>" class="hover:text-white"><?= e(setting('email')) ?></a></li>
        </ul>
      </div>
    </div>

    <div class="mt-12 pt-6 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs">
      <p>&copy; <?= date('Y') ?> <?= e(setting('site_name')) ?>. All rights reserved.</p>
      <p>Crafted with precision · Built on PHP &amp; MySQL</p>
    </div>
  </div>
</footer>

<!-- Floating WhatsApp -->
<a href="https://wa.me/<?= e(preg_replace('/\D/', '', setting('whatsapp'))) ?>" target="_blank"
   class="fixed bottom-5 right-5 z-50 grid place-items-center w-14 h-14 rounded-full bg-green-500 text-white shadow-xl shadow-green-500/40 hover:scale-110 transition" aria-label="WhatsApp">
  <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163a11.867 11.867 0 01-1.587-5.946C.16 5.335 5.495 0 12.05 0a11.82 11.82 0 018.413 3.488 11.82 11.82 0 013.48 8.414c-.003 6.557-5.338 11.892-11.893 11.892a11.9 11.9 0 01-5.688-1.448L.057 24zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884a9.86 9.86 0 001.51 5.26l-.999 3.648 3.978-.607zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.71.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
</a>

<script src="<?= url('assets/js/main.js') ?>"></script>
</body>
</html>
