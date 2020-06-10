<?php
use App\Connection;
use App\Model\Post;
use App\Model\Category;
use App\Table\PostTable;
use App\Table\CategoryTable;

$id = (int)$params['id'];
$slug = $params['slug'];

//zidna ces 3 lignes
$pdo = Connection::getPDO();
$post = (new PostTable($pdo))->find($id);
(new CategoryTable($pdo))->hydratePosts([$post]);

if($post->getSlug() !== $slug) {
    $url = $router->url('post', ['slug' => $post->getSlug(), 'id'=>$id]);
    http_response_code(301);
    header('Location: ' . $url);
}

?>

<h5 class="card-title"><?= htmlentities($post->getName()) ?></h5>
<p class="text-muted"><?= $post->getCreatedAt()->format('d F Y') ?></p>

<?php foreach ($post->getCategories() as $k => $category): ?>
<?php if ($k >0):
echo ', ';
endif
?>
<a href="<?= $router->url('category',['id' => $category->getID(), 'slug' => $category->getSlug()]) ?>">
<?= htmlentities($category->getName()) ?></a>
<?php endforeach; ?>



<p><?= $post->getFormattedContent() ?></p>


