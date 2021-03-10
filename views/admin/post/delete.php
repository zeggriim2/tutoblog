<?php

use App\Auth;
use App\Connection;
use App\Table\PostTable;

Auth::check();

$id = $params['id'];

$pdo = Connection::getPdo();
$table = new PostTable($pdo);
//$table->delete($id);

header('Location:'.$router->url('admin_posts') . '?delete=1');

?>