<?php

use App\Connection;
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


$currentPage = URL::getPositiveInt('page',1);

$count = (int)$pdo
            ->query("SELECT COUNT(category_id) FROM post_category WHERE category_id = " . $category->getId())
            ->fetch(PDO::FETCH_NUM)[0];
$perPage = 12;
$pages = ceil($count / $perPage);
if($currentPage > $pages){
    throw new Exception('Cette page n\'existe pas.');
}

$offset = $perPage * ($currentPage - 1);
$query = $pdo->query("
        SELECT p.* 
        FROM post p
        JOIN post_category pc ON p.id = pc.post_id
        WHERE pc.category_id = {$category->getId()}
        ORDER BY created_at 
        DESC LIMIT $perPage OFFSET $offset        
");
$posts = $query->fetchAll(PDO::FETCH_CLASS, Post::class);
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
    <?php if($currentPage > 1): ?>
        <?php
        $l = $link;
        if($currentPage > 2) $l .= '?page=' . ($currentPage - 1);
        ?>
        <a href="<?= $l ?>" class="btn btn-primary">&laquo; Page Précédente</a>
    <?php endif ?>
    <?php if($currentPage < $pages): ?>
        <a href="<?= $link ?>?page=<?= $currentPage + 1 ?>" class="btn btn-primary ml-auto">Page Suivante &raquo;</a>
    <?php endif ?>
</div>