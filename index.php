<?php
require "fpdf.php";

$db = new PDO('mysql:host=localhost;dbname=mahasiswa', 'raspberry', '123456789');



class myPDF extends FPDF{
    function header(){
        $this->Image('LOGO-BASIL.png',10,6);
        $this->SetFont('Arial', 'B', 14);
        $this->Cell(276,5, 'Presensi kehadiran pegawai', 0, 0, 'C');
        $this->Ln();
        $this->SetFont('Times', '', 12);
        $this->Cell(276,10, 'Sub Judul', 0, 0, 'C');
        $this->Ln(20);
    }
    
    function footer(){
        $this->SetY(-15);
        $this->SetFont('Arial', '', 8);
        $this->Cell(0,10, 'Page' .$this->PageNo().'/{nb}', 0, 0, 'C');
    }

    function headerTable(){
        $this->SetFont('Times', 'B',12);
        $this->Cell(18,10, 'ID', 1, 0, 'C');
        $this->Cell(55,10, 'Nama', 1, 0, 'C');
        $this->Cell(40,10, 'keterangan', 1, 0, 'C');
        $this->Cell(35,10, 'di kantor', 1, 0, 'C');
        // $this->Cell(220,10, 'lokasi', 1, 0, 'C');
        $this->Cell(50,10, 'Jam Presensi', 1, 0, 'C');
        $this->Ln();
    }

    function viewTable($db){
        $this->SetFont('Times', '',12);
        $count = 1;
        $uuid = "5de388b86217f0.84892492";
        // jika untuk user tertentu
        // $stmt = $db->query("select nama, keterangan, is_in_office, lokasi, created_at from tbl_kehadiran inner join tbl_user using (uuid_user) where uuid_user='".$uuid."'");

        //semua
        $stmt = $db->query("select nama, keterangan, is_in_office, lokasi, created_at from tbl_kehadiran inner join tbl_user using (uuid_user)");

        while($data = $stmt->fetch(PDO::FETCH_OBJ)){
        $this->Cell(18,10, $count, 1, 0, 'C');
        $this->Cell(55,10, $data->nama, 1, 0, 'C');
        $this->Cell(40,10, $data->keterangan, 1, 0, 'C');
        $this->Cell(35,10, $data->is_in_office, 1, 0, 'C');
        // $this->Cell(220,10, $data->lokasi, 1, 0, 'C');
        $this->Cell(50,10, $data->keterangan, 1, 0, 'C');
        $this->Ln();
        $count++;
        }
    }
}

$pdf = new myPDF();
$pdf->AliasNBPages();
$pdf->AddPage('L', 'A4', 0);
$pdf->headerTable();
$pdf->viewTable($db);
$pdf->Output();
// $pdf->Output('D','daftar_presensi.pdf', 'isUTF8');