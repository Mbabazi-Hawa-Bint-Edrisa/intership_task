<?php
// Parent class for data processing
class DataProcessor {
    protected $data = [];
    protected $errors = [];

    
    protected function sanitizeInput($input) {
        return htmlspecialchars(trim($input));
    }

    public function validate() {
        $this->errors = [];
        
        if (empty($this->data['name'])) {
            $this->errors['name'] = "Name is required";
        }
        
        if (empty($this->data['age']) || !is_numeric($this->data['age'])) {
            $this->errors['age'] = "Valid age is required";
        } elseif ($this->data['age'] < 1 || $this->data['age'] > 120) {
            $this->errors['age'] = "Age must be between 1 and 120";
        }
        
        if (empty($this->data['gender'])) {
            $this->errors['gender'] = "Gender is required";
        }
        
        if (empty($this->data['country'])) {
            $this->errors['country'] = "Country is required";
        }
        
        return empty($this->errors);
    }

    
    public function getProcessedData() {
        return $this->data;
    }

    
    public function getErrors() {
        return $this->errors;
    }
}

// Child class for registration form processing
class RegistrationForm extends DataProcessor {
    public function __construct($postData) {
        
        $this->data = [
            'name' => $this->sanitizeInput($postData['name'] ?? ''),
            'age' => $this->sanitizeInput($postData['age'] ?? ''),
            'gender' => $this->sanitizeInput($postData['gender'] ?? ''),
            'country' => $this->sanitizeInput($postData['country'] ?? ''),
            'bio' => $this->sanitizeInput($postData['bio'] ?? '')
        ];
    }

    
    public function validate() {
        parent::validate(); 
        
        
        if (strlen($this->data['bio']) > 500) {
            $this->errors['bio'] = "Bio must be 500 characters or less";
        }
        
        return empty($this->errors);
    }

   
    public function save() {
        if (!$this->validate()) {
            return false;
        }
        
        
        //  we'll just return true coz we are not saving to the database 
        return true;
    }
}
?>