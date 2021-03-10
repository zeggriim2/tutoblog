<?php

use App\Router;

require "../vendor/autoload.php";

//define('VIEW_PATH', dirname(__DIR__) . "/views");
define('DEBUG_TIME', microtime(true));

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();

if (isset($_GET['page']) && $_GET['page'] === '1'){
    // réécrite l'url sans le parametre ?page
    $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
    $get = $_GET;
    unset($get['page']);
    $paramGet = http_build_query($get);
    if(!empty($paramGet)){
        $uri = $uri . "?" . $paramGet;
    }
    http_response_code(301);
    header('Location:' . $uri);
    exit;
}

$router = new Router(dirname(__DIR__) . "/views");
$router->get('/', 'post/index','home')
        ->get('/blog/category/[*:slug]-[i:id]', 'category/show', 'category')
        ->get('/blog/[*:slug]-[i:id]','post/show', 'post')
        ->get('/admin', 'admin/post/index', 'admin_posts')
        ->match('/admin/post/[i:id]', 'admin/post/edit', 'admin_post')
        ->post('/admin/post/[i:id]/delete', 'admin/post/delete', 'admin_post_delete')
        ->get('/admin/post/new', 'admin/post/new', 'admin_post_new')
        ->run();

?>
