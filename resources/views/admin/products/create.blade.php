<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add New Product</title>
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
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard</a>
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

    <!-- Main Content Form Container -->
    <div class="container my-5">
        <!-- Back Link -->
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary mb-4">
            &larr; Back to Dashboard
        </a>

        <div class="row justify-content-center">
            <div class="col-md-8">

                <!-- Form Card Wrapper -->
                <div class="card border-0 shadow-sm p-4">
                    <div class="card-body">
                        <h2 class="fw-bold text-dark mb-2">Create New Product</h2>
                        <p class="text-muted mb-4">Input all technical parameters to display the item on client store
                            front.</p>

                        <!-- Product Form Element sending POST request to store route -->
                        <form action="{{ route('admin.products.store') }}" method="POST">
                            @csrf

                            <!-- Product Name Input -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Product Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                    name="name" value="{{ old('name') }}" placeholder="e.g., iPhone 15 Pro Max">
                                @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category Selection Dropdown Menu -->
                            <div class="mb-3">
                                <label for="category_id" class="form-label fw-semibold">Product Category</label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                                    name="category_id">
                                    <option value="" selected disabled>-- Select a category --</option>
                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <!-- Product Price Input -->
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label fw-semibold">Price ($)</label>
                                    <input type="number" step="0.01"
                                        class="form-control @error('price') is-invalid @enderror" id="price"
                                        name="price" value="{{ old('price') }}" placeholder="e.g., 999.99">
                                    @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Stock Quantity Input -->
                                <div class="col-md-6 mb-3">
                                    <label for="quantity" class="form-label fw-semibold">Stock Quantity</label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                        id="quantity" name="quantity" value="{{ old('quantity') }}"
                                        placeholder="e.g., 50">
                                    @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Product Description Textarea -->
                            <div class="mb-4">
                                <label for="description" class="form-label fw-semibold">Product Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                    id="description" name="description" rows="5"
                                    placeholder="Write detailed product parameters and specifications here...">{{ old('description') }}</textarea>
                                @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Action Button Submit -->
                            <button type="submit" class="btn btn-dark w-100 py-3 fw-bold">Publish Product</button>
                        </form>

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