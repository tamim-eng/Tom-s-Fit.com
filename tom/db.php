<?php
$conn = new mysqli("localhost", "root", "", "tomsfit");
if ($conn->connect_error) {
    die("Database Connection Failed");
}
