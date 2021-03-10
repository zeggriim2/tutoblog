<?php
$title = "Administration";

use App\Auth;
use App\Connection;
use App\Table\PostTable;

Auth::check();

$pdo = Connection::getPdo();
[$posts, $paginatedQuery] = (new PostTable($pdo))->findPaginated();

$link = $router->url('admin_posts');

?>
<?php if (isset($_GET['delete'])): ?>
<div class="alert alert-success">
    L'enregistrement a bien été supprimé
</div>
<?php endif; ?>

<table class="table">
    <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Titre</th>
            <th scope="col">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($posts as $post): ?>
            <tr>
                <td scope="col"><?= $post->getId()?>
                </td>
                <td>
                    <a href="<?= $router->url('admin_post',['id' => $post->getId()]) ?>">
                        <?= htmlentities($post->getName())?>
                    </a>
                </td>
                <td>
                    <a href="<?= $router->url('admin_post',['id' => $post->getId()]) ?>" class="btn btn-secondary">
                        Editer
                    </a>
                    <form action="<?= $router->url('admin_post_delete',['id' => $post->getId()]) ?>" method="post" style="display: inline"  onsubmit="return confirm('Voulez vous vraiment effectuer cette action ?')">
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>


<div class="d-flex justify-content-between my-4">
    <?= $paginatedQuery->previousPageLink($link)?>
    <?= $paginatedQuery->nextPageLink($link)?>
</div>
