<?php
    session_start();
    include 'conn.php';

    // session_start();
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employee_id'])) {
        $employee = $conn->real_escape_string($_POST['employee_id']);

        // Fetch employee details
        $sql = "SELECT employees.employee_id, employees.position_id
        FROM employees INNER JOIN employee_facial ON employees.employee_id=employee_facial.employee_id
        WHERE employee_facial.employee_id = '$employee' AND employees.position_id = 5";
        $query = $conn->query($sql);

        if ($query->num_rows > 0) {
            $rows = $query->fetch_assoc();
            include 'attendance_faculty.php';
        } else {
            include 'attendance_staff.php';
        }
    }
?>