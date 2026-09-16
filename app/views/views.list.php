<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once('includes/cdn_header.php'); ?>
    <title>CTS - Products</title>
</head>

<body>
    <?php
    if (isset($_SESSION['loggedin'])) {
        require_once('includes/loggedin_header.php');
    } else {
        require_once('includes/header.php');
    }
    ?>

    <?php if (isset($_GET['c']) && !empty($_GET['c']) && isset($_GET['p']) && !empty($_GET['p'])): ?>
        <?php $productByMan = $ProductModel->getProductByCriteria(trim($_GET['p']), trim($_GET['c'])); ?>

        <div class="container mt-5">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2">
                <h4 class="fs-4 mb-5">Available <?php echo ucfirst($_GET['p']); ?> Products</h4>
                <?php echoSearchForm(); ?>
            </div>
            <?php

            echo '
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col">Description</th>
            <th scope="col">Unit Price</th>
            <th scope="col">Quantity</th>
            <th scope="col">Manufacturer</th>
            <th scope="col">Category</th>
            <th scope="col">Product Image</th>
           
        </tr>
    </thead>
    <tbody>';
            ?>

            <?php foreach ($productByMan as $pbm): ?>
                <?php
                echo '<tr>
 <td>' . $pbm['id'] . '</td>
 <td>' . $pbm['name'] . '</td>
 <td>' . $pbm['description'] . '</td>
 <td>₦' . number_format($pbm['unit_price'], 2) . '</td>
 <td>' . number_format($pbm['quantity']) . '</td>
 <td>' . $pbm['manufacturer'] . '</td>
 <td>' . $pbm['category'] . '</td>
 <td><img src="' . $pbm['product_picture_url'] . '" alt="' . $pbm['name'] . '" width="50"></td>
</tr>';
                ?>
            <?php endforeach; ?>
            <?php echo '</tbody></table>'; ?>
        </div>
    <?php endif; ?>

    <?php if (!isset($_GET['c']) && !isset($_GET['p']) && !isset($_GET['searchProduct']) && !isset($_GET['searchCriteria']) && !isset($_GET['searchProduct'])): ?>

        <div class="container mt-5">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2">
                <h4 class="fs-4 mb-5">Available Product Categories</h4>
                <?php echoSearchForm(); ?>
            </div>
            <div class="row g-3">

                <?php foreach ($products as $product): ?>
                    <div class="col-md-3 mb-1">
                        <a href="/list?c=manufacturer&p=<?php echo $product['manufacturer']; ?>"
                            class="link text-decoration-none">
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-title">
                                        <?php echo $product['manufacturer'] . " - " . $product['category']; ?>
                                    </div>
                                    <div class="card-text">Count: <?php echo $product['quantity'] ?></div>
                                </div>
                            </div>
                        </a>

                    </div>
                <?php endforeach; ?>

            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['searchProduct']) && isset($_GET['searchInput']) && !empty($_GET['searchInput']) && isset($_GET['searchCriteria']) && !empty($_GET['searchCriteria'])): ?>
        <?php $productBySearch = $ProductModel->getProductByCriteria(user_input_sanitize(trim($_GET['searchInput'])), user_input_sanitize(strtolower(trim($_GET['searchCriteria'])))); ?>

        <div class="container mt-5">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2">
                <h4 class="fs-4 mb-5">Available Products from search '<?php echo ucfirst($_GET['searchInput']); ?>'</h4>
                <?php echoSearchForm(); ?>
            </div>
            <?php
            if (is_array($productBySearch)) {
                echo '
<table class="table table-striped table-bordered">
<thead>
<tr>
    <th scope="col">ID</th>
    <th scope="col">Name</th>
    <th scope="col">Description</th>
    <th scope="col">Unit Price</th>
    <th scope="col">Quantity</th>
    <th scope="col">Manufacturer</th>
    <th scope="col">Category</th>
    <th scope="col">Product Image</th>

</tr>
</thead>
<tbody>';
            }
            ?>
            <?php
            if (is_array($productBySearch) && count($productBySearch) == 0) {
                echo '<h4 class="text-muted text-center mt-5 mb-5">No products in stock for now</h4>';
            }
            ?>
            <?php if (is_array($productBySearch)):
                foreach ($productBySearch as $pbs): ?>
                    <?php
                    echo '<tr>
<td>' . $pbs['id'] . '</td>
<td>' . $pbs['name'] . '</td>
<td>' . $pbs['description'] . '</td>
<td>₦' . number_format($pbs['unit_price'], 2) . '</td>
<td>' . number_format($pbs['quantity']) . '</td>
<td>' . $pbs['manufacturer'] . '</td>
<td>' . $pbs['category'] . '</td>
<td><img src="' . $pbs['product_picture_url'] . '" alt="' . $pbs['name'] . '" width="50"></td>

</tr>';
                    ?>
                <?php endforeach; ?>
                <?php echo '</tbody></table>'; ?>
            <?php else:
                echo '<h4 class="text-muted text-center mt-5 mb-5">No result for your search</h4>';
            endif; ?>
        </div>
    <?php endif; ?>


    <?php require_once('includes/footer.php'); ?>
    <?php require_once('includes/cdn_footer.php'); ?>

</body>

</html>