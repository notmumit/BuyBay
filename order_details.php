<?php
include('server/connection.php');
include('layout/header.php');

if (isset($_GET['order_details_btn']) && isset($_GET['order_id'])) {
    $order_id = $_GET['order_id'];
    $order_status = $_GET['order_status'];

    // Set order_id and order_total in session
    $_SESSION['order_id'] = $order_id;

    $stmt = $conn->prepare("SELECT * FROM order_items WHERE order_id = ?");
    $stmt->bind_param('i', $order_id);
    $stmt->execute();

    $order_details = $stmt->get_result();
    $order_total_price = calculateTotalOrderPrice($order_details);
    $_SESSION['order_total'] = $order_total_price; // Save total to session as order_total
    $order_details->data_seek(0); // Reset pointer to the beginning
} else {
    header('location:account.php');
    exit;
}

function calculateTotalOrderPrice($order_details) {
    $total = 0;
    while ($row = $order_details->fetch_assoc()) {
        $product_price = $row['product_price'];
        $product_quantity = $row['product_quantity'];
        $total += ($product_price * $product_quantity);
    }
    return $total;
}
include('layout/header.php');
?>


<!-- Order details -->
<section id="orders" class="orders container my-5 py-5">
    <div class="container mt-2">
        <h2 class="font-weight-bold text-center">Order details</h2>
        <hr class="mx-auto">
    </div>
    <table class="mt-5 pt-5 table table-bordered mx-auto">
        <thead>
            <tr>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $order_details->fetch_assoc()) { ?>
            <tr>
                <td>
                    <div class="product-info">
                        <img src="assets/imgs/<?php echo htmlspecialchars($row['product_image']); ?>"/>
                        <div>
                            <p class="mt-3"><?php echo htmlspecialchars($row['product_name']); ?></p>
                        </div>
                    </div>
                </td>
                <td>
                    <span>$<?php echo htmlspecialchars($row['product_price']); ?></span>
                </td>
                <td>
                    <span><?php echo htmlspecialchars($row['product_quantity']); ?></span>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <?php if ($order_status == "on hold") { ?>
        <form style="float: right;" method="POST" action="payment.php">
            <input type="hidden" name="total_order_price" value="<?php echo $order_total_price; ?>" />
            <input type="hidden" name="order_id" value="<?php echo $order_id; ?>" />
            <input id="orderDetailsPayNowBtn" type="submit" class="btn btn-primary" value="Pay Now">
        </form>
    <?php } ?>
</section>

<?php include('layout/footer.php'); ?>