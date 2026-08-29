<?php

class NurseSignupModel
{
    /**
     * Establish a database connection.
     * This project is currently prototype-only, so it returns a PDO object
     * when configured. If no database credentials exist, it returns null.
     */
    public static function getConnection(): ?PDO
    {
        $host = getenv('DB_HOST') ?: 'localhost';
        $db = getenv('DB_NAME') ?: 'reddiph_db';
        $user = getenv('DB_USER') ?: 'root';
        $pass = getenv('DB_PASS') ?: '';
        $charset = 'utf8mb4';

        $dsn = "mysql:host={$host};dbname={$db};charset={$charset}";

        try {
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);

            return $pdo;
        } catch (Throwable $e) {
            return null;
        }
    }

    /**
     * Validate the registration form data.
     */
    public static function validateRegistration(array $data): array
    {
        $errors = [];

        if (empty(trim($data['full_name'] ?? ''))) {
            $errors[] = 'Full name is required.';
        }

        if (empty(trim($data['hospital_name'] ?? ''))) {
            $errors[] = 'Hospital name is required.';
        }

        $email = trim($data['work_email'] ?? '');
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid work email address.';
        }

        $password = $data['password'] ?? '';
        $confirmPassword = $data['confirm_password'] ?? '';

        if (strlen($password) < 8) {
            $errors[] = 'Password must be at least 8 characters long.';
        }

        if ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match.';
        }

        return [
            'success' => empty($errors),
            'errors' => $errors,
        ];
    }

    /**
     * Create a nurse account entry.
     * For prototype use, this will just simulate a database insert and return success.
     */
    public static function createNurseAccount(array $data): array
    {
        $fullName = trim($data['full_name'] ?? '');
        $hospitalName = trim($data['hospital_name'] ?? '');
        $workEmail = trim($data['work_email'] ?? '');
        $password = $data['password'] ?? '';

        if (empty($fullName) || empty($hospitalName) || empty($workEmail) || empty($password)) {
            return [
                'success' => false,
                'message' => 'Missing required registration data.',
            ];
        }

        $connection = self::getConnection();

        if ($connection !== null) {
            try {
                $sql = 'INSERT INTO nurses (full_name, hospital_name, work_email, password_hash, created_at) VALUES (:full_name, :hospital_name, :work_email, :password_hash, NOW())';
                $statement = $connection->prepare($sql);
                $statement->execute([
                    ':full_name' => $fullName,
                    ':hospital_name' => $hospitalName,
                    ':work_email' => $workEmail,
                    ':password_hash' => password_hash($password, PASSWORD_DEFAULT),
                ]);

                return [
                    'success' => true,
                    'message' => 'Account created successfully. You can now log in.',
                ];
            } catch (Throwable $e) {
                return [
                    'success' => false,
                    'message' => 'Database connection failed. Please check your database settings.',
                ];
            }
        }

        return [
            'success' => true,
            'message' => 'Prototype registration successful. Connect your database to persist this account.',
        ];
    }
}
