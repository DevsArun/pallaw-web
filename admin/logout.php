<?php
require_once __DIR__ . '/../includes/functions.php';
unset($_SESSION['admin_id']);
flash('success', 'Logged out successfully.');
redirect('admin/login.php');
