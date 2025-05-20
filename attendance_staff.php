<?php
    session_start();
    include 'conn.php';
    include 'timezone.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employee_id'])) {
        $employee = $conn->real_escape_string($_POST['employee_id']);

        // Fetch employee details
        $sql = "SELECT * FROM employees WHERE employee_id = '$employee'";
        $query = $conn->query($sql);

        if ($query->num_rows > 0) {
            $row = $query->fetch_assoc();
            $id = $row['employee_id'];

            $date_now = date('Y-m-d');
            $currentTime = date('H:i A');

            // Determine AM or PM
            $dayInd = ($currentTime >= "12:30:00") ? "PM" : "AM";

                // Check existing DTR record
                $sql1 = "SELECT * FROM dtr WHERE employee_id='$id' AND date_log='$date_now'";
                $result = $conn->query($sql1);

                if ($result->num_rows > 0) {
                    $rows = $result->fetch_assoc();

                    if ($dayInd == "AM") {
                        if ($rows['time_in_am'] === '00:00:00') {
                            $sql2 = "UPDATE dtr SET time_in_am='$currentTime' WHERE employee_id='$id' AND date_log='$date_now'";
                            $conn->query($sql2);
                            header('location: index.php');
                            $_SESSION['success'] = 'Successfully logged in.';

                        } elseif ($rows['time_out_am'] == '00:00:00') {
                            $sql3 = "UPDATE dtr SET time_out_am='$currentTime' WHERE employee_id='$id' AND date_log='$date_now'";
                            $conn->query($sql3);
                            header('location: index.php');
                            $_SESSION['success'] = 'Successfully logged out.';
                        } else {
                            header('location: index.php');
                            $_SESSION['error'] = 'Already logged out.';
                        }

                    } elseif ($dayInd == "PM") {
                        if ($rows['time_in_pm'] === '00:00:00') {
                            $sql4 = "UPDATE dtr SET time_in_pm='$currentTime' WHERE employee_id='$id' AND date_log='$date_now'";
                            $conn->query($sql4);
                            header('location: index.php');
                            $_SESSION['success'] = 'Successfully logged in.';

                        } elseif ($rows['time_out_pm'] === '00:00:00') {
                            $sql5 = "UPDATE dtr SET time_out_pm='$currentTime' WHERE employee_id='$id' AND date_log='$date_now'";
                            $conn->query($sql5);
                            header('location: index.php');
                            $_SESSION['success'] = 'Successfully logged out.';
                        } else {
                            header('location: index.php');
                            $_SESSION['error'] = 'Already logged out.';
                        }
                    }
                } else {
                    // Insert new DTR record
                    if ($dayInd == "AM") {
                        $sql6 = "INSERT INTO dtr(employee_id, date_log, time_in_am) VALUES ('$id', '$date_now', '$currentTime')";
                        $conn->query($sql6);
                        header('location: index.php');
                        $_SESSION['success'] = 'Successfully logged in.';
                    } elseif ($dayInd == "PM") {
                        $sql7 = "INSERT INTO dtr(employee_id, date_log, time_in_pm) VALUES ('$id', '$date_now', '$currentTime')";
                        $conn->query($sql7);
                        header('location: index.php');
                        $_SESSION['success'] = 'Successfully logged in.';
                    }
                }

        } else {
            header('location: index.php');
            $_SESSION['error'] = 'Employee ID not found.';
        }
    }
    $conn->close();
?>
