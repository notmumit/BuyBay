<?php
 include ('layout/header.php');
include('server/connection.php'); // Ensure this path is correct

if (isset($_SESSION['logged_in'])) {
    header('location:account.php');
    exit;
}

if (isset($_POST['login_btn'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Assuming passwords are stored using MD5 hashing

    $stmt = $conn->prepare("SELECT user_id, user_name, user_email FROM users WHERE user_email = ? AND user_password = ? LIMIT 1");
    $stmt->bind_param('ss', $email, $password);

    if ($stmt->execute()) {
        $stmt->bind_result($user_id, $user_name, $user_email);
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->fetch();
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $user_name;
            $_SESSION['user_email'] = $user_email;
            $_SESSION['logged_in'] = true;
            header('location:account.php?message=Logged in successfully');
            exit;
        } else {
            header('location:login.php?error=Could not verify your account');
            exit;
        }
    } else {
        header('location:login.php?error=Something went wrong');
        exit;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .admin-link {
            position: absolute; /* Absolute positioning relative to the parent container */
            top: 100px; /* Adjust this value based on the height of your navbar */
            right: 20px; /* Distance from the right edge */
            font-size: 14px;
            color: #007bff;
            text-decoration: none;
            background-color: #f8f9fa; /* Light background for better visibility */
            padding: 5px 10px;
            border-radius: 5px; /* Rounded corners */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Optional shadow for better visibility */
        }

        .admin-link i {
            margin-right: 5px;
        }

        .admin-link:hover {
            text-decoration: underline;
        }

        .login-container {
            position: relative; /* Ensure the container is relative for absolute positioning */
        }
    </style>
</head>
<body>


<!-- Admin Login Link -->
<a href="http://localhost:8000/admin/admin_login.php" class="admin-link">
    <i class="fas fa-user"></i> Admin Login
</a>

<!-- Login -->
<section class="my-5 py-5">
    <div class="container text-center mt-3 pt-5">
        <h2 class="font-weight-bold">User Login</h2>
        <hr class="mx-auto">
    </div>
    <div class="mx-auto container login-container">
        <form id="login-form" method="POST" action="login.php">
            <p style="color: red" class="text-center"><?php if (isset($_GET['error'])) { echo $_GET['error']; } ?></p>
            <div class="form-group">
                <label>Email</label>
                <input type="text" class="form-control" id="login-email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" class="form-control" id="login-password" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
                <input type="submit" class="btn" id="login-btn" name="login_btn" value="Login"/>
            </div>
            <div class="form-group">
                <a id="register-url" href="register.php" class="btn">Don't have an Account? Register</a>
            </div>
        </form>
    </div>
</section>

<?php include ('layout/footer.php'); ?>
</body>
</html>