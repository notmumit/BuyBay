<?php
 include('layout/header.php');
include('server/connection.php');

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Handle logout
if (isset($_GET['logout'])) {
    if (isset($_SESSION['logged_in'])) {
        unset($_SESSION['logged_in']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        header('Location: login.php');
        exit;
    }
}

// Handle password change
if (isset($_POST['change_password'])) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirmpassword'];
    $user_email = $_SESSION['user_email'];

    if ($password !== $confirm_password) {
        header('Location: account.php?error=Passwords do not match');
        exit();
    } elseif (strlen($password) < 6) {
        header('Location: account.php?error=Password must be at least 6 characters');
        exit();
    } else {
        $hashed_password = md5($password);

        $stmt = $conn->prepare("UPDATE users SET user_password=? WHERE user_email=?");
        $stmt->bind_param('ss', $hashed_password, $user_email);

        if ($stmt->execute()) {
            header('Location: account.php?message=Password has been updated successfully');
            exit();
        } else {
            header('Location: account.php?error=Could not update password');
            exit();
        }
    }
}

// Fetch user orders
if (isset($_SESSION['logged_in'])) {
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("SELECT * FROM orders WHERE user_id = ?");
    if ($stmt) {
        $stmt->bind_param('i', $user_id);
        $stmt->execute();
        $orders = $stmt->get_result();
        $stmt->close();
    } else {
        // Handle error - prepare failed
        echo "Error preparing statement: " . $conn->error;
        exit();
    }
} else {
    // Handle error - user not logged in
    echo "User not logged in.";
    exit();
}
?>

<!-- Account -->
<section class="container my-5 py-5">
    <div class="row container mx-auto">
        <div class="text-center mt-3 pt-5 col-lg-6 col-md-12 col-sm-12">
            <h3 class="font-weight-bold">Account Info</h3>
            <hr class="mx-auto">
            <div class="account-info">
                <p>Name: <span><?php echo htmlspecialchars($_SESSION['user_name'] ?? ''); ?></span></p>
                <p>Email: <span><?php echo htmlspecialchars($_SESSION['user_email'] ?? ''); ?></span></p>
                <p><a href="#orders" id="orders-btn">Your Orders</a></p>
                <p><a href="wishlist.php" id="wish-btn">Your Wishes</a></p>
                <p><a href="reviews.php" id="review-btn">Your Reviews</a></p>
                <p><a href="account.php?logout=1" id="logout-btn">Logout</a></p>
            </div>
        </div>

        <div class="col-lg-6 col-md-12 col-sm-12">
            <form id="account-form" method="POST" action="account.php">
                <p style="color: red" class="text-center"><?php echo htmlspecialchars($_GET['error'] ?? ''); ?></p>
                <p style="color: green" class="text-center"><?php echo htmlspecialchars($_GET['message'] ?? ''); ?></p>

                <h3>Change Password</h3>
                <hr class="mx-auto">
                <div class="form-group">
                    <label for="account-password">Password</label>
                    <input type="password" class="form-control" id="account-password" name="password" placeholder="Password" required>
                </div>
                <div class="form-group">
                    <label for="account-password-confirm">Confirm Password</label>
                    <input type="password" class="form-control" id="account-password-confirm" name="confirmpassword" placeholder="Confirm Password" required>
                </div>
                <div class="form-group">
                    <input type="submit" value="Change Password" name="change_password" class="btn" id="change-pass-btn">
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Orders -->
<section id="orders" class="orders container my-5 py-5">
    <div class="container mt-2">
        <h2 class="font-weight-bold text-center">Your Orders</h2>
        <hr class="mx-auto">
    </div>
    <table class="mt-5 pt-5 table table-bordered">
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Order Cost</th>
                <th>Order Status</th>
                <th>Order Date</th>
                <th>Order Details</th>
            </tr>
        </thead>
        <tbody>
        <?php
        if (isset($orders) && $orders->num_rows > 0) {
            while ($row = $orders->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['order_id']); ?></td>
                    <td><?php echo htmlspecialchars($row['order_cost']); ?></td>
                    <td><?php echo htmlspecialchars($row['order_status']); ?></td>
                    <td><?php echo htmlspecialchars($row['order_date']); ?></td>
                    <td>
                        <form method="GET" action="order_details.php">
                            <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($row['order_id']); ?>">
                            <input type="hidden" name="order_status" value="<?php echo htmlspecialchars($row['order_status']); ?>">
                            <input class="btn order-details-btn" name="order_details_btn" type="submit" value="Details">
                        </form>
                    </td>
                </tr>
            <?php }
        } else { ?>
            <tr>
                <td colspan="5" class="text-center">No orders found.</td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</section>

<?php include('layout/footer.php'); ?>
