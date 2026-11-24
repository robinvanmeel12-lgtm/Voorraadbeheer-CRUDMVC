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

    public function find(int $id): ?array
    {
        $statement = $this->db->prepare(
            'SELECT id, naam, smaak, prijs, voorraad FROM voorraad WHERE id = :id'
        );
        $statement->execute(['id' => $id]);
        $item = $statement->fetch();
        return $item === false ? null : $item;
    }

    public function update(int $id, array $values): bool
    {
        $statement = $this->db->prepare(
            'UPDATE voorraad
             SET naam = :naam, smaak = :smaak, prijs = :prijs, voorraad = :voorraad
             WHERE id = :id'
        );
        $statement->execute($values + ['id' => $id]);

        // MySQL telt een update met ongewijzigde waarden als nul wijzigingen.
        return $statement->rowCount() === 1 || $this->find($id) !== null;
    }
}
