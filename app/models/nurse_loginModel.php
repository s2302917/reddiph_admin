<?php

require_once __DIR__ . '/loginModel.php';

class NurseModel
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
            'nurse'
        );
    }


    public static function createSession(array $user): void
    {
        LoginModel::createSession($user);
    }


    public static function requestPasswordReset(string $idOrEmail): array
    {
        $input = trim($idOrEmail);

        if ($input === '') {
            return [
                'success' => false,
                'message' => 'Please enter your Nurse ID or registered email.'
            ];
        }

        // Password reset can be connected to PHPMailer later.
        return [
            'success' => true,
            'message' => 'A password reset link has been dispatched for '
                . htmlspecialchars($input)
                . '.'
        ];
    }
}