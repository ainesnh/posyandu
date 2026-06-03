@extends('layouts.adminlte')

@section('title', 'Input Data Pemeriksaan Jentik')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-success" style="border-radius: 8px; border-top-width: 3px;">
                <div class="box-header with-border">
                    <h3 class="box-title" style="font-weight: 700; color: #064e3b;">
                        <i class="fa fa-edit"></i> Form Pemeriksaan Lapangan
                    </h3>
                </div>
                <form method="POST" action="{{ route('admin.pemeriksaan-jentik.preview') }}">
                    @csrf
                    <div class="box-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>Periksa kembali data input.</strong>
                                <ul style="margin-bottom: 0;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Tanggal Pemeriksaan</label>
                                <input type="date" name="transdate" class="form-control" value="{{ old('transdate', date('Y-m-d')) }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Periode</label>
                                <select name="periode_id" class="form-control" required>
                                    <option value="">-- Pilih Periode --</option>
                                    @foreach($periode as $p)
                                        <option value="{{ $p->periode_id }}"
                                            {{ old('periode_id', $latestPeriode->periode_id ?? '') == $p->periode_id ? 'selected' : '' }}>
                                            {{ $p->name }} ({{ $p->startdate }} - {{ $p->enddate }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>RW</label>
                                <input type="text" name="rw" class="form-control" value="{{ old('rw') }}" placeholder="Contoh: 03" required>
                            </div>

                            <div class="col-md-6 form-group">
                                <label>RT</label>
                                <input type="text" name="rt" class="form-control" value="{{ old('rt') }}" placeholder="Opsional">
                            </div>
                        </div>

                        <hr>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Jumlah Rumah Diperiksa</label>
                                <input type="number" name="jumlah_rumah_diperiksa" class="form-control" min="1" value="{{ old('jumlah_rumah_diperiksa') }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Jumlah Rumah Positif Jentik</label>
                                <input type="number" name="jumlah_rumah_positif" class="form-control" min="0" value="{{ old('jumlah_rumah_positif') }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label>Jumlah Kontainer Diperiksa</label>
                                <input type="number" name="jumlah_kontainer_diperiksa" class="form-control" min="1" value="{{ old('jumlah_kontainer_diperiksa') }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label>Jumlah Kontainer Positif Jentik</label>
                                <input type="number" name="jumlah_kontainer_positif" class="form-control" min="0" value="{{ old('jumlah_kontainer_positif') }}" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Catatan Lapangan</label>
                            <textarea name="note" class="form-control" rows="4" placeholder="Catatan tambahan, sumber genangan, atau tindak lanjut">{{ old('note') }}</textarea>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-disk"></i> Simpan
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-default">Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@if (session('hasil_klasifikasi'))
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const hasil = {!! json_encode(session('hasil_klasifikasi')) !!};

        console.log("=== LOG PERHITUNGAN DECISION TREE (FLEKSIBEL) ===");
        if (hasil && hasil.logs) {
            hasil.logs.forEach(log => console.log(log));
        }

        let color = '#6b7280';
        let icon = 'info';
        if (hasil.risiko === 'Rendah') { color = '#10b981'; icon = 'success'; }
        else if (hasil.risiko === 'Sedang') { color = '#f59e0b'; icon = 'warning'; }
        else if (hasil.risiko === 'Tinggi') { color = '#ef4444'; icon = 'error'; }

        Swal.fire({
            icon: icon,
            width: 520,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            confirmButtonColor: color,
            cancelButtonColor: '#6b7280',
            background: '#ffffff',
            customClass: {
                popup: 'swal-rounded'
            },
            html: `
                <div style="text-align:left; font-family: Arial; font-size:14px; margin-top:10px;">
                    <div style="padding:12px; border-radius:10px; background:${color}; color:white; text-align:center; font-weight:bold; margin-bottom:12px;">
                        Risiko ${hasil.risiko}
                    </div>
                    <div style="margin-bottom: 10px; font-size: 12px; color: #64748b; text-align: center;">
                        Tanggal Periksa: <strong>${hasil.transdate}</strong>
                    </div>
                    <div style="display:grid; grid-template-columns:1fr 1fr; gap:10px;">
                        <div style="background:#f8fafc;padding:10px;border-radius:8px;">
                            <small>Rumah Jentik</small>
                            <div style="font-weight:bold;">${hasil.rumah_positif}</div>
                        </div>
                        <div style="background:#f8fafc;padding:10px;border-radius:8px;">
                            <small>Kontainer Jentik</small>
                            <div style="font-weight:bold;">${hasil.kontainer_positif}</div>
                        </div>
                        <div style="background:#f8fafc;padding:10px;border-radius:8px;">
                            <small>Rumah Diperiksa</small>
                            <div style="font-weight:bold;">${hasil.rumah_diperiksa}</div>
                        </div>
                        <div style="background:#f8fafc;padding:10px;border-radius:8px;">
                            <small>Kontainer Diperiksa</small>
                            <div style="font-weight:bold;">${hasil.kontainer_diperiksa}</div>
                        </div>
                    </div>
                    <div style="margin-top:12px; padding:10px; background:#e0f2fe; border-radius:8px; font-size:12px; color:#0369a1; text-align:center;">
                        ${hasil.status}
                    </div>
                </div>
            `
        }).then((result) => {
            if (result.isConfirmed) {
                fetch("{{ route('admin.pemeriksaan.store') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify(hasil) 
                })
                .then(res => res.json())
                .then((data) => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Tersimpan!',
                            text: 'Data pemeriksaan berhasil disimpan ke database.',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => location.reload());
                    }
                });
            }
        });
    });
</script>

<style>
    .swal-rounded {
        border-radius: 14px !important;
    }
</style>
@endif
