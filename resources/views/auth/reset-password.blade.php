<x-guest-layout>
  <header>
    <h1 class="auth-title">Buat password baru</h1>
    <p class="auth-copy">Gunakan password baru untuk mengakses kembali akun e-Djaya.</p>
  </header>

  <form method="POST" action="{{ route('password.store') }}" class="auth-form">
    @csrf
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <div class="field-stack">
      <x-input-label for="email" value="Email" />
      <x-text-input id="email" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="email" />
      <x-input-error :messages="$errors->get('email')" />
    </div>

    <div class="field-stack">
      <x-input-label for="password" value="Password baru" />
      <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
      <x-input-error :messages="$errors->get('password')" />
    </div>

    <div class="field-stack">
      <x-input-label for="password_confirmation" value="Ulangi password baru" />
      <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
      <x-input-error :messages="$errors->get('password_confirmation')" />
    </div>

    <x-primary-button class="w-full">Simpan password baru</x-primary-button>
  </form>
</x-guest-layout>
