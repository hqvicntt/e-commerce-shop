<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Shopping Cart</title>
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
                        <a class="nav-link" href="{{ route('products.index') }}">Home</a>
                    </li>
                    <li class="nav-item me-3">
                        <!-- Count total unique items in the cart array -->
                        <a class="nav-link active" href="{{ route('cart.index') }}">Cart ({{ count($cart) }})</a>
                    </li>
                    @auth
                    <li class="nav-item text-white me-3">
                        Welcome, <span class="fw-bold text-warning">{{ Auth::user()->name }}</span>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-light fw-semibold">Log Out</button>
                        </form>
                    </li>
                    @else
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
        <h1 class="fw-bold mb-4">Shopping Cart</h1>

        <!-- Check if the cart is empty -->
        @if(count($cart) > 0)
        <div class="row g-4">
            <!-- Left Column: Table of Items -->
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm p-4">
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Product</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Initialize grand total variable -->
                                @php $total = 0; @endphp

                                <!-- Loop through each item in the cart session -->
                                @foreach($cart as $id => $item)
                                <!-- Calculate subtotal for each row -->
                                @php
                                $subtotal = $item['price'] * $item['quantity'];
                                $total += $subtotal;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $item['image'] ? asset('storage/' . $item['image']) : 'https://placehold.co/600x400/png?text=Hello+World&font=roboto' }}" class="img-fluid rounded me-3" style="width: 60px; height: 40px; object-fit: cover;" alt="{{ $item['name'] }}">
                                            <span class="fw-semibold text-dark">{{ $item['name'] }}</span>
                                        </div>
                                    </td>
                                    <td>${{ number_format($item['price'], 2) }}</td>
                                    <td>
                                        <span class="badge bg-dark px-3 py-2 fs-6">{{ $item['quantity'] }}</span>
                                    </td>
                                    <td class="fw-bold text-dark">${{ number_format($subtotal, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Right Column: Order Summary & Checkout -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm p-4">
                    <h4 class="fw-bold mb-4">Order Summary</h4>
                    <div class="d-flex justify-content-between mb-3">
                        <span class="text-muted">Subtotal</span>
                        <span class="fw-semibold">${{ number_format($total, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4 border-bottom pb-3">
                        <span class="text-muted">Shipping</span>
                        <span class="text-success fw-bold">Free</span>
                    </div>
                    <div class="d-flex justify-content-between mb-4">
                        <span class="fs-5 fw-bold">Total</span>
                        <span class="fs-5 fw-bold text-primary">${{ number_format($total, 2) }}</span>
                    </div>
                    <form action="{{ route('cart.checkout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-dark w-100 py-3 fw-bold mb-2">
                            Proceed to Checkout
                        </button>
                    </form>
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary w-100 py-2">Continue
                        Shopping</a>
                </div>
            </div>
        </div>
        @else
        <!-- Displayed when cart has 0 items -->
        <div class="card border-0 shadow-sm p-5 text-center">
            <div class="py-5">
                <h3 class="text-muted mb-4">Your cart is currently empty!</h3>
                <a href="{{ route('products.index') }}" class="btn btn-dark btn-lg px-5">Go Shop Now</a>
            </div>
        </div>
        @endif
    </div>

    <!-- Include Bootstrap 5 JS Bundle via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>

</html>