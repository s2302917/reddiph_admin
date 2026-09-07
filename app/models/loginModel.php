<?php

require_once __DIR__ . '/../../config/config.php';

class LoginModel
{
    /**
     * Validate login input.
     */
    public static function validateLogin(array $data): array
    {
        $errors = [];

        $workEmail = trim($data['work_email'] ?? '');
        $password = $data['password'] ?? '';

        if ($workEmail === '') {
            $errors[] = 'Work email is required.';
        } elseif (!filter_var($workEmail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Please enter a valid work email address.';
        }

        if ($password === '') {
            $errors[] = 'Password is required.';
        }

        return [
            'success' => empty($errors),
            'errors' => $errors
        ];
    }


    /**
     * Authenticate user.
     *
     * The role is checked so that:
     * hospital_admin login cannot be used by a doctor/nurse,
     * doctor login cannot be used by another role, etc.
     */
    public static function authenticate(
        string $workEmail,
        string $password,
        string $role
    ): array {

        $allowedRoles = [
            'hospital_admin',
            'doctor',
            'nurse'
        ];

        if (!in_array($role, $allowedRoles, true)) {
            return [
                'success' => false,
                'message' => 'Invalid account type.'
            ];
        }

        $workEmail = strtolower(trim($workEmail));

        try {

            $pdo = Database::connection();

            $sql = "
                SELECT
                    id,
                    role,
                    full_name,
                    hospital_name,
                    hospital_id,
                    work_email,
                    license_number,
                    password_hash
                FROM user_type
                WHERE work_email = :work_email
                  AND role = :role
                LIMIT 1
            ";

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                ':work_email' => $workEmail,
                ':role' => $role
            ]);

            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            /*
             * Do not reveal whether the email exists.
             */
            if (!$user || !password_verify($password, $user['password_hash'])) {
                return [
                    'success' => false,
                    'message' => 'Invalid email or password.'
                ];
            }

            /*
             * Upgrade password hash if PHP's recommended
             * hashing algorithm has changed.
             */
            if (password_needs_rehash(
                $user['password_hash'],
                PASSWORD_DEFAULT
            )) {

                $newHash = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

                $update = $pdo->prepare("
                    UPDATE user_type
                    SET password_hash = :password_hash
                    WHERE id = :id
                ");

                $update->execute([
                    ':password_hash' => $newHash,
                    ':id' => $user['id']
                ]);
            }

            /*
             * Remove password hash before returning user data.
             */
            unset($user['password_hash']);

            return [
                'success' => true,
                'message' => 'Login successful.',
                'user' => $user
            ];

        } catch (Throwable $e) {

            error_log(
                'Login error: ' . $e->getMessage()
            );

            return [
                'success' => false,
                'message' => 'Unable to process login. Please try again.'
            ];
        }
    }


    /**
     * Start authenticated session.
     */
    public static function createSession(array $user): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        /*
         * Prevent session fixation.
         */
        session_regenerate_id(true);

        $_SESSION['logged_in'] = true;

        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['hospital_name'] = $user['hospital_name'];
        $_SESSION['hospital_id'] = $user['hospital_id'];
        $_SESSION['work_email'] = $user['work_email'];
        $_SESSION['license_number'] = $user['license_number'] ?? null;
    }


    /**
     * Logout user.
     */
    public static function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {

            $params = session_get_cookie_params();

            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();
    }


    /**
     * Check whether user is logged in.
     */
    public static function isLoggedIn(): bool
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return isset($_SESSION['logged_in'])
            && $_SESSION['logged_in'] === true;
    }


    /**
     * Require a specific role.
     */
    public static function requireRole(string $role): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (
            !isset($_SESSION['logged_in']) ||
            $_SESSION['logged_in'] !== true
        ) {
            header('Location: /login');
            exit;
        }

        if (
            !isset($_SESSION['role']) ||
            $_SESSION['role'] !== $role
        ) {
            http_response_code(403);
            exit('Access denied.');
        }
    }


    /**
     * Get currently logged-in user.
     */
    public static function currentUser(): ?array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!self::isLoggedIn()) {
            return null;
        }

        return [
            'id' => $_SESSION['user_id'] ?? null,
            'role' => $_SESSION['role'] ?? null,
            'full_name' => $_SESSION['full_name'] ?? null,
            'hospital_name' => $_SESSION['hospital_name'] ?? null,
            'hospital_id' => $_SESSION['hospital_id'] ?? null,
            'work_email' => $_SESSION['work_email'] ?? null,
            'license_number' => $_SESSION['license_number'] ?? null
        ];
    }
}