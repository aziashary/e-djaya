<x-guest-layout>
  <header>
    <h1 class="auth-title">Atur ulang password</h1>
    <p class="auth-copy">Masukkan email akun. Tautan pengaturan password akan dikirim jika email terdaftar.</p>
  </header>

  <x-auth-session-status class="mt-5" :status="session('status')" />

  <form method="POST" action="{{ route('password.email') }}" class="auth-form">
    @csrf
    <div class="field-stack">
      <x-input-label for="email" value="Email" />
      <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="email" />
      <x-input-error :messages="$errors->get('email')" />
    </div>

    <div class="form-actions">
      <a class="ui-link" href="{{ route('login') }}">Kembali ke halaman masuk</a>
      <x-primary-button>Kirim tautan reset</x-primary-button>
    </div>
  </form>
</x-guest-layout>
