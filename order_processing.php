<?php
session_start();
  if(!isset($_POST["submitB"])){
        header("Location: myphp.php");
    exit;
  }
  else{


    $dbhost = "127.0.0.1:3307";
    $dbuser = "root";
    $dbpass = "apple";
    $dbname = "hw_3";
    $mysqli = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname);

    if(mysqli_connect_errno()){
      die("Database connection failed:".
        mysqli_connect_error().
          "(".mysqli_connect_errno().")"
      );
    }
 
  

echo "
      <html lang = 'en'>
      <head>
        <title>Appliances Store</title>
          <link href='/css/font-awesome.min.css' rel='stylesheet' type='text/css'>
          <link href='style.css' rel='stylesheet' type='text/css'>
          <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css'>
      </head>
      <body>
        <div id='main' class='container'>
          <nav class='navbar-fluid navbar-default navbar-fixed-top'>
            <div class='container'>
              <a class='navbar-brand' href='/myphp.html'> Appliance Store! </a>
              <p class='navbar-right navbar-text'> New way of Shopping..</p>
            </div>
          </nav>
          <div class='col-md-8'>";

  
    $ph_no=$_SESSION["phno"];
  
   $val= $_POST["app_sel"]; 
   $sel= explode("+", $val);
   $appname= $sel[0];
   $config= $sel[1];
   $price= $sel[2];
   $q1= "SELECT o_time,quantity,price from orders where aname = ? and config = ? and phone = ? and status = 'Pending' and 1=1";
    if ($statement = $mysqli->prepare($q1)) 
    {
      $statement->bind_param('sss',$appname,$config,$ph_no);
      $statement->execute();
      $statement->store_result();
      mysqli_stmt_store_result($statement); 
      $statement->bind_result($o_time,$quantity,$price);
      if(mysqli_stmt_num_rows($statement) == 0){
        if($stmt = $mysqli->prepare("insert into orders(phone,aname,config,o_time,quantity,price,status) values (?,?,?,now(),1,?,'Pending')")){
         $stmt->bind_param("ssss", $ph_no,$appname,$config,$price);
         $stmt->execute();
         $stmt->close();
         }
      } else{
        if($stmt = $mysqli->prepare("update orders set quantity=quantity+1,o_time=now(),price=price+1,status='Pending' where aname = ? and phone=? and config=? and status='Pending' and 1=1")){
          $stmt->bind_param("sss", $appname,$ph_no,$config);
          $stmt->execute();
          $stmt->close();
        }
      }
      $statement->close();
    }
    echo "<h2> Order Summary</h2>";
    $q3 ="SELECT * from Customer where phone = ?";
      if ($st = $mysqli->prepare($q3)) 
      {
      $st->bind_param('s',$ph_no);
      $st->execute();
      $st->bind_result($ph_no,$buldno,$street,$apt);
        if ($st->fetch()){
        echo "<br>
            <b>Customer Phone number:</b> $ph_no<br/>
            <b>Customer Address</b><br/>
              <div style='text-indent: 5em;'> Apt: $apt <br/> <div style='text-indent: 5em;'> Street: $street</div><br/><br/>";
        }
          $st->close();
      }
      $q2 ="SELECT aname,config,o_time,price,quantity,status from orders where aname = ? and config = ? and phone = ? and status = 'Pending'";
      if ($statement = $mysqli->prepare($q2)) 
      {
      $statement->bind_param('sss',$appname,$config,$ph_no);
      $statement->execute();
      $statement->bind_result($aname,$config,$o_time,$price,$qty,$status);
        echo  "<div class='container'>
              <table><tr><th>Name</th><th>Configuration</th><th>Order Time</th><th>Price</th><th>Quantity</th><th>Status</th></tr>";
        while ($statement->fetch()) {
        echo "<tr><td>$aname</td><td>$config</td><td>$o_time</td><td>$price</td><td>$qty</td><td>$status</td></input>";
        echo "</tr>";
        }
            echo "</table>\n</div>";
        }
    $statement->close();
    $mysqli->close();
  }
      echo "</form></center>";
    echo "</body></html>";


?>
