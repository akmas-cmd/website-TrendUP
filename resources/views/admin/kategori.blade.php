<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Kelola Kategori — TRENDUP Admin</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('css/admin/kategori.css') }}" rel="stylesheet">
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
      <li><a href="{{ route('admin.kategori') }}" class="active"><i class="fa-solid fa-tags"></i> Kategori</a></li>
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
        <h1>Kelola Kategori</h1>
      </div>
      <button class="btn-primary-trendup" onclick="openAddModal()">
        <i class="fa-solid fa-plus"></i> Tambah Kategori
      </button>
    </div>

    <div class="content-pad">
      <div class="filter-bar" style="background:var(--white);border:2px solid var(--jet);border-radius:16px;padding:16px 20px;margin-bottom:22px;">
        <input type="text" class="search-input-admin" id="searchInput" placeholder="Cari nama kategori..." style="width:100%;border:2px solid var(--soft-gray);border-radius:10px;padding:10px 16px;font-size:13px;outline:none;">
      </div>
      <div class="row g-3" id="categoryGrid"></div>
      <div class="pagination-bar">
        <span id="showingInfo"></span>
        <div class="pagination-controls" id="paginationControls"></div>
      </div>
    </div>
  </div>
</div>

<!-- ===== MODAL TAMBAH/EDIT KATEGORI ===== -->
<div class="modal fade" id="categoryModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Tambah Kategori</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="categoryForm">
          <div class="mb-3">
            <label class="form-label-trendup">Nama Kategori</label>
            <input type="text" class="form-control-trendup" id="fCatName" placeholder="Contoh: Jam Tangan" oninput="updateSlugPreview()">
          </div>
          <div class="mb-2">
            <label class="form-label-trendup">Slug (otomatis)</label>
            <input type="text" class="form-control-trendup" id="fCatSlug" placeholder="jam-tangan" readonly style="background:var(--soft-gray);">
          </div>
          <p style="font-size:12px;color:var(--cool-gray);margin:0;">Slug dipakai untuk URL kategori di website, contoh: <code>shop.php?kategori=jam-tangan</code></p>
        </form>
      </div>
      <div class="modal-footer" style="border-top:2px solid var(--soft-gray);">
        <button class="btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
        <button class="btn-modal-save" onclick="saveCategory()">Simpan Kategori</button>
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
        <h5 style="font-family:'Anton',sans-serif;text-transform:uppercase;">Hapus Kategori?</h5>
        <p style="color:var(--cool-gray);font-size:13px;" id="deleteCatWarning">Tindakan ini tidak dapat dibatalkan.</p>
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
let categories = @json($categoriesData);

let editingId = null;
let deletingId = null;
let currentPage = 1;
const pageSize = 7;

function slugify(text){
  return text.toLowerCase().trim().replace(/[^a-z0-9]+/g, "-").replace(/(^-|-$)/g, "");
}

function updateSlugPreview(){
  const name = document.getElementById("fCatName").value;
  document.getElementById("fCatSlug").value = slugify(name);
}

function getFilteredCategories(){
  const search = document.getElementById("searchInput").value.toLowerCase();
  return categories.filter(c => c.name.toLowerCase().includes(search));
}

function renderCategories(){
  const filtered = getFilteredCategories();
  const totalPages = Math.max(1, Math.ceil(filtered.length / pageSize));
  if(currentPage > totalPages) currentPage = totalPages;

  const start = (currentPage - 1) * pageSize;
  const pageItems = filtered.slice(start, start + pageSize);

  const grid = document.getElementById("categoryGrid");
  grid.innerHTML = pageItems.map(c => `
    <div class="col-sm-6 col-lg-4 col-xl-3">
      <div class="cat-manage-card">
        <div class="cat-manage-icon"><i class="${c.icon}"></i></div>
        <div class="cat-manage-name">${c.name}</div>
        <div class="cat-manage-slug">/${c.slug}</div>
        <div class="cat-manage-count">${c.count} produk</div>
        <div class="cat-manage-actions">
          <button class="cat-action-btn" onclick="openEditModal(${c.id})"><i class="fa-solid fa-pen"></i> Edit</button>
          <button class="cat-action-btn danger" onclick="openDeleteModal(${c.id})"><i class="fa-solid fa-trash"></i> Hapus</button>
        </div>
      </div>
    </div>
  `).join("");

  document.getElementById("showingInfo").textContent =
    filtered.length === 0
      ? "Tidak ada data"
      : `Menampilkan ${start + 1}-${Math.min(start + pageSize, filtered.length)} dari ${filtered.length} kategori`;

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
  renderCategories();
}

function openAddModal(){
  editingId = null;
  document.getElementById("modalTitle").textContent = "Tambah Kategori";
  document.getElementById("categoryForm").reset();
  document.getElementById("fCatSlug").value = "";
  new bootstrap.Modal(document.getElementById("categoryModal")).show();
}

function openEditModal(id){
  editingId = id;
  const c = categories.find(x => x.id === id);
  document.getElementById("modalTitle").textContent = "Edit Kategori";
  document.getElementById("fCatName").value = c.name;
  document.getElementById("fCatSlug").value = c.slug;
  new bootstrap.Modal(document.getElementById("categoryModal")).show();
}

async function saveCategory(){
  const name = document.getElementById("fCatName").value.trim();

  if(!name){
    alert("Nama kategori wajib diisi.");
    return;
  }

  const formData = new FormData();
  formData.append("name", name);
  if(editingId) formData.append("_method", "PUT");

  const url = editingId ? `/admin/kategori/${editingId}` : `/admin/kategori`;

  try {
    const res = await fetch(url, {
      method: "POST",
      headers: { "X-CSRF-TOKEN": csrfToken, "Accept": "application/json" },
      body: formData,
    });
    const data = await res.json();

    if(!res.ok){
      alert(data.message || "Terjadi kesalahan saat menyimpan kategori.");
      return;
    }

    if(editingId){
      const idx = categories.findIndex(x => x.id === editingId);
      categories[idx] = data.category;
    } else {
      categories.push(data.category);
    }

    renderCategories();
    bootstrap.Modal.getInstance(document.getElementById("categoryModal")).hide();
  } catch(err){
    alert("Gagal menyimpan kategori. Coba lagi.");
  }
}

function openDeleteModal(id){
  deletingId = id;
  const c = categories.find(x => x.id === id);
  const warning = c.count > 0
    ? `Kategori "${c.name}" masih punya ${c.count} produk terkait. Menghapusnya bisa memengaruhi produk tersebut.`
    : `Kategori "${c.name}" akan dihapus permanen.`;
  document.getElementById("deleteCatWarning").textContent = warning;
  new bootstrap.Modal(document.getElementById("deleteModal")).show();
}

async function confirmDelete(){
  try {
    const formData = new FormData();
    formData.append("_method", "DELETE");

    const res = await fetch(`/admin/kategori/${deletingId}`, {
      method: "POST",
      headers: { "X-CSRF-TOKEN": csrfToken, "Accept": "application/json" },
      body: formData,
    });
    const data = await res.json();

    if(!res.ok){
      alert(data.message || "Gagal menghapus kategori.");
      return;
    }

    categories = categories.filter(c => c.id !== deletingId);
    renderCategories();
    bootstrap.Modal.getInstance(document.getElementById("deleteModal")).hide();
  } catch(err){
    alert("Gagal menghapus kategori. Coba lagi.");
  }
}

document.getElementById("searchInput").addEventListener("input", () => { currentPage = 1; renderCategories(); });

renderCategories();
</script>
</body>
</html>