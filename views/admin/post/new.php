<?php
use App\Connection;
use App\Table\PostTable;
use App\Table\CategoryTable;
use App\HTML\Form;
use App\Validators\PostValidator;
use App\Model\Post;
use App\ObjectHelper;
use App\Auth;

//appel li class check
Auth::check();

//pour les erreurs
$errors = [];
$pdo = Connection::getPDO();
//on ajoute cette ligne pour ajouter new Post
$post = new Post();

//categoryTable
$categoryTable = new CategoryTable($pdo);
//method list
$categories = $categoryTable->list();
$post->setCreatedAt(date('Y-m-d H:i:s'));

if (!empty($_POST)) {
    $pdo = Connection::getPDO();
    $postTable = new PostTable($pdo);
    //langue francaise
    //Validator::lang('fr');
    //new Validator pour table PostValidator
    // pour video validation $postTable
    $v = new PostValidator($_POST, $postTable, $post->getID(), $categories);
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
        $postTable->createPost($post);

        // video 62 (attachCategories min PostTable)
        $postTable->attachCategories($post->getID(),$_POST['categories_ids']);
        //video62
        $pdo->commit();
        // redirection vers page edit
        header('Location: ' . $router->url('admin_post', ['id' => $post->getID()]) . '?created=1');
        exit();
    }else {
        $errors = $v->errors();
    }
}
// pour formulaire
$form = new Form($post,$errors);

?>

<?php if ($errors): ?>
    <div class="alert alert-danger">
        L'article n'a pas pu etre enregistre,merci de corriger vos erreurs
    </div>

<?php endif ?>



<h1>Creer l'article  </h1>

<?php require('_form.php') ?>
