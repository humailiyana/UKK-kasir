<?php
session_start();
include '../config/koneksi.php';

if ($_SESSION['status'] != "login" || $_SESSION['role'] != "admin") {
    header("location:../auth/login.php");
    exit;
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("location:pembelian.php");
    exit;
}

$id_penjualan = $_GET['id'];

$query_utama = mysqli_query($koneksi, "SELECT penjualan.*, pelanggan.NamaPelanggan 
                                      FROM penjualan 
                                      JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
                                      WHERE penjualan.PenjualanID = '$id_penjualan'");
$data = mysqli_fetch_assoc($query_utama);

include '../templates/header.php';
include '../templates/sidebar.php';
?>

<div class="container-fluid py-4">
    <div class="mb-4 d-flex align-items-center">
        <a href="pembelian.php" class="btn btn-sm btn-outline-dark rounded-pill me-3 shadow-sm">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
        <h2 class="fw-bold m-0 text-dark">Rincian Belanja: <span class="text-pink"><?php echo $data['NamaPelanggan']; ?></span></h2>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4 py-3">Tanggal</th>
                            <th>Nama Produk</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end pe-4">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $query_detail = mysqli_query($koneksi, "SELECT detailpenjualan.*, produk.NamaProduk 
                                                               FROM detailpenjualan 
                                                               JOIN produk ON detailpenjualan.ProdukID = produk.ProdukID 
                                                               WHERE detailpenjualan.PenjualanID = '$id_penjualan'");
                        
                        while($row = mysqli_fetch_array($query_detail)){
                        ?>
                        <tr>
                            <td class="ps-4 text-muted small">
                                <?php echo date('d-m-Y', strtotime($data['TanggalPenjualan'])); ?>
                            </td>
                            <td class="fw-bold text-dark"><?php echo $row['NamaProduk']; ?></td>
                            <td class="text-center"><?php echo $row['JumlahProduk']; ?></td>
                            <td class="text-end pe-4 text-pink fw-bold">
                                Rp <?php echo number_format($row['Subtotal'], 0, ',', '.'); ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot class="table-light border-top">
                        <tr>
                            <td colspan="3" class="text-end fw-bold py-3 text-dark">TOTAL HARGA :</td>
                            <td class="text-end pe-4 text-pink fw-bold h5 mb-0">
                                Rp <?php echo number_format($data['TotalHarga'], 0, ',', '.'); ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .text-pink { color: #ff69b4 !important; }
</style>

<?php include '../templates/footer.php'; ?>