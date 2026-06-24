<?php
/**
 * Shared helper functions.
 */
require_once __DIR__ . '/../config/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ---------------------------------------------------------------
 * Output / formatting helpers
 * ------------------------------------------------------------- */
function e(?string $str): string
{
    return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8');
}

function url(string $path = ''): string
{
    return BASE_URL . '/' . ltrim($path, '/');
}

function redirect(string $path): void
{
    header('Location: ' . url($path));
    exit;
}

function money($amount): string
{
    return '₹' . number_format((float)$amount, 0);
}

function fmt_date(?string $date, string $format = 'd M Y'): string
{
    if (!$date) return '-';
    $ts = strtotime($date);
    return $ts ? date($format, $ts) : '-';
}

/* ---------------------------------------------------------------
 * Settings (cached for the request)
 * ------------------------------------------------------------- */
function settings(): array
{
    static $cache = null;
    if ($cache !== null) return $cache;
    $cache = [];
    try {
        $rows = db()->query('SELECT skey, svalue FROM settings')->fetchAll();
        foreach ($rows as $r) {
            $cache[$r['skey']] = $r['svalue'];
        }
    } catch (Throwable $e) {
        $cache = [];
    }
    return $cache;
}

function setting(string $key, string $default = ''): string
{
    $s = settings();
    return $s[$key] ?? $default;
}

/* ---------------------------------------------------------------
 * CSRF protection
 * ------------------------------------------------------------- */
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . csrf_token() . '">';
}

function verify_csrf(): bool
{
    return isset($_POST['csrf'], $_SESSION['csrf'])
        && hash_equals($_SESSION['csrf'], $_POST['csrf']);
}

/* ---------------------------------------------------------------
 * Flash messages
 * ------------------------------------------------------------- */
function flash(string $type, string $msg): void
{
    $_SESSION['flash'][] = ['type' => $type, 'msg' => $msg];
}

function get_flashes(): array
{
    $f = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $f;
}

function render_flashes(): string
{
    $out = '';
    foreach (get_flashes() as $f) {
        $color = match ($f['type']) {
            'success' => 'bg-emerald-50 text-emerald-800 border-emerald-200',
            'error'   => 'bg-rose-50 text-rose-800 border-rose-200',
            'warning' => 'bg-amber-50 text-amber-800 border-amber-200',
            default   => 'bg-sky-50 text-sky-800 border-sky-200',
        };
        $out .= '<div class="mb-4 rounded-xl border px-4 py-3 text-sm font-medium ' . $color . '">' . e($f['msg']) . '</div>';
    }
    return $out;
}

/* ---------------------------------------------------------------
 * Auth - Admin
 * ------------------------------------------------------------- */
function admin_logged_in(): bool
{
    return !empty($_SESSION['admin_id']);
}

function require_admin(): void
{
    if (!admin_logged_in()) {
        redirect('admin/login.php');
    }
}

function current_admin(): ?array
{
    if (!admin_logged_in()) return null;
    static $admin = null;
    if ($admin === null) {
        $stmt = db()->prepare('SELECT * FROM admins WHERE id = ?');
        $stmt->execute([$_SESSION['admin_id']]);
        $admin = $stmt->fetch() ?: null;
    }
    return $admin;
}

/* ---------------------------------------------------------------
 * Auth - Student
 * ------------------------------------------------------------- */
function student_logged_in(): bool
{
    return !empty($_SESSION['student_id']);
}

function require_student(): void
{
    if (!student_logged_in()) {
        redirect('student/login.php');
    }
}

function current_student(): ?array
{
    if (!student_logged_in()) return null;
    static $student = null;
    if ($student === null) {
        $stmt = db()->prepare('SELECT * FROM students WHERE id = ?');
        $stmt->execute([$_SESSION['student_id']]);
        $student = $stmt->fetch() ?: null;
    }
    return $student;
}

/* ---------------------------------------------------------------
 * Misc
 * ------------------------------------------------------------- */
function next_sequence(string $table, string $column, string $prefix): string
{
    $year = date('Y');
    $stmt = db()->prepare("SELECT $column FROM $table WHERE $column LIKE ? ORDER BY id DESC LIMIT 1");
    $stmt->execute(["%$year%"]);
    $last = $stmt->fetchColumn();
    $n = 1;
    if ($last && preg_match('/(\d+)$/', $last, $m)) {
        $n = (int)$m[1] + 1;
    }
    return sprintf('%s-%s-%04d', $prefix, $year, $n);
}

function active_class(string $page, string $current): string
{
    return $page === $current ? 'text-indigo-600' : 'text-slate-600 hover:text-indigo-600';
}


function slugify(string $text): string
{
    $text = strtolower(trim($text));
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-') ?: 'item-' . time();
}
