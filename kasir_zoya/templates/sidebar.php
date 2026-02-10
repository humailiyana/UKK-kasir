<nav id="sidebar">
    <div class="sidebar-header">
        <h3>ZOYA <span class="text-white">KASIR</span></h3>
    </div>

    <ul class="list-unstyled components mt-3">
        <li class="active">
            <a href="dashboard.php"><i class="fas fa-home me-2"></i> Dashboard</a>
        </li>
        <li>
            <a href="produk.php"><i class="fas fa-box me-2"></i> Pendataan Barang</a>
        </li>
        <li>
            <a href="stok.php"><i class="fas fa-inventory me-2"></i> Stok Barang</a>
        </li>
        <li>
            <a href="pembelian.php"><i class="fas fa-shopping-cart me-2"></i>Laporan Transaksi</a>
        </li>
        <?php if($_SESSION['role'] == 'admin'): ?>
        <li>
            <a href="registrasi.php"><i class="fas fa-user-plus me-2"></i> Registrasi Petugas</a>
        </li>
        <?php endif; ?>
        <li>
            <a href="javascript:void(0)" onclick="konfirmasiLogout()" class="nav-link text-white">
    <i class="fas fa-sign-out-alt me-2"></i> Logout
</a>
        </li>
    </ul>
</nav>

<div id="content">
    <nav class="navbar navbar-expand-lg navbar-light px-4">
        <div class="container-fluid">
            <span class="navbar-text fw-bold">
                Selamat Datang, <span class="text-pink"><?php echo $_SESSION['username']; ?></span> (<?php echo $_SESSION['role']; ?>)
            </span>
        </div>
    </nav>
    <div class="p-4">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function konfirmasiLogout() {
    Swal.fire({
        title: 'Mau Keluar?',
        text: "Kamu akan mengakhiri sesi ini.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ff69b4', 
        cancelButtonColor: '#212529',  
        confirmButtonText: 'Ya, Logout',
        cancelButtonText: 'Batal',
        customClass: {
            popup: 'rounded-4'
        }
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "../auth/logout.php";
        }
    })
}

<?php if(isset($_GET['pesan']) && $_GET['pesan'] == 'logout'): ?>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil Logout',
        text: 'Terima kasih, sampai jumpa lagi!',
        confirmButtonColor: '#ff69b4'
    });
<?php endif; ?>
</script>