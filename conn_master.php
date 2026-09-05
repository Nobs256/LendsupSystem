<?php
// Root conn_master.php
$master_host = "localhost";
$master_user = "root";
$master_pass = "";
$master_db   = "quick_master";

$master_conn = mysqli_connect($master_host, $master_user, $master_pass, $master_db);

if (!$master_conn) {
    die("Master Directory Connection Failed: " . mysqli_connect_error());
}
?>