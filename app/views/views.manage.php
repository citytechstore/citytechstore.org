<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once('includes/cdn_header.php'); ?>
    <?php
    $page_title = '';
    $pageTitle = 'Manage';
    if (isset($_GET['type']) && (strtolower($_GET['type']) == 'products' || strtolower($_GET['type']) == 'product')) {
        $page_title = 'CTS - Products management';
        $pageTitle = 'Products';
    } else if (isset($_GET['type']) && (strtolower($_GET['type']) == 'sale' || strtolower($_GET['type']) == 'sales')) {
        $page_title = 'CTS - Sales management';
        $pageTitle = 'Sales';
    } else if (isset($_GET['type']) && (strtolower($_GET['type']) == 'user' || strtolower($_GET['type']) == 'users')) {
        $page_title = 'CTS - Users management';
        $pageTitle = 'Staff';
    } else if (isset($_GET['type']) && strtolower($_GET['type']) == 'categories') {
        $page_title = 'CTS - Categories management';
        $pageTitle = 'Categories';
    } else if (isset($_GET['type']) && strtolower($_GET['type']) == 'category_banners') {
        $page_title = 'CTS - Category Banners management';
        $pageTitle = 'Banners';
    } else if (isset($_GET['type']) && strtolower($_GET['type']) == 'orders') {
        $page_title = 'CTS - Orders management';
        $pageTitle = 'Orders';
    }
    ?>
    <title><?php echo $page_title; ?></title>
    <style>
.table td, .table th {
    white-space: nowrap;        /* Prevent text wrapping */
    overflow: hidden;           /* Hide overflow */
    text-overflow: ellipsis;    /* Add ellipsis (...) for overflowed text */
    max-width: 200px;           /* Set a max-width for the cells */
}

.table-responsive {
    overflow-x: auto;           /* Allows horizontal scrolling if needed */
}
    </style>
</head>

<body>
    <div class="admin-shell">
        <?php require_once('includes/admin_sidebar.php'); ?>

        <div class="admin-main">
            <?php require_once('includes/admin_topbar.php'); ?>

            <main class="admin-content container-fluid">
        <?php
        if (isset($_GET['type']) && (strtolower($_GET['type']) == 'products' || strtolower($_GET['type']) == 'product') && !isset($_GET['action'])) {

            $categoryOptionsHtml = '<option value="">Select category</option>';
            foreach ($categories as $cat) {
                $categoryOptionsHtml .= '<option value="' . htmlspecialchars($cat['name']) . '">' . htmlspecialchars($cat['name']) . '</option>';
            }

            echo '
            <div class="container-fluid">
            <div class="row border-bottom pb-1">
            <div class="col-8"><h2 class="mb-4">Products Management</h2></div>
            <div class="col-4 fs-5">Total Products: ' . $total_products_count . ' | Total Price: ₦' . number_format($total_products_price, 2) . '</div>
            </div>
            </div>
            ';
            echo '
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Unit Price</th>
                        <th scope="col">Total Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Manufacturer</th>
                        <th scope="col">Category</th>
                        <th scope="col">Uploaded By</th>
                        <th scope="col">Product Image</th>
                        <th scope="col">Created At</th>
                        ' . ((isset($_SESSION['loggedin'])) ? '<th scope="col">Action</th>' : '') . '
                    </tr>
                </thead>
                <tbody>';
            foreach ($products as $product) {
                echo '<tr>
                    <td>' . $product['id'] . '</td>
                    <td>' . $product['name'] . '</td>
                    <td>' . $product['description'] . '</td>
                    <td>₦' . number_format($product['unit_price'], 2) . '</td>
                    <td>₦' . number_format($product['total_price'], 2) . '</td>
                    <td>' . number_format($product['quantity']) . '</td>
                    <td>' . $product['manufacturer'] . '</td>
                    <td>' . $product['category'] . '</td>
                    <td>' . ucfirst($UsersModel->getUserById($product['uploaded_by'])['firstname']) . ' ' . ucfirst($UsersModel->getUserById($product['uploaded_by'])['lastname']) . '</td>
                    <td><img src="' . $product['product_picture_url'] . '" alt="' . $product['name'] . '" width="50"></td>
                    <td>' . $product['created_at'] . '</td>
                    ' . ((isset($_SESSION['loggedin'])) ? ' <td class="text-center"><a href="manage?type=products&action=editproduct&pid=' . $product['id'] . '"><i class="fa fa-edit"></i></a></td>' : '') . '
                </tr>';
                // print_r();

            }
            echo '</tbody></table>';
            if (count($sales) == 0) {
                echo '<h4 class="text-muted text-center mt-5 mb-5">No products in stock for now</h4>';
            }
            echo '
     
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProduct">
              Add Product
            </button>
            
            <!-- Modal -->
            <div class="modal fade" id="addProduct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addProductLabel" aria-hidden="true">
              <div class="modal-dialog modal-xl">
              <div class="modal-content">
                  <div class="modal-header">

                  <h1 class="modal-title fs-5" id="addProductLabel">Add Product to Database</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">

                  <div class="container mt-4">
                  <h1>Add Product Info</h1>
                  <form action="manage" method="POST" enctype="multipart/form-data">
                      <input type="hidden" name="csrf_token" value="' . htmlspecialchars(generateCsrfToken()) . '">
                      <!-- Product Information -->
                      <div class="mb-3">
                          <div class="row">
                              <div class="col-md-6">
                                  <label for="productName" class="form-label">Product Name</label>
                                  <input type="text" class="form-control" id="productName" name="productName" placeholder="Enter product name" required>
                              </div>
                              <div class="col-md-6">
                                  <label for="description" class="form-label">Description</label>
                                  <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter product description"></textarea>
                              </div>
                          </div>
                          <div class="row mt-3">
                              <div class="col-md-6">
                                  <label for="price" class="form-label">Price</label>
                                  <div class="input-group">
                                      <span class="input-group-text">$</span>
                                      <input type="number" class="form-control" id="price" name="price" placeholder="Enter price" step="0.01" required>
                                  </div>
                              </div>
                              <div class="col-md-6">
                                  <label for="quantity" class="form-label">Quantity</label>
                                  <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter quantity" min="0" required>
                              </div>
                          </div>
                          <div class="row mt-3">
                              <div class="col-md-6">
                                  <label for="manufacturer" class="form-label">Manufacturer</label>
                                  <input type="text" class="form-control" id="manufacturer" name="manufacturer" placeholder="Enter manufacturer">
                              </div>
                              <div class="col-md-6">
                                  <label for="category" class="form-label">Category</label>
                                  <select class="form-control" id="category" name="category" required>' . $categoryOptionsHtml . '</select>
                              </div>
                          </div>
                          <div class="row mt-3">
                              <div class="col-md-6">
                                  <label for="productImage" class="form-label">Product Images</label>
                                  <input type="file" class="form-control" id="productImage" name="productImages[]" accept="image/*" multiple>
                              </div>
                              <div class="col-md-6">
                               <!--   <label for="uploaderName" class="form-label">Uploader Name</label> -->
                                  <input type="hidden" class="form-control" id="uploaderName" name="uploaderName" placeholder="" value="' . $_SESSION['user_session']['id'] . '"required>
                              </div>
                          </div>
                      </div>
                      <button type="submit" class="btn btn-primary" name="addProduct">Upload Product</button>
                  </form>

                </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
                  </div>
                  </div>
            </div>
            ';
        } elseif (isset($_GET['type']) && strtolower($_GET['type']) == 'categories' && !isset($_GET['action'])) {

            echo '
            <div class="container-fluid">
            <div class="row border-bottom pb-1">
            <div class="col-12"><h2 class="mb-4">Categories Management</h2></div>
            </div>
            </div>
            ';

            if (isset($_GET['status'])) {
                if ($_GET['status'] === 'success') {
                    echo '<div class="alert alert-success">Category added successfully.</div>';
                } elseif ($_GET['status'] === 'failed') {
                    $categoryErrorMessage = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : 'Something went wrong.';
                    echo '<div class="alert alert-danger">' . $categoryErrorMessage . '</div>';
                }
            }

            echo '
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Name</th>
                        <th scope="col">Created At</th>
                    </tr>
                </thead>
                <tbody>';
            foreach ($categories as $cat) {
                echo '<tr>
                    <td>' . $cat['id'] . '</td>
                    <td>' . htmlspecialchars($cat['name']) . '</td>
                    <td>' . $cat['created_at'] . '</td>
                </tr>';
            }
            echo '</tbody></table>';
            if (count($categories) == 0) {
                echo '<h4 class="text-muted text-center mt-5 mb-5">No categories yet</h4>';
            }

            echo '
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
              Add Category
            </button>

            <div class="modal fade" id="addCategoryModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
              <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                  <h1 class="modal-title fs-5" id="addCategoryModalLabel">Add Category</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                  <form action="manage" method="POST">
                      <input type="hidden" name="csrf_token" value="' . htmlspecialchars(generateCsrfToken()) . '">
                      <div class="mb-3">
                          <label for="categoryName" class="form-label">Category Name</label>
                          <input type="text" class="form-control" id="categoryName" name="categoryName" placeholder="Enter category name" required>
                      </div>
                      <button type="submit" class="btn btn-primary" name="addCategory">Add Category</button>
                  </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
              </div>
              </div>
            </div>
            ';
        } elseif (isset($_GET['type']) && strtolower($_GET['type']) == 'category_banners' && !isset($_GET['action'])) {

            echo '
            <div class="container-fluid">
            <div class="row border-bottom pb-1">
            <div class="col-12"><h2 class="mb-4">Category Banners Management</h2></div>
            </div>
            </div>
            ';

            if (isset($_GET['status'])) {
                if ($_GET['status'] === 'success') {
                    echo '<div class="alert alert-success">Banner saved successfully.</div>';
                } elseif ($_GET['status'] === 'failed') {
                    $bannerErrorMessage = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : 'Something went wrong.';
                    echo '<div class="alert alert-danger">' . $bannerErrorMessage . '</div>';
                }
            }

            $bannersByCategoryId = array_column($CategoryBannerModel->getAllBanners(), null, 'category_id');

            echo '
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Category</th>
                        <th scope="col">Status</th>
                        <th scope="col">Preview</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>';
            foreach ($categories as $cat) {
                $existingBanner = $bannersByCategoryId[$cat['id']] ?? null;
                echo '<tr>
                    <td>' . $cat['id'] . '</td>
                    <td>' . htmlspecialchars($cat['name']) . '</td>
                    <td>' . ($existingBanner ? '<span class="badge bg-success">Has Banner</span>' : '<span class="badge bg-secondary">No Banner</span>') . '</td>
                    <td>' . ($existingBanner ? '<img src="' . htmlspecialchars($existingBanner['image_path']) . '" alt="Banner preview" width="80">' : '&mdash;') . '</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#bannerModal' . $cat['id'] . '">
                            ' . ($existingBanner ? 'Edit Banner' : 'Add Banner') . '
                        </button>
                    </td>
                </tr>';
            }
            echo '</tbody></table>';
            if (count($categories) == 0) {
                echo '<h4 class="text-muted text-center mt-5 mb-5">No categories yet — add a category first.</h4>';
            }

            foreach ($categories as $cat) {
                $existingBanner = $bannersByCategoryId[$cat['id']] ?? null;

                echo '
                <div class="modal fade" id="bannerModal' . $cat['id'] . '" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="bannerModalLabel' . $cat['id'] . '" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                  <div class="modal-content">
                      <div class="modal-header">
                      <h1 class="modal-title fs-5" id="bannerModalLabel' . $cat['id'] . '">' . ($existingBanner ? 'Edit' : 'Add') . ' Banner &mdash; ' . htmlspecialchars($cat['name']) . '</h1>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                      <form action="manage" method="POST" enctype="multipart/form-data">
                          <input type="hidden" name="csrf_token" value="' . htmlspecialchars(generateCsrfToken()) . '">
                          <input type="hidden" name="categoryId" value="' . (int) $cat['id'] . '">
                          <div class="mb-3">
                              <label class="form-label">Banner Image' . ($existingBanner ? ' (leave blank to keep the current image)' : '') . '</label>
                              ' . ($existingBanner ? '<div class="mb-2"><img src="' . htmlspecialchars($existingBanner['image_path']) . '" alt="Current banner" width="220"></div>' : '') . '
                              <input type="file" class="form-control" name="bannerImages[]" accept="image/*"' . ($existingBanner ? '' : ' required') . '>
                          </div>
                          <div class="mb-3">
                              <label class="form-label">Headline</label>
                              <input type="text" class="form-control" name="headline" placeholder="Enter banner headline" value="' . ($existingBanner ? htmlspecialchars($existingBanner['headline']) : '') . '" required>
                          </div>
                          <div class="mb-3">
                              <label class="form-label">Subtext (optional)</label>
                              <input type="text" class="form-control" name="subtext" placeholder="Enter optional subtext" value="' . ($existingBanner ? htmlspecialchars($existingBanner['subtext']) : '') . '">
                          </div>
                          <div class="mb-3">
                              <label class="form-label">Link URL (optional, defaults to the category shop page)</label>
                              <input type="text" class="form-control" name="linkUrl" placeholder="e.g. shop?c=category&amp;p=' . htmlspecialchars($cat['name']) . '" value="' . ($existingBanner ? htmlspecialchars($existingBanner['link_url']) : '') . '">
                          </div>
                          <button type="submit" class="btn btn-primary" name="saveCategoryBanner">Save Banner</button>
                      </form>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      </div>
                  </div>
                  </div>
                </div>
                ';
            }
        } elseif (isset($_GET['type']) && (strtolower($_GET['type']) == 'sale' || strtolower($_GET['type']) == 'sales') && !isset($_GET['action'])) {
            echo ' <div class="container-fluid">
            <div class="row border-bottom pb-1">
            <div class="col-8"><h2 class="mb-4">Sales Management</h2></div>
            <div class="col-4 fs-5">Total Products: ' . $total_sales_count . ' | Total Price: ₦' . number_format($total_sales_price, 2) . '</div>
            </div>
            </div>';

            echo '
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Product ID</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Price per unit</th>
                        <th scope="col">Amount Paid</th>
                        <th scope="col">Total Price</th>
                        <th scope="col">Sale Date</th>
                        <th scope="col">Customer Name</th>
                        <th scope="col">Customer Email</th>
                        <th scope="col">Payment Method</th>
                        <th scope="col">Customer Picture</th>
                        <th scope="col">Worker Name</th>
                    </tr>
                </thead>
                <tbody>';

            foreach ($sales as $sale) {
                echo '<tr>
                    <td>' . $sale['product_id'] . '</td>
                    <td>' . number_format($sale['quantity']) . '</td>
                    <td>' . number_format($sale['price_per_unit'], 2) . '</td>
                    <td>' . number_format($sale['amount_paid'], 2) . '</td>
                    <td>' . number_format($sale['total_price'], 2) . '</td>
                    <td>' . $sale['sale_date'] . '</td>
                    <td>' . $sale['customer_name'] . '</td>
                    <td>' . $sale['customer_email'] . '</td>
                    <td>' . $sale['payment_method'] . '</td>
                    <td><img src="' . $sale['customer_picture_url'] . '" alt="' . $sale['customer_name'] . '" width="50" class="img rounded-pill"></td>
                    <td>' . ucfirst($UsersModel->getUserById($sale['worker_id'])['firstname']) . ' ' . ucfirst($UsersModel->getUserById($sale['worker_id'])['lastname']) . '</td>
                </tr>';
            }

            echo '</tbody></table>';
            if (count($sales) == 0) {
                echo '<h4 class="text-muted text-center mt-5 mb-5">No sales for now</h4>';
            }
            echo '<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
  Add Sales
</button>


<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Add Sales</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
      
      <div class="container">
      <h1 class="fs-4 mb-3">Record Sale</h1>
      <form method="POST" action="manage" enctype="multipart/form-data">
          <input type="hidden" name="csrf_token" value="' . htmlspecialchars(generateCsrfToken()) . '">
          <!-- Product Information -->
          <div class="mb-3">
              <h2 class="fs-5">Product Information</h2>
              <div class="row">
                  <div class="col-md-6">
                      <label for="productId" class="form-label">Product ID</label>
                      <input type="number" class="form-control" name="product_id" id="productId" placeholder="Enter product ID" required>
                  </div>
                  <div class="col-md-6">
                      <label for="quantitySold" class="form-label">Quantity Sold</label>
                      <input type="number" class="form-control" name="quantity_sold" id="quantitySold" placeholder="Enter quantity sold" min="1" required>
                  </div>
              </div>
             <!-- <div class="row mt-3">
                  <div class="col-md-6">
                      <label for="price" class="form-label">Price per Unit</label>
                      <div class="input-group">
                          <span class="input-group-text">$</span>
                          <input type="number" class="form-control" name="price" id="price" placeholder="Enter price per unit" step="0.01" required>
                      </div>
                  </div>
              </div> -->
          </div>
          <hr>
          <!-- Sales Information -->
          <div class="mb-3">
              <h2 class="fs-5">Sales Information</h2>
              <div class="row">
                  <div class="col-md-6">
                      <label for="customerName" class="form-label">Customer Name</label>
                      <input type="text" class="form-control" name="customer_name" id="customerName" placeholder="Enter customer name" required>
                  </div>
                  <div class="col-md-6">
                      <label for="customerContact" class="form-label">Customer Email/Phone</label>
                      <input type="text" class="form-control" name="customer_contact" id="customerContact" placeholder="Enter customer email or phone" required>
                  </div>
              </div>
              <div class="row mt-3">
                  <div class="col-md-6">
                      <label for="amountPaid" class="form-label">Amount Paid</label>
                      <div class="input-group">
                          <span class="input-group-text">$</span>
                          <input type="number" class="form-control" name="amount_paid" id="amountPaid" placeholder="Enter amount paid" step="0.01" required>
                      </div>
                  </div>
                  <div class="col-md-6">
                      <label for="paymentMethod" class="form-label">Payment Method</label>
                      <select class="form-select" name="payment_method" id="paymentMethod" required>
                          <option value="Cash">Cash</option>
                          <option value="Debit Card">Debit Card</option>
                          <!-- Add more payment methods as needed -->
                      </select>
                  </div>
              </div>
              <div class="row mt-3">
                  <div class="col-md-6">
                      <input type="hidden" class="form-control" id="worker_name" name="worker_name" placeholder="" value="' . $_SESSION['user_session']['id'] . '"required>
                  </div>
              </div>
              <div class="col-md-6">
              <label for="customerImage" class="form-label">Customer Upload</label>
              <input type="file" class="form-control" name="customerImage" id="customerImage" placeholder="Upload Customer Picture" accept="image/*">
          </div>
          </div>
          <button type="submit" class="btn btn-primary" name="addSales">Record Sale</button>
      </form>
  </div>
        </div>
    <!--  <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Understood</button>
      </div> -->
    </div>
  </div>
</div>';
        } else if (isset($_GET['type']) && (strtolower($_GET['type']) == 'user' || strtolower($_GET['type']) == 'users') && !isset($_GET['action'])) {

            echo '
            <div class="container-fluid">
            <div class="row border-bottom pb-1">
            <div class="col-12"><h2 class="mb-4">Staff Management</h2></div>
            </div>
            </div>
            ';

            if (isset($_GET['status'])) {
                if ($_GET['status'] === 'success') {
                    $staffSuccessMessage = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : 'Staff member added successfully.';
                    echo '<div class="alert alert-success">' . $staffSuccessMessage . '</div>';
                } elseif ($_GET['status'] === 'failed') {
                    $staffErrorMessage = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : 'Something went wrong.';
                    echo '<div class="alert alert-danger">' . $staffErrorMessage . '</div>';
                }
            }

            echo '
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Firstname</th>
                        <th scope="col">Lastname</th>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                        <th scope="col">Phone</th>
                        <th scope="col">Role</th>
                        <th scope="col">Status</th>
                        <th scope="col">Date Created</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>';

            $staffCsrfToken = htmlspecialchars(generateCsrfToken());

            foreach ($users as $user) {
                $isSelf = (int) $user['id'] === (int) $_SESSION['user_session']['id'];
                $isActive = (int) $user['is_active'] === 1;

                if ($isSelf) {
                    $actionCell = '<span class="text-muted">This is you</span>';
                } elseif ($isActive) {
                    $actionCell = '<form action="manage" method="POST" class="d-inline" onsubmit="return confirm(\'Deactivate this staff member? They will no longer be able to log in.\');">
                        <input type="hidden" name="csrf_token" value="' . $staffCsrfToken . '">
                        <input type="hidden" name="userId" value="' . (int) $user['id'] . '">
                        <button type="submit" name="deactivateStaff" class="btn btn-sm btn-outline-danger">Deactivate</button>
                    </form>';
                } else {
                    $actionCell = '<form action="manage" method="POST" class="d-inline">
                        <input type="hidden" name="csrf_token" value="' . $staffCsrfToken . '">
                        <input type="hidden" name="userId" value="' . (int) $user['id'] . '">
                        <button type="submit" name="activateStaff" class="btn btn-sm btn-outline-success">Reactivate</button>
                    </form>';
                }

                echo '<tr>
                    <td>' . (int) $user['id'] . '</td>
                    <td>' . htmlspecialchars(ucfirst($user['firstname'])) . '</td>
                    <td>' . htmlspecialchars(ucfirst($user['lastname'])) . '</td>
                    <td>' . htmlspecialchars($user['username']) . '</td>
                    <td>' . htmlspecialchars($user['email']) . '</td>
                    <td>' . htmlspecialchars($user['phone_number']) . '</td>
                    <td>' . htmlspecialchars(ucfirst($user['role'])) . '</td>
                    <td>' . ($isActive ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>') . '</td>
                    <td>' . htmlspecialchars($user['created_at']) . '</td>
                    <td class="text-center">' . $actionCell . '</td>
                </tr>';
            }
            echo '</tbody></table>';

            echo '
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStaffModal">
              Add Staff
            </button>

            <div class="modal fade" id="addStaffModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="addStaffModalLabel" aria-hidden="true">
              <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                  <h1 class="modal-title fs-5" id="addStaffModalLabel">Add Staff</h1>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                  <form action="manage" method="POST">
                      <input type="hidden" name="csrf_token" value="' . htmlspecialchars(generateCsrfToken()) . '">
                      <div class="mb-3">
                          <label for="staffUsername" class="form-label">Username</label>
                          <input type="text" class="form-control" id="staffUsername" name="username" required>
                      </div>
                      <div class="mb-3">
                          <label for="staffPassword" class="form-label">Password</label>
                          <input type="password" class="form-control" id="staffPassword" name="password" required>
                      </div>
                      <div class="mb-3">
                          <label for="staffEmail" class="form-label">Email</label>
                          <input type="email" class="form-control" id="staffEmail" name="email" required>
                      </div>
                      <div class="mb-3">
                          <label for="staffFirstname" class="form-label">First Name</label>
                          <input type="text" class="form-control" id="staffFirstname" name="firstname" required>
                      </div>
                      <div class="mb-3">
                          <label for="staffLastname" class="form-label">Last Name</label>
                          <input type="text" class="form-control" id="staffLastname" name="lastname" required>
                      </div>
                      <div class="mb-3">
                          <label for="staffPhone" class="form-label">Phone Number</label>
                          <input type="tel" class="form-control" id="staffPhone" name="phone_number" required>
                      </div>
                      <div class="mb-3">
                          <label for="staffRole" class="form-label">Role</label>
                          <select class="form-select" id="staffRole" name="role" required>
                              <option value="admin">Admin</option>
                              <option value="worker">Worker</option>
                          </select>
                      </div>
                      <button type="submit" class="btn btn-primary" name="addStaff">Add Staff</button>
                  </form>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                  </div>
              </div>
              </div>
            </div>
            ';
        } else if (isset($_GET['type']) && strtolower($_GET['type']) == 'orders' && !isset($_GET['action'])) {
            $isAdminUser = ($_SESSION['user_session']['role'] === 'admin');
            $statusBadgeClass = [
                'pending' => 'bg-secondary',
                'confirmed' => 'bg-info text-dark',
                'shipped' => 'bg-primary',
                'delivered' => 'bg-success',
                'cancelled' => 'bg-danger',
            ];
            $paymentBadgeClass = [
                'pending' => 'bg-secondary',
                'paid' => 'bg-success',
                'failed' => 'bg-danger',
            ];
            $orderStatusOptions = ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'];

            // Real, all-time stats from the already-loaded $orders array —
            // no extra queries. Average guards divide-by-zero explicitly.
            $ordersTotalCount = count($orders);
            $ordersPendingCount = 0;
            $ordersPaidCount = 0;
            $ordersPaidRevenue = 0.0;
            foreach ($orders as $orderRow) {
                if ($orderRow['status'] === 'pending') {
                    $ordersPendingCount++;
                }
                if ($orderRow['payment_status'] === 'paid') {
                    $ordersPaidCount++;
                    $ordersPaidRevenue += (float) $orderRow['total'];
                }
            }
            $ordersAvgValue = $ordersPaidCount > 0 ? ($ordersPaidRevenue / $ordersPaidCount) : 0.0;

            // Bulk-fetched (one query each) instead of a per-row
            // getOrderWithDetails() + getOrderItems() call.
            $ordersWithDetails = $OrderModel->getAllOrdersWithDetails();
            $orderIds = array_column($ordersWithDetails, 'id');
            $orderItemsByOrderId = $OrderModel->getOrderItemsForOrderIds($orderIds);
            ?>

            <?php if (isset($_GET['status'])): ?>
                <?php if ($_GET['status'] === 'success'): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($_GET['message'] ?? 'Order updated successfully.'); ?></div>
                <?php elseif ($_GET['status'] === 'failed'): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($_GET['message'] ?? 'Something went wrong.'); ?></div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="row g-3 mb-4">
                <div class="col-md-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-card-icon stat-card-icon-navy"><i class="fas fa-receipt"></i></div>
                        <div>
                            <div class="stat-card-value"><?php echo number_format($ordersTotalCount); ?></div>
                            <div class="stat-card-label">Total Orders</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-card-icon"><i class="fas fa-triangle-exclamation"></i></div>
                        <div>
                            <div class="stat-card-value"><?php echo number_format($ordersPendingCount); ?></div>
                            <div class="stat-card-label">Pending Orders</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-card-icon stat-card-icon-navy"><i class="fas fa-naira-sign"></i></div>
                        <div>
                            <div class="stat-card-value">&#8358;<?php echo number_format($ordersPaidRevenue, 2); ?></div>
                            <div class="stat-card-label">Paid Revenue</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-card-icon"><i class="fas fa-chart-simple"></i></div>
                        <div>
                            <div class="stat-card-value">&#8358;<?php echo number_format($ordersAvgValue, 2); ?></div>
                            <div class="stat-card-label">Avg Order Value (paid orders)</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard-panel mb-3">
                <div class="d-flex flex-wrap gap-2 align-items-center">
                    <input type="search" id="orderSearchInput" class="form-control" style="max-width: 320px;" placeholder="Search order #, customer name or email&hellip;">
                    <select id="orderStatusFilter" class="form-select" style="max-width: 200px;">
                        <option value="">All Statuses</option>
                        <?php foreach ($orderStatusOptions as $statusOption): ?>
                            <option value="<?php echo htmlspecialchars($statusOption); ?>"><?php echo htmlspecialchars(ucfirst($statusOption)); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="dashboard-panel">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 admin-orders-table" id="ordersTable">
                        <thead>
                            <tr>
                                <th scope="col">Order #</th>
                                <th scope="col">Products</th>
                                <th scope="col">Date</th>
                                <th scope="col">Customer</th>
                                <th scope="col">Total</th>
                                <th scope="col">Payment</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ordersWithDetails as $order): ?>
                                <?php
                                    $statusClass = $statusBadgeClass[$order['status']] ?? 'bg-secondary';
                                    $paymentClass = $paymentBadgeClass[$order['payment_status']] ?? 'bg-secondary';
                                    $items = $orderItemsByOrderId[(int) $order['id']] ?? [];
                                    $itemCount = count($items);
                                    $thumbItems = array_slice($items, 0, 3);
                                ?>
                                <tr data-order-row data-status="<?php echo htmlspecialchars($order['status']); ?>">
                                    <td><?php echo htmlspecialchars($order['order_number']); ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="admin-order-thumb-stack">
                                                <?php foreach ($thumbItems as $item): ?>
                                                    <img src="<?php echo htmlspecialchars($item['product_picture_url'] ?? ''); ?>"
                                                         alt="" class="admin-order-thumb">
                                                <?php endforeach; ?>
                                                <?php if ($itemCount > 3): ?>
                                                    <span class="admin-order-thumb-more">+<?php echo (int) ($itemCount - 3); ?></span>
                                                <?php endif; ?>
                                            </div>
                                            <span class="text-muted"><?php echo (int) $itemCount; ?> item<?php echo $itemCount === 1 ? '' : 's'; ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars(date('M j, Y', strtotime($order['created_at']))); ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($order['customer_first_name'] . ' ' . $order['customer_last_name']); ?>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($order['customer_email']); ?></small>
                                    </td>
                                    <td>&#8358;<?php echo number_format($order['total'], 2); ?></td>
                                    <td><span class="badge <?php echo $paymentClass; ?>"><?php echo htmlspecialchars(ucfirst($order['payment_status'])); ?></span></td>
                                    <td><span class="badge <?php echo $statusClass; ?>"><?php echo htmlspecialchars(ucfirst($order['status'])); ?></span></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#orderModal<?php echo (int) $order['id']; ?>">
                                            View
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <p id="ordersEmptyState" class="text-muted text-center py-4 mb-0 d-none">No matching orders.</p>
                <?php if (empty($ordersWithDetails)): ?>
                    <p class="text-muted text-center py-4 mb-0">No orders yet.</p>
                <?php endif; ?>
            </div>

            <?php foreach ($ordersWithDetails as $order): ?>
                <?php
                    $items = $orderItemsByOrderId[(int) $order['id']] ?? [];
                    $statusOptionsHtml = '';
                    foreach ($orderStatusOptions as $statusOption) {
                        $selectedAttr = ($statusOption === $order['status']) ? ' selected' : '';
                        $statusOptionsHtml .= '<option value="' . htmlspecialchars($statusOption) . '"' . $selectedAttr . '>' . htmlspecialchars(ucfirst($statusOption)) . '</option>';
                    }
                ?>
                <div class="modal fade" id="orderModal<?php echo (int) $order['id']; ?>" tabindex="-1" aria-labelledby="orderModalLabel<?php echo (int) $order['id']; ?>" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="orderModalLabel<?php echo (int) $order['id']; ?>">Order <?php echo htmlspecialchars($order['order_number']); ?></h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <h6 class="text-muted text-uppercase small">Customer</h6>
                                        <p class="mb-1"><?php echo htmlspecialchars($order['customer_first_name'] . ' ' . $order['customer_last_name']); ?></p>
                                        <p class="mb-1"><?php echo htmlspecialchars($order['customer_email']); ?></p>
                                        <p class="mb-0"><?php echo htmlspecialchars($order['customer_phone_number'] ?? ''); ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="text-muted text-uppercase small">Delivery Address</h6>
                                        <p class="mb-1"><?php echo htmlspecialchars($order['address_label']); ?></p>
                                        <p class="mb-1"><?php echo htmlspecialchars($order['address_full_address']); ?></p>
                                        <p class="mb-1"><?php echo htmlspecialchars($order['address_city'] . ', ' . $order['address_state']); ?></p>
                                        <p class="mb-0"><?php echo htmlspecialchars($order['address_phone_number'] ?? ''); ?></p>
                                    </div>
                                </div>

                                <h6 class="text-muted text-uppercase small">Items</h6>
                                <table class="table table-sm">
                                    <thead>
                                        <tr><th>Product</th><th>Qty</th><th>Unit Price</th><th>Subtotal</th></tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($items as $item): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                                <td><?php echo (int) $item['quantity']; ?></td>
                                                <td>&#8358;<?php echo number_format($item['price_at_purchase'], 2); ?></td>
                                                <td>&#8358;<?php echo number_format($item['price_at_purchase'] * $item['quantity'], 2); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>

                                <p class="mb-1"><strong>Subtotal:</strong> &#8358;<?php echo number_format($order['subtotal'], 2); ?></p>
                                <p class="mb-1"><strong>Delivery Fee:</strong> &#8358;<?php echo number_format($order['delivery_fee'], 2); ?></p>
                                <p class="mb-3"><strong>Total:</strong> &#8358;<?php echo number_format($order['total'], 2); ?></p>

                                <h6 class="text-muted text-uppercase small">Status</h6>
                                <span class="badge <?php echo $statusBadgeClass[$order['status']] ?? 'bg-secondary'; ?>"><?php echo htmlspecialchars(ucfirst($order['status'])); ?></span>

                                <?php if ($isAdminUser): ?>
                                    <form action="manage" method="POST" class="d-flex gap-2 align-items-center mt-2">
                                        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars(generateCsrfToken()); ?>">
                                        <input type="hidden" name="orderId" value="<?php echo (int) $order['id']; ?>">
                                        <select class="form-select form-select-sm" name="status" style="width: auto;">
                                            <?php echo $statusOptionsHtml; ?>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-primary" name="updateOrderStatus">Update Status</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <script>
            (function () {
                var searchInput = document.getElementById('orderSearchInput');
                var statusFilter = document.getElementById('orderStatusFilter');
                var emptyState = document.getElementById('ordersEmptyState');
                var rows = document.querySelectorAll('#ordersTable tbody tr[data-order-row]');

                function applyFilters() {
                    var term = searchInput.value.trim().toLowerCase();
                    var status = statusFilter.value;
                    var visibleCount = 0;

                    rows.forEach(function (row) {
                        var matchesSearch = term === '' || row.textContent.toLowerCase().indexOf(term) !== -1;
                        var matchesStatus = status === '' || row.getAttribute('data-status') === status;
                        var visible = matchesSearch && matchesStatus;
                        row.classList.toggle('d-none', !visible);
                        if (visible) {
                            visibleCount++;
                        }
                    });

                    emptyState.classList.toggle('d-none', visibleCount !== 0 || rows.length === 0);
                }

                if (searchInput && statusFilter) {
                    searchInput.addEventListener('input', applyFilters);
                    statusFilter.addEventListener('change', applyFilters);
                }
            })();
            </script>
            <?php
        } else {
            if (!isset($_GET['type'])) {

                if ($_SESSION['user_session']['role'] == 'admin') {

                    echo '<h2 class="mb-4">Welcome to City Tech Store</h2>';
                    echo '<p>What do you want to manage?</p>';
                    echo '<div class="btn-group">
            <a href="manage?type=products" class="btn btn-success">Products</a>
            <a href="manage?type=sales" class="btn btn-info">Sales</a>
            <a href="manage?type=categories" class="btn btn-secondary">Categories</a>
            <a href="manage?type=category_banners" class="btn btn-dark">Category Banners</a>
            <a href="manage?type=orders" class="btn btn-primary">Orders</a>
            <a href="manage?type=users" class="btn btn-warning">Users</a>
            </div>';
                } else {
                    echo '<h2 class="mb-4">Welcome to City Tech Store</h2>';
                    echo '<p>What do you want to manage?</p>';
                    echo '<div class="btn-group">
        <a href="manage?type=products" class="btn btn-success">Products</a>
        <a href="manage?type=sales" class="btn btn-info">Sales</a>
        <a href="manage?type=categories" class="btn btn-secondary">Categories</a>
        <a href="manage?type=category_banners" class="btn btn-dark">Category Banners</a>
        <a href="manage?type=orders" class="btn btn-primary">Orders</a>
        </div>';
                }
            }
        }
        ?>

        <?php
        if (isset($_GET['type']) && $_GET['type'] == 'products' && isset($_GET['action']) && $_GET['action'] == 'editproduct' && isset($_GET['pid']) && !empty($_GET['pid'])) {
            // get product info by ID
            $pid = user_input_sanitize($_GET['pid']);
            $productData = $ProductModel->getProductById($pid);

            $categoryOptionsHtmlEdit = '<option value="">Select category</option>';
            foreach ($categories as $cat) {
                $selectedAttr = ($cat['name'] === $productData['category']) ? ' selected' : '';
                $categoryOptionsHtmlEdit .= '<option value="' . htmlspecialchars($cat['name']) . '"' . $selectedAttr . '>' . htmlspecialchars($cat['name']) . '</option>';
            }

            $existingProductImages = $ProductImageModel->getImagesByProductId($pid);
            $existingImagesHtml = '';
            foreach ($existingProductImages as $img) {
                $existingImagesHtml .= '
                <div class="d-inline-block text-center me-2 mb-2" data-image-row="' . (int) $img['id'] . '">
                    <img src="' . htmlspecialchars($img['image_path']) . '" alt="Product image" width="80" height="80" class="d-block mb-1" style="object-fit: cover; border: 1px solid #dee2e6; border-radius: 4px;">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-product-image-btn"
                        data-image-id="' . (int) $img['id'] . '" data-product-id="' . (int) $pid . '">Remove</button>
                </div>';
            }
            if ($existingImagesHtml === '') {
                $existingImagesHtml = '<p class="text-muted mb-0">No gallery images yet.</p>';
            }

            echo '
        <div class="container mt-4">
    <h1>Edit Product Info</h1>
    <form action="manage" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="' . htmlspecialchars(generateCsrfToken()) . '">
        <!-- Product Information -->
        <div class="mb-3">
            <div class="row">
                <div class="col-md-6">
                <input type="hidden" class="form-control" id="productID" name="productID"
                value="' . $_GET['pid'] . '">
                    <label for="productName" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="productName" name="productName"
                        placeholder="Enter new product name" value="' . $productData['name'] . '" required>
                </div>
                <div class="col-md-6">
                    <label for="description" class="form-label">Description</label>
                    <input class="form-control" id="description" name="description" rows="3"
                        placeholder="Enter new product description" value="' . $productData['description'] . '"/>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="price" class="form-label">Price</label>
                    <div class="input-group">
                        <span class="input-group-text">₦</span>
                        <input type="number" class="form-control" id="price" name="price" placeholder="Enter price"
                            step="0.01" value="' . $productData['unit_price'] . '" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Enter quantity"
                        min="0" value="' . $productData['quantity'] . '" required>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="manufacturer" class="form-label">Manufacturer</label>
                    <input type="text" class="form-control" id="manufacturer" name="manufacturer"
                        placeholder="Enter manufacturer" value="' . $productData['manufacturer'] . '">
                </div>
                <div class="col-md-6">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-control" id="category" name="category" required>' . $categoryOptionsHtmlEdit . '</select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12">
                    <label class="form-label">Existing Gallery Images</label>
                    <div>' . $existingImagesHtml . '</div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="productImage" class="form-label">Add More Images</label>
                    <input type="file" class="form-control" id="productImage" name="productImages[]" accept="image/*" multiple>
                </div>
                <div class="col-md-6">
                    <input type="hidden" class="form-control" id="uploaderName" name="uploaderName" placeholder=""
                        value="' . $_SESSION['user_session']['id'] . '" required>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary" name="editProductInfo">Done</button>
    </form>

</div>
</div>
        ';

            echo '<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".remove-product-image-btn").forEach(function (button) {
        button.addEventListener("click", function () {
            if (!confirm("Remove this image?")) {
                return;
            }

            var imageId = button.getAttribute("data-image-id");
            var productId = button.getAttribute("data-product-id");

            button.disabled = true;

            var csrfToken = document.querySelector("meta[name=csrf-token]").content;

            fetch("product-image/remove", {
                method: "POST",
                headers: {
                    "Content-Type": "application/x-www-form-urlencoded",
                    "X-CSRF-Token": csrfToken
                },
                body: "imageId=" + encodeURIComponent(imageId) + "&productId=" + encodeURIComponent(productId)
            })
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    if (data.success) {
                        var row = button.closest("[data-image-row]");
                        row.parentNode.removeChild(row);
                    } else {
                        alert(data.message || "Could not remove image.");
                        button.disabled = false;
                    }
                })
                .catch(function (error) {
                    console.error("Remove image failed:", error);
                    alert("Something went wrong removing this image.");
                    button.disabled = false;
                });
        });
    });
});
</script>';
            // print_r($productData);
        }

        ?>
            </main>

            <footer class="admin-footer text-center text-muted small py-3">
                &copy; <?php echo COPYRIGHT_YEAR; ?> <?php echo htmlspecialchars(APP_NAME); ?>. All rights reserved.
            </footer>
        </div>
    </div>

    <?php require_once('includes/cdn_footer.php'); ?>
</body>

</html>

<!-- we'll rewrite the sales pages and controller such that, instead of asking instead of product full info, it will:
- ask for product id
- quantity sold
- price at which a quantity was sold

then collect the buyers info like:
fullname, email/phone, amount paid, payment method, the person who sold it (admin,worker, etc)

then it take the product id and minus the quantity sold out from the product table, and add it to the sales table including customer info that was asked for -->