<!-- <?php
// require_once ('Product.php');
// require_once ('Sales.php');
require_once ('Users.php');

// // add products:
// $products = [
//     [
//         'name' => 'Laptop',
//         'description' => 'Powerful laptop with high-end specifications.',
//         'unit_price' => 999.99,
//         'quantity' => 10,
//         'manufacturer' => 'Dell',
//         'category' => 'Electronics',
//         'uploaded_by' => 'admin',
//         'product_picture_url' => 'https://example.com/laptop.jpg',
//         'created_at' => '2024-05-23 10:00:00'
//     ],
//     [
//         'name' => 'Smartphone',
//         'description' => 'Feature-packed smartphone with the latest technology.',
//         'unit_price' => 699.99,
//         'quantity' => 15,
//         'manufacturer' => 'Samsung',
//         'category' => 'Electronics',
//         'uploaded_by' => 'admin',
//         'product_picture_url' => 'https://example.com/smartphone.jpg',
//         'created_at' => '2024-05-24 11:00:00'
//     ],
//     [
//         'name' => 'Wireless Headphones',
//         'description' => 'High-quality wireless headphones for an immersive audio experience.',
//         'unit_price' => 149.99,
//         'quantity' => 20,
//         'manufacturer' => 'Sony',
//         'category' => 'Electronics',
//         'uploaded_by' => 'admin',
//         'product_picture_url' => 'https://example.com/headphones.jpg',
//         'created_at' => '2024-05-25 12:00:00'
//     ]
// ];

// $sales = [
//     [
//         // 'id' => 1,
//         'product_id' => 101,
//         'quantity' => 2,
//         'total_price' => 1999.98,
//         'amount_paid' => 1999.98,
//         'sale_date' => '2024-05-23 10:00:00',
//         'customer_name' => 'John Doe',
//         'customer_email' => 'johndoe@example.com',
//         'payment_method' => 'Credit Card',
//         'customer_picture_url' => 'https://example.com/customers/johndoe.jpg',
//         'worker_id' => 1
//     ],
//     [
//         // 'id' => 2,
//         'product_id' => 102,
//         'quantity' => 1,
//         'total_price' => 699.99,
//         'amount_paid' => 699.99,
//         'sale_date' => '2024-05-24 11:00:00',
//         'customer_name' => 'Jane Smith',
//         'customer_email' => 'janesmith@example.com',
//         'payment_method' => 'PayPal',
//         'customer_picture_url' => 'https://example.com/customers/janesmith.jpg',
//         'worker_id' => 2
//     ],
//     [
//         // 'id' => 3,
//         'product_id' => 103,
//         'quantity' => 3,
//         'total_price' => 449.97,
//         'amount_paid' => 449.97,
//         'sale_date' => '2024-05-25 12:00:00',
//         'customer_name' => 'Alice Johnson',
//         'customer_email' => 'alicejohnson@example.com',
//         'payment_method' => 'Debit Card',
//         'customer_picture_url' => 'https://example.com/customers/alicejohnson.jpg',
//         'worker_id' => 1
//     ],
//     // Add more sales as needed
// ];


// $addProduct = false;

// foreach($products as $product){
// $addProduct = $ProductModel->addProduct($product);
// echo $appProduct;
// }

// Example usage
// $product_id = 1;
// $product_details = $ProductModel->getProductByCriteria('aptop');
// if ($product_details) {
//     // Product found
//     print_r($product_details);
// } else {
//     // Product not found
//     echo "Product not found!";
// }



// Assuming you have already instantiated your Database class and stored it in $db_instance

// Initialize Sales model

// $addSales = false;

// foreach($sales as $sale){
// $addSales = $SalesModel->addSales($sale);
// echo $addSales;
// }


// Example usage to get sales by customer name with partial match (default criteria)
// $sales_by_customer_name = $SalesModel->getSaleByCriteria('Partial Customer Name');
// if ($sales_by_customer_name) {
//     // Sales found
//     print_r($sales_by_customer_name);
// } else {
//     // No sales found
//     echo "No sales found with the provided customer name!";
// }

// Example usage to get sales by payment method with partial match
// $sales_by_payment_method = $SalesModel->getSaleByCriteria('Credit Card', 'payment_method');
// if ($sales_by_payment_method) {
//     // Sales found
//     print_r($sales_by_payment_method);
// } else {
//     // No sales found
//     echo "No sales found with the provided payment method!";
// }

// // Similar usage for worker name
// $sales_by_worker_name = $SalesModel->getSaleByCriteria('John Doe', 'worker_id');
// if ($sales_by_worker_name) {
//     // Sales found
//     print_r($sales_by_worker_name);
// } else {
//     // No sales found
//     echo "No sales found with the provided worker name!";
// }


// create users
// $users = [
//     [
//         // 'id' => 1,
//         'username' => 'admin',
//         'password' => password_hash('admin123', PASSWORD_BCRYPT),
//         'email' => 'admin@example.com',
//         'firstname' => 'Admin',
//         'lastname' => 'User',
//         'phone_number' => '1234567890',
//         'role' => 'admin',
//         'created_at' => '2024-05-21 09:00:00'
//     ],
//     [
//         // 'id' => 2,
//         'username' => 'worker1',
//         'password' => password_hash('worker123', PASSWORD_BCRYPT),
//         'email' => 'worker1@example.com',
//         'firstname' => 'John',
//         'lastname' => 'Doe',
//         'phone_number' => '0987654321',
//         'role' => 'worker',
//         'created_at' => '2024-05-21 10:00:00'
//     ],
//     [
//         // 'id' => 3,
//         'username' => 'worker2',
//         'password' => password_hash('worker123', PASSWORD_BCRYPT),
//         'email' => 'worker2@example.com',
//         'firstname' => 'Jane',
//         'lastname' => 'Smith',
//         'phone_number' => '1122334455',
//         'role' => 'worker',
//         'created_at' => '2024-05-21 11:00:00'
//     ],
//     [
//         // 'id' => 3,
//         'username' => 'developer',
//         'password' => password_hash('stevesplash1234', PASSWORD_BCRYPT),
//         'email' => 'developer@example.com',
//         'firstname' => 'Steve',
//         'lastname' => 'Splash',
//         'phone_number' => '0122334455',
//         'role' => 'worker',
//         'created_at' => '2024-05-21 11:00:00'
//     ],
//     // Add more users as needed
// ];

// foreach($users as $user){
//     $UsersModel->createUser($user);
// }

$json_data = '{
  "username": "admin2",
  "email": "stevesplash4@gmail.com",
  "firstname": "Steve",
  "lastname": "Splash",
  "password": "$2y$10$J8CjrjYA.oQR4s/aQ37pPecCeP4d1pWCBy4N7zIxw2i.DWQgUI8t.",
  "phone_number": "09035211815",
  "role": "admin"
}
';

$UsersModel->createUser(json_decode($json_data, true));


// INSERT INTO users (username, password, email, firstname, lastname, phone_number, role) 
// VALUES (
//     'admin', 
//     '$2y$10$XvQKWFYRe28NJ37vqdj0ju9GjLA2sVlwOR6LqIQZNWlsW0sEs33ii',
//     'admin@citytech.org', 
//     'City', 
//     'Tech', 
//     '+2348052882239', 
//     'admin'
// );

// CityTechAdmin2024@$