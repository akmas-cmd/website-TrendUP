<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Laporan Penjualan — TRENDUP Admin</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('css/admin/laporan.css') }}" rel="stylesheet">
</head>
<body>

<div class="admin-layout">

  <aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">TREND<span>UP</span> <span style="font-size:12px;color:var(--cool-gray);display:block;letter-spacing:2px;margin-top:2px;">ADMIN PANEL</span></div>

    <span class="sidebar-section-label">Menu Utama</span>
    <ul class="sidebar-nav">
      <li><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
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
      <li><a href="{{ route('admin.laporan') }}" class="active"><i class="fa-solid fa-chart-line"></i> Laporan Penjualan</a></li>
    </ul>

    <span class="sidebar-section-label">Lainnya</span>
    <ul class="sidebar-nav">
      <li><a href="{{ route('admin.pengaturan') }}"><i class="fa-solid fa-gear"></i> Pengaturan</a></li>
      <li><a href="{{ route('home') }}"><i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Website</a></li>
    </ul>

    <div class="sidebar-bottom">
      <div class="admin-mini">
        <div class="admin-avatar">A</div>
        <div>
          <div class="admin-name">Admin TRENDUP</div>
          <div class="admin-role">admin@trendup.com</div>
        </div>
      </div>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</button>
      </form>
    </div>
  </aside>

  <div class="main-content">
    <div class="topbar">
      <div class="d-flex align-items-center gap-3">
        <button class="btn d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('open')"><i class="fa-solid fa-bars"></i></button>
        <h1>Laporan Penjualan</h1>
      </div>
      <button class="btn-export" onclick="exportReport()"><i class="fa-solid fa-download"></i> Export CSV</button>
    </div>

    <div class="content-pad">

      <div class="generate-card">
        <div class="generate-head">
          <h3>Buat Laporan Baru</h3>
          <p>Pilih rentang tanggal untuk membuat laporan penjualan periode tertentu.</p>
        </div>
        <div class="generate-form">
          <div class="field">
            <label class="form-label-trendup">Dari Tanggal</label>
            <div class="date-field-wrap">
              <input type="date" class="form-control-trendup" id="startDate">
              <i class="fa-solid fa-calendar-days date-icon"></i>
            </div>
          </div>
          <div class="field">
            <label class="form-label-trendup">Sampai Tanggal</label>
            <div class="date-field-wrap">
              <input type="date" class="form-control-trendup" id="endDate">
              <i class="fa-solid fa-calendar-days date-icon"></i>
            </div>
          </div>
          <div class="field">
            <label class="form-label-trendup">Kategori</label>
            <div class="custom-select-wrap select-wrap-form" data-target="reportCategory">
              <button type="button" class="custom-select-toggle"><span>Semua Kategori</span><i class="fa-solid fa-chevron-down"></i></button>
              <ul class="custom-select-menu"></ul>
            </div>
            <select class="form-control-trendup" id="reportCategory" style="display:none;">
              <option value="">Semua Kategori</option>
              @foreach($categoriesData as $catLabel)
                <option>{{ $catLabel }}</option>
              @endforeach
            </select>
          </div>
          <button class="btn-generate" onclick="generateReport()"><i class="fa-solid fa-plus"></i> Buat Laporan</button>
        </div>
        <div class="error-msg" id="dateError">Tanggal mulai tidak boleh lebih besar dari tanggal akhir.</div>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
          <div class="stat-card accent">
            <div class="stat-icon"><i class="fa-solid fa-sack-dollar"></i></div>
            <div class="stat-label">Total Pendapatan</div>
            <div class="stat-value" id="statRevenue" style="font-size:22px;">Rp 0</div>
          </div>
        </div>
        <div class="col-6 col-lg-3">
          <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-receipt"></i></div>
            <div class="stat-label">Total Pesanan</div>
            <div class="stat-value" id="statOrders">0</div>
          </div>
        </div>
        <div class="col-6 col-lg-3">
          <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-cart-shopping"></i></div>
            <div class="stat-label">Rata-rata / Pesanan</div>
            <div class="stat-value" id="statAvg" style="font-size:20px;">Rp 0</div>
          </div>
        </div>
        <div class="col-6 col-lg-3">
          <div class="stat-card">
            <div class="stat-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
            <div class="stat-label">Produk Terjual</div>
            <div class="stat-value" id="statUnits">0</div>
          </div>
        </div>
      </div>

      <div class="row g-3 mb-4">
        <div class="col-lg-8">
          <div class="panel">
            <div class="panel-head">
              <h3 id="chartTitle">Grafik Pendapatan</h3>
            </div>
            <div class="chart-bars" id="chartBars"></div>
          </div>
        </div>
        <div class="col-lg-4">
          <div class="panel">
            <div class="panel-head">
              <h3>Produk Terlaris</h3>
            </div>
            <div id="topProductsList"></div>
          </div>
        </div>
      </div>

      <div class="panel mb-4">
        <div class="panel-head">
          <h3>Detail Transaksi</h3>
          <span class="result-count" id="transCount"></span>
        </div>
        <div style="overflow-x:auto;">
          <table class="table-trendup">
            <thead>
              <tr>
                <th>No. Pesanan</th>
                <th>Tanggal</th>
                <th>Kategori</th>
                <th>Item</th>
                <th>Total</th>
              </tr>
            </thead>
            <tbody id="transactionsBody"></tbody>
          </table>
        </div>
      </div>

      <div class="panel">
        <div class="panel-head">
          <h3>Riwayat Laporan Dibuat</h3>
        </div>
        <div style="overflow-x:auto;">
          <table class="table-trendup">
            <thead>
              <tr>
                <th>Periode</th>
                <th>Kategori</th>
                <th>Total Pendapatan</th>
                <th>Jumlah Pesanan</th>
                <th>Dibuat</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="savedReportsBody"></tbody>
          </table>
        </div>
      </div>

    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

const allTransactions = @json($transactionsData);
let savedReports = @json($savedReportsData);

function formatRupiah(num){ return "Rp " + num.toLocaleString("id-ID"); }

function formatDateLabel(dateStr){
  const d = new Date(dateStr);
  return d.toLocaleDateString("id-ID", {day:"numeric", month:"short"});
}

function getFilteredTransactions(start, end, cat){
  return allTransactions.filter(t => {
    const inRange = t.date >= start && t.date <= end;
    const matchCat = !cat || t.cat === cat;
    return inRange && matchCat;
  });
}

function renderReport(transactions, title){
  const revenue = transactions.reduce((a,b) => a + b.total, 0);
  const orders = transactions.length;
  const avg = orders > 0 ? Math.round(revenue / orders) : 0;
  const units = transactions.reduce((a,b) => a + b.items.split(",").length, 0);

  document.getElementById("statRevenue").textContent = formatRupiah(revenue);
  document.getElementById("statOrders").textContent = orders;
  document.getElementById("statAvg").textContent = formatRupiah(avg);
  document.getElementById("statUnits").textContent = units;
  document.getElementById("chartTitle").textContent = "Grafik Pendapatan — " + title;
  document.getElementById("transCount").textContent = orders + " transaksi";

  const grouped = {};
  transactions.forEach(t => {
    grouped[t.date] = (grouped[t.date] || 0) + t.total;
  });
  const days = Object.keys(grouped).sort();
  const maxVal = Math.max(...Object.values(grouped), 1);

  document.getElementById("chartBars").innerHTML = days.length
    ? days.map(d => `
        <div class="chart-bar-col">
          <div class="chart-bar" style="height:${(grouped[d]/maxVal*140).toFixed(0)}px;" title="${formatRupiah(grouped[d])}"></div>
          <div class="chart-bar-label">${formatDateLabel(d)}</div>
        </div>
      `).join("")
    : `<div class="empty-chart">Tidak ada data pada periode ini.</div>`;

  const productMap = {};
  transactions.forEach(t => {
    t.items.split(",").forEach(rawItem => {
      const name = rawItem.trim().replace(/\sx\d+$/, "");
      productMap[name] = (productMap[name] || 0) + 1;
    });
  });
  const topProducts = Object.entries(productMap).sort((a,b) => b[1]-a[1]).slice(0,5);

  document.getElementById("topProductsList").innerHTML = topProducts.length
    ? topProducts.map(([name, count]) => `
        <div class="top-product-item">
          <div class="tp-thumb"><i class="fa-solid fa-box"></i></div>
          <div class="tp-name">${name}</div>
          <div class="tp-sold">
            <div class="num">${count}</div>
            <div class="label">Terjual</div>
          </div>
        </div>
      `).join("")
    : `<div class="empty-chart">Belum ada data.</div>`;

  document.getElementById("transactionsBody").innerHTML = transactions.length
    ? transactions.map(t => `
        <tr>
          <td class="order-id">${t.id}</td>
          <td class="order-date">${formatDateLabel(t.date)}</td>
          <td><span class="prod-cat-tag">${t.cat}</span></td>
          <td class="trans-items">${t.items}</td>
          <td>${formatRupiah(t.total)}</td>
        </tr>
      `).join("")
    : `<tr><td colspan="5" class="text-center text-muted py-4">Tidak ada transaksi pada periode ini.</td></tr>`;

  return {revenue, orders};
}

async function generateReport(){
  const start = document.getElementById("startDate").value;
  const end = document.getElementById("endDate").value;
  const cat = document.getElementById("reportCategory").value;
  const errorMsg = document.getElementById("dateError");

  if(!start || !end){
    errorMsg.textContent = "Pilih tanggal mulai dan tanggal akhir terlebih dahulu.";
    errorMsg.classList.add("show");
    return;
  }
  if(start > end){
    errorMsg.textContent = "Tanggal mulai tidak boleh lebih besar dari tanggal akhir.";
    errorMsg.classList.add("show");
    return;
  }
  errorMsg.classList.remove("show");

  const transactions = getFilteredTransactions(start, end, cat);
  const title = formatDateLabel(start) + " - " + formatDateLabel(end);
  renderReport(transactions, title);

  try {
    const res = await fetch("{{ route('admin.laporan.store') }}", {
      method: "POST",
      headers: {
        "X-CSRF-TOKEN": csrfToken,
        "Accept": "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ start_date: start, end_date: end, category: cat || null }),
    });
    const data = await res.json();

    if(!res.ok){
      alert(data.message || "Gagal menyimpan laporan.");
      return;
    }

    savedReports.unshift(data.report);
    renderSavedReports();
  } catch (err) {
    alert("Gagal menyimpan laporan. Cek koneksi Anda.");
  }
}

function renderSavedReports(){
  const body = document.getElementById("savedReportsBody");
  body.innerHTML = savedReports.length
    ? savedReports.map((r) => `
        <tr>
          <td class="order-id">${r.period}</td>
          <td><span class="prod-cat-tag">${r.cat}</span></td>
          <td>${formatRupiah(r.revenue)}</td>
          <td>${r.orders}</td>
          <td class="order-date">${r.createdAt}</td>
          <td>
            <button class="action-btn danger" onclick="deleteReport(${r.id})"><i class="fa-solid fa-trash"></i></button>
          </td>
        </tr>
      `).join("")
    : `<tr><td colspan="6" class="text-center text-muted py-4">Belum ada laporan dibuat.</td></tr>`;
}

async function deleteReport(id){
  try {
    const res = await fetch(`/admin/laporan/${id}`, {
      method: "DELETE",
      headers: { "X-CSRF-TOKEN": csrfToken, "Accept": "application/json" },
    });
    const data = await res.json();

    if(!res.ok){
      alert(data.message || "Gagal menghapus laporan.");
      return;
    }

    savedReports = savedReports.filter(r => r.id !== id);
    renderSavedReports();
  } catch (err) {
    alert("Gagal menghapus laporan. Cek koneksi Anda.");
  }
}

function exportReport(){
  const rows = [["No. Pesanan","Tanggal","Kategori","Item","Total"]];
  document.querySelectorAll("#transactionsBody tr").forEach(tr => {
    const cols = tr.querySelectorAll("td");
    if(cols.length === 5){
      rows.push(Array.from(cols).map(c => c.textContent.trim()));
    }
  });
  const csvContent = rows.map(r => r.map(c => `"${c.replace(/"/g,'""')}"`).join(",")).join("\n");
  const blob = new Blob([csvContent], {type:"text/csv;charset=utf-8;"});
  const link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.download = "laporan-penjualan-trendup.csv";
  link.click();
}

// ===== CUSTOM SELECT (replaces native <select> look with site-styled dropdown) =====
function initCustomSelects(){
  document.querySelectorAll(".custom-select-wrap").forEach(wrap => {
    const selectId = wrap.dataset.target;
    const select = document.getElementById(selectId);
    if(!select) return;
    const toggle = wrap.querySelector(".custom-select-toggle");
    const label = toggle.querySelector("span");
    const menu = wrap.querySelector(".custom-select-menu");

    menu.innerHTML = Array.from(select.options).map(opt =>
      `<li data-value="${opt.value}" class="${opt.selected ? "active" : ""}">${opt.textContent}</li>`
    ).join("");
    label.textContent = select.options[select.selectedIndex]?.textContent || "";

    toggle.addEventListener("click", (e) => {
      e.stopPropagation();
      document.querySelectorAll(".custom-select-wrap.open").forEach(w => { if(w !== wrap) w.classList.remove("open"); });
      wrap.classList.toggle("open");
    });

    menu.querySelectorAll("li").forEach(li => {
      li.addEventListener("click", () => {
        select.value = li.dataset.value;
        menu.querySelectorAll("li").forEach(el => el.classList.remove("active"));
        li.classList.add("active");
        label.textContent = li.textContent;
        wrap.classList.remove("open");
        select.dispatchEvent(new Event("change", { bubbles: true }));
      });
    });
  });

  document.addEventListener("click", () => {
    document.querySelectorAll(".custom-select-wrap.open").forEach(w => w.classList.remove("open"));
  });
}

const today = new Date();
const monthAgo = new Date();
monthAgo.setDate(today.getDate() - 30);
document.getElementById("endDate").value = today.toISOString().split("T")[0];
document.getElementById("startDate").value = monthAgo.toISOString().split("T")[0];

renderReport(getFilteredTransactions(document.getElementById("startDate").value, document.getElementById("endDate").value, ""), formatDateLabel(document.getElementById("startDate").value) + " - " + formatDateLabel(document.getElementById("endDate").value));
renderSavedReports();
initCustomSelects();
</script>
</body>
</html>