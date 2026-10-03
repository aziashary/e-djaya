@if(session('success'))
  <div class="alert alert-success" role="status">{{ session('success') }}</div>
@endif

@if(session('error'))
  <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
@endif

@if($errors->any())
  <div class="alert alert-danger" role="alert" tabindex="-1" id="validation-summary">
    <strong>Periksa kembali data berikut:</strong>
    <ul class="mb-0 mt-2">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
