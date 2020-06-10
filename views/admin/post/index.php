<?php

use App\Connection;

use App\Table\PostTable;

use App\Auth;


//appel li class check
Auth::check();
//router pour afficher articles mil fou9
$router->layout = "admin/layouts/default";
$title = 'Administration';

// cette ligne pour la connection
$pdo = Connection::getPDO();
// router
$link = $router->url('admin_posts');
//lister les articles
[$posts, $pagination] = (new PostTable($pdo))->findPaginated();

?>

<?php if(isset($_GET['delete'])): ?>
<div class="alert alert-success">
    L'enregistrement a bien eté supprimé
</div>

<?php endif ?>


<table class="table table">
<thead>
         <th>Id</th>
         <th>Titre</th>
         <th>
             <a href="<?= $router->url('admin_posts_new') ?>" class="btn btn-primary">Nouveau</a>
         </th>
         <th>Actions</th>
</thead>

<tbody>
<?php foreach ($posts as $post): ?>
<tr>
    <td>#<?= $post->getID() ?></td>
    <td>
        <a href="<?= $router->url('admin_post',['id' => $post->getID()]) ?>">

        <?= htmlentities($post->getName()) ?>
        </a>
    </td>
    <td>
        <a href="<?= $router->url('admin_post',['id' => $post->getID()]) ?>" class="btn btn-primary">

          Editer
        </a>

    </td>

    <td>
        <form action="<?= $router->url('admin_post_delete',['id' => $post->getID()]) ?>" method="POST"
        onsubmit="return confirm('Voulez vous vraiment effectuer cette action ?')" style="display:inline "                                                          >
            <button type="submit" class="btn btn-danger">Supprimer</button>
        </form>

    </td>


</tr>
<?php endforeach ?>


</tbody>

</table>


<div class="d-flex justify-content-between my-4">
    <?= $pagination->perviousLink($link) ?>
    <?= $pagination->nextLink($link) ?>

</div>
