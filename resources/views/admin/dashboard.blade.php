<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Product Management</title>
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
                        <a class="nav-link active" href="{{ route('admin.dashboard') }}">Dashboard</a>
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

    <!-- Main Dashboard Content -->
    <div class="container my-5">

        <!-- Display Global Success Alerts -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert">
            <span class="fw-semibold">{{ session('success') }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="fw-bold text-dark m-0">Product Management</h1>
                <p class="text-muted m-0">Overview and control of your entire inventory items.</p>
            </div>
            <!-- Call to Action: Add New Product -->
            <a href="{{ route('admin.products.create') }}" class="btn btn-dark fw-bold px-4 py-2 shadow-sm">
                + Add New Product
            </a>
        </div>

        <!-- Products Table Card Container -->
        <div class="card border-0 shadow-sm p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" style="width: 80px;">ID</th>
                            <th scope="col">Product Name</th>
                            <th scope="col">Category</th>
                            <th scope="col">Price</th>
                            <th scope="col">Stock</th>
                            <th scope="col">Status</th>
                            <th scope="col" style="width: 180px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td class="fw-bold text-muted">#{{ $product->id }}</td>
                            <td>
                                <span class="fw-semibold text-dark">{{ $product->name }}</span>
                            </td>
                            <td>
                                <!-- Display category name efficiently via Eager Loading -->
                                <span class="badge bg-secondary">{{ $product->category->name }}</span>
                            </td>
                            <td class="fw-bold text-primary">${{ number_format($product->price, 2) }}</td>
                            <td>
                                @if($product->quantity > 0)
                                <span class="fw-medium text-dark">{{ $product->quantity }} pcs</span>
                                @else
                                <span class="badge bg-danger">Out of Stock</span>
                                @endif
                            </td>
                            <td>
                                @if($product->is_active)
                                <span class="badge bg-success-subtle text-success px-2 py-1">Active</span>
                                @else
                                <span class="badge bg-secondary-subtle text-secondary px-2 py-1">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <!-- Edit Button Link -->
                                <a href="{{ route('admin.products.edit', ['id' => $product->id]) }}" class="btn btn-sm btn-outline-dark fw-semibold me-1">
                                    Edit
                                </a>

                                <!-- Delete Action Form (Using POST method with an inline confirmation alert) -->
                                <form action="{{ route('admin.products.destroy', ['id' => $product->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-danger fw-semibold">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                No products found in database. Click "+ Add New Product" to populate items.
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