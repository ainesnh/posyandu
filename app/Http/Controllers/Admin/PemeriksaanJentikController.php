<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Models\Periode;
use App\Models\Klasifikasi;

class PemeriksaanJentikController extends Controller
{
    public function create(): View
    {
        $periode = Periode::orderBy('startdate', 'desc')->get();
        $latestPeriode = Periode::orderBy('startdate', 'desc')->first();

        return view('admin.pemeriksaan.create', [
            'periode' => $periode,
            'latestPeriode' => $latestPeriode
        ]);
    }

    public function preview(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'periode_id' => ['required', 'exists:periode,periode_id'],
            'transdate' => ['required', 'date'],
            'rw' => ['required', 'string', 'max:20'],
            'rt' => ['nullable', 'string', 'max:20'],
            'jumlah_rumah_diperiksa' => ['required', 'integer', 'min:1'],
            'jumlah_rumah_positif' => ['required', 'integer', 'min:0'],
            'jumlah_kontainer_diperiksa' => ['required', 'integer', 'min:1'],
            'jumlah_kontainer_positif' => ['required', 'integer', 'min:0'],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $inputData = [
            'rumah_positif' => $validated['jumlah_rumah_positif'],
            'kontainer_positif' => $validated['jumlah_kontainer_positif'],
            'rumah_diperiksa' => $validated['jumlah_rumah_diperiksa'],
            'kontainer_diperiksa' => $validated['jumlah_kontainer_diperiksa'],
        ];

        $allData = \App\Models\Klasifikasi::select('rumah_positif', 'kontainer_positif', 'rumah_diperiksa', 'kontainer_diperiksa', 'risiko')
            ->get()
            ->toArray();
            
        $logs = [];

        if (count($allData) >= 5) {
            shuffle($allData);
            $totalData = count($allData);
            $trainingCount = (int) round($totalData * 0.8);
            
            $trainingData = array_slice($allData, 0, $trainingCount);
            $testingData = array_slice($allData, $trainingCount);

            $logs[] = "=== PEMBAGIAN DATASET ===";
            $logs[] = "Total Dataset di DB: {$totalData} data.";
            $logs[] = "Data Training (80%) : " . count($trainingData) . " data.";
            $logs[] = "Data Testing (20%)  : " . count($testingData) . " data.\n";

            $features = ['rumah_positif', 'kontainer_positif', 'rumah_diperiksa', 'kontainer_diperiksa'];
            $logs[] = "=== PROSES TRAINING: MEMBENTUK STRUKTUR POHON KEPUTUSAN ===";
            
            // Membuat pohon keputusan dinamis
            $tree = $this->buildTree($trainingData, $features, $logs);

            // Menelusuri rule pohon
            $risiko = $this->classify($inputData, $tree);
        } else {
            $logs[] = "Data historis di DB terlalu sedikit (< 5) untuk training. Menggunakan rule default skripsi.";
            if ($inputData['rumah_positif'] <= 0.5) { $risiko = 'Rendah'; }
            elseif ($inputData['kontainer_positif'] > 10.5) { $risiko = 'Tinggi'; }
            elseif ($inputData['rumah_diperiksa'] <= 40) { $risiko = 'Sedang'; }
            else { $risiko = 'Rendah'; }
        }

        return back()
            ->withInput()
            ->with('hasil_klasifikasi', [
                'periode_id' => $validated['periode_id'],
                'risiko' => $risiko,
                'transdate' => $validated['transdate'],
                'rw' => $validated['rw'],
                'rt' => $validated['rt'],
                'rumah_positif' => $inputData['rumah_positif'],
                'kontainer_positif' => $inputData['kontainer_positif'],
                'rumah_diperiksa' => $inputData['rumah_diperiksa'],
                'kontainer_diperiksa' => $inputData['kontainer_diperiksa'],
                'status' => 'Klasifikasi siap disimpan.',
                'logs' => $logs
            ]);
    }

    private function buildTree($dataset, $features, &$logs, $depth = 1)
    {
        // Jika semua sisa sampel data memiliki kelas risiko yang sama 
        $classes = array_unique(array_column($dataset, 'risiko'));
        if (count($classes) === 1) {
            return ['is_leaf' => true, 'value' => reset($classes)];
        }

        // Jika sisa fitur habis, gunakan kelas terbanyak
        if (empty($features)) {
            return ['is_leaf' => true, 'value' => $this->getMajorityClass($dataset)];
        }

        $bestFeature = null;
        $bestThreshold = null;
        $maxGain = -1;
        $entropyAwal = $this->calculateEntropy($dataset);

        // Mencari Information Gain tertinggi
        foreach ($features as $feature) {
            $candidates = $this->getThresholdCandidates($dataset, $feature);
            foreach ($candidates as $th) {
                $left = array_filter($dataset, fn($row) => $row[$feature] <= $th);
                $right = array_filter($dataset, fn($row) => $row[$feature] > $th);
                
                if (empty($left) || empty($right)) continue;

                $total = count($dataset);
                $gain = $entropyAwal - ((count($left)/$total * $this->calculateEntropy($left)) + (count($right)/$total * $this->calculateEntropy($right)));
                
                if ($gain > $maxGain) {
                    $maxGain = $gain;
                    $bestFeature = $feature;
                    $bestThreshold = $th;
                }
            }
        }

        // Jika gain tidak mengalami kenaikan signifikan
        if ($maxGain <= 0 || $bestFeature === null) {
            return ['is_leaf' => true, 'value' => $this->getMajorityClass($dataset)];
        }

        // Tulis log pembentukan node
        $logs[] = " -> Node Level {$depth}: Terpilih Atribut [{$bestFeature}] dengan Batas <= {$bestThreshold} (Gain: " . round($maxGain, 4) . ")";

        // Pecah sisa data training berdasarkan threshold terpilih ke cabang kiri dan kanan
        $leftBranchData = array_values(array_filter($dataset, fn($row) => $row[$bestFeature] <= $bestThreshold));
        $rightBranchData = array_values(array_filter($dataset, fn($row) => $row[$bestFeature] > $bestThreshold));

        // Panggil fungsi ini kembali (Rekursif) untuk mendalami cabang kiri (<=) dan cabang kanan (>)
        return [
            'is_leaf' => false,
            'feature' => $bestFeature,
            'threshold' => $bestThreshold,
            'left' => $this->buildTree($leftBranchData, $features, $logs, $depth + 1),
            'right' => $this->buildTree($rightBranchData, $features, $logs, $depth + 1)
        ];
    }

    private function classify($input, $tree)
    {
        // Jika perjalanan menelusuri pohon sudah sampai di ujung daun (Leaf), ambil keputusan akhir
        if ($tree['is_leaf']) {
            return $tree['value'];
        }

        // Jalankan pengecekan aturan secara dinamis mengikuti arah objek tree
        if ($input[$tree['feature']] <= $tree['threshold']) {
            return $this->classify($input, $tree['left']);  // Belok ke cabang kiri
        } else {
            return $this->classify($input, $tree['right']); // Belok ke cabang kanan
        }
    }

    private function calculateEntropy($data)
    {
        $total = count($data);
        if ($total === 0) return 0;

        $counts = [];
        foreach ($data as $row) {
            $counts[$row['risiko']] = ($counts[$row['risiko']] ?? 0) + 1;
        }

        $entropy = 0;
        foreach ($counts as $classCount) {
            $p = $classCount / $total;
            $entropy -= $p * log($p, 2);
        }
        return $entropy;
    }

    private function getThresholdCandidates($data, $feature)
    {
        $values = array_column($data, $feature);
        sort($values);
        $uniqueValues = array_values(array_unique($values));

        $candidates = [];
        for ($i = 0; $i < count($uniqueValues) - 1; $i++) {
            $candidates[] = ($uniqueValues[$i] + $uniqueValues[$i + 1]) / 2;
        }
        return $candidates;
    }

    private function getMajorityClass($dataset)
    {
        if (empty($dataset)) return 'Rendah';
        $counts = array_count_values(array_column($dataset, 'risiko'));
        arsort($counts);
        return key($counts);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        Klasifikasi::create([
            'periode_id' => $data['periode_id'],
            'rw' => $data['rw'] ?? null,
            'rt' => $data['rt'] ?? null,
            'transdate' => $data['transdate'] ?? null,
            'rumah_positif' => $data['rumah_positif'],
            'kontainer_positif' => $data['kontainer_positif'],
            'rumah_diperiksa' => $data['rumah_diperiksa'],
            'kontainer_diperiksa' => $data['kontainer_diperiksa'],
            'risiko' => $data['risiko'],
        ]);

        return response()->json(['success' => true]);
    }
}