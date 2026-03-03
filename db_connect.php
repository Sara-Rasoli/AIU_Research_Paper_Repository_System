<?php
$host = "localhost";
$user = "root";       // change if needed
$pass = "";           // change if needed
$db   = "aiu_paper_repository_db"; // your database name

$connection = new mysqli($host, $user, $pass, $db);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>
