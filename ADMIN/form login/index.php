<?php

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

?>

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
$nomor_urut = 1;
foreach ($data as $row) {

  echo "<tr>";
  echo "<td>" . $nomor_urut . "</td>"; // Menampilkan nomor urut
  echo "<td class='border-bottom-0'><h6 class='fw-semibold mb-0'>" . $row['lokasi'] . "</h6></td>";
  echo "<td class='border-bottom-0'><h6 class='fw-semibold mb-0'>" . $row['alamat'] . "</h6></td>";
  echo "<td class='border-bottom-0'><h6 class='fw-semibold mb-0'>" . $row['luas'] . "</h6></td>";
  echo "<td class='border-bottom-0'><h6 class='fw-semibold mb-0'>" . $row['status'] . "</h6></td>";

  echo "<td class='border-bottom-0'>
      <button class='btn btn-sm btn-primary' onclick='updateData(" . $row['id'] . ")'>Cek</button>
  </td>";
}
?>
                    </table>
                </section>
            </div>
        </div>
    </div>

    <script>
    // Wait for the DOM content to be fully loaded
    document.addEventListener("DOMContentLoaded", function() {
        // Function to sort the table rows based on the second column (jumlah kasus)
        function sortTable() {
            var table, rows, switching, i, x, y, shouldSwitch;
            table = document.getElementById("data-table");
            switching = true;
            while (switching) {
                switching = false;
                rows = table.getElementsByTagName("tr");
                for (i = 1; i < (rows.length - 1); i++) {
                    shouldSwitch = false;
                    x = rows[i].getElementsByTagName("td")[2]; // Change index to match your column
                    y = rows[i + 1].getElementsByTagName("td")[2]; // Change index to match your column
                    if (parseInt(x.innerHTML) < parseInt(y.innerHTML)) {
                        shouldSwitch = true;
                        break;
                    }
                }
                if (shouldSwitch) {
                    // Swap rows
                    rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
                    switching = true;
                }
            }

            // Reassigning row numbers
            var rowCount = 1;
            for (var j = 1; j < rows.length; j++) {
                var row = rows[j].getElementsByTagName("td")[0];
                if (row) {
                    row.innerHTML = rowCount;
                    rowCount++;
                }
            }
        }

        // Call the sortTable function when the page is loaded
        sortTable();
    });
    </script>

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
var map = L.map("map").setView([-0.891871, 119.859972], 15);

L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
}).addTo(map);

const info = L.control();

info.onAdd = function(map) {
    this._div = L.DomUtil.create('div', 'info');
    this.update();
    return this._div;
};

<?php echo 'const iniDataku ='.json_encode($groupedData)?>


info.update = function(props) {
    const contents = props ? `<b>${props.namalokasi}</b><br />${iniDataku[props.namalokasi]} Luas` : 'Hover';
    this._div.innerHTML = `<h4>Sertifikasi</h4>${contents}`;
};

info.addTo(map);


// get color depending on population density value
function getColor(d) {
    return d > 71 ? '#800026' :
        d > 61 ? '#BD0026' :
        d > 51 ? '#E31A1C' :
        d > 41 ? '#FC4E2A' :
        d > 31 ? '#FD8D3C' :
        d > 21 ? '#FEB24C' :
        d > 11 ? '#FED976' :
        '#030303';
}

function style(feature) {
    return {
        weight: 2,
        opacity: 1,
        color: 'white',
        dashArray: '3',
        fillOpacity: 0.7,
        fillColor: getColor(iniDataku[feature.properties.namalokasi])
    };
}

function highlightFeature(e) {
    const layer = e.target;

    layer.setStyle({
        weight: 5,
        color: '#666',
        dashArray: '',
        fillOpacity: 0.7
    });

    layer.bringToFront();

    info.update(layer.feature.properties);
}

function resetHighlight(e) {
    var layer = e.target;
    layer.setStyle({
        weight: 2,
        opacity: 1,
        color: 'white',
        dashArray: '3',
    });
    info.update();
}

function zoomToFeature(e) {
    map.fitBounds(e.target.getBounds());
}

function onEachFeature(feature, layer) {
    layer.on({
        mouseover: highlightFeature,
        mouseout: resetHighlight,
        click: zoomToFeature
    });
}

const legend = L.control({
    position: 'bottomright'
});

legend.onAdd = function(map) {

    const div = L.DomUtil.create('div', 'info legend');
    const grades = [0, 10, 20, 30, 40, 50, 60, 70];
    const labels = [];
    let from, to;

    for (let i = 0; i < grades.length; i++) {
        from = grades[i];
        to = grades[i + 1];

        labels.push(`<i style="background:${getColor(from + 1)}"></i> ${from}${to ? `&ndash;${to}` : '+'}`);
    }

    div.innerHTML = labels.join('<br>');
    return div;
};

legend.addTo(map);

var jsonTest = new L.GeoJSON.AJAX(["./src/html/data/tes.geojson"], {
    style: style,
    onEachFeature: onEachFeature
}).addTo(map);
</script>

</html>