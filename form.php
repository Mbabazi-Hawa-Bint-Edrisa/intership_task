<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <style>
        .error { color: red; }
        .success { color: green; }
        form { max-width: 500px; margin: 0 auto; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, select, textarea { width: 100%; padding: 8px; }
        button { padding: 10px 15px; background: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <h1>Registration Form</h1>
    
    <?php if ($formSubmitted && $registrationSuccess): ?>
        <div class="success">Registration successful! Thank you.</div>
    <?php endif; ?>
    
    <form method="post">
        <div class="form-group">
            <label for="name">Name*</label>
            <input type="text" id="name" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>">
            <?php if (isset($errors['name'])): ?>
                <div class="error"><?= $errors['name'] ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="age">Age*</label>
            <input type="number" id="age" name="age" value="<?= htmlspecialchars($_POST['age'] ?? '') ?>">
            <?php if (isset($errors['age'])): ?>
                <div class="error"><?= $errors['age'] ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label>Gender*</label>
            <div>
                <input type="radio" id="male" name="gender" value="male" <?= isset($_POST['gender']) && $_POST['gender'] === 'male' ? 'checked' : '' ?>>
                <label for="male" style="display: inline;">Male</label>
                
                <input type="radio" id="female" name="gender" value="female" <?= isset($_POST['gender']) && $_POST['gender'] === 'female' ? 'checked' : '' ?>>
                <label for="female" style="display: inline;">Female</label>
                
                <input type="radio" id="other" name="gender" value="other" <?= isset($_POST['gender']) && $_POST['gender'] === 'other' ? 'checked' : '' ?>>
                <label for="other" style="display: inline;">Other</label>
            </div>
            <?php if (isset($errors['gender'])): ?>
                <div class="error"><?= $errors['gender'] ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="country">Country*</label>
            <select id="country" name="country">
                <option value="">Select Country</option>
                <option value="kenya" <?= isset($_POST['country']) && $_POST['country'] === 'kenya' ? 'selected' : '' ?>>kenya</option>
                <option value="Canada" <?= isset($_POST['country']) && $_POST['country'] === 'Canada' ? 'selected' : '' ?>>Canada</option>
                <option value="uganda" <?= isset($_POST['country']) && $_POST['country'] === 'uganda' ? 'selected' : '' ?>>uganda</option>
                <option value="congo" <?= isset($_POST['country']) && $_POST['country'] === 'congo' ? 'selected' : '' ?>>congo</option>
                <option value="Other" <?= isset($_POST['country']) && $_POST['country'] === 'Other' ? 'selected' : '' ?>>Other</option>
            </select>
            <?php if (isset($errors['country'])): ?>
                <div class="error"><?= $errors['country'] ?></div>
            <?php endif; ?>
        </div>
        
        <div class="form-group">
            <label for="bio">Bio</label>
            <textarea id="bio" name="bio" rows="4"><?= htmlspecialchars($_POST['bio'] ?? '') ?></textarea>
            <?php if (isset($errors['bio'])): ?>
                <div class="error"><?= $errors['bio'] ?></div>
            <?php endif; ?>
        </div>
        
        <button type="submit">Register</button>
    </form>
</body>
</html>