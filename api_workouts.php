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
$method = $_SERVER['REQUEST_METHOD'];

// Handle GET (Read)
if ($method === 'GET') {
    $sql = "SELECT * FROM workouts WHERE user_id = ? ORDER BY date DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $workouts = [];
    while ($row = $result->fetch_assoc()) {
        $workouts[] = $row;
    }
    echo json_encode($workouts);
    exit;
}

// Handle POST (Create)
if ($method === 'POST') {
    $data = json_decode(file_get_contents("php://input"), true);
    $sql = "INSERT INTO workouts (user_id, date, exercise_name, sets, reps, weight) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issiid", $user_id, $data['date'], $data['exercise_name'], $data['sets'], $data['reps'], $data['weight']);
    echo json_encode(["success" => $stmt->execute(), "id" => $stmt->insert_id]);
    exit;
}

// Handle PUT (Update)
if ($method === 'PUT') {
    $data = json_decode(file_get_contents("php://input"), true);
    $sql = "UPDATE workouts SET date=?, exercise_name=?, sets=?, reps=?, weight=? WHERE id=? AND user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiidii", $data['date'], $data['exercise_name'], $data['sets'], $data['reps'], $data['weight'], $data['id'], $user_id);
    echo json_encode(["success" => $stmt->execute()]);
    exit;
}

// Handle DELETE
if ($method === 'DELETE') {
    $data = json_decode(file_get_contents("php://input"), true);
    $sql = "DELETE FROM workouts WHERE id=? AND user_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $data['id'], $user_id);
    echo json_encode(["success" => $stmt->execute()]);
    exit;
}
?>