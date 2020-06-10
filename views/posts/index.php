<?php

use App\Connection;
use App\Table\PostTable;


$title = 'Mon Blog';

// cette ligne pour la connection
$pdo = Connection::getPDO();

// class PostTable
$table = new PostTable($pdo);
// find Paginated is function du class PostTable
[$posts, $pagination] = $table->findPaginated();

$link = $router->url('home');


?>

<h1>Mon Blog</h1>

<div class="row">
    <?php foreach ($posts as $post): ?>
        <div class="col-md-3">
            <?php require 'card.php' ?>
        </div>
    <?php endforeach ?>
</div>

<div class="d-flex justify-content-between my-4">
    <?= $pagination->perviousLink($link) ?>
    <?= $pagination->nextLink($link) ?>

</div>
