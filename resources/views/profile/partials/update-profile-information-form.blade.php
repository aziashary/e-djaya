<section>
  <header>
    <h2 class="section-title">Informasi profil</h2>
    <p class="section-copy">Nama dan email ini digunakan untuk mengenali akun di e-Djaya.</p>
  </header>

  <form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
  </form>

  <form method="post" action="{{ route('profile.update') }}" class="form-stack">
    @csrf
    @method('patch')

    <div class="field-stack">
      <x-input-label for="name" value="Nama lengkap" />
      <x-text-input id="name" name="name" type="text" :value="old('name', $user->name)" required autofocus autocomplete="name" />
      <x-input-error :messages="$errors->get('name')" />
    </div>

    <div class="field-stack">
      <x-input-label for="email" value="Email" />
      <x-text-input id="email" name="email" type="email" :value="old('email', $user->email)" required autocomplete="email" />
      <x-input-error :messages="$errors->get('email')" />

      @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
        <p class="section-copy">
          Email belum diverifikasi.
          <button form="send-verification" class="ui-link">Kirim ulang email verifikasi</button>
        </p>
        @if (session('status') === 'verification-link-sent')
          <p class="ui-status" role="status">Tautan verifikasi baru sudah dikirim.</p>
        @endif
      @endif
    </div>

    <div class="form-actions">
      <x-primary-button>Simpan profil</x-primary-button>
      @if (session('status') === 'profile-updated')
        <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="ui-status" role="status">Profil tersimpan.</p>
      @endif
    </div>
  </form>
</section>
