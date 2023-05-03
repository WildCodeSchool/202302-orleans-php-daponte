<?php

namespace App\Model;

use PDO;
use App\Model\AbstractManager;

class TireManager extends AbstractManager
{
    public const TABLE = 'tire';

    public function findTire(array $search = []): array
    {
        $query = 'SELECT t.*, c.name category FROM ' . self::TABLE . ' t 
        JOIN ' . CategoryManager::TABLE . ' c ON c.id=t.category_id';

        $queryParts = [];
        if (!empty($search['name'])) {
            $queryParts[] = ' t.name LIKE :search';
        }
        if (!empty($search['category'])) {
            $queryParts[] = ' t.category_id = :category';
        }

        if (!empty($queryParts)) {
            $query .= ' WHERE ' . implode(' AND ', $queryParts);
        }

        $statement = $this->pdo->prepare($query);
        if (!empty($search['name'])) {
            $statement->bindValue(':search', '%' . $search['name'] . '%', PDO::PARAM_STR);
        }
        if (!empty($search['category'])) {
            $statement->bindValue(':category', $search['category'], PDO::PARAM_INT);
        }

        $statement->execute();
        return $statement->fetchAll();
    }

    public function insert(array $tire): void
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE . " (`name`, `price`, `description`, `category_id`, `image`)
             VALUES (:name, :price, :description, :category_id, :image)"
        );

        $statement->bindValue('name', $tire['name'], PDO::PARAM_STR);
        $statement->bindValue('price', $tire['price']);
        $statement->bindValue('description', $tire['description'], PDO::PARAM_STR);
        $statement->bindValue('category_id', $tire['category'], PDO::PARAM_INT);
        $statement->bindValue('image', $tire['image'], PDO::PARAM_STR);

        $statement->execute();
    }

    public function update(array $tire): void
    {
        $statement = $this->pdo->prepare(
            "UPDATE " . self::TABLE . " 
            SET `name`=:name, `price`=:price, `description`= :description,
            `category_id` = :category_id, `image` = :image
            WHERE id=:id"
        );

        $statement->bindValue('name', $tire['name'], PDO::PARAM_STR);
        $statement->bindValue('price', $tire['price']);
        $statement->bindValue('description', $tire['description'], PDO::PARAM_STR);
        $statement->bindValue('id', $tire['id'], PDO::PARAM_STR);
        $statement->bindValue('category_id', $tire['category'], PDO::PARAM_INT);
        $statement->bindValue('image', $tire['image'], PDO::PARAM_STR);

        $statement->execute();
    }
}
