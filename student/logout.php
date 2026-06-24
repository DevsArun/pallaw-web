<?php
require_once __DIR__ . '/../includes/functions.php';
unset($_SESSION['student_id']);
flash('success', 'You have been logged out.');
redirect('student/login.php');
