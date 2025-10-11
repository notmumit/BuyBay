<?php
session_start();
include('connection.php');

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || !isset($_SESSION['user_id']) || !isset($_SESSION['user_email'])) {
    // Redirect to checkout page with a message if the user is not logged in
    header('Location: ../checkout.php?message=You must be logged in to place an order.');
    exit;
}

// Check if form data is posted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $city = $_POST['city'];
    $address = $_POST['address'];
    $total = $_SESSION['cart_total'];
    $user_id = $_SESSION['user_id'];

    // Insert order into the database
    $stmt = $conn->prepare("INSERT INTO orders (order_cost, user_id, user_phone, user_city, user_address, order_date) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param('disss', $total, $user_id, $phone, $city, $address);
    $stmt->execute();

    $order_id = $stmt->insert_id; // Get the newly created order ID

    // Insert each cart item into the order_items table
    foreach ($_SESSION['cart'] as $product_id => $product) {
        // Check if the expected keys are present in the $product array
        if (isset($product['product_name'], $product['product_price'], $product['product_quantity'], $product['product_image'])) {
            $product_name = $product['product_name'];
            $product_price = $product['product_price'];
            $product_quantity = $product['product_quantity'];
            $product_image = $product['product_image'];

            $stmt = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, product_image, product_price, product_quantity, user_id, order_date) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param('iissdii', $order_id, $product_id, $product_name, $product_image, $product_price, $product_quantity, $user_id);
            $stmt->execute();
        } else {
            // Debug output for missing keys
            echo "Missing keys in product array for product ID $product_id: ";
            var_dump($product);
        }
    }

    // Set session variables for the new order
    $_SESSION['order_id'] = $order_id;
    $_SESSION['order_total'] = $total;

    // Clear the cart
    unset($_SESSION['cart']);
    unset($_SESSION['cart_total']);

    // Redirect to the payment page
    header('Location: ../payment.php');
    exit;
} else {
    // Redirect back to checkout if cart is empty or form data is not posted
    header('Location: ../checkout.php');
    exit;
}
?>
