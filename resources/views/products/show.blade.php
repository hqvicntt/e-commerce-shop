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

                    <!-- Display main average star rating badge below product title -->
                    <div class="mb-3">
                        @if($product->reviews_avg_rating)
                            <div class="d-flex align-items-center">
                                <span class="badge bg-dark fs-6 fw-bold px-3 py-1.5 me-2">
                                    {{ number_format($product->reviews_avg_rating, 1) }} &#9733;
                                </span>
                                <span class="text-muted small fw-semibold">Based on {{ $product->reviews->count() }} customer feedback entries</span>
                            </div>
                        @else
                            <span class="badge bg-light text-secondary border px-3 py-1.5 small">Not Rated Yet</span>
                        @endif
                    </div>

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
                    <p class="text-muted mb-3 justify-content-center" style="text-align: justify;">
                        {{ $product->description }}
                    </p>

                    <!-- Product Stock Inventory Status Indicator -->
                    <div class="mb-4">
                        @if($product->quantity > 0)
                            <h4 class="text-success fw-bold m-0">In Stock: {{ $product->quantity }} units available</h4>
                        @else
                            <h4 class="text-danger fw-bold m-0 text-uppercase">&cross; Out of Stock</h4>
                        @endif
                    </div>

                    <!-- Action Area Buttons -->
                    <div class="d-grid gap-2 d-md-flex">

                        <a href="{{ route('cart.add', ['id' => $product->id]) }}"
                            class="btn btn-dark px-5 py-3 fw-bold shadow-sm @if($product->quantity <= 0) disabled @endif">
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

    <hr class="my-5">

        <!-- Product Reviews and Ratings Section Grid Layout -->
        <div class="row mt-5">
            
            <!-- Left Column: Display list of existing customer reviews -->
            <div class="col-md-7 mb-4 mb-md-0">
                <h3 class="fw-bold text-dark text-center mb-4">Customer Reviews ({{ $product->reviews->count() }})</h3>
                
                <div style="max-height: 500px; overflow-y: auto;" class="pe-2">
                    @forelse($product->reviews as $review)
                        <div class="card border-0 shadow-sm p-3 mb-3 bg-white rounded">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div>
                                    <strong class="text-dark">{{ $review->user->name }}</strong>
                                    <span class="text-muted small mx-2">|</span>
                                    <small class="text-muted">{{ $review->created_at->format('M d, Y') }}</small>
                                </div>
                                <!-- Display star rating value text -->
                                <span class="badge bg-dark fw-bold">{{ $review->rating }} &#9733;</span>
                            </div>
                            <p class="text-secondary m-0 small fw-medium" style="white-space: pre-line;">{{ $review->comment }}</p>
                        </div>
                    @empty
                        <div class="text-muted py-4 text-center border rounded bg-white">
                            No reviews found for this product yet. Be the first to share your thoughts!
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Interactive Review Form for logged-in users -->
            <div class="col-md-5">
                <div class="card border-0 shadow-sm p-4 bg-white rounded position-sticky" style="top: 20px;">
                    <h4 class="fw-bold text-dark mb-3">Write a Review</h4>
                    
                    @auth
                        <form action="{{ route('products.review.store', ['id' => $product->id]) }}" method="POST">
                            @csrf

                            <!-- Rating Score Star Select Menu Box -->
                            <div class="mb-3">
                                <label for="rating" class="form-label fw-semibold">Rating Score</label>
                                <select name="rating" id="rating" class="form-select @error('rating') is-invalid @enderror">
                                    <option value="" selected disabled>-- Select Score Stars --</option>
                                    <option value="5" {{ old('rating') == '5' ? 'selected' : '' }}>5 Stars - Excellent</option>
                                    <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>4 Stars - Very Good</option>
                                    <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>3 Stars - Average</option>
                                    <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>2 Stars - Poor</option>
                                    <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>1 Star - Terrible</option>
                                </select>
                                @error('rating')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Comment Input Field Box -->
                            <div class="mb-4">
                                <label for="comment" class="form-label fw-semibold">Your Review Feedback</label>
                                <textarea name="comment" id="comment" rows="4" class="form-control @error('comment') is-invalid @enderror" placeholder="Write your detailed specifications feedback here (minimum 5 characters)...">{{ old('comment') }}</textarea>
                                @error('comment')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-dark w-100 py-2.5 fw-bold">Submit Review</button>
                        </form>
                    @else
                        <!-- Call to Action placeholder if customer is a guest visitor -->
                        <div class="text-center py-4">
                            <p class="text-muted small">You must be authenticated to publish product reviews details.</p>
                            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-dark fw-bold px-4">Sign In Now</a>
                        </div>
                    @endauth
                </div>
            </div>

        </div>

    <!-- Include Bootstrap 5 JS Bundle via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>

</html>