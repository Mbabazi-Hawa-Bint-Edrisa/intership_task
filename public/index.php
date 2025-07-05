<?php include_once "../includes/header.php"; ?>

<div class="container">
    <h2>User Registration</h2>

    <form id="registrationForm">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" placeholder="Your full name" required>

        <label for="age">Age</label>
        <input type="number" id="age" name="age" min="1" max="120" placeholder="Your age" required>

        <label for="gender">Gender</label>
        <select id="gender" name="gender" required>
            <option value="" disabled selected>Select your gender</option>
            <option value="Female">Female</option>
            <option value="Male">Male</option>
            <option value="Other">Other</option>
        </select>

        <label for="country">Country</label>
        <input type="text" id="country" name="country" placeholder="Your country" required>

        <label for="bio">Bio</label>
        <textarea id="bio" name="bio" rows="4" placeholder="Tell us about yourself" required></textarea>

        <button type="submit">Register</button>
    </form>

    <div id="message"></div>
</div>

<script>
document.getElementById('registrationForm').addEventListener('submit', function(e){
    e.preventDefault();

    const form = e.target;
    const data = {
        name: form.name.value.trim(),
        age: form.age.value.trim(),
        gender: form.gender.value,
        country: form.country.value.trim(),
        bio: form.bio.value.trim()
    };

    if (!data.name || !data.age || !data.gender || !data.country || !data.bio) {
        alert("Please fill all fields.");
        return;
    }

    fetch('process.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(res => {
        const msgDiv = document.getElementById('message');
        if (res.success) {
            msgDiv.style.color = 'green';
            msgDiv.textContent = "User registered successfully!";
            form.reset();
        } else {
            msgDiv.style.color = 'red';
            msgDiv.textContent = "❌ " + res.message;
        }
    })
    .catch(err => {
        alert("An error occurred. Try again.");
        console.error(err);
    });
});
</script>

<?php include_once "../includes/footer.php"; ?>
