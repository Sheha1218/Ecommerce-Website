<?php
include_once 'Database.php';
include_once 'User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$message = "";

$user_data = [
    'id' => '',
    'name' => '',
    'email' => '',
    'role' => '',
    'password' => '',
];

$all_users = $user->getAllUsers();

$available_roles = ['admin', 'processing team', 'customer'];

if ($_POST) {
    $user->id = $_POST['id'];
    $user->name = $_POST['name'];
    $user->email = $_POST['email'];
    $user->role = $_POST['role'];
    $user->password = $_POST['password'];

    if ($user->update()) {
        $message = "<div class='alert alert-success'>User updated successfully.</div>";
    } else {
        $message = "<div class='alert alert-danger'>Unable to update user.</div>";
    }
} elseif (isset($_GET['id'])) {
    $user->id = $_GET['id'];
    $user_data = $user->getUserById();

    if (!$user_data) {
        $message = "<div class='alert alert-danger'>User not found.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/admin_styles.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <?php echo $message; ?>
        <h1 class="mt-5">Update User</h1>
        <form action="update_user.php" method="get" id="userSelectForm">
            <div class="mb-3">
                <label for="userSelect" class="form-label">Select User</label>
                <select class="form-control" id="userSelect" name="id" onchange="document.getElementById('userSelectForm').submit();">
                    <option value="">Select a user</option>
                    <?php foreach ($all_users as $user_item) { ?>
                        <option value="<?php echo htmlspecialchars($user_item['id']); ?>" <?php echo $user_item['id'] == $user_data['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($user_item['name']); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
        </form>

        <form action="update_user.php" method="post">
            <div class="mb-3">
                <label for="id" class="form-label">User ID</label>
                <input type="text" class="form-control" id="id" name="id" value="<?php echo htmlspecialchars($user_data['id']); ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">User Name</label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo htmlspecialchars($user_data['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">User Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="role" class="form-label">User Role</label>
                <select class="form-control" id="role" name="role" required>
                    <option value="">Select a role</option>
                    <?php foreach ($available_roles as $role) { ?>
                        <option value="<?php echo htmlspecialchars($role); ?>" <?php echo $role == $user_data['role'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($role); ?>
                        </option>
                    <?php } ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
        <a href="view_users.php" class="btn btn-primary mt-3">Back to User Details</a><br><br>
        <button onclick="location.href='admin_dashboard.php'" class="btn btn-primary">Admin Dashboard</button>
    </div>

    <script>
        document.getElementById('userSelect').addEventListener('change', function() {
            document.getElementById('userSelectForm').submit();
        });
    </script>
</body>
</html>
