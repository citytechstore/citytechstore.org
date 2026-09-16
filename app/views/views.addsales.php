<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once('includes/cdn_header.php'); ?>
    <title>Record Sale</title>
</head>
<body>
    <?php require_once('includes/header.php'); ?>
    <div class="container">
        <h1>Record Sale</h1>
        <form method="POST" enctype="multipart/form-data" action="controller/add_sale.php">
            <!-- Product Information -->
            <div class="mb-3">
                <h2>Product Information</h2>
                <div class="row">
                    <div class="col-md-6">
                        <label for="productName" class="form-label">Product Name</label>
                        <input type="text" class="form-control" name="product_name" id="productName" placeholder="Enter product name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" name="description" id="description" rows="3" placeholder="Enter product description"></textarea>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="price" class="form-label">Price</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" name="price" id="price" placeholder="Enter price" step="0.01" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="quantity" class="form-label">Quantity</label>
                        <input type="number" class="form-control" name="quantity" id="quantity" placeholder="Enter quantity" min="0" required>
                    </div>
                </div>
            </div>
            <hr>
            <!-- Sales Information -->
            <div class="mb-3">
                <h2>Sales Information</h2>
                <div class="row">
                    <div class="col-md-6">
                        <label for="customerName" class="form-label">Customer Name</label>
                        <input type="text" class="form-control" name="customer_name" id="customerName" placeholder="Enter customer name" required>
                    </div>
                    <div class="col-md-6">
                        <label for="customerEmail" class="form-label">Customer Email</label>
                        <input type="email" class="form-control" name="customer_email" id="customerEmail" placeholder="Enter customer email">
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
                            <option value="">Select payment method</option>
                            <option value="Cash">Cash</option>
                            <option value="Credit Card">Credit Card</option>
                            <option value="PayPal">PayPal</option>
                            <!-- Add more payment methods as needed -->
                        </select>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <label for="workerName" class="form-label">Worker Name</label>
                        <input type="text" class="form-control" name="worker_name" id="workerName" placeholder="Enter worker name" required>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Record Sale</button>
        </form>
    </div>
    <?php require_once('includes/footer.php'); ?>
    <?php require_once('includes/cdn_footer.php'); ?>
</body>
</html>
