<?php
include 'db.php';
if (!isset($_SESSION)) {
  session_start();
}
if(isset($_POST['submit'])){

    $uname = mysqli_real_escape_string($con,$_POST['username']);
    $password = mysqli_real_escape_string($con,$_POST['password']);

    $psw = md5($password);

    if (!empty($uname) OR !empty($psw)){

        $sql = "SELECT email, name, mobile, password FROM useradmin WHERE (email = '$uname' OR mobile = '$uname') AND password = '$psw'";

        $result = mysqli_query($con,$sql);
        
        $count = mysqli_num_rows($result);

        if ($count > 0) {
          $row = mysqli_fetch_assoc($result);

            $_SESSION['user_id'] = $row['email'];
            $_SESSION['username'] = $row['name'];
            $_SESSION['last_activity'] = time();

            header("location: index.php");                             
          }
       else{
          echo "<script>
            alert('Invalid Credentials. Try again');
            window.history.back();
          </script>";
        }
    }else{
          echo "<script>
            alert('Email and password must be filled.');
            window.history.back();
          </script>";
        }

}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <link rel="icon" href="img/iari-logo.png" type="image/x-icon">
  <!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
  <div class="container" style="padding-top: 60px;"> 
  <div class="row">
    <div class="col-md-3"></div>
    <div class="col-md-6">   
    <form method="post">      
      <div class="card border border-success">
        <div class="card-header"><h2 class="text text-success">Login to Dashboard</h2></div>
          <hr class="border border-success">
           <div class="card-body">
              <div class="form-group">
                <div class="row">
                  <div class="col-md-12">
                    <label class="text text-success">E-mail or Mobile</strong></label>
                    <input type="text" name="username" class="form-control"  required="required">
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-md-12">
                    <label class="text text-success">Password</label>
                    <input type="password" name="password" class="form-control" required="required">                     
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-md-12">
                    <input type="submit" name="submit" class="btn btn-success form-control" value="Login">
                  </div>
               </div>
             </div>
            </div>
    </form> 
 </div>
<div class="col-md-3"></div>
</div>
</div>
</body>
</html>
