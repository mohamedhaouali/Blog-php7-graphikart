<?php
namespace App;

use \PDO;

class Connection {

    public static function getPDO (): PDO {
        $host = 'localhost';
        $db ='tutoblog';
        $login ='root';
        $mdp='';
        try {
            return new PDO("mysql:host=$host;dbname=$db", $login, $mdp, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ] );
            //   print_r( $pdo->errorInfo());
        }
        catch(PDOException $e){
            echo 'erreur'.$e->getMessage();
        }

    }

}
