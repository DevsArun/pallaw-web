<?php
require_once __DIR__ . '/_layout.php';
$d = db();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf()) { flash('error','Session expired.'); redirect('admin/students.php'); }
    $do = $_POST['do'] ?? '';

    if ($do === 'delete') {
        $d->prepare("DELETE FROM students WHERE id=?")->execute([(int)$_POST['id']]);
        flash('success','Student deleted.');
        redirect('admin/students.php');
    }
    if ($do === 'resetpw') {
        $new = trim($_POST['new_password'] ?? '');
        if (strlen($new) < 6) { flash('error','Password must be 6+ chars.'); }
        else { $d->prepare("UPDATE students SET password_hash=? WHERE id=?")->execute([password_hash($new,PASSWORD_BCRYPT),(int)$_POST['id']]); flash('success','Password reset.'); }
        redirect('admin/students.php?action=edit&id=' . (int)$_POST['id']);
    }
    if ($do === 'unlock') {
        $d->prepare("UPDATE students SET details_locked=0 WHERE id=?")->execute([(int)$_POST['id']]);
        flash('success','Personal details unlocked for editing.');
        redirect('admin/students.php?action=edit&id=' . (int)$_POST['id']);
    }
    if ($do === 'save') {
        $id      = (int)($_POST['id'] ?? 0);
        $name    = trim($_POST['name'] ?? '');
        $email   = trim($_POST['email'] ?? '');
        $phone   = trim($_POST['phone'] ?? '');
        $dob     = $_POST['dob'] ?: null;
        $gender  = $_POST['gender'] ?: null;
        $address = trim($_POST['address'] ?? '');
        $status  = $_POST['status'] ?? 'active';

        if ($name==='' || !filter_var($email, FILTER_VALIDATE_EMAIL)) { flash('error','Valid name and email required.'); redirect('admin/students.php?action=' . ($id?'edit&id='.$id:'new')); }

        try {
            if ($id) {
                $d->prepare("UPDATE students SET name=?,email=?,phone=?,dob=?,gender=?,address=?,status=? WHERE id=?")
                  ->execute([$name,$email,$phone,$dob,$gender,$address,$status,$id]);
                flash('success','Student updated.');
            } else {
                $code = next_sequence('students','student_code','NEX');
                $pw   = $_POST['password'] ?: 'student@123';
                $d->prepare("INSERT INTO students (student_code,name,email,phone,password_hash,dob,gender,address,status) VALUES (?,?,?,?,?,?,?,?,?)")
                  ->execute([$code,$name,$email,$phone,password_hash($pw,PASSWORD_BCRYPT),$dob,$gender,$address,$status]);
                flash('success', "Student created. Enrollment ID: $code · Default password: $pw");
            }
        } catch (Throwable $e) { flash('error','Email already exists.'); redirect('admin/students.php?action=' . ($id?'edit&id='.$id:'new')); }
        redirect('admin/students.php');
    }
}

$action = $_GET['action'] ?? 'list';

if ($action === 'new' || $action === 'edit') {
    $s = ['id'=>0,'student_code'=>'(auto)','name'=>'','email'=>'','phone'=>'','dob'=>'','gender'=>'','address'=>'','status'=>'active','details_locked'=>0];
    if ($action==='edit') { $stmt=$d->prepare("SELECT * FROM students WHERE id=?"); $stmt->execute([(int)$_GET['id']]); $s=$stmt->fetch()?:$s; }
    admin_layout_top('students', $action==='edit'?'Edit Student':'Add Student');
    ?>
    <a href="<?= url('admin/students.php') ?>" class="inline-flex items-center gap-1 text-sm font-semibold text-slate-500 hover:text-slate-900 mb-4">← Back</a>
    <div class="grid lg:grid-cols-3 gap-6 max-w-5xl">
      <form method="post" class="lg:col-span-2 rounded-2xl bg-white border border-slate-100 p-6 sm:p-8 space-y-5">
        <?= csrf_field() ?><input type="hidden" name="do" value="save"><input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
        <div class="grid sm:grid-cols-2 gap-4">
          <div><?= field_label('Full Name *') ?><input name="name" value="<?= e($s['name']) ?>" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"></div>
          <div><?= field_label('Email *') ?><input type="email" name="email" value="<?= e($s['email']) ?>" required class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"></div>
          <div><?= field_label('Phone') ?><input name="phone" value="<?= e($s['phone']) ?>" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"></div>
          <div><?= field_label('Date of Birth') ?><input type="date" name="dob" value="<?= e($s['dob']) ?>" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"></div>
          <div><?= field_label('Gender') ?><select name="gender" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"><?php foreach(['','Male','Female','Other'] as $g): ?><option value="<?= $g ?>" <?= $s['gender']===$g?'selected':'' ?>><?= $g?:'Select…' ?></option><?php endforeach; ?></select></div>
          <div><?= field_label('Status') ?><select name="status" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"><?php foreach(['active','inactive'] as $st): ?><option <?= $s['status']===$st?'selected':'' ?>><?= $st ?></option><?php endforeach; ?></select></div>
        </div>
        <div><?= field_label('Address') ?><input name="address" value="<?= e($s['address']) ?>" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"></div>
        <?php if (!$s['id']): ?>
          <div><?= field_label('Initial Password (default: student@123)') ?><input name="password" placeholder="student@123" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400"></div>
        <?php endif; ?>
        <button class="px-7 py-3 rounded-xl bg-gradient-to-r from-brand-500 to-violet-600 text-white font-semibold hover:opacity-90 transition">Save Student</button>
      </form>

      <?php if ($s['id']): ?>
      <div class="space-y-4 h-fit">
        <div class="rounded-2xl bg-white border border-slate-100 p-6">
          <p class="text-xs text-slate-400 uppercase">Enrollment ID</p>
          <p class="font-mono font-semibold text-slate-900"><?= e($s['student_code']) ?></p>
          <p class="mt-3 text-xs text-slate-400 uppercase">Details Status</p>
          <p class="font-semibold <?= $s['details_locked']?'text-amber-600':'text-emerald-600' ?>"><?= $s['details_locked']?'🔒 Locked':'✓ Editable' ?></p>
          <?php if ($s['details_locked']): ?>
            <form method="post" class="mt-2"><?= csrf_field() ?><input type="hidden" name="do" value="unlock"><input type="hidden" name="id" value="<?= $s['id'] ?>"><button class="text-sm font-semibold text-brand-600">Unlock details →</button></form>
          <?php endif; ?>
        </div>
        <div class="rounded-2xl bg-white border border-slate-100 p-6">
          <h4 class="font-semibold text-slate-900 mb-3">Reset Password</h4>
          <form method="post" class="space-y-3"><?= csrf_field() ?><input type="hidden" name="do" value="resetpw"><input type="hidden" name="id" value="<?= $s['id'] ?>">
            <input name="new_password" placeholder="New password" required class="w-full px-4 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-brand-400">
            <button class="w-full px-4 py-2.5 rounded-xl bg-slate-900 text-white font-semibold text-sm hover:bg-brand-600">Reset</button>
          </form>
        </div>
      </div>
      <?php endif; ?>
    </div>
    <?php
    admin_layout_bottom(); exit;
}

/* List */
$q = trim($_GET['q'] ?? '');
$sql = "SELECT * FROM students";
$params = [];
if ($q!=='') { $sql .= " WHERE name LIKE ? OR email LIKE ? OR student_code LIKE ?"; $like="%$q%"; $params=[$like,$like,$like]; }
$sql .= " ORDER BY id DESC";
$stmt=$d->prepare($sql); $stmt->execute($params); $students=$stmt->fetchAll();

admin_layout_top('students', 'Students');
?>
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
  <form method="get" class="flex gap-2">
    <input name="q" value="<?= e($q) ?>" placeholder="Search students…" class="px-4 py-2.5 rounded-full border border-slate-200 text-sm focus:outline-none focus:ring-2 focus:ring-brand-400">
    <button class="px-4 py-2.5 rounded-full bg-slate-100 text-sm font-semibold">Search</button>
  </form>
  <a href="<?= url('admin/students.php?action=new') ?>" class="px-5 py-2.5 rounded-full bg-slate-900 text-white text-sm font-semibold hover:bg-brand-600 transition">+ Add Student</a>
</div>

<div class="rounded-2xl bg-white border border-slate-100 overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-slate-50 text-slate-500 text-left"><tr><th class="px-5 py-3 font-semibold">Student</th><th class="px-5 py-3 font-semibold">Enrollment ID</th><th class="px-5 py-3 font-semibold">Phone</th><th class="px-5 py-3 font-semibold">Status</th><th class="px-5 py-3 font-semibold text-right">Actions</th></tr></thead>
      <tbody class="divide-y divide-slate-100">
        <?php foreach ($students as $s): ?>
          <tr class="hover:bg-slate-50">
            <td class="px-5 py-4"><div class="flex items-center gap-3"><span class="grid place-items-center w-9 h-9 rounded-full bg-gradient-to-br from-brand-500 to-violet-600 text-white text-sm font-bold"><?= e(strtoupper(substr($s['name'],0,1))) ?></span><div><p class="font-medium text-slate-900"><?= e($s['name']) ?></p><p class="text-xs text-slate-400"><?= e($s['email']) ?></p></div></div></td>
            <td class="px-5 py-4 font-mono text-xs text-slate-500"><?= e($s['student_code']) ?> <?php if($s['details_locked']): ?>🔒<?php endif; ?></td>
            <td class="px-5 py-4 text-slate-500"><?= e($s['phone'] ?: '—') ?></td>
            <td class="px-5 py-4"><span class="px-2.5 py-1 rounded-full text-xs font-semibold <?= $s['status']==='active'?'bg-emerald-100 text-emerald-700':'bg-slate-100 text-slate-500' ?>"><?= e($s['status']) ?></span></td>
            <td class="px-5 py-4 text-right whitespace-nowrap">
              <a href="<?= url('admin/students.php?action=edit&id=' . $s['id']) ?>" class="text-brand-600 font-semibold">Edit</a>
              <form method="post" class="inline" onsubmit="return confirm('Delete this student and all their records?')"><?= csrf_field() ?><input type="hidden" name="do" value="delete"><input type="hidden" name="id" value="<?= $s['id'] ?>"><button class="ml-3 text-rose-600 font-semibold">Delete</button></form>
            </td>
          </tr>
        <?php endforeach; ?>
        <?php if(!$students): ?><tr><td colspan="5" class="px-5 py-10 text-center text-slate-400">No students found.</td></tr><?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
<?php admin_layout_bottom(); ?>
