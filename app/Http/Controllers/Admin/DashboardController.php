<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Klasifikasi;
use App\Models\Periode;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tahunIni = (int) date('Y');
        $tahunList = range($tahunIni, $tahunIni - 4);
        $tahunTerpilih = $request->has('tahun') ? (int) $request->get('tahun') : $tahunIni;

        $periodeData = Periode::whereYear('startdate', $tahunTerpilih)
            ->orderBy('periode_id', 'asc')
            ->get();
        
        $periodeList = $periodeData->pluck('name')->toArray();

        $periodeNamaTerpilih = $request->get('periode');
        $periodeIdTerpilih = null;

        if (!$periodeNamaTerpilih || !in_array($periodeNamaTerpilih, $periodeList)) {
            $periodeTerakhirDariList = $periodeData->last();
            if ($periodeTerakhirDariList) {
                $periodeIdTerpilih = $periodeTerakhirDariList->periode_id;
                $periodeNamaTerpilih = $periodeTerakhirDariList->name;
            }
        } else {
            $periodeModel = Periode::where('name', $periodeNamaTerpilih)
                ->whereYear('startdate', $tahunTerpilih)
                ->first();
            
            if ($periodeModel) {
                $periodeIdTerpilih = $periodeModel->periode_id;
            }
        }

        if ($periodeIdTerpilih) {
            $dataKlasifikasi = Klasifikasi::where('periode_id', $periodeIdTerpilih)->get();
        } else {
            $dataKlasifikasi = collect();
        }

        // Summary Data
        $totalRT = $dataKlasifikasi->count();
        $risikoRendah = $dataKlasifikasi->where('risiko', 'Rendah')->count();
        $risikoSedang = $dataKlasifikasi->where('risiko', 'Sedang')->count();
        $risikoTinggi = $dataKlasifikasi->where('risiko', 'Tinggi')->count();

        $totalRumah = $dataKlasifikasi->sum('rumah_diperiksa');
        $totalKontainer = $dataKlasifikasi->sum('kontainer_diperiksa');

        // Mapping Data
        $mappedData = $dataKlasifikasi->map(function ($item) {
            // Tinggi = 1, Sedang = 2, Rendah = 3
            $bobot = 3;
            if ($item->risiko === 'Tinggi') { $bobot = 1; }
            elseif ($item->risiko === 'Sedang') { $bobot = 2; }

            return [
                'rw' => sprintf('%02d', $item->rw), 
                'rt' => sprintf('%02d', $item->rt), 
                'rumah' => $item->rumah_diperiksa,
                'kontainer' => $item->kontainer_diperiksa,
                'risiko' => $item->risiko,
                'bobot' => $bobot
            ];
        });

        // DATA PRIORITAS
        $wilayahPrioritas = $mappedData->filter(function ($item) {
            return in_array($item['risiko'], ['Tinggi', 'Sedang']);
        })->sortBy('bobot')->values()->toArray();

        // TABEL KLASIFIKASI:
        $tabelKlasifikasi = $mappedData->sortBy('bobot')->values()->toArray();

        if ($request->ajax()) {
            return response()->json([
                'tahun' => $tahunTerpilih,
                'periode' => $periodeNamaTerpilih,
                'periodeList' => $periodeList,
                'totalRT' => $totalRT,
                'risikoRendah' => $risikoRendah,
                'risikoSedang' => $risikoSedang,
                'risikoTinggi' => $risikoTinggi,
                'wilayahPrioritas' => $wilayahPrioritas,
                'tabelKlasifikasi' => $tabelKlasifikasi,
            ]);
        }

        return view('admin.dashboard', [
            'tahun' => $tahunTerpilih,
            'tahunList' => $tahunList,
            'periode' => $periodeNamaTerpilih,
            'periodeList' => $periodeList,
            'totalRT' => $totalRT,
            'totalRumah' => $totalRumah,
            'totalKontainer' => $totalKontainer,
            'risikoRendah' => $risikoRendah,
            'risikoSedang' => $risikoSedang,
            'risikoTinggi' => $risikoTinggi,
            'wilayahPrioritas' => $wilayahPrioritas,
            'tabelKlasifikasi' => $tabelKlasifikasi,
        ]);
    }
}