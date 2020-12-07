<?php
    require_once 'config.php';
    require_once 'core/controller.Class.php';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login with Google</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous">
</head>
<body>
    <div class="container" style="margin-top: 100px;">
        <?php 
        if(isset($_COOKIE['id']) && isset($_COOKIE['sess'])){
            $Controller = new Controller;
            if($Controller->checkUserStatus($_COOKIE['id'], $_COOKIE['sess'])){
                echo $Controller->printData(intval($_COOKIE['id']));
                echo '<a href="logout.php">Logout</a>';
            }else{
                echo 'Error!';
            }
        }else{ 
            ?>
        <img src="img/logo.png" alt="Logo" style="display: table;margin: 0 auto; max-width: 150px;">
        <form action="" method="POST">
            <div class="form-group">
                <label for="exampleInputEmail1">Email Address</label>
                <input type="email" class="form-control" id="email1" placeholder="Enter email">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" class="form-control" id="password1" placeholder="Enter password">
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
            <button onClick="window.location = '<?php echo $login_url; ?>'" class="btn btn-danger" type="button">Login with Google</button>
        </form>
        <?php } ?>
    </div>
</body>
</html>