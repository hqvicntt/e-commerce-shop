<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account Profile</title>
    <!-- Include Bootstrap 5 CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body class="bg-light">

    <!-- Universal Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('products.index') }}">E-SHOP</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.index') }}">Home</a>
                    </li>
                    <li class="nav-item me-3">
                        <a class="nav-link" href="{{ route('cart.index') }}">
                            Cart ({{ count(session()->get('cart', [])) }})
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('products.orders') }}">My Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active fw-bold text-warning" href="{{ route('profile.edit') }}">
                            Hello, {{ Auth::user()->name }}
                        </a>
                    </li>
                    <li class="nav-item ms-3">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-light fw-semibold">Log Out</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <h1 class="fw-bold text-dark mb-2">Account Settings</h1>
                <p class="text-muted mb-4">Manage your personal profile security configurations and password parameters.</p>

                <!-- Profile Management Grid System -->
                <div class="row g-4">
                    
                    <!-- Left Column: Account Metadata Card -->
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm p-4 text-center bg-white rounded">
                            <div class="mb-3">
                                <div class="bg-dark text-white rounded-circle d-inline-flex align-items-center justify-content-center fw-bold fs-2" style="width: 80px; height: 80px;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            </div>
                            <h5 class="fw-bold text-dark m-0">{{ $user->name }}</h5>
                            <p class="text-muted small mb-3">{{ $user->email }}</p>
                            <hr>
                            <div class="text-start">
                                <small class="text-uppercase text-muted fw-bold d-block mb-1" style="font-size: 11px;">Account Role</small>
                                <span class="badge bg-secondary text-capitalize fs-6 px-3 py-1.5 w-100 text-center mb-3">{{ $user->role }}</span>
                                
                                <small class="text-uppercase text-muted fw-bold d-block mb-1" style="font-size: 11px;">Member Since</small>
                                <p class="text-secondary small fw-medium">{{ $user->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Interactive Settings Input Form -->
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm p-4 bg-white rounded">
                            
                            <h4 class="fw-bold text-dark mb-4">Update Profile Details</h4>

                            <!-- Triggering Global Form Action Session Alerts -->
                            @if($errors->has('current_password'))
                                <div class="alert alert-danger border-0 shadow-sm mb-4 small" role="alert">
                                    <span class="fw-semibold">&cross; {{ $errors->first('current_password') }}</span>
                                </div>
                            @endif

                            <form action="{{ route('profile.update') }}" method="POST">
                                @csrf

                                <!-- Full Name Field Box -->
                                <div class="mb-3">
                                    <label for="name" class="form-label fw-semibold">Display Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Disabled Email Informational Box -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold text-muted">Email Address (Cannot be altered)</label>
                                    <input type="email" class="form-control bg-light text-muted" value="{{ $user->email }}" disabled>
                                    <div class="form-text text-muted">Your email credentials are locked for safety security operations.</div>
                                </div>

                                <hr class="my-4">
                                <h5 class="fw-bold text-dark mb-3">Change Security Password</h5>
                                <p class="text-muted small mb-3">Leave the fields below completely blank if you do not wish to perform password alteration updates.</p>

                                <!-- Current Password Input Box -->
                                <div class="mb-3">
                                    <label for="current_password" class="form-label fw-semibold">Current Password</label>
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password" placeholder="Input old password credential verification">
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <!-- New Password Input Box -->
                                    <div class="col-md-6 mb-3">
                                        <label for="new_password" class="form-label fw-semibold">New Password</label>
                                        <input type="password" class="form-control @error('new_password') is-invalid @enderror" id="new_password" name="new_password" placeholder="Minimum 8 characters">
                                        @error('new_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Confirm Password Input Box -->
                                    <div class="col-md-6 mb-3">
                                        <label for="new_password_confirmation" class="form-label fw-semibold">Confirm New Password</label>
                                        <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" placeholder="Re-type new password">
                                    </div>
                                </div>

                                <!-- Action Submit Controls Button -->
                                <button type="submit" class="btn btn-dark w-100 py-3 mt-3 fw-bold">Save Profile Modifications</button>

                            </form>
                        </div>
                    </div>

                </div>
                
            </div>
        </div>
    </div>

    <!-- Include Bootstrap 5 JS Bundle via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>
</html>
