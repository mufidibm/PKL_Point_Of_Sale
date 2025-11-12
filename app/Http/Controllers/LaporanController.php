<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPenjualan;
use App\Models\TransaksiPembelian;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'penjualan'); // default penjualan
        $mulai = $request->tanggal_mulai;
        $selesai = $request->tanggal_selesai;

        // Data
        $penjualan = $this->getPenjualan($mulai, $selesai);
        $pembelian = $this->getPembelian($mulai, $selesai);

        // Grafik Data
        $chartPenjualan = $this->chartData($penjualan->groupBy('tanggal'), 'total_bayar');
        $chartPembelian = $this->chartData($pembelian->groupBy('tanggal'), 'total_bayar');

        return view('admin.laporan.index', compact(
            'type', 'penjualan', 'pembelian',
            'chartPenjualan', 'chartPembelian',
            'mulai', 'selesai'
        ));
    }

    private function getPenjualan($mulai = null, $selesai = null)
    {
        $query = TransaksiPenjualan::with(['pelanggan.membership', 'karyawan']);
        if ($mulai) $query->whereDate('tanggal', '>=', $mulai);
        if ($selesai) $query->whereDate('tanggal', '<=', $selesai);
        return $query->get();
    }

    private function getPembelian($mulai = null, $selesai = null)
    {
        $query = TransaksiPembelian::with(['supplier', 'karyawan']);
        if ($mulai) $query->whereDate('tanggal', '>=', $mulai);
        if ($selesai) $query->whereDate('tanggal', '<=', $selesai);
        return $query->get();
    }

    private function chartData($data, $field)
    {
        return $data->map(function ($item, $date) use ($field) {
            return ['date' => $date, 'total' => $item->sum($field)];
        })->values();
    }

    public function export(Request $request, $type)
    {
        $mulai = $request->tanggal_mulai;
        $selesai = $request->tanggal_selesai;
        $format = $request->input('format'); // pdf atau excel

        $data = $type === 'penjualan'
            ? $this->getPenjualan($mulai, $selesai)
            : $this->getPembelian($mulai, $selesai);

        $title = ucfirst($type) . " - " . ($mulai ? $mulai : 'Semua') . " s/d " . ($selesai ? $selesai : 'Sekarang');

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('admin.laporan.pdf', compact('data', 'type', 'title'));
            return $pdf->download("laporan_{$type}.pdf");
        }

        // Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        if ($type === 'penjualan') {
            $sheet->fromArray(['No', 'Invoice', 'Tanggal', 'Pelanggan', 'Subtotal', 'Diskon', 'Total'], null, 'A1');
            foreach ($data as $i => $t) {
                $sheet->fromArray([
                    $i + 1,
                    $t->no_invoice,
                    $t->tanggal->format('d/M/Y'),
                    $t->pelanggan?->nama ?? '-',
                    $t->subtotal,
                    $t->diskon,
                    $t->total_bayar
                ], null, "A" . ($i + 2));
            }
        } else {
            $sheet->fromArray(['No', 'PO', 'Tanggal', 'Supplier', 'Total'], null, 'A1');
            foreach ($data as $i => $t) {
                $sheet->fromArray([
                    $i + 1, $t->no_po, $t->tanggal->format('d/m/Y'),
                    $t->supplier?->nama ?? '-', $t->total_bayar
                ], null, "A" . ($i + 2));
            }
        }

        $writer = new Xlsx($spreadsheet);
        $filename = "laporan_{$type}.xlsx";

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename);
    }
}