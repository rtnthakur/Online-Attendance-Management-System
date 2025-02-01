<?php 
session_start();
error_reporting(0);
require_once('include/config.php');
if(strlen( $_SESSION["Empid"])==0)
    {   
header('location:index.php');
}
else{
if(isset($_POST['checkout']))
{
  $empid=$_SESSION['id'];
  $checkoutitme=date('Y-m-d H:i:s', time());
  $cdate=date('Y-m-d');
  $sql="update  attendance set checkOutTime=:checkoutitme where date(checkInTime)=:cdate and empId=:empid";
$query = $dbh -> prepare($sql);
$query->bindParam(':checkoutitme',$checkoutitme,PDO::PARAM_STR);
$query->bindParam(':empid',$empid,PDO::PARAM_STR);
$query->bindParam(':cdate',$cdate,PDO::PARAM_STR);
$query -> execute();

echo "<script>alert('Attendance checkout Successfully');</script>";
echo "<script>window.location.href='mark-attendance.php'</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
   <title>Employee Attendance System</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Main CSS-->
    <link rel="stylesheet" type="text/css" href="../css/main.css">
    <!-- Font-icon css-->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  </head>
  <body class="app sidebar-mini rtl">
    <!-- Navbar-->
   <?php include 'include/header.php'; ?>
    <!-- Sidebar menu-->
    <div class="app-sidebar__overlay" data-toggle="sidebar"></div>
    <?php include 'include/sidebar.php'; ?>
    <main class="app-content">
      
      <div class="row">

        <div class="col-md-12">
          <div class="tile">
              <h2 align="center">Mark Todays Attendance</h2>
              <hr />
       

<!---- Checking Todays Attendance --------->
<?php 
$cdate=date('Y-m-d');
$empid=$_SESSION['id'];
$sql ="SELECT id,checkInTime,checkInIP,checkOutTime FROM attendance WHERE empId=:empid and date(checkInTime)=:cdate";
$query= $dbh->prepare($sql);
$query-> bindParam(':empid',$empid, PDO::PARAM_STR);
$query-> bindParam(':cdate',$cdate, PDO::PARAM_STR);
$query-> execute();
$results = $query -> fetchAll(PDO::FETCH_OBJ);
if($query->rowCount()==0)
{ ?>

              <form class="row" method="post" action="thank-you.php">
                <div class="form-group col-md-6">
                  <label class="control-label">IP Address</label>
                  <?php $ipaddress=$_SERVER['REMOTE_ADDR'];
if($ipaddress=='::1'):
$ip='127.0.0.1';
else: $ip=$ipaddress;
endif;

                  ?>
                  <input type="text" name="ipaddress" id="ipaddress" class="form-control" value="<?php echo $ip;?>" readonly autocomplete="off">
                </div>

                <div class="form-group col-md-6">
           <label class="control-label">Check In Time (Current Time)</label>
                  <input class="form-control" type="text" name="checkintime" id="checkintime" value="<?php echo date('d-m-Y H:i:s', time()); ?>" autocomplete="off">
                </div>
             
                    <div class="form-group col-md-12">
                  <label class="control-label">Remark (If any)</label>
                  <textarea name="remark" id="remark" placeholder="Enter Remark (If any)" class="form-control" autocomplete="off"></textarea> 
                   </div>


               

                <div class="form-group col-md-4 align-self-end">
                  <input type="Submit" name="Submit" id="Submit" class="btn btn-primary" value="Submit">
                </div>
                     </form>
         
<?php } else{  ?>
  <div align="center">
  <img src="../img/attendance.png">
<h1 style="color:blue; padding-top:1%;">Todays attendance already marked.</h1>
<hr />
<h3 style="color:red;">See you tommorow</h3>
<hr />
<?php foreach ($results as $result) { ?>
<table class="table table-hover table-bordered">
      <tr>
    <th>CheckIn IP</th>
      <td><?php echo $result->checkInIP;?></td>
  </tr>
  <tr>
    <th>CheckIn Time</th>
    <td><?php $cintime=$result->checkInTime;
                  echo date("d-m-Y H:i:s", strtotime($cintime));?></td>
  </tr>

  <?php if($cotime=$result->checkOutTime!=''):?>
     <tr>
    <th>Checkout Time</th>
      <td><?php $couttime=$result->checkOutTime;
                  echo date("d-m-Y H:i:s", strtotime($couttime));?></td>
 
  </tr>
<?php endif;?>
</table>
<?php }
  if($cotime==''):?>
<form method="post">
<input type="submit" name="checkout" value="Checkout" class="btn btn-danger btn-lg">
</form>
<?php endif; ?>
</div>
<?php } ?>

            </div>
          </div>
        </div>
      </div>
    </main>
    <!-- Essential javascripts for application to work-->
    <script src="../js/jquery-3.2.1.min.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/main.js"></script>
    <script src="../js/plugins/pace.min.js"></script>
  </body>
</html>
<?php } ?>
