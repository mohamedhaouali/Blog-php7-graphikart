<?php

use App\Connection;
use App\Model\Category;
use App\PaginatedQuery;
use App\Model\Post;
use App\Table\CategoryTable;
use App\Table\PostTable;
use App\URL;

$id = (int)$params['id'];
$slug = $params['slug'];

$pdo = Connection::getPDO();
// class CategoryTable
// zidna cette ligne
$category = (new CategoryTable($pdo))->find($id);



if($category->getSlug() !== $slug) {
$url = $router->url('post', ['slug' => $category->getSlug(), 'id'=>$id]);
http_response_code(301);
header('Location: ' . $url);
}

/**
 * Parametre varie:
 * $pdo:PDO = Connection::getPDO()
 * $sqllisting: string
 * $classMapping: string
 * $sqlCount: string
 * $perPage: int = 12
 *
 * Parmetre externes;
 * $currentPage
 *
 * Methodes:
 * getItems(): array
 * previousPageLink(): ?string
 * nextPageLink(): ?string
 */
// zidna ces lignes

[$posts,$paginatedQuery] =(new PostTable($pdo))->findPaginatedForCategory($category->getID());



$link = $router->url('category',['id' =>$category->getID(),'slug' => $category->getSlug()]);

?>

<h1>Categorie<?= htmlentities(($category->getName())) ?></h1>


<div class="row">
    <?php foreach ($posts as $post): ?>
        <div class="col-md-3">
            <?php require dirname(__DIR__) .  '/posts/card.php' ?>
        </div>
    <?php endforeach ?>
</div>

<div class="d-flex justify-content-between my-4">

<?= $paginatedQuery->perviousLink($link) ?>
<?= $paginatedQuery->nextLink($link) ?>


</div>

