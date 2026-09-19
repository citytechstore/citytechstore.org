<?php

return [
    '/' => 'app/controllers/controllers.index.php',
    '/index' => 'app/controllers/controllers.index.php',
    '/about' => 'app/controllers/controllers.about.php',
    '/contact' => 'app/controllers/controllers.contact.php',
    '/contactus' => 'app/controllers/controllers.contact.php',
    '/login' => 'app/controllers/controllers.login.php',
    '/register' => 'app/controllers/controllers.customer_register.php',
    '/customer-login' => 'app/controllers/controllers.customer_login.php',
    '/logout-customer' => 'app/controllers/controllers.logout_customer.php',
    '/my-orders' => 'app/controllers/controllers.customer_orders.php',
    '/auth/google/start' => 'app/controllers/controllers.google_auth_start.php',
    '/auth/google/callback' => 'app/controllers/controllers.google_auth_callback.php',
    '/shop' => 'app/controllers/controllers.shop.php',
    '/product' => 'app/controllers/controllers.product.php',
    '/brands' => 'app/controllers/controllers.brands.php',
    '/visit-our-store' => 'app/controllers/controllers.visit_our_store.php',
    '/cart/add' => 'app/controllers/controllers.cart_add.php',
    '/cart' => 'app/controllers/controllers.cart_view.php',
    '/cart/update' => 'app/controllers/controllers.cart_update.php',
    '/cart/remove' => 'app/controllers/controllers.cart_remove.php',
    '/product-image/remove' => 'app/controllers/controllers.product_image_remove.php',
    '/checkout' => 'app/controllers/controllers.checkout.php',
    '/checkout/process' => 'app/controllers/controllers.checkout_process.php',
    '/checkout/callback' => 'app/controllers/controllers.checkout_callback.php',
    '/additem' => 'app/controllers/controllers.addproduct.php',
    '/storesection' => 'app/controllers/controllers.storesections.php',
    '/addsales' => 'app/controllers/controllers.addsales.php',
    '/listproducts' => 'app/controllers/controllers.listproduct.php',
    '/products' => 'app/controllers/controllers.listproduct.php',
    '/listsales' => 'app/controllers/controllers.listsales.php',
    '/list' => 'app/controllers/controllers.list.php',
    '/manage' => 'app/controllers/controllers.manage.php',
    '/customers' => 'app/controllers/controllers.customers.php',

    '/admin' => 'app/controllers/controllers.admin.php',
    '/dashboard' => 'app/controllers/controllers.dashboard.php',
    '/dashboard/revenue-chart' => 'app/controllers/controllers.dashboard_revenue_chart.php',
    '/stocks' => 'app/controllers/controllers.stocks.php',
    '/howto' => 'app/controllers/controllers.howto.php',
    '/tutorials' => 'app/controllers/controllers.tutorials.php',

];

// $files =  [
//     // '/' => 'app/controllers/controllers.index.php',
//     // '/index' => 'app/controllers/controllers.index.php',
//     '/about' => '../controllers/controllers.about.php',
//     '/contact' => '../controllers/controllers.contact.php',
//     '/login' => '../controllers/controllers.login.php',
//     '/addproduct' => '../controllers/controllers.addproduct.php',
//     '/listproducts' => '../controllers/controllers.listproduct.php',
//     '/addsales' => '../controllers/controllers.addsales.php',
//     '/listsales' => '../controllers/controllers.listsales.php',
//     '/admin' => '../controllers/controllers.admin.php',
//     '/dashboard' => '../controllers/controllers.dashboard.php',
// ];


// foreach($files as $filename => $filepath){
//     list($slash, $strippedfilename) = explode('/', $filename);
//     // echo $strippedfilename;
//     // echo $filepath;
//     file_put_contents($filepath, '<?php require "app/views/views.'.$strippedfilename.'.php";');
//     // break;
// }