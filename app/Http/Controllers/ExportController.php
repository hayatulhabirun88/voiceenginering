<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voter;
use App\Models\Peserta;

// Import kelas yang diperlukan
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;

class ExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function export_excel()
    {
        // Membuat objek Spreadsheet baru
        $spreadsheet = new Spreadsheet();

        // Mengisi data ke dalam Spreadsheet
        $sheet = $spreadsheet->getActiveSheet();
        // Menggabungkan sel A1 sampai D1
        $sheet->mergeCells('A1:F1');

        // Mengisi data pada sel yang digabungkan
        $sheet->setCellValue('A1', 'Perolehan Nilai dari para Voters The Voice of Engineer Universitas Muhammadiyah Sumatra Barat');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(12);
        $sheet->getStyle('A1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Membuat judul
        $sheet->setCellValue('A2', 'No')->getColumnDimension('A')->setWidth('5');
        $sheet->setCellValue('B2', 'Nama Voter')->getColumnDimension('B')->setWidth('30');
        $sheet->setCellValue('C2', 'Id Telegram Voter')->getColumnDimension('C')->setWidth('25');
        $sheet->setCellValue('D2', 'Kode Peserta')->getColumnDimension('D')->setWidth('15');
        $sheet->setCellValue('E2', 'Nama Peserta')->getColumnDimension('E')->setWidth('30');
        $sheet->setCellValue('F2', 'Nilai')->getColumnDimension('F')->setWidth('10');

        $sheet->getStyle('A2:F2')->getFont()->setBold(true);
        $sheet->getStyle('A2:F2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Mendapatkan data dari database atau sumber lain
        $data = Voter::join('peserta', 'voter.peserta_id', '=', 'peserta.id')
        ->get();

        // Menulis data ke dalam Spreadsheet
        $row = 3;
        $no = 1;
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item->nama_voter);
            $sheet->setCellValue('C' . $row, $item->telegram_id);
            $sheet->setCellValue('D' . $row, $item->kode);
            $sheet->setCellValue('E' . $row, $item->nama_peserta);
            $sheet->setCellValue('F' . $row, $item->nilai);
            $row++;
        }

        // Membuat objek Style
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];
        // Mengatur gaya border pada sel A1
        $sheet->getStyle('A2:F' . ($no+1))->applyFromArray($styleArray);

        // Membuat objek Writer untuk menyimpan Spreadsheet ke file Excel
        $writer = new Xlsx($spreadsheet);

        // Menyimpan file Excel
        $filename = 'hasil-voting'.date('Y-m-d').'.xlsx';
        $writer->save($filename);

        // Mengirimkan file Excel sebagai respons download
        return response()->download($filename)->deleteFileAfterSend(true);
    }

    public function export_nilai(){

        

        // Membuat objek Spreadsheet baru
        $spreadsheet = new Spreadsheet();

        // Mengisi data ke dalam Spreadsheet
        $sheet = $spreadsheet->getActiveSheet();
        // Menggabungkan sel A1 sampai D1
        $sheet->mergeCells('A1:F1');

        // Mengisi data pada sel yang digabungkan
        $sheet->setCellValue('A1', 'Perolehan Nilai Rata-rata dari Voters The Voice of Engineer Universitas Muhammadiyah Sumatra Barat');
        $sheet->getStyle('A1')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setSize(12);
        $sheet->getStyle('A1')->getAlignment()->setWrapText(true);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(30);

        // Membuat judul
        $sheet->setCellValue('A2', 'No')->getColumnDimension('A')->setWidth('5');
        $sheet->setCellValue('B2', 'Nama Peserta')->getColumnDimension('B')->setWidth('30');
        $sheet->setCellValue('C2', 'Fakultas')->getColumnDimension('C')->setWidth('25');
        $sheet->setCellValue('D2', 'Kategori')->getColumnDimension('D')->setWidth('15');
        $sheet->setCellValue('E2', 'Jumlah Voters')->getColumnDimension('E')->setWidth('30');
        $sheet->setCellValue('F2', 'Nilai')->getColumnDimension('F')->setWidth('10');

        $sheet->getStyle('A2:F2')->getFont()->setBold(true);
        $sheet->getStyle('A2:F2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $peserta = Peserta::all();

        // Menulis data ke dalam Spreadsheet
        $row = 3;
        $no = 1;
        foreach ($peserta as $item) {

            $jmlvote = \App\Models\Voter::where('peserta_id', $item->id)->count();
            $jmlnilai = \App\Models\Voter::where('peserta_id', $item->id)->sum('nilai');
            if($jmlvote == 0 ){
                $nilai = 0;
            } else {
                $nilai = number_format(($jmlnilai / $jmlvote), 2) ;
            }

            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $item->nama_peserta);
            $sheet->setCellValue('C' . $row, $item->fakultas);
            $sheet->setCellValue('D' . $row, $item->kategori);
            $sheet->setCellValue('E' . $row, $jmlvote);
            $sheet->setCellValue('F' . $row, $nilai);
            $row++;
        }

        // Membuat objek Style
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
        ];
        // Mengatur gaya border pada sel A1
        $sheet->getStyle('A2:F' . ($no+1))->applyFromArray($styleArray);

        // Membuat objek Writer untuk menyimpan Spreadsheet ke file Excel
        $writer = new Xlsx($spreadsheet);

        // Menyimpan file Excel
        $filename = 'hasil-daftar-nilai-rata-rata'.date('Y-m-d').'.xlsx';
        $writer->save($filename);

        // Mengirimkan file Excel sebagai respons download
        return response()->download($filename)->deleteFileAfterSend(true);
    }

    public function report_data_peserta(){
             // Membuat objek Spreadsheet baru
             $spreadsheet = new Spreadsheet();

             // Mengisi data ke dalam Spreadsheet
             $sheet = $spreadsheet->getActiveSheet();
             // Menggabungkan sel A1 sampai D1
             $sheet->mergeCells('A1:G1');
     
             // Mengisi data pada sel yang digabungkan
             $sheet->setCellValue('A1', 'Peserta Voters The Voice of Engineer Universitas Muhammadiyah Sumatra Barat');
             $sheet->getStyle('A1')->getFont()->setBold(true);
             $sheet->getStyle('A1')->getFont()->setSize(12);
             $sheet->getStyle('A1')->getAlignment()->setWrapText(true);
             $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
             $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::VERTICAL_CENTER);
             $sheet->getRowDimension(1)->setRowHeight(30);
     
             // Membuat judul
             $sheet->setCellValue('A2', 'No')->getColumnDimension('A')->setWidth('5');
             $sheet->setCellValue('B2', 'Nama Peserta')->getColumnDimension('B')->setWidth('30');
             $sheet->setCellValue('C2', 'Jenis Kelamin')->getColumnDimension('C')->setWidth('25');
             $sheet->setCellValue('D2', 'Fakultas')->getColumnDimension('D')->setWidth('20');
             $sheet->setCellValue('E2', 'Kategori')->getColumnDimension('E')->setWidth('30');
             $sheet->setCellValue('F2', 'Kode')->getColumnDimension('F')->setWidth('10');
             $sheet->setCellValue('G2', 'Alamat')->getColumnDimension('G')->setWidth('60');
     
             $sheet->getStyle('A2:G2')->getFont()->setBold(true);
             $sheet->getStyle('A2:G2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
     
             $peserta = Peserta::all();
     
             // Menulis data ke dalam Spreadsheet
             $row = 3;
             $no = 1;
             foreach ($peserta as $item) {
                 $sheet->setCellValue('A' . $row, $no++);
                 $sheet->setCellValue('B' . $row, $item->nama_peserta);
                 $sheet->setCellValue('C' . $row, $item->jenis_kelamin);
                 $sheet->setCellValue('D' . $row, $item->fakultas);
                 $sheet->setCellValue('E' . $row, $item->kategori);
                 $sheet->setCellValue('F' . $row, $item->kode);
                 $sheet->setCellValue('G' . $row, $item->alamat);
                 $row++;
             }
     
             // Membuat objek Style
             $styleArray = [
                 'borders' => [
                     'allBorders' => [
                         'borderStyle' => Border::BORDER_THIN,
                         'color' => ['argb' => '000000'],
                     ],
                 ],
             ];
             // Mengatur gaya border pada sel A1
             $sheet->getStyle('A2:G' . ($no+1))->applyFromArray($styleArray);
     
             // Membuat objek Writer untuk menyimpan Spreadsheet ke file Excel
             $writer = new Xlsx($spreadsheet);
     
             // Menyimpan file Excel
             $filename = 'peserta-'.date('Y-m-d').'.xlsx';
             $writer->save($filename);
     
             // Mengirimkan file Excel sebagai respons download
             return response()->download($filename)->deleteFileAfterSend(true);
    }
}
