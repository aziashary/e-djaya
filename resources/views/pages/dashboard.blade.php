@extends('layouts.main')

@section('judul', 'Dashboard Wardjay')

@section('content')
<div class="container-fluid">
  <h4 class="mb-4 fw-bold text-primary">
  Dashboard Warkop Djaya
  </h4>

  <!-- Cards Section -->
  <div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
      <div class="card text-center p-3 shadow-sm">
        <h6>Jumlah Transaksi Hari Ini</h6>
        <h3 class="text-primary fw-bold">{{ $transaksi_hari_ini }}</h3>
      </div>
    </div>

    <div class="col-lg-3 col-md-6">
      <div class="card text-center p-3 shadow-sm">
        <h6>Jumlah Transaksi Bulan Ini</h6>
        <h3 class="text-success fw-bold">{{ $transaksi_bulan_ini }}</h3>
      </div>
    </div>

    <div class="col-lg-3 col-md-6">
      <div class="card text-center p-3 shadow-sm">
        <h6>Nilai Transaksi Hari Ini</h6>
        <h3 class="text-primary fw-bold">Rp{{ number_format($nilai_hari_ini, 0, ',', '.') }}</h3>
      </div>
    </div>

    <div class="col-lg-3 col-md-6">
      <div class="card text-center p-3 shadow-sm">
        <h6>Nilai Transaksi Bulan Ini</h6>
        <h3 class="text-success fw-bold">Rp{{ number_format($nilai_bulan_ini, 0, ',', '.') }}</h3>
      </div>
    </div>
  </div>

  <!-- Grafik Penjualan -->
  {{-- <div class="card mb-4 shadow-sm">
    <div class="card-header bg-light">
      <strong>Grafik Penjualan 7 Hari Terakhir</strong>
    </div>
    <div class="card-body">
      <canvas id="chartPenjualan" height="100"></canvas>
    </div>
  </div> --}}

  <!-- Tabel Transaksi Terbaru -->
  <div class="card shadow-sm">
    <div class="card-header bg-light">
      <strong>Transaksi Terbaru</strong>
    </div>
    <br
    <div class="card-body table-responsive">
      <table class="table table-bordered table-striped align-middle">
        <thead>
          <tr class="text-center">
            <th>#</th>
            <th>Kode</th>
            <th>Tanggal</th>
            <th>Total</th>
            <th>Kasir</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($transaksi_terbaru as $index => $t)
            <tr>
              <td class="text-center">{{ $index + 1 }}</td>
              <td>{{ $t->kode_transaksi }}</td>
              <td>{{ $t->created_at->format('d M Y H:i') }}</td>
              <td>Rp{{ number_format($t->total, 0, ',', '.') }}</td>
              <td>{{ $t->kasir->username ?? '-' }}</td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center text-muted">Belum ada transaksi</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection

@section('scripts')
  <!-- pastikan Chart.js sudah diload sebelum ini -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <script>
    document.getElementById('chartPenjualan') // harus bukan null

  document.addEventListener('DOMContentLoaded', function () {
    try {
      const canvas = document.getElementById('chartPenjualan');
      if (!canvas) {
        console.warn('Chart: element #chartPenjualan tidak ditemukan.');
        return;
      }

      const labels = @json($chart_labels ?? []);
      const values = @json($chart_values ?? []);

      if (!Array.isArray(labels) || !Array.isArray(values)) {
        console.error('Chart: data labels/values bukan array.', { labels, values });
        return;
      }

      // optional quick sanity: lengths should match
      if (labels.length !== values.length) {
        console.warn('Chart: jumlah labels dan values beda (boleh tapi cek data).', { labelsLength: labels.length, valuesLength: values.length });
      }

      // clear previous chart instance if exists (prevents duplicate charts on HMR / turbolinks)
      if (canvas._chartInstance) {
        canvas._chartInstance.destroy();
        canvas._chartInstance = null;
      }

      const ctx = canvas.getContext('2d');

      const config = {
        type: 'line',
        data: {
          labels: labels,
          datasets: [{
            label: 'Total Penjualan',
            data: values,
            borderWidth: 3,
            tension: 0.3,
            fill: false,
            pointRadius: 3,
            pointHoverRadius: 5
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false }
          },
          scales: {
            x: {
              ticks: { maxRotation: 0, autoSkip: true }
            },
            y: {
              beginAtZero: true,
              ticks: {
                callback: function(value) {
                  // format angka (IDR)
                  return 'Rp ' + value.toLocaleString('id-ID');
                }
              }
            }
          }
        }
      };

      canvas._chartInstance = new Chart(ctx, config);
    } catch (err) {
      console.error('Chart init error:', err);
    }
  });
  </script>
@endsection

