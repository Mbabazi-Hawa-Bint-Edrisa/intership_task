<?php
class Person {
    protected $name;
    protected $age;
    protected $gender;
    protected $country;
    protected $bio;

    public function setData($name, $age, $gender, $country, $bio) {
        $this->name = $name;
        $this->age = $age;
        $this->gender = $gender;
        $this->country = $country;
        $this->bio = $bio;
    }

    public function getData() {
        return [
            'name' => $this->name,
            'age' => $this->age,
            'gender' => $this->gender,
            'country' => $this->country,
            'bio' => $this->bio,
        ];
    }
}
