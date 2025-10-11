<?php include('layout/header.php'); ?>
<?php include('server/connection.php'); ?>
<?php include('MessageHandler.php'); ?>

<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['logged_in'])) {
    // Retrieve form data and session data
    $user_id = $_SESSION['user_id'];       // Get the logged-in user's ID from session
    $user_email = $_SESSION['user_email']; // Get the logged-in user's email from session
    $user_name = $_POST['user_name'];      // Get the user's name from form input
    $user_message = $_POST['user_message'];// Get the message from form input

    // Create an instance of the MessageHandler class and submit the message
    $messageHandler = new MessageHandler($conn);
    $message = $messageHandler->submitMessage($user_id, $user_name, $user_email, $user_message);
}

// Close the database connection
$conn->close();
?>

<!-- Contact Section -->
<section id="contact" class="container my-5 py-5">
    <div class="container text-center mt-5">
        <h3 class="font-weight-bold">Contact Us</h3>
        <hr class="mx-auto">
        <div class="contact-info">
            <p class="w-50 mx-auto">Phone number: <span>01871842891</span></p>
            <p class="w-50 mx-auto">Email address: <span>sanji@gmail.com</span></p>
            <p class="w-50 mx-auto">Our team is available 24/7 to address your queries and concerns.</p>
        </div>
    </div>
</section>

<!-- DM BuyBay Section -->
<section id="dm-buybay" class="container my-5 py-5">
    <div class="container text-center mt-5">
        <h3 class="font-weight-bold">DM BuyBay</h3>
        <hr class="mx-auto">
        <p class="w-50 mx-auto">Have a request or a complaint? Let us know, and we'll get back to you as soon as possible.</p>
        
        <!-- Display success or error message after form submission -->
        <?php if (isset($message)) { echo $message; } ?>
        
        <!-- Check if the user is logged in -->
        <?php if (isset($_SESSION['logged_in'])) { ?>
            <form action="contact.php" method="POST" class="w-75 mx-auto">
                <div class="form-group">
                    <label for="userName">Your Name</label>
                    <input type="text" class="form-control" id="userName" name="user_name" required>
                </div>
                <div class="form-group">
                    <label for="userMessage">Your Message</label>
                    <textarea class="form-control" id="userMessage" name="user_message" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Submit</button>
            </form>
        <?php } else { ?>
            <p class="text-center" style="color: coral;">
                <strong>Please <a href="login.php" style="color: coral; text-decoration: underline;">log in</a> to DM BuyBay.</strong>
            </p>
        <?php } ?>
    </div>
</section>

<?php include('layout/footer.php'); ?>
