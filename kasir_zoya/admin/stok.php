<?php
session_start();
include '../config/koneksi.php';

if ($_SESSION['status'] != "login" || $_SESSION['role'] != "admin") {
    header("location:../auth/login.php");
    exit;
}

if (isset($_POST['update_stok'])) {
    $id = $_POST['ProdukID'];
    $stok_baru = $_POST['StokBaru'];

    $query = mysqli_query($koneksi, "UPDATE produk SET Stok='$stok_baru' WHERE ProdukID='$id'");
    if ($query) {
        header("location:stok.php?pesan=sukses");
    }
}

include '../templates/header.php';
include '../templates/sidebar.php';
?>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold text-dark m-0"><i class="fas fa-boxes text-pink me-2"></i> Stok <span class="text-pink">Bunga</span></h2>
            <p class="text-muted">Pantau dan perbarui ketersediaan barang jualan</p>
        </div>
        <div class="col-md-6 text-md-end pt-3">
             <div class="badge bg-dark p-2 px-3 shadow-sm" style="border-left: 3px solid #ff69b4;">
                <i class="fas fa-info-circle text-pink me-2"></i> Total Jenis Bunga: 
                <?php 
                $count = mysqli_fetch_array(mysqli_query($koneksi, "SELECT COUNT(*) as total FROM produk"));
                echo $count['total'];
                ?>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 15px;">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4 py-3">No</th>
                            <th>Nama Barang</th>
                            <th class="text-center">Stok Saat Ini</th>
                            <th>Status</th>
                            <th class="text-center pe-4">Aksi Cepat</th>
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
                            <td class="ps-4"><?php echo $no++; ?></td>
                            <td class="fw-bold"><?php echo $d['NamaProduk']; ?></td>
                            <td class="text-center">
                                <span class="fs-5 fw-bold <?php echo ($stok < 5) ? 'text-danger' : 'text-dark'; ?>">
                                    <?php echo $stok; ?>
                                </span>
                            </td>
                            <td>
                                <?php if($stok <= 0): ?>
                                    <span class="badge bg-danger">Habis</span>
                                <?php elseif($stok < 5): ?>
                                    <span class="badge bg-warning text-dark">Hampir Habis</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Tersedia</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center pe-4">
                                <button class="btn btn-sm btn-pink rounded-pill px-3" data-bs-toggle="modal" data-bs-target="#modalStok<?php echo $d['ProdukID']; ?>">
                                    <i class="fas fa-sync-alt me-1"></i> Update Stok
                                </button>
                            </td>
                        </tr>

                        <div class="modal fade" id="modalStok<?php echo $d['ProdukID']; ?>" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                                    <form action="" method="POST">
                                        <div class="modal-header border-0 bg-dark text-white" style="border-radius: 20px 20px 0 0;">
                                            <h5 class="modal-title fw-bold"><i class="fas fa-edit text-pink me-2"></i> Perbarui Stok</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body p-4 text-center">
                                            <input type="hidden" name="ProdukID" value="<?php echo $d['ProdukID']; ?>">
                                            <p class="mb-1 text-muted">Barang:</p>
                                            <h4 class="fw-bold mb-4"><?php echo $d['NamaProduk']; ?></h4>
                                            
                                            <div class="form-group mb-3">
                                                <label class="form-label fw-bold">Masukkan Jumlah Stok Baru</label>
                                                <input type="number" name="StokBaru" class="form-control form-control-lg text-center shadow-sm" value="<?php echo $d['Stok']; ?>" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer border-0 pb-4 justify-content-center">
                                            <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" name="update_stok" class="btn btn-pink px-4 shadow-sm fw-bold">SIMPAN PERUBAHAN</button>
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
<?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'sukses'): ?>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Stok barang telah diperbarui.',
        confirmButtonColor: '#ff69b4'
    });
<?php endif; ?>
</script>

<?php include '../templates/footer.php'; ?>