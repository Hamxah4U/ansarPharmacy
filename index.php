<?php
session_start();
$uri = parse_url($_SERVER['REQUEST_URI'])['path'];

if(!isset($_SESSION['userID']) && $uri != '/' && $uri != '/login' && $uri != '/resetpassword' && $uri != '/currentcapital') {
  header('Location: /');
  exit();
}

$routes = [
  '/' => 'controllers/index.php',
  '/users' => 'controllers/users.php',
  '/unit' => 'controllers/unit.php',
  '/product' => 'controllers/product.php',
  '/report' => 'controllers/report.php',
  '/dashboard' => 'controllers/dashboard.php',
  '/logout' => 'controllers/logout.php',
  '/billing' => 'controllers/billing.php',
  '/supply' => 'controllers/supply.php',
  '/changepassword' => 'controllers/changepassword.php',
  '/updateprofile' => 'controllers/updateprofile.php',
  '/finance' => 'controllers/user.finance.php',
  '/nhisbilling' => 'controllers/nhis.php',
  '/inventoryreport' => 'controllers/inventoryreport.php',
  '/reportsummery' => 'controllers/reportsummery.php',
  '/creditbilling' => 'controllers/creditbilling.php',
  '/viewcreditors' => 'controllers/viewcreditors.php',
  '/paycredit' => 'controllers/paycredit.php',
  '/sellerreportsummery' => 'controllers/sellerreportsummery.php',
  '/resetpassword' => 'controllers/resetpassword.php',
  '/servicebilling' => 'controllers/servicebilling.php',
  '/currentcapital' => 'controllers/currentcapital.php',
];

if(array_key_exists($uri, $routes)) {
  require $routes[$uri];
}else{
  require 'controllers/404.php';
}