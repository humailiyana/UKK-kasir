<?php
session_start();
include '../config/koneksi.php';

if ($_SESSION['status'] != "login" || $_SESSION['role'] != "admin") {
    header("location:../auth/login.php");
    exit;
}

include '../templates/header.php';
include '../templates/sidebar.php';
?>

<div class="container-fluid py-4">
    <div class="row mb-4 align-items-center d-print-none">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark m-0">Data <span class="text-pink">Pembelian</span></h2>
            <p class="text-muted mb-0">Riwayat transaksi penjualan toko bunga Zoya.</p>
        </div>
        <div class="col-md-6 text-md-end">
            <button onclick="window.print()" class="btn btn-dark shadow-sm px-4 rounded-pill">
                <i class="fas fa-print me-2 text-pink"></i> Cetak Laporan
            </button>
        </div>
    </div>

    <div class="d-none d-print-block text-center mb-4">
        <h2 class="fw-bold">LAPORAN DATA PEMBELIAN</h2>
        <h5 class="text-muted">Toko Bunga Zoya</h5>
        <hr>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 15px; overflow: hidden;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-dark text-center">
                        <tr>
                            <th class="py-3">No</th>
                            <th>ID Penjualan</th>
                            <th>Tanggal</th>
                            <th>Nama Pelanggan</th>
                            <th>Total Harga</th>
                            <th class="d-print-none">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        $query = mysqli_query($koneksi, "SELECT penjualan.*, pelanggan.NamaPelanggan 
                                                        FROM penjualan 
                                                        INNER JOIN pelanggan ON penjualan.PelangganID = pelanggan.PelangganID 
                                                        ORDER BY penjualan.PenjualanID DESC");
                        
                        while($d = mysqli_fetch_array($query)){
                        ?>
                        <tr>
                            <td class="text-center"><?php echo $no++; ?></td>
                            <td class="text-center">#<?php echo $d['PenjualanID']; ?></td>
                            <td class="text-center"><?php echo date('d-m-Y', strtotime($d['TanggalPenjualan'])); ?></td>
                            <td><?php echo $d['NamaPelanggan']; ?></td>
                            <td class="text-end fw-bold text-pink">
                                Rp <?php echo number_format($d['TotalHarga'], 0, ',', '.'); ?>
                            </td>
                            <td class="text-center d-print-none">
                                <a href="detail_pembelian.php?id=<?php echo $d['PenjualanID']; ?>" class="btn btn-sm btn-outline-dark rounded-pill px-3">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .text-pink { color: #ff69b4 !important; }
    
    @media print {
        #sidebar, .navbar, .d-print-none, .btn, .text-muted { 
            display: none !important; 
        }
        
        .container-fluid { 
            width: 100% !important; 
            margin: 0 !important; 
            padding: 0 !important; 
        }
        
        table { 
            width: 100% !important; 
            border: 1px solid #000 !important; 
            border-collapse: collapse !important;
        }
        
        th, td { 
            border: 1px solid #000 !important; 
            padding: 8px !important; 
            color: #000 !important;
        }

        .text-pink { color: #000 !important; } 
        @page {
            size: auto;
            margin: 10mm;
        }
    }
</style>

<?php include '../templates/footer.php'; ?>