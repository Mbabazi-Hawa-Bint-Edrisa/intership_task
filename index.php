<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 20px auto; }
        h2 { margin-top: 30px; }
        form { margin-bottom: 20px; }
        input, button { padding: 8px; margin: 5px; }
        button { background-color: #007bff; color: white; border: none; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <h1>User Management</h1>
    <p><a href="dashboard.php">Back to Dashboard</a> | <a href="logout.php">Logout</a></p>
    
    <!-- Create User -->
    <h2>Create User</h2>
    <form id="createForm">
        <input type="hidden" id="createCsrfToken" name="csrf_token">
        <input type="text" id="createName" placeholder="Name" required>
        <input type="email" id="createEmail" placeholder="Email" required>
        <input type="password" id="createPassword" placeholder="Password" required>
        <button type="submit">Create</button>
    </form>

    <!-- Update User -->
    <h2>Update User</h2>
    <form id="updateForm">
        <input type="hidden" id="updateCsrfToken" name="csrf_token">
        <input type="number" id="updateId" placeholder="User ID" required>
        <input type="text" id="updateName" placeholder="Name" required>
        <input type="email" id="updateEmail" placeholder="Email" required>
        <input type="password" id="updatePassword" placeholder="Password" required>
        <button type="submit">Update</button>
    </form>

    <!-- Delete User -->
    <h2>Delete User</h2>
    <form id="deleteForm">
        <input type="hidden" id="deleteCsrfToken" name="csrf_token">
        <input type="number" id="deleteId" placeholder="User ID" required>
        <button type="submit">Delete</button>
    </form>

    <!-- List Users -->
    <h2>Users</h2>
    <button id="listUsers">List All Users</button>
    <div id="usersList"></div>

    <script>
        // Get CSRF token
        let csrfToken;
        $.get('api.php/csrf-token', function(data) {
            csrfToken = data.csrf_token;
            $('#createCsrfToken').val(data.csrf_token);
            $('#updateCsrfToken').val(data.csrf_token);
            $('#deleteCsrfToken').val(data.csrf_token);
        });

        // Create user
        $('#createForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: 'api.php/users',
                method: 'POST',
                contentType: 'application/json',
                headers: { 'X-CSRF-Token': csrfToken },
                data: JSON.stringify({
                    name: $('#createName').val(),
                    email: $('#createEmail').val(),
                    password: $('#createPassword').val()
                }),
                success: function(response) {
                    alert('User created: ' + response.name);
                    $('#createForm')[0].reset();
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.error);
                }
            });
        });

        // Update user
        $('#updateForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: 'api.php/users/' + $('#updateId').val(),
                method: 'PUT',
                contentType: 'application/json',
                headers: { 'X-CSRF-Token': csrfToken },
                data: JSON.stringify({
                    name: $('#updateName').val(),
                    email: $('#updateEmail').val(),
                    password: $('#updatePassword').val()
                }),
                success: function(response) {
                    alert('User updated: ' + response.name);
                    $('#updateForm')[0].reset();
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.error);
                }
            });
        });

        // Delete user
        $('#deleteForm').submit(function(e) {
            e.preventDefault();
            $.ajax({
                url: 'api.php/users/' + $('#deleteId').val(),
                method: 'DELETE',
                headers: { 'X-CSRF-Token': csrfToken },
                success: function(response) {
                    alert(response.message);
                    $('#deleteForm')[0].reset();
                },
                error: function(xhr) {
                    alert('Error: ' + xhr.responseJSON.error);
                }
            });
        });

        // List users
        $('#listUsers').click(function() {
            $.get('api.php/users', function(data) {
                let html = '<ul>';
                data.forEach(user => {
                    html += `<li>ID: ${user.id}, Name: ${user.name}, Email: ${user.email}, Created: ${user.created_at}</li>`;
                });
                html += '</ul>';
                $('#usersList').html(html);
            });
        });
    </script>
</body>
</html>