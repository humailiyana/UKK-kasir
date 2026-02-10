<?php
session_start();
include '../config/koneksi.php';

if (isset($_SESSION['status']) && $_SESSION['status'] == "login") {
    if ($_SESSION['role'] == "admin/petugas") {
        header("location:../admin/dashboard.php");
    } else {
        header("location:../petugas/dashboard.php");
    }
    exit;
}

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($koneksi, $_POST['Username']);
    $password_input = $_POST['Password'];
    $password_md5 = md5($password_input);

    if ($username == "yaya" && $password_input == "1811") {
        $_SESSION['UserID'] = 01; 
        $_SESSION['username'] = "yaya";
        $_SESSION['role'] = "admin";
        $_SESSION['status'] = "login";
        header("location:../admin/dashboard.php");
        exit;
    }

    if ($username == "zoya" && $password_input == "0711") {
        $_SESSION['UserID'] = 02; 
        $_SESSION['username'] = "zoya";
        $_SESSION['role'] = "petugas";
        $_SESSION['status'] = "login";
        header("location:../admin/dashboard.php");
        exit;
    }

    $query = mysqli_query($koneksi, "SELECT * FROM user WHERE Username='$username' AND Password='$password_md5'");
    $cek = mysqli_num_rows($query);

    if ($cek > 0) {
        $data = mysqli_fetch_assoc($query);
        
        $_SESSION['UserID']   = $data['UserID'];
        $_SESSION['username'] = $data['Username'];
        $_SESSION['role']     = $data['Role']; 
        $_SESSION['status']   = "login";

        if ($data['Role'] == "admin") {
            header("location:../admin/dashboard.php");
        } else {
            header("location:../petugas/dashboard.php");
        }
        exit;
    } else {
        header("location:login.php?pesan=gagal");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Zoya Kasir</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body { background-color: #d483b2; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
        .login-card { width: 400px; border-radius: 20px; border: none; overflow: hidden; }
        .card-header { background-color: #212529; color: white; text-align: center; padding: 25px; border: none; }
        .text-pink { color: #ff69b4; }
        .btn-pink { background-color: #ff69b4; color: white; border-radius: 10px; font-weight: bold; }
        .btn-pink:hover { background-color: #ff4da6; color: white; }
        .form-control { border-radius: 10px; padding: 12px; background-color: #f1f3f5; border: none; }
    </style>
</head>
<body>

<div class="card login-card shadow-lg">
    <div class="card-header">
        <h4 class="fw-bold m-0"><span class="text-pink">TOKO BUNGA</span> ZOYA</h4>
    </div>
    <div class="card-body p-5">
        <h5 class="text-center fw-bold mb-4">Silahkan Login</h5>

        <form action="" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold">Username</label>
                <input type="text" name="Username" class="form-control" placeholder="Masukkan username" required autofocus>
            </div>
            <div class="mb-4">
                <label class="form-label small fw-bold">Password</label>
                <input type="password" name="Password" class="form-control" placeholder="Masukkan Password" required>
            </div>
            <button type="submit" name="login" class="btn btn-pink w-100 py-2 mb-3 shadow-sm">MASUK</button>
        </form>
    </div>
</div>

<script>
<?php if(isset($_GET['pesan'])): ?>
    <?php if($_GET['pesan'] == "gagal"): ?>
        Swal.fire({
            icon: 'error',
            title: 'Gagal Login',
            text: 'Username atau Password salah!',
            confirmButtonColor: '#ff69b4'
        });
    <?php elseif($_GET['pesan'] == "logout"): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil Logout',
            text: 'Sampai jumpa kembali!',
            confirmButtonColor: '#ff69b4'
        });
    <?php endif; ?>
<?php endif; ?>
</script>

</body>
</html>