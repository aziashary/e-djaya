<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Struk {{ $transaksi->kode_transaksi }}</title>
  <style>
    @page { size: 58mm auto; margin: 0; }
    body {
      font-family: 'Courier New', monospace;
      font-size: 12px;
      margin: 0;
      padding: 10px;
    }
    .center { text-align: center; }
    .bold { font-weight: bold; }
    .line { border-top: 1px dashed #000; margin: 5px 0; }
    .total { font-size: 14px; font-weight: bold; }
    .right { text-align: right; }
    table { width: 100%; border-collapse: collapse; }
    td { vertical-align: top; }
  </style>
</head>
<body onload="window.print(); window.close();">

  <div class="center bold">Warkop Djaya 590</div>
  <div class="center">Jl. Raya Puncak No. 590</div>

  <div class="line"></div>
  <div>Kode: {{ $transaksi->kode_transaksi }}</div>
  <div>Tanggal: {{ $transaksi->tanggal->format('d/m/Y H:i') }}</div>
  <div>Kasir: {{ $transaksi->kasir->name ?? '-' }}</div>

  <div class="line"></div>

  <table>
    @foreach($transaksi->items as $item)
    <tr>
      <td colspan="2">{{ $item->nama }}</td>
    </tr>
    <tr>
      <td>{{ $item->qty }} x {{ number_format($item->harga) }}</td>
      <td class="right">{{ number_format($item->subtotal) }}</td>
    </tr>
    @endforeach
  </table>

  <div class="line"></div>
  <table>
    <tr><td>Subtotal</td><td class="right">{{ number_format($transaksi->subtotal) }}</td></tr>
    {{-- <tr><td>Diskon</td><td class="right">{{ $transaksi->diskon }}%</td></tr> --}}
    <tr class="bold total"><td>Total</td><td class="right">{{ number_format($transaksi->total) }}</td></tr>
    @if($transaksi->metode_pembayaran == 'cash')
      <tr><td>Cash</td><td class="right">✔</td></tr>
    @else
      <tr><td>QRIS</td><td class="right">✔</td></tr>
    @endif
  </table>

  <div class="line"></div>
  <div class="center">Terima kasih 🙏</div>
  <div class="center">Djaya!</div>
</body>
</html>
