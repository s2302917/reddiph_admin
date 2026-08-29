<?php

require_once __DIR__ . '/../models/nurse_loginModel.php';

$resetStatus = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'nurse_forgot_password') {
    $idOrEmail = $_POST['email'] ?? '';
    $resetStatus = NurseModel::requestPasswordReset($idOrEmail);
}

require __DIR__ . '/../views/nurse_loginView.php';
