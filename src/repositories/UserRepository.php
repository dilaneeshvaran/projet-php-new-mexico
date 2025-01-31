<?php

namespace App\Repositories;

use App\Core\SQL;
use App\Models\User;

class UserRepository
{
    private SQL $db;

    public function __construct(SQL $db)
    {
        $this->db = $db;
    }

    public function findOneByEmail(string $email): ?User
    {
        $query = "SELECT id, firstname, lastname, email, password FROM users WHERE email = :email";
        $result = $this->db->queryPrepared($query, ['email' => strtolower(trim($email))]);

        if (empty($result)) {
            return null;
        }

        $userData = $result[0];

        //add database result to a User object
        $user = new User();
        $user->setId($userData['id']);
        $user->setFirstname($userData['firstname']);
        $user->setLastname($userData['lastname']);
        $user->setEmail($userData['email']);
        $user->setPassword($userData['password']); //password is already hashed in the db

        return $user;
    }

    public function save(User $user): bool
    {
        $query = "
            INSERT INTO users (firstname, lastname, email, password)
            VALUES (:firstname, :lastname, :email, :password)
        ";

        return $this->db->executePrepared($query, [
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
        ]);
    }

    public function resetPassword(string $email, string $newPassword): bool
{
    $query = "
        UPDATE users 
        SET password = :password 
        WHERE email = :email
    ";

    return $this->db->executePrepared($query, [
        'email' => $email,
        'password' => $newPassword,
    ]);
}

public function findOneById(int $id): ?User
{
    $query = "SELECT id, firstname, lastname FROM users WHERE id = :id";
    $result = $this->db->queryPrepared($query, ['id' => $id]);

    if (empty($result)) {
        return null;
    }

    $userData = $result[0];
    $user = new User();
    $user->setId($userData['id']);
    $user->setFirstname($userData['firstname']);
    $user->setLastname($userData['lastname']);

    return $user;
}
}