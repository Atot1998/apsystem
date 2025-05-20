<?php session_start(); ?>
<?php include 'header.php'; ?>
<script defer src="https://cdn.jsdelivr.net/npm/@vladmandic/face-api/dist/face-api.min.js"></script>
<style>
        button {
            margin-top: 10px;
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            background-color: #28a745;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
<body class="hold-transition login-page">
<div class="login-box">
  	<div class="login-logo">
  		<p id="date"></p>
      <p id="time" class="bold"></p>
  	</div>
  
  	<div class="login-box-body">
    <?php
        if(isset($_SESSION['error'])){
          echo "
            <div class='alert alert-danger alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-warning'></i> Error!</h4>
              ".$_SESSION['error']."
            </div>
          ";
          unset($_SESSION['error']);
        }
        if(isset($_SESSION['success'])){
          echo "
            <div class='alert alert-success alert-dismissible'>
              <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>&times;</button>
              <h4><i class='icon fa fa-check'></i> Success!</h4>
              ".$_SESSION['success']."
            </div>
          ";
          unset($_SESSION['success']);
        }
      ?>
    	<h4 class="login-box-msg">EMPLOYEE FACE ATTENDANCE</h4>
      <video id="video" width="320" height="240" autoplay muted></video>
  	</div>	

    <form id="attendanceForm" method="POST" action="attendance.php">
        <input type="hidden" name="employee_id" id="nameField">
      <button type="submit" id="submitBtn" hidden>Mark Attendance</button>
    </form>
</div>
<?php include 'scripts.php' ?>

<script type="text/javascript">
$(function() {
  var interval = setInterval(function() {
    var momentNow = moment();
    $('#date').html(momentNow.format('ddd').substring(0,3).toUpperCase() + ' - ' + momentNow.format('MMM DD, YYYY'));  
    $('#time').html(momentNow.format('hh:mm:ss A'));
  }, 100);
    
});
</script>
<script defer src="recognize.js"></script>
</body>
</html>