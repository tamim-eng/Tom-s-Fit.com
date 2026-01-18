<?php
include "db.php";

echo "<h1>Database Setup & Repair</h1>";

// 1. Create Users Table
$sql1 = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    age INT,
    photo LONGBLOB,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql1) === TRUE) {
    echo "<p style='color:green'>✔ Users table checked/created.</p>";
} else {
    echo "<p style='color:red'>✘ Error checking users table: " . $conn->error . "</p>";
}

try {
    $conn->query("SELECT age FROM users LIMIT 1");
    echo "<p style='color:green'>✔ Column 'age' exists.</p>";
} catch (Exception $e) {
    $conn->query("ALTER TABLE users ADD COLUMN age INT");
    echo "<p style='color:green'>✔ Added column 'age'.</p>";
}

try {
    $conn->query("SELECT photo FROM users LIMIT 1");
    echo "<p style='color:green'>✔ Column 'photo' exists.</p>";
} catch (Exception $e) {
    $conn->query("ALTER TABLE users ADD COLUMN photo LONGBLOB");
    echo "<p style='color:green'>✔ Added column 'photo'.</p>";
}

$sql4 = "CREATE TABLE IF NOT EXISTS workouts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    date DATE NOT NULL,
    exercise_name VARCHAR(255) NOT NULL,
    sets INT NOT NULL,
    reps INT NOT NULL,
    weight DECIMAL(5,2),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($sql4) === TRUE) {
    echo "<p style='color:green'>✔ Workouts table checked/created.</p>";
} else {
    echo "<p style='color:red'>✘ Error creating workouts table: " . $conn->error . "</p>";
}

echo "<h2>All Done! <a href='login.html'>Go to Login</a></h2>";
?>