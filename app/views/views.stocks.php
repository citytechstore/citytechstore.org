<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require_once ('includes/cdn_header.php'); ?>
    <title>CTS - Stock Activities</title>
</head>

<body>
    <?php require_once ('includes/loggedin_header.php'); ?>
    <div class="container mt-5">
        <h1 class="mb-3">Stock Activities</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Activity Type</th>
                    <th>Old Quantity</th>
                    <th>New Quantity</th>
                    <th>Remark</th>
                    <th>Activity Date</th>
                </tr>
            </thead>
            <tbody>

                <?php
                foreach ($stockActivities as $activity) {

                    echo "<tr>";
                    echo "<td>{$activity['id']}</td>";
                    echo "<td>{$activity['product_id']}</td>";
                    echo '<td>' . $ProductModel->getProductById($activity['product_id'])['name'] . '</td>';
                    if($activity['activity_type'] == "add"){
                        echo '<td class="fw-bold text-success">Added to Stock</td>' ;
                    }elseif($activity['activity_type'] == "sale"){
                        echo '<td class="text-danger fw-bold">Sold out</td>';
                    }elseif($activity['activity_type'] == "updated"){
                        echo '<td class="text-warning fw-bold">Updated Stock</td>';
                    }
                    echo "<td>{$activity['remaining_quantity']}</td>";
                    echo "<td>{$activity['quantity']}</td>";
                                        if ($activity['remaining_quantity'] <= 10) {
                        echo '<td class="bg-danger fw-bold">Product is low in stock</td>';
                    } else {
                        echo '<td class="text-info"> - </td>';

                    }
                    echo "<td>{$activity['activity_date']}</td>";
                    echo "</tr>";
                    // print_r();
                
                }

                ?>
            </tbody>
        </table>
        <?php
         if (count($stockActivities) == 0) {
            echo '<h4 class="text-muted text-center mt-5 mb-5">Stock is empty</h4>';
        }
        ?>

        </div>

        <!-- Latest Activity Section -->
        <?php require_once ('includes/footer.php'); ?>
        <?php require_once ('includes/cdn_footer.php'); ?>
</body>

</html>