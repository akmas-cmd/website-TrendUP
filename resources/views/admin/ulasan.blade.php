<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Kelola Ulasan — TRENDUP Admin</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('css/admin/ulasan.css') }}" rel="stylesheet">
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
      <li><a href="{{ route('admin.ulasan') }}" class="active"><i class="fa-solid fa-star"></i> Ulasan</a></li>
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

  <div class="main-content">
    <div class="topbar">
      <div class="d-flex align-items-center gap-3">
        <button class="btn d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('open')"><i class="fa-solid fa-bars"></i></button>
        <h1>Kelola Ulasan</h1>
      </div>
    </div>

    <div class="content-pad">

      <div class="filter-bar">
        <input type="text" class="search-input-admin" id="searchInput" placeholder="Cari nama produk atau pelanggan...">

        <div class="custom-select-wrap" data-target="ratingFilter">
          <button type="button" class="custom-select-toggle"><span>Semua Rating</span><i class="fa-solid fa-chevron-down"></i></button>
          <ul class="custom-select-menu"></ul>
        </div>
        <select class="select-admin" id="ratingFilter" style="display:none;">
          <option value="">Semua Rating</option>
          <option value="5">5 Bintang</option>
          <option value="4">4 Bintang</option>
          <option value="3">3 Bintang</option>
          <option value="2">2 Bintang</option>
          <option value="1">1 Bintang</option>
        </select>

        <div class="custom-select-wrap" data-target="pageSizeSelect">
          <button type="button" class="custom-select-toggle"><span>10 / halaman</span><i class="fa-solid fa-chevron-down"></i></button>
          <ul class="custom-select-menu"></ul>
        </div>
        <select class="select-admin" id="pageSizeSelect" style="display:none;">
          <option value="5">5 / halaman</option>
          <option value="10" selected>10 / halaman</option>
          <option value="20">20 / halaman</option>
        </select>
      </div>

      <div class="panel">
        <div style="overflow-x:auto;">
          <table class="table-trendup">
            <thead>
              <tr>
                <th>No</th>
                <th class="sortable" data-sort="product">Produk <i class="fa-solid fa-sort sort-icon"></i></th>
                <th class="sortable" data-sort="customer">Pelanggan <i class="fa-solid fa-sort sort-icon"></i></th>
                <th class="sortable" data-sort="rating">Rating <i class="fa-solid fa-sort sort-icon"></i></th>
                <th>Ulasan</th>
                <th class="sortable" data-sort="date">Tanggal <i class="fa-solid fa-sort sort-icon"></i></th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="reviewsTableBody"></tbody>
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

<div class="media-lightbox" id="mediaLightbox" hidden>
  <button type="button" class="media-lightbox-close" onclick="closeMedia()" aria-label="Tutup"><i class="fa-solid fa-xmark"></i></button>
  <div class="media-lightbox-body" id="mediaLightboxBody"></div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
let reviews = @json($reviewsData);

let currentPage = 1;
let pageSize = 10;
let sortKey = "date";
let sortDir = "desc";

function initials(name){
  return name.split(" ").map(w => w[0]).join("").substring(0,2).toUpperCase();
}

function formatDate(dateStr){
  const d = new Date(dateStr);
  return d.toLocaleDateString("id-ID", {day:"numeric", month:"short", year:"numeric"});
}

function starsHtml(rating){
  let html = "";
  for(let i = 1; i <= 5; i++){
    html += `<i class="fa-${i <= rating ? "solid" : "regular"} fa-star"></i>`;
  }
  return html;
}

function mediaHtml(r){
  const photos = (r.photos || []).map(src =>
    `<button type="button" class="admin-media-thumb" onclick="openMedia('image', '${src}')" title="Lihat foto"><img src="${src}" alt=""></button>`
  ).join("");
  const video = r.video
    ? `<button type="button" class="admin-media-thumb" onclick="openMedia('video', '${r.video}')" title="Putar video"><video src="${r.video}#t=0.1" preload="metadata" muted></video><span class="admin-media-play"><i class="fa-solid fa-play"></i></span></button>`
    : "";
  return (photos || video) ? `<div class="admin-media-list">${photos}${video}</div>` : "";
}

function openMedia(type, src){
  const body = document.getElementById("mediaLightboxBody");
  body.innerHTML = "";
  let el;
  if(type === "video"){
    el = document.createElement("video");
    el.controls = true;
    el.autoplay = true;
    el.playsInline = true;
  } else {
    el = document.createElement("img");
    el.alt = "";
  }
  el.src = src;
  body.appendChild(el);
  document.getElementById("mediaLightbox").hidden = false;
}

function closeMedia(){
  document.getElementById("mediaLightboxBody").innerHTML = "";
  document.getElementById("mediaLightbox").hidden = true;
}

document.getElementById("mediaLightbox").addEventListener("click", (e) => {
  if(e.target.id === "mediaLightbox" || e.target.id === "mediaLightboxBody") closeMedia();
});
document.addEventListener("keydown", (e) => { if(e.key === "Escape") closeMedia(); });

function getFilteredSorted(){
  const search = document.getElementById("searchInput").value.toLowerCase();
  const ratingF = document.getElementById("ratingFilter").value;

  let result = reviews.filter(r => {
    const matchSearch = r.product.toLowerCase().includes(search) || r.customer.toLowerCase().includes(search);
    const matchRating = !ratingF || String(r.rating) === ratingF;
    return matchSearch && matchRating;
  });

  result.sort((a,b) => {
    let valA = a[sortKey];
    let valB = b[sortKey];
    if(typeof valA === "string") valA = valA.toLowerCase();
    if(typeof valB === "string") valB = valB.toLowerCase();
    if(valA < valB) return sortDir === "asc" ? -1 : 1;
    if(valA > valB) return sortDir === "asc" ? 1 : -1;
    return 0;
  });

  return result;
}

function renderTable(){
  const filtered = getFilteredSorted();
  const totalPages = Math.max(1, Math.ceil(filtered.length / pageSize));
  if(currentPage > totalPages) currentPage = totalPages;

  const start = (currentPage - 1) * pageSize;
  const pageItems = filtered.slice(start, start + pageSize);

  const tbody = document.getElementById("reviewsTableBody");
  if(pageItems.length === 0){
    tbody.innerHTML = `<tr><td colspan="7" class="text-center text-muted py-4">Tidak ada ulasan ditemukan.</td></tr>`;
  } else {
    tbody.innerHTML = pageItems.map((r, i) => `
      <tr>
        <td>${start + i + 1}</td>
        <td><div class="customer-name">${r.product}</div></td>
        <td>
          <div class="d-flex align-items-center gap-3">
            <div class="customer-avatar">${initials(r.customer)}</div>
            <div>
              <div class="customer-name">${r.customer}</div>
              <div class="customer-email">${r.email ?? ""}</div>
            </div>
          </div>
        </td>
        <td><span class="review-stars-admin">${starsHtml(r.rating)}</span></td>
        <td>
          ${r.comment
            ? `<div class="review-comment-admin" title="${r.comment.replace(/"/g, '&quot;')}">${r.comment}</div>`
            : `<div class="review-comment-admin empty">Tanpa komentar</div>`}
          ${mediaHtml(r)}
        </td>
        <td>${formatDate(r.date)}</td>
        <td>
          <button class="action-btn danger" onclick="deleteReview(${r.id})" title="Hapus ulasan">
            <i class="fa-solid fa-trash"></i>
          </button>
        </td>
      </tr>
    `).join("");
  }

  document.getElementById("showingInfo").textContent =
    filtered.length === 0
      ? "Tidak ada data"
      : `Menampilkan ${start + 1}-${Math.min(start + pageSize, filtered.length)} dari ${filtered.length} ulasan`;

  renderPagination(totalPages);
  updateSortIcons();
}

function renderPagination(totalPages){
  const container = document.getElementById("paginationControls");
  let html = `<button class="page-btn" onclick="goToPage(${currentPage - 1})" ${currentPage === 1 ? "disabled" : ""}><i class="fa-solid fa-chevron-left"></i></button>`;

  for(let i = 1; i <= totalPages; i++){
    if(totalPages > 7 && (i > 2 && i < totalPages - 1 && Math.abs(i - currentPage) > 1)){
      if(i === 3 || i === totalPages - 2) html += `<span class="page-btn" style="border:none;">...</span>`;
      continue;
    }
    html += `<button class="page-btn ${i === currentPage ? "active" : ""}" onclick="goToPage(${i})">${i}</button>`;
  }

  html += `<button class="page-btn" onclick="goToPage(${currentPage + 1})" ${currentPage === totalPages ? "disabled" : ""}><i class="fa-solid fa-chevron-right"></i></button>`;
  container.innerHTML = html;
}

function goToPage(page){
  currentPage = page;
  renderTable();
}

function updateSortIcons(){
  document.querySelectorAll("th.sortable").forEach(th => {
    const key = th.dataset.sort;
    const icon = th.querySelector(".sort-icon");
    th.classList.remove("sort-active");
    icon.className = "fa-solid fa-sort sort-icon";
    if(key === sortKey){
      th.classList.add("sort-active");
      icon.className = sortDir === "asc" ? "fa-solid fa-sort-up sort-icon" : "fa-solid fa-sort-down sort-icon";
    }
  });
}

document.querySelectorAll("th.sortable").forEach(th => {
  th.addEventListener("click", () => {
    const key = th.dataset.sort;
    if(sortKey === key){
      sortDir = sortDir === "asc" ? "desc" : "asc";
    } else {
      sortKey = key;
      sortDir = "asc";
    }
    currentPage = 1;
    renderTable();
  });
});

document.getElementById("searchInput").addEventListener("input", () => { currentPage = 1; renderTable(); });
document.getElementById("ratingFilter").addEventListener("change", () => { currentPage = 1; renderTable(); });
document.getElementById("pageSizeSelect").addEventListener("change", (e) => {
  pageSize = parseInt(e.target.value);
  currentPage = 1;
  renderTable();
});

async function deleteReview(id){
  const r = reviews.find(x => x.id === id);
  if(!r) return;
  if(!confirm(`Hapus ulasan dari "${r.customer}" untuk produk "${r.product}"? Tindakan ini tidak dapat dibatalkan.`)) return;

  try {
    const res = await fetch(`/admin/ulasan/${id}`, {
      method: "DELETE",
      headers: { "X-CSRF-TOKEN": csrfToken, "Accept": "application/json" },
    });
    const data = await res.json();

    if(!res.ok){
      alert(data.message || "Gagal menghapus ulasan.");
      return;
    }

    reviews = reviews.filter(x => x.id !== id);
    renderTable();
  } catch(err){
    alert("Gagal menghapus ulasan. Coba lagi.");
  }
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

renderTable();
initCustomSelects();
</script>
</body>
</html>