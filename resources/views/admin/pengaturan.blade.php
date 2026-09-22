<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Pengaturan — TRENDUP Admin</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="{{ asset('css/admin/pengaturan.css') }}" rel="stylesheet">
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
      <li><a href="{{ route('admin.laporan') }}"><i class="fa-solid fa-chart-line"></i> Laporan Penjualan</a></li>
    </ul>

    <span class="sidebar-section-label">Lainnya</span>
    <ul class="sidebar-nav">
      <li><a href="{{ route('admin.pengaturan') }}" class="active"><i class="fa-solid fa-gear"></i> Pengaturan</a></li>
      <li><a href="{{ route('home') }}"><i class="fa-solid fa-arrow-up-right-from-square"></i> Lihat Website</a></li>
    </ul>

    <div class="sidebar-bottom">
      <div class="admin-mini">
        <div class="admin-avatar">{{ auth()->user()->initials() }}</div>
        <div class="admin-mini-info">
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

  <div class="main-content">
    <div class="topbar">
      <div class="d-flex align-items-center gap-3">
        <button class="btn d-lg-none" onclick="document.getElementById('sidebar').classList.toggle('open')"><i class="fa-solid fa-bars"></i></button>
        <h1>Pengaturan</h1>
      </div>
    </div>

    <div class="content-pad">
      <div class="alert-saved" id="savedAlert">
        <i class="fa-solid fa-circle-check"></i> <span id="savedAlertText">Perubahan berhasil disimpan.</span>
      </div>

      <div class="settings-layout">

        <div class="settings-tabs">
          <button class="settings-tab-btn active" data-tab="toko" onclick="switchTab('toko')"><i class="fa-solid fa-store"></i> Info Toko</button>
          <button class="settings-tab-btn" data-tab="pengiriman" onclick="switchTab('pengiriman')"><i class="fa-solid fa-truck"></i> Pengiriman</button>
          <button class="settings-tab-btn" data-tab="pembayaran" onclick="switchTab('pembayaran')"><i class="fa-solid fa-building-columns"></i> Pembayaran</button>
          <button class="settings-tab-btn" data-tab="notifikasi" onclick="switchTab('notifikasi')"><i class="fa-solid fa-bell"></i> Notifikasi</button>
          <button class="settings-tab-btn" data-tab="akun" onclick="switchTab('akun')"><i class="fa-solid fa-user-shield"></i> Akun Admin</button>
        </div>

        <div class="settings-panel">

          <div class="settings-section active" id="tab-toko">
            <h3>Info Toko</h3>
            <p class="desc">Informasi ini akan tampil di halaman utama dan footer website TRENDUP.</p>

            <div class="d-flex gap-3 align-items-center mb-4">
              <div class="logo-preview-box">
                <img src="{{ $toko['logo'] }}" alt="Logo Toko" id="logoPreview">
              </div>
              <div>
                <div style="font-weight:700;font-size:14px;margin-bottom:6px;">Logo Toko</div>
                <button type="button" class="icon-btn-sm" onclick="document.getElementById('fLogo').click()">
                  <i class="fa-solid fa-upload"></i> Ganti Logo
                </button>
                <input type="file" id="fLogo" accept="image/*" style="display:none;" onchange="previewLogo(event)">
              </div>
            </div>

            <div class="mb-field">
              <label class="form-label-trendup">Nama Toko</label>
              <input type="text" class="form-control-trendup" id="fNamaToko" value="{{ $toko['nama_toko'] }}">
            </div>
            <div class="mb-field">
              <label class="form-label-trendup">Tagline</label>
              <input type="text" class="form-control-trendup" id="fTagline" value="{{ $toko['tagline'] }}">
            </div>
            <div class="form-row">
              <div>
                <label class="form-label-trendup">Email Toko</label>
                <input type="email" class="form-control-trendup" id="fEmailToko" value="{{ $toko['email_toko'] }}">
              </div>
              <div>
                <label class="form-label-trendup">Nomor WhatsApp</label>
                <input type="text" class="form-control-trendup" id="fWhatsapp" value="{{ $toko['whatsapp'] }}">
              </div>
            </div>
            <div class="mb-field">
              <label class="form-label-trendup">Alamat Toko</label>
              <textarea class="form-control-trendup" id="fAlamatToko" rows="2">{{ $toko['alamat_toko'] }}</textarea>
            </div>
            <button class="btn-save" onclick="saveToko()"><i class="fa-solid fa-spinner fa-spin" id="tokoSpinner" style="display:none;"></i> Simpan Perubahan</button>
          </div>

          <div class="settings-section" id="tab-pengiriman">
            <h3>Pengiriman</h3>
            <p class="desc">Atur biaya kirim default dan syarat gratis ongkir.</p>

            <div class="form-row">
              <div>
                <label class="form-label-trendup">Ongkos Kirim Default (Rp)</label>
                <input type="number" class="form-control-trendup" id="fOngkir" value="{{ $pengiriman['ongkir_default'] }}">
              </div>
              <div>
                <label class="form-label-trendup">Minimal Belanja Gratis Ongkir (Rp)</label>
                <input type="number" class="form-control-trendup" id="fMinGratis" value="{{ $pengiriman['min_gratis_ongkir'] }}">
              </div>
            </div>
            <div class="mb-field">
              <label class="form-label-trendup">Estimasi Waktu Pengiriman</label>
              <input type="text" class="form-control-trendup" id="fEstimasi" value="{{ $pengiriman['estimasi_pengiriman'] }}">
            </div>
            <button class="btn-save" onclick="savePengiriman()">Simpan Perubahan</button>
          </div>

          <div class="settings-section" id="tab-pembayaran">
            <h3>Metode Pembayaran</h3>
            <p class="desc">Rekening bank yang ditampilkan ke pembeli saat memilih Transfer Bank.</p>

            <div id="bankList"></div>

            <button class="btn-add-bank" onclick="openAddBankModal()"><i class="fa-solid fa-plus"></i> Tambah Rekening Bank</button>
          </div>

          <div class="settings-section" id="tab-notifikasi">
            <h3>Notifikasi</h3>
            <p class="desc">Atur notifikasi apa saja yang ingin kamu terima sebagai admin.</p>

            <div class="toggle-row">
              <div>
                <div class="t-title">Pesanan Baru</div>
                <div class="t-sub">Dapat notifikasi setiap ada pesanan masuk</div>
              </div>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="notifPesananBaru" {{ $notifikasi['notif_pesanan_baru'] ? 'checked' : '' }}>
              </div>
            </div>
            <div class="toggle-row">
              <div>
                <div class="t-title">Stok Menipis</div>
                <div class="t-sub">Dapat notifikasi kalau stok produk di bawah 5 pcs</div>
              </div>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="notifStokMenipis" {{ $notifikasi['notif_stok_menipis'] ? 'checked' : '' }}>
              </div>
            </div>
            <div class="toggle-row">
              <div>
                <div class="t-title">Pelanggan Baru Mendaftar</div>
                <div class="t-sub">Dapat notifikasi setiap ada akun baru terdaftar</div>
              </div>
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="notifPelangganBaru" {{ $notifikasi['notif_pelanggan_baru'] ? 'checked' : '' }}>
              </div>
            </div>
            <div class="mt-3">
              <button class="btn-save" onclick="saveNotifikasi()">Simpan Perubahan</button>
            </div>
          </div>

          <div class="settings-section" id="tab-akun">
            <h3>Akun Admin</h3>
            <p class="desc">Kelola akun admin dan ganti password login kamu.</p>

            <div class="mb-4">
              <div class="admin-account-row">
                <div class="admin-account-avatar">{{ auth()->user()->initials() }}</div>
                <div>
                  <div style="font-weight:700;font-size:14px;">{{ auth()->user()->name }}</div>
                  <div style="font-size:12px;color:var(--cool-gray);">{{ auth()->user()->email }}</div>
                </div>
                <span class="role-pill">{{ auth()->user()->role === 'admin' ? 'Super Admin' : ucfirst(auth()->user()->role) }}</span>
              </div>
            </div>

            <hr style="border-color:var(--soft-gray);">

            <h3 style="font-size:16px;margin-top:20px;">Ganti Password</h3>
            <div class="mb-field">
              <label class="form-label-trendup">Password Saat Ini</label>
              <input type="password" class="form-control-trendup" id="fCurrentPassword" placeholder="Masukkan password saat ini">
            </div>
            <div class="form-row">
              <div>
                <label class="form-label-trendup">Password Baru</label>
                <input type="password" class="form-control-trendup" id="fNewPassword" placeholder="Minimal 6 karakter">
              </div>
              <div>
                <label class="form-label-trendup">Konfirmasi Password Baru</label>
                <input type="password" class="form-control-trendup" id="fNewPasswordConfirm" placeholder="Ulangi password baru">
              </div>
            </div>
            <button class="btn-save" onclick="changePassword()">Ubah Password</button>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- ===== MODAL TAMBAH/EDIT REKENING BANK ===== -->
<div class="modal fade" id="bankModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="bankModalTitle">Tambah Rekening Bank</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form id="bankForm">
          <div class="mb-3">
            <label class="form-label-trendup">Nama Bank</label>
            <input type="text" class="form-control-trendup" id="fBankName" placeholder="Contoh: BCA">
          </div>
          <div class="mb-3">
            <label class="form-label-trendup">Nomor Rekening</label>
            <input type="text" class="form-control-trendup" id="fAccountNumber" placeholder="Contoh: 1234567890">
          </div>
          <div class="mb-2">
            <label class="form-label-trendup">Atas Nama</label>
            <input type="text" class="form-control-trendup" id="fAccountHolder" placeholder="Contoh: TRENDUP Store">
          </div>
        </form>
      </div>
      <div class="modal-footer" style="border-top:2px solid var(--soft-gray);">
        <button class="btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
        <button class="btn-modal-save" onclick="saveBank()">Simpan Rekening</button>
      </div>
    </div>
  </div>
</div>

<!-- ===== MODAL KONFIRMASI HAPUS REKENING ===== -->
<div class="modal fade" id="bankDeleteModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body text-center py-4">
        <i class="fa-solid fa-triangle-exclamation" style="font-size:40px;color:#d33;margin-bottom:14px;"></i>
        <h5 style="font-family:'Anton',sans-serif;text-transform:uppercase;">Hapus Rekening?</h5>
        <p style="color:var(--cool-gray);font-size:13px;" id="deleteBankWarning">Tindakan ini tidak dapat dibatalkan.</p>
        <div class="d-flex gap-2 justify-content-center mt-3">
          <button class="btn-modal-cancel" data-bs-dismiss="modal">Batal</button>
          <button class="btn-modal-save" style="background:#d33;color:#fff;" onclick="confirmDeleteBank()">Ya, Hapus</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.bundle.min.js"></script>
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
let bankAccounts = @json($bankAccountsData);
let editingBankId = null;
let deletingBankId = null;

function switchTab(tab){
  document.querySelectorAll(".settings-tab-btn").forEach(btn => btn.classList.remove("active"));
  document.querySelector(`.settings-tab-btn[data-tab="${tab}"]`).classList.add("active");
  document.querySelectorAll(".settings-section").forEach(sec => sec.classList.remove("active"));
  document.getElementById("tab-" + tab).classList.add("active");
}

function showSavedAlert(message){
  const alertBox = document.getElementById("savedAlert");
  document.getElementById("savedAlertText").textContent = message || "Perubahan berhasil disimpan.";
  alertBox.classList.add("show");
  window.scrollTo({top:0, behavior:"smooth"});
  setTimeout(() => alertBox.classList.remove("show"), 2500);
}

function firstError(data){
  if(!data || !data.errors) return data?.message || "Terjadi kesalahan. Coba lagi.";
  const firstKey = Object.keys(data.errors)[0];
  return data.errors[firstKey][0];
}

// ===== INFO TOKO =====
function previewLogo(event){
  const file = event.target.files[0];
  if(!file) return;
  document.getElementById("logoPreview").src = URL.createObjectURL(file);
}

async function saveToko(){
  const spinner = document.getElementById("tokoSpinner");
  const formData = new FormData();
  formData.append("nama_toko", document.getElementById("fNamaToko").value);
  formData.append("tagline", document.getElementById("fTagline").value);
  formData.append("email_toko", document.getElementById("fEmailToko").value);
  formData.append("whatsapp", document.getElementById("fWhatsapp").value);
  formData.append("alamat_toko", document.getElementById("fAlamatToko").value);

  const logoFile = document.getElementById("fLogo").files[0];
  if(logoFile) formData.append("logo", logoFile);

  spinner.style.display = "inline-block";
  try {
    const res = await fetch("{{ route('admin.pengaturan.toko') }}", {
      method: "POST",
      headers: { "X-CSRF-TOKEN": csrfToken, "Accept": "application/json" },
      body: formData,
    });
    const data = await res.json();

    if(!res.ok){
      alert(firstError(data));
      return;
    }

    document.getElementById("logoPreview").src = data.toko.logo;
    showSavedAlert(data.message);
  } catch(err){
    alert("Gagal menyimpan info toko. Coba lagi.");
  } finally {
    spinner.style.display = "none";
  }
}

// ===== PENGIRIMAN =====
async function savePengiriman(){
  try {
    const res = await fetch("{{ route('admin.pengaturan.pengiriman') }}", {
      method: "PUT",
      headers: {
        "X-CSRF-TOKEN": csrfToken,
        "Accept": "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        ongkir_default: document.getElementById("fOngkir").value,
        min_gratis_ongkir: document.getElementById("fMinGratis").value,
        estimasi_pengiriman: document.getElementById("fEstimasi").value,
      }),
    });
    const data = await res.json();

    if(!res.ok){
      alert(firstError(data));
      return;
    }

    showSavedAlert(data.message);
  } catch(err){
    alert("Gagal menyimpan pengaturan pengiriman. Coba lagi.");
  }
}

// ===== PEMBAYARAN (REKENING BANK) =====
function renderBankAccounts(){
  const list = document.getElementById("bankList");
  list.innerHTML = bankAccounts.length
    ? bankAccounts.map(b => `
        <div class="bank-card">
          <div class="bank-icon"><i class="fa-solid fa-building-columns"></i></div>
          <div>
            <div class="bank-name">${b.bank_name} — ${b.account_number}</div>
            <div class="bank-detail">a.n. ${b.account_holder}</div>
          </div>
          <div class="bank-actions">
            <button class="icon-btn-sm" onclick="openEditBankModal(${b.id})"><i class="fa-solid fa-pen"></i></button>
            <button class="icon-btn-sm" onclick="openDeleteBankModal(${b.id})"><i class="fa-solid fa-trash"></i></button>
          </div>
        </div>
      `).join("")
    : `<p style="color:var(--cool-gray);font-size:13px;">Belum ada rekening bank ditambahkan.</p>`;
}

function openAddBankModal(){
  editingBankId = null;
  document.getElementById("bankModalTitle").textContent = "Tambah Rekening Bank";
  document.getElementById("bankForm").reset();
  new bootstrap.Modal(document.getElementById("bankModal")).show();
}

function openEditBankModal(id){
  editingBankId = id;
  const b = bankAccounts.find(x => x.id === id);
  document.getElementById("bankModalTitle").textContent = "Edit Rekening Bank";
  document.getElementById("fBankName").value = b.bank_name;
  document.getElementById("fAccountNumber").value = b.account_number;
  document.getElementById("fAccountHolder").value = b.account_holder;
  new bootstrap.Modal(document.getElementById("bankModal")).show();
}

async function saveBank(){
  const bank_name = document.getElementById("fBankName").value.trim();
  const account_number = document.getElementById("fAccountNumber").value.trim();
  const account_holder = document.getElementById("fAccountHolder").value.trim();

  if(!bank_name || !account_number || !account_holder){
    alert("Semua kolom rekening bank wajib diisi.");
    return;
  }

  const formData = new FormData();
  formData.append("bank_name", bank_name);
  formData.append("account_number", account_number);
  formData.append("account_holder", account_holder);
  if(editingBankId) formData.append("_method", "PUT");

  const url = editingBankId ? `/admin/pengaturan/bank/${editingBankId}` : `/admin/pengaturan/bank`;

  try {
    const res = await fetch(url, {
      method: "POST",
      headers: { "X-CSRF-TOKEN": csrfToken, "Accept": "application/json" },
      body: formData,
    });
    const data = await res.json();

    if(!res.ok){
      alert(firstError(data));
      return;
    }

    if(editingBankId){
      const idx = bankAccounts.findIndex(x => x.id === editingBankId);
      bankAccounts[idx] = data.bank;
    } else {
      bankAccounts.push(data.bank);
    }

    renderBankAccounts();
    bootstrap.Modal.getInstance(document.getElementById("bankModal")).hide();
    showSavedAlert(data.message);
  } catch(err){
    alert("Gagal menyimpan rekening bank. Coba lagi.");
  }
}

function openDeleteBankModal(id){
  deletingBankId = id;
  const b = bankAccounts.find(x => x.id === id);
  document.getElementById("deleteBankWarning").textContent = `Rekening "${b.bank_name} — ${b.account_number}" akan dihapus permanen.`;
  new bootstrap.Modal(document.getElementById("bankDeleteModal")).show();
}

async function confirmDeleteBank(){
  try {
    const formData = new FormData();
    formData.append("_method", "DELETE");

    const res = await fetch(`/admin/pengaturan/bank/${deletingBankId}`, {
      method: "POST",
      headers: { "X-CSRF-TOKEN": csrfToken, "Accept": "application/json" },
      body: formData,
    });
    const data = await res.json();

    if(!res.ok){
      alert(data.message || "Gagal menghapus rekening bank.");
      return;
    }

    bankAccounts = bankAccounts.filter(b => b.id !== deletingBankId);
    renderBankAccounts();
    bootstrap.Modal.getInstance(document.getElementById("bankDeleteModal")).hide();
    showSavedAlert(data.message);
  } catch(err){
    alert("Gagal menghapus rekening bank. Coba lagi.");
  }
}

// ===== NOTIFIKASI =====
async function saveNotifikasi(){
  try {
    const res = await fetch("{{ route('admin.pengaturan.notifikasi') }}", {
      method: "PUT",
      headers: {
        "X-CSRF-TOKEN": csrfToken,
        "Accept": "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({
        notif_pesanan_baru: document.getElementById("notifPesananBaru").checked,
        notif_stok_menipis: document.getElementById("notifStokMenipis").checked,
        notif_pelanggan_baru: document.getElementById("notifPelangganBaru").checked,
      }),
    });
    const data = await res.json();

    if(!res.ok){
      alert(firstError(data));
      return;
    }

    showSavedAlert(data.message);
  } catch(err){
    alert("Gagal menyimpan pengaturan notifikasi. Coba lagi.");
  }
}

// ===== AKUN ADMIN (GANTI PASSWORD) =====
async function changePassword(){
  const current_password = document.getElementById("fCurrentPassword").value;
  const password = document.getElementById("fNewPassword").value;
  const password_confirmation = document.getElementById("fNewPasswordConfirm").value;

  if(!current_password || !password || !password_confirmation){
    alert("Semua kolom password wajib diisi.");
    return;
  }

  try {
    const res = await fetch("{{ route('admin.pengaturan.password') }}", {
      method: "PUT",
      headers: {
        "X-CSRF-TOKEN": csrfToken,
        "Accept": "application/json",
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ current_password, password, password_confirmation }),
    });
    const data = await res.json();

    if(!res.ok){
      alert(firstError(data));
      return;
    }

    document.getElementById("fCurrentPassword").value = "";
    document.getElementById("fNewPassword").value = "";
    document.getElementById("fNewPasswordConfirm").value = "";
    showSavedAlert(data.message);
  } catch(err){
    alert("Gagal mengubah password. Coba lagi.");
  }
}

renderBankAccounts();
</script>
</body>
</html>