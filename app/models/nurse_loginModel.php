<?php

/**
 * Nurse Login & Password Reset Model
 */
class NurseModel
{
    /**
     * Handles nurse password reset request verification
     *
     * @param string $idOrEmail
     * @return array
     */
    public static function requestPasswordReset(string $idOrEmail): array
    {
        $input = trim($idOrEmail);

        if (empty($input)) {
            return [
                'success' => false,
                'message' => 'Please enter your Nurse ID or registered email.',
            ];
        }

        // Prototype response — ready for DB / Mailer integration
        return [
            'success' => true,
            'message' => 'A password reset link has been dispatched for ' . htmlspecialchars($input) . '.',
        ];
    }
}
