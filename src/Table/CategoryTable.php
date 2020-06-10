<?php


namespace App\Table;

use App\Model\Category;

use App\Model\Post;
use App\PaginatedQuery;
use App\Table\Exception\NotFoundException;
use \PDO;

final class CategoryTable extends Table
{
    // HERITER mil class Table
    protected $table ="category";
    protected $class = Category::class;

    /**
     * @param App/Model/Post[] $posts
     */

    public function hydratePosts(array $posts): void
    {
        $postsByID = [];
        foreach ($posts as $post) {
            $post->setCategories([]);
            $postsByID[$post->getID()] = $post;
        }

// array key la liste des cles dans un tableaux
        $categories = $this->pdo
            ->query('SELECT c.*, pc.post_id
                      FROM post_category pc
                      JOIN category c ON c.ID = pc.category_id
                      WHERE pc.post_id IN ( ' . implode(',', array_keys($postsByID)) . ')'
            )->fetchAll(PDO::FETCH_CLASS, $this->class);


// on parcout les categories
// on trouve l'article $posts correspondant a la ligne
// on ajoute la categorie a l'article

        foreach ($categories as $category) {
            $postsByID[$category->getPostID()]->addcategory($category);

        }
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


    // elle permet de recperer tous les enregistrements

    public function all (): array {
     // queryFetchAll methode jeya class Table
        return $this->queryAndFetchAll("SELECT * FROM {$this->table} ORDER BY id DESC");

    }

    public function list(): array {
        // all is methode
        $categories = $this->queryAndFetchAll("SELECT * FROM {$this->table} ORDER BY name ASC");
        // results tableaux vide
        $results = [];
        foreach ($categories as $category){
            $results[$category->getID()] = $category->getName();

        }
        return $results;
    }


}