<?php
session_start();

if (!isset($_SESSION['loggedin']) || !isset($_SESSION['user_session']) || $_SESSION['loggedin'] != true || empty($_SESSION['user_session'])) {
    header("Location: " . rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/') . "/login");
    exit();
}

if (isset($_GET['logout']) && $_GET['logout'] == 'true') {
    // Clear all session variables
    session_unset();

    // Destroy the session
    session_destroy();

    // Redirect to the login page
    header("Location: " . rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/') . "/login");
    exit();
}

// get all info
require_once('app/models/Database.php'); // Database Model
require_once('app/models/Product.php'); // Product Model
require_once('app/models/Sales.php'); // Sales Model
require_once('app/models/Users.php'); // Users Model
require_once('app/models/ProductCategory.php'); // Product Category DB Model (unrelated chart-cache table, see FIXME below)
require_once('app/models/Category.php'); // Category Model (controlled category master data)
require_once('app/models/ProductImage.php'); // Product gallery images Model
require_once('app/models/CategoryBanner.php'); // Category banner Model
require_once('app/models/Order.php'); // Order Model (storefront orders)
require_once('app/models/lib.php'); // function lib
require_once('app/models/Stock.php'); // Stock Model

// Validates and saves each file in a multi-file upload field (e.g.
// $_FILES['productImages']), returning the list of saved public paths.
// Dies with the same messages as the pre-existing single-file upload
// code on invalid input, so behavior stays consistent across both.
// $targetDir defaults to the original product-image destination so
// existing callers are unaffected; other callers (e.g. category
// banners) pass their own directory to reuse the same validation.
function uploadProductImages($filesField, $targetDir = "assets/img/products/") {
    $savedPaths = [];

    if (!isset($_FILES[$filesField]) || empty($_FILES[$filesField]['name'][0])) {
        return $savedPaths;
    }

    $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
    $fileCount = count($_FILES[$filesField]['name']);

    for ($i = 0; $i < $fileCount; $i++) {
        if ($_FILES[$filesField]['error'][$i] !== 0) {
            continue;
        }

        $fileName = basename($_FILES[$filesField]['name'][$i]);
        $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if (!in_array($fileType, $allowTypes)) {
            die("Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.");
        }

        $randomString = bin2hex(random_bytes(8));
        $newFileName = md5($fileName . $randomString) . '.' . $fileType;
        $targetFilePath = $targetDir . $newFileName;

        if (move_uploaded_file($_FILES[$filesField]['tmp_name'][$i], $targetFilePath)) {
            $savedPaths[] = $targetDir . $newFileName;
        } else {
            die("Sorry, there was an error uploading your file.");
        }
    }

    return $savedPaths;
}

// FIXME (flagged, not fixed here): ProductCategory::populateProductCategoryTable(),
// called as a side effect of requiring ProductCategory.php above, builds its
// INSERT statement via raw string interpolation ("...VALUES ('$category', ...)")
// instead of a prepared statement — a real SQL injection risk on every load of
// this page. Needs a real fix as its own follow-up, not folded into this change.

$_SESSION['products'] = $ProductModel->getAllProducts();
$_SESSION['sales'] = $SalesModel->getAllSales();
$_SESSION['users'] = $UsersModel->getAllUsers();

// keep in session
$products = $_SESSION['products'] ?? [];
$sales = $_SESSION['sales'] ?? [];
$users = $_SESSION['users'] ?? [];
$latest_activities = $_SESSION['latest_activities'] ?? [];
$categories = $CategoryModel->getAllCategories();
$orders = $OrderModel->getAllOrders();

// total sales and products price
$total_sales_price = array_sum(array_column($sales, 'total_price'));
$total_products_price = array_sum(array_column($products, 'total_price'));

// total products and sales count, plus users
$total_products_count = count($products);
$total_sales_count = count($sales);
$total_users = count($users);

if (isset($_GET['type']) && (strtolower($_GET['type']) == 'users' || strtolower($_GET['type']) == 'user')) {
    requireRole(['admin']);
}

if (isset($_GET['type']) && strtolower($_GET['type']) == 'orders') {
    requireRole(['admin', 'worker']);
}


if (isset($_POST['addProduct']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        header("Location: " . rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/') . "/manage?type=products&status=failed&message=" . urlencode('Your session expired. Please try again.'));
        exit();
    }

    // Collect and sanitize input data
    $productName = user_input_sanitize($_POST['productName']);
    $description = user_input_sanitize($_POST['description']);
    $price = user_input_sanitize($_POST['price']);
    $quantity = user_input_sanitize($_POST['quantity']);
    $manufacturer = user_input_sanitize($_POST['manufacturer']);
    $category = user_input_sanitize($_POST['category']);
    $uploaderName = user_input_sanitize($_POST['uploaderName']);


    // Handle (possibly multiple) file upload
    $uploadedImagePaths = uploadProductImages('productImages');
    $productImage = !empty($uploadedImagePaths) ? $uploadedImagePaths[0] : 'assets/img/defaults/product.jpg';

    // search maybe the man, name and category already exists before ,then update it
    $nameCheck = $ProductModel->getProductByCriteria($productName, 'name');
    $manCheck = $ProductModel->getProductByCriteria($manufacturer, 'manufacturer');
    $catCheck = $ProductModel->getProductByCriteria($category, 'category');

    // $nameCheck = $ProductModel->getProductByCriteria('iphone 132', 'name');
    // $manCheck = $ProductModel->getProductByCriteria('Apple', 'manufacturer');
    // $catCheck = $ProductModel->getProductByCriteria('phone', 'category');

    if ($nameCheck && $manCheck && $catCheck) {
        $productID = $ProductModel->getProductByCriteria($productName, 'name')[0]['id'];
        file_put_contents('newp.txt', $productID);
        $updateProductArr = [
            'description' => $description,
            'unit_price' => $price,
            'quantity' => (int) $quantity + (int) $ProductModel->getProductByCriteria($productName, 'name')[0]['quantity'],
            'total_price' => ((int) $price * (int) $quantity),
            'product_picture_url' => $productImage,
            'uploaded_by' => $uploaderName
        ];
        // take stock
        //    ;
        if (
            $ProductModel->arrayUpdateProduct($updateProductArr, $productID) &&
            $StockModel->recordStockActivity([
                'product_id' => $productID,
                'activity_type' => 'add',
                'quantity' => $quantity,
                'remaining_quantity' => $ProductModel->getProductById($productID)['quantity']
            ])
        ) {
            $displayOrder = $ProductImageModel->getNextDisplayOrder($productID);
            foreach ($uploadedImagePaths as $imagePath) {
                $ProductImageModel->addImage($productID, $imagePath, $displayOrder);
                $displayOrder++;
            }

            $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
            header("Location: " . $basePath . "/manage?type=products&status=success&init=updateProduct");
            exit();
        } else {
            header("Location: " . rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/') . "/manage?type=products&status=failed&init=updateProduct");
            exit();
        }
    } else {
        // file_put_contents('newp.txt', 'new product found!!!');
        // }
        // Insert data into the database
        $ProductModel->addProduct([
            'name' => $productName,
            'description' => ucfirst($description),
            'unit_price' => $price,
            'quantity' => $quantity,
            'total_price' => $price * $quantity,
            'manufacturer' => $manufacturer,
            'category' => $category,
            'uploaded_by' => $uploaderName,
            'product_picture_url' => $productImage
            // id	name	description	price	quantity	manufacturer	category	uploaded_by	product_picture_url	created_at	
        ]);

        $productId = $ProductModel->getProductByCriteria($productImage, 'product_picture_url')[0]['id'];
        // print_r($productId1);

        $displayOrder = 0;
        foreach ($uploadedImagePaths as $imagePath) {
            $ProductImageModel->addImage($productId, $imagePath, $displayOrder);
            $displayOrder++;
        }

        // take stock
        $StockModel->recordStockActivity([
            'product_id' => $productId,
            'activity_type' => 'add',
            'quantity' => $quantity,
            'remaining_quantity' => $ProductModel->getProductById($productId)['quantity']
        ]);
        $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
        header("Location: " . $basePath . "/manage?type=products&status=success&init=upload");
        exit();
    }
    // Redirect or inform the user

}

if (isset($_POST['addSales']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        header('Location: ' . rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/') . '/manage?type=sales&status=failed&message=' . urlencode('Your session expired. Please try again.'));
        exit();
    }

    // Gather sales data
    $productId = $_POST['product_id'];
    $quantitySold = $_POST['quantity_sold'];
    $pricePerUnit = $ProductModel->getProductById($productId)['unit_price'];

    $customerName = $_POST['customer_name'];
    $customerContact = $_POST['customer_contact'];
    $amountPaid = $_POST['amount_paid'];
    $paymentMethod = $_POST['payment_method'];
    $workerName = $_POST['worker_name'];

    $totalPricePaid = $quantitySold * $amountPaid;

    // Handle file upload
    file_put_contents('file-debug.txt', $_FILES['customerImage']);
    if (isset($_FILES['customerImage']) && $_FILES['customerImage']['error'] == 0) {
        $targetDir = "assets/img/customers/";
        $fileName = basename($_FILES["customerImage"]["name"]);
        $fileType = pathinfo($fileName, PATHINFO_EXTENSION);

        // Allow certain file formats
        $allowTypes = array('jpg', 'png', 'jpeg', 'gif');
        if (in_array($fileType, $allowTypes)) {
            // Generate a unique file name
            $randomString = bin2hex(random_bytes(8)); // Generate a random string
            $newFileName = md5($fileName . $randomString) . '.' . $fileType; // Concatenate and hash
            $targetFilePath = $targetDir . $newFileName;

            // Upload file to server
            if (move_uploaded_file($_FILES["customerImage"]["tmp_name"], $targetFilePath)) {
                $customerImage = 'assets/img/customers/' . $newFileName;
            } else {
                die("Sorry, there was an error uploading your file.");
            }
        } else {
            die("Sorry, only JPG, JPEG, PNG, & GIF files are allowed to upload.");
        }
    } else {
        $customerImage = 'assets/img/defaults/product.jpg';
    }

    // Check if the product exists and has enough quantity
    $product = $ProductModel->getProductById($productId);
    if ($product && $product['quantity'] >= $quantitySold) {
        // Deduct the sold quantity from the product
        $newQuantity = $product['quantity'] - $quantitySold;
        $ProductModel->updateProductQuantity($productId, $newQuantity);

        // Save the sales data to the database
        $salesData = [
            'product_id' => $productId,
            'quantity' => $quantitySold,
            'price_per_unit' => $pricePerUnit,
            'total_price' => $totalPricePaid,
            'amount_paid' => $amountPaid,
            'customer_name' => $customerName,
            'customer_email' => $customerContact,
            'payment_method' => $paymentMethod,
            'worker_id' => $workerName,
            'customer_picture_url' => $customerImage
        ];

        $SalesModel->addSales($salesData);

        // get product info
        $productData = $ProductModel->getProductById($productId);
        $updateProductTable = [
            'total_price' => $productData['total_price'] - $totalPricePaid
        ];
        $ProductModel->arrayUpdateProduct(
            $updateProductTable,
            $productId
        );

        // take stock
        $StockModel->recordStockActivity([
            'product_id' => $productId,
            'activity_type' => 'sale',
            'quantity' => $quantitySold,
            'remaining_quantity' => $newQuantity
        ]);
        // Redirect or show a success message
        $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
        header('Location: ' . $basePath . '/manage?type=sales&init=addsales&status=success');
        exit();
    } else {
        // Handle the error (e.g., product not found or insufficient quantity)
        echo "Product not found or insufficient quantity.";
        header('Location: ' . rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/') . '/manage?type=sales&init=addsales&status=failed');
    }
}

if (isset($_POST['addCategory']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');

    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        header('Location: ' . $basePath . '/manage?type=categories&status=failed&message=' . urlencode('Your session expired. Please try again.'));
        exit();
    }

    $categoryName = user_input_sanitize($_POST['categoryName']);

    if ($categoryName === '') {
        header('Location: ' . $basePath . '/manage?type=categories&status=failed&message=' . urlencode('Category name cannot be empty.'));
        exit();
    }

    try {
        $CategoryModel->addCategory($categoryName);
        header('Location: ' . $basePath . '/manage?type=categories&status=success');
        exit();
    } catch (Exception $e) {
        header('Location: ' . $basePath . '/manage?type=categories&status=failed&message=' . urlencode($e->getMessage()));
        exit();
    }
}

if (isset($_POST['saveCategoryBanner']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');

    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        header('Location: ' . $basePath . '/manage?type=category_banners&status=failed&message=' . urlencode('Your session expired. Please try again.'));
        exit();
    }

    $categoryId = filter_var($_POST['categoryId'] ?? null, FILTER_VALIDATE_INT);
    $headline = user_input_sanitize($_POST['headline'] ?? '');
    $subtext = user_input_sanitize($_POST['subtext'] ?? '');
    // Not run through user_input_sanitize: that strips characters ("/", ":",
    // "?", "=", "&") that a real URL needs. Bound as a prepared-statement
    // parameter (SQL-safe) and htmlspecialchars()'d on output (XSS-safe).
    $linkUrl = trim($_POST['linkUrl'] ?? '');

    $uploadedBannerImages = uploadProductImages('bannerImages', 'assets/img/category_banners/');
    if (!empty($uploadedBannerImages)) {
        $imagePath = $uploadedBannerImages[0];
    } else {
        $existingBanner = $categoryId ? $CategoryBannerModel->getBannerByCategoryId($categoryId) : null;
        $imagePath = $existingBanner['image_path'] ?? null;
    }

    if (!$categoryId || $headline === '' || $imagePath === null) {
        header('Location: ' . $basePath . '/manage?type=category_banners&status=failed&message=' . urlencode('A category, headline, and image are all required.'));
        exit();
    }

    if ($linkUrl === '') {
        $categoryName = null;
        foreach ($categories as $cat) {
            if ((int) $cat['id'] === $categoryId) {
                $categoryName = $cat['name'];
                break;
            }
        }
        $linkUrl = $categoryName !== null ? ('shop?c=category&p=' . urlencode($categoryName)) : '';
    }

    $CategoryBannerModel->saveBanner($categoryId, $imagePath, $headline, $subtext, $linkUrl);

    header('Location: ' . $basePath . '/manage?type=category_banners&status=success');
    exit();
}

if (isset($_POST['editProductInfo']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        header("Location: " . rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/') . "/manage?type=products&status=failed&message=" . urlencode('Your session expired. Please try again.'));
        exit();
    }

    // Collect and sanitize input data
    $productName = user_input_sanitize($_POST['productName']);
    $description = user_input_sanitize($_POST['description']);
    $price = user_input_sanitize($_POST['price']);
    $quantity = user_input_sanitize($_POST['quantity']);
    $manufacturer = user_input_sanitize($_POST['manufacturer']);
    $category = user_input_sanitize($_POST['category']);
    $uploaderName = user_input_sanitize($_POST['uploaderName']);
    $productID = user_input_sanitize($_POST['productID']);


    // Handle (possibly multiple) new file uploads. Editing a product no
    // longer requires re-selecting an image every time — if none are
    // uploaded, the existing product_picture_url is left untouched instead
    // of being reset to the default placeholder.
    $uploadedImagePaths = uploadProductImages('productImages');
    if (!empty($uploadedImagePaths)) {
        $productImage = $uploadedImagePaths[0];
    } else {
        $existingProduct = $ProductModel->getProductById($productID);
        $productImage = $existingProduct['product_picture_url'];
    }

    $updateStockValues = [
        'product_id' => $productID,
        'activity_type' => 'updated',
        'quantity' => $quantity,
        'remaining_quantity' => $ProductModel->getProductById($productID)['quantity']
    ];
    $editingProductArr = [
        'name' => $productName,
        'description' => $description,
        'unit_price' => $price,
        'total_price' => (int) $quantity * (int) $price,
        'quantity' => $quantity,
        'manufacturer' => $manufacturer,
        'category' => $category,
        'uploaded_by' => $uploaderName,
        'product_picture_url' => $productImage,
    ];

    if (
        $ProductModel->arrayUpdateProduct($editingProductArr, $productID) &&
        $StockModel->recordStockActivity($updateStockValues)
    ) {
        $displayOrder = $ProductImageModel->getNextDisplayOrder($productID);
        foreach ($uploadedImagePaths as $imagePath) {
            $ProductImageModel->addImage($productID, $imagePath, $displayOrder);
            $displayOrder++;
        }

        $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');
        header("Location: " . $basePath . "/manage?type=products&status=success&init=updateProduct");
        exit();
    } else {
        header("Location: " . rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/') . "/manage?type=products&status=failed&init=updateProduct");
        exit();
    }
}

if (isset($_POST['addStaff']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    requireRole(['admin']);

    $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');

    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        header('Location: ' . $basePath . '/manage?type=users&status=failed&message=' . urlencode('Your session expired. Please try again.'));
        exit();
    }

    $rawFields = [
        'username' => $_POST['username'] ?? '',
        'password' => $_POST['password'] ?? '',
        'email' => $_POST['email'] ?? '',
        'firstname' => $_POST['firstname'] ?? '',
        'lastname' => $_POST['lastname'] ?? '',
        'phone_number' => $_POST['phone_number'] ?? '',
        'role' => $_POST['role'] ?? '',
    ];

    foreach ($rawFields as $key => $value) {
        if (trim($value) === '') {
            header('Location: ' . $basePath . '/manage?type=users&status=failed&message=' . urlencode(ucfirst(str_replace('_', ' ', $key)) . ' is required.'));
            exit();
        }
    }

    if (!in_array($rawFields['role'], ['admin', 'worker'], true)) {
        header('Location: ' . $basePath . '/manage?type=users&status=failed&message=' . urlencode('Invalid role selected.'));
        exit();
    }

    $newStaff = [
        'username' => htmlspecialchars($rawFields['username'], ENT_QUOTES, 'UTF-8'),
        'email' => filter_var($rawFields['email'], FILTER_SANITIZE_EMAIL),
        'firstname' => htmlspecialchars($rawFields['firstname'], ENT_QUOTES, 'UTF-8'),
        'lastname' => htmlspecialchars($rawFields['lastname'], ENT_QUOTES, 'UTF-8'),
        'password' => password_hash($rawFields['password'], PASSWORD_BCRYPT),
        'phone_number' => htmlspecialchars($rawFields['phone_number'], ENT_QUOTES, 'UTF-8'),
        'role' => $rawFields['role'],
    ];

    try {
        $UsersModel->createUser($newStaff);
        header('Location: ' . $basePath . '/manage?type=users&status=success');
        exit();
    } catch (Exception $e) {
        header('Location: ' . $basePath . '/manage?type=users&status=failed&message=' . urlencode($e->getMessage()));
        exit();
    }
}

if (isset($_POST['deactivateStaff']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    requireRole(['admin']);

    $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');

    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        header('Location: ' . $basePath . '/manage?type=users&status=failed&message=' . urlencode('Your session expired. Please try again.'));
        exit();
    }

    $staffId = filter_var($_POST['userId'] ?? null, FILTER_VALIDATE_INT);

    if (!$staffId) {
        header('Location: ' . $basePath . '/manage?type=users&status=failed&message=' . urlencode('Invalid staff member.'));
        exit();
    }

    if ($staffId === (int) $_SESSION['user_session']['id']) {
        header('Location: ' . $basePath . '/manage?type=users&status=failed&message=' . urlencode('You cannot deactivate your own account.'));
        exit();
    }

    $UsersModel->deactivateUser($staffId);
    header('Location: ' . $basePath . '/manage?type=users&status=success&message=' . urlencode('Staff member deactivated.'));
    exit();
}

if (isset($_POST['activateStaff']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    requireRole(['admin']);

    $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');

    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        header('Location: ' . $basePath . '/manage?type=users&status=failed&message=' . urlencode('Your session expired. Please try again.'));
        exit();
    }

    $staffId = filter_var($_POST['userId'] ?? null, FILTER_VALIDATE_INT);

    if (!$staffId) {
        header('Location: ' . $basePath . '/manage?type=users&status=failed&message=' . urlencode('Invalid staff member.'));
        exit();
    }

    $UsersModel->activateUser($staffId);
    header('Location: ' . $basePath . '/manage?type=users&status=success&message=' . urlencode('Staff member reactivated.'));
    exit();
}

if (isset($_POST['updateOrderStatus']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    requireRole(['admin']);

    $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/');

    if (!validateCsrfToken($_POST['csrf_token'] ?? '')) {
        header('Location: ' . $basePath . '/manage?type=orders&status=failed&message=' . urlencode('Your session expired. Please try again.'));
        exit();
    }

    $orderId = filter_var($_POST['orderId'] ?? null, FILTER_VALIDATE_INT);
    $newStatus = $_POST['status'] ?? '';

    if (!$orderId) {
        header('Location: ' . $basePath . '/manage?type=orders&status=failed&message=' . urlencode('Invalid order.'));
        exit();
    }

    try {
        $OrderModel->updateOrderStatus($orderId, $newStatus);
        header('Location: ' . $basePath . '/manage?type=orders&status=success&message=' . urlencode('Order status updated.'));
        exit();
    } catch (Exception $e) {
        header('Location: ' . $basePath . '/manage?type=orders&status=failed&message=' . urlencode($e->getMessage()));
        exit();
    }
}


// $nameCheck = $ProductModel->getProductByCriteria('iphone 12', 'name')[0]["id"];
// $manCheck = $ProductModel->getProductByCriteria('Apple', 'manufacturer');
// $catCheck = $ProductModel->getProductByCriteria('phone', 'category');
// file_put_contents('sql-debug.json', json_encode($nameCheck));

// file_put_contents('sess_users.json', json_encode($_SESSION['users']));

require "app/views/views.manage.php";
