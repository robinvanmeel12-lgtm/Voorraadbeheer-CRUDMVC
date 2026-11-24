<?php

declare(strict_types=1);

final class Item
{
    public function __construct(private PDO $db)
    {
    }

    public function all(string $search): array
    {
        if ($search === '') {
            return $this->db->query(
                'SELECT id, naam, smaak, prijs, voorraad FROM voorraad ORDER BY id DESC'
            )->fetchAll();
        }

        $statement = $this->db->prepare(
            "SELECT id, naam, smaak, prijs, voorraad FROM voorraad
             WHERE naam LIKE :naam ESCAPE '!' ORDER BY id DESC"
        );
        $literal = strtr($search, ['!' => '!!', '%' => '!%', '_' => '!_']);
        $statement->execute(['naam' => '%' . $literal . '%']);
        return $statement->fetchAll();
    }

    public function create(array $values): void
    {
        $statement = $this->db->prepare(
            'INSERT INTO voorraad (naam, smaak, prijs, voorraad)
             VALUES (:naam, :smaak, :prijs, :voorraad)'
        );
        $statement->execute($values);
    }
}
