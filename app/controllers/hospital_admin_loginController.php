<?php

require_once __DIR__ . '/../models/hospital_admin_loginModel.php';

$resetStatus = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'admin_forgot_password') {
    $email = $_POST['email'] ?? '';
    $resetStatus = HospitalAdminModel::requestPasswordReset($email);
}

require __DIR__ . '/../views/hospital_admin_loginView.php';
