<?php
    session_start();

    if (isset($_POST['login'])) {
        $con = mysqli_connect("localhost", "root", "","xyz_travel")
        or die  ("Connection is failed ");

        $email = mysqli_real_escape_string($con, $_POST['email']);
        $password = mysqli_real_escape_string($con, $_POST['password']);

        $query = "select * from users where email = '$email' && password = '$password'";
        $result = mysqli_query($con, $query) or die ("Query is failed: " . mysqli_error($con));

        if (mysqli_num_rows($result) != 0) {
            if( $email == 'admin@gmail.com') {
                header('location: adminPage.php');
            } else {
                $_SESSION['email'] = $_POST['email'];
                header('location: userPage.php');
            }
        } else {
            echo "<script>alert('Login is failed.')</script>";
        }
        mysqli_close($con);
    }

    if (isset($_POST['create'])) {
        header('location: registerPage.php');
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

    <title>XYZ Travel Agency</title>
</head>
<body>
    <div class="jumbotron text-center">
        <h1>Welcome to XYZ Travel Agency</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fuga ipsam iusto officiis quis sint vitae! Aliquam architecto, et eum explicabo harum ipsa magnam maxime minus non pariatur quaerat vel vitae.</p>
    </div>

    <div class="container p-3 my-3 stylish-color text-white w-50 sticky-bottom">
        <h3>Login</h3>
        <form method="post">
            <div class="form-group">
                <label for="email">Email address: </label>
                <input type="email" class="form-control" placeholder="Please enter email" id="email" name="email"/>
            </div>

            <div class="form-group">
                <label for="password">Password: </label>
                <input type="password" class="form-control" id="password" name="password"/>
            </div>

            <div>
                <input class="btn btn-primary" type="submit" name="login" value="Login"/>
                <input class="btn btn-light" type="submit" name="create" value="Create an account"/>
            </div>
        </form>
    </div>

    <footer class="page-footer font-small special-color-dark fixed-bottom">
        <div class="footer-copyright text-center py-3">© 2020 Copyright
            <a href="https://luongtanphat25.github.io/">Luong Tan Phat</a>
        </div>
    </footer>
</body>
</html>