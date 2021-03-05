<?php

use App\Connection;
use App\Helpers\Text;
use App\Models\Category;
use App\Models\Post;
use App\PaginatedQuery;
use App\URL;

$title = 'blog';
$pdo = Connection::getPdo();

$paginatedQuery = new PaginatedQuery(
        "SELECT * FROM post ORDER BY created_at DESC",
    "SELECT COUNT(id) FROM post "
);

$posts = $paginatedQuery->getItems(Post::class);
$postsById = [];
foreach ($posts as $post){
    $postsById[$post->getId()] = $post;
}
$ids = array_keys($postsById);

$categories = $pdo
                ->query("SELECT c.*, pc.post_id
                    FROM post_category pc
                    JOIN category c ON pc.category_id = c.id
                    WHERE pc.post_id IN (" . implode(' ,', $ids) .")
                ")->fetchAll(PDO::FETCH_CLASS, Category::class);

// On parcourt les catégories
foreach ($categories as $category){
    /** @var $category Category */
    $postsById[$category->getPostId()]->addCategorie($category);
}

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