<?php
namespace models;

class UserModel {
    public $firstname;
    public $lastname;
    public $position;
    public $email;
    public $password;
    public $image;
    public $major;
    public $school;
    public $sex;
    public $school_year;

    public function __construct($user,$file) {
        $this->firstname = $user["firstname"];
        $this->lastname = $user["lastname"];
        $this->position = $user["position"];
        $this->email = $user["email"];
        $this->password = $user["password"];
        $this->image = $file["image"];
        $this->major = $user['major'];
        $this->school = $user['school'];
        $this->sex = $user['sex'];
        if(!empty($user['school_year'])){
            $this->school_year = $user['school_year'];
        }else{
            $this->school_year = null;
        }
    }
}