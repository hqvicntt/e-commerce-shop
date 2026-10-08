<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Orders Management</title>
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
                    <li class="nav-item me-3">
                        <a class="nav-link" href="{{ route('products.index') }}" target="_blank">View Store &rarr;</a>
                    </li>
                    <li class="nav-item text-white me-3">
                        Welcome, <span class="fw-bold text-warning">{{ Auth::user()->name }}</span>
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

    <!-- Main Content Container -->
    <div class="container my-5">

        <!-- Display Global Success/Error Alerts for Admin Orders -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
                <span class="fw-semibold">{{ session('success') }}</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold text-dark m-0">Orders Management</h1>
                <p class="text-muted m-0">Track and monitor all client purchase transactions across your store.</p>
            </div>
            <!-- Shortcut back to Product Dashboard -->
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-dark fw-bold px-4 py-2 shadow-sm">
                &larr; Back to Products
            </a>
        </div>

        <!-- Orders Table Card Wrapper -->
        <div class="card border-0 shadow-sm p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 100px;">Order ID</th>
                            <th scope="col">Customer</th>
                            <th scope="col">Purchased Products</th>
                            <th scope="col">Total Price</th>
                            <th scope="col">Date & Time</th>
                            <th scope="col" style="width: 130px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Loop through each order entry using forelse for defensive coding -->
                        @forelse($orders as $order)
                            <tr>
                                <td class="fw-bold text-muted">#{{ $order->id }}</td>
                                <td>
                                    <!-- Safely fetch buyer name via Eager Loading relationship -->
                                    <span class="fw-semibold text-dark">{{ $order->user->name }}</span>
                                    <br>
                                    <small class="text-muted">{{ $order->user->email }}</small>
                                </td>
                                <td>
                                    <!-- Display compiled concatenated product items text string -->
                                    <span class="text-secondary fw-medium">{{ $order->product_names }}</span>
                                </td>
                                <td class="fw-bold text-primary">${{ number_format($order->total_price, 2) }}</td>
                                <td>
                                    <small class="text-muted fw-semibold">
                                        {{ $order->created_at->format('M d, Y - H:i') }}
                                    </small>
                                </td>
                                <td>
                                    <!-- Display current status badge -->
                                    <div class="mb-2">
                                        @if($order->status === 'pending')
                                            <span class="badge bg-warning text-dark px-3 py-2 fw-bold text-uppercase">Pending</span>
                                        @elseif($order->status === 'completed')
                                            <span class="badge bg-success px-3 py-2 fw-bold text-uppercase">Completed</span>
                                        @else
                                            <span class="badge bg-danger px-3 py-2 fw-bold text-uppercase">Cancelled</span>
                                        @endif
                                    </div>

                                    <!-- Quick Action Buttons: Only display if the order status is currently pending -->
                                    @if($order->status === 'pending')
                                        <div class="d-flex gap-1">
                                            <!-- Complete Action Form -->
                                            <form action="{{ route('admin.orders.update_status', ['id' => $order->id]) }}" method="POST" onsubmit="return confirm('Mark this order as COMPLETED?');">
                                                @csrf
                                                <input type="hidden" name="status" value="completed">
                                                <button type="submit" class="btn btn-xs btn-success fw-bold text-white" style="font-size: 11px; padding: 2px 6px;">
                                                    Done
                                                </button>
                                            </form>

                                            <!-- Cancel Action Form -->
                                            <form action="{{ route('admin.orders.update_status', ['id' => $order->id]) }}" method="POST" onsubmit="return confirm('Are you sure you want to CANCEL this order?');">
                                                @csrf
                                                <input type="hidden" name="status" value="cancelled">
                                                <button type="submit" class="btn btn-xs btn-outline-danger fw-bold" style="font-size: 11px; padding: 2px 6px;">
                                                    Cancel
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <!-- Triggered if orders collection database yield is zero records -->
                            <tr>
                                <td colspan="6" class="text-center text-muted py-5">
                                    No customer orders found in the database. 
                                    <br>
                                    <small class="text-sm">Go to client interface to perform checkout test simulations.</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Include Bootstrap 5 JS Bundle via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>
</html>
