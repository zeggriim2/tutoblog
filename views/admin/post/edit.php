<?php

use App\Connection;
use App\Table\PostTable;
use App\Validator;
use App\HTML\Form;

$pdo = Connection::getPdo();
$postTable = new PostTable($pdo);
/** @var \App\Models\Post $post */
$post = $postTable->find($params['id']);
$success = false;

$errors = [];
if (!empty($_POST)){
    Validator::lang('fr');
    $v = new Validator($_POST);
    $v->rule('required', ['name', 'slug']);
    $v->rule('lengthBetween', ['name','slug'], 3, 200);
    $post
        ->setName($_POST['name'])
        ->setSlug($_POST['slug'])
        ->setContent($_POST['content'])
        ->setCreatedAt($_POST['created_at']);

    if ($v->validate()){
        $postTable->update($post);
        $success = true;
    }else{
        $errors = $v->errors();
    }
}

$form = new Form($post, $errors);
?>
<h1>Editer l'article : '<?= htmlentities($post->getName()); ?>'</h1>

<?php if($success): ?>
    <div class="alert alert-success">
        L'article à été mis à jour.
    </div>
<?php endif;?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        L'article n'a pas pu être modifier, merci de corr été mis à jour.
    </div>
<?php endif; ?>
<form action="" method="post">
    <?= $form->input('name', 'Titre'); ?>
    <?= $form->input('slug', 'URL'); ?>
    <?= $form->textarea('content', 'Contenu'); ?>
    <?= $form->input('created_at', 'Date de publication')?>
    <button type="submit" class="btn btn-primary">Modifier</button>
</form>
