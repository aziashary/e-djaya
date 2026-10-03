<x-guest-layout>
  <header>
    <h1 class="auth-title">Masuk ke e-Djaya</h1>
    <p class="auth-copy">Gunakan akun toko untuk membuka dashboard atau langsung menuju kasir.</p>
  </header>

  <x-auth-session-status class="mt-5" :status="session('status')" />

  <form method="POST" action="{{ route('login') }}" class="auth-form" id="loginForm">
    @csrf

    <fieldset class="field-stack">
      <legend class="ui-label">Tujuan setelah masuk</legend>
      <div class="role-switch" role="group" aria-label="Pilih tujuan setelah masuk">
        <button type="button" class="role-option" data-role="admin" aria-pressed="true">Dashboard</button>
        <button type="button" class="role-option" data-role="kasir" aria-pressed="false">Kasir POS</button>
      </div>
      <input type="hidden" name="role" id="selectedRole" value="admin">
    </fieldset>

    <div class="field-stack">
      <x-input-label for="username" value="Username" />
      <x-text-input id="username" type="text" name="username" :value="old('username')" placeholder="Masukkan username" required autofocus autocomplete="username" aria-describedby="username-error" />
      <div id="username-error">
        <x-input-error :messages="$errors->get('username')" />
      </div>
    </div>

    <div class="field-stack">
      <x-input-label for="password" value="Password" />
      <div class="password-field">
        <x-text-input id="password" type="password" name="password" placeholder="Masukkan password" required autocomplete="current-password" aria-describedby="password-error" />
        <button type="button" class="password-toggle" id="passwordToggle" aria-controls="password" aria-pressed="false">Lihat</button>
      </div>
      <div id="password-error">
        <x-input-error :messages="$errors->get('password')" />
      </div>
    </div>

    <div class="flex min-h-11 items-center justify-between gap-3">
      <label for="remember" class="flex min-h-11 cursor-pointer items-center gap-2 font-semibold text-[#241915]">
        <input id="remember" type="checkbox" name="remember" class="rounded border-[#8b776f] text-[#b42318] focus:ring-[#b42318]">
        <span>Ingat saya</span>
      </label>

      @if (Route::has('password.request'))
        <a class="ui-link" href="{{ route('password.request') }}">Lupa password?</a>
      @endif
    </div>

    <x-primary-button class="w-full">Masuk ke akun</x-primary-button>
  </form>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const roleInput = document.getElementById('selectedRole');
      const roleButtons = document.querySelectorAll('[data-role]');
      const password = document.getElementById('password');
      const passwordToggle = document.getElementById('passwordToggle');

      roleButtons.forEach((button) => {
        button.addEventListener('click', () => {
          roleButtons.forEach((item) => item.setAttribute('aria-pressed', String(item === button)));
          roleInput.value = button.dataset.role;
        });
      });

      passwordToggle.addEventListener('click', () => {
        const reveal = password.type === 'password';
        password.type = reveal ? 'text' : 'password';
        passwordToggle.textContent = reveal ? 'Sembunyikan' : 'Lihat';
        passwordToggle.setAttribute('aria-pressed', String(reveal));
      });
    });
  </script>
</x-guest-layout>
