<?php
require("conn.php");
session_start();
if($_SESSION['email']){
$email = $_SESSION['email'];
mysqli_query($conn,"DELETE FROM term_setting WHERE user='$email'");
session_destroy();

header("Location:../index.php");
}else if($_SESSION['email']){
session_destroy();
header("Location:../index.php");
}else{
header("Location:../index.php");
}
mysqli_close($conn);
?>