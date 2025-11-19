<?php

namespace Repository;



use Entity\Marque;
use PDO;

class MarquesRepository
{
    private PDO $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    /**
     * Récupère toutes les marques
     */
    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT id_marque, nom_marque FROM marque ORDER BY nom_marque");
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $marques = [];
        foreach ($result as $row) {
            $marque = new Marque($row['nom_marque']);
            $marque->setIdMarque($row['id_marque']);
            $marques[] = $marque;
        }

        return $marques;
    }

    /**
     * Récupère une marque par son ID
     */
    public function find(int $id): ?Marque
    {
        $stmt = $this->conn->prepare("SELECT id_marque, nom_marque FROM marques WHERE id_marque = ?");
        $stmt->execute([$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) return null;

        $marque = new Marque($row['nom_marque']);
        $marque->setIdMarque($row['id_marque']);
        return $marque;
    }

    /**
     * Ajoute une nouvelle marque
     */
    public function insert(Marque $marque): bool
    {
        $stmt = $this->db->prepare("INSERT INTO marque (nom_marque) VALUES (?)");
        $ok = $stmt->execute([$marque->getNomMarque()]);

        if ($ok) {
            $marque->setIdMarque((int)$this->db->lastInsertId());
        }

        return $ok;
    }

    /**
     * Met à jour une marque existante
     */
    public function update(Marque $marque): bool
    {
        $stmt = $this->db->prepare("UPDATE marque SET nom_marque = ? WHERE id_marque = ?");
        return $stmt->execute([
            $marque->getNomMarque(),
            $marque->getIdMarque()
        ]);
    }

    /**
     * Supprime une marque
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM marque WHERE id_marque = ?");
        return $stmt->execute([$id]);
    }
}
