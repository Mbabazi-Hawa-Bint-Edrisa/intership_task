<?php
class Person {
    protected $name;
    protected $gender;
    protected $country;
    protected $bio;
    protected $email;
    protected $password;

    public function setData($name, $gender, $country, $bio, $email, $password) {
        $this->name = $name;
        $this->gender = $gender;
        $this->country = $country;
        $this->bio = $bio;
        $this->email = $email;
        $this->password = $password;
    }

    public function getData() {
        return [
            'name' => $this->name,
            'gender' => $this->gender,
            'country' => $this->country,
            'bio' => $this->bio,
            'email' => $this->email
        ];
    }
}
