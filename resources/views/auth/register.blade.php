<x-guest-layout>
  <header>
    <h1 class="auth-title">Buat akun pengguna</h1>
    <p class="auth-copy">Isi identitas dan hak akses untuk pengguna e-Djaya.</p>
  </header>

  <form method="POST" action="{{ route('register') }}" class="auth-form">
    @csrf

    <div class="field-stack">
      <x-input-label for="name" value="Nama lengkap" />
      <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
      <x-input-error :messages="$errors->get('name')" />
    </div>

    <div class="field-stack">
      <x-input-label for="username" value="Username" />
      <x-text-input id="username" type="text" name="username" :value="old('username')" required autocomplete="username" />
      <x-input-error :messages="$errors->get('username')" />
    </div>

    <div class="field-stack">
      <x-input-label for="email" value="Email" />
      <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="email" />
      <x-input-error :messages="$errors->get('email')" />
    </div>

    <div class="field-stack">
      <x-input-label for="level" value="Hak akses" />
      <select name="level" id="level" class="ui-input" required>
        <option value="">Pilih hak akses</option>
        <option value="Admin" @selected(old('level') === 'Admin')>Admin</option>
        <option value="Kasir" @selected(old('level') === 'Kasir')>Kasir</option>
      </select>
      <x-input-error :messages="$errors->get('level')" />
    </div>

    <div class="field-stack">
      <x-input-label for="password" value="Password" />
      <x-text-input id="password" type="password" name="password" required autocomplete="new-password" />
      <x-input-error :messages="$errors->get('password')" />
    </div>

    <div class="field-stack">
      <x-input-label for="password_confirmation" value="Ulangi password" />
      <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
      <x-input-error :messages="$errors->get('password_confirmation')" />
    </div>

    <div class="form-actions">
      <a class="ui-link" href="{{ route('login') }}">Kembali ke halaman masuk</a>
      <x-primary-button>Buat akun</x-primary-button>
    </div>
  </form>
</x-guest-layout>
