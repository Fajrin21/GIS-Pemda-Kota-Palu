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

  // Query SQL untuk menyimpan data ke database
  $query = "INSERT INTO peta (lokasi, alamat, luas, status) VALUES ('$lokasi', '$alamat', '$luas', '$status')";

  


  // Eksekusi query
  if ($conn->query($query) === TRUE) {
    echo "Data berhasil disimpan";
    header("Location: ./datapernikahan.php");
    exit();
  } else {
    echo "Error: " . $query . "<br>" . $conn->error;
  }

  // Tutup koneksi
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
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
  <aside class="left-sidebar">
        <!-- Sidebar scroll-->
        <div>
          <div class="brand-logo d-flex align-items-center justify-content-between">
           
            <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
              <i class="ti ti-x fs-8"></i>
            </div>
          </div>
          <!-- Sidebar navigation-->
          <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
            <ul id="sidebarnav">
              <li class="nav-small-cap">
                <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
                <span class="hide-menu">Dashboard</span>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link" href="./index.php" aria-expanded="false">
                  <span>
                    <i class="ti ti-layout-dashboard"></i>
                  </span>
                  <span class="hide-menu">Data Sertifikasi</span>
                </a>
              </li>
              <li class="sidebar-item">
                <a class="sidebar-link" href="./datapernikahan.php" aria-expanded="false">
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

                <label for="lokasi" class="form-label">Lokasi</label>
                <div class="dropdown">
                  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton5" data-bs-toggle="dropdown" aria-expanded="true">
                    <?php echo isset($_POST['lokasi']) ? $_POST['lokasi'] : 'lokasi :'; ?>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton5">
                    <li><a class="dropdown-item" onclick="updateDropdown5('kantor lurah watusampu')">kantor lurah watusampu</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('SD inpres watusampu')">SD inpres watusampu</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('SD negeri inpres buluri')">SD negeri inpres buluri</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('kantor kelurahan buluri')">kantor kelurahan buluri</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('Kawasan RS Anutapura')">Kawasan RS Anutapura</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('dinas perumahan dan kawasan permukiman kota palu')">dinas perumahan dan kawasan permukiman kota palu</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('kawasan TK negeri pembina BAW jannah ulujadi')">kawasan TK negeri pembina BAW jannah ulujadi</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('kawasan tanpa nama')">kawasan tanpa nama</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('daerah jalan i gusti ngurah rai')">daerah jalan i gusti ngurah rai</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('persimpangan i gusti')">persimpangan i gusti</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('daerah kawasan jalan penggaraman')">daerah kawasan jalan penggaraman</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('kantor kelurahan palupi')">kantor kelurahan palupi</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('DPPPKB palu')">DPPPKB palu</a></li>

                    <li><a class="dropdown-item" onclick="updateDropdown5('perumahan daerah jalan bulili')">perumahan daerah jalan bulili</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('perumahan dekat pasar lasoani')">perumahan dekat pasar lasoani</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('daerah perumahan jalan tanjung manimbaya')">daerah perumahan jalan tanjung manimbaya</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('daerah di jalan tanjung manimbaya')">daerah di jalan tanjung manimbaya</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('Rumah potong hewan pemerintan jln ranjidondo')">Rumah potong hewan pemerintan jln ranjidondo</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('taman bundaran')">taman bundaran</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('taman patung kuda bumi bahari')">taman patung kuda bumi bahari</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('daerah dekat lampu merah gajah mada')">daerah dekat lampu merah gajah mada</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('wilayah pasar talise')">wilayah pasar talise</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('pasar tavanjuka')">pasar tavanjuka</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('pasar vinase tavaili')">pasar vinase tavaili</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('pasar silae')">pasar silae</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('TK negeri pembina posimpotove')">TK negeri pembina posimpotove</a></li>
                    
                    <li><a class="dropdown-item" onclick="updateDropdown5('lapangan ps. dharma putra')">lapangan ps. dharma putra</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('single tree beach kayumalue')">single tree beach kayumalue</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('pasar mamboro')">pasar mamboro</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('SPDN taipa')">SPDN taipa</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('daerah jalan bulu masomba')">daerah jalan bulu masomba</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('TK mantikulore lasoani')">TK mantikulore lasoani</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('daerah dekat SD inpres 4 birobuli')">daerah dekat SD inpres 4 birobuli</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('daerah dekat perumahan BMKG palu')">daerah dekat perumahan BMKG palu</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('IPA PDAM KOTA PALU')">IPA PDAM KOTA PALU</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('SD negeri inpres 1 tondo')">SD negeri inpres 1 tondo</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('sungai daerah talise')">sungai daerah talise</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('daerah sawah kawatuna')">daerah sawah kawatuna</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('kantor kelurahan boyaoge')">kantor kelurahan boyaoge</a></li>

                    <li><a class="dropdown-item" onclick="updateDropdown5('BKBPLD')">BKBPLD</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('daerah kawasan dijalan tanggul selatan')">daerah kawasan dijalan tanggul selatan</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown5('daerah pemukiman di kawatuna')">daerah pemukiman di kawatuna</a></li>
                  </ul>
                  <input type="hidden" name="lokasi" id="lokasi" value="<?php echo isset($_POST['lokasi']) ? $_POST['lokasi'] : ''; ?>" required>
                </div>
                <script>
                  function updateDropdown5(value) {
                    document.querySelector("#dropdownMenuButton5").innerText = value;
                    document.querySelector("#lokasi").value = value;
                  }
                </script>

                <br>
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
                  <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton3" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php echo isset($_POST['status']) ? $_POST['status'] : 'status :'; ?>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton3">
                    <li><a class="dropdown-item" onclick="updateDropdown3('Tersertifikasi')">Tersertifikasi</a></li>
                    <li><a class="dropdown-item" onclick="updateDropdown3('Tidak Tersertifikasi')">Tidak Tersertifikasi</a></li>
                  </ul>
                  <input type="hidden" name="status" id="status" value="<?php echo isset($_POST['status']) ? $_POST['status'] : ''; ?>" required>
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