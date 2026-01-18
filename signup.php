<?php
include "db.php";

$data = json_decode(file_get_contents("php://input"), true);

$name = $data['name'];
$email = $data['email'];
$password = password_hash($data['password'], PASSWORD_DEFAULT);

$sql = "INSERT INTO users (name,email,password) VALUES (?,?,?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $name, $email, $password);

echo json_encode(["success" => $stmt->execute()]);
?>