<?php

/**
 * Hospital Admin Login & Password Reset Model
 */
class HospitalAdminModel
{
    /**
     * Handles password reset request verification and token generation
     *
     * @param string $email
     * @return array
     */
    public static function requestPasswordReset(string $email): array
    {
        $email = filter_var(trim($email), FILTER_SANITIZE_EMAIL);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [
                'success' => false,
                'message' => 'Please enter a valid hospital email address.',
            ];
        }

        // Prototype response — ready for DB / Mailer integration
        return [
            'success' => true,
            'message' => 'A password reset link has been dispatched to ' . htmlspecialchars($email) . '.',
        ];
    }
}
