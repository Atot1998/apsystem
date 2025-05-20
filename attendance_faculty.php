<?php
    session_start();
    include 'conn.php'; 
    include 'timezone.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['employee_id'])) {
        $employee = $conn->real_escape_string($_POST['employee_id']);

        // Check Faculty
        $sql = "SELECT * FROM employees WHERE employee_id = '$employee' AND position_id = 5";
        $query = $conn->query($sql);

        if ($query->num_rows > 0) {
            $row = $query->fetch_assoc();
            $id = $row['employee_id'];    

            $date_now = date('Y-m-d');
            $currentTime = date('H:i A');

            // Check AM or PM
            $dayInd = ($currentTime >= "12:30:00") ? "PM" : "AM";

                // Check if the faculty has a DAY schedule like SUN, M, T, W, TH, F and SAT
                $query = "SELECT * FROM employee_schedule WHERE employee_id = '$id' AND day LIKE '%" . strtoupper(substr(date('D', strtotime($date_now)), 0, 2)) . "%'";
                $result = $conn->query($query);

                if ($result->num_rows > 0) {
                    $row1 = $result->fetch_assoc();
                    $myid = $row1['employee_id'];
                    $schedule_time = $row1['time_in'];
                    $schedule_timeTo = $row1['time_out'];

                    // $schedule_timestamp = strtotime($schedule_time);
                    // $current_timestamp = strtotime($currentTime);
                    // $time_difference = ($schedule_timestamp - $current_timestamp) / 60;

                    //     if ($time_difference > 20) {
                    //         $_SESSION['error'] = "you cannot time in now. Please wait until 20 minutes before your scheduled time";
                    //         header('location: index.php');
            
                    //     }else{
                            // Check DTR Record
                            $query = "SELECT * FROM dtr WHERE employee_id = '$myid' AND date_log = '$date_now'";
                            $result = $conn->query($query);

                            if ($result->num_rows > 0) {
                                $rows = $result->fetch_assoc();

                                // Update existing record
                                if ($dayInd === "AM") {
                                    if ($rows['time_in_am'] === '00:00:00') {
                                        $query1 = "UPDATE dtr SET time_in_am = '$currentTime' WHERE employee_id='$myid' AND date_log = '$date_now'";
                                        $conn->query($query1);
                                        $_SESSION['success'] = 'Successfully logged in.';
                                        header('location: index.php');

                                    } elseif ($rows['time_out_am'] === '00:00:00') {
                                        if (strtotime($schedule_timeTo) < strtotime($currentTime)) {
                                            $query = "UPDATE dtr SET time_out_am = '$currentTime' WHERE employee_id='$myid' AND date_log = '$date_now'";
                                            $conn->query($query);
                                            $_SESSION['success'] = 'Successfully logged out.';
                                            header('location: index.php');

                                        } else {
                                            $query = "UPDATE dtr SET time_out_am = '$currentTime' WHERE employee_id='$myid' AND date_log = '$date_now'";
                                            $conn->query($query);
                                            $_SESSION['success'] = 'Successfully logged out.';
                                            header('location: index.php');
                                        }

                                    } else {
                                        $_SESSION['error'] = 'Already logged out.';
                                        header('location: index.php');
                                    }

                                } elseif ($dayInd === "PM") {
                                    if ($rows['time_in_pm'] === '00:00:00') {
                                        $query = "UPDATE dtr SET time_in_pm = '$currentTime' WHERE employee_id='$myid' AND date_log = '$date_now'";
                                        $conn->query($query);
                                        $_SESSION['success'] = 'Successfully logged in.';
                                        header('location: index.php');

                                    } elseif ($rows['time_out_pm'] === '00:00:00') {
                                        $query = "UPDATE dtr SET time_out_pm = '$currentTime' WHERE employee_id='$myid' AND date_log = '$date_now'";
                                        $conn->query($query);
                                        $_SESSION['success'] = 'Successfully logged out.';
                                        header('location: index.php');

                                    } else {
                                        $_SESSION['error'] = 'Already logged out.';
                                        header('location: index.php');
                                    }
                                }

                            } else {
                                // Insert new record
                                if ($dayInd == "AM") {
                                    $query = "SELECT * FROM employee_schedule WHERE employee_id = '$myid'";
                                    $result = $conn->query($query);
                                    $rows = $result->fetch_assoc();
                                    if($rows['time_out'] < $currentTime){
                                        $_SESSION['error'] = 'Your time schedule has been done!';
                                        header('location: index.php');
                                        exit();
                                    }else{
                                        $sql6 = "INSERT INTO dtr(employee_id, date_log, time_in_am) VALUES ('$myid', '$date_now', '$currentTime')";
                                        $conn->query($sql6);
                                        $_SESSION['success'] = 'Successfully logged in.';
                                        header('location: index.php');
                                    }
                                } elseif ($dayInd == "PM") {
                                    $query = "SELECT * FROM employee_schedule WHERE employee_id = '$myid'";
                                    $result = $conn->query($query);
                                    $rows = $result->fetch_assoc();
                                    if($rows['time_out'] < $currentTime){
                                        $_SESSION['error'] = 'Your time schedule has been done!';
                                        header('location: index.php');
                                        exit();
                                    }else{
                                        $sql7 = "INSERT INTO dtr(employee_id, date_log, time_in_pm) VALUES ('$myid', '$date_now', '$currentTime')";
                                        $conn->query($sql7);
                                        $_SESSION['success'] = 'Successfully logged in.';
                                        header('location: index.php');
                                    }
                                }

                            }
                        // }
                } else {
                    $_SESSION['error'] = "You don't have a class schedule today.";
                    header('Location: index.php');
                }
            } else {
                $_SESSION['error'] = 'Employee ID not found.';
                header('location: index.php');
            }
        }
    $conn->close();
?>
