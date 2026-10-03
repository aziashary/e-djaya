<x-guest-layout>
  <header>
    <h1 class="auth-title">Verifikasi email</h1>
    <p class="auth-copy">Buka tautan yang dikirim ke email akun. Kirim ulang jika pesan belum diterima.</p>
  </header>

  @if (session('status') === 'verification-link-sent')
    <div class="ui-status mt-5" role="status">Tautan verifikasi baru sudah dikirim.</div>
  @endif

  <div class="form-actions mt-6">
    <form method="POST" action="{{ route('verification.send') }}">
      @csrf
      <x-primary-button>Kirim ulang verifikasi</x-primary-button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button type="submit" class="ui-button-secondary">Keluar</button>
    </form>
  </div>
</x-guest-layout>
