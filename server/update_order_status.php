<?php
session_start();
include('connection.php');

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

$order_id = $data['order_id'];
$status = $data['status'];
$user_id = $_SESSION['user_id'];
$payment_amount = $_SESSION['order_total'];

try {
    // Begin transaction
    $conn->begin_transaction();

    // Insert payment record
    $stmt = $conn->prepare("INSERT INTO payment (user_id, order_id, payment_amount) VALUES (?, ?, ?)");
    $stmt->bind_param('iid', $user_id, $order_id, $payment_amount);
    if (!$stmt->execute()) {
        throw new Exception("Error inserting payment: " . $stmt->error);
    }

    // Update order status
    $stmt = $conn->prepare("UPDATE orders SET order_status = ? WHERE order_id = ?");
    $stmt->bind_param('si', $status, $order_id);
    if (!$stmt->execute()) {
        throw new Exception("Error updating order status: " . $stmt->error);
    }

    // Commit transaction
    $conn->commit();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Rollback transaction
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
} finally {
    $stmt->close();
    $conn->close();
}
?>
