
<?php
require_once('app/models/Router.php');

$routerModel = new Router($_SERVER['REQUEST_URI']);
$routerModel->route();

?>
