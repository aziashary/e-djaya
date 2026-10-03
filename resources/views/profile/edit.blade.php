<x-app-layout>
  <x-slot name="header">
    <div>
      <h1 class="text-2xl font-extrabold tracking-tight text-[#241915]">Profil akun</h1>
      <p class="mt-1 text-[#675a54]">Perbarui identitas akun, password, atau pengaturan akun.</p>
    </div>
  </x-slot>

  <div class="profile-grid">
    <div class="profile-card">
      @include('profile.partials.update-profile-information-form')
    </div>

    <div class="profile-card">
      @include('profile.partials.update-password-form')
    </div>

    <div class="profile-card">
      @include('profile.partials.delete-user-form')
    </div>
  </div>
</x-app-layout>
