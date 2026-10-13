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
                    <!-- Display "My Orders" link for authenticated users -->
                    <li class="nav-item me-2">
                        <a class="nav-link @if(Route::is('products.orders')) active @endif" href="{{ route('products.orders') }}">My Orders</a>
                    </li>
                    <!-- Display authenticated user's name as a welcome greeting -->
                    <li class="nav-item me-3">
                        <a class="nav-link @if(Route::is('profile.edit')) active fw-bold text-warning @endif" href="{{ route('profile.edit') }}">
                            Hello, {{ Auth::user()->name }}
                        </a>
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

        <!-- Featured Latest Arrivals Section Title -->
        <div class="mb-5">
            <h2 class="fw-bold text-dark mb-1">Latest Arrivals</h2>
            <p class="text-muted small mb-4">Discover our newest inventory entries added fresh to the store catalog.</p>
            
            <div class="row row-cols-1 row-cols-md-3 row-cols-lg-6 g-3">
                @foreach($latestProducts as $latestItem)
                    <div class="col">
                        <div class="card h-100 border-0 shadow-sm bg-white rounded-3 position-relative overflow-hidden">
                            <!-- New Item Badge Overlay -->
                            <span class="position-absolute top-0 start-0 badge bg-danger text-uppercase fw-bold rounded-0" style="font-size: 10px; z-index: 2; padding: 4px 8px;">
                                New
                            </span>
                            
                            <!-- Product Image Render link -->
                            <a href="{{ route('products.show', ['id' => $latestItem->id]) }}">
                                <img src="{{ $latestItem->image ? asset('storage/' . $latestItem->image) : 'https://placehold.co/600x400/png?text=Hello+World&font=roboto' }}" class="card-img-top" style="height: 130px; object-fit: cover;" alt="{{ $latestItem->name }}">
                            </a>
                            
                            <div class="card-body p-2 d-flex flex-column justify-content-between">
                                <h6 class="card-title fw-bold text-dark text-truncate mb-1" title="{{ $latestItem->name }}">
                                    {{ $latestItem->name }}
                                </h6>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold text-primary small">${{ number_format($latestItem->price, 2) }}</span>
                                    @if($latestItem->reviews_avg_rating)
                                        <small class="text-warning fw-bold" style="font-size: 11px;">
                                            {{ number_format($latestItem->reviews_avg_rating, 1) }} &#9733;
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Search and Filter Navigation Bar Row -->
        <div class="card border-0 shadow-sm p-3 mb-5 bg-white rounded">
            <form action="{{ route('products.index') }}" method="GET" class="row g-3 align-items-center">
                
                <!-- Keyword Input Field Container -->
                <div class="col-md-5">
                    <input type="text" name="keyword" class="form-control" value="{{ request('keyword') }}" placeholder="Search products by name...">
                </div>

                <!-- Category Dropdown Filter Container -->
                <div class="col-md-4">
                    <select name="category_id" class="form-select">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Search Submit Action Button Container -->
                <div class="col-md-3 d-grid gap-2 d-md-flex">
                    <button type="submit" class="btn btn-dark w-100 fw-bold">Search</button>
                    <!-- Reset Button link to clear filters -->
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100 fw-semibold">Clear</a>
                </div>

            </form>
        </div>

        <h1 class="text-center fw-bold mb-4">Our Products</h1>
        <p class="text-muted text-center mb-5">Explore our wide range of high-quality items filtered specially for you.
        </p>

        <!-- Product Grid System -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">

            @foreach($products as $product)
            <div class="col">
                <!-- Individual Product Card -->
                <div class="card h-100 border-0 shadow-sm product-card">
                    <!-- Check if product has an uploaded image file path -->
                    <a href="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/600x400/png?text=Hello+World&font=roboto' }}" target="_blank">
                        <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/600x400/png?text=Hello+World&font=roboto' }}" class="card-img-top" style="height: 200px; object-fit: cover; cursor: pointer;" alt="{{ $product->name }}">
                    </a>

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

                        <!-- Display dynamic average star rating badge -->
                        <div class="mb-3">
                            @if($product->reviews_avg_rating)
                                <span class="text-warning fw-bold" style="font-size: 14px;">
                                    {{ number_format($product->reviews_avg_rating, 1) }} &#9733;
                                </span>
                                <small class="text-muted font-monospace">({{ $product->reviews_count ?? $product->reviews->count() }} reviews)</small>
                            @else
                                <small class="text-muted italic" style="font-size: 12px;">No ratings yet</small>
                            @endif
                        </div>

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
        <div class="d-flex justify-content-center mt-5">
            {{ $products->links() }}
        </div>
    </div>

    <!-- Include Bootstrap 5 JS Bundle via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>

</html>