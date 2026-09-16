<?php

return [
    '/' => 'app/controllers/controllers.index.php',
    '/index' => 'app/controllers/controllers.index.php',
    '/about' => 'app/controllers/controllers.about.php',
    '/contact' => 'app/controllers/controllers.contact.php',
    '/contactus' => 'app/controllers/controllers.contact.php',
    '/login' => 'app/controllers/controllers.login.php',
    '/additem' => 'app/controllers/controllers.addproduct.php',
    '/adduser' => 'app/controllers/controllers.adduser.php',
    '/storesection' => 'app/controllers/controllers.storesections.php',
    '/addsales' => 'app/controllers/controllers.addsales.php',
    '/listproducts' => 'app/controllers/controllers.listproduct.php',
    '/products' => 'app/controllers/controllers.listproduct.php',
    '/listsales' => 'app/controllers/controllers.listsales.php',
    '/list' => 'app/controllers/controllers.list.php',
    '/manage' => 'app/controllers/controllers.manage.php',
    
    '/admin' => 'app/controllers/controllers.admin.php',
    '/dashboard' => 'app/controllers/controllers.dashboard.php',
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