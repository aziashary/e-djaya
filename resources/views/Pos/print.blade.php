<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Print Struk {{ $transaksi->kode_transaksi }}</title>
  <style>
    body {
      font-family: "Courier New", monospace;
      font-size: 12px;
      margin: 0;
      padding: 10px;
      color: #000;
      background: #fff;
    }
  </style>
</head>
<body>
<script>
(function() {
  const ESC = '\x1B';
  const GS  = '\x1D';

  const center     = ESC + 'a' + '\x01';
  const left       = ESC + 'a' + '\x00';
  const boldOn     = ESC + 'E' + '\x01';
  const boldOff    = ESC + 'E' + '\x00';
  const doubleSize = ESC + '!' + '\x20';
  const normalSize = ESC + '!' + '\x00';
  const cutFull    = GS  + 'V' + '\x00'; // auto-cut full

  // Helper buat rata kanan subtotal
  function padRight(leftText, rightText, width = 48) {
    const totalLength = leftText.length + rightText.length;
    if (totalLength >= width) return leftText + rightText;
    const spaces = ' '.repeat(width - totalLength);
    return leftText + spaces + rightText;
  }

  let struk = '';

  // HEADER
  struk += center + boldOn + doubleSize + 'Warkop Djaya 590\n' + boldOff + normalSize;
  struk += center + 'Jln Raya Puncak No. 590\n';
  struk += left +'------------------------------------------------\n';

  // INFO TRANSAKSI
  struk += left + `Kode   : {{ $transaksi->kode_transaksi }}\n`;
  struk += `Tanggal: {{ $transaksi->tanggal->format('d/m/Y H:i') }}\n`;
  struk += `Kasir  : {{ $transaksi->kasir->name ?? '-' }}\n`;
  struk += '------------------------------------------------\n';

  // ITEMS (daftar belanja)
  @foreach($transaksi->items as $item)
    struk += `{{ $item->nama }}\n`;
    struk += padRight('{{ $item->qty }}  x  {{ number_format($item->harga, 0, ',', '.') }}', '{{ number_format($item->subtotal, 0, ',', '.') }}') + '\n';
  @endforeach

  struk += '------------------------------------------------\n';

  // TOTAL, METODE PEMBAYARAN, DLL
  // DISKON (hanya kalau ada & > 0)
  @if(!empty($transaksi->diskon) && floatval($transaksi->diskon) > 0)
    struk += padRight('Diskon', '{{ $transaksi->diskon }}%') + '\n';
  @endif


  struk += boldOn + padRight('TOTAL', 'Rp {{ number_format($transaksi->total, 0, ',', '.') }}') + '\n' + boldOff;
  struk += padRight('Metode Pembayaran', '{{ strtoupper($transaksi->metode_pembayaran) }}') + '\n';
  // CATATAN (hanya kalau ada)
  @if(!empty($transaksi->catatan))
    struk += '------------------------------------------------\n';
    struk += 'Catatan:\n';
    struk += '{{ trim(preg_replace("/\r|\n/", " ", $transaksi->catatan)) }}\n';
  @endif

  struk += '------------------------------------------------\n';
  struk += center + 'Terima kasih 🙏\n';
  struk += center + '\n\n';
  struk += center + 'Djaya!\n';
  struk += left +'------------------------------------------------\n';

  // AUTO CUT
  struk += cutFull;

  // Kirim ke RawBT (langsung cetak)
  const encoded = encodeURIComponent(struk);
  window.location.href = "rawbt:" + encoded;

  // Tutup otomatis biar clean
  setTimeout(() => window.close(), 2000);
})();
</script>
</body>
</html>
