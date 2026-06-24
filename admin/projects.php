<?php
require_once __DIR__ . '/_layout.php';
$d = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) { flash('error','Session expired.'); redirect('admin/projects.php'); }
    $do = $_POST['do'] ?? '';
    if ($do === 'delete') {
        $d->prepare("DELETE FROM projects WHERE id=?")->execute([(int)$_POST['id']]);
        flash('success','Project deleted.');
        redirect('admin/projects.php');
    }
    if ($do === 'save') {
        $id = (int)($_POST['id'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $student_id = $_POST['student_id'] ?: null;
        $course_id  = $_POST['course_id'] ?: null;
        $desc = trim($_POST['description'] ?? '');
        $purl = trim($_POST['project_url'] ?? '');
        $status = $_POST['status'] ?? 'published';
        if ($title==='') { flash('error','Title required.'); redirect('admin/projects.php?action=' . ($id?'edit&id='.$id:'new')); }
        if ($id) {
            $d->prepare("UPDATE projects SET title=?,student_id=?,course_id=?,description=?,project_url=?,status=? WHERE id=?")->execute([$title,$student_id,$course_id,$desc,$purl,$status,$id]);
            flash('success','Project updated.');
        } else {
            $d->prepare("INSERT INTO projects (title,student_id,course_id,description,project_url,status) VALUES (?,?,?,?,?,?)")->execute([$title,$student_id,$course_id,$desc,$purl,$status]);
            flash('success','Project added.');
        }
        redirect('admin/projects.php');
    }
}

$students = $d->query("SELECT id,name FROM students ORDER BY name")->fetchAll();
$courses  = $d->query("SELECT id,title FROM courses ORDER BY title")->fetchAll();
$action = $_GET['action'] ?? 'list';

if ($action === 'new' || $action === 'edit') {
    $p = ['id'=>0,'title'=>'','student_id'=>'','course_id'=>'','description'=>'','project_url'=>'','status'=>'published'];
    if ($action==='edit') { $stmt=$d->prepare("SELECT * FROM projects WHERE id=?"); $stmt->execute([(int)$_GET['id']]); $p=$stmt->fetch()?:$p; }
    admin_layout_top('projects', $action==='edit'?'Edit Project':'Add Project');
    ?>
    <a href="<?= url('admin/projects.php') ?>" class="inline-flex items-center gap-1 text-sm font-semibold text-slate-500 hover:text-slate-900 mb-4">← Back</a>
    <form method="post" class="rounded-2xl bg-white border border-slate-100 p-6 sm:p-8 max-w-2xl space-y-5">
      <?= csrf_field() ?><input type="hidden" name="do" value="save"><input type="hidden" name="id" value="<?= (int)$p['id'] ?>">
      <div><?= field_label('Project Title *') ?><input name="title" value="<?= e($p['title']) ?>" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"></div>
      <div class="grid sm:grid-cols-2 gap-4">
        <div><?= field_label('Student (optional)') ?><select name="student_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"><option value="">— None —</option><?php foreach($students as $s): ?><option value="<?= $s['id'] ?>" <?= (string)$p['student_id']===(string)$s['id']?'selected':'' ?>><?= e($s['name']) ?></option><?php endforeach; ?></select></div>
        <div><?= field_label('Course (optional)') ?><select name="course_id" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"><option value="">— None —</option><?php foreach($courses as $c): ?><option value="<?= $c['id'] ?>" <?= (string)$p['course_id']===(string)$c['id']?'selected':'' ?>><?= e($c['title']) ?></option><?php endforeach; ?></select></div>
      </div>
      <div><?= field_label('Description') ?><textarea name="description" rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"><?= e($p['description']) ?></textarea></div>
      <div><?= field_label('Project URL') ?><input name="project_url" value="<?= e($p['project_url']) ?>" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"></div>
      <div><?= field_label('Status') ?><select name="status" class="px-4 py-3 rounded-xl border border-slate-200"><?php foreach(['published','draft'] as $st): ?><option <?= $p['status']===$st?'selected':'' ?>><?= $st ?></option><?php endforeach; ?></select></div>
      <div class="flex gap-3"><button class="px-7 py-3 rounded-xl bg-gradient-to-r from-brand-500 to-violet-600 text-white font-semibold hover:opacity-90 transition">Save</button><a href="<?= url('admin/projects.php') ?>" class="px-7 py-3 rounded-xl border border-slate-200 font-semibold text-slate-700">Cancel</a></div>
    </form>
    <?php admin_layout_bottom(); exit;
}

$rows = $d->query("SELECT p.*, s.name AS student, c.title AS course FROM projects p LEFT JOIN students s ON s.id=p.student_id LEFT JOIN courses c ON c.id=p.course_id ORDER BY p.id DESC")->fetchAll();
admin_layout_top('projects', 'Projects');
?>
<div class="flex items-center justify-between mb-6"><p class="text-slate-500 text-sm"><?= count($rows) ?> projects</p><a href="<?= url('admin/projects.php?action=new') ?>" class="px-5 py-2.5 rounded-full bg-slate-900 text-white text-sm font-semibold hover:bg-brand-600 transition">+ Add Project</a></div>
<div class="rounded-2xl bg-white border border-slate-100 overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-slate-50 text-slate-500 text-left"><tr><th class="px-5 py-3 font-semibold">Title</th><th class="px-5 py-3 font-semibold">Student</th><th class="px-5 py-3 font-semibold">Course</th><th class="px-5 py-3 font-semibold">Status</th><th class="px-5 py-3 font-semibold text-right">Actions</th></tr></thead>
      <tbody class="divide-y divide-slate-100">
        <?php foreach ($rows as $r): ?>
          <tr class="hover:bg-slate-50">
            <td class="px-5 py-4 font-medium text-slate-900"><?= e($r['title']) ?></td>
            <td class="px-5 py-4 text-slate-500"><?= e($r['student'] ?: '—') ?></td>
            <td class="px-5 py-4 text-slate-500"><?= e($r['course'] ?: '—') ?></td>
            <td class="px-5 py-4"><span class="px-2.5 py-1 rounded-full text-xs font-semibold <?= $r['status']==='published'?'bg-emerald-100 text-emerald-700':'bg-slate-100 text-slate-500' ?>"><?= e($r['status']) ?></span></td>
            <td class="px-5 py-4 text-right whitespace-nowrap"><a href="<?= url('admin/projects.php?action=edit&id=' . $r['id']) ?>" class="text-brand-600 font-semibold">Edit</a><form method="post" class="inline" onsubmit="return confirm('Delete project?')"><?= csrf_field() ?><input type="hidden" name="do" value="delete"><input type="hidden" name="id" value="<?= $r['id'] ?>"><button class="ml-3 text-rose-600 font-semibold">Delete</button></form></td>
          </tr>
        <?php endforeach; ?>
        <?php if(!$rows): ?><tr><td colspan="5" class="px-5 py-10 text-center text-slate-400">No projects yet.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php admin_layout_bottom(); ?>
