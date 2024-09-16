<?php
session_start();
include('server/connection.php');

if (isset($_SESSION['logged_in']) && isset($_POST['product_id']) && isset($_POST['rating']) && isset($_POST['review_text'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $rating = $_POST['rating'];
    $review_text = $_POST['review_text'];

    $stmt = $conn->prepare("INSERT INTO reviews (product_id, user_id, rating, review_text) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("iiis", $product_id, $user_id, $rating, $review_text);
    $stmt->execute();

    header("Location: single_product.php?product_id=" . $product_id);
} else {
    header("Location: login.php");
}
?>