<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin — TRENDUP</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('css/admin/dashboard.css') }}" rel="stylesheet">
</head>
<body>

<div class="admin-layout">

  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">TREND<span>UP</span> <span style="font-size:12px;color:var(--cool-gray);display:block;letter-spacing:2px;margin-top:2px;">ADMIN PANEL</span></div>

    <span class="sidebar-section-label">Menu Utama</span>
    <ul class="sidebar-nav">
      <li><a href="#" class="active"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
      <li><a href="{{ route('admin.produk') }}"><i class="fa-solid fa-box"></i> Produk</a></li>
      <li><a href="{{ route('admin.kategori') }}"><i class="fa-solid fa-tags"></i> Kategori</a></li>
      <li>
        <a href="{{ route('admin.pesanan') }}">
          <i class="fa-solid fa-receipt"></i> Pesanan
          @if($pendingOrdersBadge > 0)
          <span class="badge rounded-pill" style="background:var(--lime);color:var(--jet);font-size:10px;margin-left:auto;">{{ $pendingOrdersBadge }}</span>
          @endif
        </a>
      </li>
      <li><a href="{{ route('admin.pelanggan') }}"><i class="fa-solid fa-users"></i> Pelanggan</a></li>
      <li><a href="{{ route('admin.ulasan') }}"><i class="fa-solid fa-star"></i> Ulasan</a></li>
      <li><a href="{{ route('admin.laporan') }}"><i class="fa-solid fa-chart-line"></i> Laporan Penjualan</a></li>
    </ul>

    <span class="sidebar-section-label">Lainnya</span>
    <ul class="sidebar-nav">
      <li><a href="{{ route('admin.pengaturan') }}"><i class="fa-solid fa-gear"></i> Pengaturan</a></li>
      <li><a href="{{ route('home') }}"><i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Website</a></li>
    </ul>

    <div class="sidebar-bottom">
      <div class="admin-mini">
        <div class="admin-avatar">{{ auth()->user()->initials() }}</div>
        <div>
          <div class="admin-name">{{ auth()->user()->name }}</div>
          <div class="admin-role">{{ auth()->user()->email }}</div>
        </div>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
      </form>
    </div>
  </aside>

  <!-- ===== MAIN CONTENT ===== -->
  <div class="main-content">

    <div class="topbar">
      <div class="d-flex align-items-center gap-3">
        <button class="btn d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('open')"><i class="fa-solid fa-bars"></i></button>
        <div>
          <h1>Dashboard</h1>
          <span class="date-today" id="todayDate"></span>
        </div>
      </div>
      <div class="d-flex align-items-center gap-3">
        <div class="topbar-search d-none d-md-flex">
          <i class="fa-solid fa-magnifying-glass" style="color:var(--cool-gray);font-size:13px;"></i>
          <input type="text" placeholder="Cari pesanan, produk...">
        </div>
        <button class="topbar-icon"><i class="fa-solid fa-bell"></i><span class="dot"></span></button>
      </div>
    </div>

    <div class="content-pad">

      <!-- ===== STAT CARDS ===== -->
      <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
          <div class="stat-card accent">
            <div class="stat-icon"><i class="fa-solid fa-box-open"></i></div>
            <div class="stat-label">Total Produk</div>
            <div class="stat-value">{{ $statProduk['total'] }}</div>
            <div class="stat-trend up"><i class="fa-solid fa-arrow-up"></i> {{ $statProduk['trendText'] }}</div>
          </div>
        </div>
        <div class="col-6 col-lg-3">
          <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-receipt"></i></div>
            <div class="stat-label">Total Pesanan</div>
            <div class="stat-value">{{ $statPesanan['total'] }}</div>
            <div class="stat-trend {{ $statPesanan['trend']['direction'] }}">
              <i class="fa-solid {{ $statPesanan['trend']['direction'] === 'down' ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
              {{ $statPesanan['trend']['text'] }}
            </div>
          </div>
        </div>
        <div class="col-6 col-lg-3">
          <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-hourglass-half"></i></div>
            <div class="stat-label">Menunggu Pembayaran</div>
            <div class="stat-value">{{ $statPending['total'] }}</div>
            <div class="stat-trend down"><i class="fa-solid fa-triangle-exclamation"></i> Perlu ditinjau</div>
          </div>
        </div>
        <div class="col-6 col-lg-3">
          <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-sack-dollar"></i></div>
            <div class="stat-label">Pendapatan Bulan Ini</div>
            <div class="stat-value" style="font-size:24px;">{{ $statRevenue['formatted'] }}</div>
            <div class="stat-trend {{ $statRevenue['trend']['direction'] }}">
              <i class="fa-solid {{ $statRevenue['trend']['direction'] === 'down' ? 'fa-arrow-down' : 'fa-arrow-up' }}"></i>
              {{ $statRevenue['trend']['text'] }}
            </div>
          </div>
        </div>
      </div>

      <div class="row g-3 mb-4">
        <!-- ===== CHART PENJUALAN ===== -->
        <div class="col-lg-8">
          <div class="panel">
            <div class="panel-head">
              <h3>Penjualan 7 Hari Terakhir</h3>
              <a href="{{ route('admin.laporan') }}" class="panel-link">Lihat Semua</a>
            </div>
            <div class="chart-bars">
              @php $maxVal = max(1, $salesChartData->max('value')); @endphp
              @foreach($salesChartData as $d)
                <div class="chart-bar-col">
                  <div class="chart-bar" style="height:{{ (int) round($d['value'] / $maxVal * 140) }}px;" title="Rp {{ number_format($d['value'], 0, ',', '.') }}"></div>
                  <div class="chart-bar-label">{{ $d['day'] }}</div>
                </div>
              @endforeach
            </div>
          </div>
        </div>

        <!-- ===== STOK MENIPIS ===== -->
        <div class="col-lg-4">
          <div class="panel">
            <div class="panel-head">
              <h3>Stok Menipis</h3>
              <a href="{{ route('admin.produk') }}" class="panel-link">Kelola</a>
            </div>
            <div>
              @forelse($lowStockData as $p)
                <div class="stock-item">
                  <span class="name">{{ $p['name'] }}</span>
                  <span class="stock-pill">{{ $p['stock'] }} pcs</span>
                </div>
              @empty
                <p style="color:var(--cool-gray);font-size:13px;padding:12px 0;">Semua stok produk aman.</p>
              @endforelse
            </div>
          </div>
        </div>
      </div>

      <div class="row g-3">
        <!-- ===== PESANAN TERBARU ===== -->
        <div class="col-lg-8">
          <div class="panel">
            <div class="panel-head">
              <h3>Pesanan Terbaru</h3>
              <a href="{{ route('admin.pesanan') }}" class="panel-link">Lihat Semua</a>
            </div>
            <div style="overflow-x:auto;">
              @php
                $statusClassMap = [
                  'Menunggu Pembayaran' => 'status-menunggu',
                  'Diproses' => 'status-diproses',
                  'Dikirim' => 'status-dikirim',
                  'Selesai' => 'status-selesai',
                  'Dibatalkan' => 'status-dibatalkan',
                ];
              @endphp
              <table class="table-trendup">
                <thead>
                  <tr>
                    <th>No. Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($recentOrdersData as $o)
                    <tr>
                      <td class="order-id">{{ $o['id'] }}</td>
                      <td class="order-customer">{{ $o['customer'] }}</td>
                      <td class="order-date">{{ $o['date'] }}</td>
                      <td>Rp {{ number_format($o['total'], 0, ',', '.') }}</td>
                      <td><span class="status-badge {{ $statusClassMap[$o['status']] ?? '' }}">{{ $o['status'] }}</span></td>
                      <td><a href="{{ route('admin.pesanan') }}" class="row-action"><i class="fa-solid fa-eye"></i></a></td>
                    </tr>
                  @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada pesanan masuk.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- ===== PRODUK TERLARIS ===== -->
        <div class="col-lg-4">
          <div class="panel">
            <div class="panel-head">
              <h3>Produk Terlaris</h3>
              <a href="{{ route('admin.produk') }}" class="panel-link">Lihat Semua</a>
            </div>
            <div>
              @forelse($topProductsData as $p)
                <div class="top-product-item">
                  <div class="tp-thumb"><i class="{{ $p['icon'] }}"></i></div>
                  <div>
                    <div class="tp-name">{{ $p['name'] }}</div>
                    <div class="tp-cat">{{ $p['cat'] }}</div>
                  </div>
                  <div class="tp-sold">
                    <div class="num">{{ $p['sold'] }}</div>
                    <div class="label">Terjual</div>
                  </div>
                </div>
              @empty
                <p style="color:var(--cool-gray);font-size:13px;padding:12px 0;">Belum ada data penjualan.</p>
              @endforelse
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<script>
const today = new Date();
document.getElementById("todayDate").textContent = today.toLocaleDateString("id-ID", {weekday:'long', day:'numeric', month:'long', year:'numeric'});
</script>
</body>
</html>