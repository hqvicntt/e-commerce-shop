<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Order Details #{{ $order->id }}</title>
    <!-- Include Bootstrap 5 CSS via CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
</head>
<body class="bg-light">

    <!-- Admin Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-danger" href="{{ route('admin.dashboard') }}">E-SHOP ADMIN</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.orders.index') }}">Orders</a>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-light fw-semibold">Log Out</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Invoice Container -->
    <div class="container my-5">
        <!-- Back Button -->
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary mb-4">
            &larr; Back to Orders List
        </a>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                
                <!-- Invoice Sheet Card Wrapper -->
                <div class="card border-0 shadow-sm p-5 bg-white rounded">
                    
                    <!-- Invoice Header Section -->
                    <div class="row border-bottom pb-4 mb-4 align-items-center">
                        <div class="col-sm-6">
                            <h2 class="fw-bold text-dark m-0">ORDER INVOICE</h2>
                            <p class="text-muted m-0">Official customer transaction breakdown record.</p>
                        </div>
                        <div class="col-sm-6 text-sm-end mt-3 mt-sm-0">
                            <h4 class="fw-bold text-muted m-0">Order ID: #{{ $order->id }}</h4>
                            <span class="small text-secondary">Placed on: {{ $order->created_at->format('M d, Y - H:i') }}</span>
                        </div>
                    </div>

                    <!-- Customer Profile & Metadata Row -->
                    <div class="row mb-5">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <h5 class="fw-bold text-dark mb-3 text-uppercase" style="letter-spacing: 0.5px;">Customer Profile</h5>
                            <div class="p-3 bg-light rounded border-start border-3 border-dark">
                                <p class="mb-1"><strong>Name:</strong> {{ $order->user->name }}</p>
                                <p class="mb-1"><strong>Email:</strong> {{ $order->user->email }}</p>
                                <p class="mb-0"><strong>Account Role:</strong> <span class="badge bg-secondary text-capitalize">{{ $order->user->role }}</span></p>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <h5 class="fw-bold text-dark mb-3 text-uppercase" style="letter-spacing: 0.5px;">Order Status</h5>
                            <div class="mb-3">
                                @if($order->status === 'pending')
                                    <span class="badge bg-warning text-dark px-4 py-2 fs-6 fw-bold text-uppercase shadow-sm">Pending</span>
                                @elseif($order->status === 'completed')
                                    <span class="badge bg-success px-4 py-2 fs-6 fw-bold text-uppercase shadow-sm">Completed</span>
                                @else
                                    <span class="badge bg-danger px-4 py-2 fs-6 fw-bold text-uppercase shadow-sm">Cancelled</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Table container displaying exploded items breakdown lists -->
                    <h5 class="fw-bold text-dark mb-3 text-uppercase" style="letter-spacing: 0.5px;">Purchased Items Summary</h5>
                    <div class="table-responsive mb-4">
                        <table class="table table-bordered align-middle">
                            <thead class="table-dark text-uppercase" style="font-size: 13px;">
                                <tr>
                                    <th scope="col" style="width: 80px;" class="text-center">Line</th>
                                    <th scope="col">Product Specification Details & Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Loop through the array items exploded from string in controller -->
                                @foreach($productsArray as $index => $productItem)
                                    <tr>
                                        <td class="text-center fw-bold text-muted">{{ $index + 1 }}</td>
                                        <td class="fw-semibold text-secondary py-3 px-3">
                                            {{ $productItem }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Financial Invoice Total Summary -->
                    <div class="row justify-content-end mt-4">
                        <div class="col-md-5 col-sm-7">
                            <div class="p-3 bg-light rounded">
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Total Paid Amount:</span>
                                    <span class="fs-4 fw-bold text-primary">${{ number_format($order->total_price, 2) }}</span>
                                </div>
                                <div class="text-end">
                                    <small class="text-success fw-bold">&check; Paid via Session Wallet Gateway</small>
                                </div>
                            </div>
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
