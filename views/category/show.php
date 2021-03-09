<?php

use App\Connection;
use App\PaginatedQuery;
use App\Table\CategoryTable;
use App\Table\PostTable;
use App\Models\{Category, Post};
use App\URL;

$id = (int)$params['id'];
$slug = $params['slug'];


$pdo = Connection::getPdo();

$category = (new CategoryTable($pdo))->find($id);


if ($slug !== $category->getSlug()){
    $url = $router->url('category', ['slug' => $category->getSlug(), 'id' => $category->getId()]);
    http_response_code(301);
    header('location:' . $url);
}
$title = "Catégorie " . $category->getName();

[$posts, $paginatedQuery] = (new PostTable($pdo))->findPaginatedForCategory($category->getId());


$link = $router->url('category', ['id' => $category->getId(), 'slug'=> $category->getSlug()]);

?>

<h1><?= htmlentities($title) ?></h1>


<div class="row">
    <?php foreach($posts as $post): ?>
        <div class="col-md-3">
            <?php require dirname(__DIR__) . DIRECTORY_SEPARATOR .'post/card.php' ?>
        </div>
    <?php endforeach; ?>
</div>

<div class="d-flex justify-content-between my-4">
    <?= $paginatedQuery->previousPageLink($link) ?>
    <?= $paginatedQuery->nextPageLink($link) ?>
</div>