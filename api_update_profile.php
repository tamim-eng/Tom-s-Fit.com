<?php
session_start();
include "db.php";
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$success = false;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Update Age if provided
    if (isset($_POST['age'])) {
        $age = $_POST['age'];
        $stmt = $conn->prepare("UPDATE users SET age=? WHERE id=?");
        $stmt->bind_param("ii", $age, $user_id);
        if ($stmt->execute()) {
            $success = true;
        } else {
            $error = $stmt->error;
        }
    }

    // 2. Update Photo if uploaded
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photo = file_get_contents($_FILES['photo']['tmp_name']);
        // Use send_long_data for large BLOBs effectively, or simple bind_param
        $stmt = $conn->prepare("UPDATE users SET photo=? WHERE id=?");
        $null = NULL; // Placeholder
        $stmt->bind_param("bi", $null, $user_id);
        $stmt->send_long_data(0, $photo);

        if ($stmt->execute()) {
            $success = true;
        } else {
            $error = $stmt->error;
        }
    }

    if ($success) {
        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $error ?? "No changes made"]);
    }
}
?>