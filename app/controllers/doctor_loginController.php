<?php

require_once __DIR__ . '/../models/doctor_loginModel.php';

$resetStatus = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'doctor_forgot_password') {
    $idOrEmail = $_POST['email'] ?? '';
    $resetStatus = DoctorModel::requestPasswordReset($idOrEmail);
}

require __DIR__ . '/../views/doctor_loginView.php';