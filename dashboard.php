<?php
session_start();
include "db/connection.php";

if(!isset($_SESSION['user'])){
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container mt-5">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Welcome, <?php echo $user['name']; ?> 👋</h2>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

    <!-- ROLE -->
    <p><strong>Role:</strong> <?php echo $user['role']; ?></p>

    <!-- STUDENT BUTTON -->
    <?php if($user['role'] == 'student'): ?>
        <a href="upload.php" class="btn btn-primary mb-3">Upload File</a>
    <?php endif; ?>

    <!-- SEARCH -->
    <input type="text" id="search" class="form-control mb-3" placeholder="Search file...">
    <div id="result"></div>

    <!-- FILE TABLE -->
    <h4>File List</h4>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>File Name</th>
                <th>Size (KB)</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

        <?php
        // admin nampak semua, student nampak file sendiri
        if($user['role'] == 'admin'){
            $stmt = $conn->query("SELECT * FROM files");
        } else {
            $stmt = $conn->prepare("SELECT * FROM files WHERE user_id=?");
            $stmt->execute([$user['id']]);
        }

        $no = 1;
        while($row = $stmt->fetch()){
        ?>
            <tr>
                <td><?php echo $no++; ?></td>
                <td><?php echo $row['original_name']; ?></td>
                <td><?php echo round($row['file_size']/1024,2); ?></td>
                <td>
                    <a href="<?php echo $row['file_path']; ?>" class="btn btn-success btn-sm" download>Download</a>

                    <?php if($user['role'] == 'student'): ?>
                        <a href="delete.php?id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php } ?>

        </tbody>
    </table>

</div>

<!-- AJAX SEARCH -->
<script>
document.getElementById("search").addEventListener("keyup", function(){
    let q = this.value;

    fetch("search.php?q=" + q)
    .then(res => res.text())
    .then(data => {
        document.getElementById("result").innerHTML = data;
    });
});
</script>

</body>
</html>