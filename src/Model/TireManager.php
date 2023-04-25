<?php

namespace App\Model;

use PDO;
use App\Model\AbstractManager;

class TireManager extends AbstractManager
{
    public const TABLE = 'tire';

    public function selectTires(): array
    {
        $query = 'SELECT t.*, c.name category FROM ' . self::TABLE . ' t 
        JOIN ' . CategoryManager::TABLE . ' c ON c.id=t.category_id';

        return $this->pdo->query($query)->fetchAll();
    }

    public function insert(array $tire): void
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE . " (`name`, `price`, `description`, `category_id`)
             VALUES (:name, :price, :description, :category_id)"
        );

        $statement->bindValue('name', $tire['name'], PDO::PARAM_STR);
        $statement->bindValue('price', $tire['price']);
        $statement->bindValue('description', $tire['description'], PDO::PARAM_STR);
        $statement->bindValue('category_id', $tire['category'], PDO::PARAM_INT);

        $statement->execute();
    }

    public function update(array $tire): void
    {
        $statement = $this->pdo->prepare(
            "UPDATE " . self::TABLE . " 
            SET `name`=:name, `price`=:price, `description`= :description, `category_id` = :category_id
            WHERE id=:id"
        );

        $statement->bindValue('name', $tire['name'], PDO::PARAM_STR);
        $statement->bindValue('price', $tire['price']);
        $statement->bindValue('description', $tire['description'], PDO::PARAM_STR);
        $statement->bindValue('id', $tire['id'], PDO::PARAM_STR);
        $statement->bindValue('category_id', $tire['category'], PDO::PARAM_INT);

        $statement->execute();
    }
}
