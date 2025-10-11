<?php
include('server/connection.php');

// Fetch random products for the "Related Products" section
$relatedStmt = $conn->prepare("SELECT * FROM products ORDER BY RAND() LIMIT 4");
$relatedStmt->execute();
$relatedProducts = $relatedStmt->get_result();

if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    $stmt = $conn->prepare("SELECT * FROM products WHERE product_id=?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $product = $stmt->get_result();

    // Fetch reviews
    $reviewStmt = $conn->prepare("
        SELECT reviews.*, users.user_name 
        FROM reviews 
        JOIN users ON reviews.user_id = users.user_id 
        WHERE reviews.product_id = ?
    ");
    $reviewStmt->bind_param("i", $product_id);
    $reviewStmt->execute();
    $reviews = $reviewStmt->get_result();

} else {
    header('location: index.php');
}
?>

<?php include('layout/header.php'); ?>

<!-- Single product Section -->
<section class="container single-product my-5 pt-5">
    <div class="row mt-5">
        <?php while ($row = $product->fetch_assoc()) { ?>
            <div class="col-lg-5 col-md-6 col-sm-12">
                <img class="img-fluid w-100 pb-1" src="assets/imgs/<?php echo $row['product_image']; ?>" id="mainImg" />
                <div class="small-img-group">
                    <div class="small-img-col">
                        <img src="assets/imgs/<?php echo $row['product_image']; ?>" width="100%" class="small-img" />
                    </div>
                    <div class="small-img-col">
                        <img src="assets/imgs/<?php echo $row['product_image2']; ?>" width="100%" class="small-img" />
                    </div>
                    <div class="small-img-col">
                        <img src="assets/imgs/<?php echo $row['product_image3']; ?>" width="100%" class="small-img" />
                    </div>
                    <div class="small-img-col">
                        <img src="assets/imgs/<?php echo $row['product_image4']; ?>" width="100%" class="small-img" />
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12 col-12">
                <h6>Product</h6>
                <h3 class="py-4"><?php echo $row['product_name']; ?></h3>
                <h2>$<?php echo $row['product_price']; ?></h2>

                <form method="POST" action="cart.php">
                    <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>"/>
                    <input type="hidden" name="product_image" value="<?php echo $row['product_image']; ?>"/>
                    <input type="hidden" name="product_name" value="<?php echo $row['product_name']; ?>"/>
                    <input type="hidden" name="product_price" value="<?php echo $row['product_price']; ?>"/>
                    <input type="number" name="product_quantity" value="1" min="1"/>
                    <button class="buy-btn" type="submit" name="add_to_cart">Add To Cart</button>
                </form>

                <!-- Add to Wishlist Form -->
                <?php if (isset($_SESSION['logged_in'])) { ?>
                    <form method="POST" action="wishlist.php" class="mt-3">
                        <input type="hidden" name="product_id" value="<?php echo $row['product_id']; ?>"/>
                        <button type="submit" name="add_to_wishlist" class="btn btn-outline-primary">
                            <i class="fas fa-heart"></i> Add to Wishlist
                        </button>
                    </form>
                <?php } ?>
                
                <h4 class="mt-5 mb-5">Product details</h4>
                <span><?php echo $row['product_description']; ?></span>
            </div>
        <?php } ?>
    </div>
</section>

<!-- Reviews Section -->
<section class="container my-5">
    <h3>Product Reviews</h3>
    <div class="accordion" id="reviewsAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingReviews">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReviews" aria-expanded="true" aria-controls="collapseReviews">
                    Reviews
                </button>
            </h2>
            <div id="collapseReviews" class="accordion-collapse collapse show" aria-labelledby="headingReviews" data-bs-parent="#reviewsAccordion">
                <div class="accordion-body">
                    <?php if ($reviews->num_rows > 0) { ?>
                        <?php while ($review = $reviews->fetch_assoc()) { ?>
                            <div class="review">
                                <div class="review-header">
                                    <strong><?php echo htmlspecialchars($review['user_name'] ?? 'Anonymous'); ?></strong>
                                    <span class="text-muted"><?php echo date('d M Y', strtotime($review['review_date'])); ?></span>
                                </div>
                                <div class="review-body">
                                    <div class="rating">
                                        <?php for ($i = 0; $i < $review['rating']; $i++) { ?>
                                            <i class="fas fa-star"></i>
                                        <?php } ?>
                                        <?php for ($i = $review['rating']; $i < 5; $i++) { ?>
                                            <i class="far fa-star"></i>
                                        <?php } ?>
                                    </div>
                                    <p><?php echo htmlspecialchars($review['review_text']); ?></p>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } else { ?>
                        <p>No reviews yet. <?php echo isset($_SESSION['user_id']) ? 'Feel free to review this product!' : 'Login to BuyBay to be the first to review this product!'; ?></p>
                    <?php } ?>
                </div>
            </div>
        </div>
        
        <?php if (isset($_SESSION['user_id'])) { ?>
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingLeaveReview">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLeaveReview" aria-expanded="false" aria-controls="collapseLeaveReview">
                        Leave a Review
                    </button>
                </h2>
                <div id="collapseLeaveReview" class="accordion-collapse collapse" aria-labelledby="headingLeaveReview" data-bs-parent="#reviewsAccordion">
                    <div class="accordion-body">
                        <form action="submit_review.php" method="POST" class="review-form">
                            <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                            <div class="mb-3">
                                <label for="rating" class="form-label">Rating</label>
                                <select class="form-select" id="rating" name="rating" required>
                                <option value="5">5 - Excellent</option>
                                    <option value="4">4 - Very Good</option>
                                    <option value="3">3 - Good</option>
                                    <option value="2">2 - Fair</option>
                                    <option value="1">1 - Poor</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="review_text" class="form-label">Review</label>
                                <textarea class="form-control" id="review_text" name="review_text" rows="3" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit Review</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</section>

<!-- Related Products Section -->
<section id="related-products" class="my-5 pb-5">
    <div class="container text-center mt-5 py-5">
        <h3>Related products</h3>
        <hr class="mx-auto">
    </div>
    <div class="row mx-auto container-fluid">
        <?php while ($relatedRow = $relatedProducts->fetch_assoc()) { ?>
            <div onclick="window.location.href='single_product.php?product_id=<?php echo $relatedRow['product_id']; ?>';" class="product text-center col-lg-3 col-md-4 col-sm-12">
                <img class="img-fluid mb-3" src="assets/imgs/<?php echo $relatedRow['product_image']; ?>" />
                <div class="star">
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                    <i class="fas fa-star"></i>
                </div>
                <h5 class="p-name"><?php echo $relatedRow['product_name']; ?></h5>
                <h4 class="p-price">$<?php echo $relatedRow['product_price']; ?></h4>
                <a href="single_product.php?product_id=<?php echo $relatedRow['product_id']; ?>"><button class="buy-btn">Buy Now</button></a>
            </div>
        <?php } ?>
    </div>
</section>

<script>
    var mainImg = document.getElementById("mainImg");
    var smallImg = document.getElementsByClassName("small-img");

    for (let i = 0; i < smallImg.length; i++) {
        smallImg[i].onclick = function() {
            mainImg.src = smallImg[i].src;
        }
    }
</script>

<?php include('layout/footer.php'); ?>
