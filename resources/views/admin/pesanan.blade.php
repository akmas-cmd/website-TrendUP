<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Kelola Pesanan — TRENDUP Admin</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('css/admin/pesanan.css') }}" rel="stylesheet">
</head>
<body>

<div class="admin-layout">

  <!-- ===== SIDEBAR ===== -->
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
      <li><a href="{{ route('admin.laporan') }}"><i class="fa-solid fa-chart-line"></i> Laporan Penjualan</a></li>
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

  <!-- ===== MAIN CONTENT ===== -->
  <div class="main-content">
    <div class="topbar">
      <div class="d-flex align-items-center gap-3">
        <button class="btn d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('open')"><i class="fa-solid fa-bars"></i></button>
        <h1>Kelola Pesanan</h1>
      </div>
    </div>

    <div class="content-pad">

      <!-- ===== STATUS TABS ===== -->
      <div class="status-tabs" id="statusTabs"></div>

      <!-- ===== FILTER ===== -->
      <div class="filter-bar">
        <input type="text" class="search-input-admin" id="searchInput" placeholder="Cari no. pesanan atau nama pelanggan...">
      </div>

      <!-- ===== TABLE ===== -->
      <div class="panel">
        <div style="overflow-x:auto;">
          <table class="table-trendup">
            <thead>
              <tr>
                <th>No. Pesanan</th>
                <th>Pelanggan</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Pembayaran</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="ordersTableBody"></tbody>
          </table>
        </div>
        <div class="pagination-bar">
          <span id="showingInfo"></span>
          <div class="pagination-controls" id="paginationControls"></div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- ===== MODAL DETAIL PESANAN ===== -->
<div class="modal fade" id="orderModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalOrderId">Detail Pesanan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-4">
          <div class="col-md-6">
            <div class="detail-section-label">Data Pembeli</div>
            <div class="buyer-info-row"><span class="label">Nama</span><span class="value" id="dName"></span></div>
            <div class="buyer-info-row"><span class="label">No. HP</span><span class="value" id="dPhone"></span></div>
            <div class="buyer-info-row"><span class="label">Alamat</span><span class="value" id="dAddress"></span></div>
          </div>
          <div class="col-md-6">
            <div class="detail-section-label">Info Pesanan</div>
            <div class="buyer-info-row"><span class="label">Tanggal</span><span class="value" id="dDate"></span></div>
            <div class="buyer-info-row"><span class="label">Pembayaran</span><span class="value" id="dPayment"></span></div>
            <div class="buyer-info-row"><span class="label">Status Saat Ini</span><span class="value" id="dStatusCurrent"></span></div>
          </div>
        </div>

        <hr style="border-color:var(--soft-gray);margin:20px 0;">

        <div class="detail-section-label">Produk Dipesan</div>
        <div id="dItems"></div>

        <div class="d-flex justify-content-between mt-3" style="font-weight:800;font-size:16px;">
          <span>Total</span>
          <span id="dTotal"></span>
        </div>

        <hr style="border-color:var(--soft-gray);margin:20px 0;">

        <div class="detail-section-label">Ubah Status Pesanan</div>
        <select class="status-select-admin" id="dStatusSelect">
          <option value="Menunggu Pembayaran">Menunggu Pembayaran</option>
          <option value="Diproses">Diproses</option>
          <option value="Dikirim">Dikirim</option>
          <option value="Selesai">Selesai</option>
          <option value="Dibatalkan">Dibatalkan</option>
        </select>
      </div>
      <div class="modal-footer" style="border-top:2px solid var(--soft-gray);">
        <button class="btn-modal-cancel" data-bs-dismiss="modal">Tutup</button>
        <button class="btn-modal-save" onclick="saveStatus()">Simpan Status</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
let orders = @json($ordersData);

const statusList = ["Semua", "Menunggu Pembayaran", "Diproses", "Dikirim", "Selesai", "Dibatalkan"];
const statusClassMap = {
  "Menunggu Pembayaran":"status-menunggu",
  "Diproses":"status-diproses",
  "Dikirim":"status-dikirim",
  "Selesai":"status-selesai",
  "Dibatalkan":"status-dibatalkan",
};
let activeStatus = "Semua";
let activeOrderId = null;
let currentPage = 1;
const pageSize = 10;

function formatRupiah(num){ return "Rp " + Number(num).toLocaleString("id-ID"); }

function renderTabs(){
  const container = document.getElementById("statusTabs");
  container.innerHTML = statusList.map(s => {
    const count = s === "Semua" ? orders.length : orders.filter(o => o.status === s).length;
    return `
      <button class="status-tab ${activeStatus===s?'active':''}" onclick="setStatusFilter('${s}')">
        ${s} <span class="count">${count}</span>
      </button>
    `;
  }).join("");
}

function setStatusFilter(s){
  activeStatus = s;
  currentPage = 1;
  renderTabs();
  renderTable();
}

function getFilteredOrders(){
  const search = document.getElementById("searchInput").value.toLowerCase();
  return orders.filter(o => {
    const matchStatus = activeStatus === "Semua" || o.status === activeStatus;
    const matchSearch = o.id.toLowerCase().includes(search) || o.customer.toLowerCase().includes(search);
    return matchStatus && matchSearch;
  });
}

function renderTable(){
  const filtered = getFilteredOrders();
  const totalPages = Math.max(1, Math.ceil(filtered.length / pageSize));
  if(currentPage > totalPages) currentPage = totalPages;

  const start = (currentPage - 1) * pageSize;
  const pageItems = filtered.slice(start, start + pageSize);

  const tbody = document.getElementById("ordersTableBody");
  if(pageItems.length === 0){
    tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-4">Tidak ada pesanan ditemukan.</td></tr>`;
  } else {
    tbody.innerHTML = pageItems.map(o => `
      <tr>
        <td class="order-id">${o.id}</td>
        <td>${o.customer}</td>
        <td class="order-date">${o.date}</td>
        <td>${formatRupiah(o.total)}</td>
        <td>${o.payment}</td>
        <td><span class="status-badge ${statusClassMap[o.status]}">${o.status}</span></td>
        <td><button class="action-btn" onclick="openDetail('${o.id}')"><i class="fa-solid fa-eye"></i></button></td>
      </tr>
    `).join("");
  }

  document.getElementById("showingInfo").textContent =
    filtered.length === 0
      ? "Tidak ada data"
      : `Menampilkan ${start + 1}-${Math.min(start + pageSize, filtered.length)} dari ${filtered.length} pesanan`;

  renderPagination(totalPages);
}

function renderPagination(totalPages){
  const container = document.getElementById("paginationControls");
  let html = `<button class="page-btn" onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? "disabled" : ""}><i class="fa-solid fa-chevron-left"></i></button>`;
  for(let i = 1; i <= totalPages; i++){
    html += `<button class="page-btn ${i === currentPage ? "active" : ""}" onclick="goToPage(${i})">${i}</button>`;
  }
  html += `<button class="page-btn" onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? "disabled" : ""}><i class="fa-solid fa-chevron-right"></i></button>`;
  container.innerHTML = html;
}

function goToPage(page){
  currentPage = page;
  renderTable();
}

document.getElementById("searchInput").addEventListener("input", () => { currentPage = 1; renderTable(); });

function openDetail(id){
  activeOrderId = id;
  const o = orders.find(x => x.id === id);

  document.getElementById("modalOrderId").textContent = o.id;
  document.getElementById("dName").textContent = o.customer;
  document.getElementById("dPhone").textContent = o.phone;
  document.getElementById("dAddress").textContent = o.address;
  document.getElementById("dDate").textContent = o.date;
  document.getElementById("dPayment").textContent = o.payment;
  document.getElementById("dStatusCurrent").innerHTML = `<span class="status-badge ${statusClassMap[o.status]}">${o.status}</span>`;
  document.getElementById("dStatusSelect").value = o.status;

  document.getElementById("dItems").innerHTML = o.items.map(it => `
    <div class="order-item-row">
      <span>${it.name} <span class="qty">x${it.qty}</span></span>
      <span>${formatRupiah(it.price * it.qty)}</span>
    </div>
  `).join("");

  document.getElementById("dTotal").textContent = formatRupiah(o.total);

  new bootstrap.Modal(document.getElementById("orderModal")).show();
}

async function saveStatus(){
  const newStatus = document.getElementById("dStatusSelect").value;

  try {
    const res = await fetch(`/admin/pesanan/${activeOrderId}`, {
      method: "PUT",
      headers: {
        "X-CSRF-TOKEN": csrfToken,
        "Accept": "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ status: newStatus }),
    });
    const data = await res.json();

    if(!res.ok){
      alert(data.message || "Gagal memperbarui status pesanan.");
      return;
    }

    const idx = orders.findIndex(x => x.id === activeOrderId);
    orders[idx] = data.order;

    renderTabs();
    renderTable();
    bootstrap.Modal.getInstance(document.getElementById("orderModal")).hide();
  } catch(err){
    alert("Gagal memperbarui status pesanan. Coba lagi.");
  }
}

renderTabs();
renderTable();
</script>
</body>
</html>