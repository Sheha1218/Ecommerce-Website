<?php
session_start();

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_email'])) {
    echo json_encode(["status" => false, "message" => "User not logged in."]);
    exit;
}

include_once 'Database.php';
include_once 'Feedback.php';

$database = new Database();
$db = $database->getConnection();
$feedback = new Feedback($db);

$feedback->user_id = $_SESSION['user_id'];
$feedback->user_email = $_SESSION['user_email'];
$feedback->feedback = $_POST['feedback'];
$feedback->rating = $_POST['rating'];

if ($feedback->addFeedback()) {
    echo json_encode(["status" => true, "message" => "Feedback submitted successfully!"]);
} else {
    echo json_encode(["status" => false, "message" => "Failed to submit feedback."]);
}
?>
