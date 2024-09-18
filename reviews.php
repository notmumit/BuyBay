<?php
include('layout/header.php');
include('server/connection.php');

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch reviews for the logged-in user
$stmt = $conn->prepare("
    SELECT products.product_name, products.product_image, reviews.rating, reviews.review_text, reviews.review_date 
    FROM reviews 
    JOIN products ON reviews.product_id = products.product_id 
    WHERE reviews.user_id = ?
");
$stmt->bind_param('i', $user_id);
$stmt->execute();
$reviews = $stmt->get_result();
?>

<section id="Reviews" class="orders container my-5 py-5">
    <div class="container text-center mt-5">
        <h2 class="font-weight-bold text-center">Your Reviews</h2>
        <hr class="mx-auto">
        <p class="text-center">Your all the reviews across BuyBay website is here</p>
    </div>
    <div class="row">
        <div class="col-12">
            <table class="table table-striped reviews">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Product Image</th>
                        <th>Ratings</th>
                        <th>Review Text</th>
                        <th>Review Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $reviews->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['product_name']); ?></td>
                            <td><img src="assets/imgs/<?php echo htmlspecialchars($row['product_image']); ?>" alt="<?php echo htmlspecialchars($row['product_name']); ?>" class="img-fluid"></td>
                            <td><?php echo htmlspecialchars($row['rating']); ?></td>
                            <td><?php echo htmlspecialchars($row['review_text']); ?></td>
                            <td><?php echo htmlspecialchars($row['review_date']); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
            <div style="text-align: center; font-weight: bold; color: coral; margin-top: 20px;">
                Your honest feedback is important to us! Please share your genuine product review.
            </div>
        </div>
    </div>
</section>

<?php include('layout/footer.php'); ?>