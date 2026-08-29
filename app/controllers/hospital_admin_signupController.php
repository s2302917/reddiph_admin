<?php

require_once __DIR__ . '/../models/hospital_admin_signupModel.php';

class HospitalAdminSignupController
{
    public function handleRequest(): void
    {
        $errors = [];
        $successMessage = '';
        $formData = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $formData = [
                'full_name' => $_POST['full_name'] ?? '',
                'hospital_name' => $_POST['hospital_name'] ?? '',
                'work_email' => $_POST['work_email'] ?? '',
                'password' => $_POST['password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? '',
            ];

            $validation = HospitalAdminSignupModel::validateRegistration($formData);
            if (!$validation['success']) {
                $errors = $validation['errors'];
            } else {
                $result = HospitalAdminSignupModel::createHospitalAdminAccount($formData);
                if ($result['success']) {
                    $successMessage = $result['message'];
                    $formData = [];
                } else {
                    $errors[] = $result['message'];
                }
            }
        }

        require __DIR__ . '/../views/hospital_admin_signupView.php';
    }
}

$controller = new HospitalAdminSignupController();
$controller->handleRequest();
