<section>
  <header>
    <h2 class="section-title">Ganti password</h2>
    <p class="section-copy">Gunakan password yang panjang dan tidak dipakai pada akun lain.</p>
  </header>

  <form method="post" action="{{ route('password.update') }}" class="form-stack">
    @csrf
    @method('put')

    <div class="field-stack">
      <x-input-label for="update_password_current_password" value="Password saat ini" />
      <x-text-input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password" />
      <x-input-error :messages="$errors->updatePassword->get('current_password')" />
    </div>

    <div class="field-stack">
      <x-input-label for="update_password_password" value="Password baru" />
      <x-text-input id="update_password_password" name="password" type="password" autocomplete="new-password" />
      <x-input-error :messages="$errors->updatePassword->get('password')" />
    </div>

    <div class="field-stack">
      <x-input-label for="update_password_password_confirmation" value="Ulangi password baru" />
      <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" />
      <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" />
    </div>

    <div class="form-actions">
      <x-primary-button>Simpan password</x-primary-button>
      @if (session('status') === 'password-updated')
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="ui-status" role="status">Password tersimpan.</p>
      @endif
    </div>
  </form>
</section>
