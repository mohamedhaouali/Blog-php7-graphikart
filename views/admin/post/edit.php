<?php
use App\Connection;
use App\Table\PostTable;
use App\Table\CategoryTable;
use App\HTML\Form;
use App\Validators\PostValidator;
use App\ObjectHelper;
use App\Auth;

//appel li class check
Auth::check();


$pdo = Connection::getPDO();
$postTable = new PostTable($pdo);
//categoryTable
$categoryTable = new CategoryTable($pdo);
//method list
$categories = $categoryTable->list();

$post = $postTable->find($params['id']);
//on ajoute cette ligne video 62
$categoryTable->hydratePosts([$post]);
$success = false;
//pour les erreurs
$errors = [];

if (!empty($_POST)) {
  //langue francaise
 // Validator::lang('fr');
  //new Validator pour table PostValidator
    // pour video validation $postTable
    $v = new PostValidator($_POST, $postTable, $post->getID(),  $categories);
    // appel il class ObjectHelper
    App\ObjectHelper::hydrate($post,$_POST, ['name', 'content','slug','created_at']);
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
        // video 62
    $pdo->beginTransaction();
    $postTable->updatePost($post);

    // video 62 (attachCategories min PostTable)
      $postTable->attachCategories($post->getID(),$_POST['categories_ids']);
    //video62
    $pdo->commit();
    $categoryTable->hydratePosts([$post]);
    $success = true;
    }else {
      $errors = $v->errors();
    }
}
// pour formulaire
$form = new Form($post,$errors);

?>

<?php if ($errors): ?>
    <div class="alert alert-danger">
        L'article n'a pas pu etre modifié, merci de corriger vos erreurs
    </div>

<?php endif ?>

<?php if ($success): ?>
<div class="alert alert-success">
    L'article a bien eté modifie
</div>

<?php endif ?>

<?php if (isset($_GET['created'])): ?>
    <div class="alert alert-success">
        L'article a bien eté creer
    </div>

<?php endif ?>

<h1>Editer l'article <?= htmlentities($post->getName()) ?></h1>


<?php require('_form.php') ?>
