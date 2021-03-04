<?php

use App\Connection;
use App\PaginatedQuery;
use App\Models\{Category, Post};
use App\URL;

$id = (int)$params['id'];
$slug = $params['slug'];


$pdo = Connection::getPdo();
$query = $pdo->prepare("SELECT * FROM category WHERE id = :id");
$query->execute(['id' => $id]);
$query->setFetchMode(PDO::FETCH_CLASS, Category::class);
/** @var Category|false $post */
$category = $query->fetch();

if ($category === false){
    throw new Exception("Aucune categorie ne correspond à cette id = $id");
}

if ($slug !== $category->getSlug()){
    $url = $router->url('category', ['slug' => $category->getSlug(), 'id' => $category->getId()]);
    http_response_code(301);
    header('location:' . $url);
}
$title = "Catégorie " . $category->getName();


$paginatedQuery = new PaginatedQuery(
        "SELECT p.* 
                FROM post p
                JOIN post_category pc ON p.id = pc.post_id
                WHERE pc.category_id = {$category->getId()}
        ORDER BY created_at ",
    "SELECT COUNT(category_id) FROM post_category WHERE category_id = {$category->getId()}"
);

/** @var Page $posts */
$posts = $paginatedQuery->getItems(Post::class);


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