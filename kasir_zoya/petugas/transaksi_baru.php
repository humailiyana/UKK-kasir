<?php
session_start();
include '../config/koneksi.php';

if ($_SESSION['status'] != "login") {
    header("location:../auth/login.php");
    exit;
}

include '../templates/header.php';
include '../templates/sidebar.php';
?>

<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark m-0"><i class="fas fa-cart-plus text-pink me-2"></i> Transaksi <span class="text-pink">Baru</span></h2>
            <p class="text-muted">Input data penjualan barang hari ini.</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-4">
            <form action="proses_transaksi.php" method="post">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Pilih Pelanggan</label>
                        <select name="PelangganID" class="form-select rounded-pill" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            <?php
                            $pelanggan = mysqli_query($koneksi, "SELECT * FROM pelanggan");
                            while ($p = mysqli_fetch_array($pelanggan)) {
                                echo "<option value='".$p['PelangganID']."'>".$p['NamaPelanggan']."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Tanggal Transaksi</label>
                        <input type="text" class="form-control rounded-pill bg-light" value="<?php echo date('d-m-Y'); ?>" readonly>
                        <input type="hidden" name="TanggalPenjualan" value="<?php echo date('Y-m-d'); ?>">
                    </div>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Produk</label>
                        <select name="ProdukID" class="form-select rounded-pill" required>
                            <option value="">-- Pilih Produk --</option>
                            <?php
                            $produk = mysqli_query($koneksi, "SELECT * FROM produk WHERE Stok > 0");
                            while ($pr = mysqli_fetch_array($produk)) {
                                echo "<option value='".$pr['ProdukID']."'>".$pr['NamaProduk']." (Stok: ".$pr['Stok'].") - Rp ".number_format($pr['Harga'],0,',','.')."</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label fw-bold">Jumlah</label>
                        <input type="number" name="JumlahProduk" class="form-control rounded-pill" min="1" required>
                    </div>
                </div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-pink rounded-pill px-5 shadow-sm fw-bold text-white">
                        <i class="fas fa-save me-2"></i> Simpan Transaksi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>