<?php
include('layout/header.php');


// Initialize the cart session variable if it is not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}

if (isset($_POST['add_to_cart'])) {
    if (isset($_SESSION['cart'])) {
        $products_array_ids = array_column($_SESSION['cart'], "product_id");

        if (!in_array($_POST['product_id'], $products_array_ids)) {
            $product_id = $_POST['product_id'];
            $product_array = array(
                'product_id' => $_POST['product_id'],
                'product_name' => $_POST['product_name'],
                'product_price' => $_POST['product_price'],
                'product_image' => $_POST['product_image'],
                'product_quantity' => max(1, $_POST['product_quantity']) // Ensure quantity is not negative
            );

            $_SESSION['cart'][$product_id] = $product_array;
        } else {
            echo '<script>alert("Product was already added to cart");</script>';
        }
    } else {
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $product_price = $_POST['product_price'];
        $product_image = $_POST['product_image'];
        $product_quantity = max(1, $_POST['product_quantity']); // Ensure quantity is not negative

        $product_array = array(
            'product_id' => $product_id,
            'product_name' => $product_name,
            'product_price' => $product_price,
            'product_image' => $product_image,
            'product_quantity' => $product_quantity
        );

        $_SESSION['cart'][$product_id] = $product_array;
    }

    calculateTotalCart();
    header("Location: cart.php"); // Refresh the page to update the session variable
    exit();
} else if (isset($_POST['remove_product'])) {
    $product_id = $_POST['product_id'];
    unset($_SESSION['cart'][$product_id]);
    calculateTotalCart();
    header("Location: cart.php"); // Refresh the page to update the session variable
    exit();
} else if (isset($_POST['edit_quantity'])) {
    $product_id = $_POST['product_id'];
    $product_quantity = max(1, $_POST['product_quantity']); // Ensure quantity is not negative

    if (isset($_SESSION['cart'][$product_id])) {
        $product_array = $_SESSION['cart'][$product_id];
        $product_array['product_quantity'] = $product_quantity;

        $_SESSION['cart'][$product_id] = $product_array;
        calculateTotalCart();
    }
    header("Location: cart.php"); // Refresh the page to update the session variable
    exit();
}

function calculateTotalCart() {
    $total_price = 0;
    $total_quantity = 0;
    
    foreach ($_SESSION['cart'] as $key => $value) {
        $price = $value['product_price'];
        $quantity = $value['product_quantity'];
    
        $total_price += ($price * $quantity);
        $total_quantity += $quantity;
    }
    
    $_SESSION['cart_total'] = $total_price;
    $_SESSION['quantity'] = $total_quantity;
}

// Ensure the total cart is calculated before rendering the page content
calculateTotalCart();
?>

<!-- Cart -->
<section class="cart container my-5 py-5">
    <div class="container mt-5">
        <h2 class="font-weight-bold">Your Cart</h2>
    </div>
    <table class="mt-5 pt-5">
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Subtotal</th>
        </tr>

        <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) { 
            foreach ($_SESSION['cart'] as $key => $value) { ?>

        <tr>
            <td>
                <div class="product-info">
                    <img src="assets/imgs/<?php echo $value['product_image']; ?>" />
                    <div>
                        <p><?php echo $value['product_name']; ?></p>
                        <small><span>$</span><?php echo $value['product_price']; ?></small>
                        <br>
                        <form method="POST" action="cart.php">
                            <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>" />
                            <input type="submit" name="remove_product" class="remove-btn" value="remove" />
                        </form>
                    </div>
                </div>
            </td>
            <td>
                <form method="POST" action="cart.php">
                    <input type="hidden" name="product_id" value="<?php echo $value['product_id']; ?>" />
                    <input type="number" name="product_quantity" value="<?php echo $value['product_quantity']; ?>" min="1" />
                    <input type="submit" class="edit-btn" value="edit" name="edit_quantity" />
                </form>
            </td>
            <td>
                <span>$</span>
                <span class="product-price"><?php echo $value['product_quantity'] * $value['product_price']; ?></span>
            </td>
        </tr>

        <?php } } else { ?>

        <tr>
            <td colspan="3" class="text-center">Your cart is empty.</td>
        </tr>

        <?php } ?>

    </table>

    <div class="cart-total">
    <table>
        <tr>
            <td>Total:</td>
            <td><?php echo isset($_SESSION['cart_total']) ? '$' . strval($_SESSION['cart_total']) : '$0'; ?></td>
        </tr>
    </table>
</div>


    <div class="checkout-container">
        <form method="POST" action="checkout.php">
            <input type="submit" class="btn checkout-btn" value="Checkout" name="checkout">
        </form>
    </div>
</section>

<?php include('layout/footer.php'); ?>
