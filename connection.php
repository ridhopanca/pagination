<?php

$hostname = "localhost";
$database = "army";
$username = "root";
$password = "Rahasia01";

$connect = mysqli_connect($hostname,$username,$password,$database);

if(!$connect) die('Connection failed ,'.mysqli_connect_error().'');
