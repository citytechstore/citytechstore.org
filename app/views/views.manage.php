<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once('includes/cdn_header.php'); ?>
    <?php
    $page_title = '';
    if (isset($_GET['type']) && (strtolower($_GET['type']) == 'products' || strtolower($_GET['type']) == 'product')) {
        $page_title = 'CTS - Products management';
    } else if (isset($_GET['type']) && (strtolower($_GET['type']) == 'sale' || strtolower($_GET['type']) == 'sales')) {
        $page_title = 'CTS - Sales management';
    } else if (isset($_GET['type']) && (strtolower($_GET['type']) == 'user' || strtolower($_GET['type']) == 'users')) {
        $page_title = 'CTS - Users management';
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

    <?php
    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] = true) {
        require_once('includes/loggedin_header.php');
    } else {
        require_once('includes/header.php');
    }
    ?>



    <div class="container mt-4">
        <?php
        if (isset($_GET['type']) && (strtolower($_GET['type']) == 'products' || strtolower($_GET['type']) == 'product') && !isset($_GET['action'])) {

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
                  <form action="/manage" method="POST" enctype="multipart/form-data">
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
                                  <input type="text" class="form-control" id="category" name="category" placeholder="Enter category">
                              </div>
                          </div>
                          <div class="row mt-3">
                              <div class="col-md-6">
                                  <label for="productImage" class="form-label">Product Image</label>
                                  <input type="file" class="form-control" id="productImage" name="productImage" accept="image/*">
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
      <form method="POST" action="/manage" enctype="multipart/form-data">
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

            if ($_SESSION['user_session']['role'] == 'admin') {
                $trs = '';
                $html_data1 = '
                <div class="container">
              <div class="row mb-5">
                  <div class="col-md-12">
                      <h4 class="mb-3">Manage All Users</h4>
                      <a href="/adduser" class="btn btn-info">Add Users</a>

                      <table class="table">
                          <thead>
                              <tr>
                                  <th scope="col">ID</th>
                                  <th scope="col">Firstname</th>
                                  <th scope="col">Lastname</th>
                                  <th scope="col">Username</th>
                                  <th scope="col">Email</th>
                                  <th scope="col">Phone</th>
                                  <th scope="col">Role</th>
                                  <th scope="col">Date Created</th>
                                  <th scope="col">Action</th>
                              </tr>
                          </thead>
                          <tbody>';
        
                foreach ($users as $user) {
                    $trs .= '
                                  <tr>
                                      <td>' . ucfirst($user['id']) . '</td>
                                      <td>' . ucfirst($user['firstname']) . '</td>
                                      <td>' . ucfirst($user['lastname']) . '</td>
                                      <td>' . ucfirst($user['username']) . '</td>
                                      <td>' . ucfirst($user['email']) . '</td>
                                      <td>' . ucfirst($user['phone_number']) . '</td>
                                      <td>' . ucfirst($user['role']) . '</td>
                                      <td>' . ucfirst($user['created_at']) . '</td>
                                     <td class="text-center"><a href="adduser?action=delete&id=' . $user['id'] . '"><i class="fa fa-trash text-danger"></i></a></td>
                                  </tr>';
                }
                $html_data2 = ' </tbody>
                      </table>
                      <a href="/adduser" class="btn btn-info">Add Users</a>
                  </div>
                  </div>
              </div>';
        
              echo $html_data1.$trs.$html_data2;
            }
            // <th scope="col">Picture</th>
        
        //     <td>
        //     <img src="' . $user['profile_picture'] . '"
        //         alt="' .  ucfirst($user['firstname']) . ' not found" width="45px"
        //         hehight="45px" class="rounded-pill">
        // </td>
        } else {
            if (!isset($_GET['type'])) {

                if ($_SESSION['user_session']['role'] == 'admin') {

                    echo '<h2 class="mb-4">Welcome to City Tech Store</h2>';
                    echo '<p>What do you want to manage?</p>';
                    echo '<div class="btn-group">
            <a href="/manage?type=products" class="btn btn-success">Products</a>
            <a href="/manage?type=sales" class="btn btn-info">Sales</a>
            <a href="/manage?type=users" class="btn btn-warning">Users</a>
            </div>';
                } else {
                    echo '<h2 class="mb-4">Welcome to City Tech Store</h2>';
                    echo '<p>What do you want to manage?</p>';
                    echo '<div class="btn-group">
        <a href="/manage?type=products" class="btn btn-success">Products</a>
        <a href="/manage?type=sales" class="btn btn-info">Sales</a>
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
            echo '
        <div class="container mt-4">
    <h1>Edit Product Info</h1>
    <form action="/manage" method="POST" enctype="multipart/form-data">
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
                    <input type="text" class="form-control" id="category" name="category" placeholder="Enter category" value="' . $productData['category'] . '">
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-md-6">
                    <label for="productImage" class="form-label">Product Image</label>
                    <input type="file" class="form-control" id="productImage" name="productImage" accept="image/*" value="' . $productData['product_picture_url'] . '">
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
            // print_r($productData);
        }

        ?>
    </div>


    <?php require_once('includes/footer.php'); ?>
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