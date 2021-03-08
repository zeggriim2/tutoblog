<?php

use App\Connection;
use App\table\PostTable;
use App\Models\{Post, Category};


$id = (int)$params['id'];
$slug = $params['slug'];


$pdo = Connection::getPdo();
$post = (new PostTable($pdo))->find($id);
(new \App\Table\CategoryTable($pdo))->hydratePosts([$post]);

if ($slug !== $post->getSlug()){
    $url = $router->url('post', ['slug' => $post->getSlug(), 'id' => $post->getId()]);
    http_response_code(301);
    header('location:' . $url);
}

?>

<h1><?= htmlentities($post->getName()) ?></h1>
<p class="text-muted"><?= $post->getCreatedAt()->format('d F Y') ?></p>
<?php foreach ($post->getCategories() as $key => $category):?>
    <?php if ($key > 0): ?>
        ,
    <?php endif; ?>
    <a href="<?= $router->url('category', ['id' => $category->getId(), 'slug' => $category->getSlug()]) ?>"><?= htmlentities($category->getName()) ?></a>
<?php endforeach;?>
<p><?= $post->getFormattedContent() ?></p>
