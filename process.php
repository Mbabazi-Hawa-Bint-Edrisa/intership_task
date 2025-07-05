<?php
require_once 'classes.php';

// Handle form submission
$formSubmitted = false;
$registrationSuccess = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formSubmitted = true;
    $registration = new RegistrationForm($_POST);
    
    if ($registration->validate()) {
        $registrationSuccess = $registration->save();
    } else {
        $errors = $registration->getErrors();
    }
}

//  form view
require 'form.php';
?>