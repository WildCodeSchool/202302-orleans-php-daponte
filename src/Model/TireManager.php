<?php

namespace App\Model;

use PDO;
use App\Model\AbstractManager;

class TireManager extends AbstractManager
{
    public const TABLE = 'tire';

    public function insert(array $tire): void
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE . " (`name`, `price`, `description`)
             VALUES (:name, :price, :description)"
        );

        $statement->bindValue('name', $tire['name'], PDO::PARAM_STR);
        $statement->bindValue('price', $tire['price']);
        $statement->bindValue('description', $tire['description'], PDO::PARAM_STR);

        $statement->execute();
    }
}
