<?php

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

    // take stock
    $StockModel->recordStockActivity([
        'product_id' => $productId,
        'activity_type' => 'add',
        'quantity' => $quantity,
        'remaining_quantity' => $ProductModel->getProductById($productId)['quantity']
    ]);
    header("Location: /manage?type=products&status=success&init=upload");
    exit();
}
// Re