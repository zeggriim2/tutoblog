<?php

use App\Connection;
use App\Helpers\Text;
use App\Models\Post;
use App\PaginatedQuery;
use App\URL;

$title = 'blog';
$pdo = Connection::getPdo();

$paginatedQuery = new PaginatedQuery(
        "SELECT * 
                FROM post 
                ORDER BY created_at DESC",
    "SELECT COUNT(id) 
                FROM post "
);



$posts = $paginatedQuery->getItems(Post::class);

$link = $router->url('home');
?>
<h1>Mon blog</h1>

<div class="row">
    <?php foreach($posts as $post): ?>
        <div class="col-md-3">
            <?php require 'card.php' ?>
        </div>
    <?php endforeach; ?>
</div>

<div class="d-flex justify-content-between my-4">
    <?= $paginatedQuery->previousPageLink($link)?>
    <?= $paginatedQuery->nextPageLink($link)?>

</div>