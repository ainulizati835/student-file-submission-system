<?php
session_start();
include "db/connection.php";

if(isset($_POST['upload'])){
    $file = $_FILES['file'];
    $name = $file['name'];
    $tmp = $file['tmp_name'];
    $size = $file['size'];

    // VALIDATION
    if($size > 2000000){
        echo "File too large!";
        exit;
    }

    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    if($ext != "pdf"){
        echo "Only PDF allowed!";
        exit;
    }

    $newName = time() . "_" . $name;
    move_uploaded_file($tmp, "uploads/" . $newName);

    $stmt = $conn->prepare("INSERT INTO files (user_id,original_name,stored_name,file_path,file_size) VALUES (?,?,?,?,?)");
    $stmt->execute([
        $_SESSION['user']['id'],
        $name,
        $newName,
        "uploads/".$newName,
        $size
    ]);

    echo "Upload success!";
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="file" name="file" required>
    <button name="upload">Upload</button>
</form>