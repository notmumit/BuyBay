<?php
include('layout/header.php');
include('connection.php');

if (empty($_SESSION['cart'])) {
    header('location: index.php');
    exit;
}

// Save cart total to session when placing an order
$_SESSION['cart_total'] = isset($_SESSION['cart_total']) ? $_SESSION['cart_total'] : 0;
?>

<!-- Checkout -->
<section class="my-5 py-5">
    <div class="container text-center mt-3 pt-5">
        <h2 class="font-weight-bold">Check Out</h2>
        <hr class="mx-auto">
        <?php if (isset($_GET['message'])): ?>
            <p class="text-center" style="color: coral; display: inline;">
                <?php echo htmlspecialchars($_GET['message']); ?> 
                <strong><a href="login.php" style="color: coral; text-decoration: underline;">log in</a></strong> to continue.
            </p>
        <?php endif; ?>
    </div>
    <div class="mx-auto container">
        <form id="checkout-form" method="POST" action="server/place_order.php">
            <div class="form-group checkout-small-element">
                <label>Name</label>
                <input type="text" class="form-control" id="checkout-name" name="name" placeholder="Name" required>
            </div>
            <div class="form-group checkout-small-element">
                <label>Email</label>
                <input type="text" class="form-control" id="checkout-email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group checkout-small-element">
                <label>Phone</label>
                <input type="tel" class="form-control" id="checkout-phone" name="phone" placeholder="Phone" required>
            </div>
            <div class="form-group checkout-small-element">
                <label>City</label>
                <input type="text" class="form-control" id="checkout-city" name="city" placeholder="City" required>
            </div>
            <div class="form-group checkout-large-element">
                <label>Address</label>
                <input type="text" class="form-control" id="checkout-address" name="address" placeholder="Address" required>
            </div>
            <div class="form-group checkout-btn-container">
                <p>Total Amount: $<?php echo isset($_SESSION['cart_total']) ? $_SESSION['cart_total'] : '0'; ?></p>
                <input type="submit" class="btn" id="checkout-btn" name="place_order" value="Place Order"/>
            </div>
        </form>
    </div>
</section>

<?php include('layout/footer.php'); ?>