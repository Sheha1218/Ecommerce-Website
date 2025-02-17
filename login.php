<?php
session_start();
require_once 'Database.php';
require_once 'User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $role = isset($_POST["role"]) ? trim($_POST["role"]) : 'customer';

    $user->email = $email;
    $user->password = $password;
    $user->role = $role;

    $userData = $user->getUserByEmailAndPassword();

    if ($userData) {
        if ($role == 'customer') {
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['user_email'] = $email;
            $_SESSION['role'] = $role;
            
            echo "<script>
                    alert('Login Successful');
                    window.location.href = 'index.php';
                  </script>";
        } elseif ($role == 'admin') {
            echo "<script>
                    alert('Login Successful');
                    window.location.href = 'admin_dashboard.php';
                  </script>";
        } elseif ($role == 'processing team') {
            echo "<script>
                    alert('Login Successful');
                    window.location.href = 'processing_dashboard.php';
                  </script>";
        }
    } else {
        echo "<script>
                alert('Login Failed');
                window.location.href = 'index.php';
              </script>";
    }
}
?>
