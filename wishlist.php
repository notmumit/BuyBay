<?php
include('layout/header.php');
include('server/connection.php');

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get the current number of wishlist items
$stmt = $conn->prepare("SELECT COUNT(*) AS count FROM wishlist WHERE user_id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
$wishlist_count = $result->fetch_assoc()['count'];

// Handle addition to wishlist
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_wishlist'])) {
    $product_id = $_POST['product_id'];

    if ($wishlist_count < 10) {
        // Get product image
        $stmt = $conn->prepare("SELECT product_image FROM products WHERE product_id = ?");
        $stmt->bind_param('i', $product_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $product = $result->fetch_assoc();
        $product_image = $product['product_image'];

        // Check if the product is already in the wishlist
        $stmt = $conn->prepare("SELECT * FROM wishlist WHERE user_id = ? AND product_id = ?");
        $stmt->bind_param('ii', $user_id, $product_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            // Add the product to the wishlist
            $stmt = $conn->prepare("INSERT INTO wishlist (user_id, product_id, product_image) VALUES (?, ?, ?)");
            $stmt->bind_param('iis', $user_id, $product_id, $product_image);
            $stmt->execute();
        }
        $stmt->close();
        
        // Update the wishlist count
        $wishlist_count++;
    }
}

// Handle removal from wishlist
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['remove_from_wishlist'])) {
    $product_id = $_POST['product_id'];

    // Remove the product from the wishlist
    $stmt = $conn->prepare("DELETE FROM wishlist WHERE user_id = ? AND product_id = ?");
    $stmt->bind_param('ii', $user_id, $product_id);
    $stmt->execute();
    $stmt->close();

    // Update the wishlist count
    $wishlist_count--;
}

// Fetch wishlist items
$stmt = $conn->prepare("SELECT products.* FROM wishlist JOIN products ON wishlist.product_id = products.product_id WHERE wishlist.user_id = ?");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$wishlist = $stmt->get_result();
?>
<section id="Wishlist" class="container my-5 py-5">
<div class="container text-center mt-5">
        <h2 class="font-weight-bold text-center">Your Wishlist</h2>
        <hr class="mx-auto">
        <?php if ($wishlist_count >= 10) { ?>
            <p class="text-center text-danger">Your wishlist queue is full!</p>
        <?php } else { ?>
            <p class="text-center text-success">You can add up to 10 products in your wishlist.</p>
        <?php } ?>
    </div>
    <div class="row">
        <?php while ($row = $wishlist->fetch_assoc()) { ?>
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card">
                    <img src="assets/imgs/<?php echo htmlspecialchars($row['product_image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['product_name']); ?>">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($row['product_name']); ?></h5>
                        <p class="card-text">$<?php echo htmlspecialchars($row['product_price']); ?></p>
                        <form action="wishlist.php" method="POST" class="d-flex justify-content-between">
                            <input type="hidden" name="product_id" value="<?php echo htmlspecialchars($row['product_id']); ?>">
                            <button type="submit" name="remove_from_wishlist" class="btn btn-danger">Remove</button>
                            <a href="single_product.php?product_id=<?php echo htmlspecialchars($row['product_id']); ?>" class="wish-buy-btn">Buy Now</a>

</form>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</section>
<?php include('layout/footer.php'); ?>