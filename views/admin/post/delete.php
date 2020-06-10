<?php
use App\Connection;
use App\Table\PostTable;
use App\Auth;

//appel li class check
Auth::check();

$pdo = Connection::getPDO();
$table = new PostTable($pdo);

$table->delete($params['id']);
header('Location:' . $router->url('admin_posts') . '?delete=1');
