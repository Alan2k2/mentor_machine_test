<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Mini ERP</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom Styles -->
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
    <div class="login-bg">
        <div class="login-card">
            <div class="login-logo">
                <i class="ph-fill ph-circles-four mb-2" style="font-size: 2.5rem;"></i><br>
                Mini ERP
            </div>
            
            @if ($errors->any())
                <div class="alert alert-danger border-0 bg-danger text-white rounded-3 mb-4 py-2 px-3">
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li class="small">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-floating mb-4">
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="name@example.com" required autofocus>
                    <label for="email" class="text-muted"><i class="ph ph-envelope-simple me-2"></i>Email Address</label>
                </div>
                
                <div class="form-floating mb-4">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password" class="text-muted"><i class="ph ph-lock-key me-2"></i>Password</label>
                </div>
                
                <div class="d-grid mt-5">
                    <button type="submit" class="btn btn-primary py-3 fs-6 fw-bold d-flex align-items-center justify-content-center gap-2">
                        <span>Sign In</span>
                        <i class="ph-bold ph-arrow-right"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
