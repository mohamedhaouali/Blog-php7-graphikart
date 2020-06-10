<?php
require '../vendor/autoload.php';

// temps d'execution
define('DEBUG_TIME' , microtime(true));

$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();
// redirection
if(isset($_GET['page']) && $_GET['page'] === '1') {
    // reecrire l'url sans le parametre ?page
    $uri = explode('?', $_SERVER['REQUEST_URI'])[0];
    $get = $_GET;
    unset($get['page']);
    $query= http_build_query($get);
    if (!empty($query)){
       $uri = $uri . '?' .$query;
    }
    http_response_code(301);
    header('location: ' .$uri);
    exit();

}




$router = new App\Router(dirname(__DIR__) . '/views');
$router
    ->get('/', 'posts/index', 'home')
    ->get('/blog/category/[*:slug]-[i:id]', 'category/show', 'category')
    ->get('/blog/[*:slug]-[i:id]','posts/show', 'post')
    // ADMIN
    // Gestion des articles

    // route pour afficher
    ->get('/admin','admin/post/index', 'admin_posts')
    // route pour modifier
    ->match('/admin/post/[i:id]','admin/post/edit', 'admin_post')
    // route pour ajout
    ->match('/admin/post/new', 'admin/post/new', 'admin_posts_new')
    // route pour delete
    ->post('/admin/post/[i:id]/delete','admin/post/delete', 'admin_post_delete')

    // Gestion des categories

    // route pour afficher
    ->get('/admin/categories','admin/category/index', 'admin_categories')
    // route pour modifier
    ->match('/admin/categories/[i:id]','admin/category/edit', 'admin_category')
    // route pour ajout
    ->match('/admin/categories/new', 'admin/category/new', 'admin_category_new')
    // route pour delete
    ->post('/admin/categories/[i:id]/delete','admin/category/delete', 'admin_category_delete')

    ->run();




