<section>
  <header>
    <h2 class="section-title">Hapus akun</h2>
    <p class="section-copy">Tindakan ini menghapus akun secara permanen. Pastikan data yang masih dibutuhkan sudah disimpan.</p>
  </header>

  <div class="mt-6">
    <x-danger-button x-data="" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">Hapus akun</x-danger-button>
  </div>

  <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
    <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
      @csrf
      @method('delete')

      <h2 class="section-title">Konfirmasi penghapusan akun</h2>
      <p class="section-copy">Masukkan password untuk menghapus akun dan seluruh data terkait secara permanen.</p>

      <div class="field-stack mt-6">
        <x-input-label for="delete_account_password" value="Password" />
        <x-text-input id="delete_account_password" name="password" type="password" placeholder="Masukkan password" autocomplete="current-password" />
        <x-input-error :messages="$errors->userDeletion->get('password')" />
      </div>

      <div class="form-actions mt-6 justify-end">
        <x-secondary-button x-on:click="$dispatch('close')">Batal</x-secondary-button>
        <x-danger-button>Hapus akun permanen</x-danger-button>
      </div>
    </form>
  </x-modal>
</section>
