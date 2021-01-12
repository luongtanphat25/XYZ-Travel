<?php
session_start();
if(isset($_SESSION['editID'])){
    $con = mysqli_connect("localhost", "root", "","xyz_travel")
    or die  ("Connection is failed ");

    $editID = $_SESSION['editID'];
    $query = "select s.re_id, email, s.customer_name, s.address, t.tour_name, t.travel_date
              from Users s, Tours t, Groups g
              where s.re_id = g.re_id && t.tour_id = g.tour_id && s.re_id='$editID'
              order by t.travel_date, t.tour_name";
    $result = mysqli_query($con, $query)
    or die ("Failed: " . mysqli_error($con));

    $row = mysqli_fetch_row($result);
    $email = $row[1];
    $name = $row[2];
    $address = $row[3];
    $tour = $row[4];
    $date = $row[5];

    if(isset($_POST['save'])){
        $name = $_POST['name'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $tour = $_POST['tour'];
        $date = $_POST['date'];
        $errors = 0;
        if (empty($name)||empty($email)||empty($address)||empty($date)){
            echo "Please fill all the missing fields!";
        } else {
            $query = "update Users set email='$email',customer_name='$name',address='$address' where re_id='$editID'";
            $result = mysqli_query($con, $query) or die ("query is failed: " . mysqli_error($con));

            //Update the new tour to Tours Table
            $query = "select * from Tours where tour_name = '$tour' && travel_date = '$date'";
            $result = mysqli_query($con, $query) or die ("Query is failed: " . mysqli_error($con));
            if (mysqli_num_rows($result) == 0) {
                $tour_id = rand(100,999);
                $query = "insert into Tours value ('$tour_id','$tour','$date')";
                $result = mysqli_query($con, $query) or die ("Query is failed: " . mysqli_error($con));
            }

            //Update Groups Table
            $query = "select tour_id from Tours where tour_name = '$tour' && travel_date = '$date'";
            $result = mysqli_query($con, $query) or die ("Query is failed: " . mysqli_error($con));
            $tour_id = mysqli_fetch_row($result)[0];

            $query = "update Groups set tour_id='$tour_id' where re_id='$editID'";
            $result = mysqli_query($con, $query) or die ("Query is failed: " . mysqli_error($con));

            header('location: adminPage.php');
        }
        mysqli_close($con);
    }

    if (isset($_POST['back'])) {
        header('location: adminPage.php');
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
    <title>XYZ Travel Agency - Admin Page</title>
</head>
<body>
<div class="jumbotron text-center">
    <h1>XYZ Travel Agency - Edit User</h1>
    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fuga ipsam iusto officiis quis sint vitae! Aliquam architecto, et eum explicabo harum ipsa magnam maxime minus non pariatur quaerat vel vitae.</p>
</div>

<div class="container p-3 my-3 w-50">
    <form method="post">

        <div class="card border-light" style="margin: 0 auto">
            <div class="card-header">Registration ID: <?php echo $_SESSION['editID']?></div>
            <div class="card-body">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input class="form-control" type="text" name="name" id="name" value="<?php echo $name?>"/>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input class="form-control" type="email" id="name" name="email" value="<?php echo $email?>"/>
                </div>
                <div class="form-group">
                    <label for="address">Address:</label>
                    <input class="form-control" id="address" type="text" name="address" value="<?php echo $address?>"/>
                </div>
            </div>
            <div class="card-header">Tour Information</div>
            <div class="card-body">
                <div class="form-group">
                    <label for="tour">Tour</label>
                    <select id="tour" class="form-control" name="tour">
                        <option value="CN Tower" <?php if ($tour == 'CN Tower') {echo 'selected';}?>>CN Tower</option>
                        <option value="Wonderland" <?php if ($tour == 'Wonderland') {echo 'selected';}?>>Wonderland</option>
                        <option value="Thousand Islands" <?php if ($tour == 'Thousand Islands') {echo 'selected';}?>>Thousand Islands</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="travel_date">Travel Date:</label>
                    <input class="form-control" id="travel_date" type="date" name="date" value="<?php echo $date?>"/>
                </div>
                <div class="form-group">
                    <input class="btn btn-info"type="submit" value="Save" name="save"/>
                    <input class="btn btn-light float-right" type='submit' value='Back' name='back'/>
                </div>
            </div>
        </div>
    </form>
</div>

<footer class="page-footer font-small special-color-dark sticky-bottom">
    <div class="footer-copyright text-center py-3">
        © 2020 Copyright Luong Tan Phat
    </div>
</footer>
</body>
</html>
