<?php
include_once 'Database.php';
include_once 'User.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);

$name = $email = $password = $confirm_password = "";
$name_err = $email_err = $password_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (empty(trim($_POST["name"]))) {
        $name_err = "Please enter a name.";
    } else {
        $name = trim($_POST["name"]);
    }

    if (empty(trim($_POST["email"]))) {
        $email_err = "Please enter an email.";
    } else {
        $email = trim($_POST["email"]);
    }

    if (empty(trim($_POST["password"]))) {
        $password_err = "Please enter a password.";     
    } elseif (strlen(trim($_POST["password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $password = trim($_POST["password"]);
    }

    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";     
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if ($password != $confirm_password) {
            $confirm_password_err = "Password did not match.";
        }
    }

    if (empty($name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {

        $user->name = $name;
        $user->email = $email;
        $user->role = 'customer';
        $user->password = $password;

        if ($user->create()) {
            echo "<script>
                    alert('Registration Successful');
                    window.location.href = 'index.php';
                  </script>";
            exit();
        } else {
            echo "Unable to create user.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Cyber-Deals</title>
    <link href="css/customer_register_styles.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="customer-index">
        <nav class="navbar">
            <div class="container">
                <a href="#" class="brand">Cyber-Deals</a>
                <ul class="nav-links">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="customer_shop.php">Shop</a></li>
                    <li><a href="customer_register.php">Register</a></li>
                </ul>
            </div>
        </nav>

        <div class="error-messages">
            <?php 
            if (!empty($name_err)) echo "<p>$name_err</p>";
            if (!empty($email_err)) echo "<p>$email_err</p>";
            if (!empty($password_err)) echo "<p>$password_err</p>";
            if (!empty($confirm_password_err)) echo "<p>$confirm_password_err</p>";
            ?>
        </div>

        <div class="register" id="register">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id="registerForm">
                <div>
                    <fieldset>
                        <legend>User Information</legend>
                        <div class="regForm">
                            <input type="text" name="name" placeholder="Name" required>
                            <i class='bx bxs-user'></i>
                        </div>
                        <div class="regForm">
                            <input type="email" name="email" placeholder="Email Address" required>
                            <i class='bx bxl-gmail'></i>
                        </div>
                        <div class="regForm">
                            <input type="password" name="password" id="password" placeholder="Password" required>
                            <i class='bx bxs-lock'></i>
                        </div>
                        <div class="regForm">
                            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>
                            <i class='bx bxs-lock'></i>
                        </div>
                        <input type="submit" class="btn" value="Register">
                    </fieldset>
                </div>
            </form>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                var success = "<?php echo $success ?? ''; ?>";
                if (success) {
                    alert('Registration Successful');
                    window.location.href = 'index.php';
                }
                });
            </script>
        </div>
    </div>
    <footer class="footer">
        <div class="container">
            <p>&copy; 2024 Cyber-Deals. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
