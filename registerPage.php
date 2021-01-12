<?php
    if (isset($_POST['create'])) {
        $con = mysqli_connect("localhost", "root", "","xyz_travel")
        or die  ("Connection is failed ");

        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];
        $address = $_POST['address'];
        $tour = $_POST['tour'];
        $date = $_POST['date'];

        if (empty($name)||empty($email)||empty($password)||empty($address)||empty($date)){
            echo "<script>alert('Please fill all the missing fields!')</script>";
        } else if ($password != $confirm_password) {
            echo "<script>alert('Confirm-password doesn't match with the password!')</script>";
        } else {
            //Insert to Users table.
            $re_id = rand(100000,999999);
            $query = "insert into users value ('$re_id','$email','$password','$name','$address','-1','-1')";
            $result = mysqli_query($con, $query) or die ("Query is failed: " . mysqli_error($con));
            if (mysqli_affected_rows($con) > 0) {
                echo    "<script>
                            var r = confirm('Account is created successfully. Your Registration ID: " . $re_id . "');
                            if (r == true) {
                                window.location.replace('index.php');
                            }
                        </script>";
            } else {
                echo "<script>alert('Fail to create an account!')</script>";
            }

            //Insert to Tours table
            $query = "select * from Tours where tour_name = '$tour' && travel_date = '$date'";
            $result = mysqli_query($con, $query) or die ("Query is failed at Tours 0: " . mysqli_error($con));
            if (mysqli_num_rows($result) == 0) {
                $tour_id = rand(100,999);
                $query = "insert into Tours value ('$tour_id','$tour','$date')";
                $result = mysqli_query($con, $query) or die ("Query is failed at Tours 1: " . mysqli_error($con));
            }
            $query = "select tour_id from Tours where tour_name = '$tour' && travel_date = '$date'";
            $result = mysqli_query($con, $query) or die ("Query is failed at Tours 2: " . mysqli_error($con));
            $tour_id = mysqli_fetch_row($result)[0];

            $query = "insert into Groups value ('$tour_id','$re_id')";
            $result = mysqli_query($con, $query) or die ("Query is failed at Groups: " . mysqli_error($con));
        }
    }

    if (isset($_POST['back'])) {
        header('location: index.php');
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

    <title>XYZ Travel Agency - Register</title>
</head>
<body>
    <div class="jumbotron text-center">
        <h3>XYZ Travel Agency - Register</h3>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ex, nesciunt voluptatem. Accusamus dignissimos distinctio esse inventore labore quia rem repellendus saepe, veritatis! Accusamus alias autem doloremque officia rem repellendus ut!</p>
    </div>

    <div class="container p-3 my-3 w-50">
        <form method="post">
            <h3>User Information</h3>

            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" name="name" placeholder="Enter username" id="username"/>
            </div>

            <div class="form-group">
                <label for="email">Email address:</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="Email address"/>
            </div>

            <div class="form-group">
                <label for="password">Password:</label>
                <input class="form-control" type="password" name="password"/>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm-Password:</label>
                <input class="form-control" type="password" name="confirm_password" id="confirm_password"/>
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <input class="form-control" id="address" type="text" name="address" placeholder="ex: Toronto"/><br><br>
            </div>

            <h3>Tour Information</h3>
            <div class="form-group">
                <label for="tours">Tour</label>
                <select class="form-control" name="tour" id="tours">
                    <option value="CN Tower">CN Tower</option>
                    <option value="Wonderland">Wonderland</option>
                    <option value="Thousand Islands">Thousand Islands</option>
                </select>
            </div>

            <div class="form-group">
                <label for="date">Travel Date:</label>
                <input id="date" class="form-control" type="date" name="date"/>
            </div>

            <div class="form-group">
                <input class="btn btn-primary" type="submit" value="Create" name="create"/>
                <input class="btn btn-light float-right" type='submit' value="Back" name="back"/>
            </div>
        </form>
    </div>

    <footer class="page-footer font-small special-color-dark sticky-bottom">
        <div class="footer-copyright text-center py-3">© 2020 Copyright
            <a href="https://luongtanphat25.github.io/">Luong Tan Phat</a>
        </div>
    </footer>
</body>
</html>