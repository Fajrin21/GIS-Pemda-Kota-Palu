<?php

function rc4($key, $encrypted_str)
  {
    // Inisialisasi array s
    $s = array();
    for ($i = 0; $i < 256; $i++) {
      $s[$i] = $i;
    }

    // Inisialisasi variabel dan array lainnya
    $j = 0;
    $key_length = strlen($key);
    $str_length = strlen($encrypted_str);
    $res = '';

    // Key-Scheduling Algorithm (KSA)
    for ($i = 0; $i < 256; $i++) {
      $j = ($j + $s[$i] + ord($key[$i % $key_length])) % 256;
      $temp = $s[$i];
      $s[$i] = $s[$j];
      $s[$j] = $temp;
    }

    // Pseudo-Random Generation Algorithm (PRGA) dan Dekripsi
    $i = $j = 0;
    for ($y = 0; $y < $str_length / 2; $y++) {
      $i = ($i + 1) % 256;
      $j = ($j + $s[$i]) % 256;
      $temp = $s[$i];
      $s[$i] = $s[$j];
      $s[$j] = $temp;

      // Mendapatkan byte enkripsi dari ciphertext dalam format heksadesimal
      $hex = substr($encrypted_str, $y * 2, 2); //Baris ini mengambil dua karakter dari ciphertext dalam format heksadesimal pada setiap iterasi loop.

      // Mendekripsi byte
      $res .= chr(hexdec($hex) ^ $s[($s[$i] + $s[$j]) % 256]); //Baris ini mendekripsi byte yang diambil dari ciphertext.
    }

    return $res;
  }
  
  $key = 'kuncisaya';

$servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "kaipang";

  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $fetchQuery = "SELECT luas FROM peta"; 
$fetchResultLuas = $conn->query($fetchQuery);

if ($fetchResultLuas->num_rows > 0) {
    $groupedData = [];
    while ($row = $fetchResultLuas->fetch_assoc()) {
        $lokasi = $row['luas'];
        $jumlah = 1;

        if (isset($groupedData[$lokasi])) {
            $jumlah = $groupedData[$lokasi] + 1;
        }

        $groupedData[$lokasi] = $jumlah;
    }
}

$fetchQuery = "SELECT lokasi, alamat, luas, status, latitude, longitude FROM peta";
$fetchResult = $conn->query($fetchQuery);

if ($fetchResult->num_rows > 0) {
    $groupedData = [];
    while ($row = $fetchResult->fetch_assoc()) {
        $lokasi = $row['lokasi'];
        $alamat = $row['alamat'];
        $luas = $row['luas'];
        $status = $row['status'];
        $latitude = $row['latitude'];
        $longitude = $row['longitude'];

        // Memasukkan data ke dalam array terpisah berdasarkan lokasi
        $groupedData[$lokasi]['alamat'][] = $alamat;
        $groupedData[$lokasi]['luas'][] = $luas;
        $groupedData[$lokasi]['status'][] = $status;
        $groupedData[$lokasi]['latitude'] = $latitude;
        $groupedData[$lokasi]['longitude'] = $longitude;
    }
} else {
    echo "Tidak ada data yang ditemukan.";
}

 $recordsPerPage = 5; // Adjust as needed
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $recordsPerPage;

// Fetch total number of records
$totalQuery = "SELECT COUNT(*) AS total FROM peta";
$totalResult = $conn->query($totalQuery);
$totalRecords = $totalResult->fetch_assoc()['total'];

// Calculate total pages
$totalPages = ceil($totalRecords / $recordsPerPage);

$query = "SELECT * FROM peta LIMIT $offset, $recordsPerPage";
$result = $conn->query($query);

  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form was submitted
    $id = isset($_POST['id']) ? $_POST['id'] : null;

    // Validate other fields as needed
    $lokasi = $_POST['lokasi'];
    $alamat = $_POST['alamat'];
    $luas = $_POST['luas'];
    $status = $_POST['status'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];


    if ($id) {
      $updateQuery = "UPDATE peta SET lokasi = '$lokasi', alamat = '$alamat', luas = '$luas', status = '$status', latitude = '$latitude', longitude = '$longitude' WHERE id = $id";
      if ($conn->query($updateQuery) === TRUE) {
          echo "Record updated successfully";
      } else {
          echo "Error updating record: " . $conn->error;
      }
  } else {
      $insertQuery = "INSERT INTO peta (lokasi, alamat, luas, status, latitude, longitude) VALUES ('$lokasi', '$alamat', '$luas', '$status', '$latitude', '$longitude')";
      if ($conn->query($insertQuery) === TRUE) {
          echo "New record created successfully";
      } else {
          echo "Error: " . $insertQuery . "<br>" . $conn->error;
      }
  }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Data Pernikahan</title>
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Jost:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet" />

    <!-- Vendor CSS Files -->
    <link href="assets/vendor/aos/aos.css" rel="stylesheet" />
    <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet" />
    <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet" />
    <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet" />
    <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet" />

    <!-- Template Main CSS File -->
    <link href="assets/css/style.css" rel="stylesheet" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <style>
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        z-index: 1;
    }

    .overlay.active {
        display: block;
    }

    .popup {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        z-index: 1;
        display: none;
        width: 400px;
    }

    .popup.active {
        display: block;
    }

    .popup h2 {
        margin-top: 0;
    }

    .popup button {
        margin-top: 20px;
        padding: 10px 20px;
        background: #007bff;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .popup button:hover {
        background: #0056b3;
    }

    #map {
        z-index: 0;
        width: 800px;
        height: 500px;
    }

    #popup-map {
        width: 100%;
        height: 200px;
    }

    .info {
        padding: 6px 8px;
        font: 14px/16px Arial, Helvetica, sans-serif;
        background: white;
        background: rgba(255, 255, 255, 0.8);
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
        border-radius: 5px;
    }

    .info h4 {
        margin: 0 0 5px;
        color: #777;
    }

    .legend {
        text-align: left;
        line-height: 18px;
        color: #555;
    }

    .legend i {
        width: 18px;
        height: 18px;
        float: left;
        margin-right: 8px;
        opacity: 0.7;
    }
    </style>
    <script>
    function submitForm() {
        // Fetch form data
        var formData = $('#updateForm').serialize();

        // Send an AJAX request to update data
        $.ajax({
            type: 'POST',
            url: window.location.href,
            data: formData,
            success: function(response) {
                try {
                    var data = JSON.parse(response);
                    if (data.status === 'success') {
                        // If update is successful, reload the page
                        location.reload();
                    } else {
                        // If there is an error, display the error message
                        alert('Error updating data: ' + data.message);
                        location.reload();
                    }
                } catch (error) {
                    console.log(error);
                } finally {
                    // Regardless of success or error, hide the modal
                    $('#updateModal').modal('hide');
                    location.reload();
                }
            },
            error: function(error) {
                console.log(error);
                // In case of an error, hide the modal
                $('#updateModal').modal('hide');
                location.reload();
            }
        });
    }

    function deleteData(id) {
        var isConfirmed = confirm("Are you sure you want to delete this record?");
        if (isConfirmed) {
            window.location.href = '?delete=true&id=' + id;
        }
    }

    function updateData(id) {
        $('#updateModal').modal('show');
        $.ajax({
            type: 'GET',
            url: window.location.href,
            data: {
                fetch_data: true,
                id: id
            },
            success: function(response) {
                try {
                    var data = JSON.parse(response);
                    $('#user_id').val(data.id);
                    $('#lokasi').val(data.lokasi);
                    $('#alamat').val(data.alamat);
                    $('#luas').val(data.luas);
                    $('#status').val(data.status);
                    $('#latitude').val(data.latitude);
                    $('#longitude').val(data.longitude);
                } catch (error) {
                    console.log(error);
                }
            },
            error: function(error) {
                console.log(error);
            }
        });
    }
    </script>
</head>

<body>
    <header id="header" class="fixed-top" style="background-color: rgba(40, 58, 90, 0.9)">
        <div class="container d-flex align-items-center">
            <h1 class="logo me-auto"><a href="index.php">Kota Palu</a></h1>

            <nav id="navbar" class="navbar">
                <ul>
                    <li><a href="datapernikahan.php">Data Persertifikatan</a></li>
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav>
        </div>
    </header>

    <div class="container-fluid" style="margin-top: 100px">
        <div class="container-fluid" style="margin-top: 100px">
            <div class="container"
                style="box-shadow: rgba(6, 24, 44, 0.4) 0px 0px 0px 2px, rgba(6, 24, 44, 0.65) 0px 4px 6px -1px, rgba(255, 255, 255, 0.08) 0px 1px 0px inset; padding: 10px">
                <div id="map" style="width: 100%; height: 600px">
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="container">
                <section id="datapernikahan">
                    <table id="data-table" class="table table-striped table-hover">
                        <thead class="table-primary fw-bold">
                            <tr>
                                <td>No</td>
                                <td>Lokasi</td>
                                <td>Alamat</td>
                                <td>Luas</td>
                                <td>Status</td>
                                <td>Latitude</td>
                                <td>Longitude</td>
                                <td>Aksi</td>
                            </tr>
                        </thead>


                        <form action="" method="GET" class="mb-4">
                            <div class="flex items-center">
                                <input type="text" name="search" class="form-input w-full rounded-l-md"
                                    placeholder="Cari berdasarkan nama...">
                                <button type="submit"
                                    class="bg-blue-500 text-white px-4 py-2 rounded-r-md hover:bg-blue-600">Cari</button>
                            </div>
                        </form>
                        <?php

                        function rc4encrypt($key, $str)
    {
      //inisialisasi array 3
      $s = array();
      for ($i = 0; $i < 256; $i++) {
        $s[$i] = $i;
      }
    
      //Key-Scheduling Algorithm
      $j = 0;
      for ($i = 0; $i < 256; $i++) { //loop yang akan dieksekusi sebanyak 256 kali
        $j = ($j + $s[$i] + ord($key[$i % strlen($key)])) % 256; // langkah untuk mengacak array S
        $temp = $s[$i]; //  Ini adalah langkah untuk menukar nilai antara elemen array S pada indeks $i dengan nilai pada indeks $j.
        $s[$i] = $s[$j];
        $s[$j] = $temp;
      }
    
      //Pseudo-Random Generation Algorithm (PRGA) untuk melakukan enkripsi atau dekripsi data.
      $i = $j = 0;
      $res = '';
      for ($y = 0; $y < strlen($str); $y++) {
        $i = ($i + 1) % 256;
        $j = ($j + $s[$i]) % 256;
        $temp = $s[$i];
        $s[$i] = $s[$j];
        $s[$j] = $temp;
        // Menggunakan fungsi sprintf() untuk menghasilkan format heksadesimal
        $res .= sprintf("%02X", ord($str[$y]) ^ $s[($s[$i] + $s[$j]) % 256]);
      }
      return $res;
    }
function encryptKeyword($keyword, $key) {
    return rc4encrypt($key, $keyword);
}

function bruteForce($search, $data, $key) {
    $matches = [];
    foreach ($data as $row) {
        // Enkripsi kata kunci untuk membandingkan dengan kata di database
        $encrypted_row_lokasi = rc4encrypt($key, $row['lokasi']);
        if (strpos(strtolower($encrypted_row_lokasi), strtolower($search)) !== false) {
            $matches[] = $row;
        }
    }
    return $matches;
}

$key = 'kuncisaya';

// Memperoleh kata kunci dari input pengguna
$search = isset($_GET['search']) ? $_GET['search'] : '';
// Enkripsi kata kunci sebelum melakukan pencarian
$encrypted_search = encryptKeyword($search, $key);

// Memperoleh data dari database
$data = [];
$query = "SELECT * FROM peta";
if ($encrypted_search !== '') {
    // Modifikasi query untuk mencocokkan kata yang sudah dienkripsi
    $encrypted_search = mysqli_real_escape_string($conn, $encrypted_search);
    $query .= " WHERE lokasi LIKE '%$encrypted_search%'";
}
$result = $conn->query($query);
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
$nomor_urut = 1;

foreach ($data as $row)  {
    // Dekripsi data sebelum ditampilkan
    $lokasi_decrypted = rc4($key, $row['lokasi']);
    $alamat_decrypted = rc4($key, $row['alamat']);
    $luas_decrypted = rc4($key, $row['luas']);
    $status_decrypted = rc4($key, $row['status']);
    $latitude = $row['latitude'];
    $longitude = $row['longitude'];

    // Tampilkan data dalam tabel
    echo "<tr>";
    echo "<td class='border-bottom-0'><h6 class='fw-semibold mb-0'>" . $nomor_urut . "</h6></td>";
    echo "<td class='border-bottom-0'><h6 class='fw-semibold mb-0'>" . $lokasi_decrypted . "</h6></td>";
    echo "<td class='border-bottom-0'><h6 class='fw-semibold mb-0'>" . $alamat_decrypted . "</h6></td>";
    echo "<td class='border-bottom-0'><h6 class='fw-semibold mb-0'>" . $luas_decrypted . "</h6></td>";
    echo "<td class='border-bottom-0'><h6 class='fw-semibold mb-0'>" . $status_decrypted . "</h6></td>";
    echo "<td class='border-bottom-0'><h6 class='fw-semibold mb-0'>" . $latitude . "</h6></td>";
    echo "<td class='border-bottom-0'><h6 class='fw-semibold mb-0'>" . $longitude . "</h6></td>";
    echo "<td class='border-bottom-0'>
        <button class='btn btn-sm btn-primary' onclick='showPopup(\"$lokasi_decrypted\", \"$alamat_decrypted\", \"$luas_decrypted\", \"$status_decrypted\", \"$latitude\", \"$longitude\")'>Cek</button>
    </td>";
    echo "</tr>";
    $nomor_urut++;
}

// foreach ($data as $row) {
//   


  // echo "</tr>";}
                      echo '<ul class="pagination">';
for ($i = 1; $i <= $totalPages; $i++) {
    echo '<li class="page-item ' . ($page == $i ? 'active' : '') . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
}
                      ?>
                    </table>
                </section>
            </div>
        </div>
    </div>

    <div class="overlay" id="overlay"></div>
    <div class="popup" id="popup">
        <B>
            <center>
                <h2>INFORMASI LOKASI</h2>
            </center>
        </B>
        <br>
        <div id="popup-content"></div>
        <br>
        <button onclick="closePopup()">Tutup</button>
    </div>
</body>

<!-- End Footer -->

<div id="preloader"></div>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>

<!-- Vendor JS Files -->
<script src="assets/vendor/aos/aos.js"></script>
<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
<script src="assets/vendor/php-email-form/validate.js"></script>

<!-- Template Main JS File -->
<script src="assets/js/main.js"></script>
<script src="assets/js/ajaxleaflet.js"></script>

<script>
var map = L.map("map").setView([-0.891871, 119.859972], 12);

var layer = L.tileLayer('http://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
    maxZoom: 20,
    subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
}).addTo(map);

<?php
    foreach ($groupedData as $location => $data) {
        $latitude = $data['latitude'];
        $longitude = $data['longitude'];
        $lokasi_decrypted = rc4($key, $location);
        $alamat_decrypted = rc4($key, $alamat);
        $luas_decrypted = rc4($key, $luas);
        $status_decrypted = rc4($key, $status);

        // Output JavaScript to add a marker for this location
        echo "var marker = L.marker([$latitude, $longitude]).addTo(map);\n";

        // Bind a popup to the marker
        echo "marker.bindPopup('<b>Lokasi:</b> $lokasi_decrypted<br><b>Latitude:</b> $latitude<br><b>Longitude:</b> $longitude<br><b>alamat:</b> $alamat_decrypted<br><b>luas:</b> $luas_decrypted<br><b>status:</b> $status_decrypted<br>');\n";

        // Define coordinates for the rectangle
        $rectangleCoordinates = [
            [$latitude - 0.0005, $longitude - 0.0005], // Lower left corner
            [$latitude - 0.0005, $longitude + 0.0005], // Lower right corner
            [$latitude + 0.0005, $longitude + 0.0005], // Upper right corner
            [$latitude + 0.0005, $longitude - 0.0005], // Upper left corner
        ];

        // Output JavaScript to add a rectangle for this location
        echo "L.rectangle(" . json_encode($rectangleCoordinates) . ").addTo(map);\n";
    }
    ?>

function showPopup(location, address, area, status, latitude, longitude) {
    var popupContent = `
        <div id='popup-map'></div>
        </br>
        <p><strong>Lokasi:</strong> ${location}</p>
        <p><strong>Alamat:</strong> ${address}</p>
        <p><strong>Luas:</strong> ${area}</p>
        <p><strong>Status:</strong> ${status}</p>
        <p><strong>Latitude:</strong> ${latitude}</p>
        <p><strong>Longitude:</strong> ${longitude}</p>
    `;

    document.getElementById('popup-content').innerHTML = popupContent;
    document.getElementById('popup').classList.add('active');
    document.getElementById('overlay').classList.add('active');

    var popupMap = L.map('popup-map').setView([latitude, longitude], 17);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(popupMap);
    L.marker([latitude, longitude]).addTo(popupMap);

    // Tambahkan koordinat kotak
    var corner1 = [parseFloat(latitude) - 0.00015, parseFloat(longitude) - 0.00015]; // Contoh koordinat sudut kotak
    var corner2 = [parseFloat(latitude) + 0.00015, parseFloat(longitude) + 0.00015]; // Contoh koordinat sudut kotak

    // Buat kotak dan tambahkan ke peta
    var rectangle = L.rectangle([corner1, corner2], {
        color: "#ff7800",
        weight: 1
    }).addTo(popupMap);
}

function closePopup() {
    document.getElementById('popup').classList.remove('active');
    document.getElementById('overlay').classList.remove('active');
}

closePopup();
</script>


</html>