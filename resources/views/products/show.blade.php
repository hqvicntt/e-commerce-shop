<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Product Details</title>
    <!-- Include Bootstrap 5 CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>

<body class="bg-light">

    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold" href="{{ route('products.index') }}">E-SHOP</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('products.index') }}">Home</a>
                    </li>
                    <li class="nav-item me-3">
                        <!-- Read directly from session, default to 0 items if cart session is empty or null -->
                        <a class="nav-link @if(Route::is('cart.index')) active @endif" href="{{ route('cart.index') }}">
                            Cart ({{ count(session()->get('cart', [])) }})
                        </a>
                    </li>

                    <!-- Check if the user is logged in -->
                    @auth
                    <!-- Display "My Orders" link for authenticated users -->
                    <li class="nav-item me-2">
                        <a class="nav-link @if(Route::is('products.orders')) active @endif" href="{{ route('products.orders') }}">My Orders</a>
                    </li>
                    <!-- Display authenticated user's name as a welcome greeting -->
                    <li class="nav-item text-white me-3">
                        Welcome, <span class="fw-bold text-warning">{{ Auth::user()->name }}</span>
                    </li>
                    <!-- Logout Form (Must use POST method for security) -->
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-light fw-semibold">Log Out</button>
                        </form>
                    </li>
                    @else
                    <!-- Display access links for guests / unauthenticated users -->
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="{{ route('login') }}">Sign In</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-sm btn-warning fw-bold ms-2 px-3" href="{{ route('register') }}">Sign Up</a>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content Container -->
    <div class="container my-5">

        <!-- Display Global Success/Error Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <span class="fw-semibold">{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <span class="fw-semibold">{{ session('error') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        <!-- Back Button -->
        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary mb-4">
            &larr; Back to Catalog
        </a>

        <!-- Product Detail Card -->
        <div class="card border-0 shadow-sm p-4">
            <div class="row g-5">

                <!-- Left Column: Product Image -->
                <div class="col-md-6 text-center">
                    <a href="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/600x400/png?text=Hello+World&font=roboto' }}" target="_blank">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/600x400/png?text=Hello+World&font=roboto' }}" class="img-fluid rounded shadow-sm w-100" style="max-height: 400px; object-fit: cover; cursor: pointer;" alt="{{ $product->name }}">
                    </a>
                </div>

                <!-- Right Column: Product Information -->
                <div class="col-md-6 d-flex flex-column justify-content-center">
                    <!-- Category Badge -->
                    <span class="badge bg-secondary align-self-start mb-3">
                        {{ $product->category->name }}
                    </span>

                    <!-- Product Name -->
                    <h1 class="fw-bold text-dark mb-2">{{ $product->name }}</h1>

                    <!-- Availability / Stock Status -->
                    <p class="text-muted mb-4">
                        Availability:
                        @if($product->quantity > 0)
                        <span class="text-success fw-bold">In Stock ({{ $product->quantity }} available)</span>
                        @else
                        <span class="text-danger fw-bold">Out of Stock</span>
                        @endif
                    </p>

                    <!-- Product Price -->
                    <h2 class="text-primary fw-bold mb-4">${{ number_format($product->price, 2) }}</h2>

                    <!-- Product Description Heading -->
                    <h5 class="fw-bold text-dark">Product Description:</h5>
                    <!-- Detailed Description Text -->
                    <p class="text-muted mb-5 justify-content-center" style="text-align: justify;">
                        {{ $product->description }}
                    </p>

                    <!-- Action Area Buttons -->
                    <div class="d-grid gap-2 d-md-flex">
                        <a href="{{ route('cart.add', ['id' => $product->id]) }}"
                            class="btn btn-dark w-100 mt-2 @if($product->quantity <= 0) disabled @endif">
                            Add to Cart
                        </a>
                        <button class="btn btn-outline-dark btn-lg px-4" href="#">
                            Add to Wishlist
                        </button>
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