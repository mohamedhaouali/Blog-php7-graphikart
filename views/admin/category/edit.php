<?php
use App\Connection;
use App\Table\CategoryTable;
use App\HTML\Form;
use App\Validators\CategoryValidator;
use App\ObjectHelper;
use App\Auth;



//appel li class check
Auth::check();


$pdo = Connection::getPDO();
$table = new CategoryTable($pdo);
$item = $table->find($params['id']);
$success = false;
//pour les erreurs
$errors = [];
$fields = ['name','slug'];

if (!empty($_POST)) {
    // table mil $table = new CategoryTable($pdo);
    $v = new CategoryValidator($_POST, $table, $item->getID());
    // appel il class ObjectHelper


   ObjectHelper::hydrate($item,$_POST,$fields);
    /*
   // validation
  $v = new Validator($_POST);
  $v->rule('required', ['name', 'slug']);
  $v->rule('lengthBetween', ['name','slug'], 3 , 200);

    // appel mil Model Post
    // si tu va bien passe
    // forumlaire
    $post
        ->setName($_POST['name'])
        ->setContent($_POST['content'])
        ->setSlug($_POST['slug'])
        ->setCreatedAt($_POST['created_at']);

*/
    if ($v->validate()) {
    $table->update([
         'name' => $item->getName(),
         'slug' => $item->getSlug()
    ], $item->getID());
    $success = true;
    }else {
      $errors = $v->errors();
    }
}
// pour formulaire
$form = new Form($item,$errors);

?>

<?php if ($errors): ?>
    <div class="alert alert-danger">
        L'article n'a pas pu etre modifié, merci de corriger vos erreurs
    </div>

<?php endif ?>

<?php if ($success): ?>
<div class="alert alert-success">
    La categorie a bien eté modifie
</div>

<?php endif ?>

<?php if (isset($_GET['created'])): ?>
    <div class="alert alert-success">
        La categorie a bien eté creer
    </div>

<?php endif ?>

<h1>Editer la categorie <?= htmlentities($item->getName()) ?></h1>


<?php require('_form.php') ?>
