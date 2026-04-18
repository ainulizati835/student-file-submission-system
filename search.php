<?php
include "db/connection.php";

$keyword = $_GET['q'];

$stmt = $conn->prepare("SELECT * FROM files WHERE original_name LIKE ?");
$stmt->execute(["%$keyword%"]);

while($row = $stmt->fetch()){
    echo $row['original_name'] . "<br>";
}