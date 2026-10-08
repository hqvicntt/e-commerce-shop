<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit Product</title>
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
                        <h2 class="fw-bold text-dark mb-2">Edit Product: {{ $product->name }}</h2>
                        <p class="text-muted mb-4">Modify the parameters below to update this inventory item details.</p>

                        <!-- Product Edit Form Element sending POST request to update route -->
                        <form action="{{ route('admin.products.update', ['id' => $product->id]) }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Product Name Input -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">Product Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $product->name) }}" placeholder="e.g., iPhone 15 Pro Max">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Category Selection Dropdown Menu -->
                            <div class="mb-3">
                                <label for="category_id" class="form-label fw-semibold">Product Category</label>
                                <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                    <option value="" disabled>-- Select a category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" value="{{ old('price', $product->price) }}" placeholder="e.g., 999.99">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Stock Quantity Input -->
                                <div class="col-md-6 mb-3">
                                    <label for="quantity" class="form-label fw-semibold">Stock Quantity</label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" value="{{ old('quantity', $product->quantity) }}" placeholder="e.g., 50">
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Product Description Textarea -->
                            <div class="mb-4">
                                <label for="description" class="form-label fw-semibold">Product Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" placeholder="Write detailed product parameters and specifications here...">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Product Image Input Group with Preview and Delete Action -->
                            <div class="mb-4">
                                <label for="image" class="form-label fw-semibold">Product Image</label>
                                
                                <!-- Image Preview Container if image exists in database -->
                                @if($product->image)
                                    <div class="mb-3">
                                        <p class="text-muted small mb-2">Current image preview (Click on image to open in new tab):</p>
                                        <!-- Design a relative wrapper box for absolute X button styling -->
                                        <div class="position-relative d-inline-block">
                                            <!-- Wrap image in <a> tag with target="_blank" to open in a new tab -->
                                            <a href="{{ asset('storage/' . $product->image) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $product->image) }}" class="img-thumbnail rounded shadow-sm" style="width: 150px; height: 110px; object-fit: cover; cursor: zoom-in;" alt="{{ $product->name }}">
                                            </a>
                                            
                                            <!-- Absolute badge style for delete form anchor button -->
                                            <button type="button" onclick="if(confirm('Are you sure you want to delete this file?')) { document.getElementById('deleteImageForm').submit(); }" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 start-100 translate-middle p-0" style="width: 22px; height: 22px; font-size: 11px; font-weight: bold; line-height: 20px; text-align: center;" title="Remove image">
                                                X
                                            </button>
                                        </div>
                                    </div>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" placeholder="Upload a new image to replace current one">
                                @else
                                    <div class="mb-3">
                                        <img src="https://placehold.co/600x400/png?text=Hello+World&font=roboto" class="img-thumbnail rounded" style="width: 150px; height: 110px; object-fit: cover;" alt="No Image Available">
                                    </div>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image">
                                @endif
                                
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Action Button Submit -->
                            <button type="submit" class="btn btn-dark w-100 py-3 fw-bold">Update Product Details</button>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <form id="deleteImageForm" action="{{ route('admin.products.delete_image', ['id' => $product->id]) }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Include Bootstrap 5 JS Bundle via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>
</html>
