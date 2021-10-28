<?php

session_start();

$con = mysqli_connect('localhost','root','');

mysqli_select_db($con, 'userregistration');

$id = $_POST['id'];
$pass = $_POST['password'];

$s = "select * from usertable where id = '$id' && password = '$pass'";

$result = mysqli_query($con, $s);

$num = mysqli_num_rows($result);

if($num == 1)
{   
	$_SESSION['id'] = $id;
	header('location: home.php');
}
else
{
	header('location: login.php');
}

?>