<div class="container mt-4">
    <h1>Edit Product Info</h1>
    <form action="/manage" method="POST" enctype="multipart/form-data">
        <!-- Product Information -->
        <div class="mb-3">
            <div class="row">
                <div class="col-md-6">
                    <label for="productName" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="productName" name="productName"
                        placeholder="Enter product name" value="" required>
                </div>
                <div class="col-md-6">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3"
                        placeholder="Enter product description" value=""></textarea>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="price" class="form-label">Price</label>
                    <div class="input-group">
                        <span class="input-group-text">₦</span>
                        <input type="number" class="form-control" id="price" name="price" placeholder="Enter price"
                            step="0.01" value="" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter quantity"
                        min="0" value="" required>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="manufacturer" class="form-label">Manufacturer</label>
                    <input type="text" class="form-control" id="manufacturer" name="manufacturer"
                        placeholder="Enter manufacturer" value="">
                </div>
                <div class="col-md-6">
                    <label for="category" class="form-label">Category</label>
                    <input type="text" class="form-control" id="category" name="category" placeholder="Enter category" value="">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="productImage" class="form-label">Product Image</label>
                    <input type="file" class="form-control" id="productImage" name="productImage" accept="image/*" value="">
                </div>
                <div class="col-md-6">
                    <input type="hidden" class="form-control" id="uploaderName" name="uploaderName" placeholder=""
                        value="' . $_SESSION['user_session']['id'] . '" required>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary" name="addProduct">Upload Product</button>
    </form>

</div>
</div>