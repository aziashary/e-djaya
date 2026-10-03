<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Cetak Struk {{ $transaksi->kode_transaksi }}</title>
  <style>
    *,
    *::before,
    *::after { box-sizing: border-box; }
    body { min-height: 100vh; display: grid; place-items: center; margin: 0; padding: 24px; background: #fff8f3; color: #241915; font-family: system-ui, sans-serif; }
    main { width: min(100%, 440px); padding: 24px; border: 1px solid #e7d8d1; border-radius: 12px; background: #fff; text-align: center; }
    h1 { margin: 0 0 8px; font-size: 24px; }
    p { margin: 0; color: #675a54; }
    button { min-height: 44px; margin-top: 20px; padding: 10px 16px; border: 1px solid #b42318; border-radius: 8px; background: #b42318; color: #fff; font-weight: 700; cursor: pointer; }
    button:focus-visible { outline: 3px solid #b42318; outline-offset: 3px; }
    [hidden] { display: none; }
  </style>
</head>
<body>
  <main aria-live="polite">
    <h1 id="print-title">Mengirim struk ke printer</h1>
    <p id="print-status">Jangan tutup halaman sampai proses selesai.</p>
    <button type="button" id="close-page" hidden>Tutup halaman</button>
  </main>

@php
  $receiptDate = $transaksi->tanggal->format('d/m/Y H:i');
  $receiptCashier = $transaksi->kasir->name ?? '-';
  $receiptCustomer = $transaksi->nama_customer ?? '-';
  $receiptOrderType = $transaksi->makan_dimana ?? '-';
  $receiptTotal = 'Rp ' . number_format($transaksi->total, 0, ',', '.');
  $receiptPaymentMethod = strtoupper($transaksi->metode_pembayaran);
  $receiptNote = !empty($transaksi->catatan)
    ? trim(preg_replace('/\r|\n/', ' ', $transaksi->catatan))
    : null;
  $receiptCopies = request('copies', 1);
@endphp

<script>
(() => {
  const content = [];

  @if(strtolower((string) Auth::user()->level) === 'staff')
    content.push({ type: 'text', text: 'KOPI RANU', align: 'center', bold: true, size: 'large' });
    content.push({ type: 'text', text: 'Jl. Raya Puncak - Gadog, Tugu Selatan, Bogor', align: 'center' });
  @else
    content.push({ type: 'text', text: 'Warkop Djaya 590', align: 'center', bold: true, size: 'large' });
    content.push({ type: 'text', text: 'Jln Raya Puncak No. 590', align: 'center' });
  @endif

  content.push({ type: 'divider' });
  content.push({ type: 'row', left: 'Kode', right: @json($transaksi->kode_transaksi) });
  content.push({ type: 'row', left: 'Tanggal', right: @json($receiptDate) });
  content.push({ type: 'row', left: 'Kasir', right: @json($receiptCashier) });
  content.push({ type: 'row', left: 'Atas Nama', right: @json($receiptCustomer) });
  content.push({ type: 'text', text: @json($receiptOrderType), align: 'left' });
  content.push({ type: 'divider' });

  @foreach($transaksi->items as $item)
    @php
      $receiptLineQuantity = $item->qty . ' x ' . number_format($item->harga, 0, ',', '.');
      $receiptLineSubtotal = number_format($item->subtotal, 0, ',', '.');
    @endphp
    content.push({ type: 'text', text: @json($item->nama), align: 'left' });
    content.push({ type: 'row', left: @json($receiptLineQuantity), right: @json($receiptLineSubtotal) });
  @endforeach

  content.push({ type: 'divider' });
  @if(!empty($transaksi->diskon) && floatval($transaksi->diskon) > 0)
    content.push({ type: 'row', left: 'Diskon', right: @json($transaksi->diskon . '%') });
  @endif
  content.push({ type: 'row', left: 'TOTAL', right: @json($receiptTotal), bold: true });
  content.push({ type: 'row', left: 'Metode Pembayaran', right: @json($receiptPaymentMethod) });

  @if($receiptNote !== null)
    content.push({ type: 'divider' });
    content.push({ type: 'text', text: 'Catatan:', align: 'left' });
    content.push({ type: 'text', text: @json($receiptNote), align: 'left' });
  @endif

  content.push({ type: 'divider' });
  @if(strtolower((string) Auth::user()->level) === 'staff')
    content.push({ type: 'text', text: 'HARGA DI ATAS ADALAH', align: 'center' });
    content.push({ type: 'text', text: 'HARGA PROMO KOPI PAGI', align: 'center' });
  @endif
  content.push({ type: 'text', text: 'Terima kasih!', align: 'center' });
  content.push({ type: 'text', text: 'Djaya!', align: 'center' });

  const status = document.getElementById('print-status');
  const title = document.getElementById('print-title');
  const closeButton = document.getElementById('close-page');
  const copies = Math.max(1, Number(@json($receiptCopies)) || 1);

  closeButton.addEventListener('click', () => window.close());

  const printJob = () => fetch('http://localhost:9100/print', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ cut: true, content })
  });

  const run = async () => {
    try {
      for (let copy = 0; copy < copies; copy += 1) {
        status.textContent = `Mencetak salinan ${copy + 1} dari ${copies}.`;
        const response = await printJob();
        if (!response.ok) throw new Error(response.statusText);
        if (copy < copies - 1) await new Promise((resolve) => setTimeout(resolve, 1500));
      }
      title.textContent = 'Struk terkirim';
      status.textContent = 'Halaman akan ditutup otomatis.';
      setTimeout(() => window.close(), 1000);
    } catch (error) {
      title.textContent = 'Struk gagal dicetak';
      status.textContent = 'Pastikan aplikasi printer lokal pada port 9100 sedang berjalan, lalu coba cetak ulang dari riwayat transaksi.';
      closeButton.hidden = false;
      closeButton.focus();
    }
  };

  run();
})();
</script>
</body>
</html>
