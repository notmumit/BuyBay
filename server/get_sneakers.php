<?php
include('connection.php');

$stmt = $conn->prepare("SELECT * FROM products WHERE product_category='sneakers' LIMIT 4");

$stmt->execute();

$sneakers_products = $stmt->get_result();
?>
