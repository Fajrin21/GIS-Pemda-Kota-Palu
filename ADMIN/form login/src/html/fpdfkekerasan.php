<?php
    include "library/fpdf.php";

    $servername = "localhost";
    $username = "root"; // Ganti dengan username database Anda
    $password = ""; // Ganti dengan password database Anda
    $dbname = "datapernikahananak"; // Ganti dengan nama database Anda

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    $sql = "SELECT * FROM datakekerasan";
    $result = $conn->query($sql);

    $pdf = new FPDF();

    // Add a page
    $pdf->AddPage();
    
    // Set font properties
    $pdf->SetFont('Arial', 'B', 16);
    
    // Add a title
    $pdf->Cell(0, 10, 'Data Kekerasan', 0, 1, 'C'); 
    // Set font properties for data rows
    $pdf->SetFont('Arial', '', 10);
    
    // Add data rows from the database
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(40, 6, 'Nama Korban:', 0);
        $pdf->MultiCell(0, 6, $row['nama_korban'], 1);
        
        $pdf->Cell(40, 6, 'Nama Pelaku:', 0);
        $pdf->MultiCell(0, 6, $row['nama_pelaku'], 1);
        
        $pdf->Cell(40, 6, 'Jenis Kelamin Korban:', 0);
        $pdf->MultiCell(0, 6, $row['jk_korban'], 1);
        
        $pdf->Cell(40, 6, 'Jenis Kelamin Pelaku:', 0);
        $pdf->MultiCell(0, 6, $row['jk_pelaku'], 1);
        
        $pdf->Cell(40, 6, 'Umur Korban:', 0);
        $pdf->MultiCell(0, 6, $row['umur_korban'], 1);
        
        $pdf->Cell(40, 6, 'Umur Pelaku:', 0);
        $pdf->MultiCell(0, 6, $row['umur_pelaku'], 1);
        
        $pdf->Cell(40, 6, 'Status Korban:', 0);
        $pdf->MultiCell(0, 6, $row['status_korban'], 1);
        
        $pdf->Cell(40, 6, 'Status Pelaku:', 0);
        $pdf->MultiCell(0, 6, $row['status_pelaku'], 1);
        
        $pdf->Cell(40, 6, 'Pendidikan Korban:', 0);
        $pdf->MultiCell(0, 6, $row['pendidikan_korban'], 1);
        
        $pdf->Cell(40, 6, 'Tempat Kejadian:', 0);
        $pdf->MultiCell(0, 6, $row['tempat_kejadian'], 1);
        
        $pdf->Cell(40, 6, 'Tanggal Input:', 0);
        $pdf->MultiCell(0, 6, date('Y-m-d', strtotime($row['tanggal_penginputan'])), 1);
        
        $pdf->Cell(40, 6, 'Kabupaten:', 0);
        $pdf->MultiCell(0, 6, $row['kabkota'], 1);
        
        $pdf->Ln(); // Move to the next line
    }
    
    // Output the PDF to the browser
    $pdf->Output();
?>
