<?php

namespace Repository;

use Config\Database;
use Entity\Ville;
use PDO;
use PDOException;

class VillesRepository
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getConnection();
    }

    /**
     * 🔹 Récupérer toutes les villes
     */
    public function findAll(): array
    {
        try {
            $stmt = $this->conn->query("SELECT * FROM villes ORDER BY nom_ville ASC");
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $villes = [];
            foreach ($rows as $row) {
                $ville = new Ville($row['nom_ville']);
                $ville->setIdVille((int)$row['id_ville']);
                $villes[] = $ville;
            }

            return $villes;

        } catch (PDOException $e) {
            error_log("Erreur findAll Villes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * 🔹 Récupérer une ville par son ID
     */
    public function findById(int $id): ?Ville
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM villes WHERE id_ville = :id");
            $stmt->execute([':id' => $id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) {
                $ville = new Ville($data['nom_ville']);
                $ville->setIdVille((int)$data['id_ville']);
                return $ville;
            }

            return null;
        } catch (PDOException $e) {
            error_log("Erreur findById Villes: " . $e->getMessage());
            return null;
        }
    }

    /**
     * 🔹 Créer une nouvelle ville
     */
    public function create(Ville $ville): bool
    {
        try {
            $ville->validate(); // Valide avant insertion

            $stmt = $this->conn->prepare("
                INSERT INTO villes (nom_ville) VALUES (:nom_ville)
            ");
            $success = $stmt->execute([
                ':nom_ville' => $ville->getNomVille(),
            ]);

            if ($success) {
                $ville->setIdVille((int)$this->conn->lastInsertId());
            }

            return $success;
        } catch (PDOException $e) {
            error_log("Erreur create Ville: " . $e->getMessage());
            return false;
        }
    }

    /**
     * 🔹 Supprimer une ville
     */
    public function delete(int $id): bool
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM villes WHERE id_ville = :id");
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Erreur delete Ville: " . $e->getMessage());
            return false;
        }
    }

    public function getNomVille(int $id): ?string {
        $stmt = $this->conn->prepare("SELECT nom_ville FROM villes WHERE id_ville = :id");
        $stmt->execute([':id_ville' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['nom_ville'] : null;
    }

    public function getAllVillesOrdered(): array
    {
        $stmt = $this->conn->query("SELECT id_ville, nom_ville FROM villes ORDER BY nom_ville ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
