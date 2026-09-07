<?php

require_once __DIR__ . '/loginModel.php';

class HospitalAdminModel
{
    public static function validateLogin(array $data): array
    {
        return LoginModel::validateLogin($data);
    }


    public static function login(array $data): array
    {
        return LoginModel::authenticate(
            $data['work_email'] ?? '',
            $data['password'] ?? '',
            'hospital_admin'
        );
    }


    public static function createSession(array $user): void
    {
        LoginModel::createSession($user);
    }


    public static function requestPasswordReset(string $email): array
    {
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'Please enter a valid hospital email address.'
            ];
        }

        // Password reset can be connected to PHPMailer later.
        return [
            'success' => true,
            'message' => 'A password reset link has been dispatched to '
                . htmlspecialchars($email)
                . '.'
        ];
    }
}