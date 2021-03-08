<?php

use App\Connection;
use App\table\PostTable;

$title = 'blog';
$pdo = Connection::getPdo();

$table = new PostTable($pdo);
[$posts, $paginatedQuery] = $table->findPaginated();

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