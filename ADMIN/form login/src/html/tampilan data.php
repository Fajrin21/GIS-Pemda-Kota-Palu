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


  if (isset($_GET['delete']) && isset($_GET['id'])) {
    // Get the ID from the URL
    $id = $_GET['id'];

    // Prepare a SQL statement to delete the record with the given ID
    $deleteQuery = "DELETE FROM peta WHERE id = $id";

    // Execute the delete query
    if ($conn->query($deleteQuery) === TRUE) {
      // Deletion successful, redirect to the same page without 'delete' parameter
      header("Location: " . $_SERVER['PHP_SELF']);
      exit();
    } else {
      // Handle the error if deletion fails
      echo "Error deleting data: " . $conn->error;
    }
  }

  $fetchQuery = "SELECT lokasi, alamat, luas, status FROM peta";
$fetchResult = $conn->query($fetchQuery);

if ($fetchResult->num_rows > 0) {
    $groupedData = [];
    while ($row = $fetchResult->fetch_assoc()) {
        $lokasi = $row['lokasi'];
        $alamat = $row['alamat'];
        $luas = $row['luas'];
        $status = $row['status'];

        // Memasukkan data ke dalam array terpisah berdasarkan lokasi
        $groupedData[$lokasi]['alamat'][] = $alamat;
        $groupedData[$lokasi]['luas'][] = $luas;
        $groupedData[$lokasi]['status'][] = $status;
    }
} else {
    echo "Tidak ada data yang ditemukan.";
}

  if (isset($_GET['fetch_data']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $fetchQuery = "SELECT * FROM peta WHERE id = $id";
    $fetchResult = $conn->query($fetchQuery);

    if ($fetchResult->num_rows > 0) {
      $rowData = $fetchResult->fetch_assoc();
      echo json_encode($rowData);
      exit();
    } else {
      echo json_encode(['error' => 'Data not found']);
      exit();
    }
  }

  if (isset($_POST['change_status']) && isset($_POST['id']) && isset($_POST['status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    
    // Update hanya status pada record dengan ID yang diberikan
    $updateStatusQuery = "UPDATE peta SET proses = '$status' WHERE id = $id";
    
    if ($conn->query($updateStatusQuery) === TRUE) {
    } else {
    }
    exit(); // Stop further execution
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

// Fetch data for the current page
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
  ?>

  <!DOCTYPE html>
  <html lang="en">

  <head>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title>DP3A ADMIN DASHBOARD</title>
      <link rel="shortcut icon" type="image/png" href="../assets/images/logos/favicon.png" />
      <link rel="stylesheet" href="../assets/css/styles.min.css" />
      <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
      <script src="../assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
      <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

      <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
      <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
          integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
      <style>
      #map {
          width: 800px;
          height: 500px;
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

      <!-- Body Wrapper -->
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
          <!-- Sidebar End -->
          <!-- Main wrapper -->
          <div class="body-wrapper">
              <div class="container-fluid">
                  <div class="container-fluid" style="margin-top: 100px">
                      <div class="container"
                          style="box-shadow: rgba(6, 24, 44, 0.4) 0px 0px 0px 2px, rgba(6, 24, 44, 0.65) 0px 4px 6px -1px, rgba(255, 255, 255, 0.08) 0px 1px 0px inset; padding: 10px">
                          <div id="map" style="width: 100%; height: 600px">
                          </div>
                      </div>
                  </div>
                  <br>
                  <!-- Row 1 -->
                  <div class="col-lg-12 d-flex align-items-stretch">
                      <div class="card w-300">
                          <div class="card-body p-4">
                              <h5 class="card-title fw-semibold mb-4">Data Peta</h5>
                              <div class="table-responsive">
                                  <table class="table text-nowrap mb-0 align-middle">
                                      <thead class="text-dark fs-4">
                                          <tr>
                                              <th class="border-bottom-0">
                                                  <h6 class="fw-semibold mb-0">Lokasi</h6>
                                              </th>
                                              <th class="border-bottom-0">
                                                  <h6 class="fw-semibold mb-0">Alamat</h6>
                                              </th>
                                              <th class="border-bottom-0">
                                                  <h6 class="fw-semibold mb-0">Luas</h6>
                                              </th>
                                              <th class="border-bottom-0">
                                                  <h6 class="fw-semibold mb-0">Status</h6>
                                              </th>
                                              <th class="border-bottom-0">
                                                  <h6 class="fw-semibold mb-0">Latitude</h6>
                                              </th>
                                              <th class="border-bottom-0">
                                                  <h6 class="fw-semibold mb-0">Longitude</h6>
                                              </th>
                                      </thead>
                                      <tbody>

                                          <form action="" method="GET" class="mb-4">
                                              <div class="flex items-center">
                                                  <input type="text" name="search"
                                                      class="form-input w-full rounded-l-md"
                                                      placeholder="Cari berdasarkan nama...">
                                                  <button type="submit"
                                                      class="bg-blue-500 text-white px-4 py-2 rounded-r-md hover:bg-blue-600">Cari</button>
                                              </div>
                                          </form>
                                          <?php

function bruteForce($search, $data) {
  $matches = [];
  foreach ($data as $row) {
      if (strpos(strtolower($row['lokasi']), strtolower($search)) !== false) {
          $matches[] = $row;
      }
  }
  return $matches;
}

$data = [];
$query2 = "SELECT * FROM peta";
if (isset($_GET['search'])) {
  $search = $_GET['search'];
  $query2 .= " WHERE lokasi LIKE '%$search%'";
}
$result2 = mysqli_query($conn, $query2);
while ($row = mysqli_fetch_assoc($result2)) {
  $data[] = $row;
}

while ($row = $result->fetch_assoc()) {
$lokasi_decrypted = rc4($key, $row['lokasi']);
$alamat_decrypted = rc4($key, $row['alamat']);
$luas_decrypted = rc4($key, $row['luas']);
$status_decrypted = rc4($key, $row['status']);
$latitude = $row['latitude'];
$longitude = $row['longitude'];

echo "<tr>";
echo "<td class='border-bottom-0'><h6 class='fw-semibold mb-0'>" . $lokasi_decrypted . "</h6></td>";
echo "<td class='border-bottom-0'><h6 class='fw-semibold mb-0'>" . $alamat_decrypted . "</h6></td>";
echo "<td class='border-bottom-0'><h6 class='fw-semibold mb-0'>" . $luas_decrypted . "</h6></td>";
echo "<td class='border-bottom-0'><h6 class='fw-semibold mb-0'>" . $status_decrypted . "</h6></td>";
echo "<td class='border-bottom-0'><h6 class='fw-semibold mb-0'>" . $longitude . "</h6></td>";
echo "<td class='border-bottom-0'><h6 class='fw-semibold mb-0'>" . $latitude . "</h6></td>";

  echo "<td class='border-bottom-0'>
      <button class='btn btn-sm btn-primary' onclick='updateData(" . $row['id'] . ")'>Update</button>
      <button class='btn btn-sm btn-danger' onclick='deleteData(" . $row['id'] . ")'>Delete</button>
  </td>";
}

// foreach ($data as $row) {
//   


  // echo "</tr>";}
                      echo '<ul class="pagination">';
for ($i = 1; $i <= $totalPages; $i++) {
    echo '<li class="page-item ' . ($page == $i ? 'active' : '') . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
}
                      ?>
                                      </tbody>
                                  </table>
                                  <!-- <button><a href="fpdfpernikahan.php" class="btn btn-success">PRINT</a></button> -->
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <div class="modal" id="updateModal" tabindex="-1" role="dialog" aria-labelledby="updateModalLabel"
              aria-hidden="true">
              <div class="modal-dialog" role="document">
                  <div class="modal-content">
                      <div class="modal-header">
                          <h5 class="modal-title" id="updateModalLabel">Update Data</h5>
                          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                          </button>
                      </div>
                      <div class="modal-body">

                          <!-- Update Form -->
                          <form id="updateForm" action="" method="post">
                              <div class="mb-3">
                                  <input type="hidden" class="form-control" id="user_id" name="id">
                              </div>
                              <div class="mb-3">
                                  <label for="lokasi" class="form-label">Lokasi</label>
                                  <input type="text" class="form-control" id="lokasi" name="lokasi">
                              </div>
                              <div class="mb-3">
                                  <label for="alamat" class="form-label">Alamat</label>
                                  <input type="text" class="form-control" id="alamat" name="alamat">
                              </div>

                              <div class="mb-3">
                                  <label for="luas" class="form-label">Luas</label>
                                  <input type="text" class="form-control" id="luas" name="luas">
                              </div>

                              <div class="mb-3">
                                  <label for="status" class="form-label">Status</label>
                                  <input type="text" class="form-control" id="status" name="status">
                              </div>

                              <div class="mb-3">
                                  <label for="Latitude" class="form-label">Latitude</label>
                                  <input type="text" class="form-control" id="latitude" name="latitude">
                              </div>

                              <div class="mb-3">
                                  <label for="longitude" class="form-label">Longitude</label>
                                  <input type="text" class="form-control" id="longitude" name="longitude">
                              </div>
                              <div class="modal-footer">
                                  <!-- Update button triggers form submission using JavaScript -->
                                  <button type="button" class="btn btn-primary" onclick="submitForm()">Update</button>
                                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              </div>
                      </div>
                  </div>
              </div>
              <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
              <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
              <script src="../assets/js/sidebarmenu.js"></script>
              <script src="../assets/js/app.min.js"></script>
              <script src="../assets/libs/simplebar/dist/simplebar.js"></script>
              <script src="../assets/js/dashboard.js"></script>
  </body>
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
      integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

  <script src="data/ajaxleaflet.js"></script>

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

    // Output JavaScript to add a marker for this location
    echo "L.marker([$latitude, $longitude]).addTo(map);\n";

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
  </script>

  </html>