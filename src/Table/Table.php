<?php

namespace App\Table;

use App\Models\Post;
use App\Table\Exception\NotFoundException;
use PDO;

abstract class Table {

    protected $pdo;
    protected $table = null;
    protected $class = null;

    public function __construct(PDO $pdo)
    {
        if($this->table === null){
            throw new \Exception("La class " . get_class($this) . "  n'a pas de propriété \$table");
        }
        if($this->class === null){
            throw new \Exception("La class " . get_class($this) . "  n'a pas de propriété \$class");
        }
        $this->pdo = $pdo;
    }

    public function find(int $id)
    {
        $query = $this->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $result = $query->fetch();
        if ($result === false){
            throw new NotFoundException($this->table, $id);
        }
        return $result;
    }

    public function delete (int $id): void
    {
        $query = $this->pdo->prepare("DELETE FROM " . $this->table . " WHERE id = :id");
        $ok = $query->execute(['id' => $id]);
        if ($ok === false){
            throw new \Exception("Impossible de supprimer l'enregistrement $id dans le table {$this->table}");
        }
    }

}