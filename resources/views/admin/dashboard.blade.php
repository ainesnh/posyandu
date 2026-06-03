@extends('layouts.adminlte')

@section('title', 'Dashboard Klasifikasi Risiko')

@section('content')

<div class="box box-primary">
    <div class="box-header with-border"><h3 class="box-title">Filter Data</h3></div>
    <div class="box-body">
        <form method="GET" id="filterForm" onsubmit="return false;">
            <div class="row">
                <div class="col-md-3">
                    <label>Tahun</label>
                    <select name="tahun" id="tahunSelect" class="form-control" onchange="filterData(true);">
                        @foreach($tahunList as $item)
                            <option value="{{ $item }}" {{ $tahun == $item ? 'selected' : '' }}>
                                {{ $item }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Periode</label>
                    <select name="periode" id="periodeSelect" class="form-control" onchange="filterData(false);">
                        @foreach($periodeList as $item)
                            <option value="{{ $item }}" {{ $periode == $item ? 'selected' : '' }}>
                                {{ $item }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3 id="totalRTCount">{{ $totalRT }}</h3>
                <p>Total RT Dipantau</p>
            </div>
            <div class="icon"><i class="fa fa-home"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3 id="risikoRendahCount">{{ $risikoRendah }}</h3>
                <p>RT Risiko Rendah</p>
            </div>
            <div class="icon"><i class="fa fa-check-circle"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3 id="risikoSedangCount">{{ $risikoSedang }}</h3>
                <p>RT Risiko Sedang</p>
            </div>
            <div class="icon"><i class="fa fa-exclamation-circle"></i></div>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
            <div class="inner">
                <h3 id="risikoTinggiCount">{{ $risikoTinggi }}</h3>
                <p>RT Risiko Tinggi</p>
            </div>
            <div class="icon"><i class="fa fa-warning"></i></div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="box box-success">
            <div class="box-header with-border"><h3 class="box-title">Distribusi Risiko RT</h3></div>
            <div class="box-body" style="max-width: 350px; margin:auto;">
                <canvas id="riskChart" height="40"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box box-danger">
            <div class="box-header with-border"><h3 class="box-title">RT Prioritas</h3></div>
            <div id="prioritasContainer" class="box-body" style="max-height: 350px; overflow-y: auto;">
                @foreach($wilayahPrioritas as $item)
                    <div style="margin-bottom:10px;">
                        <strong>RW {{ $item['rw'] }} - RT {{ $item['rt'] }}</strong>
                        @if($item['risiko'] == 'Sedang')
                            <span class="label label-warning pull-right">Sedang</span>
                        @else
                            <span class="label label-danger pull-right">Tinggi</span>
                        @endif
                        <br>
                    </div>
                    <hr>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border"><h3 class="box-title">Hasil Klasifikasi Per RT</h3></div>
            <div class="box-body table-responsive">
                <table id="tabelHasilKlasifikasi" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>RW</th>
                            <th>RT</th>
                            <th>Total Rumah</th>
                            <th>Risiko</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        @foreach($tabelKlasifikasi as $item)
                        <tr>
                            <td>{{ $item['rw'] }}</td>
                            <td>{{ $item['rt'] }}</td>
                            <td>{{ $item['rumah'] }}</td>
                            <td>
                                @if($item['risiko'] == 'Rendah')
                                    <span class="label label-success">Rendah</span>
                                @elseif($item['risiko'] == 'Sedang')
                                    <span class="label label-warning">Sedang</span>
                                @else
                                    <span class="label label-danger">Tinggi</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')


<script>
    let riskChart;
    let dataTableInstance = null;

    $(document).ready(function () {
        // Inisialisasi Chart
        const ctx = document.getElementById('riskChart');
        if (ctx) {
            riskChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Rendah', 'Sedang', 'Tinggi'],
                    datasets: [{
                        data: [{{ $risikoRendah }}, {{ $risikoSedang }}, {{ $risikoTinggi }}],
                        backgroundColor: ['#22c55e', '#f59e0b', '#ef4444']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }

        initDataTable();
    });

    function initDataTable() {
        if ($.fn.DataTable) {
            if ($.fn.DataTable.isDataTable('#tabelHasilKlasifikasi')) {
                $('#tabelHasilKlasifikasi').DataTable().destroy();
            }
            
            dataTableInstance = $('#tabelHasilKlasifikasi').DataTable({
                "paging": true,
                "pageLength": 10,
                "lengthChange": true,
                "ordering": true,
                "info": true, 
                "autoWidth": false,
                "retrieve": true 
            });
        } else {
            console.error("Gagal memuat library DataTables. Pastikan jQuery sudah aktif.");
        }
    }

    function filterData(isTahunChanged = false) {
        const tahun = document.getElementById('tahunSelect').value;
        const periodeSelect = document.getElementById('periodeSelect');
        let periode = periodeSelect.value;

        if (isTahunChanged) {
            periode = '';
        }

        const url = new URL(window.location.href);
        url.searchParams.set('tahun', tahun);
        if (periode) {
            url.searchParams.set('periode', periode);
        } else {
            url.searchParams.delete('periode');
        }

        window.history.pushState({}, '', url);

        fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
            .then(response => response.json())
            .then(data => {
                
            if (isTahunChanged) {
                periodeSelect.innerHTML = '';
                data.periodeList.forEach(p => {
                    const option = document.createElement('option');
                    option.value = p;
                    option.textContent = p;
                    if (p === data.periode) option.selected = true;
                    periodeSelect.appendChild(option);
                });
            }

            // Perbarui Angka Card Box
            document.getElementById('totalRTCount').textContent = data.totalRT;
            document.getElementById('risikoRendahCount').textContent = data.risikoRendah;
            document.getElementById('risikoSedangCount').textContent = data.risikoSedang;
            document.getElementById('risikoTinggiCount').textContent = data.risikoTinggi;

            // Perbarui Chart
            if (riskChart) {
                riskChart.data.datasets[0].data = [data.risikoRendah, data.risikoSedang, data.risikoTinggi];
                riskChart.update();
            }

            // Perbarui Box Prioritas
            const prioritasContainer = document.getElementById('prioritasContainer');
            prioritasContainer.innerHTML = '';
            data.wilayahPrioritas.forEach(item => {
                let badge = item.risiko === 'Sedang' ? 'label-warning' : 'label-danger';
                prioritasContainer.innerHTML += `
                    <div style="margin-bottom:10px;">
                        <strong>RW ${item.rw} - RT ${item.rt}</strong>
                        <span class="label ${badge} pull-right">${item.risiko}</span>
                        <br>
                    </div>
                    <hr>
                `;
            });

            if ($.fn.DataTable && $.fn.DataTable.isDataTable('#tabelHasilKlasifikasi')) {
                $('#tabelHasilKlasifikasi').DataTable().destroy();
            }

            const tableBody = document.getElementById('tableBody');
            tableBody.innerHTML = '';
            data.tabelKlasifikasi.forEach(item => {
                let badge = item.risiko === 'Rendah' ? 'label-success' : (item.risiko === 'Sedang' ? 'label-warning' : 'label-danger');
                tableBody.innerHTML += `
                    <tr>
                        <td>${item.rw}</td>
                        <td>${item.rt}</td>
                        <td>${item.rumah}</td>
                        <td><span class="label ${badge}">${item.risiko}</span></td>
                    </tr>
                `;
            });

            initDataTable();
        });
    }
</script>
@endpush