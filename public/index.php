<?php
require "../vendor/autoload.php";

define('VIEW_PATH', dirname(__DIR__) . "/views");
$router = new Router();

$router = new AltoRouter();

$router->get($)
$router->map("GET", "/blog", function () {
    require VIEW_PATH . "/post/index.php";
});

$router->map("GET", "/blog/categorie", function () {
    require VIEW_PATH . "/categorie/show.php";
});

$match = $router->match();
$match['target']();
?>
