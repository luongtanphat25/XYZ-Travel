<?php
    session_start();
    if(isset($_SESSION['email'])){
        $con = mysqli_connect("localhost", "root", "","xyz_travel")
        or die  ("Connection is failed ");

        $email = $_SESSION['email'];
        $query = "select s.re_id, email, s.customer_name, s.address, t.tour_name, t.travel_date
                from Users s, Tours t, Groups g
                where s.re_id = g.re_id && t.tour_id = g.tour_id && email='$email'";
        $result = mysqli_query($con, $query)
        or die ("Failed: " . mysqli_error($con));

        $row = mysqli_fetch_row($result);
        $id = $row [0];
        $email = $row[1];
        $name = $row[2];
        $address = $row[3];
        $tour = $row[4];
        $date = $row[5];

        if (isset($_POST['logout'])) {
            header('location: index.php');
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <!-- Google Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">
    <!-- Bootstrap core CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">
    <title>XYZ Travel Agency - Customer</title>
</head>
<body>

    <div class="jumbotron text-center">
        <h1>Welcome <?php echo $name?></h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fuga ipsam iusto officiis quis sint vitae! Aliquam architecto, et eum explicabo harum ipsa magnam maxime minus non pariatur quaerat vel vitae.</p>
    </div>

    <div class="container">
        <div class="card border-light w-50" style="margin: 0 auto">
            <div class="card-header">Customer Information</div>
            <div class="card-body">
                <p class="card-text">Email: <?php echo $email?></p>
                <p class="card-text">Name: <?php echo $name?></p>
                <p class="card-text">Address: <?php echo $address?>
            </div>

            <div class="card-header">Tour Information</div>
            <div class="card-body">
                <p class="card-text">Tour: <?php echo $tour?></p>
                <p class="card-text">Date: <?php echo $date?></p>
                <p class="card-text">Registration ID: <?php echo $id?>
            </div>

            <div class="card-header">Group Information</div>
            <div class="card-body">
                <?php
                $con = mysqli_connect("localhost", "root", "","xyz_travel")
                or die  ("Connection is failed ");

                $query = "select group_id, group_size from Users where re_id='$id'";
                $result = mysqli_query($con, $query) or die ("Query is failed: " . mysqli_error($con));
                $groupInfo = mysqli_fetch_row($result);
                $group_id = $groupInfo[0];
                $group_size = $groupInfo[1];

                $query = "select email, customer_name, address from Users
          where group_id='$group_id'";
                $result = mysqli_query($con, $query) or die ("Failed: " .mysqli_error($con));
                //
                if($group_id > 0) {
                    echo "<p class=\"card-text\">Group of $group_size - ID: $group_id</p>";
                } else {
                    $miss = $group_size - mysqli_num_rows($result);
                    echo "<p class=\"card-text\">Group of $group_size is not confirmed, waiting for $miss more peoples to join.</p>";
                }

                echo "<table  class=\"table\">";
                echo "<thead class=\"thead-dark\">";
                echo "<tr>
          <th scope=\"col\">Email</th>
          <th scope=\"col\">Name</th>
          <th scope=\"col\">Address</th></tr></thead><tbody>";

                while($row = mysqli_fetch_row($result)) {
                    echo "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td>";
                }
                echo "</table><br><br>";
                mysqli_close($con);
                ?>
            </div>
        </div>
    </div>

    <div class="text-center my-3">
        <form method="post" class="form-group" style="">
            <input class="btn btn-outline-info waves-effect" type="submit" value="Log out" name="logout"/>
        </form>
    </div>

    <footer class="page-footer font-small special-color-dark sticky-bottom">
        <div class="footer-copyright text-center py-3">
            © 2020 Copyright Luong Tan Phat
        </div>
    </footer>
</body>
</html>