<?php
session_start();
  if(isset($_POST["submitA"])){
    $keyword = $_POST["key"];
    $phno = $_POST["phno"];
    $flag = false;

    if (!empty($keyword)){
      $flag = true;
    }

    $_SESSION["phno"]= $phno;

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

    $key= '%'.$keyword.'%';
    $new= "SELECT street from customer where phone=? ";
    $statement = null;
    if ($statement = $mysqli->prepare($new)) 
    {
      $statement->bind_param('s',$ph_no);
      $statement->execute();
      $statement->store_result();
      mysqli_stmt_store_result($statement); 
      $statement->bind_result($res);
      if(mysqli_stmt_num_rows($statement) == 0){
         echo " <header>
                    <div class='container'>
                        <div class='intro-text'>
                            <div class='intro-lead-in'>Hi..!Your phone number $ph_no is not Registered.<br/> Please try again or contact Admin!
                            </div>
                        </div>
                    </div>
                </header>";
    
      }
      else{
      echo "<header>
              <div class='container'>
                  <div class='intro-lead-in'>Hi..!Your phone number is $ph_no and here are Appliances related to your description $keyword
                  </div>
              </div>
            </header>"; 
      if($flag){
      $qr= "select a.aname,a.description,c.config, c.price from appliance a, catalog c where  a.aname = c.aname and a.description like '{$key}' and c.price != 'discontinued'";
      }else{
      $qr= "select a.aname,a.description,c.config, c.price from appliance a, catalog c where  a.aname = c.aname and c.price != 'discontinued'";
      }

     echo "<form action='order_processing.php' method='POST'>";

    if ($stmt = $mysqli->prepare($qr))
      {
                 $stmt->execute();
                 $stmt->store_result();
                 mysqli_stmt_store_result($stmt); 
                 $stmt->bind_result($aname, $description, $config, $price);
                
                 if (mysqli_stmt_num_rows($stmt) == 0){
                  echo "
                            <div class='container'>
                                <div class='row'>
                                    <div class='col-lg-12 text-center'>
                                        <h2 class='section-heading'>Oops!!</h2>
                                        <h3 class='section-subheading text-muted'>Sorry, No Match Found. Plz Try again.</h3>
                                    </div>
                                </div>
                            </div>
                      ";
                 }
                 else{
                  echo "<div class='container'>
                                <div class='row'>
                                    <div class='col-lg-12 text-center'>";
                   echo "<table border = '1'>\n" ;
                   echo "<tr><th></th><th>Name</th><th>Decription</th><th>Configuration</th><th>Price</th></tr>";
                 while ($stmt->fetch()) {
                    $attr = $aname."+".$config."+".$price;

                 	echo "<tr><td><input type='radio' name='app_sel' value='$attr'></td><td> $aname</td><td> $description</td><td> $config</td><td> $price</td></input>";
                 	echo "</tr>";

                 }
                         echo "</table>\n</div></div></div>";
                         echo "<br/><br/><center><input type='submit' id='search' class= 'page-scroll btn btn-lg' name='submitB' value='Confirm Purchase'/>";
                 }
                         $stmt->close();
	                     $mysqli->close();

                }
                }

    
    echo "</form></center>";
    echo "</body></html>";



}
} 
else{
    header("Location: myphp.php");
    exit;
  }
?>
