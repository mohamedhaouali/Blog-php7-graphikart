<form action="" method="POST">
    <?= $form->input('name','Titre'); ?>
    <?= $form->input('slug','URL'); ?>
    <?= $form->select('categories_ids','Catégories', $categories); ?>
    <?= $form->textarea('content','Contenu'); ?>
    <?= $form->input('created_at','Date du création'); ?>



    <button class="btn btn-primary">
    <?php if ($post->getID() !== null) : ?>
    Modifier
    <?php else: ?>
    Creer
    <?php endif ?></button>

</form>
