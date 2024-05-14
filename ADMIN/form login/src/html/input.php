<?php


$key = 'kuncisaya';

// Lakukan koneksi ke database (gunakan informasi koneksi Anda)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "kaipang";

$conn = new mysqli($servername, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}



// Cek apakah formulir dikirim (melalui metode POST)
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Ambil nilai dari formulir
  $lokasi = $_POST['lokasi'];
  $alamat = $_POST['alamat'];
  $luas = $_POST['luas'];
  $status = $_POST['status'];
  $latitude = $_POST['latitude'];
  $longitude = $_POST['longitude'];

  $query = "INSERT INTO peta (lokasi, alamat, luas, status, latitude, longitude) VALUES ('$lokasi', '$alamat', '$luas', '$status', '$latitude', '$longitude')";

  if ($conn->query($query) === TRUE) {
    echo "Data berhasil disimpan";
    header("Location: ./datapernikahan.php");
    exit();
  } else {
    echo "Error: " . $query . "<br>" . $conn->error;
  }

  $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin</title>
    <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
    <link rel="stylesheet" href="../assets/css/styles.min.css" />
</head>

<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full"
        data-sidebar-position="fixed" data-header-position="fixed">
        <!-- Sidebar Start -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="./index.php" class="text-nowrap logo-img">
                        <img src="../assets/images/logos/palu.jpg" width="100px"
                            style="margin-top: 20px; margin-left: 50px;" alt="">
                    </a>
                    <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
                        <i class="ti ti-x fs-8"></i>
                    </div>
                </div>
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
                    <ul id="sidebarnav">
                        <li class="nav-small-cap">
                            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                            <span class="hide-menu">Pemda Kota Palu</span>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./index.php" aria-expanded="false">
                                <span>
                                    <i class="ti ti-layout-dashboard"></i>
                                </span>
                                <span class="hide-menu">Dashboard</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./tampilan data.php" aria-expanded="false">
                                <span>
                                    <i class="ti ti-layout-grid"></i>
                                </span>
                                <span class="hide-menu">Data Sertifikasi</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="./input.php" aria-expanded="false">
                                <span>
                                    <i class="ti ti-file-description"></i>
                                </span>
                                <span class="hide-menu">Forms Sertifikasi</span>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title fw-semibold mb-4">Forms</h5>
                <div class="card">
                    <div class="card-body">
                        <form method="post" action="">

                            <div class="body-wrapper">

                                <div class="mb-3">
                                    <label for="lokasi" class="form-label">Lokasi</label>
                                    <input type="text" class="form-control" id="lokasi" name="lokasi" required>
                                </div>
                                <div class="mb-3">
                                    <label for="alamat" class="form-label">alamat</label>
                                    <input type="text" class="form-control" id="alamat" name="alamat" required>
                                </div>
                                <div class="mb-3">
                                    <label for="luas" class="form-label">Luas</label>
                                    <input type="text" class="form-control" id="luas" name="luas" required>
                                </div>

                                <label for="status" class="form-label">Status</label>
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button"
                                        id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
                                        <?php echo isset($_POST['status']) ? $_POST['status'] : 'status :'; ?>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
                                        <li><a class="dropdown-item"
                                                onclick="updateDropdown3('Tersertifikasi')">Tersertifikasi</a></li>
                                        <li><a class="dropdown-item"
                                                onclick="updateDropdown3('Tidak Tersertifikasi')">Tidak
                                                Tersertifikasi</a></li>
                                    </ul>
                                    <input type="hidden" name="status" id="status"
                                        value="<?php echo isset($_POST['status']) ? $_POST['status'] : ''; ?>" required>
                                </div>
                                <script>
                                function updateDropdown3(value) {
                                    document.querySelector("#dropdownMenuButton3").innerText = value;
                                    document.querySelector("#status").value = value;
                                }
                                </script>
                                <br>
                                <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    <script src="../assets/libs/jquery/dist/jquery.min.js"></script>
    <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/sidebarmenu.js"></script>
    <script src="../assets/js/app.min.js"></script>
    <script src="../assets/libs/simplebar/dist/simplebar.js"></script>
</body>

</html>