<?php
session_start(); 
include 'conn.php';

$result = $conn->query("SELECT employee_id, descriptor FROM employee_facial");
$user = [];
while ($row = $result->fetch_assoc()) {
    $user[] = $row;
}
echo json_encode($user);
?>
