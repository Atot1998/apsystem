<?php
$conn = new mysqli("127.0.0.1", "root", "", "apsystem");
$conn->set_charset("utf8");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>