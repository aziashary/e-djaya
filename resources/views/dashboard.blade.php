<x-app-layout>
  <x-slot name="header">
    <div>
      <h1 class="text-2xl font-extrabold tracking-tight text-[#241915]">Dashboard</h1>
      <p class="mt-1 text-[#675a54]">Akun Anda sudah terhubung ke e-Djaya.</p>
    </div>
  </x-slot>

  <section class="profile-card">
    <h2 class="section-title">Pilih area kerja</h2>
    <p class="section-copy">Buka dashboard operasional atau mulai transaksi pada POS.</p>
    <div class="form-actions mt-6 justify-start">
      <a href="{{ route('dashboard') }}" class="ui-button-secondary">Dashboard operasional</a>
      <a href="{{ route('pos.index') }}" class="ui-button-primary">Buka POS</a>
    </div>
  </section>
</x-app-layout>
