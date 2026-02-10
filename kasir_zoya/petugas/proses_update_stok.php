<?php 
session_start();
include '../config/koneksi.php';

$id = $_POST['id'];
$stok_baru = $_POST['stok_baru'];

$query = mysqli_query($koneksi, "UPDATE produk SET Stok='$stok_baru' WHERE ProdukID='$id'");

if($query){
    header("location:stok.php?pesan=berhasil");
} else {
    header("location:stok.php?pesan=gagal");
}
?>