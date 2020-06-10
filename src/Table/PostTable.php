<?php
namespace App\Table;

use App\PaginatedQuery;
use App\Model\Post;
use App\Model\Category;
use App\Table\CategoryTable;
use App\Table\Exception\NotFoundException;
use \PDO;


final class PostTable extends Table
{
    // HERITER mil class Table
    protected $table ="post";
    protected $class = Post::class;

    public function updatePost (Post $post): void
    {
        //{$this->table} pour table post
        // begin transaction securite en terme d'execution

       $this->update([
            'name' => $post->getName(),
            'slug' => $post->getSlug(),
            'content' => $post->getContent(),
            'created_at' => $post->getCreatedAt()->format('Y-m-d H:i:s')
        ], $post->getID());

    }

    public function createPost (Post $post): void
    {
        //{$this->table} pour table post

     $id = $this->create([
            'name' => $post->getName(),
            'slug' => $post->getSlug(),
            'content' => $post->getContent(),
            'created_at' => $post->getCreatedAt()->format('Y-m-d H:i:s')
        ]);

        // dernier enregistrement qui a ete fait
        $post->setID($id);

    }

public function attachCategories(int $id,array $categories){

    $this->pdo->exec('DELETE FROM post_category WHERE post_id = ' . $id);
    $query=  $this->pdo->prepare('INSERT INTO post_category SET post_id = ?,category_id = ?');
    foreach ($categories as $category){
        $query->execute([$id, $category]);
    }
    $this->pdo->commit();

}



    public function find(int $id): Post
    {
        $query = $this->pdo->prepare('SELECT * FROM post WHERE id = :id');
        $query->execute(['id' =>$id]);
        $post = $query->setFetchMode(PDO::FETCH_CLASS, Post::class);
        /** @var  Category|false */
        //id mfemech
        $result = $query->fetch();

        if($result === false) {
            throw new NotFoundException('post',$id);
        }
        return $result;
    }


    public function findPaginated()
    {
        $paginatedQuery = new PaginatedQuery(
        // lister les articles
            "SELECT * FROM post ORDER BY created_at DESC",
            //recuper les nombres d'articles totale
            "SELECT COUNT(id) FROM {$this->table}",
          $this->pdo
      );
      $posts = $paginatedQuery->getItems(Post::class);
        // hydratePosts MIN Category
        (new CategoryTable($this->pdo))->hydratePosts($posts);

return [$posts, $paginatedQuery];
}

public function findPaginatedForCategory (int $categoryID)
{
    $paginatedQuery = new PaginatedQuery(
        "SELECT p.* 
FROM post {$this->table} p
JOIN post_category pc ON pc.post_id = p.id

WHERE pc.category_id = {$categoryID}
ORDER BY created_at DESC",
        "SELECT COUNT(category_id) FROM post_category WHERE category_id = {$categoryID}"
    );
    /** @var Post[] */
    $posts = $paginatedQuery->getItems(Post::class);
    // hydratePosts MIN CategoryTable
    (new CategoryTable($this->pdo))->hydratePosts($posts);
    return [$posts, $paginatedQuery];
}

}