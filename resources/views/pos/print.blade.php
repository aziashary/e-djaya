<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Print Struk {{ $transaksi->kode_transaksi }}</title>
   <style>
  @page {
    margin: 0; /* hilangin margin print bawaan */
  }
  body {
    font-family: "Courier New", monospace;
    font-size: 12px;
    margin: 0;
    padding: 0 10px 10px 10px; /* atas 0px biar ga ada spasi kosong */
    color: #000;
    background: #fff;
  }
</style>
</head>
<body>
<script>
(function() {
  const content = [];

  // HEADER
  @if(Auth::user()->level === 'staff')
    content.push({ type: "text", text: "Ranu", align: "center", bold: true, size: "large" });
    content.push({ type: "text", text: "Jl. Raya Puncak - Gadog, Tugu Selatan, Bogor", align: "center" });
  @else
    content.push({ type: "text", text: "Warkop Djaya 590", align: "center", bold: true, size: "large" });
    content.push({ type: "text", text: "Jln Raya Puncak No. 590", align: "center" });
  @endif
  content.push({ type: "divider" });

  // INFO TRANSAKSI
  content.push({ type: "row", left: "Kode", right: "{{ $transaksi->kode_transaksi }}" });
  content.push({ type: "row", left: "Tanggal", right: "{{ $transaksi->tanggal->format('d/m/Y H:i') }}" });
  content.push({ type: "row", left: "Kasir", right: "{{ $transaksi->kasir->name ?? '-' }}" });
  content.push({ type: "row", left: "Atas Nama", right: "{{ $transaksi->nama_customer ?? '-' }}" });
  content.push({ type: "text", text: "{{ $transaksi->makan_disini ?? '-' }}", align: "left" });
  content.push({ type: "divider" });

  // ITEMS (daftar belanja)
  @foreach($transaksi->items as $item)
    content.push({ type: "text", text: "{{ $item->nama }}", align: "left" });
    content.push({ type: "row", left: "{{ $item->qty }}  x  {{ number_format($item->harga, 0, ',', '.') }}", right: "{{ number_format($item->subtotal, 0, ',', '.') }}" });
  @endforeach
  content.push({ type: "divider" });

  // DISKON
  @if(!empty($transaksi->diskon) && floatval($transaksi->diskon) > 0)
    content.push({ type: "row", left: "Diskon", right: "{{ $transaksi->diskon }}%" });
  @endif

  // TOTAL & METODE PEMBAYARAN
  content.push({ type: "row", left: "TOTAL", right: "Rp {{ number_format($transaksi->total, 0, ',', '.') }}", bold: true });
  content.push({ type: "row", left: "Metode Pembayaran", right: "{{ strtoupper($transaksi->metode_pembayaran) }}" });

  // CATATAN
  @if(!empty($transaksi->catatan))
    content.push({ type: "divider" });
    content.push({ type: "text", text: "Catatan:", align: "left" });
    content.push({ type: "text", text: "{{ trim(preg_replace('/\\r|\\n/', ' ', $transaksi->catatan)) }}", align: "left" });
  @endif

  content.push({ type: "divider" });

  // FOOTER
  @if(Auth::user()->level === 'staff')
    content.push({ type: "text", text: "Terima Kasih!", align: "center" });
  @else
    content.push({ type: "text", text: "Terima kasih!", align: "center" });
    content.push({ type: "text", text: "Djaya!", align: "center" });
  @endif

  // EXTRA SPACING
  // (Dihapus agar tidak ada space kosong sama sekali di bawah sebelum di-cut)

  const payload = {
    cut: true,
    content: content
  };

  const copies = {{ request('copies', 1) }};

  const printJob = () => {
    return fetch('http://localhost:9100/print', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify(payload)
    });
  };

  const doPrint = async () => {
    try {
      for (let i = 0; i < copies; i++) {
        const response = await printJob();
        if (!response.ok) {
          console.error('Print failed:', response.statusText);
        }
        // delay 1.5 detik jika ada copy selanjutnya biar mesin print ada jeda
        if (i < copies - 1) {
          await new Promise(r => setTimeout(r, 1500));
        }
      }
      setTimeout(() => window.close(), 1000);
    } catch (error) {
      console.error('Error:', error);
      alert('Gagal print ke localhost:9100. Pastikan aplikasi print lokal berjalan.');
      setTimeout(() => window.close(), 1000);
    }
  };
  
  doPrint();
})();
</script>
</body>
</html>