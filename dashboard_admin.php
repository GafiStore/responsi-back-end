<?php
session_start();
include "config.php"; 

// Check login status
if ($_SESSION['status'] != "login") {
    header("location:index.php?pesan=belum_login");
    exit(); 
}

// Logout logic
if (isset($_POST['logout'])) {
    session_destroy(); 
    header("Location: index.php"); 
    exit();
}

// Delete user logic
if (isset($_POST['delete'])) {
    $userIdToDelete = intval($_POST['user_id']);
    $deleteQuery = "DELETE FROM users WHERE id='$userIdToDelete'";
    mysqli_query($connect, $deleteQuery);
    header("Location: dashboard_root.php"); // Refresh to see changes
    exit();
}

// Add user logic
if (isset($_POST['add_user'])) {
    $fullname = mysqli_real_escape_string($connect, $_POST['fullname']);
    $username = mysqli_real_escape_string($connect, $_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password

    // Check if username already exists
    $checkQuery = "SELECT * FROM users WHERE username='$username'";
    $checkResult = mysqli_query($connect, $checkQuery);
    
    if (mysqli_num_rows($checkResult) > 0) {
        $_SESSION['error'] = "Data sudah ada!";
    } else {
        $insertQuery = "INSERT INTO users (fullname, username, password) VALUES ('$fullname', '$username', '$password')";
        
        if (mysqli_query($connect, $insertQuery)) {
            // Success
            header("Location: dashboard_admin.php"); // Refresh to see changes
            exit();
        } else {
            $_SESSION['error'] = "Error: " . mysqli_error($connect); // Show database error
        }
    }
}

// Fetch user data
$query = "SELECT fullname FROM users WHERE username='" . $_SESSION['username'] . "'";
$result = mysqli_query($connect, $query);
$user = mysqli_fetch_assoc($result);
$fullname = $user['fullname'] ?? '';
$firstName = strtok($fullname, " ");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Arial', sans-serif;
        }
        .dashboard-header {
            background-color: #007bff;
            color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .welcome-message {
            font-size: 1.5rem;
        }
        .table th {
            background-color: #e9ecef;
        }
        footer {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .btn-danger {
            transition: background-color 0.3s;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }
        .table-container {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .table-header {
            background-color: #e9ecef;
        }
        .table th {
            position: sticky;
            top: 0;
            z-index: 10;
        }
        .table th, .table td {
            text-align: center;
        }
        .table td {
            vertical-align: middle;
        }
    </style>
</head>
<body>

    <div class="container mt-5">
        <div class="dashboard-header text-center">
            <h2>Selamat Datang!</h2>
            <p class="welcome-message">Hallo Admin, kamu sudah berhasil login.</p>
            <form method="POST" class="mt-3">
                <button type="submit" name="logout" class="btn btn-danger">Logout</button>
            </form>
        </div>

        <div class="mt-4">
            <h4 class="mb-3">Tambah Pengguna</h4>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger" role="alert">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>
            <form method="POST" class="mb-4 row g-2 align-items-end">
                <div class="col">
                    <input type="text" name="fullname" placeholder="Nama Lengkap" class="form-control" required>
                </div>
                <div class="col">
                    <input type="text" name="username" placeholder="Username" class="form-control" required>
                </div>
                <div class="col">
                    <input type="password" name="password" placeholder="Password" class="form-control" required>
                </div>
                <div class="col-auto">
                    <button type="submit" name="add_user" class="btn btn-primary">Tambah Pengguna</button>
                </div>
            </form>

            <h4 class="mb-3">Data Pengguna</h4>
            <div class="table-container">
                <table class="table table-bordered">
                    <thead class="table-header">
                        <tr>
                            <th>USER ID</th>
                            <th>USERNAME</th>
                            <th>NAMA LENGKAP</th>
                            <th>PASSWORD</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        <?php
                        // Query to get all user data
                        $query = "SELECT * FROM users";
                        $result = mysqli_query($connect, $query);
                        
                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($user = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($user['id']) . "</td>";
                                echo "<td>" . htmlspecialchars($user['username']) . "</td>";
                                echo "<td>" . htmlspecialchars($user['fullname']) . "</td>";
                                echo "<td>********</td>"; // Masking password
                                echo "<td>
                                        <form method='POST' style='display:inline;'>
                                            <input type='hidden' name='user_id' value='" . htmlspecialchars($user['id']) . "'>
                                            <button type='submit' name='delete' class='btn btn-danger btn-sm' onclick='return confirm(\"Yakin ingin menghapus pengguna ini?\");'>Hapus</button>
                                        </form>
                                    </td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='5' class='text-center'>Tidak ada data</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <footer class="text-center mt-4">
            <p>&copy; <?= date('Y') ?> Dashboard 23.01.4969</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
