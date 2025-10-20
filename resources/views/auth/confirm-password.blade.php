{{-- resources/views/auth/confirm-password.blade.php --}}
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Confirm Password</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  {{-- Include your CSS (Bootstrap/Metronic/Tailwind) --}}
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-5">
        @if (session('status'))
          <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div class="card shadow-sm">
          <div class="card-body">
            <h4 class="mb-3">Confirm Password</h4>
            <p class="text-muted small mb-4">
              This is a secure area of the application. Please confirm your password before continuing.
            </p>

            <form method="POST" action="{{ route('password.confirm') }}">
              @csrf

              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input id="password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" required autocomplete="current-password">
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <div class="d-grid">
                <button type="submit" class="btn btn-primary">Confirm</button>
              </div>
            </form>

            <div class="mt-3 text-center">
              <a href="{{ url()->previous() }}" class="text-decoration-none">← Back</a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</body>
</html>
