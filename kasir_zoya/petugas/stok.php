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
            <h2 class="fw-bold text-dark m-0"><i class="fas fa-boxes text-pink me-2"></i> Cek <span class="text-pink">Stok Bunga</span></h2>
            <p class="text-muted">Pantau dan perbarui ketersediaan barang jualan</p>
        </div>
        <div class="col-md-6">
            <div class="input-group shadow-sm" style="border-radius: 10px; overflow: hidden;">
                <span class="input-group-text bg-white border-0"><i class="fas fa-search text-muted"></i></span>
                <input type="text" id="inputCari" class="form-control border-0" placeholder="Cari nama barang..." onkeyup="cariBarang()">
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-dark text-white p-2" style="border-radius: 15px;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="m-0 small">Total Jenis Bunga</p>
                        <h4 class="fw-bold m-0 text-pink"><?php echo mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM produk")); ?></h4>
                    </div>
                    <i class="fas fa-layer-group fa-2x opacity-50"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-2" style="border-radius: 15px; border-left: 5px solid #ffc107;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="m-0 small text-muted">Stok hampir habis</p>
                        <h4 class="fw-bold m-0 text-warning"><?php echo mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM produk WHERE Stok > 0 AND Stok < 10")); ?></h4>
                    </div>
                    <i class="fas fa-exclamation-triangle fa-2x opacity-25"></i>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 shadow-sm bg-white p-2" style="border-radius: 15px; border-left: 5px solid #dc3545;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <p class="m-0 small text-muted">Stok Kosong</p>
                        <h4 class="fw-bold m-0 text-danger"><?php echo mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM produk WHERE Stok <= 0")); ?></h4>
                    </div>
                    <i class="fas fa-times-circle fa-2x opacity-25"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tabelStok">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4 py-3" width="5%">No</th>
                            <th width="35%">Nama Produk</th>
                            <th width="20%">Harga Jual</th>
                            <th class="text-center" width="10%">Sisa Stok</th>
                            <th class="text-center" width="15%">Status</th>
                            <th class="text-center pe-4" width="15%">Aksi Cepat</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        $query = mysqli_query($koneksi, "SELECT * FROM produk ORDER BY Stok ASC");
                        while($d = mysqli_fetch_array($query)){
                            $stok = $d['Stok'];
                        ?>
                        <tr>
                            <td class="ps-4 text-muted"><?php echo $no++; ?></td>
                            <td>
                                <span class="fw-bold text-dark d-block"><?php echo $d['NamaProduk']; ?></span>
                                <small class="text-muted">ID: #PROD-<?php echo $d['ProdukID']; ?></small>
                            </td>
                            <td class="fw-bold">Rp <?php echo number_format($d['Harga'], 0, ',', '.'); ?></td>
                            <td class="text-center">
                                <span class="fs-5 fw-bold <?php echo ($stok < 10) ? 'text-danger' : 'text-dark'; ?>"><?php echo $stok; ?></span>
                            </td>
                            <td class="text-center">
                                <?php if($stok <= 0): ?>
                                    <span class="badge bg-danger rounded-pill px-3">Habis</span>
                                <?php elseif($stok < 10): ?>
                                    <span class="badge bg-warning text-dark rounded-pill px-3">Menipis</span>
                                <?php else: ?>
                                    <span class="badge bg-success rounded-pill px-3">Aman</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center pe-4">
                                <button type="button" class="btn btn-sm text-white" 
                                        style="background-color: #ff6b9d; border-radius: 20px; padding: 5px 15px;"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modalUpdate<?php echo $d['ProdukID']; ?>">
                                    <i class="fas fa-sync-alt me-1"></i> Update Stok
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalUpdate<?php echo $d['ProdukID']; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content" style="border-radius: 15px; border: none;">
                                    <div class="modal-header bg-dark text-white" style="border-radius: 15px 15px 0 0;">
                                        <h5 class="modal-title"><i class="fas fa-edit me-2 text-pink"></i> Update Stok</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form action="proses_update_stok.php" method="POST">
                                        <div class="modal-body p-4">
                                            <input type="hidden" name="id" value="<?php echo $d['ProdukID']; ?>">
                                            <div class="mb-3">
                                                <label class="form-label fw-bold">Nama Bunga</label>
                                                <input type="text" class="form-control bg-light" value="<?php echo $d['NamaProduk']; ?>" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold" style="color: #ff6b9d;">Masukkan Jumlah Stok Terbaru</label>
                                                <input type="number" name="stok_baru" class="form-control border-pink" placeholder="Contoh: 50" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0">
                                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn text-white rounded-pill px-4" style="background-color: #ff6b9d;">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function cariBarang() {
    let input = document.getElementById("inputCari").value.toUpperCase();
    let table = document.getElementById("tabelStok");
    let tr = table.getElementsByTagName("tr");
    for (let i = 1; i < tr.length; i++) {
        let td = tr[i].getElementsByTagName("td")[1]; 
        if (td) {
            let txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(input) > -1) { tr[i].style.display = ""; } 
            else { tr[i].style.display = "none"; }
        }
    }
}
</script>

<style>
    .text-pink { color: #ff6b9d !important; }
    .border-pink:focus { border-color: #ff6b9d; box-shadow: 0 0 0 0.25rem rgba(255, 107, 157, 0.25); }
    #inputCari:focus { box-shadow: none; background-color: #fff0f5; }
</style>

<?php include '../templates/footer.php'; ?>