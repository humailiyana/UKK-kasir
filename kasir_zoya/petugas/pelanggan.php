<?php
session_start();
include '../config/koneksi.php';

if ($_SESSION['status'] != "login" || $_SESSION['role'] != "petugas") {
    header("location:../auth/login.php");
    exit;
}

include '../templates/header.php';
include '../templates/sidebar_petugas.php';
?>

<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark m-0"><i class="fas fa-users text-pink me-2"></i> Riwayat <span class="text-pink">Pelanggan</span></h2>
            <p class="text-muted">Pantau aktivitas belanja pelanggan Zoya Kasir.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4 py-3">ID Pelanggan</th>
                            <th>Nama Pelanggan</th>
                            <th>Alamat</th>
                            <th>No. Telp</th>
                            <th class="text-center">Total Transaksi</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        
                        $query = mysqli_query($koneksi, "SELECT p.PelangganID, p.NamaPelanggan, p.Alamat, p.`No.Telp`, 
                                COUNT(j.PenjualanID) AS TotalTransaksi 
                                FROM pelanggan p 
                                LEFT JOIN penjualan j ON p.PelangganID = j.PelangganID 
                                GROUP BY p.PelangganID 
                                ORDER BY TotalTransaksi DESC");

                        while($d = mysqli_fetch_array($query)){
                        ?>
                        <tr>
                            <td class="ps-4">
                                <span class="badge bg-light text-dark border">#PLG-<?php echo $d['PelangganID']; ?></span>
                            </td>
                            <td class="fw-bold"><?php echo $d['NamaPelanggan']; ?></td>
                            <td><?php echo $d['Alamat']; ?></td>
                            <td><?php echo $d['No.Telp']; ?></td> <td class="text-center">
                                <span class="badge bg-pink rounded-pill px-3">
                                    <?php echo $d['TotalTransaksi']; ?> Kali Belanja
                                </span>
                            </td>
                            <td class="text-center pe-4">
                                <a href="riwayat_produk.php?id=<?php echo $d['PelangganID']; ?>" class="btn btn-sm btn-dark rounded-pill px-3 shadow-sm">
                                    <i class="fas fa-search-plus me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                        <?php } 
                         ?>
                        
                        <?php if(mysqli_num_rows($query) == 0): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Belum ada data pelanggan.</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php include '../templates/footer.php'; ?>