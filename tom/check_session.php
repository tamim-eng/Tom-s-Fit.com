<?php
session_start();
include "db.php";
header('Content-Type: application/json');

if (isset($_SESSION['user_id'])) {
    // Fetch full user details from DB to get photo
    $stmt = $conn->prepare("SELECT name, email, age, photo FROM users WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    $photo_base64 = null;
    if ($user['photo']) {
        $photo_base64 = base64_encode($user['photo']);
    }

    echo json_encode([
        "loggedIn" => true,
        "user" => [
            "name" => $user['name'],
            "email" => $user['email'],
            "age" => $user['age'],
            "photo" => $photo_base64
        ]
    ]);
} else {
    echo json_encode(["loggedIn" => false]);
}
?>