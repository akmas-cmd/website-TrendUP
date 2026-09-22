<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pesanan Berhasil — TRENDUP</title>
<link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Anton&family=Work+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
  body { font-family: 'Work Sans', sans-serif; background: #f6f6f6; }
  .success-wrap { max-width: 640px; margin: 60px auto; padding: 0 16px; }
  .success-card { background: #fff; border-radius: 16px; padding: 40px 32px; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.06); }
  .success-icon { width: 80px; height: 80px; border-radius: 50%; background: #d6f5d6; color: #1a7d1a; display:flex; align-items:center; justify-content:center; font-size: 36px; margin: 0 auto 20px; }
  .order-number-box { background: #f6f6f6; border-radius: 12px; padding: 16px; margin: 24px 0; }
  .order-number-box .label { font-size: 13px; color: #888; }
  .order-number-box .value { font-family: 'Anton', sans-serif; font-size: 22px; letter-spacing: 1px; }
  .item-row { display:flex; justify-content:space-between; padding:10px 0; border-bottom:1px solid #eee; text-align:left; }
  .item-row:last-child { border-bottom:none; }
  .total-row { display:flex; justify-content:space-between; padding-top:14px; font-weight:700; font-size:18px; }
  .btn-group-actions { display:flex; gap:12px; margin-top: 28px; }
  .btn-group-actions a { flex:1; padding:12px; border-radius:999px; font-weight:600; text-decoration:none; text-align:center; }
  .btn-primary-trendup { background:#111; color:#fff; }
  .btn-outline-trendup { border:2px solid #111; color:#111; }
</style>
</head>
<body>

<div class="success-wrap">
  <div class="success-card">
    <div class="success-icon"><i class="fa-solid fa-check"></i></div>
    <h2 style="font-family:'Anton',sans-serif;">Pesanan Berhasil Dibuat!</h2>
    <p style="color:#888;">Terima kasih, {{ $order->name }}. Pesananmu sedang kami proses.</p>

    <div class="order-number-box">
      <div class="label">Nomor Pesanan</div>
      <div class="value">{{ $order->order_number }}</div>
    </div>

    <div style="text-align:left;">
      @foreach ($order->items as $item)
        <div class="item-row">
          <div>
            <div style="font-weight:600;">{{ $item->name }}</div>
            <div style="font-size:13px;color:#888;">x{{ $item->qty }}</div>
          </div>
          <div>Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</div>
        </div>
      @endforeach

      <div class="item-row">
        <div>Subtotal</div>
        <div>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</div>
      </div>
      <div class="item-row">
        <div>Ongkos Kirim</div>
        <div>Rp {{ number_format($order->shipping_fee, 0, ',', '.') }}</div>
      </div>
      <div class="total-row">
        <div>Total</div>
        <div>Rp {{ number_format($order->total, 0, ',', '.') }}</div>
      </div>
    </div>

    <p style="font-size:13px;color:#888;margin-top:20px;">
      Metode pembayaran: <strong>{{ $order->payment_method }}</strong><br>
      Kamu bisa cek status pesanan ini kapan saja lewat halaman Riwayat Pesanan pakai nomor di atas.
    </p>

    <div class="btn-group-actions">
      <a href="{{ route('home') }}" class="btn-outline-trendup">Kembali ke Beranda</a>
      <a href="{{ route('profile') }}#tab-orders" class="btn-primary-trendup">Riwayat Pesanan</a>
    </div>
  </div>
</div>

</body>
</html>