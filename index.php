<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require "fpdf.php";

$db = new PDO('mysql:host=localhost;dbname=mahasiswa', 'raspberry', '123456789');

// json response array
$response = array("error" => FALSE);
$data = json_decode(file_get_contents("php://input"));
// echo 'console.log('. json_encode( $data ) .')';


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
        $this->Cell(40,10, 'Keterangan', 1, 0, 'C');
        $this->Cell(35,10, 'Di Kantor', 1, 0, 'C');
        // $this->Cell(220,10, 'lokasi', 1, 0, 'C');
        $this->Cell(50,10, 'Jam Presensi', 1, 0, 'C');
        $this->Ln();
    }

    function viewTable($db, $uuid){
        $this->SetFont('Times', '',12);
        $count = 1;
        // $uuid = "5de388b86217f0.84892492";
        // jika untuk user tertentu
        $stmt = $db->query("select nama, keterangan, is_in_office, lokasi, created_at from tbl_kehadiran inner join tbl_user using (uuid_user) where uuid_user='".$uuid."'");

        //semua
        // $stmt = $db->query("select nama, keterangan, is_in_office, lokasi, created_at from tbl_kehadiran inner join tbl_user using (uuid_user)");

        while($data = $stmt->fetch(PDO::FETCH_OBJ)){
        $this->Cell(18,10, $count, 1, 0, 'C');
        $this->Cell(55,10, $data->nama, 1, 0, 'C');
        $this->Cell(40,10, $data->keterangan, 1, 0, 'C');
        $this->Cell(35,10, $data->is_in_office, 1, 0, 'C');
        // $this->Cell(220,10, $data->lokasi, 1, 0, 'C');
        $this->Cell(50,10, $data->created_at, 1, 0, 'C');
        $this->Ln();
        $count++;
        }
    }
}



if(!is_null($data)){
    $uuid = $data->user_id;
    // $uuid = "5de388b86217f0.84892492";
    $pdf = new myPDF();
    $pdf->AliasNBPages();
    $pdf->AddPage('L', 'A4', 0);
    $pdf->headerTable();
    $pdf->viewTable($db,$uuid);
    // $pdf->Output();
    // $pdf->Output('F','daftar_presensi.pdf', 'isUTF8');
    // $pdf->Output("D","$uuid.pdf");
    $filename="D:/xampp/htdocs/MembuatPdf/FPDF/$uuid.pdf";
    $pdf->Output('F',$filename,TRUE);
    $response["error"] = FALSE;
    echo json_encode($response);
} else{
    $response["error"] = TRUE;
    $response["error_msg"] = "gagaga";
    echo json_encode($response);
}
   





