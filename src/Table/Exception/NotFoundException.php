<?php
namespace App\Table\Exception;



class NotFoundException extends \Exception
{
    public function __construct(string $table, int $id)
    {
        $this->getMessage = "Aucun enregistrement ne correspond a l'id #$id dans la table '$table'";
    }

}