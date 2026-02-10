<?php 
include '../config/koneksi.php';

if (isset($_POST['update'])) {
    $id = $_POST['ProdukID'];
    $nama_produk = $_POST['NamaProduk'];
    $harga = $_POST['Harga'];
    $stok = $_POST['Stok'];

    $query = mysqli_query($koneksi, "UPDATE produk SET NamaProduk='$nama_produk', Harga='$harga', Stok='$stok' WHERE ProdukID='$id'");

    if ($query) {
        header("location:produk.php?pesan=update");
    } else {
        echo "Gagal mengupdate data: " . mysqli_error($koneksi);
    }
}
?>