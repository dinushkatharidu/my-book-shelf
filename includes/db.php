<?php
$conn = new mysqli("localhost:3307", "root", "", "book_shelf_db");

if($conn->connect_error){
    die("Connection Faild: " . $conn->connect_error);
}