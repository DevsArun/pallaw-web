<?php
require_once __DIR__ . '/_layout.php';
$d = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) { flash('error','Session expired.'); redirect('admin/categories.php'); }
    $do = $_POST['do'] ?? '';
    if ($do === 'delete') {
        $d->prepare("DELETE FROM categories WHERE id=?")->execute([(int)$_POST['id']]);
        flash('success','Category deleted.');
    } elseif ($do === 'save') {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        if ($name === '') { flash('error','Name required.'); redirect('admin/categories.php'); }
        $slug = slugify($name);
        if ($id) {
            $d->prepare("UPDATE categories SET name=?, slug=? WHERE id=?")->execute([$name,$slug,$id]);
            flash('success','Category updated.');
        } else {
            try { $d->prepare("INSERT INTO categories (name,slug,icon) VALUES (?,?, 'graduation-cap')")->execute([$name,$slug]); flash('success','Category added.'); }
            catch (Throwable $e) { flash('error','Category already exists.'); }
        }
    }
    redirect('admin/categories.php');
}

$edit = null;
if (($_GET['action'] ?? '') === 'edit') {
    $stmt = $d->prepare("SELECT * FROM categories WHERE id=?"); $stmt->execute([(int)$_GET['id']]); $edit = $stmt->fetch();
}
$cats = $d->query("SELECT c.*, (SELECT COUNT(*) FROM courses WHERE category_id=c.id) AS course_count FROM categories c ORDER BY c.name")->fetchAll();

admin_layout_top('categories', 'Categories');
?>
<div class="grid lg:grid-cols-3 gap-6">
  <!-- Form -->
  <div class="rounded-2xl bg-white border border-slate-100 p-6 h-fit">
    <h3 class="font-display text-lg font-bold text-slate-900 mb-4"><?= $edit ? 'Edit' : 'Add' ?> Category</h3>
    <form method="post" class="space-y-4">
      <?= csrf_field() ?><input type="hidden" name="do" value="save"><input type="hidden" name="id" value="<?= (int)($edit['id'] ?? 0) ?>">
      <div><?= field_label('Category Name') ?><input name="name" value="<?= e($edit['name'] ?? '') ?>" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"></div>
      <div class="flex gap-2">
        <button class="px-6 py-3 rounded-xl bg-gradient-to-r from-brand-500 to-violet-600 text-white font-semibold hover:opacity-90 transition"><?= $edit?'Update':'Add' ?></button>
        <?php if($edit): ?><a href="<?= url('admin/categories.php') ?>" class="px-6 py-3 rounded-xl border border-slate-200 font-semibold text-slate-700">Cancel</a><?php endif; ?>
      </div>
    </form>
  </div>

  <!-- List -->
  <div class="lg:col-span-2 rounded-2xl bg-white border border-slate-100 overflow-hidden">
    <table class="w-full text-sm">
      <thead class="bg-slate-50 text-slate-500 text-left"><tr><th class="px-5 py-3 font-semibold">Name</th><th class="px-5 py-3 font-semibold">Slug</th><th class="px-5 py-3 font-semibold">Courses</th><th class="px-5 py-3 font-semibold text-right">Actions</th></tr></thead>
      <tbody class="divide-y divide-slate-100">
        <?php foreach ($cats as $c): ?>
          <tr class="hover:bg-slate-50">
            <td class="px-5 py-4 font-medium text-slate-900"><?= e($c['name']) ?></td>
            <td class="px-5 py-4 text-slate-400 font-mono text-xs"><?= e($c['slug']) ?></td>
            <td class="px-5 py-4 text-slate-500"><?= $c['course_count'] ?></td>
            <td class="px-5 py-4 text-right whitespace-nowrap">
              <a href="<?= url('admin/categories.php?action=edit&id=' . $c['id']) ?>" class="text-brand-600 font-semibold">Edit</a>
              <form method="post" class="inline" onsubmit="return confirm('Delete this category?')"><?= csrf_field() ?><input type="hidden" name="do" value="delete"><input type="hidden" name="id" value="<?= $c['id'] ?>"><button class="ml-3 text-rose-600 font-semibold">Delete</button></form>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if(!$cats): ?><tr><td colspan="4" class="px-5 py-10 text-center text-slate-400">No categories yet.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php admin_layout_bottom(); ?>
