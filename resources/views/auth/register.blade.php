<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Shop - Sign Up</title>
    <!-- Include Bootstrap 5 CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body class="bg-light">

    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-5">

                <!-- Registration Card Container -->
                <div class="card border-0 shadow-sm p-4">
                    <div class="card-body">
                        <!-- Heading Title -->
                        <h2 class="fw-bold text-center text-dark mb-2">Create Account</h2>
                        <p class="text-muted text-center mb-4">Get started with your free e-shopping account today.</p>

                        <!-- Form element sending data via POST to registration store route -->
                        <form action="{{ route('register.store') }}" method="POST">
                            <!-- CSRF Protection Token (Mandatory in Laravel Forms) -->
                            @csrf

                            <!-- Full Name Input Group -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Full Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name') }}" placeholder="John Doe">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Address Input Group -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Email Address</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                    name="email" value="{{ old('email') }}" placeholder="example@domain.com">
                                @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password Input Group -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" placeholder="Minimum 8 characters">
                                @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password Input Group -->
                            <div class="mb-4">
                                <label for="password_confirmation" class="form-label fw-semibold">Confirm
                                    Password</label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" placeholder="Re-type your password">
                            </div>

                            <!-- Submit Action Button -->
                            <button type="submit" class="btn btn-dark w-100 py-2 fw-bold">Sign Up</button>
                        </form>

                        <!-- Link navigation toggle for existing users -->
                        <div class="text-center mt-4">
                            <p class="mb-0 text-muted">Already have an account? <a href="{{ route('login') }}"
                                    class=" text-dark fw-bold text-decoration-none">Sign In</a></p>
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