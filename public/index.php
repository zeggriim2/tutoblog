<?php

use App\Router;

require "../vendor/autoload.php";

//define('VIEW_PATH', dirname(__DIR__) . "/views");
//define('DEBUG_TIME', microtime(true));

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();


$router = new Router(dirname(__DIR__) . "/views");
$router->get('/blog', 'post/index','blog');
$router->get('/blog/category', 'category/show', 'category');
$router->run();

?>
