<?php
// Meng-include file konfigurasi dan library FPDF
include '../config.php';
require('fpdf/fpdf.php');

// Membuat class PDF yang extend dari FPDF
class PDF extends FPDF
{
    // Membuat fungsi word-wrap
    function WordWrap(&$text, $maxwidth) {
        $text = trim($text);
        if ($text === '') return 0;
        $space = $this->GetStringWidth(' ');
        $lines = explode("\n", $text);
        $text = '';
        $count = 0;

        foreach ($lines as $line) {
            $words = preg_split('/ +/', $line);
            $width = 0;

            foreach ($words as $word) {
                $wordwidth = $this->GetStringWidth($word);
                if ($wordwidth > $maxwidth) {
                    for($i=0; $i<strlen($word); $i++) {
                        $wordwidth = $this->GetStringWidth(substr($word, $i, 1));
                        if($width + $wordwidth <= $maxwidth) {
                            $width += $wordwidth;
                            $text .= substr($word, $i, 1);
                        }
                        else {
                            $width = $wordwidth;
                            $text = rtrim($text)."\n".substr($word, $i, 1);
                            $count++;
                        }
                    }
                }
                elseif($width + $wordwidth <= $maxwidth) {
                    $width += $wordwidth + $space;
                    $text .= $word.' ';
                } else {
                    $width = $wordwidth + $space;
                    $text = rtrim($text)."\n".$word.' ';
                    $count++;
                }
            }
            $text = rtrim($text)."\n";
            $count++;
        }
        $text = rtrim($text);
        return $count;
    }

    // Membuat header tabel
    function Header() {
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(0, 10, 'Daftar novel', 0, 1, 'C');
        $this->Cell(8, 8, 'ID', 1, 0, 'C');
        $this->Cell(15, 8, 'Poster', 1, 0, 'C');
        $this->Cell(65.5, 8, 'Judul novel', 1, 0, 'C');
        $this->Cell(17.5, 8, 'Tahun Rilis', 1, 0, 'C');
        $this->Cell(32.5, 8, 'Author', 1, 0, 'C');
        $this->Cell(52, 8, 'Genre', 1, 0, 'C');
        $this->Ln();
    }

    // Membuat footer tabel
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, 'Halaman '.$this->PageNo().'/{nb}', 0, 0, 'C');
    }
}

// Membuat instance PDF
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();

// Mengambil data dari database
$sql = "SELECT novel.id_novel, novel.poster, novel.judul_novel, novel.tahun_rilis, author.nama_author, GROUP_CONCAT(genre.nama_genre SEPARATOR ', ') as genres
        FROM novel
        LEFT JOIN novel_genre ON novel.id_novel = novel_genre.id_novel
        LEFT JOIN genre ON novel_genre.id_genre = genre.id_genre
        LEFT JOIN author ON novel.id_author = author.id_author
        GROUP BY novel.id_novel";
$result = $conn->query($sql);

// Menampilkan data pada tabel PDF
while ($row = $result->fetch_assoc()) {
    $genre = $row['genres'];
    $pdf->WordWrap($genre, 50);
    $pdf->Cell(8, 20, $row['id_novel'], 1, 0, 'C');
    $pdf->Cell(15, 20, $pdf->Image("../images/".str_replace('=', '"', str_replace("_", "'", $row['poster'])), $pdf->GetX(), $pdf->GetY(), 15, 20, 'JPG'), 1, 0, 'C');
    $pdf->Cell(65.5, 20, htmlspecialchars($row['judul_novel']), 1);
    $pdf->Cell(17.5, 20, $row['tahun_rilis'], 1);
    $pdf->Cell(32.5, 20, htmlspecialchars($row['nama_author']), 1);
    $pdf->Cell(52, 20, $genre, 1);
    $pdf->Ln();
}

// Menutup koneksi database
$conn->close();

// Output file PDF
$pdf->Output();
?>
