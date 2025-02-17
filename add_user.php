<?php
include_once 'Database.php';
include_once 'User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$message = "";

if ($_POST) {
    $user->name = $_POST['name'];
    $user->email = $_POST['email'];
    $user->role = $_POST['role'];
    $user->password = $_POST['password'];

    if ($user->create()) {
        $message = "<div class='alert alert-success'>User added successfully.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Unable to add user.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/admin_styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <?php echo $message; ?>
        <h1 class="mt-5">Add New User</h1>
        <form action="add_user.php" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">User Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">User Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">User Role</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="admin">Admin</option>
                    <option value="processing team">Processing team</option>
                    <option value="customer">Customer</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Add User</button><br><br>
        </form>
        <button onclick="location.href='admin_dashboard.php'" class="btn btn-primary">Admin Dashboard</button>
    </div>
</body>
</html>
