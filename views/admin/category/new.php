<?php
use App\Connection;
use App\Table\CategoryTable;
use App\HTML\Form;
use App\Validators\CategoryValidator;
use App\Model\Category;
use App\ObjectHelper;
use App\Auth;

//appel li class check
Auth::check();


//pour les erreurs
$errors = [];
//on ajoute cette ligne pour ajouter new Post
$item = new Category();


if (!empty($_POST)) {
    $pdo = Connection::getPDO();
    // table au lieu du categorytable
    $table = new CategoryTable($pdo);
    //langue francaise
    //Validator::lang('fr');
    //new Validator pour table PostValidator
    // pour video validation $postTable
    $v = new CategoryValidator($_POST, $table);
    //remplir nos informations
   ObjectHelper::hydrate($item,$_POST, ['name', 'slug']);
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
        $table->create([
            'name' => $item->getName(),
            'slug' => $item->getSlug()
        ]);
        // redirection vers page edit
        header('Location: ' . $router->url('admin_categories') . '?created=1');
        exit();
    }else {
        $errors = $v->errors();
    }
}
// pour formulaire
$form = new Form($item,$errors);

?>

<?php if ($errors): ?>
    <div class="alert alert-danger">
        La categorie n'a pas pu etre enregistre,merci de corriger vos erreurs
    </div>

<?php endif ?>



<h1>Creer une categorie  </h1>

<?php require('_form.php') ?>
