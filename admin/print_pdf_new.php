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
        if ($text === '')
            return 0;
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
                    // Word is too long, we cut it
                    for ($i = 0; $i < strlen($word); $i++) {
                        $wordwidth = $this->GetStringWidth(substr($word, $i, 1));
                        if ($width + $wordwidth <= $maxwidth) {
                            $width += $wordwidth;
                            $text .= substr($word, $i, 1);
                        } else {
                            $width = $wordwidth;
                            $text = rtrim($text) . "\n" . substr($word, $i, 1);
                            $count++;
                        }
                    }
                } elseif ($width + $wordwidth <= $maxwidth) {
                    $width += $wordwidth + $space;
                    $text .= $word . ' ';
                } else {
                    $width = $wordwidth + $space;
                    $text = rtrim($text) . "\n" . $word . ' ';
                    $count++;
                }
            }
            $text = rtrim($text) . "\n";
            $count++;
        }
        $text = rtrim($text);
        return $count;
    }

    // Membuat header tabel
    function Header()
    {
        // Setting font
        $this->SetFont('Arial', 'B', 8);
        // Mencetak judul header
        $this->Cell(0, 10, 'Daftar novel', 0, 1, 'C');
        // Membuat header tabel
        $this->Cell(8, 8, 'ID', 1, 0, 'C');
        $this->Cell(15, 8, 'Poster', 1, 0, 'C');
        $this->Cell(65.5, 8, 'Judul novel', 1, 0, 'C');
        $this->Cell(17.5, 8, 'Tahun Rilis', 1, 0, 'C');
        $this->Cell(32.5, 8, 'Author', 1, 0, 'C');
        $this->Cell(52, 8, 'Genre', 1, 0, 'C');
        $this->Ln();
    }

    // Membuat footer tabel
    function Footer()
    {
        // Posisi 1,5 cm dari bawah
        $this->SetY(-15);
        // Setting font
        $this->SetFont('Arial', 'I', 8);
        // Nomor halaman
        $this->Cell(0, 10, 'Halaman ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    // Fungsi tambahan untuk menghitung jumlah baris yang dibutuhkan oleh teks
    function NbLines($w, $txt)
    {
        // Memperkirakan jumlah baris yang dibutuhkan oleh teks dalam sebuah cell dengan lebar tertentu
        $cw = &$this->CurrentFont['cw'];
        if ($w == 0)
            $w = $this->w - $this->rMargin - $this->x;
        $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
        $s = str_replace("\r", '', $txt);
        $nb = strlen($s);
        if ($nb > 0 && $s[$nb - 1] == "\n")
            $nb--;
        $sep = -1;
        $i = 0;
        $j = 0;
        $l = 0;
        $nl = 1;
        while ($i < $nb) {
            $c = $s[$i];
            if ($c == "\n") {
                $i++;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
                continue;
            }
            if ($c == ' ')
                $sep = $i;
            $l += $cw[$c];
            if ($l > $wmax) {
                if ($sep == -1) {
                    if ($i == $j)
                        $i++;
                } else
                    $i = $sep + 1;
                $sep = -1;
                $j = $i;
                $l = 0;
                $nl++;
            } else
                $i++;
        }
        return $nl;
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

    // Tentukan tinggi cell
    $cellHeight = 20; // Tinggi cell yang ditentukan oleh cell poster

    // Hitung jumlah baris yang diperlukan untuk judul novel dan genre
    $judulNovelLines = $pdf->NbLines(65.5, htmlspecialchars($row['judul_novel']));
    $genreLines = $pdf->NbLines(52, $genre);
    $maxLines = max($judulNovelLines, $genreLines);
    $rowHeight = max($cellHeight, $maxLines * 5);

    // Menampilkan ID novel
    $pdf->Cell(8, $rowHeight, $row['id_novel'], 1, 0, 'C');

    // Menampilkan poster novel
    $pdf->Cell(15, $rowHeight, $pdf->Image("../images/" . str_replace('=', '"', str_replace("_", "'", $row['poster'])), $pdf->GetX(), $pdf->GetY(), 15, 20, 'JPG'), 1, 0, 'C');

    // Menampilkan judul novel
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(65.5, 5, htmlspecialchars($row['judul_novel']), 1);
    $pdf->SetXY($x + 65.5, $y);

    // Menampilkan tahun rilis
    $pdf->Cell(17.5, $rowHeight, $row['tahun_rilis'], 1);

    // Menampilkan nama author
    $pdf->Cell(32.5, $rowHeight, htmlspecialchars($row['nama_author']), 1);

    // Menampilkan genre
    $x = $pdf->GetX();
    $y = $pdf->GetY();
    $pdf->MultiCell(52, 5, $genre, 1);
    $pdf->SetXY($x + 52, $y);

    // Pindah ke baris berikutnya
    $pdf->Ln($rowHeight);
}

// Menutup koneksi database
$conn->close();

// Output file PDF
$pdf->Output();
?>
