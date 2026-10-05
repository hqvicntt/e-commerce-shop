<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Shop - Product Catalog</title>
    <!-- Include Bootstrap 5 CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- Custom Style for Hover Effect -->
    <style>
        .product-card {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15) !important;
        }
    </style>
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
        <h1 class="text-center fw-bold mb-4">Our Products</h1>
        <p class="text-muted text-center mb-5">Explore our wide range of high-quality items filtered specially for you.
        </p>

        <!-- Product Grid System -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">

            @foreach($products as $product)
            <div class="col">
                <!-- Individual Product Card -->
                <div class="card h-100 border-0 shadow-sm product-card">
                    <!-- Placeholder Image (Since our database image column is null) -->
                    <img src="https://placehold.co/600x400/png?text=Hello+World&font=roboto" class="card-img-top"
                        alt="{{ $product->name }}">

                    <!-- Card Body Container -->
                    <div class="card-body d-flex flex-column">
                        <!-- Category Badge (Belongs to Relationship) -->
                        <span class="badge bg-secondary align-self-start mb-2">
                            {{ $product->category->name }}
                        </span>

                        <!-- Product Name -->
                        <h5 class="card-title fw-bold text-dark text-truncate" title="{{ $product->name }}">
                            <a href="{{ route('products.show', ['id' => $product->id]) }}"
                                class="text-dark text-decoration-none hover-primary">
                                {{ $product->name }}
                            </a>
                        </h5>

                        <!-- Product Description Snippet -->
                        <p class="card-text text-muted text-sm flex-grow-1">
                            {{ Str::limit($product->description, 80, '...') }}
                        </p>

                        <!-- Price and Action Area -->
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="fs-5 fw-bold text-primary">${{ number_format($product->price, 2) }}</span>
                                <small class="text-muted">Stock: {{ $product->quantity }}</small>
                            </div>
                            <!-- Call to Action Button -->
                            <a href="{{ route('cart.add', ['id' => $product->id]) }}"
                                class="btn btn-dark w-100 mt-2 @if($product->quantity <= 0) disabled @endif">
                                Add to Cart
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

        </div>
    </div>

    <!-- Include Bootstrap 5 JS Bundle via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>

</html>