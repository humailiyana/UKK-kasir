<aside id="sidebar" class="bg-dark text-white shadow-lg" style="min-width: 250px; min-height: 100vh; transition: all 0.3s;">
    <div class="p-4">
        <div class="text-center mb-4">
            <h3 class="fw-bold text-white"><span class="text-pink">ZOYA</span> KASIR</h3>
            <hr class="border-secondary">
        </div>

        <div class="d-flex align-items-center mb-4 p-2 bg-secondary bg-opacity-25 rounded-3">
            <div class="bg-pink rounded-circle p-2 me-2 text-center" style="width: 35px; height: 35px;">
                <i class="fas fa-user-tie text-white small"></i>
            </div>
            <div class="overflow-hidden">
                <p class="m-0 small text-muted text-uppercase fw-bold" style="font-size: 10px;">Petugas</p>
                <p class="m-0 fw-bold text-truncate" style="font-size: 14px;"><?php echo $_SESSION['username']; ?></p>
            </div>
        </div>

        <ul class="nav flex-column gap-2">
            <li class="nav-item">
                <a href="../petugas/dashboard.php" class="nav-link text-white rounded-3 p-3 <?php echo (basename($_SERVER['PHP_SELF']) == 'dashboard.php') ? 'bg-pink shadow-sm active' : 'hover-effect'; ?>">
                    <i class="fas fa-home me-2"></i> Dashboard
                </a>
            </li>

            <li class="nav-item">
                <a href="../petugas/transaksi.php" class="nav-link text-white rounded-3 p-3 <?php echo (basename($_SERVER['PHP_SELF']) == 'transaksi.php') ? 'bg-pink shadow-sm active' : 'hover-effect'; ?>">
                    <i class="fas fa-shopping-cart me-2"></i> Transaksi Kasir
                </a>
            </li>

            <li class="nav-item">
                <a href="../petugas/stok.php" class="nav-link text-white rounded-3 p-3 <?php echo (basename($_SERVER['PHP_SELF']) == 'stok.php') ? 'bg-pink shadow-sm active' : 'hover-effect'; ?>">
                    <i class="fas fa-boxes me-2"></i> Cek Stok Barang
                </a>
            </li>

            <li class="nav-item">
                <a href="../petugas/pelanggan.php" class="nav-link text-white rounded-3 p-3 <?php echo (basename($_SERVER['PHP_SELF']) == 'pelanggan.php') ? 'bg-pink shadow-sm active' : 'hover-effect'; ?>">
                    <i class="fas fa-users me-2"></i> Data Pelanggan
                </a>
            </li>

            <hr class="border-secondary my-3">

            <li class="nav-item">
                <li class="nav-item">
    <a href="javascript:void(0)" onclick="konfirmasiLogoutPetugas()" class="nav-link text-white rounded-3 p-3 hover-danger">
        <i class="fas fa-sign-out-alt me-2 text-pink"></i> Logout
    </a>
</li>
                </a>
            </li>
        </ul>
    </div>
</aside>

<style>
    .text-pink { color: #ff69b4 !important; }
    .bg-pink { background-color: #ff69b4 !important; }
    
    .nav-link { transition: all 0.2s ease; font-weight: 500; }
    
    .hover-effect:hover {
        background-color: rgba(255, 255, 255, 0.1);
        transform: translateX(5px);
    }

    .hover-danger:hover {
        background-color: #dc3545 !important;
        transform: translateX(5px);
    }

    .nav-link.active {
        background-color: #ff69b4 !important;
    }

    #sidebar::-webkit-scrollbar { display: none; }
</style>

<script>
function konfirmasiLogout() {
    Swal.fire({
        title: 'Ingin Logout?',
        text: "Pastikan semua transaksi kasir sudah tersimpan.",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#ff69b4',
        cancelButtonColor: '#212529',
        confirmButtonText: 'Ya, Keluar',
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
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function konfirmasiLogoutPetugas() {
    Swal.fire({
        title: 'Akhiri Sesi Kasir?',
        text: "Pastikan semua transaksi pelanggan sudah selesai diproses.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ff69b4', 
        cancelButtonColor: '#212529',  
        confirmButtonText: 'Ya, Keluar',
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
</script>

<style>
    .hover-danger:hover {
        background-color: #dc3545 !important;
        color: white !important;
        transition: 0.3s;
    }
</style>