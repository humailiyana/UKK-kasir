<?php
session_start();
include '../config/koneksi.php';

$id_pelanggan = $_GET['id'];
$pelanggan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT NamaPelanggan FROM pelanggan WHERE PelangganID = '$id_pelanggan'"));

include '../templates/header.php';
include '../templates/sidebar_petugas.php';
?>

<div class="container-fluid">
    <div class="mb-4">
        <a href="pelanggan.php" class="btn btn-sm btn-outline-dark rounded-pill mb-3">
            <i class="fas fa-arrow-left me-1"></i> Kembali
        </a>
        <h2 class="fw-bold">Rincian Belanja: <span class="text-pink"><?php echo $pelanggan['NamaPelanggan']; ?></span></h2>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Tanggal</th>
                        <th>Nama Produk</th>
                        <th class="text-center">Jumlah</th>
                        <th class="text-end pe-4">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $query = mysqli_query($koneksi, "SELECT penjualan.TanggalPenjualan, produk.NamaProduk, 
                                                    detailpenjualan.JumlahProduk, detailpenjualan.Subtotal 
                                                    FROM detailpenjualan 
                                                    JOIN penjualan ON detailpenjualan.PenjualanID = penjualan.PenjualanID 
                                                    JOIN produk ON detailpenjualan.ProdukID = produk.ProdukID 
                                                    WHERE penjualan.PelangganID = '$id_pelanggan' 
                                                    ORDER BY penjualan.TanggalPenjualan DESC");
                    
                    while($row = mysqli_fetch_array($query)){
                    ?>
                    <tr>
                        <td class="ps-4"><?php echo date('d/m/Y', strtotime($row['TanggalPenjualan'])); ?></td>
                        <td class="fw-bold"><?php echo $row['NamaProduk']; ?></td>
                        <td class="text-center"><?php echo $row['JumlahProduk']; ?></td>
                        <td class="text-end pe-4 text-pink fw-bold">Rp <?php echo number_format($row['Subtotal'], 0, ',', '.'); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../templates/footer.php'; ?>