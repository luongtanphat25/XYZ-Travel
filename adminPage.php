<?php
session_start();

$con = mysqli_connect("localhost", "root", "","xyz_travel")
or die  ("Connection is failed ");

if(isset($_POST['logout'])){
    header('location: index.php');
}

if (!empty($_GET['delete_id'])) {
    $value = $_GET['delete_id'];
    $query = "delete from Groups where re_id='$value'";
    $result = mysqli_query($con, $query)
    or die ("Failed" .mysqli_error($con));

    $query = "delete from Users where re_id='$value'";
    $result = mysqli_query($con, $query)
    or die ("Failed" .mysqli_error($con));

    if (mysqli_affected_rows($con) > 0)
        header('location: adminPage.php');
    else
        echo "The account is not deleted.<br><br>";
}

if(!empty($_GET['edit_id'])) {
    $value = $_GET['edit_id'];
    $query = "select * from Users where re_id='$value'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) != 0) {
        $_SESSION['editID'] = $value;
        header('location: editUser.php');
    } else {
        echo "Account is not found!<br><br>";
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
        <h1>XYZ Travel Agency - Admin page</h1>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Fuga ipsam iusto officiis quis sint vitae! Aliquam architecto, et eum explicabo harum ipsa magnam maxime minus non pariatur quaerat vel vitae.</p>
    </div>

    <form method="post">
        <div class="container">
            <div class="row my-3">
                <div class="col"><h3>Customer's information</h3></div>
                <div class="col"><input class="btn btn-outline-info waves-effect float-right" type="submit" value="Log out" name="logout"/></div>
            </div>

            <?php
            $con = mysqli_connect("localhost", "root", "","xyz_travel")
            or die  ("Connection is failed ");

            $query = "select s.re_id, email, s.customer_name, s.address, t.tour_name, t.travel_date
                  from Users s, Tours t, Groups g
                  where s.re_id = g.re_id && t.tour_id = g.tour_id
                  order by t.travel_date, t.tour_name";

            $result = mysqli_query($con, $query)
            or die ("Failed: " .mysqli_error($con));

            echo "<table  class=\"table\">";
            echo "<thead class=\"thead-dark\">";

            echo "<tr>
                <th scope=\"col\">Registration ID</th>
                <th scope=\"col\">Email</th>
                <th scope=\"col\">Name</th>
                <th scope=\"col\">Address</th>
                <th scope=\"col\">Tour</th>
                <th scope=\"col\">Date</th>
                <th scope=\"col\"></th>
                </tr></thead><tbody>";

            while($row = mysqli_fetch_row($result)) {
                echo "<tr><th scope=\"row\">$row[0]</th>
                        <td>$row[1]</td>
                        <td>$row[2]</td>
                        <td>$row[3]</td> 
                        <td>$row[4]</td>
                        <td>$row[5]</td>
                        <td>
                            <a class=\"btn btn-outline-warning waves-effect\" href='adminPage.php?edit_id=$row[0]'>Edit</a>
                            <a class=\"btn btn-outline-danger waves-effect\" href='adminPage.php?delete_id=$row[0]'>Delete</a>
                        </td>
                    </tr></tbody>";
            }
            echo "</table>";
            mysqli_close($con);
            ?>

            <h3>Create a group</h3>

            <div class="form-group">
                <label for="size">Enter group size: </label>
                <input class="form-control" type="number" name="size" id="size"/>
             </div>
            <div class="form-group my-3">
                <input class="btn btn-outlin-info waves-effect" type="submit" name="group" value="Group">
            </div>
        </div>

            <?php
            if(isset($_POST['group'])){
                if ($_POST['size'] <= 0) {
                    echo "<script>alert('Size must be greater than 0.')</script>";
                } else {

                    $size = $_POST['size'];
                    $con = mysqli_connect("localhost", "root", "","xyz_travel")
                    or die  ("Connection is failed ");

                    //Get tours information to group
                    $query = "select * from Tours";
                    $result = mysqli_query($con, $query) or die ("Failed: " .mysqli_error($con));

                    //Group on each tour
                    while ($row = mysqli_fetch_row($result)) {
                        //Get each tour_id
                        $queryFind = "select s.re_id, email, s.customer_name, s.address, t.tour_name, t.travel_date
                      from Users s, Tours t, Groups g
                      where s.re_id = g.re_id && t.tour_id = g.tour_id && t.tour_id='$row[0]'";
                        $resultFind = mysqli_query($con, $queryFind) or die ("Failed: " .mysqli_error($con));

                        //Calculate how many group with the group size
                        $numRow = mysqli_num_rows($resultFind);
                        if (($numRow % $size) != 0){
                            $isEnough = false;
                            $numberOfGroup = intdiv($numRow, $size) + 1;
                        } else {
                            $numberOfGroup = $numRow / $size;
                            $isEnough = true;
                        }
                        //Group
                        for ($x = 1; $x <= $numberOfGroup; $x++){
                            echo '<div class="container my-3">';
                            echo '<div class="card border-light" style="margin: 0 auto">';
                            echo "<div class=\"card-header\">Group of $size to $row[1] on $row[2]</div>";
                            echo'<div class="card-body">';

                            echo "<table  class=\"table\">";
                            echo "<thead class=\"thead-dark\">";
                            echo "<tr>
                                        <th scope=\"col\">Registration ID</th>
                                        <th scope=\"col\">Email</th>
                                        <th scope=\"col\">Name</th>
                                        <th scope=\"col\">Address</th>
                                        <th scope=\"col\">Tour</th>
                                        <th scope=\"col\">Date</th>
                                        <th scope=\"col\"></th>
                                        </tr></thead><tbody>";


                            if($x == $numberOfGroup) {
                                if ($isEnough) {
                                    $groupID = rand(11,99);
                                    echo "<h5>Group ID: $groupID</h5>";
                                } else {
                                    $groupID = rand(-99,-1);
                                    echo "<h5>Group is not confirmed.</h5>";
                                }

                                while ($rowFind = mysqli_fetch_row($resultFind)){
                                    echo "<tr><th scope=\"row\">$rowFind[0]</th>
                                                <td>$rowFind[1]</td>
                                                <td>$rowFind[2]</td>
                                                <td>$rowFind[3]</td> 
                                                <td>$rowFind[4]</td>
                                                <td>$rowFind[5]</td></tr></tbody>";
                                    $queryUpdateGroup = "update Users set group_id='$groupID', group_size='$size' where re_id='$rowFind[0]'";
                                    mysqli_query($con, $queryUpdateGroup) or die ("Update group_id failed: " .mysqli_error($con));
                                }
                            } else {
                                $groupID = rand(11,99);
                                echo "<h5>Group ID: $groupID</h5>";
                                for($i = 1; $i <= $size; $i++){
                                    $rowFind = mysqli_fetch_row($resultFind);
                                    echo "<tr><td>$rowFind[0]</td><td>$rowFind[1]</td><td>$rowFind[2]</td>
                        <td>$rowFind[3]</td><td>$rowFind[4]</td><td>$rowFind[5]</td></tr>";
                                    $queryUpdateGroup = "update Users set group_id='$groupID', group_size='$size' where re_id='$rowFind[0]'";
                                    mysqli_query($con, $queryUpdateGroup) or die ("Update group_id failed: " .mysqli_error($con));
                                }
                            }

                            echo "</table></div></div></div>";
                        }
                    }
                    mysqli_close($con);
                }
            }
            ?>
</form>

    <!--    Footer-->
    <footer class="page-footer font-small special-color-dark sticky-bottom">
        <div class="footer-copyright text-center py-3">
            © 2020 Copyright Luong Tan Phat
        </div>
    </footer>
</body>
</html>