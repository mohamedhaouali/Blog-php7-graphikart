<?php

use App\Connection;

use App\Table\CategoryTable;

use App\Auth;


//appel li class check
Auth::check();
//router pour afficher articles mil fou9
$router->layout = "admin/layouts/default";
$title = 'Gestion des categories';

// cette ligne pour la connection
$pdo = Connection::getPDO();
// router
$link = $router->url('admin_categories');
//item remplace table category w all function fi table
$items = (new CategoryTable($pdo))->all();

?>

<?php if(isset($_GET['delete'])): ?>
<div class="alert alert-success">
    L'enregistrement a bien eté supprimé
</div>

<?php endif ?>


<table class="table table">
<thead>
         <th>#</th>
         <th>Titre</th>
         <th>URL</th>
         <th>
             <a href="<?= $router->url('admin_category_new') ?>" class="btn btn-primary">Nouveau</a>
         </th>
         <th>Actions</th>
</thead>

<tbody>
<?php foreach ($items as $item): ?>
<tr>
    <td>#<?= $item->getID() ?></td>
    <td>
        <a href="<?= $router->url('admin_category',['id' => $item->getID()]) ?>">

        <?= htmlentities($item->getName()) ?>
        </a>
    </td>
    <td>#<?= $item->getSlug() ?></td>
    <td>
        <a href="<?= $router->url('admin_category',['id' => $item->getID()]) ?>" class="btn btn-primary">

          Editer
        </a>

    </td>

    <td>
        <form action="<?= $router->url('admin_category_delete',['id' => $item->getID()]) ?>" method="POST"
        onsubmit="return confirm('Voulez vous vraiment effectuer cette action ?')" style="display:inline "                                                          >
            <button type="submit" class="btn btn-danger">Supprimer</button>
        </form>

    </td>


</tr>
<?php endforeach ?>


</tbody>

</table>

