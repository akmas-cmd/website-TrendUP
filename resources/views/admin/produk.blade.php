<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Kelola Produk — TRENDUP Admin</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('css/admin/produk.css') }}" rel="stylesheet">
</head>
<body>

<div class="admin-layout">

  <!-- ===== SIDEBAR ===== -->
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">TREND<span>UP</span> <span style="font-size:12px;color:var(--cool-gray);display:block;letter-spacing:2px;margin-top:2px;">ADMIN PANEL</span></div>

    <span class="sidebar-section-label">Menu Utama</span>
    <ul class="sidebar-nav">
      <li><a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
      <li><a href="{{ route('admin.produk') }}" class="active"><i class="fa-solid fa-box"></i> Produk</a></li>
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
      <a href="#" class="btn-logout"><i class="fa-solid fa-right-from-bracket"></i> Logout</a>
    </div>
  </aside>

  <!-- ===== MAIN CONTENT ===== -->
  <div class="main-content">
    <div class="topbar">
      <div class="d-flex align-items-center gap-3">
        <button class="btn d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('open')"><i class="fa-solid fa-bars"></i></button>
        <h1>Kelola Produk</h1>
      </div>
      <button class="btn-primary-trendup" data-bs-toggle="modal" data-bs-target="#productModal" onclick="openAddModal()">
        <i class="fa-solid fa-plus"></i> Tambah Produk
      </button>
    </div>

    <div class="content-pad">

      <!-- ===== FILTER BAR ===== -->
      <div class="filter-bar">
        <input type="text" class="search-input-admin" id="searchInput" placeholder="Cari nama produk...">

        <div class="custom-select-wrap" data-target="categoryFilter">
          <button type="button" class="custom-select-toggle"><span>Semua Kategori</span><i class="fa-solid fa-chevron-down"></i></button>
          <ul class="custom-select-menu"></ul>
        </div>
        <select class="select-admin" id="categoryFilter" style="display:none;">
          <option value="">Semua Kategori</option>
          @foreach($categoriesData as $cat)
          <option value="{{ $cat['id'] }}">{{ $cat['label'] }}</option>
          @endforeach
        </select>

        <div class="custom-select-wrap" data-target="stockFilter">
          <button type="button" class="custom-select-toggle"><span>Semua Stok</span><i class="fa-solid fa-chevron-down"></i></button>
          <ul class="custom-select-menu"></ul>
        </div>
        <select class="select-admin" id="stockFilter" style="display:none;">
          <option value="">Semua Stok</option>
          <option value="ok">Stok Aman</option>
          <option value="low">Stok Menipis</option>
          <option value="empty">Stok Habis</option>
        </select>
      </div>

      <!-- ===== TABLE ===== -->
      <div class="panel">
        <div style="overflow-x:auto;">
          <table class="table-trendup">
            <thead>
              <tr>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Badge</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="productsTableBody"></tbody>
          </table>
        </div>
        <div class="pagination-bar">
          <span id="showingInfo">Menampilkan 0 dari 0 produk</span>
          <div class="pagination-controls" id="paginationControls"></div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- ===== MODAL TAMBAH/EDIT PRODUK ===== -->
<div class="modal fade" id="productModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Tambah Produk</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="productForm">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="form-label-trendup">Nama Produk</label>
              <input type="text" class="form-control-trendup" id="fName" placeholder="Contoh: TRENDUP Chrono Black">
            </div>
            <div class="col-md-6">
              <label class="form-label-trendup">Kategori</label>
              <div class="custom-select-wrap select-wrap-form" data-target="fCategory">
                <button type="button" class="custom-select-toggle"><span>Jam Tangan</span><i class="fa-solid fa-chevron-down"></i></button>
                <ul class="custom-select-menu"></ul>
              </div>
              <select class="form-control-trendup" id="fCategory" style="display:none;">
                @foreach($categoriesData as $cat)
                <option value="{{ $cat['id'] }}">{{ $cat['label'] }}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-6">
              <label class="form-label-trendup">Harga (Rp)</label>
              <input type="number" min="0" class="form-control-trendup" id="fPrice" placeholder="350000">
            </div>
            <div class="col-md-6">
              <label class="form-label-trendup">Stok</label>
              <input type="number" min="0" class="form-control-trendup" id="fStock" placeholder="25">
            </div>
            <div class="col-md-6">
              <label class="form-label-trendup">Ukuran (opsional, pisahkan koma)</label>
              <input type="text" class="form-control-trendup" id="fSize" placeholder="S,M,L,XL">
            </div>
            <div class="col-md-6">
              <label class="form-label-trendup">Badge</label>
              <div class="custom-select-wrap select-wrap-form" data-target="fBadge">
                <button type="button" class="custom-select-toggle"><span>Tidak Ada</span><i class="fa-solid fa-chevron-down"></i></button>
                <ul class="custom-select-menu"></ul>
              </div>
              <select class="form-control-trendup" id="fBadge" style="display:none;">
                <option value="NONE">Tidak Ada</option>
                <option value="NEW">NEW</option>
                <option value="SALE">SALE</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label-trendup">Deskripsi</label>
              <textarea class="form-control-trendup" id="fDesc" rows="3" placeholder="Deskripsi singkat produk..."></textarea>
            </div>
            <div class="col-12">
              <label class="form-label-trendup">Gambar Produk</label>
              <div class="upload-box" onclick="document.getElementById('fImage').click()">
                <i class="fa-solid fa-cloud-arrow-up fa-lg mb-2"></i>
                <div id="uploadLabel">Klik untuk upload gambar (JPG/PNG, maks 2MB)</div>
                <input type="file" id="fImage" accept="image/*" style="display:none;" onchange="document.getElementById('uploadLabel').textContent = this.files[0]?.name || 'Klik untuk upload gambar'">
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer" style="border-top:2px solid var(--soft-gray);">
        <button class="btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
        <button class="btn-modal-save" onclick="saveProduct()">Simpan Produk</button>
      </div>
    </div>
  </div>
</div>

<!-- ===== MODAL KONFIRMASI HAPUS ===== -->
<div class="modal fade" id="deleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center py-4">
        <i class="fa-solid fa-triangle-exclamation" style="font-size:40px;color:#d33;margin-bottom:14px;"></i>
        <h5 style="font-family:'Anton',sans-serif;text-transform:uppercase;">Hapus Produk?</h5>
        <p style="color:var(--cool-gray);font-size:13px;" id="deleteProductName">Tindakan ini tidak dapat dibatalkan.</p>
        <div class="d-flex gap-2 justify-content-center mt-3">
          <button class="btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
          <button class="btn-modal-save" style="background:#d33;color:#fff;" onclick="confirmDelete()">Ya, Hapus</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
let products = @json($productsData);

let editingId = null;
let deletingId = null;
let currentPage = 1;
const pageSize = 10;

function formatRupiah(num){ return "Rp " + Number(num).toLocaleString("id-ID"); }

function stockInfo(stock){
  if(stock === 0) return {cls:"stock-empty", label:"Habis"};
  if(stock <= 5) return {cls:"stock-low", label:stock + " pcs"};
  return {cls:"stock-ok", label:stock + " pcs"};
}

function badgeInfo(badge){
  if(badge === "NEW") return {cls:"badge-new-mini", label:"NEW"};
  if(badge === "SALE") return {cls:"badge-sale-mini", label:"SALE"};
  return {cls:"badge-none-mini", label:"—"};
}

function getFilteredProducts(){
  const search = document.getElementById("searchInput").value.toLowerCase();
  const cat = document.getElementById("categoryFilter").value;
  const stockF = document.getElementById("stockFilter").value;

  return products.filter(p => {
    const matchSearch = p.name.toLowerCase().includes(search);
    const matchCat = !cat || String(p.category_id) === String(cat);
    let matchStock = true;
    if(stockF === "ok") matchStock = p.stock > 5;
    if(stockF === "low") matchStock = p.stock > 0 && p.stock <= 5;
    if(stockF === "empty") matchStock = p.stock === 0;
    return matchSearch && matchCat && matchStock;
  });
}

function renderTable(){
  const filtered = getFilteredProducts();
  const totalPages = Math.max(1, Math.ceil(filtered.length / pageSize));
  if(currentPage > totalPages) currentPage = totalPages;

  const start = (currentPage - 1) * pageSize;
  const pageItems = filtered.slice(start, start + pageSize);

  const tbody = document.getElementById("productsTableBody");
  if(pageItems.length === 0){
    tbody.innerHTML = `<tr><td colspan="6" class="text-center text-muted py-4">Tidak ada produk ditemukan.</td></tr>`;
  } else {
    tbody.innerHTML = pageItems.map(p => {
      const s = stockInfo(p.stock);
      const b = badgeInfo(p.badge);
      const thumb = p.image
        ? `<img src="${p.image}" style="width:100%;height:100%;object-fit:cover;border-radius:8px;">`
        : `<i class="${p.icon}"></i>`;
      return `
        <tr>
          <td>
            <div class="d-flex align-items-center gap-3">
              <div class="prod-thumb">${thumb}</div>
              <span class="prod-name">${p.name}</span>
            </div>
          </td>
          <td><span class="prod-cat-tag">${p.cat}</span></td>
          <td>${formatRupiah(p.price)}</td>
          <td><span class="stock-pill ${s.cls}">${s.label}</span></td>
          <td><span class="badge-mini ${b.cls}">${b.label}</span></td>
          <td>
            <button class="action-btn" onclick="openEditModal(${p.id})"><i class="fa-solid fa-pen"></i></button>
            <button class="action-btn danger" onclick="openDeleteModal(${p.id})"><i class="fa-solid fa-trash"></i></button>
          </td>
        </tr>
      `;
    }).join("");
  }

  document.getElementById("showingInfo").textContent =
    filtered.length === 0
      ? "Tidak ada data"
      : `Menampilkan ${start + 1}-${Math.min(start + pageSize, filtered.length)} dari ${filtered.length} produk`;

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
document.getElementById("categoryFilter").addEventListener("change", () => { currentPage = 1; renderTable(); });
document.getElementById("stockFilter").addEventListener("change", () => { currentPage = 1; renderTable(); });

function openAddModal(){
  editingId = null;
  document.getElementById("modalTitle").textContent = "Tambah Produk";
  document.getElementById("productForm").reset();
  document.getElementById("uploadLabel").textContent = "Klik untuk upload gambar";
  syncSelectUI("fCategory");
  syncSelectUI("fBadge");
}

function openEditModal(id){
  editingId = id;
  const p = products.find(x => x.id === id);
  document.getElementById("modalTitle").textContent = "Edit Produk";
  document.getElementById("fName").value = p.name;
  document.getElementById("fCategory").value = p.category_id;
  document.getElementById("fPrice").value = p.price;
  document.getElementById("fStock").value = p.stock;
  document.getElementById("fBadge").value = p.badge || "NONE";
  document.getElementById("fSize").value = p.sizes || "";
  document.getElementById("fDesc").value = p.description || "";
  document.getElementById("uploadLabel").textContent = "Klik untuk upload gambar";
  syncSelectUI("fCategory");
  syncSelectUI("fBadge");
  new bootstrap.Modal(document.getElementById("productModal")).show();
}

async function saveProduct(){
  const name = document.getElementById("fName").value.trim();
  const categoryId = document.getElementById("fCategory").value;
  const price = parseInt(document.getElementById("fPrice").value) || 0;
  const stock = parseInt(document.getElementById("fStock").value) || 0;
  const badge = document.getElementById("fBadge").value;
  const sizes = document.getElementById("fSize").value.trim();
  const description = document.getElementById("fDesc").value.trim();
  const imageFile = document.getElementById("fImage").files[0];

  if(!name || price <= 0 || !categoryId){
    alert("Nama produk, kategori, dan harga wajib diisi dengan benar.");
    return;
  }

  const formData = new FormData();
  formData.append("name", name);
  formData.append("category_id", categoryId);
  formData.append("price", price);
  formData.append("stock", stock);
  formData.append("badge", badge);
  formData.append("sizes", sizes);
  formData.append("description", description);
  if(imageFile) formData.append("image", imageFile);
  if(editingId) formData.append("_method", "PUT");

  const url = editingId ? `/admin/produk/${editingId}` : `/admin/produk`;

  try {
    const res = await fetch(url, {
      method: "POST",
      headers: { "X-CSRF-TOKEN": csrfToken, "Accept": "application/json" },
      body: formData,
    });
    const data = await res.json();

    if(!res.ok){
      alert(data.message || "Terjadi kesalahan saat menyimpan produk.");
      return;
    }

    if(editingId){
      const idx = products.findIndex(x => x.id === editingId);
      products[idx] = data.product;
    } else {
      products.unshift(data.product);
      currentPage = 1;
    }

    renderTable();
    bootstrap.Modal.getInstance(document.getElementById("productModal")).hide();
  } catch(err){
    alert("Gagal menyimpan produk. Coba lagi.");
  }
}

function openDeleteModal(id){
  deletingId = id;
  const p = products.find(x => x.id === id);
  document.getElementById("deleteProductName").textContent = `"${p.name}" akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.`;
  new bootstrap.Modal(document.getElementById("deleteModal")).show();
}

async function confirmDelete(){
  try {
    const formData = new FormData();
    formData.append("_method", "DELETE");

    const res = await fetch(`/admin/produk/${deletingId}`, {
      method: "POST",
      headers: { "X-CSRF-TOKEN": csrfToken, "Accept": "application/json" },
      body: formData,
    });
    const data = await res.json();

    if(!res.ok){
      alert(data.message || "Gagal menghapus produk.");
      return;
    }

    products = products.filter(p => p.id !== deletingId);
    renderTable();
    bootstrap.Modal.getInstance(document.getElementById("deleteModal")).hide();
  } catch(err){
    alert("Gagal menghapus produk. Coba lagi.");
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

function syncSelectUI(selectId){
  const select = document.getElementById(selectId);
  const wrap = document.querySelector(`.custom-select-wrap[data-target="${selectId}"]`);
  if(!select || !wrap) return;
  const label = wrap.querySelector(".custom-select-toggle span");
  const menu = wrap.querySelector(".custom-select-menu");
  label.textContent = select.options[select.selectedIndex]?.textContent || "";
  menu.querySelectorAll("li").forEach(li => li.classList.toggle("active", li.dataset.value === select.value));
}

renderTable();
initCustomSelects();
</script>
</body>
</html>