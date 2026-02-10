<?php
session_start();
include '../config/koneksi.php';

if ($_SESSION['status'] != "login" || $_SESSION['role'] != "admin") {
    header("location:../auth/login.php");
    exit;
}

$data_produk = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM produk"));

$data_petugas = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM user WHERE Role = 'petugas'"));

$data_pendapatan = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT SUM(TotalHarga) as total FROM penjualan"));
$data_stok_rendah = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM produk WHERE Stok < 5"));

$transaksi_terbaru = mysqli_query($koneksi, "SELECT penjualan.*, pelanggan.NamaPelanggan 
                                            FROM penjualan 
                                            LEFT JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
                                            ORDER BY TanggalPenjualan DESC LIMIT 5");

include '../templates/header.php';
include '../templates/sidebar.php';
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark m-0">Toko Bunga<span class="text-pink"> Zoya</span></h2>
            <p class="text-muted">Heiho, <span class="fw-bold text-pink"><?php echo $_SESSION['username']; ?></span>. Selamat bekerja!</p>
        </div>
        <div class="col-md-6 text-md-end pt-2">
            <div class="badge bg-dark p-2 px-3 shadow-sm" style="border-bottom: 2px solid #ff69b4;">
                <i class="fas fa-calendar-alt text-pink me-2"></i>
                <span id="server-date"><?php echo date('d F Y | H:i'); ?> WIB</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm" style="border-top: 4px solid #198754;">
                <div class="card-body">
                    <h6 class="text-muted small fw-bold">TOTAL PENDAPATAN</h6>
                    <h4 class="fw-bold mb-0">Rp <?php echo number_format($data_pendapatan['total'] ?? 0, 0, ',', '.'); ?></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm" style="border-top: 4px solid #ff69b4;">
                <div class="card-body">
                    <h6 class="text-muted small fw-bold">STOK MENIPIS</h6>
                    <h4 class="fw-bold mb-0 text-danger"><?php echo $data_stok_rendah['total']; ?> <small class="text-muted" style="font-size: 12px;">Produk</small></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm" style="border-top: 4px solid #212529;">
                <div class="card-body">
                    <h6 class="text-muted small fw-bold">TOTAL PRODUK</h6>
                    <h4 class="fw-bold mb-0"><?php echo $data_produk['total']; ?></h4>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm" style="border-top: 4px solid #ff69b4;">
                <div class="card-body">
                    <h6 class="text-muted small fw-bold">PETUGAS</h6>
                    <h4 class="fw-bold mb-0"><?php echo $data_petugas['total']; ?></h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold m-0"><i class="fas fa-history text-pink me-2"></i> Transaksi Terbaru</h5>
                    <a href="pembelian.php" class="btn btn-sm btn-pink">Lihat Semua</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mt-2">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>ID Penjualan</th>
                                <th>Tanggal</th>
                                <th>Pelanggan</th>
                                <th>Total Harga</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $no = 1;
                            while($d = mysqli_fetch_array($transaksi_terbaru)){ 
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>#<?php echo $d['PenjualanID']; ?></td>
                                <td><?php echo date('d-m-Y', strtotime($d['TanggalPenjualan'])); ?></td>
                                <td><?php echo $d['NamaPelanggan']; ?></td>
                                <td class="fw-bold">Rp <?php echo number_format($d['TotalHarga'], 0, ',', '.'); ?></td>
                                <td class="text-center">
                                    <a href="detail_penjualan.php?id=<?php echo $d['PenjualanID']; ?>" class="btn btn-sm btn-outline-dark">Detail</a>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php if(mysqli_num_rows($transaksi_terbaru) == 0): ?>
                                <tr><td colspan="6" class="text-center text-muted">Belum ada transaksi hari ini.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
include '../templates/footer.php';
?>