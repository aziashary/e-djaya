@if($paginator->hasPages())
  <nav class="history-pagination" aria-label="Halaman riwayat transaksi">
    <div class="pagination mb-0">
      @if($paginator->onFirstPage())
        <span class="page-link disabled" aria-disabled="true">Sebelumnya</span>
      @else
        <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Sebelumnya</a>
      @endif
      <span class="page-current" aria-current="page">{{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}</span>
      @if($paginator->hasMorePages())
        <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Berikutnya</a>
      @else
        <span class="page-link disabled" aria-disabled="true">Berikutnya</span>
      @endif
    </div>
  </nav>
@endif
