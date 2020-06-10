<?php


namespace App;

use \PDO;

class PaginatedQuery
{
    private $query;
    private $queryCount;
    private $pdo;
    private $perPage;
    private $count;
    private $items;

    public function __construct(
        string $query,
        string $queryCount,

        ?\PDO $pdo = null,
        int $perPage = 12
    )
    {
     $this->query = $query;
     $this->queryCount = $queryCount;
     $this->pdo = $pdo?: Connection::getPDO();
     $this->perPage = $perPage;
    }

    public function getItems(string $classMapping): array
    {
        if ($this->items === null) {
            $currentPage = $this->getCurrentPage();
            $pages = $this->getPages();
            // compteur des resultats
            if ($currentPage > $pages) {
                throw new \Exception('cette page n\'existe pas');
            }
            $offset = $this->perPage * ($currentPage -1);
            $this->items = $this->pdo->query(
                $this->query .
                " LIMIT  {$this->perPage}  OFFSET $offset ")
                ->fetchAll(PDO::FETCH_CLASS,$classMapping);
        }
           return $this->items;
    }

    public function perviousLink(string $link): ?string {

        $currentPage = $this->getCurrentPage();
        // pour la page precedente si la page est 0 on ne peut pas retourner
        if($currentPage <=1) return null;
        if($currentPage > 2) $link .="?page=" . ($currentPage - 1);
        return  <<<HTML
              <a href="{$link}" class="btn btn-primary">&laquo; Page précedente</a>
HTML;
              
    }

    public function nextLink(string $link): ?string {

        $currentPage = $this->getCurrentPage();
        $pages = $this->getPages();

        // pour la page precedente si la page est 0 on ne peut pas retourner
        if($currentPage >= $pages) return null;
        $link .= "?page=" .($currentPage + 1);
        if($currentPage > 2) $link .="?page=" . ($currentPage - 1);
        return  <<<HTML
              <a href="{$link}" class="btn btn-primary ml-auto">&laquo; Page suivante</a>
HTML;

    }



    private function getCurrentPage(){

        return URL::getPositiveInt('page', 1);
    }

    private function getPages (): int {

     if($this->count === null) {
       $this->count = (int)$this->pdo
            ->query($this->queryCount)
            ->fetch(PDO::FETCH_NUM)[0];

          }
      return ceil($this->count / $this->perPage);






    }

}