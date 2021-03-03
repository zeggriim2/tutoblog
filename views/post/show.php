<?php

use App\Connection;
use App\Models\{Post, Category};


$id = (int)$params['id'];
$slug = $params['slug'];


$pdo = Connection::getPdo();
$query = $pdo->prepare("SELECT * FROM post WHERE id = :id");
$query->execute(['id' => $id]);
$query->setFetchMode(PDO::FETCH_CLASS, Post::class);
/** @var Post|false $post */
$post = $query->fetch();

if ($post === false){
    throw new Exception("Aucun article ne correspond à cette id = $id");
}

if ($slug !== $post->getSlug()){
    $url = $router->url('post', ['slug' => $post->getSlug(), 'id' => $post->getId()]);
    http_response_code(301);
    header('location:' . $url);
}

$query =  $pdo->prepare("
SELECT c.id, c.name, c.slug
FROM post_category pc 
JOIN category c ON pc.category_id = c.id
WHERE pc.post_id = :id");
$query->execute(['id' => $post->getId()]);
/** @var  Category[] */
$categories = $query->fetchAll(PDO::FETCH_CLASS, Category::class);

?>

<h1><?= htmlentities($post->getName()) ?></h1>
<p class="text-muted"><?= $post->getCreatedAt()->format('d F Y') ?></p>
<?php foreach ($categories as $key => $category):?>
    <?php if ($key > 0): ?>
        ,
    <?php endif; ?>
    <a href="<?= $router->url('category', ['id' => $category->getId(), 'slug' => $category->getSlug()]) ?>"><?= htmlentities($category->getName()) ?></a>
<?php endforeach;?>
<p><?= $post->getFormattedContent() ?></p>
