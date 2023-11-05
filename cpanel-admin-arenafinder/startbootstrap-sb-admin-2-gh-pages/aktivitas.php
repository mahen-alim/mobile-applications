<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "arenafinderweb";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Tidak bisa terkoneksi");
}

$id = "";
$nama = "";
$jenis = "";
$lokasi = "";
$tanggal = "";
$anggota = "";
$jam = "";
$harga = "";
$sukses = "";
$error = "";
$limit = 10 * 1024 * 1024; // Batasan ukuran file (10MB)

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'delete') {
    $id = $_GET['id'];
    $sql1 = "DELETE FROM aktivitas WHERE id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    if ($q1) {
        $sukses = "Data Berhasil Dihapus";
    } else {
        $error = "Data Gagal Terhapus";
    }
}

if ($op == 'edit') {
    $id = $_GET['id'];
    $sql1 = "SELECT * FROM aktivitas WHERE id = '$id'";
    $q1 = mysqli_query($koneksi, $sql1);
    $r1 = mysqli_fetch_array($q1);
    $nama = $r1['nama_aktivitas'];
    $jenis = $r1['jenis_olga'];
    $lokasi = $r1['lokasi'];
    $tanggal = $r1['tanggal'];
    $anggota = $r1['keanggotaan'];
    $jam = $r1['jam'];
    $harga = $r1['harga'];


    if ($nama == '') {
        $error = "Data tidak ditemukan";
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") { //untuk create data
    $nama = $_POST['nama'];
    $jenis = $_POST['jenis_olga'];
    $lokasi = $_POST['lokasi'];
    $tanggal = $_POST['tanggal'];
    $anggota = $_POST['keanggotaan'];
    $jam = $_POST['jam_main'];
    $harga = $_POST['harga'];


    if (!empty($_FILES['foto']['name'])) {
        $nama_file = $_FILES['foto']['name'];
        $tmp = $_FILES['foto']['tmp_name'];

        // Tentukan folder tempat menyimpan gambar (ganti dengan folder Anda)
        $upload_folder = 'C:\\xampp\\htdocs\\ArenaFinder\\public\\img\\venue\\';

        // Pindahkan file gambar ke folder tujuan
        if (move_uploaded_file($tmp, $upload_folder . $nama_file)) {
            // Jika pengunggahan berhasil, lanjutkan dengan query SQL
            // Periksa apakah file gambar diunggah
            if ($op == 'edit') {
                // Perbarui data jika ini adalah operasi edit
                $sql1 = "UPDATE aktivitas SET nama_aktivitas = '$nama', jenis_olga = '$jenis', lokasi = '$lokasi', tanggal = '$tanggal', keanggotaan = '$anggota', jam = '$jam', harga = '$harga', gambar = '$nama_file' WHERE id = '$id'";
            } else {
                // Tambahkan data jika ini adalah operasi insert
                $sql1 = "INSERT INTO aktivitas (nama_aktivitas, jenis_olga, lokasi, tanggal, keanggotaan, jam, harga, gambar) VALUES ('$nama', '$jenis', '$lokasi', '$tanggal', '$anggota', '$jam', '$harga', '$nama_file')";

            }

            $q1 = mysqli_query($koneksi, $sql1);

            if ($q1) {
                $sukses = "Data aktivitas berhasil diupdate/ditambahkan";
            } else {
                $error = "Data aktivitas gagal diupdate/ditambahkan";
            }

        } else {
            $error = "Harap pilih gambar yang akan diunggah";
        }
    } else {
        $error = "Harap pilih gambar yang akan diunggah";
    }
}

if ($error) {
    // Set header sebelum mencetak pesan kesalahan
    header("refresh:2;url=aktivitas.php"); // 2 = detik
?>
<?php
}

if ($sukses) {
    // Set header sebelum mencetak pesan sukses
    header("refresh:2;url=aktivitas.php"); // 2 = detik
?>
<?php
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>ArenaFinder - Jadwal</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/924b40cfb7.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        #save-btn {
            background-color: #e7f5ff;
            color: #02406d;
            font-weight: bold;
        }

        #save-btn:hover {
            background-color: #02406d;
            color: white;
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #02406d;">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fa-solid fa-clipboard"></i>
                </div>
                <div class="sidebar-brand-text mx-3">ArenaFInder <sup>Admin</sup></div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="index.php">
                    <i class="fa-solid fa-house-user"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Nav Item - Web -->
            <li class="nav-item">
                <a class="nav-link" href="/ArenaFinder/html/beranda.html">
                    <i class="fa-brands fa-edge"></i>
                    <span>Lihat Website</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Pengelolaan Data
            </div>

            <!-- Nav Item - Jadwal Menu -->
            <li class="nav-item ">
                <a class="nav-link" href="jadwal.php">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>Jadwal Lapangan</span></a>
            </li>

            <!-- Nav Item - Aktivitas Menu -->
            <li class="nav-item active">
                <a class="nav-link" href="">
                    <i class="fa-solid fa-fire"></i>
                    <span>Aktivitas</span></a>
            </li>

            <!-- Nav Item - Keanggotaan -->
            <li class="nav-item ">
                <a class="nav-link" href="keanggotaan.php">
                    <i class="fa-solid fa-users"></i>
                    <span>Keanggotaan</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Notifikasi
            </div>

            <!-- Nav Item - Pesanan -->
            <li class="nav-item">
                <a class="nav-link" href="pesanan.php">
                    <i class="fa-solid fa-cart-shopping">
                        <span class="badge badge-danger badge-counter">New</span>
                    </i>
                    <span>Pesanan</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column" style="background-color: #e7f5ff;">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3"
                        style="color: #02406d;">
                        <i class="fa fa-bars"></i>
                    </button>

                    <div class="d-sm-flex align-items-center justify-content-between mb-3">
                        <i class="fa-solid fa-fire mt-3 mr-3" style="color: #02406d;"></i>
                        <h1 class="h3 mr-2 mt-4" style="color: #02406d; font-size: 20px; font-weight: bold;">Aktivitas
                        </h1>
                    </div>
                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">Douglas McGee</span>
                                <img class="img-profile rounded-circle" src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <div class="row">

                        <div class="col-xxl-8 col-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
                                    style="background-color: #02406d; color: white">
                                    <h6 class="m-0 font-weight-bold">Tambah/Edit Aktivitas</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive overflow-hidden">
                                        <?php if ($error || $sukses): ?>
                                            <div class="alert <?php echo $error ? 'alert-danger' : 'alert-success'; ?>"
                                                role="alert">
                                                <?php echo $error ? $error : $sukses; ?>
                                            </div>
                                        <?php endif; ?>
                                        <form action="" method="POST" enctype="multipart/form-data" autocomplete="off">
                                            <div class="mb-3 row">
                                                <label for="nama" class="col-sm-2 col-form-label">Nama Aktivitas</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="staticEmail" name="nama"
                                                        value="<?php echo $nama ?>" required>
                                                </div>
                                            </div>
                                            <div class="mb-3 row">
                                                <label for="jenis_olga" class="col-sm-2 col-form-label">Jenis
                                                    Olahraga</label>
                                                <div class="col-sm-10">
                                                    <select class="form-control" name="jenis_olga" id="jenis_olga"
                                                        required>
                                                        <option value="">-Pilih Jenis Olahraga-</option>
                                                        <option value="Badminton" <?php if ($jenis == "Badminton")
                                                            echo "selected" ?>>Badminton
                                                            </option>
                                                            <option value="Futsal" <?php if ($jenis == "Futsal")
                                                            echo "selected" ?>>Futsal
                                                            </option>
                                                            <option value="Sepak Bola" <?php if ($jenis == "Sepak Bola")
                                                            echo "selected" ?>>Sepak Bola
                                                            </option>
                                                            <option value="Bola Voli" <?php if ($jenis == "Bola Voli")
                                                            echo "selected" ?>>Bola Voli
                                                            </option>
                                                            <option value="Bola Basket" <?php if ($jenis == "Bola Basket")
                                                            echo "selected" ?>>Bola Basket
                                                            </option>
                                                            <option value="Tenis Lapangan" <?php if ($jenis == "Tenis Lapangan")
                                                            echo "selected" ?>>Tenis Lapangan
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="mb-3 row">
                                                    <label for="lokasi" class="col-sm-2 col-form-label">Lokasi</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="staticEmail"
                                                            name="lokasi" value="<?php echo $lokasi ?>" required>
                                                </div>
                                            </div>

                                            <div class="mb-3 row">
                                                <label for="alamat" class="col-sm-2 col-form-label">Tanggal Main</label>
                                                <div class="col-sm-10" onclick="">
                                                    <input type="datetime-local" placeholder="-Pilih Tanggal-"
                                                        class="form-control" id="staticEmail" name="tanggal"
                                                        value="<?php echo $tanggal ?>" required>
                                                </div>
                                            </div>

                                            <div class="mb-3 row">
                                                <label for="keanggotaan"
                                                    class="col-sm-2 col-form-label">Keanggotaan</label>
                                                <div class="col-sm-10">
                                                    <input type="radio" id="member" name="keanggotaan" value="Member"
                                                        <?php if ($anggota == "Member")
                                                            echo "checked"; ?> required>
                                                    <label for="member">Member</label>

                                                    <input type="radio" id="nonmember" name="keanggotaan"
                                                        value="Non Member" style="margin-left: 20px;" <?php if ($anggota == "Non Member")
                                                            echo "checked"; ?> required>
                                                    <label for="nonmember">Non Member</label>
                                                </div>
                                            </div>

                                            <div class="mb-3 row">
                                                <label for="jam main" class="col-sm-2 col-form-label"
                                                    style="cursor: pointer">Jam Main</label>
                                                <div class="col-sm-10">
                                                    <select class="form-control" name="jam_main" id="jam_main" required>
                                                        <option value="">-Jam Main-</option>
                                                        <option value="1 jam" <?php if ($jam == "1 jam")
                                                            echo "selected" ?>>1 jam
                                                            </option>
                                                            <option value="2 jam" <?php if ($jam == "2 jam")
                                                            echo "selected" ?>>2 jam
                                                            </option>
                                                            <option value="3 jam" <?php if ($jam == "3 jam")
                                                            echo "selected" ?>>3 jam
                                                            </option>
                                                            <option value="4 jam" <?php if ($jam == "4 jam")
                                                            echo "selected" ?>>4 jam
                                                            </option>
                                                            <option value="5 jam" <?php if ($jam == "5 jam")
                                                            echo "selected" ?>>5 jam
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="mb-3 row">
                                                    <label for="harga" class="col-sm-2 col-form-label">Harga</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="harga" name="harga"
                                                            readonly value="<?php echo $harga ?>">
                                                </div>
                                            </div>

                                            <div class="mb-3 row">
                                                <label for="gambar" class="col-sm-2 col-form-label">Gambar</label>
                                                <div class="col-sm-10">
                                                    <input class="col-xxl-8 col-12" type="file" id="foto" name="foto"
                                                        required="required" multiple />
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-xxl-8 col-12">
                                                    <input type="submit" name="simpan" value="Simpan Data"
                                                        class="btn w-100 mt-5" id="save-btn">
                                                </div>
                                            </div>

                                        </form>
                                    </div>
                                </div>
                            </div>

                            <script>
                                // Mendapatkan elemen-elemen yang diperlukan
                                var jamMainSelect = document.getElementById("jam_main");
                                var hargaInput = document.getElementById("harga");

                                // Tambahkan event listener untuk memantau perubahan pada pilihan jam_main
                                jamMainSelect.addEventListener("change", function () {
                                    // Mendapatkan nilai yang dipilih oleh pengguna
                                    var selectedValue = jamMainSelect.value;

                                    // Tentukan harga berdasarkan nilai yang dipilih
                                    var harga = 0;
                                    if (selectedValue === "1 jam") {
                                        harga = 15000;
                                    } else if (selectedValue === "2 jam") {
                                        harga = 30000;
                                    } else if (selectedValue === "3 jam") {
                                        harga = 45000;
                                    } else if (selectedValue === "4 jam") {
                                        harga = 50000;
                                    } else if (selectedValue === "5 jam") {
                                        harga = 65000;
                                    } else {
                                        <?php echo $error ?>
                                    }

                                    // Masukkan harga ke dalam input harga
                                    hargaInput.value = harga;
                                });

                            </script>

                            <!-- DataTales Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
                                    style="color: white; background-color: #02406d;">
                                    <h6 class="m-0 font-weight-bold">Tabel Aktivitas</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <form action="aktivitas.php" method="GET">
                                            <div class="form-group" style="display: flex; gap: 10px;">
                                                <input type="text" name="search" class="form-control" id="searchInput"
                                                    style="width: 30%;" placeholder="Cari Aktivitas"
                                                    value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
                                                <button type="submit" class="btn btn-info"
                                                    id="searchButton">Cari</button>
                                                <?php if (isset($_GET['search'])): ?>
                                                    <a href="aktivitas.php" class="btn btn-secondary">Hapus Pencarian</a>
                                                <?php endif; ?>
                                            </div>
                                        </form>

                                        <script>
                                            document.getElementById('searchButton').addEventListener('click', function (event) {
                                                var searchInput = document.getElementById('searchInput');

                                                if (searchInput.value === '') {
                                                    event.preventDefault(); // Mencegah pengiriman form jika field pencarian kosong
                                                    searchInput.placeholder = 'Kolom pencarian tidak boleh kosong!';
                                                    searchInput.style.borderColor = 'red'; // Mengubah warna border field

                                                } else {
                                                    searchInput.style.borderColor = '';
                                                }
                                            });

                                            document.getElementById('searchInput').addEventListener('click', function () {
                                                var searchInput = document.getElementById('searchInput');
                                                searchInput.placeholder = 'Cari Aktivitas'; // Mengembalikan placeholder ke default saat input diklik
                                                searchInput.style.borderColor = ''; // Mengembalikan warna border ke default saat input diklik
                                            });
                                        </script>

                                        <table class="table text-nowrap table-centered table-hover" id="dataTable"
                                            width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th scope="col">No.</th>
                                                    <th scope="col">Nama Aktivitas</th>
                                                    <th scope="col">Jenis Olahraga</th>
                                                    <th scope="col">Lokasi</th>
                                                    <th scope="col">Tanggal Main</th>
                                                    <th scope="col">Keanggotaan</th>
                                                    <th scope="col">Jam Main</th>
                                                    <th scope="col">Harga</th>
                                                    <th scope="col">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (isset($_GET['reset'])) {
                                                    // Pengguna menekan tombol "Hapus Pencarian"
                                                    header("Location: aktivitas.php"); // Mengarahkan ke halaman tanpa parameter pencarian
                                                    exit();
                                                }

                                                if (isset($_GET['search'])) {
                                                    $searchTerm = $koneksi->real_escape_string($_GET['search']);
                                                    $sql = "SELECT * FROM aktivitas WHERE nama_aktivitas LIKE '%$searchTerm%'";
                                                } else {
                                                    $sql = "SELECT * FROM aktivitas ORDER BY id DESC";
                                                }
                                                $q2 = mysqli_query($koneksi, $sql);
                                                $urut = 1;
                                                while ($r2 = mysqli_fetch_array($q2)) {
                                                    $id = $r2['id'];
                                                    $nama = $r2['nama_aktivitas'];
                                                    $jenis = $r2['jenis_olga'];
                                                    $lokasi = $r2['lokasi'];
                                                    $tanggal = $r2['tanggal'];
                                                    $anggota = $r2['keanggotaan'];
                                                    $jam = $r2['jam'];
                                                    $harga = $r2['harga'];
                                                    ?>
                                                    <tr>
                                                        <th scope="row">
                                                            <?php echo $urut++ ?>
                                                        </th>
                                                        <td scope="row">
                                                            <?php echo $nama ?>
                                                        </td>
                                                        <td scope="row">
                                                            <?php echo $jenis ?>
                                                        </td>
                                                        <td scope="row">
                                                            <?php echo $lokasi ?>
                                                        </td>
                                                        <td scope="row">
                                                            <?php echo $tanggal ?>
                                                        </td>
                                                        <td scope="row">
                                                            <?php echo $anggota ?>
                                                        </td>
                                                        <td scope="row">
                                                            <?php echo $jam ?>
                                                        </td>
                                                        <td scope="row">
                                                            <?php echo $harga ?>
                                                        </td>
                                                        <td scope="row">
                                                            <a href="aktivitas.php?op=edit&id=<?php echo $id ?>"><button
                                                                    type="button" class="btn btn-warning">Edit</button></a>
                                                            <a href="aktivitas.php?op=delete&id=<?php echo $id ?>"
                                                                onclick="return confirm('Yakin mau menghapus data ini?')"><button
                                                                    type="button" class="btn btn-danger">Delete</button></a>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>



                        </div>




                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.php">Logout</a>
                </div>
            </div>
        </div>
    </div>



    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
    <!-- flatpickr -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        flatpickr("input[type=datetime-local]", {});
    </script>

    <script>
        config = {
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",

        }
        flatpickr("input[type=time]", config);
    </script>

</body>

</html>