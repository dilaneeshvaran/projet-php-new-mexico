<?php

namespace App\Repositories;

use App\Core\SQL;

class ResetPasswordTokenRepository
{
    private SQL $db;

    public function __construct(SQL $db)
    {
        $this->db = new SQL();
    }

    public function saveResetToken(string $email, string $token, \DateTime $expiresAt): void
    {
        $query = "
            INSERT INTO password_resets (email, token, expires_at)
            VALUES (:email, :token, :expires_at)
            ON DUPLICATE KEY UPDATE token = :token, expires_at = :expires_at
        ";

        $this->db->executePrepared($query, [
            'email' => $email,
            'token' => $token,
            'expires_at' => $expiresAt->format('Y-m-d H:i:s')
        ]);
    }

    public function findToken(string $token): ?array
    {
        $query = "SELECT email, token, expires_at FROM password_resets WHERE token = :token";
        $result = $this->db->queryPrepared($query, ['token' => $token]);

        return $result[0] ?? null;
    }

    public function deleteToken(string $token): void
    {
        $query = "DELETE FROM password_resets WHERE token = :token";
        $this->db->executePrepared($query, ['token' => $token]);
    }
}
