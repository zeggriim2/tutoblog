<?php

use App\Connection;
use App\Table\PostTable;
use App\Validator;

$pdo = Connection::getPdo();
$postTable = new PostTable($pdo);
/** @var \App\Models\Post $post */
$post = $postTable->find($params['id']);
$success = false;

$errors = [];

if (!empty($_POST)){
    Validator::lang('fr');
    $v = new Validator($_POST);

    $v->rule('required', 'name');
    $v->rule('lengthBetween', 'name', 3, 200);
    if ($v->validate()){
        $errors['name'][] = 'Le champ titre ne peux pas être vide';
    }else{
        $errors = $v->errors();
    }
    $post
        ->setName($_POST['name']);
    if(empty($errors)){
        $postTable->update($post);
        $success = true;
    }
}

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
    <div class="form-group">
        <label for="name">Titre</label>
        <input type="text" name="name" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" id="exampleInputName" value="<?= htmlentities($post->getName()); ?>">
        <?php if (isset($errors['name'])): ?>
            <div class="invalid-feedback">
                <?= implode('<br>', $errors['name']); ?>
            </div>
        <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-primary">Modifier</button>
</form>
