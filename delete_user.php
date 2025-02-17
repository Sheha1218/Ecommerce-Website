<?php
include_once 'Database.php';
include_once 'User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if (isset($_GET['id']) && isset($_GET['action']) && $_GET['action'] == 'fetch') {
    $details = $user->getUserDetailsById($_GET['id']);
    echo json_encode($details);
    exit;
}

if (isset($_POST['id']) && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $user->id = $_POST['id'];

    if ($user->delete()) {
        echo json_encode(['status' => 'success', 'message' => 'User deleted successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Unable to delete user.']);
    }
    exit;
}

$stmt = $db->prepare("SELECT id FROM users");
$stmt->execute();
$userIds = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/admin_styles.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#id').change(function() {
                var userId = $(this).val();
                if (userId) {
                    $.ajax({
                        url: 'delete_user.php',
                        type: 'GET',
                        data: {id: userId, action: 'fetch'},
                        success: function(response) {
                            var user = JSON.parse(response);
                            if (user) {
                                $('#user-details').html(
                                    '<p><strong>Name:</strong> ' + user.name + '</p>' +
                                    '<p><strong>Email:</strong> ' + user.email + '</p>'
                                );
                            } else {
                                $('#user-details').html('<p class="text-danger">User not found.</p>');
                            }
                        }
                    });
                } else {
                    $('#user-details').html('');
                }
            });

            $('#deleteForm').submit(function(event) {
                event.preventDefault();
                var userId = $('#id').val();
                if (userId) {
                    $.ajax({
                        url: 'delete_user.php',
                        type: 'POST',
                        data: {id: userId, action: 'delete'},
                        success: function(response) {
                            var result = JSON.parse(response);
                            if (result.status === 'success') {
                                $('#message').html('<div class="alert alert-success">' + result.message + '</div>');
                                
                                $('#id option[value="' + userId + '"]').remove();
                            } else {
                                $('#message').html('<div class="alert alert-danger">' + result.message + '</div>');
                            }
                            $('#user-details').html('');
                        }
                    });
                }
            });
        });
    </script>
</head>
<body>
    <div class="container">
        <div id="message"></div>
        <h1 class="mt-5">Delete User</h1>
        
        <form id="deleteForm">
            <div class="mb-3">
                <label for="id" class="form-label">Select User ID</label>
                <select class="form-select" id="id" name="id" required>
                    <option value="" disabled selected>Select a user ID</option>
                    <?php foreach ($userIds as $user): ?>
                        <option value="<?php echo $user['id']; ?>"><?php echo $user['id']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div id="user-details" class="mb-3"></div>
            <button type="submit" class="btn btn-danger">Delete User</button><br><br>
        </form>
        <a href="view_users.php" class="btn btn-primary">Back to User Details</a><br><br>
        <button onclick="location.href='admin_dashboard.php'" class="btn btn-primary">Admin Dashboard</button>
    </div>
</body>
</html>
