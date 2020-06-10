<?php


namespace App\Table;


use \PDO;

abstract class Table
{
    protected $pdo;

    protected $table = null;
    protected $class = null;

    public function __construct(PDO $pdo)
    {
        if ($this->table === null) {
            throw new \Exception("la class " . get_class($this) . " n'a pas du propriete \$table");
        }
        if ($this->class === null) {
            throw new \Exception("la class " . get_class($this) . " n'a pas du propriete \$class");
        }
        $this->pdo = $pdo;
    }

    public function find(int $id)
    {
        $query = $this->pdo->prepare('SELECT * FROM ' . $this->table . ' WHERE id = :id');
        $query->execute(['id' => $id]);
        $post = $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        /** @var  Category|false */
        //id mfemech
        $result = $query->fetch();

        if ($result === false) {
            throw new NotFoundException($this->table, $id);
        }
        return $result;
    }
    /*
     * VERIFIER si une valeur existe dans la table
     * @param string $field champs a rechercher
     * @param mixed $value valeur associe au champs
     *table howa PostTable
     */

    public function exists(string $field, $value, ?int $except = null): bool
    {
        // true si l'enregistrement existe
        // except que enregistrement soit utilise
        $sql ="SELECT COUNT(id) FROM {$this->table} WHERE $field = ?";
        $params = ([$value]);
        if ($except !== null){
            $sql .= " AND id != ?";
            $params[] = $except;
        }

        $query = $this->pdo->prepare($sql);
        $query->execute($params);
        // FETCH_NUM tableau numérique
        return (int)$query->fetch(PDO::FETCH_NUM)[0] > 0;

    }
   // elle permet de recperer tous les enregistrements

    public function all (): array {
     $sql = "SELECT * FROM {$this->table}";
     return $this->pdo->query($sql, PDO::FETCH_CLASS, $this->class)->fetchAll();

    }

    public function delete  (int $id): void {
        //{$this->table} pour table post
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $ok = $query->execute([$id]);
        if ($ok === false) {
            throw new \Exception("Impossible de supprimer l'enrgistrement $id dans la table {$this->table}");
        }
    }

    public function create (array $data): int{
        $sqlFields = [];
        // key valeur du champs value valeur associe
         foreach ($data as $key => $value) {
             $sqlFields[] = "$key = :$key";

         }


        //{$this->table} pour table post
        // implode(',',$sqlFields) BECH remplace name et slug
        $query = $this->pdo->prepare("INSERT INTO  {$this->table} SET " . implode(',',$sqlFields));
        $ok = $query->execute($data);

        if ($ok === false) {
            throw new \Exception("Impossible de creer l'enrgistrement  dans la table {$this->table}");
        }
        // dernier enregistrement qui a ete fait
          return (int) $this->pdo->lastInsertId();
    }
   // array data tableaux des donnes ,$data entier
    public function update (array $data,int $id){
        $sqlFields = [];
        // key valeur du champs value valeur associe
        foreach ($data as $key => $value) {
            $sqlFields[] = "$key = :$key";

        }

        //{$this->table} pour table post
        // implode(',',$sqlFields) BECH remplace name et slug
        $query = $this->pdo->prepare("UPDATE {$this->table} SET " . implode(',',$sqlFields) . " WHERE id = :id");
        $ok = $query->execute(array_merge($data,['id' =>$id]));

        if ($ok === false) {
            throw new \Exception("Impossible de modifier l'enrgistrement  dans la table {$this->table}");
        }

    }


    public function queryAndFetchAll(string $sql): array
    {
        return $this->pdo->query($sql, PDO::FETCH_CLASS, $this->class)->fetchAll();
    }

}