<?php
session_start();
include '../config/koneksi.php';

if ($_SESSION['status'] != "login" || $_SESSION['role'] != "petugas") {
    header("location:../auth/login.php");
    exit;
}

$data_produk = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM produk"));

$hari_ini = date('Y-m-d');
$transaksi_hari_ini = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM penjualan WHERE TanggalPenjualan = '$hari_ini'"));

$stok_menipis = mysqli_fetch_assoc(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM produk WHERE Stok < 5"));

$query_petugas = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM user WHERE Role = 'petugas'");
$data_petugas = mysqli_fetch_assoc($query_petugas);

include '../templates/header.php';
include '../templates/sidebar_petugas.php'; 
?>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark m-0">Dashboard <span class="text-pink">Petugas</span></h2>
            <p class="text-muted">Selamat datang kembali, <span class="fw-bold text-pink"><?php echo $_SESSION['username']; ?></span>!</p>
        </div>
        <div class="col-md-6 text-md-end pt-2">
            <div class="badge bg-dark p-2 px-3 shadow-sm" style="border-bottom: 2px solid #ff69b4;">
                <i class="fas fa-clock text-pink me-2"></i>
                <span><?php echo date('d F Y'); ?></span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-left: 5px solid #ff69b4; border-radius: 15px;">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-light rounded-circle p-3 me-3">
                        <i class="fas fa-shopping-cart fa-2x text-pink"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small fw-bold mb-1">TRANSAKSI HARI INI</h6>
                        <h3 class="fw-bold mb-0"><?php echo $transaksi_hari_ini['total']; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-left: 5px solid #dc3545; border-radius: 15px;">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-light rounded-circle p-3 me-3">
                        <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small fw-bold mb-1">STOK MENIPIS</h6>
                        <h3 class="fw-bold mb-0 text-danger"><?php echo $stok_menipis['total']; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-left: 5px solid #212529; border-radius: 15px;">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-light rounded-circle p-3 me-3">
                        <i class="fas fa-box fa-2x text-dark"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small fw-bold mb-1">TOTAL PRODUK</h6>
                        <h3 class="fw-bold mb-0"><?php echo $data_produk['total']; ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100" style="border-left: 5px solid #6c757d; border-radius: 15px;">
                <div class="card-body d-flex align-items-center">
                    <div class="icon-box bg-light rounded-circle p-3 me-3">
                        <i class="fas fa-users fa-2x text-secondary"></i>
                    </div>
                    <div>
                        <h6 class="text-muted small fw-bold mb-1">PETUGAS</h6>
                        <h3 class="fw-bold mb-0"><?php echo $data_petugas['total']; ?></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm p-4" style="border-radius: 15px; background: linear-gradient(to right, #ffffff, #fff0f5);">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="fw-bold text-dark">Siap Melayani Pelanggan?</h4>
                        <p class="text-muted">Klik tombol di samping untuk mulai mencatat transaksi baru dengan cepat dan mudah.</p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="transaksi.php" class="btn btn-pink btn-lg px-5 shadow rounded-pill">
                            <i class="fas fa-plus-circle me-2"></i> Transaksi Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../templates/footer.php'; ?>