<?php
// public/payment_verify.php
require_once '../config/db.php';
require_once '../src/functions.php';
session_start();

$method = $_GET['method'] ?? '';
$status = $_GET['status'] ?? '';

if ($status === 'success') {
    $amount = $_SESSION['pending_donation']['amount'] ?? 0;
    $name = $_SESSION['pending_donation']['donor_name'] ?? 'Anonymous';
    $message = $_SESSION['pending_donation']['message'] ?? '';
    $anon = $_SESSION['pending_donation']['is_anonymous'] ?? 0;
    $user_id = $_SESSION['user_id'] ?? null;
    $receiver_id = $_SESSION['pending_donation']['receiver_id'] ?? null;

    if ($amount > 0) {
        try {
            $stmt = $pdo->prepare("INSERT INTO money_donations (donor_name, amount, message, is_anonymous, user_id, receiver_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $amount, $message, $anon, $user_id, $receiver_id]);
            
            // Notify Admin
            addNotification($pdo, 'Money Donation (Real-ish)', "Received Rs. " . number_format($amount) . " from $name via $method");

            unset($_SESSION['pending_donation']);
            setFlash('success', 'Thank you! Your donation of Rs. ' . number_format($amount) . ' via ' . ucfirst($method) . ' was successful.');
        } catch (PDOException $e) {
            setFlash('error', 'Database error: ' . $e->getMessage());
        }
    }
} else {
    setFlash('error', 'Payment was cancelled or failed.');
}

redirect('donate_money.php');
