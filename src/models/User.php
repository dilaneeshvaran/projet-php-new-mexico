<?php

namespace App\Models;
class User{
    private $id;
    private $firstname;
    private $lastname;
    private $email;
    private $password;

    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getFirstname(){
        return $this->firstname;
    }

    public function setFirstname($firstname){
        $this->firstname = $firstname;
    }

    public function getLastname(){
        return $this->lastname;
    }

    public function setLastname($lastname){
        $this->lastname = $lastname;
    }

    public function getEmail(){
        return $this->email;
    }

    public function setEmail($email){
        $this->email = $email;
    }
    public function getPassword(): string {
        return $this->password;
    }
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }
}