<?php
session_start();
$username = $_POST['username'];
$password = $_POST['password'];
if ($username == "yaya" && $password == "1811") {
    $_SESSION['username'] = $username;
    $_SESSION['role'] = "admin";
    header("Location: ../admin/index.php"); 

} elseif ($username == "zoya" && $password == "0711") {
    $_SESSION['username'] = $username;
    $_SESSION['role'] = "petugas";
    header("Location: ../petugas/index.php"); 

} else {
    echo "<script>
            alert('Username atau Password Salah!');
            window.location.href='../login.php';
          </script>";
}
?>