<?php

namespace App\Models;
class Group{
    private $id;
    private $name;
    private $description;
    private $created_at;
    private $access_type;
    
    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }
    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function getDescription(){
        return $this->description;
    }

    public function setDescription($description){
        $this->description = $description;
    }

    public function getCreatedAt(){
        return $this->created_at;
    }

    public function setCreatedAt($created_at){
        $this->created_at = $created_at;
    }

    public function getAccessType() {
        return $this->access_type;
    }

    public function setAccessType($access_type) {
        $this->access_type = $access_type;
    }
}