<x-guest-layout>
  <header>
    <h1 class="auth-title">Konfirmasi password</h1>
    <p class="auth-copy">Masukkan password akun untuk melanjutkan ke area yang dilindungi.</p>
  </header>

  <form method="POST" action="{{ route('password.confirm') }}" class="auth-form">
    @csrf
    <div class="field-stack">
      <x-input-label for="password" value="Password" />
      <x-text-input id="password" type="password" name="password" required autocomplete="current-password" />
      <x-input-error :messages="$errors->get('password')" />
    </div>
    <x-primary-button class="w-full">Konfirmasi dan lanjutkan</x-primary-button>
  </form>
</x-guest-layout>
