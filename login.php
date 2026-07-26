<?php
session_start();
require 'config/database.php';

$error="";

if($_SERVER["REQUEST_METHOD"]=="POST")
{
    $username=$_POST['username'];
    $password=$_POST['password'];

    $stmt=$pdo->prepare("SELECT * FROM users WHERE username=? LIMIT 1");
    $stmt->execute([$username]);

    $user=$stmt->fetch(PDO::FETCH_ASSOC);

    if($user && password_verify($password,$user['password']))
    {
        $_SESSION['user_id']=$user['id'];
        $_SESSION['fullname']=$user['fullname'];
        $_SESSION['role']=$user['role_id'];

        header("Location: dashboard.php");
        exit;
    }
    else
    {
        $error="Invalid Username or Password";
    }
}
?>

<!doctype html>

<html>

<head>

<meta charset="utf-8">

<title>SafeTrack Login</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body class="bg-light">

<div class="container">

<div class="row justify-content-center mt-5">

<div class="col-md-4">

<div class="card shadow">

<div class="card-header text-center">

<h3>SafeTrack HSE</h3>

</div>

<div class="card-body">

<?php if($error!=""){ ?>

<div class="alert alert-danger">

<?= $error ?>

</div>

<?php } ?>

<form method="post">

<input
class="form-control mb-3"
name="username"
placeholder="Username"
required>

<input
type="password"
class="form-control mb-3"
name="password"
placeholder="Password"
required>

<button
class="btn btn-primary w-100">

Login

</button>

</form>

</div>

</div>

</div>

</div>

</div>

</body>

</html>
