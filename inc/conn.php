<?php
$server = 'localhost';
$username = 'root';
$password = '';
$database = 'shopiapp';

$connection = mysqli_connect($server, $username, $password, $database);

if (!$connection) {
   die("error:".mysqli_connect_error());
}
