<?php
namespace App\Core;

class Session
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function isLogged(): bool
    {
        return isset($_SESSION['firstname']);
    }

    public function getRoles(): array
    {
        return $_SESSION['roles'] ?? [];
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset(); // Clear session data
            session_destroy(); // close session
            session_write_close(); // Ensure session is closed
            setcookie(session_name(), '', 0, '/'); // Clear session cookie
        }
    }
}